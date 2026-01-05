// === TOAST CONTAINER ===
console.log("✅ [ui.js] loaded");

if (!document.querySelector('.toast-container')) {
    const div = document.createElement('div');
    div.className = 'toast-container';
    document.body.appendChild(div);
}

// === TOAST FUNCTION FUTURISTIK ===
function showToast(message, type = "success") {
    const container = document.querySelector('.toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    let icon = "fa-check-circle";
    if (type === "error") icon = "fa-times-circle";
    if (type === "warning") icon = "fa-exclamation-triangle";
    toast.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// === POPUP KONFIRMASI FUTURISTIK ===
function showConfirm(message, callback) {
    const overlay = document.createElement('div');
    overlay.className = 'popup-overlay active';
    overlay.innerHTML = `
        <div class="popup">
            <h3><i class="fas fa-exclamation-triangle"></i> Konfirmasi</h3>
            <p>${message}</p>
            <div class="popup-buttons">
                <button class="confirm"><i class="fas fa-check"></i> Ya</button>
                <button class="cancel"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>`;
    document.body.appendChild(overlay);

    overlay.querySelector('.confirm').onclick = () => {
        callback(true);
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 300);
    };
    overlay.querySelector('.cancel').onclick = () => {
        callback(false);
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 300);
    };
}