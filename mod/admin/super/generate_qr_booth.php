<?php
// ==================================================
// GENERATE QR BOOTH — FINAL FIX SUBFOLDER XAMPP
// ==================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===============================
// FIX PATH (SUBFOLDER SAFE)
// ===============================
require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../phpqrcode/qrlib.php";

// ===============================
// VALIDASI ID BOOTH
// ===============================
$idbooth = isset($_GET['idbooth']) ? (int) $_GET['idbooth'] : 0;
if ($idbooth <= 0) {
    die("❌ ID Booth tidak valid");
}

// ===============================
// QUERY DATA BOOTH
// ===============================
$stmt = $conn2->prepare("
    SELECT idbooth, nama_booth, qr_code 
    FROM booth 
    WHERE idbooth = ? 
    LIMIT 1
");
$stmt->bind_param("i", $idbooth);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("❌ Booth tidak ditemukan");
}

$booth = $res->fetch_assoc();

// ===============================
// DATA DASAR
// ===============================
$namaBooth = preg_replace('/[^a-zA-Z0-9 _\-]/', '', $booth['nama_booth']);
$qrCode    = !empty($booth['qr_code']) ? $booth['qr_code'] : "BOOTH-" . $idbooth;

// ===============================
// URL YANG AKAN DI-SCAN
// ===============================
$scanUrl = "http://localhost/openhouse.smbbtelkom.ac.id/mod/user/scan_booth.php?code=" . urlencode($qrCode);

// ===============================
// FOLDER QR (PUBLIC, SATU LEVEL DOMAIN)
// ===============================
$qrDir = __DIR__ . "/qrcodes/";
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0755, true);
}

// ===============================
// PATH FILE & URL
// ===============================
$qrFile    = $qrDir . $qrCode . ".png";
$qrWebPath = "../super/qrcodes/" . $qrCode . ".png";

// ===============================
// GENERATE QR
// ===============================
if (!file_exists($qrFile)) {
    QRcode::png($scanUrl, $qrFile, QR_ECLEVEL_L, 8);
}

// ===============================
// SAFETY CHECK
// ===============================
if (!file_exists($qrFile)) {
    die("❌ QR gagal dibuat (permission folder qrcodes)");
}

// ===============================
// SIMPAN QR KE DB (JIKA BARU)
// ===============================
if (empty($booth['qr_code'])) {
    $up = $conn2->prepare("UPDATE booth SET qr_code=? WHERE idbooth=?");
    $up->bind_param("si", $qrCode, $idbooth);
    $up->execute();
}

// ===============================
// NAMA FILE DOWNLOAD
// ===============================
$downloadName = str_replace(' ', '_', $namaBooth . "_" . $qrCode . ".png");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>QR Booth <?= htmlspecialchars($namaBooth) ?></title>

<style>
body{
    margin:0;
    background:#050505;
    color:#fff;
    font-family:Arial,Helvetica,sans-serif;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    height:100vh;
}
h2{
    color:#ff4545;
    margin-bottom:18px;
    text-align:center;
}
img{
    width:300px;
    height:300px;
    background:#fff;
    padding:10px;
    border-radius:16px;
    box-shadow:0 0 30px rgba(255,0,0,.6);
}
a{
    margin-top:24px;
    padding:12px 28px;
    background:linear-gradient(90deg,#ff4545,#cc0000);
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    font-weight:bold;
    transition:.25s;
}
a:hover{
    transform:scale(1.05);
    box-shadow:0 0 18px rgba(255,0,0,.7);
}
</style>
</head>
<body>

<h2>QR Code Booth<br><?= htmlspecialchars($namaBooth) ?></h2>

<img src="<?= $qrWebPath ?>?t=<?= time() ?>" alt="QR Booth">

<a href="<?= $qrWebPath ?>" download="<?= $downloadName ?>">⬇️ Download QR</a>

</body>
</html>
