<!-- mod/admin/index.php -->
<?php
require_once "../koneksi.php";
require_once "auth/session_check.php";

$username = $username ?? ($_SESSION['username'] ?? 'User');
$role     = $role ?? ($_SESSION['role'] ?? 'staff');

// Judul otomatis sesuai role
$pageTitle = ($role === 'superadmin') ? "Dashboard Admin" : "Dashboard Staff";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle; ?> - Open House Telkom University</title>

    <!-- Favicon -->
    <link rel="icon" href="../../images/telu-logo.png" type="image/png">

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/index/style.css">

    <!-- Vendor JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- === BACKGROUND === -->
    <div class="bg-wrapper">
        <div class="grid-bg"></div>
        <div class="gradient-overlay"></div>
        <div class="scanlines"></div>
    </div>

    <!-- === TOPBAR === -->
    <header id="topbar">
        <div class="dashboard-title">
            <i class="fas fa-bolt"></i> <?= $pageTitle; ?>
        </div>

        <div class="profile">
            <button class="profile-btn" id="profileBtn">
                <i class="fas fa-user-circle"></i>
                <?= htmlspecialchars($username); ?>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown" id="profileDropdown">
                <a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </header>

    <main>
        <?php
        if ($role === 'superadmin') {
            include __DIR__ . "/super/dashboard_super.php";
        } else {
            include __DIR__ . "/staff/dashboard_staff.php";
        }
        ?>
    </main>

    <footer>
        <p>© <?= date('Y'); ?> Open House Telkom University | Powered by <a href="#">Electric Xtra</a></p>
    </footer>

    <!-- JS -->
    <script src="js/index/script.js"></script>
    <!-- Load CSS Modular -->
    <link rel="stylesheet" href="super/css/dashboard.css">
    <link rel="stylesheet" href="super/css/ui.css">

    <!-- JS Modular -->
    <script src="super/js/ui.js" defer></script>
    <script src="super/js/staff.js" defer></script>
    <script src="super/js/booth.js" defer></script>
    <script src="super/js/main.js" defer></script>
</body>

</html>