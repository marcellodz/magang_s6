<!-- mod/scanner.php -->
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

    <link rel="stylesheet" href="css/templatemo-electric-xtra.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/scanner.css">
    <script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body>
    <!-- Background -->
    <div class="grid-bg"></div>
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
            <a href="index.php" class="logo-link">
                <img src="images/logo-openhouse.png" alt="Logo Telkom University" class="logo-svg">
            </a>
        </div>
    </nav>

    <!-- Main Section -->
    <section class="features" id="features">
        <div class="features-container">
            <center>
                <div class="content-panel">
                    <h3>📷 Scan Booth</h3>
                </div>
            </center>
        </div>

        <div id="loading">Klik tombol di bawah untuk mengaktifkan kamera...</div>
        <div id="reader">
            <div class="scan-laser"></div>
        </div>
        <button id="startScanBtn">🚀 Mulai Scan</button>
        <button id="switchCamBtn" style="display:none; margin-top:10px;">
            🔄 Ganti Kamera
        </button>


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
            resultElement.innerHTML = '<i class="fas fa-satellite-dish"></i> Mengirim data ke server...';

            fetch('process_qr.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'qr_data=' + encodeURIComponent(qrData)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.redirect_url) {
                        resultElement.innerHTML =
                            `<i class="fas fa-check-circle"></i> Sukses! Mengalihkan ke: <b>${data.redirect_url}</b>`;
                        window.location.href = data.redirect_url;
                    } else {
                        resultElement.innerHTML =
                            `<i class="fas fa-exclamation-triangle"></i> ${data.message || 'Respon tidak valid.'}`;
                    }
                })
                .catch(err => {
                    resultElement.innerHTML = '<i class="fas fa-times-circle"></i> Gagal kirim ke server.';
                    console.error(err);
                });
        }

        function onScanSuccess(decodedText) {
            resultElement.innerHTML = `<i class="fas fa-search"></i> <b>${decodedText}</b>`;
            html5QrCode.stop().then(() => sendDataToPHP(decodedText));
        }

        function startScanner(cameraId) {
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(cameraId, config, onScanSuccess)
                .then(() => {
                    resultElement.innerHTML = '<i class="fas fa-camera"></i> Arahkan kamera ke QR Code booth...';
                    loadingElement.style.display = "none";
                })
                .catch(err => {
                    loadingElement.innerHTML =
                        `<i class="fas fa-exclamation-triangle"></i> Gagal membuka kamera: ${err.message}`;
                    console.error(err);
                });
        }

        let isScanning = false;
        let cameraList = [];
        let currentCameraId = null;

        const switchCamBtn = document.getElementById("switchCamBtn");
        startScanBtn.addEventListener("click", () => {
            if (!isScanning) {
                // Kamera belum aktif → AKTIFKAN
                startScanBtn.disabled = true;
                startScanBtn.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Mengaktifkan Kamera...';
                loadingElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengakses kamera perangkat...';

                Html5Qrcode.getCameras().then(devices => {
                    if (devices.length === 0) {
                        resultElement.innerHTML = '<i class="fas fa-exclamation-circle"></i> Tidak ada kamera ditemukan.';
                        return;
                    }

                    const backCam = devices.find(cam =>
                        cam.label.toLowerCase().includes("back") ||
                        cam.label.toLowerCase().includes("rear")
                    );

                    const selectedCam = backCam ? backCam.id : devices[0].id;

                    document.documentElement.dataset.camera = backCam ? "back" : "front";

                    startScanner(selectedCam);

                    // === Kamera ON ===
                    isScanning = true;
                    startScanBtn.disabled = false;
                    startScanBtn.innerHTML = '❌ Matikan Kamera';

                    console.log("🎥 Daftar kamera:", devices);
                    cameraList = devices;
                    currentCameraId = selectedCam;

                    console.log("📸 Kamera aktif:", selectedCam);
                    if (cameraList.length > 1) {
                        switchCamBtn.style.display = "block";
                    }

                }).catch(err => {
                    resultElement.innerHTML = `<i class="fas fa-times-circle"></i> Kamera tidak bisa dibuka: ${err.message}`;
                });

            } else {
                // Kamera lagi aktif → MATIKAN
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();

                    isScanning = false;
                    startScanBtn.innerHTML = '🚀 Mulai Scan';
                    resultElement.innerHTML = 'Menunggu...';
                    loadingElement.style.display = "block";

                }).catch(err => {
                    console.error("Gagal menghentikan kamera:", err);
                });
            }
        });

        async function switchCamera() {
            if (!isScanning || cameraList.length < 2) return;

            const currentIndex = cameraList.findIndex(cam => cam.id === currentCameraId);
            const nextIndex = (currentIndex + 1) % cameraList.length;

            const nextCameraId = cameraList[nextIndex].id;

            // 🔥 Log kamera yang akan dipakai
            console.log("🔄 Switch ke kamera:", nextCameraId);

            await html5QrCode.stop();
            html5QrCode.clear();

            startScanner(nextCameraId);
            currentCameraId = nextCameraId;

            const nextLabel = cameraList[nextIndex].label.toLowerCase();
            document.documentElement.dataset.camera =
                nextLabel.includes("front") ? "front" : "back";

            console.log("🎛 Mode kamera sekarang:", document.documentElement.dataset.camera);
        }

        switchCamBtn.addEventListener("click", () => {
            switchCamBtn.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Switching...';
            switchCamera().then(() => {
                switchCamBtn.innerHTML = '🔄 Ganti Kamera';
            });
        });


    </script>

</body>

</html>