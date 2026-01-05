console.log("🔥 [pagination_kegiatan.js] Loaded - Orion Patch Final");

(function () {

  function renderKegiatanPagination() {
    const pag = window._kpPagination || { data: [], page: 1, limit: 15 };

    const tbody     = document.getElementById("kegiatan-body");
    const container = document.getElementById("kegiatan-pagination");

    if (!tbody || !container) return;

    const data  = Array.isArray(pag.data) ? pag.data : [];
    const limit = parseInt(pag.limit, 10) || 15;
    const total = data.length;
    const totalPage = Math.max(1, Math.ceil(total / limit));

    pag.page = Math.min(Math.max(1, pag.page), totalPage);

    // DATA SLICE
    const start = (pag.page - 1) * limit;
    const slice = data.slice(start, start + limit);

    tbody.innerHTML = slice.length
      ? slice.map(d => `
        <tr>
          <td>${d.id_kegiatan ?? "-"}</td>
          <td>${d.iduser ?? "-"}</td>
          <td>${d["Nama Peserta"] ?? "-"}</td>
          <td>${d["Nama Kegiatan"] ?? "-"}</td>
          <td>${d["Waktu Kegiatan"] ?? "-"}</td>
        </tr>
      `).join("")
      : `<tr><td colspan="5" style="text-align:center;color:#888;">Tidak ada data.</td></tr>`;


    // PAGINATION (LIMIT 3 ANGKA - MATCH TAB LAIN)
    if (totalPage <= 1) {
      container.innerHTML = "";
      return;
    }

    const limitLinks = 3;
    const half = Math.floor(limitLinks / 2);

    let pStart = Math.max(1, pag.page - half);
    let pEnd = Math.min(totalPage, pStart + limitLinks - 1);

    if (pEnd - pStart + 1 < limitLinks) {
      pStart = Math.max(1, pEnd - limitLinks + 1);
    }

    let html = "";

    // <<
    if (pag.page > 1) {
      html += `<button class="kp-page-btn" data-kp="1" data-page="1">&laquo;</button>`;
    }
    // <
    if (pag.page > 1) {
      html += `<button class="kp-page-btn" data-kp="1" data-page="${pag.page - 1}">&lsaquo;</button>`;
    }

    // numbered
    for (let i = pStart; i <= pEnd; i++) {
      const active = i === pag.page ? "active" : "";
      html += `<button class="kp-page-btn ${active}" data-kp="1" data-page="${i}">${i}</button>`;
    }

    // >
    if (pag.page < totalPage) {
      html += `<button class="kp-page-btn" data-kp="1" data-page="${pag.page + 1}">&rsaquo;</button>`;
    }
    // >>
    if (pag.page < totalPage) {
      html += `<button class="kp-page-btn" data-kp="1" data-page="${totalPage}">&raquo;</button>`;
    }

    container.innerHTML = html;

    // EVENT LISTENER
    container.querySelectorAll(".kp-page-btn").forEach(btn => {
      btn.onclick = ev => {
        ev.stopPropagation();
        pag.page = parseInt(btn.dataset.page);
        window._kpPagination = pag;
        renderKegiatanPagination();

        const topEl = document.getElementById("kegiatan-body");
        if (topEl) topEl.scrollIntoView({ behavior: "smooth", block: "start" });
      };
    });
  }

  window.renderKegiatanPagination = renderKegiatanPagination;

  window._kpPagination = window._kpPagination || {
    data: window._kegiatanPesertaData || [],
    page: 1,
    limit: 15
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", renderKegiatanPagination);
  } else {
    renderKegiatanPagination();
  }

})();
