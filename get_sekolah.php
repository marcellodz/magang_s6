<?php
include 'koneksi.php';

// Pastikan parameter dikirim
if (!isset($_POST['kota']) || empty($_POST['kota'])) {
    echo "<option value=''>⚠️ Silakan pilih kota terlebih dahulu</option>";
    exit;
}

$kota = mysqli_real_escape_string($conn, $_POST['kota']);
$sql = "SELECT DISTINCT namasma FROM porsi_sma WHERE kota = '$kota' ORDER BY namasma ASC";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<option value=''>⚠️ Data sekolah tidak ditemukan</option>";
} else {
    echo "<option value=''>Pilih Sekolah/Instansi</option>";
        echo "<option value='Lainnya'>LAINNYA</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $nama = htmlspecialchars($row['namasma']);
        echo "<option value='$nama'>$nama</option>";
    }
}

mysqli_close($conn);
?>
