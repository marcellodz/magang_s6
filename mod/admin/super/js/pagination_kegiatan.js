// pagination_kegiatan.js
console.log("✅ [pagination_kegiatan.js] loaded");

(function () {

  function renderKegiatanPagination() {
    // pastikan ada objek pagination (data diisi dari PHP window._kegiatanPesertaData)
    const pag = window._kpPagination || { data: [], page: 1, limit: 15 };

    const tbody     = document.getElementById("kegiatan-body");
    const container = document.getElementById("kegiatan-pagination");

    if (!tbody || !container) {
      console.warn("[pagination_kegiatan] tbody/container not found");
      return;
    }

    const data  = Array.isArray(pag.data) ? pag.data : [];
    const limit = parseInt(pag.limit, 10) || 15;
    const total = data.length;

    const totalPage = Math.max(1, Math.ceil(total / limit));

    // pastikan page valid
    pag.page = Math.min(Math.max(1, parseInt(pag.page || 1, 10)), totalPage);

    // Slice data untuk page sekarang
    const start = (pag.page - 1) * limit;
    const slice = data.slice(start, start + limit);

    // Render rows
    tbody.innerHTML = slice.length
      ? slice.map(d => `
        <tr>
          <td>${d.id_kegiatan ?? "-"}</td>
          <td>${d.iduser ?? "-"}</td>
          <td>${(d["Nama Peserta"] ?? "-")}</td>
          <td>${(d["Nama Kegiatan"] ?? "-")}</td>
          <td>${(d["Waktu Kegiatan"] ?? "-")}</td>
        </tr>
      `).join("")
      : `<tr><td colspan="5" style="text-align:center;color:#888;">Tidak ada data.</td></tr>`;

    // Render pagination buttons (LOCAL, tidak mengubah URL / tidak fetch)
    if (totalPage <= 1) {
      container.innerHTML = "";
      return;
    }

    // Build buttons: previous, numbered, next.
    let html = '';

    html += `<button class="kp-page-btn kp-prev" data-kp="1" data-page="${Math.max(1, pag.page - 1)}" aria-label="previous">‹</button>`;

    for (let i = 1; i <= totalPage; i++) {
      const active = (i === pag.page) ? "active" : "";
      html += `<button class="kp-page-btn ${active}" data-kp="1" data-page="${i}">${i}</button>`;
    }

    html += `<button class="kp-page-btn kp-next" data-kp="1" data-page="${Math.min(totalPage, pag.page + 1)}" aria-label="next">›</button>`;

    container.innerHTML = html;

    // Attach local listeners (only for kp-page-btn)
    container.querySelectorAll(".kp-page-btn").forEach(btn => {
      btn.onclick = (ev) => {
        ev.stopPropagation();             // penting: jangan biarkan global handler tangkap click
        const newPage = parseInt(btn.dataset.page, 10) || 1;
        pag.page = newPage;
        window._kpPagination = pag;
        renderKegiatanPagination();

        // opsi scroll ke tabel
        const topEl = document.getElementById("kegiatan-body");
        if (topEl) topEl.scrollIntoView({ behavior: "smooth", block: "start" });
      };
    });
  }

  // expose global function supaya filter_kegiatan.js bisa panggil render ulang
  window.renderKegiatanPagination = renderKegiatanPagination;

  // init global pagination data (diisi oleh PHP di super_content.php)
  window._kpPagination = window._kpPagination || {
    data: window._kegiatanPesertaData || [],
    page: 1,
    limit: 15
  };

  // auto render on load
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", renderKegiatanPagination);
  } else {
    renderKegiatanPagination();
  }

})();
