<?php
ob_end_clean();
ob_start();

require_once __DIR__ . '/../../koneksi.php';

// Query data
$sql = "
    SELECT 
        nama AS nama_lengkap,
        email,
        hp AS no_wa,
        kelas AS profesi_kelas,
        CASE 
            WHEN sekolah IS NULL OR sekolah = '' THEN sekolah_lainnya
            ELSE sekolah
        END AS asal_sekolah,
        provinsi,
        kota,
        createdAt AS tanggal_daftar
    FROM super_user
    ORDER BY createdAt DESC
";

$result = $conn2->query($sql);

$filename = "Data_Peserta_OpenHouse_" . date("Ymd_His") . ".csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// BOM UTF-8 supaya Excel baca karakter dengan benar
echo "\xEF\xBB\xBF";

// PAKAI SEMICOLON — SOLUSI AGAR EXCEL INDONESIA MEMISAHKAN KOLOM
$delimiter = ";";

// Header kolom
echo "Nama Lengkap{$delimiter}Email{$delimiter}No. WhatsApp{$delimiter}Profesi / Kelas{$delimiter}Asal Sekolah{$delimiter}Provinsi{$delimiter}Kota{$delimiter}Tanggal Daftar\n";

// Isi data
while ($row = $result->fetch_assoc()) {
    echo 
        '"' . $row['nama_lengkap']   . '"' . $delimiter .
        '"' . $row['email']          . '"' . $delimiter .
        '"' . $row['no_wa']          . '"' . $delimiter .
        '"' . $row['profesi_kelas']  . '"' . $delimiter .
        '"' . $row['asal_sekolah']   . '"' . $delimiter .
        '"' . $row['provinsi']       . '"' . $delimiter .
        '"' . $row['kota']           . '"' . $delimiter .
        '"' . $row['tanggal_daftar'] . '"' .
    "\n";
}

exit;
