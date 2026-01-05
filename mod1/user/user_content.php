<!-- mod/user_content.php -->

<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once "../koneksi.php";

$iduser = $_SESSION['iduser'] ?? 0;
$type = $_GET['type'] ?? '';

function safeQuery($conn, $query): bool|mysqli_result
{
    try {
        return mysqli_query($conn, $query);
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

switch ($type) {

    // =================== QR CODE ===================
    case 'qr':
        $sql = "SELECT iduser, nama, email FROM super_user WHERE iduser='$iduser'";
        $res = safeQuery($conn2, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $data_qr = json_encode([
                'id' => $row['iduser'],
                'nama' => $row['nama'],
                'email' => $row['email']
            ]);
            $filename = $row['iduser'] . "_qr.png";
            $path = "../phpqrcode/" . $filename;
            if (!file_exists($path)) {
                include "../phpqrcode/qrlib.php";
                QRcode::png($data_qr, $path, QR_ECLEVEL_H, 4);
            }
            echo "<center>
                    <img src='$path' style='width:180px;margin-bottom:10px;'>
                    <p><b>{$row['nama']}</b><br><small>ID: {$row['iduser']}</small></p>
                 </center>";
        } else {
            echo "<p>QR Code tidak ditemukan.</p>";
        }
        break;


    // =================== SCAN BOOTH ===================
    case 'scan':
        $data = safeQuery($conn2, "
            SELECT nama_booth 
            FROM booth_kunjungan 
            WHERE iduser='$iduser' 
            ORDER BY waktu_kunjungan DESC
        ");
        $jumlah = $data ? mysqli_num_rows($data) : 0;

        echo "<div class='card'>
            <h3><i class='fas fa-qrcode'></i> Scan Booth</h3>
            <p>Lakukan scan QR di setiap booth untuk mendapatkan hadiah menarik!</p>
            <a href='scanner.php' class='cta-button'>Scan Sekarang</a>
            <hr style='margin:15px 0;opacity:0.2;'>
            <p style='font-size:18px;'>
                <b>Jumlah Booth Dikunjungi:</b>
                <span style='color:#00b2ff;font-weight:bold;'>$jumlah</span>
            </p>";

        if ($jumlah > 0) {
            echo "<ul style='margin-top:10px;list-style-type:disc;padding-left:20px;'>";
            while ($r = mysqli_fetch_assoc($data)) {
                $nama_booth = htmlspecialchars($r['nama_booth']);
                echo "<li>$nama_booth <span style='color:#00ff8f;font-weight:bold;'>+1</span></li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color:#ccc;'>Kamu belum mengunjungi booth manapun.</p>";
        }

        echo "</div>";
        break;


    // =================== KEGIATAN SAYA ===================
    case 'kegiatan':
        $check = safeQuery($conn2, "SHOW TABLES LIKE 'kegiatan_peserta'");
        echo "<div class='card'><h3><i class='fas fa-calendar-check'></i> Kegiatan Saya</h3>";
        if (!$check || mysqli_num_rows($check) === 0) {
            echo "<p style='color:#aaa;'>Tabel <b>kegiatan_peserta</b> belum dibuat di database.</p>";
        } else {
            $kegiatan = safeQuery($conn2, "SELECT * FROM kegiatan_peserta WHERE iduser='$iduser'");
            if ($kegiatan && mysqli_num_rows($kegiatan) > 0) {
                echo "<ul>";
                while ($k = mysqli_fetch_assoc($kegiatan)) {
                    echo "<li><b>" . htmlspecialchars($k['nama_kegiatan']) . "</b><br><small>" . htmlspecialchars($k['waktu_kegiatan']) . "</small></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Belum ada kegiatan yang kamu ikuti.</p>";
            }
        }
        echo "</div>";
        break;


    // =================== PRESENSI SAYA ===================
    case 'presensi':
        $check = safeQuery($conn2, "SHOW TABLES LIKE 'presensi_peserta'");
        echo "<div class='card'><h3><i class='fas fa-user-check'></i> Presensi Saya</h3>";

        if (!$check || mysqli_num_rows($check) === 0) {
            echo "<p style='color:#aaa;'>Tabel <b>presensi_peserta</b> belum dibuat di database.</p>";
        } else {
            $presensi = safeQuery($conn2, "
                SELECT 
                    p.id_presensi, 
                    p.iduser, 
                    p.id_kegiatan,
                    p.status,
                    p.waktu_presensi,
                    k.nama_kegiatan
                FROM presensi_peserta p
                LEFT JOIN kegiatan_peserta k ON p.id_kegiatan = k.id_kegiatan
                WHERE p.iduser = '$iduser'
                ORDER BY p.waktu_presensi DESC
            ");

            if ($presensi && mysqli_num_rows($presensi) > 0) {
                echo "<ul class='presensi-list'>";
                while ($p = mysqli_fetch_assoc($presensi)) {
                    $nama = htmlspecialchars($p['nama_kegiatan'] ?? 'Tidak diketahui');
                    $status = htmlspecialchars($p['status'] ?? 'Belum Hadir');
                    $waktu = htmlspecialchars($p['waktu_presensi'] ?? '-');
                    $status_badge = $status === 'Hadir'
                        ? "<span class='badge' style='background:#4CAF50;color:#fff;padding:4px 8px;border-radius:6px;'>Hadir</span>"
                        : "<span class='badge' style='background:#f44336;color:#fff;padding:4px 8px;border-radius:6px;'>Belum Hadir</span>";

                    echo "<li style='margin-bottom:10px;'>
                        <b>$nama</b><br>
                        <small>Waktu: $waktu</small><br>
                        Status: $status_badge
                    </li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Belum ada data presensi.</p>";
            }
        }

        echo "</div>";
        break;

    // =================== POINT & REWARD ===================
    case 'reward':
        //config file di sesuaikan dengan js yang udah modular
        $configFile = __DIR__ . '/../admin/super/config/reward_config.php';
        if (file_exists($configFile)) {
            $rewardConfig = include $configFile;
            $facultyTarget = $rewardConfig['facultyTarget'] ?? 7;
            $otherTarget = $rewardConfig['otherTarget'] ?? 2;
        } else {
            $facultyTarget = 7;
            $otherTarget = 2;
        }

        // Hitung booth fakultas
        $facultyQuery = safeQuery($conn2, "
        SELECT COUNT(DISTINCT b.idbooth) AS total
        FROM booth_kunjungan k
        LEFT JOIN booth b ON k.idbooth = b.idbooth
        WHERE k.iduser='$iduser' AND b.kategori='Booth Fakultas'
    ");
        $facultyVisit = ($facultyQuery && mysqli_num_rows($facultyQuery) > 0)
            ? (int) mysqli_fetch_assoc($facultyQuery)['total'] : 0;

        // Hitung booth lainnya (jika dibutuhkan)
        $otherVisit = 0;
        if ($otherTarget > 0) {
            $otherQuery = safeQuery($conn2, "
            SELECT COUNT(DISTINCT b.idbooth) AS total
            FROM booth_kunjungan k
            LEFT JOIN booth b ON k.idbooth = b.idbooth
            WHERE k.iduser='$iduser' AND (b.kategori IS NULL OR b.kategori != 'Booth Fakultas')
        ");
            $otherVisit = ($otherQuery && mysqli_num_rows($otherQuery) > 0)
                ? (int) mysqli_fetch_assoc($otherQuery)['total'] : 0;
        }

        // Kelayakan & status klaim
        $isEligible = ($facultyVisit >= $facultyTarget) && ($otherVisit >= $otherTarget);
        $claimCheck = safeQuery($conn2, "SELECT id FROM reward_claim WHERE iduser='$iduser' LIMIT 1");
        $isClaimed = $claimCheck && mysqli_num_rows($claimCheck) > 0;

        // Deskripsi
        $descText = ($otherTarget > 0)
            ? "Kunjungi $facultyTarget booth fakultas dan $otherTarget booth pilihan untuk mendapatkan hadiah eksklusif."
            : "Kunjungi $facultyTarget booth fakultas untuk mendapatkan hadiah eksklusif.";

        echo "
    <div class='card reward-card'>
        <h3><i class='fas fa-gift'></i> Point & Reward</h3>
        <p class='subtitle'>
            $descText
            <br>
            <span class='notice'><i class='fas fa-exclamation-circle'></i> Hadiah terbatas, cepat klaim sebelum kehabisan.</span>
        </p>

        <hr style='margin:15px 0;opacity:0.2;'>
        <div class='progress-section'>
            <div class='progress-label'><i class='fas fa-university'></i> Booth Fakultas</div>
            <div class='progress-bar'><div class='progress-fill' style='width:" . min(($facultyVisit / $facultyTarget) * 100, 100) . "%'></div></div>
            <div class='progress-text'>$facultyVisit / $facultyTarget</div>
        </div>";

        if ($otherTarget > 0) {
            echo "
        <div class='progress-section'>
            <div class='progress-label'><i class='fas fa-store'></i> Booth Lainnya</div>
            <div class='progress-bar'><div class='progress-fill' style='width:" . min(($otherVisit / $otherTarget) * 100, 100) . "%'></div></div>
            <div class='progress-text'>$otherVisit / $otherTarget</div>
        </div>";
        }

        echo "<hr style='margin:15px 0;opacity:0.2;'>
        <div style='text-align:center;margin-top:10px;'>";

        if ($isClaimed) {
            // Sudah klaim
            echo "
        <div class='reward-status success'>
            <i class='fas fa-check-circle'></i> Kamu sudah klaim hadiah!
        </div>
        <button class='cta-button' disabled style='background:rgba(0,255,100,0.2);cursor:not-allowed;'>
            <i class='fas fa-gift'></i> Sudah Klaim Hadiah
        </button>";
        } elseif ($isEligible) {
            // Siap klaim
            echo "
        <div class='reward-status success'>
            <i class='fas fa-check-circle'></i> Selamat! Kamu bisa klaim hadiah.
        </div>
        <button class='cta-button claimBtn'>
            <i class='fas fa-qrcode'></i> QR Klaim Hadiah
        </button>
        <p style='color:#aaa;font-size:13px;margin-top:6px;'>Tunjukkan QR ini ke petugas untuk menukar hadiahmu.</p>";
        } else {
            // Belum memenuhi
            echo "
        <div class='reward-status wait'>
            <i class='fas fa-hourglass-half'></i> Lengkapi kunjungan untuk membuka QR hadiah.
        </div>
        <button class='cta-button' disabled style='opacity:0.5;cursor:not-allowed;'>
            <i class='fas fa-lock'></i> QR Belum Tersedia
        </button>";
        }

        echo "
        </div>
    </div>

    <style>
    .reward-card {
        background: #111;
        border-radius: 16px;
        padding: 25px;
        color: #fff;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.25);
        max-width: 450px;
        margin: 25px auto;
    }
    .reward-card h3 {
        color: #ff3333;
        font-size: 1.6rem;
        margin-bottom: 8px;
        text-align: center;
    }
    .reward-card .subtitle {
        text-align: center;
        color: #ccc;
        font-size: 0.95rem;
        line-height: 1.4;
    }
    .reward-card .notice {
        display: block;
        color: #ff6666;
        font-weight: 600;
        margin-top: 5px;
    }
    .progress-section {
        margin-top: 15px;
    }
    .progress-label {
        font-weight: 600;
        color: #ffb3b3;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .progress-bar {
        height: 10px;
        background: #333;
        border-radius: 8px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ff3333, #ff6666);
        border-radius: 8px;
        transition: width 0.6s ease-in-out;
    }
    .progress-text {
        margin-top: 6px;
        font-weight: 600;
        color: #ffb3b3;
        font-size: 0.95rem;
        text-align: right;
    }
    .reward-status {
        padding: 10px;
        border-radius: 10px;
        margin-top: 15px;
        font-weight: 600;
        text-align: center;
    }
    .reward-status.success {
        background: rgba(0,255,100,0.1);
        color: #00ff8f;
    }
    .reward-status.wait {
        background: rgba(255,255,255,0.08);
        color: #ccc;
    }
    .cta-button {
        margin-top: 15px;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, #ff3333, #cc0000);
        transition: all 0.3s;
    }
    .cta-button:hover:not([disabled]) {
        background: linear-gradient(135deg, #ff5555, #ff0000);
    }
    </style>

    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.claimBtn');
        if (!btn) return;

        try {
            const res = await fetch('generate_qr_claim.php');
            const html = await res.text();

            Swal.fire({
                title: 'QR Klaim Hadiah',
                html: html,
                width: 480,
                background: 'rgba(0,0,0,0.95)',
                color: '#fff',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                customClass: { popup: 'swal2-qr-popup' },
                didOpen: () => {
                    const img = document.querySelector('.swal2-qr-popup img');
                    if (img) {
                        img.style.maxWidth = '220px';
                        img.style.border = '3px solid #ff3333';
                        img.style.borderRadius = '10px';
                        img.style.boxShadow = '0 0 20px rgba(255,0,0,0.3)';
                    }
                }
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat QR',
                text: 'Terjadi kesalahan saat memuat QR Klaim.',
                background: 'rgba(0,0,0,0.9)',
                color: '#fff'
            });
        }
    });
    </script>";
        break;


    default:
        echo "<p>Tidak ada konten ditemukan.</p>";
        break;
}
?>