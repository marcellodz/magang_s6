// mod/admin/staff/js/script.js (patched)
// Versi: patched for presensi+klaim (no process_qr.php)
// 🔥 staff scanner LOADED v777 -> patched v2
console.log("🔥 staff scanner LOADED v777 -> patched v2");

let html5QrCode = null;
let isScanning = false;
let scannerLock = false;

const resultBox = document.getElementById("scanResult");
const startBtn = document.getElementById("startScanBtn");
const loadingEl = document.getElementById("loading");

// -----------------------------
// Utility: stop camera safely
// -----------------------------
async function stopCamera() {
    if (html5QrCode && isScanning) {
        try {
            await html5QrCode.stop();
            html5QrCode.clear();
        } catch (err) {
            console.warn("stopCamera: error while stopping html5QrCode", err);
        }
    }

    // Also try to cleanup any <video> element left in #reader
    try {
        const reader = document.getElementById("reader");
        const vid = reader ? reader.querySelector("video") : null;
        if (vid) {
            try { vid.pause(); } catch (e) {}
            try { vid.srcObject = null; } catch (e) {}
            try { vid.remove(); } catch(e) {}
        }
    } catch (err) {
        console.warn("stopCamera: cleanup video failed", err);
    }

    document.documentElement.dataset.activeCamera = "false";
    isScanning = false;
}

// -----------------------------
// Helper: parse QR payload
// Accepts: URL string, numeric id string, or JSON string
// returns object { mode: 'claim'|'presensi'|'unknown', payload: string|object }
// -----------------------------
function detectModeFromDecoded(decoded) {
    const s = (decoded || "").trim();

    // try JSON first
    try {
        const obj = JSON.parse(s);
        // heuristics: if obj has id or token -> presensi/claim
        if (obj.id || obj.iduser) {
            return { mode: "presensi", payload: obj };
        }
        if (obj.token || obj.verify) {
            return { mode: "claim", payload: obj };
        }
    } catch (e) {
        // not JSON, continue
    }

    // If it's a URL
    try {
        const url = new URL(s);
        const path = url.pathname.toLowerCase();
        // if url points to verify_claim or contains token param
        if (path.includes("verify_claim") || url.searchParams.has("token") || path.includes("claim")) {
            return { mode: "claim", payload: s };
        }
        // if url points to staff_content or scan_booth or has iduser param
        if (path.includes("staff_content") || url.searchParams.has("iduser") || path.includes("scan_booth")) {
            return { mode: "presensi", payload: s };
        }
        // fallback: unknown but return url
        return { mode: "unknown_url", payload: s };
    } catch (e) {
        // not a URL
    }

    // numeric ID (pure digits)
    if (/^\d+$/.test(s)) {
        return { mode: "presensi", payload: s };
    }

    // token-like short strings -> maybe claim
    if (/^[A-Za-z0-9\-_]{8,}$/.test(s)) {
        return { mode: "claim", payload: s };
    }

    return { mode: "unknown", payload: s };
}

// -----------------------------
// inject HTML (with scripts) helper (kept from original)
// -----------------------------
function injectContentWithScripts(target, html) {
    target.innerHTML = html;
    const scripts = target.querySelectorAll("script");
    scripts.forEach(oldScript => {
        const newScript = document.createElement("script");
        if (oldScript.src) {
            newScript.src = oldScript.src;
        } else {
            newScript.textContent = oldScript.textContent;
        }
        document.body.appendChild(newScript);
        oldScript.remove();
    });
}

// -----------------------------
// Handler after successful scan
// -----------------------------
async function handleScan(decodedText) {
    console.log("🔎 handleScan called with:", decodedText);
    // prevent re-entrancy
    if (scannerLock) {
        console.log("handleScan: locked, ignoring duplicate scan");
        return;
    }
    scannerLock = true;

    // stop camera first (so UI will not keep capturing)
    await stopCamera();

    // restore start button state
    if (startBtn) {
        startBtn.disabled = false;
        startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
    }
    if (loadingEl) {
        loadingEl.innerHTML = '<i class="fas fa-sync-alt"></i> Memuat data...';
    }

    try {
        const modeDetected = detectModeFromDecoded(decodedText);
        console.log("🔍 Mode detected:", modeDetected);

        const isMobile = window.innerWidth <= 768;
        const footer = document.querySelector("footer");

        if (modeDetected.mode === "claim") {
            // klaim hadiah flow:
            // if payload is URL -> fetch it, otherwise try to call verify_claim endpoint or generate verify view
            let html = "";
            if (typeof modeDetected.payload === "string" && modeDetected.payload.startsWith("http")) {
                // fetch remote content (might be local path)
                const res = await fetch(modeDetected.payload, { credentials: "include" });
                html = await res.text();
            } else if (typeof modeDetected.payload === "object") {
                // object payload: maybe contains direct id or token -> call verify_claim.php with id or token
                const obj = modeDetected.payload;
                if (obj.iduser) {
                    const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/verify_claim.php?iduser=${encodeURIComponent(obj.iduser)}`, { credentials: "include" });
                    html = await res.text();
                } else if (obj.token) {
                    const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/verify_claim.php?token=${encodeURIComponent(obj.token)}`, { credentials: "include" });
                    html = await res.text();
                } else {
                    html = "<p>Data klaim tidak dikenali.</p>";
                }
            } else {
                // fallback: try to call verify_claim with token or raw payload
                const query = encodeURIComponent(modeDetected.payload);
                const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/verify_claim.php?code=${query}`, { credentials: "include" });
                html = await res.text();
            }

            // render result UI
            if (isMobile) {
                const popup = document.getElementById("scanPopup");
                const content = document.getElementById("popupContent");
                document.getElementById("popupTitle").innerText = "Verifikasi Klaim Hadiah";
                content.innerHTML = html;
                popup.classList.add("active");
                if (footer) {
                    footer.style.opacity = "0";
                    footer.style.pointerEvents = "none";
                }
            } else {
                resultBox.innerHTML = html;
                resultBox.classList.remove("hidden");
                window.scrollTo({ top: resultBox.offsetTop, behavior: "smooth" });
            }

            loadingEl.innerHTML = '<i class="fas fa-check-circle"></i> Klaim hadiah berhasil dimuat!';
            scannerLock = false;
            return;
        }

        // ------------------------
        // PRESENSI MODE (default)
        // ------------------------
        // payload may be:
        // - plain numeric id -> staff_content.php?iduser=ID
        // - URL -> call that url
        // - object with id -> use id
        let presensiHtml = "";
        if (typeof modeDetected.payload === "string" && modeDetected.payload.startsWith("http")) {
            const res = await fetch(modeDetected.payload, { credentials: "include" });
            presensiHtml = await res.text();
        } else if (typeof modeDetected.payload === "object") {
            const id = modeDetected.payload.id || modeDetected.payload.iduser;
            if (id) {
                const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/staff/staff_content.php?iduser=${encodeURIComponent(id)}`, { credentials: "include" });
                presensiHtml = await res.text();
            } else {
                presensiHtml = "<p>ID peserta tidak ditemukan di payload.</p>";
            }
        } else if (/^\d+$/.test(modeDetected.payload)) {
            const id = modeDetected.payload;
            const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/staff/staff_content.php?iduser=${encodeURIComponent(id)}`, { credentials: "include" });
            presensiHtml = await res.text();
        } else {
            // unknown: try treat as id param
            const q = encodeURIComponent(modeDetected.payload);
            const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/staff/staff_content.php?iduser=${q}`, { credentials: "include" });
            presensiHtml = await res.text();
        }

        if (isMobile) {
            const popup = document.getElementById("scanPopup");
            const content = document.getElementById("popupContent");
            document.getElementById("popupTitle").innerText = "Presensi Peserta";
            content.innerHTML = presensiHtml;
            popup.classList.add("active");
            if (footer) {
                footer.style.opacity = "0";
                footer.style.pointerEvents = "none";
            }
        } else {
            injectContentWithScripts(resultBox, presensiHtml);
            resultBox.classList.remove("hidden");
            window.scrollTo({ top: resultBox.offsetTop, behavior: "smooth" });
        }

        loadingEl.innerHTML = '<i class="fas fa-check-circle"></i> Data peserta berhasil dimuat!';
    } catch (err) {
        console.error("handleScan error:", err);
        loadingEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal memuat data peserta.';
    } finally {
        scannerLock = false;
    }
}

// -----------------------------
// Start scanner (cameraId optional)
// -----------------------------
async function startScanner(cameraId) {
    if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    try {
        // set UI
        isScanning = true;
        startBtn.innerHTML = '<i class="fas fa-hourglass-half"></i> Menunggu QR Code...';
        loadingEl.innerHTML = '<i class="fas fa-video"></i> Mengaktifkan kamera...';

        // small patch: inject video placeholder to hint user gesture
        const reader = document.getElementById("reader");
        if (reader) reader.innerHTML = '<video muted autoplay playsinline style="width:100%;height:100%;object-fit:cover;"></video>';

        await html5QrCode.start(cameraId, config, (decoded) => {
            // sanitized decoded callback
            Promise.resolve().then(() => handleScan(String(decoded)));
        });

        // ensure embedded video attributes (best-effort)
        setTimeout(() => {
            try {
                const videoEl = document.querySelector("#reader video");
                if (videoEl) {
                    videoEl.muted = true;
                    videoEl.autoplay = true;
                    videoEl.playsInline = true;
                    videoEl.setAttribute("muted", "");
                    videoEl.setAttribute("autoplay", "");
                    videoEl.setAttribute("playsinline", "");
                    videoEl.play().catch(()=>{});
                    console.log("🎥 Video element patched for autoplay (best-effort).");
                }
            } catch (e) {
                console.warn("patch video attrs failed", e);
            }
        }, 300);

        document.documentElement.dataset.activeCamera = "true";
        loadingEl.innerHTML = '<i class="fas fa-qrcode"></i> Arahkan kamera ke QR peserta...';
    } catch (err) {
        console.error("Camera start error:", err);
        isScanning = false;
        document.documentElement.dataset.activeCamera = "false";
        startBtn.disabled = false;
        startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
        loadingEl.innerHTML = '<i class="fas fa-ban"></i> Tidak bisa mengakses kamera.';
    }
}

// -----------------------------
// Start button - user gesture
// -----------------------------
startBtn.addEventListener("click", async () => {
    if (isScanning) {
        // Stop flow
        await stopCamera();
        startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
        loadingEl.innerHTML = '<i class="fas fa-qrcode"></i> Siap untuk scan berikutnya.';
        return;
    }

    startBtn.disabled = true;
    startBtn.innerHTML = '<i class="fas fa-cog fa-spin"></i> Mengaktifkan Kamera...';
    loadingEl.innerHTML = '<i class="fas fa-video"></i> Meminta izin kamera...';

    try {
        // Ensure user gesture permission prompt (getUserMedia)
        await navigator.mediaDevices.getUserMedia({ video: true });
        const devices = await Html5Qrcode.getCameras();
        if (!devices || devices.length === 0) {
            loadingEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Tidak ada kamera ditemukan.';
            startBtn.disabled = false;
            startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
            return;
        }

        // choose back camera if available
        const backCam = devices.find(cam => /back|rear|environment/i.test(cam.label));
        const selectedCam = backCam ? backCam.id : devices[0].id;
        document.documentElement.dataset.camera = backCam ? "back" : "front";

        await startScanner(selectedCam);
        startBtn.disabled = false;
        // change start button to stop label
        startBtn.innerHTML = '❌ Matikan Kamera';
    } catch (err) {
        console.error("Permission error:", err);
        startBtn.disabled = false;
        startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
        loadingEl.innerHTML = `<i class="fas fa-ban"></i> Tidak bisa akses kamera: ${err.message || err}`;
    }
});

// -----------------------------
// global closeScanResult (keperluan content injection)
// -----------------------------
function closeScanResult() {
    const popup = document.getElementById("scanPopup");
    const resultBoxEl = document.getElementById("scanResult");
    const popupContent = document.getElementById("popupContent");
    const footer = document.querySelector("footer");

    if (popup) popup.classList.remove("active");
    if (popupContent) popupContent.innerHTML = "";

    if (resultBoxEl) {
        resultBoxEl.classList.add("hidden");
        resultBoxEl.innerHTML = "";
    }

    if (footer) {
        footer.style.opacity = "1";
        footer.style.pointerEvents = "auto";
    }

    if (loadingEl) {
        loadingEl.innerHTML = '<i class="fas fa-qrcode"></i> Siap untuk scan berikutnya.';
    }

    if (startBtn) {
        startBtn.disabled = false;
        startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
    }

    // make sure camera stopped
    stopCamera().catch(()=>{});
    console.log("✅ closeScanResult() triggered — view reset.");
}
window.closeScanResult = closeScanResult;

// -----------------------------
// attach mobile popup close if present
// -----------------------------
const closePopupBtn = document.getElementById("closePopup");
if (closePopupBtn) closePopupBtn.addEventListener("click", closeScanResult);

// -----------------------------
// cleanup before unload
// -----------------------------
window.addEventListener("beforeunload", () => {
    try { stopCamera(); } catch(e){}
});

// -----------------------------
// global confirm claim handler (kept from original)
// -----------------------------
document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".btn-confirm-claim");
    if (!btn) return;

    const iduser = btn.dataset.iduser;
    const nama = btn.dataset.nama;

    btn.disabled = true;
    btn.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Memproses...";

    try {
        const res = await fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/verify_claim.php?iduser=${encodeURIComponent(iduser)}`, {
            method: "POST",
            credentials: "include"
        });
        const text = await res.text();
        const data = JSON.parse(text);

        await Swal.fire({
            icon: data.success ? "success" : "info",
            title: data.success ? "Berhasil!" : "Gagal",
            text: data.msg,
            background: "#111",
            color: "#fff",
            confirmButtonColor: "#ff3333"
        });

        if (data.success) {
            closeScanResult();
        }
    } catch (err) {
        console.error("❌ Gagal kirim klaim:", err);
        Swal.fire({
            icon: "error",
            title: "Koneksi Gagal",
            text: err.message || "Tidak dapat menghubungi server.",
            background: "#111",
            color: "#fff"
        });
    } finally {
        btn.disabled = false;
        btn.innerHTML = "<i class='fa fa-check'></i> Konfirmasi Klaim";
    }
});
