<!-- mod/admin/staff_content.php -->

<?php
require_once __DIR__ . "/../../koneksi.php";


// Tentukan mode
$mode = $_GET['mode'] ?? 'presensi';

if ($mode === 'claim') {
  // =======================
  // MODE KLAIM HADIAH (LOKAL)
  // =======================
  $iduser = $_GET['iduser'] ?? '';
  $iduser = preg_replace('/\D/', '', $iduser);

  if (empty($iduser)) {
    echo "<p style='color:#ff6666;text-align:center;'>ID peserta tidak valid.</p>";
    exit;
  }

  // Ambil konten HTML dari verify_claim.php lokal
  $verifyUrl = __DIR__ . "/verify_claim.php?iduser=" . urlencode($iduser);
  ob_start();
  include $verifyUrl;
  $claimHTML = ob_get_clean();

  if (!$claimHTML) {
    echo "<p style='color:#ff5555;text-align:center;'>Gagal memuat data klaim hadiah.</p>";
    exit;
  }
  ?>

  <!-- === VERIFIKASI KLAIM HADIAH === -->
  <div class="claim-box">
    <div class="claim-header">
      <h3><i class="fas fa-gift"></i> Verifikasi Klaim Hadiah</h3>
      <!-- Tombol tutup untuk klaim -->
      <button class="btn-close-claim" onclick="closeScanResult()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="claim-body">
      <?= $claimHTML ?>
    </div>

    <?php
    exit;
}

// =======================
// MODE PRESENSI (DEFAULT)
// =======================
$iduser = $_GET['iduser'] ?? '';
$iduser = preg_replace('/\D/', '', $iduser);

if (empty($iduser)) {
  echo "<p style='color:#ff5555;'>ID peserta tidak valid.</p>";
  exit;
}

$peserta = $conn2->query("SELECT * FROM super_user WHERE iduser = '$iduser'")->fetch_assoc();

if (!$peserta) {
  echo "<p style='color:#ff6666;'>Data peserta tidak ditemukan.</p>";
  exit;
}

$query = $conn2->query("
    SELECT 
        k.id_kegiatan,
        k.nama_kegiatan,
        k.waktu_kegiatan,
        p.status,
        p.waktu_presensi
    FROM kegiatan_peserta k
    LEFT JOIN presensi_peserta p 
        ON k.id_kegiatan = p.id_kegiatan 
        AND k.iduser = p.iduser
    WHERE k.iduser = '$iduser'
");
?>

  <!-- === DATA PESERTA === -->
  <div id="scan-content">
    <div class="participant-info">
      <h3><i class="fas fa-user"></i> Data Peserta</h3>
      <div class="info-grid">
        <div><strong>Nama:</strong> <?= htmlspecialchars($peserta['nama']); ?></div>
        <div><strong>Email:</strong> <?= htmlspecialchars($peserta['email']); ?></div>
        <div><strong>Sekolah:</strong> <?= htmlspecialchars($peserta['sekolah']); ?></div>
        <div><strong>No HP:</strong> <?= htmlspecialchars($peserta['hp'] ?? '-'); ?></div>
      </div>
    </div>

    <div class="kegiatan-list">
      <h3><i class="fas fa-calendar-alt"></i> Daftar Kegiatan</h3>
      <table>
        <thead>
          <tr>
            <th>Nama Kegiatan</th>
            <th>Waktu</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($query->num_rows > 0): ?>
            <?php while ($row = $query->fetch_assoc()):
              $isHadir = ($row['status'] ?? 'Belum Hadir') === 'Hadir';
              ?>
              <tr id="row-<?= $row['id_kegiatan']; ?>">
                <td><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
                <td><?= htmlspecialchars($row['waktu_kegiatan']); ?></td>
                <td style="text-align:center;">
                  <span class="status <?= strtolower(str_replace(' ', '-', $row['status'] ?? 'belum-hadir')); ?>">
                    <?= $row['status'] ?? 'Belum Hadir'; ?>
                  </span>
                </td>
                <td>
                  <button class="btn-hadir <?= $isHadir ? 'active' : ''; ?>"
                    onclick="toggleHadir(<?= $row['id_kegiatan']; ?>, <?= $iduser; ?>, this)">
                    <i class="fas <?= $isHadir ? 'fa-undo' : 'fa-check'; ?>"></i>
                    <?= $isHadir ? 'Batalkan' : 'Hadir'; ?>
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" style="text-align:center;color:#ccc;">Belum ada kegiatan terdaftar.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <!-- Tombol daftar OTS -->
      <div class="daftar-ots-container" style="text-align:center;margin-top:15px;">
        <button class="btn-ots" onclick="daftarOTS(<?= $iduser ?>)">
          <i class="fas fa-walking"></i> Daftarkan ke Campus Tour OTS
        </button>
      </div>
      <!-- Tombol tutup (desktop only) -->
      <div class="close-desktop" style="text-align:center;">
        <button class="btn-close" onclick="closeScanResult()">
          <i class="fas fa-times"></i> Tutup
        </button>
      </div>

    </div>
  </div>

<script src="/openhouse.smbbtelkom.ac.id/mod/admin/staff/js/content.js" defer></script>
<link rel="stylesheet" href="/openhouse.smbbtelkom.ac.id/mod/admin/staff/css/content.css">

