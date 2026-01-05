<?php
// ========================================
// FILE: mod/user/scan_booth.php (Final Revisi + Integrasi Point UI)
// ========================================
require_once "../koneksi.php";
session_start();

// 🔒 Pastikan user login
if (!isset($_SESSION['iduser'])) {
    echo "
    <script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = '../login';
    </script>";
    exit;
}

$iduser = $_SESSION['iduser'];
$code = $_GET['code'] ?? null;

// ⚠️ QR tidak ditemukan
if (!$code) {
    echo "
    <html><head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head><body>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'QR Tidak Valid ⚠️',
            text: 'Kode booth tidak ditemukan atau QR tidak sah.',
            background: 'rgba(0,0,0,0.92)',
            color: '#fff',
            timer: 2500,
            showConfirmButton: false
        }).then(() => window.location.href='../user/index.php?tab=scan');
        </script>
    </body></html>";
    exit;
}

// 🔍 Ambil data user
$user_q = mysqli_query($conn2, "SELECT nama FROM super_user WHERE iduser='$iduser' LIMIT 1");
if (mysqli_num_rows($user_q) === 0) {
    echo "
    <script>
        alert('Data peserta tidak ditemukan.');
        window.location.href='../user/index.php?tab=scan';
    </script>";
    exit;
}
$user = mysqli_fetch_assoc($user_q);
$nama_peserta = $user['nama'];

// 🔍 Ambil data booth
$q = mysqli_query($conn2, "SELECT * FROM booth WHERE qr_code='$code' LIMIT 1");
if (mysqli_num_rows($q) === 0) {
    echo "
    <html><head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head><body>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Booth Tidak Dikenali ❌',
            text: 'Kode QR ini tidak terdaftar dalam sistem booth Open House.',
            background: 'rgba(0,0,0,0.92)',
            color: '#fff',
            timer: 2500,
            showConfirmButton: false
        }).then(() => window.location.href='../user/index.php?tab=scan');
        </script>
    </body></html>";
    exit;
}

$booth = mysqli_fetch_assoc($q);
$idbooth = $booth['idbooth'];
$nama_booth = $booth['nama_booth'];
$kategori = $booth['kategori'] ?? '-';
$lantai = $booth['lantai'] ?? '-';

// 🔁 Cek apakah user sudah scan booth ini hari ini
$cek = mysqli_query($conn2, "
    SELECT * FROM booth_kunjungan 
    WHERE iduser='$iduser' 
    AND idbooth='$idbooth' 
    AND DATE(waktu_kunjungan)=CURDATE()
");

if (mysqli_num_rows($cek) === 0) {
    // ✅ Belum pernah, catat kunjungan baru
    $insert = mysqli_query($conn2, "
        INSERT INTO booth_kunjungan (iduser, nama_peserta, idbooth, nama_booth, kategori, lantai)
        VALUES ('$iduser', '$nama_peserta', '$idbooth', '$nama_booth', '$kategori', '$lantai')
    ");

    if ($insert) {
        // 🧮 Tambah poin user (dummy +1)
        mysqli_query($conn2, "
            INSERT INTO user_points (iduser, total_point)
            VALUES ('$iduser', 1)
            ON DUPLICATE KEY UPDATE total_point = total_point + 1
        ");

        echo "
        <html><head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head><body>
            <script>
            Swal.fire({
                icon: 'success',
                title: 'Kunjungan Tercatat 🎉',
                html: '<b>$nama_peserta</b><br>berhasil mengunjungi booth:<br><b style=\"color:#00b2ff;\">$nama_booth</b><br><br><span style=\"color:#00ff8f;font-weight:bold;\">+1 Poin!</span>',
                background: 'rgba(0,0,0,0.92)',
                color: '#e6f7ff',
                showConfirmButton: false,
                timer: 2600,
                backdrop: `
                    rgba(0,0,0,0.85)
                    url('../images/confetti.gif')
                    center top
                    no-repeat
                `,
                customClass: { popup: 'rounded-xl shadow-2xl text-lg' }
            }).then(() => window.location.href='../user/index.php?tab=scan&scanned=1');
            </script>
        </body></html>";
    } else {
        echo "
        <html><head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head><body>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan ❌',
                text: 'Terjadi kesalahan saat mencatat kunjungan. Silakan coba lagi.',
                background: 'rgba(0,0,0,0.92)',
                color: '#fff',
                timer: 2500,
                showConfirmButton: false
            }).then(() => window.location.href='../user/index.php?tab=scan');
            </script>
        </body></html>";
    }

} else {
    // 🏷️ Sudah pernah scan hari ini
    echo "
    <html><head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head><body>
        <script>
        Swal.fire({
            icon: 'info',
            title: 'Sudah Dikunjungi 🏷️',
            html: 'Kamu sudah mengunjungi <b>$nama_booth</b> hari ini.<br><small>(Hanya 1x per booth per hari)</small>',
            background: 'rgba(0,0,0,0.92)',
            color: '#e6f7ff',
            showConfirmButton: false,
            timer: 2500,
            customClass: { popup: 'rounded-xl shadow-xl text-md' }
        }).then(() => window.location.href='../user/index.php?tab=scan');
        </script>
    </body></html>";
}
?>
