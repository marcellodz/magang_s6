<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title></title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<link href="css/vendors.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">
    
	<script type="text/javascript">
    function delayedRedirect(){
        window.location = "/mod/index"
    }
    </script>

</head>

<body onLoad="setTimeout('delayedRedirect()', 2000)" style="background-color:#fff;">
<!--<body>-->

<?php
session_start(); // Wajib: untuk mengakses variabel sesi
require_once "koneksi.php";

// Cek apakah sesi 'loggedin' tidak diset ATAU bernilai false
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // --- KODE BARU UNTUK POPUP DAN REDIRECT ---
    $redirect_url = "https://openhouse.smbbtelkom.ac.id/login";
            
    echo "<script type='text/javascript'>";
    echo "alert('Silahkan login terlebih dahulu.');";
    // Menggunakan window.location.href untuk melakukan redirect
    echo "window.location.href = '" . $redirect_url . "';"; 
    echo "</script>";
    exit; // Pastikan script berhenti setelah mencetak JavaScript
}

$iduser = $_SESSION['iduser'];

$user = mysqli_query($conn2,"SELECT * FROM super_user WHERE iduser='$iduser'");
$dtuser = mysqli_fetch_array($user);
$nama = $dtuser['nama'];
$email = $dtuser['email'];
$hp = $dtuser['hp'];

$idbooth = 3;
$booth = mysqli_query($conn2,"SELECT * FROM booth WHERE idbooth=$idbooth");
$dtbooth = mysqli_fetch_array($booth);
$nama_booth = $dtbooth['nama_booth'];

$current_timestamp = date('Y-m-d H:i:s');

$r = mysqli_query($conn2,"INSERT INTO booth_visitor(iduser,nama,email,hp,idbooth,nama_booth,timestamp) VALUES ('$iduser', '$nama', '$email', '$hp', '$idbooth', '$nama_booth', '$current_timestamp') ON DUPLICATE KEY UPDATE timestamp = NOW() ");
    
if(!$r){
    echo "Error: " . mysqli_error($conn2). "<br>";
    echo "$r";
} else {
    // --- KODE BARU UNTUK POPUP DAN REDIRECT ---
    echo "<div id='success'>
            <br>
            <br>
            <center>
            <div class='icon icon--order-success svg'>
                 <svg xmlns='http://www.w3.org/2000/svg' width='72px' height='72px'>
                  <g fill='none' stroke='#8EC343' stroke-width='2'>
                     <circle cx='36' cy='36' r='35' style='stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;'></circle>
                     <path d='M17.417,37.778l9.93,9.909l25.444-25.393' style='stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;'></path>
                  </g>
                 </svg>
             </div>
        	<h4>Terima kasih atas sudah mengisi formulir daftar hadir ini.<br>Nantikan informasi menarik lainnya seputar Telkom University ya. Stay tune! 😊</h4>
        	<small>Kamu akan dialihkan You will be redirect back in 5 seconds.</small>
        	</center>
        </div>
        ";
    exit; // Pastikan script berhenti setelah mencetak JavaScript
}


?>

</body>