<?php
require_once __DIR__ . "../../../koneksi.php";
require_once __DIR__ . "../../../phpqrcode/qrlib.php";

$idbooth = $_GET['idbooth'] ?? null;

if (!$idbooth) die("<p style='color:white;text-align:center;'>❌ ID Booth tidak ditemukan.</p>");

$q = $conn2->query("SELECT * FROM booth WHERE idbooth='$idbooth' LIMIT 1");
if ($q->num_rows === 0) die("<p style='color:white;text-align:center;'>❌ Booth tidak ditemukan.</p>");

$booth = $q->fetch_assoc();
$nama_booth = preg_replace('/[^a-zA-Z0-9_\- ]/', '', $booth['nama_booth']);

# ✅ simpan ke folder /mod/admin/super/qrcodes/
$qrDir = __DIR__ . "/qrcodes/";
if (!is_dir($qrDir)) mkdir($qrDir, 0777, true);

$qrCode = $booth['qr_code'] ?: 'BOOTH-' . $idbooth;
$urlTarget = "http://localhost/openhouse.smbbtelkom.ac.id/mod/user/scan_booth.php?code=" . urlencode($qrCode);

# ✅ path file di server dan URL publiknya
$qrFile = $qrDir . $qrCode . ".png";
$qrWebPath = "http://localhost/openhouse.smbbtelkom.ac.id/mod/admin/super/qrcodes/" . $qrCode . ".png";

if (!file_exists($qrFile)) {
    QRcode::png($urlTarget, $qrFile, QR_ECLEVEL_L, 6);
}

if (empty($booth['qr_code'])) {
    $stmt = $conn2->prepare("UPDATE booth SET qr_code=? WHERE idbooth=?");
    $stmt->bind_param("si", $qrCode, $idbooth);
    $stmt->execute();
}

$downloadName = str_replace(' ', '_', $nama_booth . '_' . $qrCode . '.png');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>QR Booth <?= htmlspecialchars($nama_booth) ?></title>
<style>
body {
    background: #000;
    color: #fff;
    font-family: 'Rajdhani', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
}
h2 {
    color: #ff3b3b;
    margin-bottom: 20px;
}
img {
    width: 280px;
    height: 280px;
    border: 5px solid #ff3b3b;
    border-radius: 12px;
    box-shadow: 0 0 25px rgba(255,0,0,0.5);
}
a {
    margin-top: 25px;
    display: inline-block;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 10px;
    background: linear-gradient(90deg,#ff3b3b,#cc0000);
    transition: 0.3s;
}
a:hover {
    background: linear-gradient(90deg,#ff5555,#ff1111);
}
</style>
</head>
<body>
    <h2>QR Code Booth: <?= htmlspecialchars($nama_booth) ?></h2>
    <img src="<?= $qrWebPath ?>?t=<?= time() ?>" alt="QR Booth">
    <a href="<?= $qrWebPath ?>" download="<?= $downloadName ?>">⬇️ Download QR</a>
</body>
</html>
