<?php
require_once __DIR__ . "/../../koneksi.php";
date_default_timezone_set('Asia/Jakarta');
header('Content-Type: application/json');

$iduser = isset($_POST['iduser']) ? (int) $_POST['iduser'] : 0;
$mode   = $_POST['mode'] ?? 'create'; // check / create

if ($iduser <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID peserta tidak valid.']);
    exit;
}

// === Ambil Data Peserta ===
$user = $conn2->query("SELECT nama, email FROM super_user WHERE iduser='$iduser'")->fetch_assoc();
if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'Peserta tidak ditemukan.']);
    exit;
}

// =====================================================
//             CEK DULU KALAU MODE CHECK
// =====================================================
if ($mode === 'check') {

    $cek = $conn2->query("
        SELECT nama_kegiatan 
        FROM kegiatan_peserta
        WHERE iduser = '$iduser'
        AND (nama_kegiatan = 'Campus Tour - Sesi 3' OR nama_kegiatan = 'Campus Tour - Sesi 5')
        LIMIT 1
    ");

    if ($row = $cek->fetch_assoc()) {
        echo json_encode([
            'status' => 'already',
            'sesi' => $row['nama_kegiatan']
        ]);
        exit;
    }

    // Tentukan sesi tujuan berdasarkan jam
    $now = date('H:i');
    $sesiTujuan = ($now < "13:45") ? "Campus Tour - Sesi 3" : "Campus Tour - Sesi 5";

    echo json_encode([
        'status' => 'ok',
        'sesiTujuan' => $sesiTujuan
    ]);
    exit;
}



// =====================================================
//             MODE CREATE — INSERT DATA
// =====================================================

// Tentukan sesi berdasarkan waktu
$now = date('H:i');

if ($now < "13:45") {
    $namaKegiatan = "Campus Tour - Sesi 3";
    $waktu = "13:15 - 13:45";
} else {
    $namaKegiatan = "Campus Tour - Sesi 5";
    $waktu = "15:00 - 15:30";
}

// === INSERT KE kegiatan_peserta ===
$stmt1 = $conn2->prepare("
    INSERT INTO kegiatan_peserta (iduser, nama_peserta, nama_kegiatan, waktu_kegiatan)
    VALUES (?, ?, ?, ?)
");
$stmt1->bind_param("isss", $iduser, $user['nama'], $namaKegiatan, $waktu);
if (!$stmt1->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan ke tabel kegiatan_peserta.']);
    exit;
}

$idKegiatan = $stmt1->insert_id;

// === INSERT KE presensi_peserta ===
$stmt2 = $conn2->prepare("
    INSERT INTO presensi_peserta (iduser, nama, email, nama_kegiatan, id_kegiatan, status)
    VALUES (?, ?, ?, ?, ?, 'Belum Hadir')
");
$stmt2->bind_param("isssi", $iduser, $user['nama'], $user['email'], $namaKegiatan, $idKegiatan);
if (!$stmt2->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan ke tabel presensi_peserta.']);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => "Peserta berhasil didaftarkan ke $namaKegiatan ($waktu)."
]);
