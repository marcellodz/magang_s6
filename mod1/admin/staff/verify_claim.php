<?php
// ==============================
// FILE: mod/admin/verify_claim.php (v6 - Sinkron Session Login)
// ==============================
require_once "../../koneksi.php";
if (session_status() === PHP_SESSION_NONE) session_start();

// 🔐 Validasi staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
  echo "<p style='color:red;text-align:center;margin-top:100px;'>❌ Akses ditolak.</p>";
  exit;
}

// Ambil ID User
$iduser = $_GET['iduser'] ?? ($_POST['iduser'] ?? '');
if (!$iduser)
  die("<h3 style='color:red;text-align:center;margin-top:100px;'>❌ ID peserta tidak ditemukan.</h3>");

// === CONFIG ===
$configFile = __DIR__ . '/../super/config/reward_config.php';
if (file_exists($configFile)) {
  $rewardConfig = include $configFile;
  $facultyTarget = $rewardConfig['facultyTarget'] ?? 7;
  $otherTarget   = $rewardConfig['otherTarget'] ?? 2;
} else {
  $facultyTarget = 7;
  $otherTarget   = 2;
}

// === DATA PESERTA ===
$qUser = $conn2->query("SELECT nama FROM super_user WHERE iduser='$iduser' LIMIT 1");
if (!$qUser || $qUser->num_rows === 0)
  die("<h3 style='color:red;text-align:center;margin-top:100px;'>❌ Peserta tidak ditemukan.</h3>");
$nama = htmlspecialchars($qUser->fetch_assoc()['nama']);

// === HITUNG BOOTH ===
$qFaculty = $conn2->query("
  SELECT COUNT(DISTINCT b.idbooth) AS total
  FROM booth_kunjungan k
  LEFT JOIN booth b ON k.idbooth=b.idbooth
  WHERE k.iduser='$iduser' AND b.kategori='Booth Fakultas'
");
$faculty = ($qFaculty && $qFaculty->num_rows > 0) ? (int)$qFaculty->fetch_assoc()['total'] : 0;

$qOther = $conn2->query("
  SELECT COUNT(DISTINCT b.idbooth) AS total
  FROM booth_kunjungan k
  LEFT JOIN booth b ON k.idbooth=b.idbooth
  WHERE k.iduser='$iduser' AND (b.kategori IS NULL OR b.kategori!='Booth Fakultas')
");
$other = ($qOther && $qOther->num_rows > 0) ? (int)$qOther->fetch_assoc()['total'] : 0;

// === KELAYAKAN ===
$isEligible = ($faculty >= $facultyTarget) && ($other >= $otherTarget);
$isClaimed = ($conn2->query("SELECT id FROM reward_claim WHERE iduser='$iduser' LIMIT 1")->num_rows > 0);

// === MODE AJAX ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');

  if (!$isEligible)
    exit(json_encode(['success' => false, 'msg' => 'Peserta belum memenuhi syarat klaim hadiah.']));
  if ($isClaimed)
    exit(json_encode(['success' => false, 'msg' => 'Peserta sudah pernah diklaim sebelumnya.']));

  // 🔧 Gunakan session yang sama dengan login.php
  $staff_id = $_SESSION['admin_id'] ?? 0;
  $now = date('Y-m-d H:i:s');

  $ok = $conn2->query("
    INSERT INTO reward_claim (iduser, staff_id, waktu_klaim, status)
    VALUES ('$iduser', '$staff_id', '$now', 'approved')
  ");

  exit(json_encode([
    'success' => $ok,
    'msg' => $ok
      ? "✅ Klaim hadiah berhasil disimpan untuk $nama oleh staff ID #$staff_id."
      : "❌ Gagal menyimpan data klaim."
  ]));
}
?>

<!-- === FONT AWESOME === -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- === UI CARD === -->
<div class="reward-verify-card">
  <h2><i class="fa-solid fa-gift"></i> Verifikasi Klaim Hadiah</h2>

  <div class="reward-meta">
    <p><strong>Nama:</strong> <?= $nama ?></p>
    <p><strong>Booth Fakultas:</strong> <?= $faculty ?>/<?= $facultyTarget ?></p>
    <?php if ($otherTarget > 0): ?>
      <p><strong>Booth Lainnya:</strong> <?= $other ?>/<?= $otherTarget ?></p>
    <?php endif; ?>
  </div>

  <hr class="divider">

  <?php if ($isClaimed): ?>
    <div class="reward-status claimed">
      <i class="fa-solid fa-check-circle"></i> Hadiah sudah diklaim.
    </div>
  <?php elseif (!$isEligible): ?>
    <div class="reward-status wait">
      <i class="fa-solid fa-hourglass-half"></i> Belum memenuhi syarat klaim.
    </div>
  <?php else: ?>
    <div class="reward-status ready">
      <i class="fa-solid fa-unlock"></i> Peserta memenuhi syarat klaim!
    </div>
    <button class="btn-confirm-claim" data-iduser="<?= $iduser ?>" data-nama="<?= htmlspecialchars($nama) ?>">
      <i class="fa-solid fa-check"></i> Konfirmasi Klaim
    </button>
  <?php endif; ?>
</div>

<style>
body {
  background: #0b0b0b;
  color: #fff;
  font-family: 'Rajdhani', sans-serif;
  text-align: center;
  margin: 0;
  padding: 30px 15px;
}
.reward-verify-card {
  background: rgba(25, 25, 25, 0.95);
  border-radius: 12px;
  padding: 25px 20px;
  display: inline-block;
  width: 100%;
  max-width: 420px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 0 18px rgba(255, 0, 0, 0.25);
  animation: fadeIn 0.3s ease-out;
}
.reward-verify-card h2 {
  color: #ff3333;
  font-size: 20px;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 15px;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.reward-verify-card h2 i { color: #ff5555; }
.reward-meta p {
  color: #ddd;
  font-size: 15px;
  margin: 4px 0;
}
.divider {
  border: none;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  margin: 15px 0;
}
.reward-status {
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  margin-top: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
.reward-status.ready { background: rgba(0,255,100,0.1); color:#00ff8f; border:1px solid rgba(0,255,100,0.2); }
.reward-status.wait { background: rgba(255,255,255,0.08); color:#ccc; border:1px solid rgba(255,255,255,0.15); }
.reward-status.claimed { background: rgba(0,191,255,0.1); color:#00bfff; border:1px solid rgba(0,191,255,0.2); }
.btn-confirm-claim {
  background: #ff3333;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 18px;
  transition: all 0.25s ease;
  width: 100%;
  max-width: 240px;
}
.btn-confirm-claim:hover { background: #ff5555; transform: scale(1.03); }
@media (max-width: 600px) {
  .reward-verify-card { padding: 20px 15px; max-width: 90%; }
  .reward-verify-card h2 { font-size: 18px; }
  .reward-meta p, .reward-status, .btn-confirm-claim { font-size: 14px; }
}
@keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
</style>
