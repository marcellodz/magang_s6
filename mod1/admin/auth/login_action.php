<?php
session_start();
require_once __DIR__ . "/../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = $conn2->prepare("SELECT * FROM admin_user WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // perbandingan password
        if ($password === $data['password']) {
            $_SESSION['admin_id'] = $data['id_admin'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            $conn2->query("UPDATE admin_user SET last_login = NOW() WHERE id_admin = " . $data['id_admin']);

            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Password salah!";
        }
    } else {
        $_SESSION['login_error'] = "Username tidak ditemukan!";
    }

    header("Location: login.php");
    exit;
}
