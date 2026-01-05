// Efek fade di header saat scroll
document.addEventListener("scroll", () => {
    const header = document.getElementById("topbar");
    if (window.scrollY > 30) header.classList.add("scrolled");
    else header.classList.remove("scrolled");
});

// Dropdown manual (click toggle)
const btn = document.getElementById("profileBtn");
const dropdown = document.getElementById("profileDropdown");
btn.addEventListener("click", () => {
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
});
window.addEventListener("click", (e) => {
    if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }
});


// Toggle kehadiran peserta
window.toggleHadir = function (idKegiatan, idUser, btn) {
    const isActive = btn.classList.contains('active');
    const action = isActive ? 'batalkan' : 'hadir';

    fetch(`update_presensi.php?id_kegiatan=${idKegiatan}&iduser=${idUser}&action=${action}`)
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                const row = btn.closest('tr');
                const statusEl = row.querySelector('.status');

                if (action === 'hadir') {
                    btn.classList.add('active');
                    btn.innerHTML = `<i class='fas fa-undo'></i> Batalkan`;
                    statusEl.textContent = 'Hadir';
                    statusEl.className = 'status hadir';
                } else {
                    btn.classList.remove('active');
                    btn.innerHTML = `<i class='fas fa-check'></i> Hadir`;
                    statusEl.textContent = 'Belum Hadir';
                    statusEl.className = 'status belum-hadir';
                }
            } else {
                console.warn("⚠️ " + (resp.message || "Gagal memperbarui status."));
                alert(resp.message || "Terjadi kesalahan.");
            }
        })
        .catch(err => {
            console.error("❌ Gagal memperbarui status:", err);
            alert("Gagal memperbarui status.");
        });
};