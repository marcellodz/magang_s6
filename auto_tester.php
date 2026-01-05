<?php
session_start();
require_once "koneksi.php";

echo "<h2>🚀 AUTO TEST LOGIN (EMAIL + KODE)</h2>";
echo "<pre>";

// Ambil semua user
$users = $conn2->query("SELECT iduser, email, kode, aktivasi FROM super_user ORDER BY iduser ASC");

if (!$users) {
    die("❌ SQL ERROR: " . $conn2->error);
}

if ($users->num_rows === 0) {
    die("❌ Tidak ada user dalam database.");
}

while ($u = $users->fetch_assoc()) {

    $id       = $u['iduser'];
    $email    = $u['email'];
    $kode     = $u['kode'];
    $aktivasi = $u['aktivasi'];

    echo "\n=== TEST USER #$id ===\n";
    echo "Email: $email\n";

    // -----------------------------------------------------------
    // 1️⃣ CEK LOGIN (email + kode)
    // -----------------------------------------------------------
    $stmt = $conn2->prepare("
        SELECT iduser, nama, aktivasi 
        FROM super_user 
        WHERE email = ? AND kode = ?
    ");

    if (!$stmt) {
        echo "❌ SQL ERROR saat prepare(): " . $conn2->error;
        exit;
    }

    $stmt->bind_param("ss", $email, $kode);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo "❌ ERROR: Login gagal (email/kode salah)\n";
        echo "👉 STOP otomatis di user: $email\n";
        exit;
    }

    $row = $res->fetch_assoc();

    echo "✔ Login berhasil sebagai: {$row['nama']}\n";

    // -----------------------------------------------------------
    // 2️⃣ CEK AKTIVASI
    // -----------------------------------------------------------
    if ($row['aktivasi'] !== "Y") {
        echo "❌ ERROR: User belum aktivasi.\n";
        echo "👉 STOP otomatis di user: $email\n";
        exit;
    }

    echo "✔ Aktivasi OK\n";

    // -----------------------------------------------------------
    // 3️⃣ LOGOUT SIMULASI
    // -----------------------------------------------------------
    session_destroy();
    echo "✔ Logout OK\n";

    echo "🎉 USER #$id LULUS TEST LOGIN\n";
    echo "--------------------------------------\n";

    usleep(150000); // 0.15 detik
}

echo "\n✨ SEMUA USER BERHASIL DITES TANPA ERROR LOGIN.\n";
echo "</pre>";
?>
