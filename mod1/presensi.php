<?php
// ==============================
// FILE: mod/user/presensi.php
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

require_once "koneksi.php";
$iduser = $_SESSION['iduser'];

// Ambil data presensi user
$query = "
    SELECT nama_kegiatan, waktu_presensi, status
    FROM presensi_peserta
    WHERE iduser = '$iduser'
    ORDER BY id_presensi DESC
";
$result = mysqli_query($conn2, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Saya - Open House Telkom University</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link href="templatemo-electric-xtra.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">

    <style>
/* ======= STYLE FIXED FOR PRESENSI TABLE ======= */

.profile-container {
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 60px;
    padding: 40px 60px;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
    color: #fff;
    max-width: 900px;
}

.profile-title {
    text-transform: uppercase;
    color: #ff6363;
    letter-spacing: 1px;
    margin-bottom: 25px;
    text-align: center;
}

/* ======= TABLE STRUCTURE ======= */
.presensi-table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
    font-size: 14px;
    table-layout: fixed; /* ✅ biar lebar kolom konsisten */
}

/* Header style */
.presensi-table th {
    background: rgba(255, 255, 255, 0.07);
    color: #ff9f00;
    text-transform: uppercase;
    padding: 12px;
    font-weight: 600;
}

/* Cell style */
.presensi-table td {
    padding: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    vertical-align: middle;
}

/* ======= COLUMN ALIGNMENT ======= */
.presensi-table th:nth-child(1),
.presensi-table td:nth-child(1) {
    width: 30%;
    text-align: left; /* ✅ nama kegiatan di kiri */
}

.presensi-table th:nth-child(2),
.presensi-table td:nth-child(2) {
    width: 25%;
    text-align: center; /* ✅ waktu di tengah */
}

.presensi-table th:nth-child(3),
.presensi-table td:nth-child(3) {
    width: 25%;
    text-align: center; /* ✅ status di tengah sejajar header */
}

/* ======= STATUS BADGE ======= */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    display: inline-block;
    min-width: 100px; /* ✅ biar semua badge sejajar */
    text-align: center;
}

.status-hadir {
    background: rgba(0, 255, 100, 0.2);
    color: #50fa7b;
}

.status-belum {
    background: rgba(255, 255, 255, 0.1);
    color: #bbb;
}

.status-absen {
    background: rgba(255, 50, 50, 0.2);
    color: #ff5555;
}

/* ======= RESPONSIVE ======= */
@media (max-width: 768px) {
    .profile-container {
        margin: 100px 20px 60px 20px;
        padding: 30px 25px;
    }
    .presensi-table {
        font-size: 13px;
    }
}

    </style>
</head>

<body>
    <div class="grid-bg"></div>
    <div class="gradient-overlay"></div>
    <div class="scanlines"></div>

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
                        <a href="kegiatan">Kegiatan Saya</a>
                        <a href="presensi" class="active">Presensi Saya</a>
                        <a href="logout" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="profile-container">
        <h2 class="profile-title">Presensi Saya</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="presensi-table">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Waktu Presensi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $status = $row['status'];
                        $badgeClass = match ($status) {
                            'Hadir' => 'status-hadir',
                            'Tidak Hadir' => 'status-absen',
                            default => 'status-belum'
                        };
                        $waktu = $row['waktu_presensi'] ? date('d M Y, H:i', strtotime($row['waktu_presensi'])) : '-';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
                            <td><?= htmlspecialchars($waktu) ?></td>
                            <td><span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Belum ada data presensi.</div>
        <?php endif; ?>
    </section>

    <script>
        // Toggle dropdown user menu
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