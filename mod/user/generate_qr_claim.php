<?php
// ==============================
// FILE: mod/generate_qr_claim.php (Final - Sinkron Reward System Baru)
// ==============================
session_start();
require_once "../koneksi.php";
require_once "../phpqrcode/qrlib.php";

// === VALIDASI SESI USER ===
$iduser = $_SESSION['iduser'] ?? 0;
if (!$iduser) {
    die("<p style='color:white;text-align:center;margin-top:50px;'>❌ Sesi user tidak ditemukan.<br>Silakan login ulang.</p>");
}

// === AMBIL DATA USER ===
$q = $conn2->query("SELECT nama, email FROM super_user WHERE iduser='$iduser' LIMIT 1");
if (!$q || $q->num_rows === 0) {
    die("<p style='color:white;text-align:center;margin-top:50px;'>❌ Data user tidak ditemukan.</p>");
}
$user = $q->fetch_assoc();
$nama = htmlspecialchars($user['nama']);
$email = htmlspecialchars($user['email']);

// === LOAD TARGET DARI CONFIG ===
$configFile = __DIR__ . '/../admin/super/config/reward_config.php';
if (!file_exists($configFile)) {
    $configFile = __DIR__ . '/../admin/super/config/reward_config.php';
}
if (file_exists($configFile)) {
    $rewardConfig = include $configFile;
    $facultyTarget = $rewardConfig['facultyTarget'] ?? 7;
    $otherTarget = $rewardConfig['otherTarget'] ?? 2;
} 
else {
    $facultyTarget = 7;
    $otherTarget = 2;
}

// === HITUNG JUMLAH BOOTH ===
// Fakultas
$qFaculty = $conn2->query("
    SELECT COUNT(DISTINCT b.idbooth) AS total
    FROM booth_kunjungan k
    LEFT JOIN booth b ON k.idbooth = b.idbooth
    WHERE k.iduser='$iduser' AND b.kategori='Booth Fakultas'
");
$facultyVisit = ($qFaculty && $qFaculty->num_rows > 0) ? (int)$qFaculty->fetch_assoc()['total'] : 0;

// Lainnya
$qOther = $conn2->query("
    SELECT COUNT(DISTINCT b.idbooth) AS total
    FROM booth_kunjungan k
    LEFT JOIN booth b ON k.idbooth = b.idbooth
    WHERE k.iduser='$iduser' AND (b.kategori IS NULL OR b.kategori!='Booth Fakultas')
");
$otherVisit = ($qOther && $qOther->num_rows > 0) ? (int)$qOther->fetch_assoc()['total'] : 0;

// === CEK KELAYAKAN ===
$isEligible = ($facultyVisit >= $facultyTarget) && ($otherVisit >= $otherTarget);

// === CEK SUDAH PERNAH KLAIM ===
$cekClaim = $conn2->query("SELECT id FROM reward_claim WHERE iduser='$iduser' LIMIT 1");
$isClaimed = $cekClaim && $cekClaim->num_rows > 0;

// === GENERATE QR CODE ===
$qrDir = "phpqrcode/";
if (!is_dir($qrDir)) mkdir($qrDir, 0777, true);

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
    "://" . $_SERVER['HTTP_HOST'] .
    rtrim(dirname($_SERVER['PHP_SELF']), '/');

// QR mengarah ke halaman verifikasi petugas
$verifyUrl = $baseUrl . "/../admin/staff/verify_claim.php?iduser=" . urlencode($iduser);
$filename = "claim_" . $iduser . ".png";
$filepath = $qrDir . $filename;
if (file_exists($filepath)) unlink($filepath);
QRcode::png($verifyUrl, $filepath, QR_ECLEVEL_H, 6);

// === TEKS DINAMIS ===
$desc = ($otherTarget > 0)
    ? "Kunjungi $facultyTarget booth fakultas dan $otherTarget booth pilihan untuk klaim hadiah."
    : "Kunjungi $facultyTarget booth fakultas untuk klaim hadiah.";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Klaim Hadiah - <?= $nama ?></title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
body {
    background: #ffffff;
    color: #000;
    font-family: 'Figtree', sans-serif;
    text-align: center;
    margin: 0;
    padding: 0;
}
.card {
    max-width: 430px;
    margin: 80px auto 40px;
    /*background: rgba(25,25,25,0.95);*/
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 0 20px rgba(255,0,0,0.25);
    padding: 30px 20px;
}
h2 {
    font-family: 'Orbitron', sans-serif;
    color: #ff3333;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-size: 20px;
}
.desc {
    color: #000;
    font-size: 14px;
    margin-bottom: 10px;
}
.qr-box {
    background: #000;
    display: inline-block;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(255,51,51,0.3);
    margin: 20px 0;
}
.qr-box img {
    width: 260px;
    height: 260px;
    border-radius: 10px;
}
.status {
    padding: 10px;
    border-radius: 10px;
    font-weight: 600;
    margin: 10px auto 15px;
    width: 90%;
    max-width: 360px;
}
.ok {
    background: rgba(0,255,100,0.1);
    color: #51b036;
    border: 1px solid rgba(0,255,100,0.25);
}
.wait {
    background: rgba(255,255,255,0.08);
    color: #ccc;
    border: 1px solid rgba(255,255,255,0.15);
}
.claimed {
    background: rgba(0,191,255,0.1);
    color: #00bfff;
    border: 1px solid rgba(0,191,255,0.25);
}
.info {
    font-size: 13px;
    color: #aaa;
    margin-top: 10px;
}
@media (max-width: 600px) {
    .card {
        margin-top: 60px;
        width: 88%;
        padding: 25px 15px;
    }
    .qr-box img {
        width: 220px;
        height: 220px;
    }
}
</style>
</head>
<body>
<div class="card">
    <!--<h2><i class="fa-solid fa-gift"></i> QR Klaim Hadiah</h2>-->
    <p class="desc"><?= $desc ?></p>

    <div class="qr-box">
        <img src="<?= $filepath ?>?v=<?= time() ?>" alt="QR Klaim Hadiah">
    </div>

    <p><b><?= $nama ?></b><br><small><?= $email ?></small></p>
    <hr style="opacity:0.2;margin:15px 0;">

    <p><b>Booth Fakultas:</b> <?= $facultyVisit ?> / <?= $facultyTarget ?><br>
    <?php if ($otherTarget > 0): ?>
        <b>Tel-U Explore:</b> <?= $otherVisit ?> / <?= $otherTarget ?>
    <?php endif; ?></p>

    <?php if ($isClaimed): ?>
        <div class="status claimed">
            <i class="fa-solid fa-check-circle"></i> Hadiah sudah diklaim.
        </div>
    <?php elseif ($isEligible): ?>
        <div class="status ok">
            <i class="fa-solid fa-unlock"></i> Kamu sudah memenuhi syarat klaim hadiah!<br>Tunjukkan QR ini ke petugas.
        </div>
    <?php else: ?>
        <div class="status wait">
            <i class="fa-solid fa-hourglass-half"></i> Kamu belum memenuhi syarat klaim hadiah.<br>Lengkapi kunjunganmu dulu.
        </div>
    <?php endif; ?>

    <p class="info"><i class="fas fa-eye"></i> QR ini digunakan petugas untuk memverifikasi hadiahmu.</p>
</div>
</body>
</html>
