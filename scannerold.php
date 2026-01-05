<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pemindai QR Code</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        /* CSS sederhana untuk tampilan */
        #reader {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
        #result-container {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>

    <header style="text-align: center;">
        <h1>Pemindai QR Code Web</h1>
        <p>Arahkan kamera HP Anda ke QR Code</p>
    </header>

    <div id="reader"></div>

    <div id="result-container">
        <p>Hasil Pemindaian:</p>
        <strong id="result">Menunggu...</strong>
    </div>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const resultElement = document.getElementById('result');
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            // Preferensi kamera (menggunakan kamera belakang di ponsel)
            facingMode: "environment" 
        };
        
        // Fungsi yang dipanggil ketika QR Code berhasil dipindai
        const onScanSuccess = (decodedText, decodedResult) => {
            // 1. Tampilkan hasilnya di halaman
            resultElement.innerHTML = `<b>${decodedText}</b>`;
            
            // 2. Hentikan pemindaian agar tidak memindai terus-menerus
            html5QrCode.stop().then((ignore) => {
                console.log("Pemindaian dihentikan.");
                
                // 3. Kirim data ke PHP (PENTING!)
                sendDataToPHP(decodedText); 
        
            }).catch((err) => {
                console.error("Gagal menghentikan pemindai:", err);
            });
        };
        
        // Fungsi untuk mengirim data ke file PHP menggunakan AJAX/Fetch API
        function sendDataToPHP(qrData) {
            resultElement.textContent = 'Memproses data di server dan mencatat kunjungan...';
            
            fetch('process_qr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'qr_data=' + encodeURIComponent(qrData) 
            })
            .then(response => response.json()) // Ubah menjadi response.json() untuk data terstruktur
            .then(data => {
                if(data.success && data.redirect_url) {
                    // Jika PHP sukses dan mengembalikan URL, lakukan redirect
                    resultElement.innerHTML = `Pencatatan sukses. Mengalihkan ke: <b>${data.redirect_url}</b>`;
                    window.location.href = data.redirect_url;
                } else {
                    resultElement.innerHTML = `Gagal memproses data: ${data.message || 'Respon tidak valid'}`;
                }
            })
            .catch(error => {
                resultElement.innerHTML = `ERROR: Gagal terhubung ke server.`;
                console.error('Error:', error);
            });
        }
        
        // Mulai pemindaian menggunakan ID elemen (reader) dan konfigurasi
        html5QrCode.start(
            { facingMode: "environment" }, // Gunakan kamera belakang
            config, 
            onScanSuccess, 
            // onScanFailure: function() {} // Opsional: fungsi jika pemindaian gagal
        ).catch((err) => {
            // Tangani error jika gagal mengakses kamera (misal: izin ditolak)
            resultElement.innerHTML = `Gagal mengakses kamera: ${err.message}. Pastikan website berjalan di HTTPS.`;
            console.error("Gagal memulai pemindaian:", err);
        });
    </script>

</body>
</html>

