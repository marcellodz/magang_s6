<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Generate QR Code dari Database</title>
    <style>
        .qr-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            display: inline-block;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>Daftar Pengguna dan QR Code</h1>

    <?php
    session_start(); // Wajib: untuk mengakses variabel sesi
    require_once "koneksi.php";
    
    $iduser = $_SESSION['iduser'];
    
    // --- 2. Ambil Data dari Tabel super_user ---
    $sql = "SELECT iduser, nama, email, kelas, jenjang_studi FROM super_user WHERE iduser=$iduser";
    $result = $conn2->query($sql);
    
    $qr_directory = "phpqrcode/"; 

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            
            // 1. Gabungkan Data dalam format JSON (Hanya blok ini yang digunakan)
            $data_user_array = [
                'id' => $row['iduser'],
                'nama' => $row['nama'],
                'email' => $row['email']
            ];
            
            $data_qr = json_encode($data_user_array); // Hasil: {"id":"123","nama":"Budi","email":"budi@mail.com"}
            
            // --- BARIS YANG DIHAPUS/DIKOREKSI KARENA MENGGANGGU FORMAT JSON ---
            // $data_qr = "ID:" . $row['iduser'] . "|Nama:" . $row['nama'] . "|Email:" . $row['email']; // HAPUS BARIS INI!
    
            $filename = $row['iduser'] . "_qr.png";
            $filepath = $qr_directory . $filename;
            
            // 2. Cek apakah file sudah ada, jika belum, generate QR Code
            if (!file_exists($filepath)) {
                // Include library HANYA jika file belum ada
                include "phpqrcode/qrlib.php"; 
                
                // Perintah untuk membuat QR Code dan menyimpannya sebagai file PNG
                QRcode::png($data_qr, $filepath, QR_ECLEVEL_H, 4);
            }
            
            // 3. Tampilkan QR Code menggunakan tag <img> standar
            echo '<div class="qr-container">';
            echo '<h3>' . htmlspecialchars($row['nama']) . '</h3>';
            echo '<p>ID: ' . htmlspecialchars($row['iduser']) . '</p>';
            echo '<img src="' . $filepath . '" alt="QR Code untuk ' . htmlspecialchars($row['nama']) . '">';
            echo '</div>';
        }
    } else {
        echo "0 hasil ditemukan.";
    }
    $conn2->close();
    ?>

    <script>
        // --- Fungsi JavaScript untuk Generate QR Code ---
        function generateQRCode(userId) {
            // Dapatkan elemen container QR Code
            var el = document.getElementById("qrcode-" + userId);
            
            // DEBUG: Cek apakah elemen ditemukan
            if (!el) {
                console.error("Elemen QR Code dengan ID: qrcode-" + userId + " tidak ditemukan!");
                return;
            }
            
            // Ambil data dari atribut data-qr-content
            var content = el.getAttribute("data-qr-content");
            
            // DEBUG: Cek isi data yang akan di-encode
            console.log("Meng-" + "encode data untuk " + userId + ":", content);

            // Buat objek QR Code menggunakan library qrcode.js
            new QRCode(el, {
                text: content,
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H // Level koreksi kesalahan tinggi
            });
        }
    </script>

</body>
</html>