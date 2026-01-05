// mod/admin/super/js/filter_kegiatan.js
console.log("✅ [filter_kegiatan.js] loaded");

(function waitForFilter() {
  const btn            = document.querySelector("#applyFilter");
  const sesiSelect     = document.querySelector("#filterSesi");
  const kegiatanSelect = document.querySelector("#filterKegiatan");
  const tbody          = document.getElementById("kegiatan-body") || document.querySelector("tbody");
  const summary        = document.getElementById("filterSummary");
  const paginationEl   = document.getElementById("kegiatan-pagination");

  if (!btn || !sesiSelect || !tbody || !summary) {
    setTimeout(waitForFilter, 200);
    return;
  }
  console.log("✅ Filter UI aktif dan siap!");

  const allData = Array.isArray(window._kegiatanPesertaData) ? window._kegiatanPesertaData : [];
  const norm = s => (s || "").toString().trim().toLowerCase();

  // isi daftar kegiatan saat sesi berubah (menggunakan window.kegiatanData, jika ada)
  sesiSelect.addEventListener("change", () => {
    const sesi = sesiSelect.value;
    kegiatanSelect.innerHTML = `<option value="all">Semua Kegiatan</option>`;
    if (sesi !== "all" && window.kegiatanData && Array.isArray(window.kegiatanData[sesi])) {
      window.kegiatanData[sesi].forEach(nama => {
        const opt = document.createElement("option");
        opt.value = nama;
        opt.textContent = nama;
        kegiatanSelect.appendChild(opt);
      });
      kegiatanSelect.disabled = false;
    } else {
      kegiatanSelect.disabled = true;
      kegiatanSelect.value = "all";
    }
  });

  // klik Terapkan Filter
  btn.addEventListener("click", () => {
    const sesi = sesiSelect.value;
    const kegiatan = kegiatanSelect ? kegiatanSelect.value : "all";

    // safety: pastikan ada data
    if (!Array.isArray(allData)) {
      console.warn("[filter_kegiatan] _kegiatanPesertaData missing or not array");
      return;
    }

    const hasil = allData.filter(item => {
      const namaItem = norm(item["Nama Kegiatan"]);
      const passSesi = sesi === "all" || (window.kegiatanData && Array.isArray(window.kegiatanData[sesi])
        ? (window.kegiatanData[sesi] || []).map(norm).some(n => namaItem === n || namaItem.includes(n))
        : true);
      const passKegiatan = kegiatan === "all" || namaItem === norm(kegiatan);
      return passSesi && passKegiatan;
    });

    // Integrasi pagination frontend
    window._kpPagination = window._kpPagination || { data: [], page: 1, limit: 15 };
    window._kpPagination.data = hasil;
    window._kpPagination.page = 1;

    // Jika fungsi pagination tersedia panggil, jika tidak render langsung
    if (typeof window.renderKegiatanPagination === "function") {
      window.renderKegiatanPagination();
    } else {
      // fallback: render rows langsung
      tbody.innerHTML = hasil.length
        ? hasil.map(d => `
          <tr>
            <td>${d.id_kegiatan ?? "-"}</td>
            <td>${d.iduser ?? "-"}</td>
            <td>${(d["Nama Peserta"] ?? "-")}</td>
            <td>${(d["Nama Kegiatan"] ?? "-")}</td>
            <td>${(d["Waktu Kegiatan"] ?? "-")}</td>
          </tr>
        `).join("")
        : `<tr><td colspan="5" style="text-align:center;color:#888;">Tidak ada data untuk filter ini.</td></tr>`;
      if (paginationEl) paginationEl.innerHTML = "";
    }

    // summary peserta unik
    const uniqueUsers = {};
    hasil.forEach(item => {
      const id = item.iduser;
      if (!uniqueUsers[id]) uniqueUsers[id] = item["Profesi / Kelas"] || "Tidak Diketahui";
    });
    const totalUserUnik = Object.keys(uniqueUsers).length;
    summary.innerHTML = hasil.length
      ? `<i class="fas fa-user-check"></i> <b>${totalUserUnik}</b> peserta unik ditemukan`
      : `<i class="fas fa-exclamation-triangle"></i> Tidak ada peserta untuk filter ini.`;

    // notifikasi (Swal jika ada)
    if (typeof Swal !== "undefined") {
      Swal.fire({
        title: "Filter Diterapkan",
        html: (sesi === "all" ? "Semua sesi" : `Sesi ${sesi}`) + (kegiatan !== "all" ? ` - ${kegiatan}` : "") + ` (${totalUserUnik} peserta unik)`,
        icon: "success",
        background: "#111",
        color: "#fff",
        confirmButtonColor: "#e60000"
      });
    } else {
      console.log("Filter diterapkan:", sesi, kegiatan, "| unik:", totalUserUnik);
    }
  });
  
})();

