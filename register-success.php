<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pendaftaran Berhasil</title>

<!-- ✅ Gunakan path absolut -->
<meta http-equiv="refresh" content="3;url=login">
<link rel="stylesheet" href="templatemo-electric-xtra.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">

<style>
.hero-content {
  text-align: center;
  padding-top: 150px;
  color: #fff;
}
.hero-content h2 {
  font-size: 2rem;
  margin-bottom: 15px;
}
.hero-content p {
  font-size: 1.1rem;
  opacity: 0.85;
}
.cta-button {
  display: inline-block;
  background: #e74646;
  color: #fff;
  padding: 10px 20px;
  border-radius: 10px;
  margin-top: 20px;
  text-decoration: none;
  transition: background 0.3s ease;
}
.cta-button:hover {
  background: #ff7676;
}
</style>
</head>

<body>
<!-- Background Animations -->
<div class="grid-bg"></div>
<div class="gradient-overlay"></div>
<div class="scanlines"></div>

<!-- Shapes -->
<div class="shapes-container">
  <div class="shape shape-circle"></div>
  <div class="shape shape-triangle"></div>
  <div class="shape shape-square"></div>
</div>

<section class="hero">
  <div class="hero-content">
    <h2 class="section-title">✅ Pendaftaran Berhasil!</h2>
    <p>Anda akan diarahkan ke halaman login dalam beberapa detik...</p>
    <a href="login" class="cta-button cta-primary">Ke Halaman Login Sekarang</a>
  </div>
</section>

<!-- Optional: animasi dots kecil -->
<script>
setTimeout(() => {
  window.location.href = "login";
}, 3000);
</script>
</body>
</html>
