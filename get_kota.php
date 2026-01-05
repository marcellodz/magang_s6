<?php
include 'koneksi.php';

// Pastikan parameter dikirim
if (!isset($_POST['provinsi']) || empty($_POST['provinsi'])) {
    echo "<option value=''>⚠️ Silakan pilih provinsi terlebih dahulu</option>";
    exit;
}

$provinsi = mysqli_real_escape_string($conn, $_POST['provinsi']);
$sql = "SELECT DISTINCT kota FROM porsi_sma WHERE provinsi = '$provinsi' ORDER BY kota ASC";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<option value=''>⚠️ Data kota tidak ditemukan</option>";
} else {
    echo "<option value=''>Pilih Kota/Kabupaten</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $kota = htmlspecialchars($row['kota']);
        echo "<option value='$kota'>$kota</option>";
    }
}

mysqli_close($conn);
?>
