<!-- openhouse.smbbtelkom.ac.id/login-action.php -->

<?php
include "koneksi.php";
session_start();

// Fungsi sanitasi dasar
function RemoveSpecialChar($str) { 
    $str = str_replace(
        ["UNION", "http", "https", "www", ":", "|", "}", "{", ")", ",", ";", "'", '"'],
        ' ', $str
    );
    return preg_replace('/[^A-Za-z0-9 @._\-!?]/', '', trim($str));
}

// Pastikan perintah datang dari form login
if (isset($_POST['command']) && $_POST['command'] === 'Login') {

    $email = RemoveSpecialChar($_POST['email'] ?? '');
    $password = RemoveSpecialChar($_POST['password'] ?? '');
    $password_hash = md5($password);

    if (empty($email) || empty($password)) {
        header("Location: login?err=empty");
        exit;
    }

    // 🧠 Gunakan prepared statement biar aman dari SQL injection
    $stmt = $conn2->prepare("SELECT * FROM super_user WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['aktivasi'] === 'Y') {
            $_SESSION['iduser'] = $user['iduser'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['hp'] = $user['hp'];
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['email'];
            $_SESSION['start_time'] = time();

            header('Location: mod/user/');
            exit;
        } else {
            header("Location: login?err=inactive");
            exit;
        }
    } else {
        header("Location: login?err=wrong");
        exit;
    }

} else {
    header("Location: login?err=invalid");
    exit;
}
?>
