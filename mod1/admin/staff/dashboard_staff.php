<!-- mod/admin/dashboard_staff.php -->
<div class="staff-info-panel">

  <!-- === SECTION: Default Summary === -->
  <h2 class="panel-title">
    <i class="fas fa-chart-pie"></i> Ringkasan Kehadiran
  </h2>

  <div class="summary-grid">
    <div class="summary-box">
      <h3>Total Pendaftar</h3>
      <p id="sumTotalPeserta">0</p>
    </div>

    <div class="summary-box">
      <h3>Hadir</h3>
      <p id="sumOpeningHadir">0</p>
    </div>

    <div class="summary-box">
      <h3>Belum Hadir</h3>
      <p id="sumTotalHadir">0</p>
    </div>
  </div>

  <!-- === SECTION: Filter Kegiatan === -->
  <h2 class="panel-title" style="margin-top:30px;">
    <i class="fas fa-filter"></i> Informasi Per Kegiatan
  </h2>

  <div class="filter-kegiatan-box">
    <select id="pilihSesi">
      <option value="OC">Opening Ceremony</option>
      <option value="1">Sesi 1</option>
      <option value="2">Sesi 2</option>
      <option value="3">Sesi 3</option>
      <option value="4">Sesi 4</option>
    </select>

    <select id="pilihKegiatan" disabled>
      <option value="Semua">Semua Kegiatan</option>
    </select>
  </div>

<div class="staff-dashboard">
  <div class="staff-header">
    <h2><i class="fas fa-qrcode"></i> Pemindaian QR Peserta</h2>
    <p>Gunakan fitur ini untuk memindai QR peserta dan menandai kehadiran pada kegiatan tertentu.</p>
  </div>

  <div class="staff-actions">
    <button class="btn-scan" id="startScanBtn">
      <i class="fas fa-camera"></i> Mulai Scan QR
    </button>
  </div>

  <div id="loading" class="loading-text">
    <i class="fas fa-info-circle"></i> Klik tombol di atas untuk mengaktifkan kamera...
  </div>

  <!-- Tempat kamera scanner -->
  <div id="reader">
    <div class="scan-laser"></div>
  </div>

  <!-- Hasil peserta (desktop) -->
  <div id="scanResult" class="scan-result hidden"></div>
</div>

<!-- === POPUP MOBILE === -->
<div id="scanPopup" class="scan-popup">
  <div class="popup-card">
    <div class="popup-header">
      <h3 id="popupTitle">Detail Peserta</h3>
      <button class="btn-popup-close" id="closePopup"><i class="fas fa-times"></i></button>
    </div>
    <div class="popup-content" id="popupContent"></div>
  </div>
</div>

<!-- === LIBRARY === -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="staff/js/dashboard.js"></script>


<link rel="stylesheet" href="staff/css/style.css">
<script src="staff/js/script.js" defer></script>


