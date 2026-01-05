<?php
// dashboard.php (versi testing lokal)
session_start();

// ==============================
// MODE TESTING (anggap sudah login)
// ==============================

// Cek kalau belum ada username di session, isi dummy aja
if (!isset($_SESSION['username'])) {
    // Anggap login sebagai user dummy
    $_SESSION['username'] = "Tester Tel-U";
}

// Ambil username dari session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Tel-U Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container py-5">
    <!-- Greeting -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Halo, <?php echo htmlspecialchars($username); ?> 👋</h2>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Judul -->
    <h3 class="mb-4">Dashboard Peserta</h3>

    <!-- Cards -->
    <div class="row">
      <!-- Card 1 -->
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <h5 class="card-title">Stamp Booth</h5>
            <p class="card-text">Scan QR atau dapatkan stamp di booth acara.</p>
            <a href="qr_booth.php" class="btn btn-primary">Masuk</a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <h5 class="card-title">List Booth yang Telah Dikunjungi</h5>
            <p class="card-text">Lihat daftar booth yang sudah kamu kunjungi.</p>
            <a href="list_booth.php" class="btn btn-success">Lihat</a>
          </div>
        </div>
      </div>
    </div>

  </div>

</body>
</html>
