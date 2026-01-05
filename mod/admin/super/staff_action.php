<!-- mod/admin/super/staff_action.php -->
<?php
session_start();
require_once "../../koneksi.php";
header('Content-Type: application/json; charset=utf-8');

// ==== Helper ====
function response($status, $message) {
    echo json_encode(["status" => $status, "message" => $message]);
    exit;
}

// ==== Validasi Role ====
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    response("error", "❌ Akses ditolak. Hanya superadmin yang dapat mengelola staff.");
}

// ==== Deteksi Aksi ====
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    /* ======================================================
       🧩 TAMBAH STAFF
    ====================================================== */
    case 'add':
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'staff');

        if ($nama === '' || $username === '' || $password === '') {
            response("error", "⚠️ Semua field wajib diisi.");
        }

        // Pastikan username unik
        $check = $conn2->prepare("SELECT 1 FROM admin_user WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            response("error", "⚠️ Username '$username' sudah digunakan.");
        }
        $check->close();

        // Simpan password polos (tanpa hash)
        $stmt = $conn2->prepare("
            INSERT INTO admin_user (nama_lengkap, username, password, role, last_login)
            VALUES (?, ?, ?, ?, NULL)
        ");
        $stmt->bind_param("ssss", $nama, $username, $password, $role);

        if ($stmt->execute()) {
            response("success", "✅ Staff '$nama' berhasil ditambahkan.");
        } else {
            response("error", "❌ Gagal menambahkan staff: " . $conn2->error);
        }
        $stmt->close();
        break;


    /* ======================================================
       🧩 EDIT STAFF
    ====================================================== */
    case 'edit':
        $id = intval($_POST['id_admin'] ?? 0);
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'staff');

        if (!$id || $nama === '' || $username === '') {
            response("error", "⚠️ Semua field wajib diisi.");
        }

        // Cek username unik
        $check = $conn2->prepare("SELECT 1 FROM admin_user WHERE username = ? AND id_admin != ?");
        $check->bind_param("si", $username, $id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            response("error", "⚠️ Username '$username' sudah digunakan oleh staff lain.");
        }
        $check->close();

        // Kalau password diubah, simpan password baru polos
        if ($password !== '') {
            $stmt = $conn2->prepare("UPDATE admin_user SET nama_lengkap=?, username=?, password=?, role=? WHERE id_admin=?");
            $stmt->bind_param("ssssi", $nama, $username, $password, $role, $id);
        } else {
            // Kalau password kosong, jangan diubah
            $stmt = $conn2->prepare("UPDATE admin_user SET nama_lengkap=?, username=?, role=? WHERE id_admin=?");
            $stmt->bind_param("sssi", $nama, $username, $role, $id);
        }

        if ($stmt->execute()) {
            response("success", "✅ Data staff '$nama' berhasil diperbarui.");
        } else {
            response("error", "❌ Gagal memperbarui data: " . $conn2->error);
        }
        $stmt->close();
        break;


    /* ======================================================
       🧩 HAPUS STAFF
    ====================================================== */
    case 'delete':
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);

        if (!$id) {
            response("error", "⚠️ ID tidak ditemukan.");
        }

        // Tidak boleh hapus diri sendiri
        if ($_SESSION['admin_id'] == $id) {
            response("error", "⚠️ Kamu tidak bisa menghapus akunmu sendiri.");
        }

        $stmt = $conn2->prepare("DELETE FROM admin_user WHERE id_admin = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            response("success", "✅ Staff berhasil dihapus.");
        } else {
            response("error", "❌ Gagal menghapus staff: " . $conn2->error);
        }
        $stmt->close();
        break;


    /* ======================================================
       🧩 DEFAULT
    ====================================================== */
    default:
        response("error", "⚠️ Aksi tidak valid.");
}

$conn2->close();
?>
