<?php
require_once "kegiatan_data.php";
header("Content-Type: application/json");

// Ambil sesi
$sesi = $_GET['sesi'] ?? null;

if (!$sesi || !isset($kegiatanData[$sesi])) {
    echo json_encode([]);
    exit;
}

echo json_encode($kegiatanData[$sesi]);
