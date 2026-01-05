<?php
// Ganti nilai-nilai berikut sesuai dengan konfigurasi database Anda
$servername = "localhost"; // Biasanya 'localhost'
$username = "openhouse";        // Ganti dengan username database Anda
$password = "R4xpMyMwjE7HmAHa";            // Ganti dengan password database Anda
$dbname = "openhouse";     // Nama database yang telah kita buat

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    // Hentikan eksekusi dan tampilkan error jika koneksi gagal
    die("Koneksi gagal: " . $conn->connect_error);
}
// Jika berhasil, variabel $conn sekarang berisi objek koneksi yang siap digunakan.
?>