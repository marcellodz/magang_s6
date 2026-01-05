<?php
// ==============================
// FILE: mod/profile.php
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

// Ambil data user
$query = "SELECT * FROM super_user WHERE iduser = '$iduser' LIMIT 1";
$result = mysqli_query($conn2, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Open House Telkom University</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link href="templatemo-electric-xtra.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">

    <style>
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
            max-width: 800px;
        }

        .profile-title {
            text-transform: uppercase;
            color: #ff6363;
            letter-spacing: 1px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            color: #ff9f00;
            font-weight: 600;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #333;
            background: rgba(20, 20, 20, 0.85);
            color: #fff;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #ff6363;
            box-shadow: 0 0 5px rgba(255, 99, 99, 0.5);
        }

        .save-btn {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: #ff6363;
            color: white;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .save-btn:hover {
            background: #ff7b7b;
        }

        .save-btn:disabled {
            background: #444 !important;
            color: #aaa !important;
            cursor: not-allowed;
        }

        .success-msg,
        .error-msg {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px;
        }

        .success-msg {
            background: rgba(0, 255, 100, 0.15);
            color: #50fa7b;
        }

        .error-msg {
            background: rgba(255, 50, 50, 0.15);
            color: #ff5555;
        }

        .nav-container {
            z-index: 100;
        }

        #alert-box {
            position: relative;
            min-height: 60px;
            /* biar form gak lompat waktu alert muncul */
            transition: all 0.5s ease;
        }

        .alert-message {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px;
        }

        .alert-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        .success-msg {
            background: rgba(0, 255, 100, 0.15);
            color: #50fa7b;
        }

        .error-msg {
            background: rgba(255, 50, 50, 0.15);
            color: #ff5555;
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
                        <a href="profile" class="active">Profil Saya</a>
                        <a href="kegiatan">Kegiatan Saya</a>
                        <a href="presensi">Presensi Saya</a>
                        <a href="logout" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="profile-container">
        <h2 class="profile-title">Profil Saya</h2>

        <div id="alert-box"></div>

        <form id="profileForm">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>No. WhatsApp</label>
                <input type="text" name="hp" value="<?= htmlspecialchars($user['hp']) ?>" required>
            </div>

            <div class="form-group">
                <label>Provinsi</label>
                <input type="text" name="provinsi" value="<?= htmlspecialchars($user['provinsi']) ?>">
            </div>

            <div class="form-group">
                <label>Kota / Kabupaten</label>
                <input type="text" name="kota" value="<?= htmlspecialchars($user['kota']) ?>">
            </div>

            <div class="form-group">
                <label>Sekolah / Instansi</label>
                <input type="text" name="sekolah" value="<?= htmlspecialchars($user['sekolah']) ?>">
            </div>

            <div class="form-group">
                <label>Jenjang Studi Yang di Minati</label>
                <select name="jenjang_studi">
                    <?php
                    $jenjang_options = ["D3/D4", "S1", "S1 Ekstensi", "S2", "S3", "Tidak Minat"];
                    foreach ($jenjang_options as $opt) {
                        $selected = ($user['jenjang_studi'] === $opt) ? "selected" : "";
                        echo "<option value='$opt' $selected>$opt</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="save-btn">Simpan Perubahan</button>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userIconBtn');
            const dropdown = document.getElementById('userDropdown');
            const form = document.getElementById('profileForm');
            const saveBtn = form.querySelector('.save-btn');
            const alertBox = document.getElementById('alert-box');

            // Toggle dropdown menu
            btn.addEventListener('click', () => {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            window.addEventListener('click', function (e) {
                if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Simpan data awal untuk deteksi perubahan
            const originalData = new FormData(form);

            function isChanged() {
                const currentData = new FormData(form);
                for (const [key, value] of currentData.entries()) {
                    if (value !== originalData.get(key)) return true;
                }
                return false;
            }

            function toggleSaveButton() {
                if (isChanged()) {
                    saveBtn.disabled = false;
                } else {
                    saveBtn.disabled = true;
                }
            }

            // Pantau perubahan di setiap input/select
            form.querySelectorAll('input, select').forEach(el => {
                el.addEventListener('input', toggleSaveButton);
                el.addEventListener('change', toggleSaveButton);
            });

            toggleSaveButton(); // Inisialisasi awal

            // Form submit via AJAX
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                saveBtn.disabled = true;
                saveBtn.textContent = 'Menyimpan...';

                window.scrollTo({ top: 0, behavior: 'smooth' });

                fetch('update_profile.php', {
                    method: 'POST',
                    body: new FormData(form)
                })
                    .then(res => res.json())
                    .then(data => {
                        const box = document.getElementById('alert-box');

                        // Buat elemen alert baru dengan animasi
                        const type = data.success ? 'success-msg' : 'error-msg';
                        const notif = document.createElement('div');
                        notif.className = `alert-message ${type}`;
                        notif.textContent = data.message;
                        box.innerHTML = ''; // hapus alert lama kalau ada
                        box.appendChild(notif);

                        // Animasi masuk
                        requestAnimationFrame(() => notif.classList.add('show'));

                        // Animasi keluar
                        setTimeout(() => {
                            notif.classList.remove('show');
                        }, 3000); // muncul selama 3 detik

                        // Hapus dari DOM setelah fade-out selesai
                        setTimeout(() => {
                            notif.remove();
                        }, 4000);

                        // Reset tombol
                        saveBtn.textContent = 'Simpan Perubahan';
                        if (data.success) {
                            for (const [key, value] of new FormData(form).entries()) {
                                originalData.set(key, value);
                            }
                        }
                        toggleSaveButton();
                    })
                    .catch(() => {
                        const box = document.getElementById('alert-box');
                        box.innerHTML = `<div class='error-msg' id='notifBox'>Terjadi kesalahan server.</div>`;
                        saveBtn.textContent = 'Simpan Perubahan';
                        saveBtn.disabled = false;
                        setTimeout(() => {
                            box.innerHTML = "";
                        }, 3500);
                    });
            });
        });
    </script>

    <script src="templatemo-electric-scripts.js"></script>
</body>

</html>