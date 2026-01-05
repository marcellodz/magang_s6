// === MODAL CONTROL STAFF ===
console.log("✅ [staff.js] loaded");
function openForm(action, id = '', nama = '', username = '', password = '', role = 'staff') {
    const modal = document.getElementById('staffModal');
    modal.style.display = 'flex';
    const formTitle = document.getElementById('formTitle');
    const passwordLabel = document.querySelector('label[for="password"]');

    // Set Judul Modal
    formTitle.innerHTML = (action === 'add')
        ? "<i class='fas fa-user-plus'></i> Tambah Admin/Staff"
        : "<i class='fas fa-edit'></i> Edit Staff";

    // Isi data field
    document.getElementById('id_admin').value = id;
    document.getElementById('nama_lengkap').value = nama;
    document.getElementById('username').value = username;
    document.getElementById('password').value = '';
    document.getElementById('role').value = role;

    // Ubah teks label password sesuai mode
    if (action === 'add') {
        passwordLabel.innerHTML = `<i class="fas fa-lock"></i> Password`;
    } else {
        passwordLabel.innerHTML = `<i class="fas fa-lock"></i> Password (kosongkan jika tidak diubah)`;
    }

    document.getElementById('staffForm').onsubmit = (e) => {
        e.preventDefault();
        submitForm(action);
    };
}

function closeForm() {
    document.getElementById('staffModal').style.display = 'none';
}

// === TAMBAH / EDIT STAFF ===
function submitForm(action) {
    const form = document.getElementById('staffForm');
    const formData = new FormData(form);
    const role = document.getElementById('role').value;
    formData.append('action', action);

    fetch('./super/staff_action.php', {
        method: 'POST',
        body: formData
    })
        .then(async res => {
            let text = await res.text();
            let data;

            try {
                data = JSON.parse(text);
            } catch {
                data = { status: text.includes("berhasil") ? "success" : "error", message: text.trim() };
            }

            if (data.status === 'success') {
                // Pesan dinamis
                if (action === 'add') {
                    showToast(`✅ Berhasil menambahkan ${role.charAt(0).toUpperCase() + role.slice(1)}.`, "success");
                } else {
                    showToast(`✅ Berhasil memperbarui ${role.charAt(0).toUpperCase() + role.slice(1)}.`, "success");
                }

                closeForm();
                setTimeout(() => refreshStaffTableAndCount(), 300);
            } else {
                showToast(data.message || "❌ Terjadi kesalahan saat menyimpan data staff.", "error");
            }
        })
        .catch(() => showToast("❌ Gagal mengirim data ke server.", "error"));
}

// === HAPUS STAFF ===
function deleteUser(id) {
    showConfirm('Yakin ingin menghapus data ini?', (confirmed) => {
        if (!confirmed) return;

        const row = document.querySelector(`button[onclick="deleteUser('${id}')"]`)?.closest('tr');
        let roleText = 'Staff';
        if (row) {
            const roleCell = Array.from(row.querySelectorAll('td')).find(td => /staff|superadmin/i.test(td.textContent));
            if (roleCell) roleText = roleCell.textContent.trim();
        }

        fetch('./super/staff_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=delete&id=${id}`
        })
            .then(async res => {
                await res.text();
                showToast(`✅ Berhasil menghapus ${roleText.charAt(0).toUpperCase() + roleText.slice(1)}.`, "success");

                if (row) row.remove();

                const staffCard = document.querySelector('.card:nth-child(3) p');
                if (staffCard) {
                    const current = parseInt(staffCard.textContent) || 0;
                    staffCard.textContent = `${Math.max(current - 1, 0)} Orang`;
                }
            })
            .catch(() => showToast("❌ Gagal menghapus data staff.", "error"));
    });
}

// === REFRESH STAFF ===
function refreshStaffTableAndCount() {
    const container = document.getElementById('data-section');
    container.innerHTML = `<div class="loading">⏳ Memuat data staff...</div>`;

    // ✅ Ambil ulang data staff dari PHP super_content
    fetch('./super/super_content.php?type=staff')
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;

            // Hitung jumlah staff & admin
            const headers = Array.from(container.querySelectorAll('thead th')).map(th => th.textContent.trim().toLowerCase());
            const roleIndex = headers.findIndex(h => h.includes('role'));

            let totalStaff = 0;
            let totalAdmin = 0;

            if (roleIndex !== -1) {
                container.querySelectorAll('tbody tr').forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const roleText = cells[roleIndex]?.textContent.trim().toLowerCase();
                    if (roleText.includes('staff')) totalStaff++;
                    if (roleText.includes('superadmin')) totalAdmin++;
                });
            }

            const card = document.querySelector('.card:nth-child(3) p');
            if (card) {
                card.textContent = `${totalStaff + totalAdmin} Orang (${totalStaff} Staff + ${totalAdmin} Admin)`;
            }
        })
        .catch(() => showToast("❌ Gagal memuat data staff.", "error"));
}


