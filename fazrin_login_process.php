<?php
// login_process.php
session_start();
include 'db_connect.php'; // koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cek username di tabel login
    $sql = "SELECT * FROM login WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan session
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Arahkan ke halaman dashboard (atau halaman lain sesuai kebutuhan)
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>
                    alert('Password salah!');
                    window.location.href='login.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Username tidak ditemukan!');
                window.location.href='login.php';
              </script>";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
