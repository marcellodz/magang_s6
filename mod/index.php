<?php
// ==============================
// FILE: mod/index.php
// ==============================

// Pastikan session hanya dipanggil sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
            alert('Silahkan login terlebih dahulu.');
            window.location.href = 'https://openhouse.smbbtelkom.ac.id/login';
          </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House Telkom University</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="templatemo-electric-xtra.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/survey_func.js"></script>
    <script src="script.js" defer></script>

    <style>
        .qr-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            display: inline-block;
            text-align: center;
        }
        .user-icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.8em;
            color: #fff;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #111;
            min-width: 160px;
            border-radius: 6px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
            z-index: 99;
        }
        .dropdown-content a {
            color: white;
            padding: 10px 14px;
            display: block;
            text-decoration: none;
            border-bottom: 1px solid #222;
        }
        .dropdown-content a:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="grid-bg"></div>
    <div class="gradient-overlay"></div>
    <div class="scanlines"></div>
    <div class="shapes-container">
        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-square"></div>
    </div>
    <div id="particles"></div>

    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo-link">
                <img src="images/asset-telu.png" alt="Logo Telkom University" class="logo-svg">
                <span class="logo-text">OPEN HOUSE TELKOM UNIVERSITY</span>
            </a>

            <div class="user-nav">
                <div class="user-menu-dropdown">
                    <button class="user-icon-btn" id="userIconBtn">
                        <i class="fas fa-user-circle"></i>
                    </button>

                    <div class="dropdown-content" id="userDropdown">
                        <a href="profile">Profil Saya</a>
                        <a href="logout" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userIconBtn');
            const dropdown = document.getElementById('userDropdown');
            btn.addEventListener('click', () => {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            window.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content" style="margin-top: 80px;">
            <div class="about-text">
                <h2>Selamat Datang,<br><?php echo htmlspecialchars($_SESSION['nama']); ?>!</h2>
                <p>
                    Ini adalah aplikasi yang akan kamu gunakan selama kegiatan Open House Telkom University berlangsung.
                    Hal-hal yang bisa kamu lakukan:
                </p>
                <ul class="feature-list">
                    <li><b>Mengisi kehadiran peserta</b> Open House dengan cara <b>menunjukkan QR Code</b> yang tertera pada Dashboard.</li>
                    <li><b>Mengisi keikutsertaan booth</b> dengan <b>scan QR Code booth</b> untuk mendapatkan hadiah menarik.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <h2 class="section-title">Core Features</h2>
        <div class="features-container">

            <!-- === QR CODE PRESENSIKU === -->
            <div class="feature-content">
                <div class="content-panel">
                    <center>
                        <h3>QR Code Presensiku</h3>
                        <p>Perlihatkan QR Code ini kepada petugas Open House Telkom University untuk menandai kehadiranmu.</p>
                    </center>
                    <br><hr style="color:#282828"><br><br>

                    <?php
                    require_once "koneksi.php";
                    $iduser = $_SESSION['iduser'];

                    $sql = "SELECT iduser, nama, email, kelas, jenjang_studi FROM super_user WHERE iduser=$iduser";
                    $result = $conn2->query($sql);
                    $qr_directory = "phpqrcode/";

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $data_user = [
                                'id' => $row['iduser'],
                                'nama' => $row['nama'],
                                'email' => $row['email']
                            ];
                            $data_qr = json_encode($data_user);
                            $filename = $row['iduser'] . "_qr.png";
                            $filepath = $qr_directory . $filename;

                            if (!file_exists($filepath)) {
                                include "phpqrcode/qrlib.php";
                                QRcode::png($data_qr, $filepath, QR_ECLEVEL_H, 4);
                            }

                            echo "<center>
                                    <img src='$filepath' alt='QR Code untuk " . htmlspecialchars($row['nama']) . "'>
                                    <h3>" . htmlspecialchars($row['nama']) . "</h3>
                                    <p>ID: " . htmlspecialchars($row['iduser']) . "</p>
                                  </center>";
                        }
                    } else {
                        echo "<p>Data pengguna tidak ditemukan.</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- === SCAN BOOTH === -->
            <div class="feature-content">
                <div class="content-panel">
                    <center>
                        <h3>Scan Booth</h3>
                        <p>Lakukan scan QR Code pada setiap booth yang dikunjungi untuk mendapatkan hadiah menarik!</p>
                        <p><a href="scanner" class="cta-button cta-primary">Scan Disini!</a></p>
                    </center>
                    <br><hr style="color:#282828"><br><br>

                    <?php
                    $iduser = $_SESSION['iduser'];
                    require_once "koneksi.php";
                    $kunjungan = mysqli_query($conn2, "SELECT * FROM booth_visitor WHERE iduser='$iduser'");
                    $jumlah_kunjungan = mysqli_num_rows($kunjungan);
                    ?>

                    <table>
                        <tr>
                            <td><p style="font-size:25px; padding-right:20px;"><b>Jumlah Kunjungan</b></p></td>
                            <td><p class="cta-button cta-secondary"><?php echo $jumlah_kunjungan . " booth"; ?></p></td>
                        </tr>
                    </table>

                    <?php
                    if ($jumlah_kunjungan > 0) {
                        echo "<p>Booth yang sudah kamu kunjungi:</p><ul class='feature-list'>";
                        while ($dt = mysqli_fetch_array($kunjungan)) {
                            echo "<li>" . htmlspecialchars($dt['nama_booth']) . "</li>";
                        }
                        echo "</ul><p>Terima kasih sudah mengunjungi booth kami!</p>";
                    } else {
                        echo "<p>Yuk kunjungi booth kami dan menangkan hadiah menarik!</p>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#privacy">Privacy Policy</a>
                <a href="#terms">Terms of Service</a>
                <a href="#careers">Careers</a>
            </div>
            <p class="copyright">
                © 2025 ELECTRIC XTRA. All rights reserved. Building tomorrow, today. 
                | Design: <a href="https://templatemo.com" target="_blank" rel="nofollow noopener">TemplateMo</a>
            </p>
        </div>
    </footer>

    <script src="templatemo-electric-scripts.js"></script>
</body>
</html>
