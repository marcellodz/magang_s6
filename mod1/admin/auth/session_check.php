<?php
session_start();

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Ambil role user dari session
$role = $_SESSION['role'] ?? '';

/**
 * Fungsi untuk membatasi akses halaman berdasarkan role
 * Contoh penggunaan:
 *   requireRole(['super']);
 *   requireRole(['staff', 'super']);
 */
function requireRole($allowedRoles) {
    global $role;
    if (!in_array($role, (array) $allowedRoles)) {
        // Kalau bukan role yang diizinkan
        if ($role === 'staff') {
            header("Location: staff/dashboard_staff.php");
        } elseif ($role === 'super') {
            header("Location: super/dashboard_super.php");
        } else {
            header("Location: auth/login.php");
        }
        exit;
    }
}
?>
