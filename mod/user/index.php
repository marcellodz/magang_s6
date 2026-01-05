<?php
if (session_status() === PHP_SESSION_NONE) session_start();

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
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="css/templatemo-electric-xtra.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <?php
        include 'nav.php';
    ?>
</head>

<body>
    <nav id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo-link">
                <img src="images/logo-openhouse.png" alt="Logo Telkom University" class="logo-svg">
            </a>

            <div class="user-nav">
                <div class="user-menu-dropdown">
                    <button class="user-icon-btn" id="userIconBtn"><i class="fas fa-user-circle"></i></button>
                    <div class="dropdown-content" id="userDropdown">
                        <!--<a href="profile">Profil Saya</a>-->
                        <a href="logout.php" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="grid-2">
            <div class="card">
                <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h2>
                <br>
                <p>
                    Kamu telah terdaftar dalam kegiatan <b>Open House Telkom University 2025</b>.<br>
                    Gunakan halaman ini untuk:
                </p>
                <ul>
                    <li>Menunjukkan QR Code ke petugas untuk presensi.</li>
                    <li>Scan QR Booth untuk mendapatkan hadiah menarik.</li>
                    <li>Melihat kegiatan dan riwayat presensimu.</li>
                </ul>
            </div>

            <div class="card">
                <h3><i class="fas fa-qrcode"></i> QR Code Presensiku</h3>
                <div id="qr-wrapper" style="margin-top:15px;text-align:center;"></div>
            </div>
        </div>

        <div class="tabs-container">
            <div class="tabs-header">
                <div class="tab-item active" data-tab="scan"><i class="fas fa-qrcode"></i> Scan Booth</div>
                <div class="tab-item" data-tab="kegiatan"><i class="fas fa-calendar-check"></i> Kegiatan Saya</div>
                <div class="tab-item" data-tab="presensi"><i class="fas fa-user-check"></i> Presensi Saya</div>
                <div class="tab-item" data-tab="reward"><i class="fas fa-gift"></i> Point & Reward</div>
            </div>
            <div id="tab-content">
                <div class="loading"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('userIconBtn');
        const dropdown = document.getElementById('userDropdown');
        btn.addEventListener('click', () => {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        window.addEventListener('click', e => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = 'none';
        });
    });

    fetch('user_content.php?type=qr')
        .then(res => res.text())
        .then(html => document.getElementById('qr-wrapper').innerHTML = html);

    const tabs = document.querySelectorAll('.tab-item');
    const container = document.getElementById('tab-content');
    function loadTab(type) {
        container.classList.add('fade-out');
        container.classList.remove('fade-in');
        setTimeout(() => {
            fetch(`user_content.php?type=${type}`)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;
                    container.classList.remove('fade-out');
                    container.classList.add('fade-in');
                })
                .catch(() => {
                    container.innerHTML = "<p style='color:red;text-align:center;'>❌ Gagal memuat data.</p>";
                });
        }, 150);
    }
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            if (tab.classList.contains('active')) return;
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            loadTab(tab.dataset.tab);
        });
    });
    loadTab('scan');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.claimBtn');
        if (!btn) return;

        btn.disabled = true;
        const oldHTML = btn.innerHTML;
        btn.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Membuka QR...";

        try {
            const res = await fetch('generate_qr_claim.php');
            const html = await res.text();

            Swal.fire({
                title: '<i class="fas fa-gift"></i> QR Klaim Hadiah',
                html: html,
                width: 550,
                background: 'rgba(15,15,15,0.96)',
                color: '#fff',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                didOpen: () => {
                    const img = document.querySelector('.swal2-popup img');
                    if (img) {
                        img.style.width = '230px';
                        img.style.border = '3px solid #ff3333';
                        img.style.borderRadius = '10px';
                        img.style.boxShadow = '0 0 20px rgba(255,0,0,0.3)';
                        img.style.marginBottom = '10px';
                    }
                }
            });
        } catch {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat QR',
                text: 'Terjadi kesalahan saat memuat QR Klaim.',
                background: 'rgba(0,0,0,0.9)',
                color: '#fff'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldHTML;
        }
    });
    </script>
</body>
</html>
