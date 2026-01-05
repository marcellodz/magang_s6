// mod/admin/super/js/generate_qr_booth.js

// === GENERATE QR TANPA RELOAD ===
    async function generateQR(idbooth, btn) {
        // Ganti tombol jadi loading spinner
        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Generating...`;

        try {
            const res = await fetch(`generate_qr_booth.php?idbooth=${idbooth}`);
            const html = await res.text();

            // Ambil src QR dari respon HTML
            const match = html.match(/<img[^>]+src="([^"]+)"/i);
            if (match) {
                const qrFile = match[1];
                const qrDir = qrFile.split('/').slice(-1)[0]; // ambil nama file QR

                // Ganti cell tombol menjadi gambar QR
                const cell = btn.closest("td");
                cell.innerHTML = `
                <div style="display:flex;align-items:center;gap:10px;">
                    <img src="${qrFile}?t=${Date.now()}" width="60" height="60"
                        style="border-radius:10px;border:1px solid rgba(255,255,255,0.2);">
                    <a href="${qrFile}" download class="btn-download" title="Download QR ${qrDir}">
                        <i class="fas fa-download"></i>
                    </a>
                </div>`;
            } else {
                Swal.fire("⚠️ Gagal", "Gagal memuat QR Code.", "warning");
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        } catch (err) {
            console.error("QR Error:", err);
            Swal.fire("❌ Error", "Terjadi kesalahan saat generate QR.", "error");
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    }
