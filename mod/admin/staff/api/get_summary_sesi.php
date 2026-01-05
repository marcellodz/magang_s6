<?php
require_once "../../../koneksi.php";
$kegiatanData = require "kegiatan_data.php";
header("Content-Type: application/json");

$sesi = $_GET['sesi'] ?? null;

// Jika sesi tidak valid → return kosong
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

// Loop semua kegiatan dalam sesi
foreach ($listKegiatan as $kg) {

    // Hitung total pendaftar kegiatan
    $q1 = $conn2->prepare("
        SELECT COUNT(*) AS total 
        FROM kegiatan_peserta
        WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
    ");
    $q1->bind_param("s", $kg);
    $q1->execute();
    $r1 = $q1->get_result()->fetch_assoc();
    $totalPeserta += (int)$r1['total'];

    // Hitung yang hadir di presensi_peserta
    $q2 = $conn2->prepare("
        SELECT COUNT(*) AS total 
        FROM presensi_peserta
        WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
          AND status = 'Hadir'
    ");
    $q2->bind_param("s", $kg);
    $q2->execute();
    $r2 = $q2->get_result()->fetch_assoc();
    $hadir += (int)$r2['total'];
}

// Hitung tidak hadir berdasarkan gabungan semua kegiatan sesi
$tidakHadir = max(0, $totalPeserta - $hadir);

echo json_encode([
    "totalPeserta" => $totalPeserta,
    "hadir"        => $hadir,
    "tidakHadir"   => $tidakHadir
]);
