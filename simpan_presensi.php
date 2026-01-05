<?php
// Konfigurasi Koneksi Database
include 'koneksi.php'; 

if (isset($_POST['qr_data']) && !empty($_POST['qr_data'])) {
    
    // 1. Membersihkan string dan mencoba decode JSON
    $qr_data_string = trim($_POST['qr_data']);
    $qr_data_string = stripslashes($qr_data_string); // Penting untuk membersihkan backslash
    
    $data_json = json_decode($qr_data_string, true);
    
    // --- VERIFIKASI JSON ---
    if ($data_json === null) {
        // Gagal total karena string bukan JSON valid
        echo "❌ Format data QR tidak valid: Gagal menguraikan JSON. Data mentah: " . htmlspecialchars($qr_data_string);
        $conn2->close();
        exit;
    }

    if (!isset($data_json['id'], $data_json['nama'], $data_json['email'])) {
        // Gagal karena key di JSON tidak lengkap
        echo "❌ Format data QR tidak valid: Key (id, nama, email) tidak ditemukan di JSON.";
        $conn2->close();
        exit;
    }
    
    // --- 2. Ambil dan Sanitasi Data (Menggunakan Prepared Statement) ---
    $iduser = $data_json['id'];
    $nama = $data_json['nama'];
    $email = $data_json['email'];
    $waktu_presensi = date("Y-m-d H:i:s");
    $status = 'HADIR';
    
    // Mengubah ID menjadi integer jika kolom DB adalah INT (intval membantu menghindari 'Incorrect integer value')
    $iduser_db = intval($iduser); 

    // --- 3. Menyimpan ke Tabel presensi_peserta menggunakan Prepared Statement ---
    // Prepared statement wajib untuk keamanan (menghindari SQL Injection)

    $stmt = $conn2->prepare("INSERT INTO presensi_peserta (iduser, nama, email, waktu_presensi, status) 
                            VALUES (?, ?, ?, ?, ?)");
    
    // Tipe data: i (integer untuk iduser), s (string untuk nama, email, waktu, status)
    $stmt->bind_param("issss", $iduser_db, $nama, $email, $waktu_presensi, $status); 

    if ($stmt->execute()) {
        echo "✅ Presensi untuk ID {$iduser} berhasil dicatat!";
    } else {
        echo "❌ Error saat menyimpan presensi: " . $stmt->error;
    }
    
    $stmt->close();

} else {
    echo "Tidak ada data QR Code yang diterima.";
}

$conn2->close();
?>