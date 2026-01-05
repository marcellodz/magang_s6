<?php
require_once "koneksi.php";

// =======================================
// Fungsi buat generate kuota proporsional
// (Base -> +5% dulu, lalu 10% mahasiswa, 90% umum)
// =======================================
function generateQuota($base)
{
    $total = round($base * 1.05); // tambah 5%
    $mahasiswa = round($total * 0.10);
    $umum = $total - $mahasiswa;
    return [
        "total" => $total,
        "mahasiswa" => $mahasiswa,
        "umum" => $umum
    ];
}

// =======================================
// 🧮 Batas Kuota Dasar per Kegiatan
// =======================================
$baseLimit = [
    // === SEMINAR (Lt.2 – kapasitas 500) ===
    "Seminar Fakultas Informatika" => 500,         // FIF
    "Seminar Fakultas Rekayasa Industri" => 500,   // FRI
    "Seminar Fakultas Ekonomi Bisnis" => 500,      // FEB

    // === SEMINAR (Lt.16 – kapasitas 100) ===
    "Seminar Fakultas Teknik Elektro" => 100,      // FTE
    "Seminar Fakultas Ilmu Terapan" => 100,        // FIT
    "Seminar Fakultas Industri Kreatif" => 100,    // FIK
    "Seminar Fakultas Komunikasi dan Ilmu Sosial" => 100, // FKS
    "Seminar Double Degree Program" => 100,

    // === SEMINAR (Lt.18 – kapasitas 30) ===
    "Seminar Parent" => 30,
    "Seminar Pascasarjana" => 30,
    "Seminar International Class" => 30,
    "Seminar Minat Bakat" => 30,
    "Sponsor Session" => 30,
    

    // === TRIAL CLASS (default kapasitas 30) ===
    "Trial Class 1: Future Preneur - Siap Jadi Pebisnis di Era AI" => 30,
    "Trial Class 2: Decision Making Under Pressure - Jadi Manajer Sehari!" => 30,
    "Trial Class 3: The Power of Empathy - Seni Memahami Perasaan Orang Lain" => 30,
    "Trial Class 1: Media, Mitos, dan Manipulasi - Siapa yang Mengendalikan Narasi?" => 30,
    "Trial Class 2: Smart Health Revolution - Ketika Teknologi Bertemu Tubuh Manusia" => 30,
    "Trial Class 3: Data Sains" => 30,
    "Trial Class 1: AI dan Revolusi Sinema - Ketika Mesin Ikut Berkarya" => 30,
    "Trial Class 2: Robot Mini Challenge - Kendalikan Dunia dengan Kode!" => 30,
    "Trial Class 3: Tech Meets Business - Membangun Startup Digital dari Nol" => 30,
    "Trial Class 1: From Human to Machine - Membangun AI yang Bisa Berpikir" => 30,
    "Trial Class 2: Leisure Leadership - Managing People, Places, and Emotions" => 30,
    "Trial Class 3: Build Your Own Logistics Startup - Inovasi di Dunia Pengiriman" => 30,

    // === CAMPUS TOUR ===
    "Sesi 1" => 48,
    "Sesi 2" => 48,
    "Sesi 3" => 48,
    "Sesi 4" => 48,
    "Sesi 5" => 48,
];

// Convert ke format dengan 3 kategori kuota (total / mahasiswa / umum)
$limitList = [];
foreach ($baseLimit as $name => $base) {
    $limitList[$name] = generateQuota($base);
}

// =======================================
//  Hitung Total Peserta per Kegiatan dari DB
// =======================================
$sql = "SELECT nama_kegiatan, COUNT(*) AS total FROM kegiatan_peserta GROUP BY nama_kegiatan";
$res = mysqli_query($conn2, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $namaAsli = trim($row['nama_kegiatan']);
    $total = (int)$row['total'];

    // Bersihkan prefix (kalau ada "Seminar - ", "Trial Class - ", "Campus Tour - ")
    $nama = preg_replace('/^(Seminar|Trial Class|Campus Tour)\s*-\s*/i', '', $namaAsli);
    $nama = trim($nama);

    // Cari key limitList yang cocok
    $limit = $limitList[$nama] ?? null;

    if ($limit === null) {
        // fallback — cari yang mirip (contains)
        foreach ($limitList as $key => $val) {
            if (stripos($nama, $key) !== false) {
                $limit = $val;
                break;
            }
        }
    }

    if ($limit === null) continue; // skip kalau tetap gak ketemu

    // =======================================
    // Tentukan status (penuh / hampir / tersedia)
    // berdasarkan total peserta dibanding kuota total
    // =======================================
    $status = "tersedia";
    $limitTotal = $limit['total'];
    if ($total >= $limitTotal) {
        $status = "penuh";
    } elseif ($total / $limitTotal >= 0.8) { // ambang “hampir penuh” 80%
        $status = "hampir";
    }

    // =======================================
    // Simpan data hasil akhir
    // =======================================
    $data[$nama] = [
        "total_pendaftar" => $total,
        "limit_total" => $limitTotal,
        "limit_mahasiswa" => $limit['mahasiswa'],
        "limit_umum" => $limit['umum'],
        "status" => $status,
        "raw" => $namaAsli
    ];
}

// =======================================
// Output JSON ke frontend
// =======================================
header("Content-Type: application/json");
echo json_encode($data, JSON_PRETTY_PRINT);
?>
