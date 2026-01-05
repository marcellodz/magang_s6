// mod/admin/super/js/main.js
console.log("✅ [main.js] loaded");

    // === SHOW DATA CARD === 4/11
function showData(type, event) {
  const container = document.getElementById('data-section');
  container.innerHTML = `<div class="loading">⏳ Memuat data ${type}...</div>`;
  document.querySelectorAll('.card').forEach(card => card.classList.remove('active'));
  if (event && event.currentTarget) event.currentTarget.classList.add('active');

  fetch(`super/super_content.php?type=${type}`)
    .then(res => res.text())
    .then(html => {
      container.innerHTML = html;

      // --- Jalankan ulang semua <script> di hasil fetch ---
      const scripts = container.querySelectorAll("script");
      scripts.forEach(oldScript => {
        const newScript = document.createElement("script");
        if (oldScript.src) {
          // Script eksternal seperti mod/admin/filter_kegiatan.js
          newScript.src = oldScript.src;
        } else {
          // Inline script (misalnya window._kegiatanPesertaData)
          newScript.textContent = oldScript.textContent;
        }
        document.body.appendChild(newScript);
      });
    })
    .catch(() => showToast(`❌ Gagal memuat data ${type}.`, "error"));
}