<?php
// ==============================
// FILE: mod/update_profile.php
// ==============================

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Anda belum login.']);
    exit;
}

require_once "koneksi.php";
$iduser = $_SESSION['iduser'];

// ==============================
// Validasi input
// ==============================
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$hp = trim($_POST['hp'] ?? '');
$provinsi = trim($_POST['provinsi'] ?? '');
$kota = trim($_POST['kota'] ?? '');
$sekolah = trim($_POST['sekolah'] ?? '');
$jenjang_studi = trim($_POST['jenjang_studi'] ?? '');

if ($nama === '' || $email === '' || $hp === '') {
    echo json_encode(['success' => false, 'message' => 'Nama, email, dan nomor WhatsApp wajib diisi.']);
    exit;
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
    exit;
}

// Validasi nomor HP (minimal 9 digit)
if (!preg_match('/^[0-9]{9,15}$/', $hp)) {
    echo json_encode(['success' => false, 'message' => 'Nomor WhatsApp tidak valid.']);
    exit;
}

// ==============================
// Update ke database
// ==============================
$stmt = $conn2->prepare("
    UPDATE super_user 
    SET nama = ?, email = ?, hp = ?, provinsi = ?, kota = ?, sekolah = ?, jenjang_studi = ?
    WHERE iduser = ?
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan query: ' . $conn2->error]);
    exit;
}

$stmt->bind_param("sssssssi", $nama, $email, $hp, $provinsi, $kota, $sekolah, $jenjang_studi, $iduser);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui ✅']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data: ' . $stmt->error]);
}

$stmt->close();
$conn2->close();
?>
