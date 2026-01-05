<?php
require_once "../../../koneksi.php";
require_once "kegiatan_data.php";
header("Content-Type: application/json");

$sesi = $_GET['sesi'] ?? null;

if (!$sesi || !isset($kegiatanData[$sesi])) {
    echo json_encode(["hadirOC" => 0]);
    exit;
}

$listKegiatan = $kegiatanData[$sesi];

// Ambil semua iduser di sesi tsb
$ids = [];
foreach ($listKegiatan as $kg) {
    $sql = $conn2->prepare("
        SELECT DISTINCT iduser
        FROM kegiatan_peserta
        WHERE nama_kegiatan LIKE CONCAT('%', ?, '%')
    ");
    $sql->bind_param("s", $kg);
    $sql->execute();
    $result = $sql->get_result();

    while ($row = $result->fetch_assoc()) {
        $ids[$row['iduser']] = true;
    }
}

if (empty($ids)) {
    echo json_encode(["hadirOC" => 0]);
    exit;
}

$idList = implode(",", array_keys($ids));

// Hitung berapa yang hadir opening ceremony
$q = $conn2->query("
    SELECT COUNT(*) AS total 
    FROM presensi_peserta
    WHERE nama_kegiatan LIKE '%Opening Ceremony%'
      AND status = 'Hadir'
      AND iduser IN ($idList)
");

$hadirOC = $q->fetch_assoc()['total'] ?? 0;

echo json_encode(["hadirOC" => (int)$hadirOC]);
