<?php
// Tentukan tipe konten sebagai JSON
header('Content-Type: application/json'); 

$response = [
    'success' => false,
    'message' => 'Data tidak diproses',
    'redirect_url' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_data'])) {
    
    $qr_data = $_POST['qr_data'];
    $clean_url = filter_var($qr_data, FILTER_SANITIZE_URL);
    
    // =========================================================
    // LAKUKAN PROSES DI SINI (CONTOH: MENCATAT LOG)
    // =========================================================
    
    // Verifikasi apakah ini benar-benar URL yang valid
    if (!filter_var($clean_url, FILTER_VALIDATE_URL)) {
        $response['message'] = "Data yang dipindai bukan format URL yang valid.";
    } else {
        try {
            // --- SIMULASI PENCATATAN KE DATABASE ---
            // Gantikan kode ini dengan koneksi database Anda yang sebenarnya
            // $db->query("INSERT INTO log_kunjungan (url) VALUES ('$clean_url')");
            // ----------------------------------------

            $response['success'] = true;
            $response['message'] = "URL berhasil dicatat.";
            // Kembalikan URL yang sudah divalidasi ke JavaScript
            $response['redirect_url'] = $clean_url; 

        } catch (Exception $e) {
            $response['message'] = "Terjadi kesalahan saat mencatat log.";
        }
    }
} else {
    http_response_code(400); 
    $response['message'] = "Permintaan tidak valid.";
}

// Kembalikan hasil dalam format JSON
echo json_encode($response);
?>