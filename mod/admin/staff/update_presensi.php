
<?php
require_once __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

$iduser = $_GET['iduser'] ?? '';
$id_kegiatan = $_GET['id_kegiatan'] ?? '';
$action = $_GET['action'] ?? '';

if (empty($iduser) || empty($id_kegiatan)) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap."]);
    exit;
}

// Ambil data user dan kegiatan
$user = $conn2->query("SELECT nama, email FROM super_user WHERE iduser='$iduser'")->fetch_assoc();
$kegiatan = $conn2->query("SELECT nama_kegiatan FROM kegiatan_peserta WHERE id_kegiatan='$id_kegiatan'")->fetch_assoc();

if (!$user || !$kegiatan) {
    echo json_encode(["success" => false, "message" => "Data peserta atau kegiatan tidak ditemukan."]);
    exit;
}

$nama = $user['nama'];
$email = $user['email'];
$nama_kegiatan = $kegiatan['nama_kegiatan'];

// Cek apakah sudah ada di tabel presensi
$cek = $conn2->query("SELECT * FROM presensi_peserta WHERE iduser='$iduser' AND id_kegiatan='$id_kegiatan' LIMIT 1");

if ($cek->num_rows > 0) {
    // Update status
    if ($action === 'hadir') {
        $q = $conn2->query("
            UPDATE presensi_peserta 
            SET status='Hadir', waktu_presensi=NOW() 
            WHERE iduser='$iduser' AND id_kegiatan='$id_kegiatan'
        ");
    } else {
        $q = $conn2->query("
            UPDATE presensi_peserta 
            SET status='Belum Hadir', waktu_presensi=NULL 
            WHERE iduser='$iduser' AND id_kegiatan='$id_kegiatan'
        ");
    }
} else {
    // Insert baru kalau belum ada
    $status = ($action === 'hadir') ? 'Hadir' : 'Belum Hadir';
    $waktu = ($action === 'hadir') ? "NOW()" : "NULL";

    $q = $conn2->query("
        INSERT INTO presensi_peserta (iduser, nama, email, nama_kegiatan, id_kegiatan, waktu_presensi, status)
        VALUES ('$iduser', '$nama', '$email', '$nama_kegiatan', '$id_kegiatan', $waktu, '$status')
    ");
}

if ($q) {
    echo json_encode(["success" => true, "message" => "Status presensi berhasil diperbarui."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal memperbarui status: " . $conn2->error]);
}
?>
