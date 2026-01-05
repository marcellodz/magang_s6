<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    echo "<script>alert('Akses ditolak.'); window.location.href='../index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" href="../../images/telu-logo.png" type="image/png">
    <title>Staff Scanner - Open House Telkom University</title>

    <meta http-equiv="Permissions-Policy" content="autoplay=*">

    <style>
        body {
    margin: 0;
    font-family: "Figtree", Arial, sans-serif;
    background: #050505;
    color: white;
    text-align: center;
}

.topbar {
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    background: rgba(20, 20, 20, 0.55);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(255, 60, 60, 0.35);
}

.back-btn {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(30, 30, 30, 0.65);
    padding: 8px 10px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    backdrop-filter: blur(6px);
}

.back-btn svg {
    width: 22px;
    height: 22px;
    stroke: #ff3b3b;
}

.title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 22px;
}

.title svg {
    stroke: #ff3b3b;
    width: 22px;
    height: 22px;
}

.top-divider {
    height: 3px;
    width: 100%;
    background: linear-gradient(90deg, transparent, #ff3b3b, transparent);
    margin-bottom: 24px;
}

/* WRAPPER SCANNER */
#reader-container {
    display: none;
    opacity: 0;
    transform: scale(.96);
    transition: .35s ease;
}

#reader-container.active {
    display: block;
    opacity: 1;
    transform: scale(1);
}

/* FIX PALING PENTING: LOCK HEIGHT, BUAT ASPECT-RATIO TETAP */
#reader {
    width: 100%;
    max-width: 440px;
    margin: 0 auto;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 60, 60, 0.28);

    /* FIX “kamera mengecil setelah scan” */
    aspect-ratio: 1 / 1;
    height: auto;
    min-height: 340px; /* stabil banget untuk semua iPhone & Android */
    overflow: hidden;
    padding: 0 !important;
    position: relative;
}

/* html5-qrcode inject <div> → kita paksa ikut ukuran parent */
#reader > div {
    width: 100% !important;
    height: 100% !important;
}

/* VIDEO FIX FINAL */
#reader video {
    position: absolute;
    inset: 0;
    width: 100% !important;
    height: 100% !important;

    /* HARUS COVER → supaya QR tetap tajem */
    object-fit: cover !important;

    /* HILANGKAN TRANSISI SUPAYA GA GLITCH */
    transition: none !important;

    /* Background hitam supaya tidak transparan */
    background: black;
}

#startScanBtn {
    margin: 20px auto;
    padding: 14px 30px;
    font-weight: 700;
    font-size: 16px;
    background: linear-gradient(90deg, #ff4545, #cc0000);
    color: white;
    border-radius: 12px;
    border: none;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

#switchCamBtn {
    display: none;
    margin: 10px auto;
    padding: 12px 26px;
    border-radius: 10px;
    background: linear-gradient(90deg, #ff4545, #d40000);
    color: white;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

#resultBox {
    margin-top: 22px;
    font-size: 17px;
    color: rgba(255, 255, 255, 0.85);
    min-height: 40px;
}

.mirror-wrapper {
    transform: scaleX(-1) !important;
}

    </style>

    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
</head>

<body>

    <div class="topbar">
        <button onclick="window.location.href='../index.php?scan=success'" class="back-btn">
            <svg fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
        </button>

        <div class="title">
            <svg fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3l2-3h8l2 3h3a2 2 0 0 1 2 2z" />
                <circle cx="12" cy="13" r="4" />
            </svg>
            Staff Scanner
        </div>
    </div>

    <div class="top-divider"></div>

    <p>Arahkan kamera ke QR peserta.</p>

    <div id="reader-container">
        <div id="reader"></div>
    </div>

    <button id="startScanBtn">
        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <polygon points="5 3 19 12 5 21 5 3"></polygon>
        </svg>
        Mulai Scan
    </button>

    <button id="switchCamBtn">
        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <polyline points="1 4 1 10 7 10"></polyline>
            <polyline points="23 20 23 14 17 14"></polyline>
            <path d="M20.49 9A9 9 0 0 0 6.21 4.56L1 10"></path>
            <path d="M3.51 15A9 9 0 0 0 17.79 19.44L23 14"></path>
        </svg>
        Ganti Kamera
    </button>

    <div id="resultBox">Menunggu scan…</div>

    <script>
        console.log("🚀 Orion Glass Scanner TURBO Loaded (adaptive speed)");

        /* ==========================================================
           ELEMEN DOM & STATE GLOBAL
        ========================================================== */
        const startBtn = document.getElementById("startScanBtn");
        const switchBtn = document.getElementById("switchCamBtn");
        const resultBox = document.getElementById("resultBox");
        const readerContainer = document.getElementById("reader-container");
        const readerWrapper = document.getElementById("reader");

        const html5QrCode = new Html5Qrcode("reader");

        const ScannerState = {
            cameras: [],
            activeCameraId: null,
            isScanning: false,
            isStarting: false,
            isStopping: false,
            lastDecodedText: null,
            lastScanAt: 0
        };

        /* ==========================================================
           POPUP GENERIC (untuk OTS, klaim, dsb)
        ========================================================== */
        function showPopup(title, message, onConfirm) {
            const overlay = document.getElementById("popup-ots");
            if (!overlay) {
                alert(title + " - " + message);
                if (typeof onConfirm === "function") onConfirm();
                return;
            }

            document.getElementById("popup-title").textContent = title;
            document.getElementById("popup-message").textContent = message;

            overlay.classList.add("show");

            const cancelBtn = document.getElementById("popup-cancel");
            const okBtn = document.getElementById("popup-ok");

            if (cancelBtn) {
                cancelBtn.onclick = () => {
                    overlay.classList.remove("show");
                };
            }

            if (okBtn) {
                okBtn.onclick = () => {
                    overlay.classList.remove("show");
                    if (typeof onConfirm === "function") onConfirm();
                };
            }
        }

        /* ==========================================================
           FUNGSI PRESENSI / HADIR / BATALKAN / OTS / KLAIM
        ========================================================== */
        function toggleHadir(id_kegiatan, iduser, btnElement) {
            const isActive = btnElement.classList.contains("active");
            const action = isActive ? "belum" : "hadir";

            const url = `update_presensi.php?iduser=${iduser}&id_kegiatan=${id_kegiatan}&action=${action}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || "Gagal update presensi.");
                        return;
                    }

                    if (action === "hadir") {
                        btnElement.classList.add("active");
                        btnElement.innerHTML = `<i class="fas fa-undo"></i> Batalkan`;
                    } else {
                        btnElement.classList.remove("active");
                        btnElement.innerHTML = `<i class="fas fa-check"></i> Hadir`;
                    }

                    showPresensiContent(iduser);
                })
                .catch(err => {
                    console.error("ERR", err);
                    alert("Koneksi gagal.");
                });
        }

        async function daftarOTS(iduser) {
            const cek = await fetch("daftar_ots.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `iduser=${iduser}&mode=check`
            }).then(r => r.json());

            if (cek.status === "already") {
                showPopup(
                    "Sudah Terdaftar",
                    `Peserta sudah terdaftar di ${cek.sesi}.`,
                    null
                );
                return;
            }

            showPopup(
                "Daftarkan OTS?",
                `Daftarkan peserta ke ${cek.sesiTujuan}?`,
                () => {
                    resultBox.innerHTML = "Mendaftarkan peserta…";

                    fetch("daftar_ots.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `iduser=${iduser}`
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status !== "success") {
                                showPopup("Gagal", data.message);
                                return;
                            }

                            showPopup("Berhasil", data.message);
                            showPresensiContent(iduser);
                        });
                }
            );
        }

        async function showPresensiContent(id) {
            const url = `staff_content.php?iduser=${id}`;
            const res = await fetch(url);
            resultBox.innerHTML = `<div class="content-box">${await res.text()}</div>`;
        }

        async function showClaimContent(url) {
            const res = await fetch(url, { credentials: "include" });
            const html = await res.text();

            resultBox.innerHTML = `<div class="content-box">${html}</div>`;
            bindClaimButton();
        }

        function bindClaimButton() {
            const btn = document.querySelector(".btn-confirm-claim");
            if (!btn) return;

            btn.onclick = () => {
                const iduser = btn.dataset.iduser;
                const nama = btn.dataset.nama;

                showPopup(
                    "Konfirmasi Klaim?",
                    `Konfirmasi klaim hadiah untuk ${nama}?`,
                    () => submitKlaim(iduser)
                );
            };
        }

        function submitKlaim(iduser) {
            fetch("verify_claim.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `iduser=${iduser}`
            })
                .then(r => r.json())
                .then(async data => {
                    showPopup("Klaim Hadiah", data.msg);

                    const updated = await fetch(`staff_content.php?mode=claim&iduser=${iduser}`)
                        .then(r => r.text());

                    resultBox.innerHTML = `<div class="content-box">${updated}</div>`;
                });
        }

        function closeClaimPopup() {
            const el = document.getElementById("popup-claim");
            if (el) el.classList.remove("show");
        }

        /* ==========================================================
           UTIL: DETEKSI MODE QR
        ========================================================== */
        function detectMode(payload) {
            if (payload.includes("verify_claim.php") || payload.includes("token="))
                return { mode: "claim", url: payload.trim() };

            try {
                const obj = JSON.parse(payload);
                if (obj.id) return { mode: "presensi", payload: obj };
            } catch (e) { }

            return { mode: "unknown" };
        }

        /* ==========================================================
           UTIL: SHOW / HIDE SCANNER BOX
        ========================================================== */
        function showScannerBox() {
            readerContainer.style.display = "block";
            requestAnimationFrame(() => {
                readerContainer.classList.add("active");
            });
        }

        function hideScannerBox() {
            readerContainer.classList.remove("active");
            setTimeout(() => {
                readerContainer.style.display = "none";
            }, 220);
        }

        async function closeScanResult() {
            resultBox.style.opacity = "0";

            setTimeout(() => {
                resultBox.innerHTML = "Menunggu scan…";
                resultBox.style.opacity = "1";
            }, 200);

            await stopScanning({ hideBox: true, reasonText: "Menunggu scan…" });
        }

        /* ==========================================================
           UTIL: MIRROR KAMERA BERDASARKAN LABEL
        ========================================================== */
        function isFrontCameraGuess(cam) {
            const label = (cam.label || "").toLowerCase();
            return (
                label.includes("front") ||
                label.includes("depan") ||
                label.includes("user") ||
                label.includes("selfie")
            );
        }

        function applyMirrorForCamera(cam) {
            if (!readerWrapper) return;
            const isFront = isFrontCameraGuess(cam);
            readerWrapper.classList.toggle("mirror-wrapper", isFront);
        }

        /* ==========================================================
           CONFIG TURBO: FPS & QRBOX ADAPTIF + NATIVE DECODER
        ========================================================== */
        function buildScannerConfig() {
            const ua = navigator.userAgent || "";
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);

            // fps cukup tinggi buat cepat, tapi nggak bikin HP kepanasan
            const fps = isMobile ? 18 : 15;

            // QRBox: kotak di tengah, 50–60% sisi terkecil → lebih kecil = lebih cepat
            const qrboxFn = function (viewW, viewH) {
                const minEdge = Math.min(viewW, viewH);
                const size = Math.floor(minEdge * (isMobile ? 0.6 : 0.5));
                return { width: size, height: size };
            };

            const config = {
                fps,
                qrbox: qrboxFn,
                // aspek rasio 16:9 di mobile bikin stream lebih efisien
                aspectRatio: isMobile ? 1.7777778 : undefined,
                // jangan matikan flip, biar QR dari kamera depan tetap aman
                disableFlip: false,
                // pakai native BarcodeDetector kalau ada → decode bisa < 10ms/frame
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            };

            // Batasi hanya QR_CODE kalau objek global-nya ada
            if (window.Html5QrcodeSupportedFormats && Html5QrcodeSupportedFormats.QR_CODE) {
                config.formatsToSupport = [Html5QrcodeSupportedFormats.QR_CODE];
            }

            return config;
        }

        /* ==========================================================
           INISIALISASI KAMERA
        ========================================================== */
        async function initCamerasIfNeeded() {
            if (ScannerState.cameras.length > 0 && ScannerState.activeCameraId) return;

            try {
                resultBox.innerHTML = "Mengakses kamera…";
                const devices = await Html5Qrcode.getCameras();

                if (!devices || devices.length === 0) {
                    resultBox.innerHTML = "Tidak ditemukan kamera di perangkat ini.";
                    startBtn.disabled = true;
                    switchBtn.style.display = "none";
                    return;
                }

                ScannerState.cameras = devices;

                const backCam = devices.find(c => !isFrontCameraGuess(c));
                const chosen = backCam || devices[0];

                ScannerState.activeCameraId = chosen.id;
                applyMirrorForCamera(chosen);

                // Tombol ganti kamera hanya ditampilkan saat sedang scan
                switchBtn.style.display = "none";

            } catch (err) {
                console.error("getCameras error:", err);
                resultBox.innerHTML = "Gagal mengakses daftar kamera. Cek izin akses kamera di browser.";
            }
        }

        /* ==========================================================
           START / STOP SCANNING (ULTRA STABLE + TURBO)
        ========================================================== */
        async function startScanning() {
            if (ScannerState.isScanning || ScannerState.isStarting) return;

            ScannerState.isStarting = true;
            startBtn.disabled = true;
            switchBtn.disabled = true;

            await initCamerasIfNeeded();
            if (!ScannerState.activeCameraId) {
                ScannerState.isStarting = false;
                startBtn.disabled = false;
                switchBtn.disabled = false;
                return;
            }

            const activeCam = ScannerState.cameras.find(c => c.id === ScannerState.activeCameraId) || ScannerState.cameras[0];
            applyMirrorForCamera(activeCam);

            showScannerBox();
            resultBox.innerHTML = "Membuka kamera…";

            const config = buildScannerConfig();

            try {
                await html5QrCode.start(
                    ScannerState.activeCameraId,
                    config,
                    handleDecodeSuccess,
                    handleDecodeError
                );

                ScannerState.isScanning = true;
                resultBox.innerHTML = "Arahkan kamera ke QR…";

                // Tombol ganti kamera hanya saat scan + kalau ada lebih dari 1 kamera
                if (ScannerState.cameras.length > 1) {
                    switchBtn.style.display = "inline-block";
                    switchBtn.disabled = false;
                } else {
                    switchBtn.style.display = "none";
                }

                startBtn.innerHTML = `
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="5" width="10" height="10"></rect>
                    </svg>
                    Hentikan Scan
                `;
            } catch (err) {
                console.error("startCamera error:", err);
                hideScannerBox();
                resultBox.innerHTML = "Kamera gagal dibuka. Cek izin akses kamera di browser.";
                ScannerState.isScanning = false;
            } finally {
                ScannerState.isStarting = false;
                startBtn.disabled = false;
                // switchBtn diaktifkan hanya kalau lagi scanning
                if (!ScannerState.isScanning) {
                    switchBtn.style.display = "none";
                }
            }
        }

        async function stopScanning(options = {}) {
            const { hideBox = true, reasonText = "Scan dihentikan." } = options;

            if ((!ScannerState.isScanning && !ScannerState.isStarting) || ScannerState.isStopping) return;

            ScannerState.isStopping = true;
            startBtn.disabled = true;
            switchBtn.disabled = true;

            try {
                if (ScannerState.isScanning) {
                    await html5QrCode.stop();
                }
            } catch (err) {
                console.warn("Stop scanner error:", err);
            } finally {
                ScannerState.isScanning = false;
                ScannerState.isStopping = false;

                if (hideBox) {
                    hideScannerBox();
                }

                resultBox.innerHTML = reasonText;

                startBtn.innerHTML = `
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    Mulai Scan
                `;

                startBtn.disabled = false;
                // setelah stop, tombol ganti kamera disembunyikan
                switchBtn.style.display = "none";
                switchBtn.disabled = false;
            }
        }

        /* ==========================================================
           HANDLER DECODE
        ========================================================== */
        async function handleDecodeSuccess(decodedText /*, decodedResult */) {
            const now = Date.now();

            // Anti multi-trigger
            if (decodedText === ScannerState.lastDecodedText && (now - ScannerState.lastScanAt) < 1000) {
                return;
            }
            ScannerState.lastDecodedText = decodedText;
            ScannerState.lastScanAt = now;

            await stopScanning({ hideBox: true, reasonText: "Memproses…" });
            resultBox.innerHTML = "Memproses…";

            const mode = detectMode(decodedText);

            try {
                if (mode.mode === "presensi") {
                    await showPresensiContent(mode.payload.id);
                } else if (mode.mode === "claim") {
                    await showClaimContent(mode.url);
                } else {
                    resultBox.innerHTML = "Format QR tidak dikenali.";
                }
            } catch (err) {
                console.error("Error saat memproses hasil scan:", err);
                resultBox.innerHTML = "Terjadi kesalahan saat memproses hasil scan.";
            }
        }

        function handleDecodeError(errorMessage) {
            // Error decode per-frame → diabaikan saja demi kecepatan
            // console.debug("Decode error:", errorMessage);
        }

        /* ==========================================================
           BUTTON HANDLING
        ========================================================== */
        startBtn.onclick = async () => {
            if (ScannerState.isScanning || ScannerState.isStarting) {
                await stopScanning();
            } else {
                await startScanning();
            }
        };

        switchBtn.onclick = async () => {
            if (ScannerState.cameras.length < 2) return;

            const index = ScannerState.cameras.findIndex(c => c.id === ScannerState.activeCameraId);
            const nextCam = ScannerState.cameras[(index + 1) % ScannerState.cameras.length];

            ScannerState.activeCameraId = nextCam.id;
            applyMirrorForCamera(nextCam);

            if (ScannerState.isScanning && !ScannerState.isStarting && !ScannerState.isStopping) {
                await stopScanning({ hideBox: false, reasonText: "Mengganti kamera…" });
                await startScanning();
            }
        };

        (async () => {
            await initCamerasIfNeeded();
            resultBox.innerHTML = "Menunggu scan…";
        })();
    </script>

</body>

</html>
