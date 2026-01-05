// mod/admin/staff/js/content/js

function toggleHadir(idKegiatan, idUser, btn) {
  const isActive = btn.classList.contains('active');
  const action = isActive ? 'batalkan' : 'hadir';

  fetch(`/openhouse.smbbtelkom.ac.id/mod/admin/staff/update_presensi.php?id_kegiatan=${idKegiatan}&iduser=${idUser}&action=${action}`)
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
        alert(resp.message || "Terjadi kesalahan.");
      }
    })
    .catch(() => alert("Gagal memperbarui status."));
}

//pop up daftar ots
async function daftarOTS(iduser) {
  Swal.fire({
    title: "Daftarkan ke Campus Tour OTS?",
    text: "Peserta akan otomatis dimasukkan ke sesi 3 atau 5 sesuai waktu saat ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Ya, Daftarkan",
    cancelButtonText: "Batal",
    background: "#111",
    color: "#fff",
    confirmButtonColor: "#e60000"
  }).then(async (result) => {
    if (!result.isConfirmed) return;

    try {
      const res = await fetch("/openhouse.smbbtelkom.ac.id/mod/admin/staff/daftar_ots.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ iduser })
      });

      const data = await res.json();

      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Berhasil!",
          text: data.message,
          background: "#111",
          color: "#fff",
          confirmButtonColor: "#e60000"
        }).then(() => location.reload());
      } else if (data.status === "info") {
        Swal.fire({
          icon: "info",
          title: "Sudah Terdaftar",
          text: data.message,
          background: "#111",
          color: "#fff",
          confirmButtonColor: "#e60000"
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: data.message,
          background: "#111",
          color: "#fff",
          confirmButtonColor: "#e60000"
        });
      }
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Tidak dapat menghubungi server.",
        background: "#111",
        color: "#fff",
        confirmButtonColor: "#e60000"
      });
    }
  });
}

// Ini baris penting supaya tombolnya bisa akses fungsi-nya:
window.daftarOTS = daftarOTS;
//======================//


// === Fungsi global untuk menutup hasil scan / popup klaim hadiah ===
function closeScanResult() {
  const resultBox = document.getElementById("scanResult");
  const popup = document.getElementById("scanPopup");
  const footer = document.querySelector("footer");
  const loadingEl = document.getElementById("loading");
  const startBtn = document.getElementById("startScanBtn");

  // Tutup popup mobile (klaim hadiah)
  if (popup) {
    popup.classList.remove("active");
    const popupContent = document.getElementById("popupContent");
    if (popupContent) popupContent.innerHTML = "";
  }

  // Tutup hasil scan desktop
  if (resultBox) {
    resultBox.classList.add("hidden");
    resultBox.innerHTML = "";
  }

  // Reset UI kamera
  if (footer) {
    footer.style.opacity = "1";
    footer.style.pointerEvents = "auto";
  }

  if (loadingEl) {
    loadingEl.innerHTML = '<i class="fas fa-qrcode"></i> Siap untuk scan berikutnya.';
  }

  if (startBtn) {
    startBtn.disabled = false;
    startBtn.innerHTML = '<i class="fas fa-camera"></i> Mulai Scan QR';
  }

  console.log("closeScanResult() executed — result & popup cleared");
}
