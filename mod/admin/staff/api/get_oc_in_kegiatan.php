<?php
require_once "../../../koneksi.php";
header("Content-Type: application/json");

$nama = $_GET['nama'] ?? null;
$sesi = $_GET['sesi'] ?? null;

if (!$nama || !$sesi) {
    echo json_encode(["hadirOC" => 0]);
    exit;
}

// 1️⃣ Ambil semua iduser yang terdaftar pada kegiatan ini
$stmt = $conn2->prepare("
    SELECT DISTINCT iduser
    FROM kegiatan_peserta
    WHERE nama_kegiatan = ?
");
$stmt->bind_param("s", $nama);
$stmt->execute();
$res = $stmt->get_result();

$ids = [];
while ($row = $res->fetch_assoc()) {
    $ids[] = (int)$row['iduser'];
}

if (empty($ids)) {
    echo json_encode(["hadirOC" => 0]);
    exit;
}

$idList = implode(",", $ids);

// 2️⃣ Hitung jumlah peserta kegiatan ini yang hadir di *Registrasi Awal*
$q = $conn2->query("
    SELECT COUNT(DISTINCT iduser) AS total
    FROM presensi_peserta
    WHERE nama_kegiatan = 'Registrasi Awal'
      AND status = 'Hadir'
      AND iduser IN ($idList)
");

$hadirOC = $q->fetch_assoc()['total'] ?? 0;

// 3️⃣ Return JSON
echo json_encode(["hadirOC" => (int)$hadirOC]);
