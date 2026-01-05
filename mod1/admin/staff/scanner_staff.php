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

        #reader {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            border-radius: 18px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 60, 60, 0.28);
        }

        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform .25s ease;
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
        console.log("🔥 Orion Glass Scanner Loaded");



        const html5QrCode = new Html5Qrcode("reader");
        let cameras = [];
        let currentCamId = null;
        let isScanning = false;

        const startBtn = document.getElementById("startScanBtn");
        const switchBtn = document.getElementById("switchCamBtn");
        const resultBox = document.getElementById("resultBox");
        const readerContainer = document.getElementById("reader-container");

        function showPopup(title, message, onConfirm) {
    const overlay = document.getElementById("popup-ots");
    document.getElementById("popup-title").textContent = title;
    document.getElementById("popup-message").textContent = message;

    overlay.classList.add("show");

    // Tombol batal
    document.getElementById("popup-cancel").onclick = () => {
        overlay.classList.remove("show");
    };

    // Tombol OK
    document.getElementById("popup-ok").onclick = () => {
        overlay.classList.remove("show");
        if (typeof onConfirm === "function") onConfirm();
    };
}


function toggleHadir(id_kegiatan, iduser, btnElement) {

    // Cek tombolnya aktif atau tidak → tentukan aksi
    const isActive = btnElement.classList.contains("active");
    const action = isActive ? "belum" : "hadir";

    const url = `/openhouse.smbbtelkom.ac.id/mod/admin/staff/update_presensi.php?iduser=${iduser}&id_kegiatan=${id_kegiatan}&action=${action}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {

            if (!data.success) {
                alert(data.message || "Gagal update presensi.");
                return;
            }

            // Update UI tombol langsung
            if (action === "hadir") {
                btnElement.classList.add("active");
                btnElement.innerHTML = `<i class="fas fa-undo"></i> Batalkan`;
            } else {
                btnElement.classList.remove("active");
                btnElement.innerHTML = `<i class="fas fa-check"></i> Hadir`;
            }

            // Refresh tabel supaya status langsung ganti
            showPresensiContent(iduser);
        })
        .catch(err => {
            console.error("ERR", err);
            alert("Koneksi gagal.");
        });
}

function closeScanResult() {
    resultBox.style.opacity = "0";

    setTimeout(() => {
        resultBox.innerHTML = "Menunggu scan…";
        resultBox.style.opacity = "1";
    }, 200);

    readerContainer.classList.remove("active");
    setTimeout(() => readerContainer.style.display = "none", 250);

    if (isScanning && html5QrCode) {
        html5QrCode.stop().then(() => {
            isScanning = false;
        });
    }
}


async function daftarOTS(iduser) {

    // 1️⃣ cek dulu
    const cek = await fetch("/openhouse.smbbtelkom.ac.id/mod/admin/staff/daftar_ots.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
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

    // 2️⃣ belum → tanya dulu
    showPopup(
        "Daftarkan OTS?",
        `Daftarkan peserta ke ${cek.sesiTujuan}?`,
        () => {
            // 3️⃣ jika OK, jalankan create
            resultBox.innerHTML = "Mendaftarkan peserta…";

            fetch("/openhouse.smbbtelkom.ac.id/mod/admin/staff/daftar_ots.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
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

        function showScannerBox() {
            readerContainer.classList.add("active");
        }
        function hideScannerBox() {
            readerContainer.classList.remove("active");
            setTimeout(() => readerContainer.style.display = "none", 300);
        }


        /* ==========================
           DETEKSI QR
        =========================== */
        function detectMode(payload) {
            if (payload.includes("verify_claim.php") || payload.includes("token="))
                return { mode: "claim", url: payload.trim() };

            try {
                const obj = JSON.parse(payload);
                if (obj.id) return { mode: "presensi", payload: obj };
            } catch (e) { }

            return { mode: "unknown" };
        }

        async function showPresensiContent(id) {
            const url = `/openhouse.smbbtelkom.ac.id/mod/admin/staff/staff_content.php?iduser=${id}`;
            const res = await fetch(url);
            resultBox.innerHTML = `<div class="content-box">${await res.text()}</div>`;
        }

async function showClaimContent(url) {
    const res = await fetch(url, { credentials: "include" });
    const html = await res.text();

    // Tampilkan hasil verify_claim ke resultBox
    resultBox.innerHTML = `<div class="content-box">${html}</div>`;

    // Hubungkan tombol konfirmasi
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
    fetch("/openhouse.smbbtelkom.ac.id/mod/admin/verify_claim.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `iduser=${iduser}`
    })
    .then(r => r.json())
    .then(async data => {

        showPopup("Klaim Hadiah", data.msg);

        // refresh tampilan claim agar tombol hilang
        const updated = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/staff/staff_content.php?mode=claim&iduser=${iduser}`)
            .then(r => r.text());

        resultBox.innerHTML = `<div class="content-box">${updated}</div>`;
    });
}


function closeClaimPopup() {
    document.getElementById("popup-claim").classList.remove("show");
}


        /* ==========================
           QR SUCCESS
        =========================== */
        async function onSuccess(decoded) {
            await html5QrCode.stop();
            isScanning = false;

            resultBox.innerHTML = "Memproses…";

            const mode = detectMode(decoded);
            if (mode.mode === "presensi") await showPresensiContent(mode.payload.id);
            else if (mode.mode === "claim") await showClaimContent(mode.url);
            else resultBox.innerHTML = "Format QR tidak dikenali.";

            hideScannerBox();
        }



        /* ==========================================================
           MIRROR — **100% FIXED** (langsung ke <video>)
        ========================================================== */
        async function applyMirrorInstant(id) {

            const stream = await navigator.mediaDevices.getUserMedia({
                video: { deviceId: { exact: id } }
            });

            const track = stream.getVideoTracks()[0];
            const facing = track.getSettings().facingMode || "unknown";

            stream.getTracks().forEach(t => t.stop());

            const isFront = (facing === "user" || facing === "unknown");

            const wrapper = document.querySelector("#reader");
            if (wrapper) {
                if (isFront) wrapper.classList.add("mirror-wrapper");
                else wrapper.classList.remove("mirror-wrapper");
            }
        }

        /* ==========================================================
           START CAMERA
        ========================================================== */
        async function startCamera(id) {

            // 1️⃣ DETEKSI FRONT/BACK DULU
            let isFront = false;
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { deviceId: { exact: id } }
                });

                const track = stream.getVideoTracks()[0];
                const facing = track.getSettings().facingMode || "unknown";
                isFront = (facing === "user" || facing === "unknown");

                stream.getTracks().forEach(t => t.stop());
            } catch (e) {
                console.warn("Detection facingMode gagal:", e);
            }

            // 2️⃣ SET MIRROR SEBELUM VIDEO DIBUAT
            const wrapper = document.querySelector("#reader");
            if (wrapper) {
                wrapper.style.transform = isFront ? "scaleX(-1)" : "scaleX(1)";
            }

            // 3️⃣ TUNGGU 500ms AGAR CAMERA RELEASE DULU
            await new Promise(res => setTimeout(res, 500));

            // 4️⃣ START HTML5-QRCODE SECARA BERSIH
            html5QrCode
                .start(id, { fps: 10, qrbox: 260 }, onSuccess)
                .then(() => {
                    isScanning = true;
                    resultBox.innerHTML = "Arahkan kamera ke QR…";
                })
                .catch(err => {
                    resultBox.innerHTML = "Kamera gagal dibuka.";
                    console.error(err);
                });
        }


        /* ==========================================================
           BUTTON HANDLING
        ========================================================== */
        startBtn.onclick = () => {
            if (!isScanning) {
                Html5Qrcode.getCameras().then(list => {
                    cameras = list;
                    currentCamId = cameras[0].id;

                    readerContainer.style.display = "block";
                    setTimeout(() => showScannerBox(), 10);

                    if (cameras.length > 1) switchBtn.style.display = "inline-block";

                    startCamera(currentCamId);
                });
            } else {
                html5QrCode.stop();
                resultBox.innerHTML = "Scan dihentikan.";
                isScanning = false;
                hideScannerBox();
            }
        };

        switchBtn.onclick = () => {
            if (!isScanning) return;

            const index = cameras.findIndex(c => c.id === currentCamId);
            currentCamId = cameras[(index + 1) % cameras.length].id;

            html5QrCode.stop().then(() => startCamera(currentCamId));
        };
    </script>

</body>

</html>