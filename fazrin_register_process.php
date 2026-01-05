<?php
// register_process.php
include 'db_connect.php'; // koneksi database

if (isset($_POST['submit'])) {
    $nama_lengkap    = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $status          = mysqli_real_escape_string($conn, $_POST['status']);
    $asal_sekolah    = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $asal_provinsi   = mysqli_real_escape_string($conn, $_POST['asal_provinsi']);
    $asal_kota       = mysqli_real_escape_string($conn, $_POST['asal_kota']);
    $rencana_jenjang = mysqli_real_escape_string($conn, $_POST['rencana_jenjang']);
    $info_iup        = mysqli_real_escape_string($conn, $_POST['info_iup']);
    $fakultas_tujuan = mysqli_real_escape_string($conn, $_POST['fakultas_tujuan']);
    
    // Checkbox (bisa lebih dari 1)
    $info_didapat    = isset($_POST['info_didapat']) ? implode(", ", $_POST['info_didapat']) : "";

    $info_wa         = mysqli_real_escape_string($conn, $_POST['info_wa']);
    $no_wa           = mysqli_real_escape_string($conn, $_POST['no_wa']);
    $info_email      = mysqli_real_escape_string($conn, $_POST['info_email']);
    $email           = mysqli_real_escape_string($conn, $_POST['email']);
    $username        = mysqli_real_escape_string($conn, $_POST['username']);
    $password        = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Mulai transaksi biar aman
    mysqli_begin_transaction($conn);

    try {
        // Insert ke tabel registrasi
        $sql_reg = "INSERT INTO registrations (
                        nama_lengkap, status, asal_sekolah, asal_provinsi, asal_kota,
                        rencana_jenjang, info_iup, fakultas_tujuan, info_didapat,
                        info_wa, no_wa, info_email, email, created_at
                    ) VALUES (
                        '$nama_lengkap', '$status', '$asal_sekolah', '$asal_provinsi', '$asal_kota',
                        '$rencana_jenjang', '$info_iup', '$fakultas_tujuan', '$info_didapat',
                        '$info_wa', '$no_wa', '$info_email', '$email', NOW()
                    )";

        if (!mysqli_query($conn, $sql_reg)) {
            throw new Exception("Gagal insert registrasi: " . mysqli_error($conn));
        }

        // Insert ke tabel login
        $sql_login = "INSERT INTO login (
                        username, password, created_at
                      ) VALUES (
                        '$username', '$password', NOW()
                      )";

        if (!mysqli_query($conn, $sql_login)) {
            throw new Exception("Gagal insert login: " . mysqli_error($conn));
        }

        // Commit kalau semua berhasil
        mysqli_commit($conn);

        echo "<script>
                alert('Registrasi berhasil!');
                window.location.href = 'login.php';
              </script>";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}

mysqli_close($conn);
?>
