<?php
require_once "../../../koneksi.php";
require_once "kegiatan_data.php";
header("Content-Type: application/json");

$nama = $_GET['nama'] ?? null;
$sesi = $_GET['sesi'] ?? null;
if (!$nama || !$sesi) { echo json_encode(["hadirOC"=>0]); exit; }

// normalisasi input (hilangkan kata-kata "Seminar - ", "Trial Class - " bila ada)
function normalize($s){
    $s = trim($s);
    $s = preg_replace('/^(Seminar|Trial Class)\s*-\s*/i', '', $s);
    return $s;
}

$namaNorm = $conn2->real_escape_string(normalize($nama));

// Ambil iduser peserta yang terdaftar pada *nama kegiatan yang mengandung* namaNorm
$stmt = $conn2->prepare("
    SELECT DISTINCT iduser
    FROM kegiatan_peserta
    WHERE REPLACE(REPLACE(nama_kegiatan, 'Seminar - ', ''), 'Trial Class - ', '') LIKE CONCAT('%', ?, '%')
");
$stmt->bind_param("s", $namaNorm);
$stmt->execute();
$res = $stmt->get_result();

$ids = [];
while ($r = $res->fetch_assoc()) $ids[] = (int)$r['iduser'];
if (empty($ids)) { echo json_encode(["hadirOC"=>0]); exit; }

$userList = implode(",", $ids);

// Hitung hadir Opening Ceremony untuk iduser list ini
$q = $conn2->query("
    SELECT COUNT(DISTINCT iduser) AS total
    FROM presensi_peserta
    WHERE nama_kegiatan = 'Opening Ceremony'
      AND status = 'Hadir'
      AND iduser IN ($userList)
");
$hadirOC = $q->fetch_assoc()['total'] ?? 0;
echo json_encode(["hadirOC" => (int)$hadirOC]);
