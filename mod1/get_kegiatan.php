<?php
// ==============================
// FILE: mod/get_kegiatan.php
// ==============================

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Belum login']);
    exit;
}

require_once "koneksi.php";
$iduser = $_SESSION['iduser'];

// ===============================
// Ambil data kegiatan user
// ===============================
$sql = "SELECT nama_kegiatan, waktu_kegiatan FROM kegiatan_peserta WHERE iduser = ?";
$stmt = $conn2->prepare($sql);
$stmt->bind_param("i", $iduser);
$stmt->execute();
$result = $stmt->get_result();

$seminar = [];
$trial = [];
$tour = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nama = strtolower($row['nama_kegiatan']);
        $display = $row['nama_kegiatan'] . " (" . $row['waktu_kegiatan'] . ")";

        if (str_contains($nama, 'seminar')) {
            $seminar[] = $display;
        } elseif (str_contains($nama, 'trial')) {
            $trial[] = $display;
        } elseif (str_contains($nama, 'tour')) {
            $tour[] = $display;
        }
    }
}

// ===============================
// Ambil data kunjungan booth
// ===============================
$booth = [];
$q_booth = $conn2->prepare("
    SELECT b.nama_booth, k.waktu_kunjungan 
    FROM booth_kunjungan k
    JOIN booth b ON k.idbooth = b.idbooth
    WHERE k.iduser = ?
    ORDER BY k.waktu_kunjungan DESC
");
$q_booth->bind_param("i", $iduser);
$q_booth->execute();
$res_booth = $q_booth->get_result();

while ($row = $res_booth->fetch_assoc()) {
    $booth[] = [
        'nama_booth' => $row['nama_booth'],
        'waktu' => date('d M Y, H:i', strtotime($row['waktu_kunjungan']))
    ];
}

// ===============================
// Return JSON ke frontend
// ===============================
if (!empty($seminar) || !empty($trial) || !empty($tour) || !empty($booth)) {
    echo json_encode([
        'status' => 'success',
        'seminar' => $seminar,
        'trial_class' => $trial,
        'campus_tour' => $tour,
        'booth' => $booth
    ]);
} else {
    echo json_encode(['status' => 'empty']);
}

$stmt->close();
$q_booth->close();
$conn2->close();
?>
