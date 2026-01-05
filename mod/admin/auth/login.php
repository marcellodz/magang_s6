<!-- mod/admin/auth/login.php -->
<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Open House</title>

    <!-- Favicon -->
    <link rel="icon" href="../../images/telu-logo.png" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/login/style.css">

    <!-- JS -->
    <script src="../js/login/script.js" defer></script>
</head>

<body>
    <form class="login-box" method="POST" action="login_action.php">
        <h2>Admin Login</h2>
        <input type="text" name="username" placeholder="Username" required autocomplete="off" />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login">Masuk</button>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>

    <footer>© <?= date('Y'); ?> Open House Telkom University</footer>
</body>
</html>
