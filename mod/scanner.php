<?php
session_start(); // Wajib: untuk mengakses variabel sesi

// Cek login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $redirect_url = "https://openhouse.smbbtelkom.ac.id/login";
    echo "<script>
        alert('Silahkan login terlebih dahulu.');
        window.location.href = '$redirect_url';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Open House Telkom University</title>

<link href="templatemo-electric-xtra.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
/* === STYLE SESUAI UI AWAL KAMU === */
#reader {
    width: 100%;
    max-width: 500px;
    margin: 20px auto;
    border: 2px solid #ff6363;
    padding: 10px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.45);
    box-shadow: 0 0 15px rgba(255, 99, 99, 0.3);
    position: relative;
    overflow: hidden;
}
#result-container {
    text-align: center;
    margin-top: 20px;
    font-size: 1.2em;
    color: #fff;
}
#startScanBtn {
    display: block;
    margin: 25px auto;
    background: linear-gradient(90deg, #ff6363, #ff1e56);
    color: white;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    cursor: pointer;
    transition: 0.25s;
}
#startScanBtn:hover {
    transform: scale(1.05);
    background: linear-gradient(90deg, #ff1e56, #ff6363);
}
#loading {
    text-align: center;
    color: #ffb703;
    font-size: 1rem;
    margin-top: 15px;
}

/* === FIX KAMERA SUPAYA PAS DI FRAME === */
#reader video {
    object-fit: cover !important;
    width: 100% !important;
    height: 100% !important;
    border-radius: 10px;
    transform: scaleX(-1);
}
html[data-camera="back"] #reader video {
    transform: none;
}
#reader__scan_region {
    border-radius: 10px;
    overflow: hidden;
}
#reader__dashboard_section_csr button {
    display: none !important;
}

/* === EFEK SCAN LASER MERAH === */
.scan-laser {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, transparent, #ff1e56, transparent);
    animation: scanMove 2s linear infinite;
    opacity: 0.8;
}
@keyframes scanMove {
    0% { top: 0; }
    50% { top: calc(100% - 3px); }
    100% { top: 0; }
}
</style>
</head>

<body>
<!-- Background -->
<div class="grid-bg"></div>
<div class="gradient-overlay"></div>
<div class="scanlines"></div>
<div class="shapes-container">
    <div class="shape shape-circle"></div>
    <div class="shape shape-triangle"></div>
    <div class="shape shape-square"></div>
</div>
<div id="particles"></div>

<!-- Navbar -->
<nav id="navbar">
    <div class="nav-container">
        <a href="index" class="logo-link">
            <img src="images/asset-telu.png" alt="" class="logo-svg">
            <span class="logo-text">OPEN HOUSE TELKOM UNIVERSITY</span>
        </a>
    </div>
</nav>

<!-- Main Section -->
<section class="features" id="features">
    <div class="features-container">
        <center><div class="content-panel"><h3>📷 Scan Booth</h3></div></center>
    </div>

    <div id="loading">Klik tombol di bawah untuk mengaktifkan kamera...</div>
    <div id="reader">
        <div class="scan-laser"></div>
    </div>
    <button id="startScanBtn">🚀 Mulai Scan</button>

    <div id="result-container">
        <p>Hasil Pemindaian:</p>
        <strong id="result">Menunggu...</strong>
    </div>
</section>

<footer>
    <div class="footer-content">
        <p class="copyright">© 2025 ELECTRIC XTRA. All rights reserved.</p>
    </div>
</footer>

<script>
const html5QrCode = new Html5Qrcode("reader");
const resultElement = document.getElementById("result");
const startScanBtn = document.getElementById("startScanBtn");
const loadingElement = document.getElementById("loading");

function sendDataToPHP(qrData) {
    resultElement.textContent = '📡 Mengirim data ke server...';
    fetch('process_qr.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'qr_data=' + encodeURIComponent(qrData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            resultElement.innerHTML = `✅ Sukses! Mengalihkan ke: <b>${data.redirect_url}</b>`;
            window.location.href = data.redirect_url;
        } else {
            resultElement.innerHTML = `⚠️ ${data.message || 'Respon tidak valid.'}`;
        }
    })
    .catch(err => {
        resultElement.innerHTML = '❌ Gagal kirim ke server.';
        console.error(err);
    });
}

function onScanSuccess(decodedText) {
    resultElement.innerHTML = `<b>${decodedText}</b>`;
    html5QrCode.stop().then(() => sendDataToPHP(decodedText));
}

function startScanner(cameraId) {
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    html5QrCode.start(cameraId, config, onScanSuccess)
        .then(() => {
            resultElement.textContent = "📷 Arahkan kamera ke QR Code booth...";
            loadingElement.style.display = "none";
        })
        .catch(err => {
            loadingElement.innerHTML = "⚠️ Gagal membuka kamera: " + err.message;
            console.error(err);
        });
}

// Trigger manual (Chrome memerlukan user action)
startScanBtn.addEventListener("click", () => {
    startScanBtn.disabled = true;
    startScanBtn.textContent = "🔄 Mengaktifkan Kamera...";
    loadingElement.textContent = "Mengakses kamera perangkat...";

    Html5Qrcode.getCameras().then(devices => {
        if (devices.length === 0) {
            resultElement.textContent = "⚠️ Tidak ada kamera ditemukan.";
            return;
        }

        const backCam = devices.find(cam =>
            cam.label.toLowerCase().includes("back") ||
            cam.label.toLowerCase().includes("rear")
        );
        const selectedCam = backCam ? backCam.id : devices[0].id;

        document.documentElement.dataset.camera = backCam ? "back" : "front";
        startScanner(selectedCam);
    }).catch(err => {
        resultElement.textContent = "❌ Tidak bisa akses kamera: " + err.message;
        console.error(err);
    });
});
</script>
</body>
</html>
