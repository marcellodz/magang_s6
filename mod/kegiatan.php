<?php
// ==============================
// FILE: mod/kegiatan.php
// ==============================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
            alert('Silakan login terlebih dahulu.');
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
    <title>Kegiatan Saya - Open House Telkom University</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link href="templatemo-electric-xtra.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .kegiatan-container {
            margin: 120px auto 80px auto;
            padding: 50px 60px;
            background: rgba(0, 0, 0, 0.65);
            border-radius: 16px;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.08);
            color: #fff;
            max-width: 900px;
            min-height: 70vh;
        }

        .kegiatan-title {
            text-transform: uppercase;
            color: #ff6363;
            margin-bottom: 15px;
            letter-spacing: 1px;
            text-align: center;
        }

        .kegiatan-list {
            background: #111;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .kegiatan-list h3 {
            color: #ff9f00;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .kegiatan-list ul {
            list-style: none;
            padding-left: 0;
        }

        .kegiatan-list li {
            padding: 8px 0;
            border-bottom: 1px solid #333;
        }

        .no-data {
            text-align: center;
            color: #aaa;
            font-style: italic;
            margin: auto;
            font-size: 16px;
            min-height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            background: #ff6363;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #ff7b7b;
        }

        @media (max-width: 768px) {
            .kegiatan-container {
                margin: 100px 20px 60px 20px;
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="grid-bg"></div>
    <div class="gradient-overlay"></div>
    <div class="scanlines"></div>

    <!-- Navbar -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="index" class="logo-link">
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
                        <a href="kegiatan" class="active">Kegiatan Saya</a>
                        <a href="presensi">Presensi Saya</a>
                        <a href="logout" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="kegiatan-container">
        <h2 class="kegiatan-title">Kegiatan Saya</h2>

        <div id="kegiatan-content">
            <div class="kegiatan-list">
                <h3>Seminar</h3>
                <div id="seminar-content" class="no-data">Memuat data...</div>
            </div>

            <div class="kegiatan-list">
                <h3>Trial Class</h3>
                <div id="trial-content" class="no-data">Memuat data...</div>
            </div>

            <div class="kegiatan-list">
                <h3>Campus Tour</h3>
                <div id="campus-content" class="no-data">Memuat data...</div>
            </div>

            <!-- 🆕 Tambahan Section: Kunjungan Booth -->
            <div class="kegiatan-list">
                <h3>Kunjungan Booth</h3>
                <div id="booth-content" class="no-data">Memuat data...</div>
            </div>
        </div>

        <center>
            <a href="index" class="back-btn">⬅ Kembali ke Dashboard</a>
        </center>
    </section>

    <script>
        $(document).ready(function () {
            $.getJSON("get_kegiatan", function (res) {
                if (res.status === "success") {

                    // Seminar
                    if (res.seminar.length > 0) {
                        let list = "<ul>";
                        res.seminar.forEach(s => list += `<li>${s}</li>`);
                        list += "</ul>";
                        $("#seminar-content").html(list);
                    } else $("#seminar-content").html("<p class='no-data'>Tidak mengikuti seminar.</p>");

                    // Trial Class
                    if (res.trial_class.length > 0) {
                        let list = "<ul>";
                        res.trial_class.forEach(t => list += `<li>${t}</li>`);
                        list += "</ul>";
                        $("#trial-content").html(list);
                    } else $("#trial-content").html("<p class='no-data'>Tidak mengikuti trial class.</p>");

                    // Campus Tour
                    if (res.campus_tour) {
                        $("#campus-content").html(`<ul><li>${res.campus_tour}</li></ul>`);
                    } else $("#campus-content").html("<p class='no-data'>Tidak mengikuti campus tour.</p>");

                    // 🆕 Booth Kunjungan
                    if (res.booth && res.booth.length > 0) {
                        let list = "<ul>";
                        res.booth.forEach(b => list += `<li><strong>${b.nama_booth}</strong> — <em>${b.waktu}</em></li>`);
                        list += "</ul>";
                        $("#booth-content").html(list);
                    } else {
                        $("#booth-content").html("<p class='no-data'>Belum ada kunjungan booth.</p>");
                    }

                } else {
                    $("#kegiatan-content").html("<div class='no-data'>Gagal memuat data kegiatan.</div>");
                }
            }).fail(() => {
                $("#kegiatan-content").html("<div class='no-data'>Terjadi kesalahan koneksi.</div>");
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userIconBtn');
            const dropdown = document.getElementById('userDropdown');
            btn.addEventListener('click', () => {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            window.addEventListener('click', function (e) {
                if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>

    <script src="templatemo-electric-scripts.js"></script>
</body>

</html>
