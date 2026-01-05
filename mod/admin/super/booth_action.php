<!-- mod/admin/super/booth_action.php -->

<?php
require_once __DIR__ . "/../../koneksi.php";


$action = $_POST['action'] ?? '';
$id = $_POST['id_booth'] ?? ($_POST['id'] ?? ''); // ✅ fix di sini
$nama_booth = $_POST['nama_booth'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$lantai = $_POST['lantai'] ?? '';

switch ($action) {
    case 'add':
        $stmt = $conn2->prepare("INSERT INTO booth (nama_booth, kategori, lantai) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama_booth, $kategori, $lantai);
        $stmt->execute();

        echo ($stmt->affected_rows > 0)
            ? "✅ Booth berhasil ditambahkan!"
            : "⚠️ Tidak ada data yang ditambahkan.";
        break;

    case 'edit':
        $stmt = $conn2->prepare("UPDATE booth SET nama_booth=?, kategori=?, lantai=? WHERE idbooth=?");
        $stmt->bind_param("sssi", $nama_booth, $kategori, $lantai, $id);
        $stmt->execute();

        echo ($stmt->affected_rows > 0)
            ? "✅ Booth berhasil diperbarui!"
            : "⚠️ Tidak ada perubahan data.";
        break;

    case 'delete':
        if (empty($id)) {
            echo "❌ ID booth tidak valid.";
            exit;
        }

        // 🗑️ Hapus semua data kunjungan terkait booth ini
        $delVisit = $conn2->prepare("DELETE FROM booth_kunjungan WHERE idbooth = ?");
        $delVisit->bind_param("i", $id);
        $delVisit->execute();

        // 🏠 Hapus booth-nya
        $stmt = $conn2->prepare("DELETE FROM booth WHERE idbooth = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "✅ Booth dan semua data kunjungan terkait berhasil dihapus!";
        } else {
            echo "⚠️ Gagal menghapus Booth (mungkin sudah dihapus atau tidak ditemukan).";
        }
        break;


    default:
        echo "⚠️ Aksi tidak dikenal.";
}
?>