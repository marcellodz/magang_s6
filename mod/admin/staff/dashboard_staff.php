<!-- mod/admin/dashboard_staff.php -->

<!-- === PATCH EDGE AUTOPLAY === -->
<meta http-equiv="Permissions-Policy" content="autoplay=*">
<script>
    console.log("🛡 Permissions-Policy: autoplay=* injected (Edge fix active)");
</script>
<!-- ============================ -->

<div class="staff-info-panel">

  <!-- === SECTION: Default Summary === -->
  <h2 class="panel-title">
    <i class="fas fa-chart-pie"></i> Ringkasan Kehadiran
  </h2>

  <div class="summary-grid">
    <div class="summary-box">
      <h3 id="label1">Total Pendaftar</h3>
      <p id="val1">0</p>
    </div>

    <div class="summary-box">
      <h3 id="label2">Hadir</h3>
      <p id="val2">0</p>
    </div>

    <div class="summary-box">
      <h3 id="label3">Hadir Kegiatan</h3>
      <p id="val3">0</p>
    </div>
  </div>

  <!-- === SECTION: Filter Kegiatan === -->
  <h2 class="panel-title" style="margin-top:30px;">
    <i class="fas fa-filter"></i> Informasi Per Kegiatan
  </h2>

  <div class="filter-kegiatan-box">
    <select id="pilihSesi">
      <option value="Registrasi Awal">Registrasi Awal</option>
      <option value="1">Sesi 1</option>
      <option value="2">Sesi 2</option>
      <option value="3">Sesi 3</option>
      <option value="4">Sesi 4</option>
      <option value="5">Sesi 5</option>
    </select>

    <select id="pilihKegiatan" disabled>
      <option value="Semua">Semua Kegiatan</option>
    </select>
  </div>

  <!-- ====== SCAN SECTION ====== -->
  <div class="staff-dashboard">
    <div class="staff-header">
      <h2><i class="fas fa-qrcode"></i> Pemindaian QR Peserta</h2>
      <p>Gunakan fitur ini untuk memindai QR peserta.</p>
    </div>

    <a href="staff/scanner_staff.php" id="openScannerBtn" class="btn-scan">
        <i class="fas fa-camera"></i> Mulai Scan QR
    </a>
  </div>

</div>


<!-- LIBRARIES -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="staff/js/dashboard.js"></script>
<link rel="stylesheet" href="staff/css/style.css">

<script>
console.log("🛰 STAFF dashboard loaded");


</script>
