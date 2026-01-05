// mod/admin/super/js/booth.js
console.log("✅ [booth.js] loaded");

function openBoothForm(action, id = '', nama = '', kategori = '', lantai = '') {
    const modal = document.getElementById('boothModal');
    modal.style.display = 'flex';
    document.getElementById('boothFormTitle').innerHTML =
        (action === 'add')
            ? "<i class='fas fa-store'></i> Tambah Booth"
            : "<i class='fas fa-edit'></i> Edit Booth";

    document.getElementById('id_booth').value = id;
    document.getElementById('nama_booth').value = nama;
    document.getElementById('kategori').value = kategori || '';
    document.getElementById('lantai').value = lantai || '';

    document.getElementById('boothForm').onsubmit = (e) => {
        e.preventDefault();
        submitBoothForm(action);
    };
}

function closeBoothForm() {
    document.getElementById('boothModal').style.display = 'none';
}

// === TAMBAH / EDIT BOOTH ===
function submitBoothForm(action) {
    const form = document.getElementById('boothForm');
    const formData = new FormData(form);
    formData.append('action', action);

    // ✅ FIX PATH BENAR — langsung ke /mod/admin/super/booth_action.php
    fetch('./super/booth_action.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.text())
        .then(response => {
            console.log("[submitBoothForm] response:", response);
            showToast(response.trim() || "✅ Data Booth berhasil disimpan!", "success");
            closeBoothForm();
            refreshBoothTableAndCount();
        })
        .catch(() => showToast("❌ Gagal memproses data Booth.", "error"));
}

// === HAPUS BOOTH ===
function deleteBooth(id) {
    showConfirm('Yakin ingin menghapus Booth ini?', (confirmed) => {
        if (!confirmed) return;

        fetch('./super/booth_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=delete&id=${id}`
        })
            .then(res => res.text())
            .then(response => {
                console.log("[deleteBooth] response:", response);
                if (response.includes("✅")) {
                    showToast(response, "success");
                    refreshBoothTableAndCount();
                } else {
                    showToast(response || "❌ Gagal menghapus Booth.", "error");
                }
            })
            .catch(err => {
                console.error("Delete error:", err);
                showToast("❌ Gagal menghapus Booth (network error).", "error");
            });
    });
}

// === REFRESH TABLE & UPDATE CARD ===
function refreshBoothTableAndCount() {
    const container = document.getElementById('data-section');
    container.innerHTML = `<div class="loading">⏳ Memuat data booth...</div>`;

    fetch('./super/super_content.php?type=booth')
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            const totalBooth = container.querySelectorAll('tbody tr').length;
            document.querySelector('.card:nth-child(2) p').textContent = `${totalBooth} Aktif`;
        })
        .catch(() => showToast("❌ Gagal memuat data booth.", "error"));
}
