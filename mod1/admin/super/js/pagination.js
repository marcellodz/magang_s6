// pagination.js (GLOBAL - untuk tab: peserta, booth, staff, kunjungan)
document.addEventListener("click", function (e) {

    // --- Skip kalau tombol pagination milik KEGIATAN PESERTA ---
    if (e.target.dataset.kp === "1") {
        return; // biarkan pagination_kegiatan.js yang urus
    }

    if (!e.target.classList.contains("page-btn")) return;

    const page = e.target.dataset.page;
    const type = e.target.dataset.type;

    fetch("super/super_content.php?type=" + type, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "page=" + page
    })
    .then(res => res.text())
    .then(html => {

        const container = document.getElementById("data-section");

        if (!container) {
            console.error("Container #data-section tidak ditemukan!");
            return;
        }

        container.innerHTML = html;

        window.scrollTo({ top: 0, behavior: "smooth" });
    })
    .catch(err => console.error(err));
});
