<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Decode QR Code ke Database</title>
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
    <h1>Pindai QR Code Presensi</h1>

    <video id="preview" style="width: 100%; max-width: 400px;"></video>
    
    <form id="presensiForm" method="POST" action="simpan_presensi">
        <input type="hidden" name="qr_data" id="qrDataInput">
        <input type="submit" value="Simpan Presensi" style="display:none;">
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    
    <script>
        const video = document.getElementById('preview');
        const form = document.getElementById('presensiForm');
        const qrDataInput = document.getElementById('qrDataInput');
        let scanning = false;
        
        // Memulai kamera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function(stream) {
                video.srcObject = stream;
                video.setAttribute("playsinline", true); // Penting untuk iOS
                video.play();
                requestAnimationFrame(tick);
            })
            .catch(function(err) {
                console.error("Gagal mengakses kamera: ", err);
                alert("Gagal mengakses kamera. Pastikan izin kamera diberikan.");
            });
        
        // Loop untuk memproses setiap frame video
        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                if (!scanning) {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
        
                    if (code) {
                        scanning = true; // Hentikan pemindaian sementara
                        handleScan(code.data);
                    }
                }
            }
            requestAnimationFrame(tick);
        }
        
        // Fungsi setelah data berhasil dipindai
        function handleScan(data) {
            alert("Data QR Berhasil Dipindai:\n" + data);
            
            // 1. Masukkan data ke input tersembunyi
            qrDataInput.value = data;
            
            // 2. Kirim form secara otomatis ke PHP
            form.submit();
        }
    </script>
    

</body>
</html>