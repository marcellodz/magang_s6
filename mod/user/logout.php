<?php
// Pastikan session sudah dimulai
session_start();

// Hapus semua variabel sesi yang terdaftar
$_SESSION = array();

// Jika menggunakan cookie sesi, hapus juga cookie-nya
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan sesi sepenuhnya
session_destroy();

// Arahkan user ke halaman login/utama
// --- KODE BARU UNTUK POPUP DAN REDIRECT ---
$redirect_url = "https://openhouse.smbbtelkom.ac.id/"; // Sementara pakai lokal ( Marcell )

            
echo "<script type='text/javascript'>";
echo "alert('Kamu sudah keluar dari sistem Open House.');";
    // Menggunakan window.location.href untuk melakukan redirect
echo "window.location.href = '" . $redirect_url . "';"; 
echo "</script>";
exit;
?>