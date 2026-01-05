<?php
require_once "../../../koneksi.php";
header('Content-Type: application/json');

$nama = $_GET['nama'] ?? null;
$sesi = $_GET['sesi'] ?? null;

if (!$nama) {
    echo json_encode([
        "totalPeserta" => 0,
        "hadir" => 0,
        "tidakHadir" => 0
    ]);
    exit;
}

// ========== TOTAL PENDAFTAR KEGIATAN ==========
$sql1 = $conn2->prepare("
    SELECT COUNT(*) AS total
    FROM kegiatan_peserta
    WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
");
$sql1->bind_param("s", $nama);
$sql1->execute();
$totalPeserta = $sql1->get_result()->fetch_assoc()['total'] ?? 0;

// ========== YANG HADIR KEGIATAN ==========
$sql2 = $conn2->prepare("
    SELECT COUNT(*) AS total
    FROM presensi_peserta
    WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
      AND status = 'Hadir'
");
$sql2->bind_param("s", $nama);
$sql2->execute();
$hadir = $sql2->get_result()->fetch_assoc()['total'] ?? 0;

$tidakHadir = max(0, $totalPeserta - $hadir);

echo json_encode([
    "totalPeserta" => $totalPeserta,
    "hadir"        => $hadir,
    "tidakHadir"   => $tidakHadir
]);
