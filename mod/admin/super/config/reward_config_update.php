<!-- file untuk mengupdate konfigurasi nilai reward -->

<?php
session_start();

if ($_SESSION['role'] !== 'superadmin') {
    die("Akses ditolak.");
}

$facultyTarget = intval($_POST['facultyTarget'] ?? 7);
$otherTarget   = intval($_POST['otherTarget'] ?? 2);

$configFile = __DIR__ . '/../config/reward_config.php';

// Buat isi baru file konfigurasi
$newConfig = "<?php\nreturn [\n" .
    "    'facultyTarget' => {$facultyTarget},\n" .
    "    'otherTarget' => {$otherTarget},\n" .
    "];\n";

if (file_put_contents($configFile, $newConfig)) {
    echo "<script>
        alert('✅ Pengaturan berhasil disimpan!');
        window.location.href = '../../index.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Gagal menyimpan pengaturan!');
        window.history.back();
    </script>";
}

