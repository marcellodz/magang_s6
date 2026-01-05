console.log("📊 dashboard.js loaded");

/* =====================
   SUMMARY: DEFAULT (OC)
   ===================== */
async function updateSummary(nama, sesi = null) {
    const url = sesi
        ? `staff/api/get_summary.php?nama=${encodeURIComponent(nama)}&sesi=${sesi}`
        : `staff/api/get_summary.php?nama=${encodeURIComponent(nama)}`;

    const res = await fetch(url);
    const d = await res.json();

    document.getElementById("label1").innerText = "Total Pendaftar";
    document.getElementById("label2").innerText = "Hadir";
    document.getElementById("label2").innerText = "Hadir Kegiatan";

    document.getElementById("val1").innerText = d.totalPeserta;
    document.getElementById("val2").innerText = d.hadir;
    document.getElementById("val3").innerText = d.hadir;
}

/* =====================
   SUMMARY: SESI (Semua)
   ===================== */
async function updateSummarySesiExtended(sesi) {
    const res = await fetch(`staff/api/get_summary_sesi.php?sesi=${sesi}`);
    const d = await res.json();

    const oc = await fetch(`staff/api/get_oc_in_sesi.php?sesi=${sesi}`);
    const ocData = await oc.json();

    document.getElementById("label1").innerText = "Pendaftar Sesi";
    document.getElementById("label2").innerText = "Hadir Opening Ceremony";
    document.getElementById("label3").innerText = "Hadir Kegiatan";

    document.getElementById("val1").innerText = d.totalPeserta;
    document.getElementById("val2").innerText = ocData.hadirOC;
    document.getElementById("val3").innerText = d.hadir;
}

/* =====================
   DROPDOWN
   ===================== */
const pilihSesi = document.getElementById("pilihSesi");
const pilihKegiatan = document.getElementById("pilihKegiatan");

// Default = Opening Ceremony
updateSummary("Opening Ceremony");

/* =====================
   EVENT SESI DIPILIH
   ===================== */
pilihSesi.addEventListener("change", async () => {
    const sesi = pilihSesi.value;

    pilihKegiatan.innerHTML = `<option value="Semua">Semua</option>`;
    pilihKegiatan.disabled = true;

    if (sesi === "OC") {
        document.getElementById("label1").innerText = "Total Pendaftar";
        document.getElementById("label2").innerText = "Hadir";
        document.getElementById("label2").innerText = "Hadir Kegiatan";

        updateSummary("Opening Ceremony");
        return;
    }

    await updateSummarySesiExtended(sesi);

    const res = await fetch(`staff/api/get_kegiatan_sesi.php?sesi=${sesi}`);
    const list = await res.json();

    list.forEach(k => {
        pilihKegiatan.innerHTML += `<option value="${k}">${k}</option>`;
    });

    pilihKegiatan.disabled = false;
});

/* =====================
   EVENT KEGIATAN DIPILIH
   ===================== */
pilihKegiatan.addEventListener("change", async () => {
    let nama = pilihKegiatan.value.trim();   // ← FIX WAJIB
    const sesi = pilihSesi.value;

    console.log("Dipilih kegiatan:", nama);

    if (nama === "Semua") {
        await updateSummarySesiExtended(sesi);
        return;
    }

    // === MODE KEGIATAN ===
    document.getElementById("label1").innerText = "Pendaftar Kegiatan";
    document.getElementById("label2").innerText = "Hadir Opening Ceremony";
    document.getElementById("label3").innerText = "Hadir Kegiatan";

    // Ambil summary kegiatan
    const res = await fetch(`staff/api/get_summary.php?nama=${encodeURIComponent(nama)}&sesi=${sesi}`);
    const d = await res.json();

    // Ambil hadir OC kegiatan
    const oc = await fetch(`staff/api/get_oc_in_kegiatan.php?nama=${encodeURIComponent(nama)}&sesi=${sesi}`);
    const ocData = await oc.json();

    console.log("d:", d);
    console.log("ocData:", ocData);

    document.getElementById("val1").innerText = d.totalPeserta;
    document.getElementById("val2").innerText = ocData.hadirOC;
    document.getElementById("val3").innerText = d.hadir;
});
