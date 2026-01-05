<?php
require_once "../../../koneksi.php";
require_once "kegiatan_data.php"; // ambil daftar kegiatan per sesi
header('Content-Type: application/json');

$sesi = $_GET['sesi'] ?? null;

if (!$sesi || !isset($kegiatanData[$sesi])) {
    echo json_encode([
        "totalPeserta" => 0,
        "hadir" => 0,
        "tidakHadir" => 0
    ]);
    exit;
}

$listKegiatan = $kegiatanData[$sesi];

$totalPeserta = 0;
$hadir = 0;

foreach ($listKegiatan as $kg) {

    // cari data di DB yang mengandung nama kegiatan (karena format beda)
    $sql1 = $conn2->prepare("
        SELECT COUNT(*) AS total 
        FROM kegiatan_peserta 
        WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
    ");
    $sql1->bind_param("s", $kg);
    $sql1->execute();
    $r1 = $sql1->get_result()->fetch_assoc();
    $totalPeserta += (int)$r1['total'];

    $sql2 = $conn2->prepare("
        SELECT COUNT(*) AS total 
        FROM presensi_peserta 
        WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
          AND status = 'Hadir'
    ");
    $sql2->bind_param("s", $kg);
    $sql2->execute();
    $r2 = $sql2->get_result()->fetch_assoc();
    $hadir += (int)$r2['total'];
}

$tidakHadir = max(0, $totalPeserta - $hadir);

echo json_encode([
    "totalPeserta" => $totalPeserta,
    "hadir"        => $hadir,
    "tidakHadir"   => $tidakHadir
]);
