<?php
require_once "koneksi.php";

/* ===========================================================
   UTILITY CLEANERS
   =========================================================== */
function replacenormal($field) {
    return trim(str_replace(["'", "\n", ","], '', $field));
}

function replacephone($field) {
    $field = preg_replace("/[^0-9]/", "", str_replace(["'", "\n", ",", "?", "+", " "], '', $field));
    if (substr($field, 0, 2) === "08") {
        $field = "62" . substr($field, 1);
    } elseif (substr($field, 0, 2) !== "62") {
        $field = "62" . $field;
    }
    return $field;
}

function cleanData($data) {
    return substr(preg_replace('/[^a-zA-Z0-9\s]/', '', trim($data)), 0, 255);
}

function cleanEmail($data) {
    return trim($data);
}

function RemoveSpecialChar($str) {
    $search = ["UNION", "http", "https", "www", ":", "|", "}", "{", ")", "(", ";", ",", "'", "<", ">"];
    return trim(str_replace($search, '', $str));
}

/* ===========================================================
   MULAI PROSES REGISTER
   =========================================================== */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    /* ---------------- DATA UTAMA ---------------- */
    $sumber_data = "Openhouse";
    $nama        = replacenormal($_POST['nama']);
    $hp          = replacephone($_POST['hp']);
    $email       = cleanEmail($_POST['email']);
    $kode        = RemoveSpecialChar($_POST['password']);
    $password    = md5($kode);
    $kelas       = $_POST['kelas'] ?? '';
    $provinsi    = $_POST['provinsi'] ?? '';
    $kota        = $_POST['kota'] ?? '';

    /* ID Kota */
    $idkota = 0;
    $q = mysqli_query($conn, "SELECT idkota FROM porsi_sma WHERE kota='" . mysqli_real_escape_string($conn, $kota) . "' LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $idkota = (int) mysqli_fetch_assoc($q)['idkota'];
    }

    /* Sekolah */
    $sekolah         = cleanData($_POST['sekolah'] ?? '');
    $sekolah_lainnya = cleanData($_POST['sekolah_lainnya'] ?? '');
    $sekolah_input   = (strcasecmp($sekolah, 'Lainnya') === 0) ? '' : $sekolah;
    $sekolah_lainnya_input = (strcasecmp($sekolah, 'Lainnya') === 0) ? $sekolah_lainnya : '';

    /* Jurusan */
    $jurusan_sekarang = $_POST['prodi_sekarang'] ?? null;
    $jurusan_tujuan   = $_POST['prodi_tujuan'] ?? null;
    $jenjang_studi    = $_POST['jenjang_studi'] ?? '-';

    if ($jurusan_sekarang === 'Lainnya') $jurusan_sekarang = null;
    if ($jurusan_tujuan === '') $jurusan_tujuan = null;

    /* ---------------- KEGIATAN ---------------- */
    $seminar_titles = [];
    $seminar_times  = [];
    $trial_titles   = [];
    $trial_times    = [];

    if (isset($_POST['kegiatan']) && is_array($_POST['kegiatan'])) {

        foreach ($_POST['kegiatan'] as $item) {

            if (stripos($item, 'Tidak Mengikuti') !== false) continue;

            // Format: "09.15 - 10.45 WIB|Seminar Fakultas Informatika"
            $parts = explode("|", $item, 2);
            $waktu = trim($parts[0] ?? '-');
            $judul = trim($parts[1] ?? '');

            if ($judul === '') continue;

            if (stripos($judul, 'Trial Class') !== false) {
                $trial_titles[] = $judul;
                $trial_times[]  = $waktu;
            } else {
                $seminar_titles[] = $judul;
                $seminar_times[]  = $waktu;
            }
        }
    }

    $seminar       = !empty($seminar_titles) ? implode('#', $seminar_titles) : "Tidak Mengikuti";
    $seminar_waktu = !empty($seminar_times)  ? implode('#', $seminar_times)  : "-";

    $trial_class       = !empty($trial_titles) ? implode('#', $trial_titles) : "Tidak Mengikuti";
    $trial_class_waktu = !empty($trial_times)  ? implode('#', $trial_times)  : "-";

    /* ---------------- CAMPUS TOUR ---------------- */
    $campus_tour       = "Tidak Mengikuti";
    $campus_tour_waktu = "-";

    if (!empty($_POST['campus_tour'])) {
        $ct = explode("|", $_POST['campus_tour'], 2);
        $campus_tour_waktu = $ct[0] ?? "-";
        $campus_tour       = $ct[1] ?? $_POST['campus_tour'];
    }

    // FIX: OTS CAMPUS TOUR
    if (isset($_POST['ikut_tour']) && $_POST['ikut_tour'] === "ots") {
        $campus_tour = "Sesi OTS";
        $campus_tour_waktu = "11.15 - 11.45 WIB";
    }

    /* ---------------- Lainnya ---------------- */
    $telu_explore      = $_POST['telu_explore'];
    $kampus            = "Bandung";
    $kebijakan_privasi = $_POST['kebijakan_privasi'] ?? 'Tidak';
    $broadcast         = 'Sudah';
    $tahunsmb          = (date('n') >= 8) ? date('Y') + 1 : date('Y');
    $kegiatan          = "Openhouse";

    /* ===========================================================
       INSERT SUPER USER
       =========================================================== */

    $insert = "
        INSERT INTO super_user (
            sumber_data, kegiatan, nama, hp, email, password, kode, kelas,
            provinsi, kota, idkota, sekolah, sekolah_lainnya,
            jurusan_sekarang, jurusan_tujuan, jenjang_studi,
            campus_tour, campus_tour_waktu, seminar, seminar_waktu,
            trial_class, trial_class_waktu, telu_explore,
            kampus, kebijakan_privasi, tahunsmb, broadcast, aktivasi
        ) VALUES (
            '$sumber_data', '$kegiatan', '$nama', '$hp', '$email', '$password', '$kode', '$kelas',
            '$provinsi', '$kota', '$idkota', '$sekolah_input', '$sekolah_lainnya_input',
            " . ($jurusan_sekarang ? "'$jurusan_sekarang'" : "NULL") . ",
            " . ($jurusan_tujuan   ? "'$jurusan_tujuan'"   : "NULL") . ",
            '$jenjang_studi', '$campus_tour', '$campus_tour_waktu',
            '$seminar', '$seminar_waktu', '$trial_class', '$trial_class_waktu',
            '$telu_explore',
            '$kampus', '$kebijakan_privasi', '$tahunsmb', '$broadcast', 'Y'
        )";

    if (!mysqli_query($conn2, $insert)) {
        die("❌ Gagal menyimpan: " . mysqli_error($conn2));
    }

    $iduser   = mysqli_insert_id($conn2);
    $userData = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT * FROM super_user WHERE iduser='$iduser'"));

    /* ===========================================================
       MAPPING LOKASI + WAKTU dr EXCEl Mb FIFI
       =========================================================== */

    function bersihkan_judul($x) {
        return trim(str_replace(
            ["Seminar - ", "Trial Class - ", "Campus Tour - "],
            "",
            trim($x)
        ));
    }

    $kegiatan_map = [

        // OPENING
        "Registrasi Awal" => [
            "lokasi" => "Gedung Telkom University Landmark Tower Lantai 1",
            "waktu"  => "07.30 WIB - selesai"
        ],

        // --- Sesi 1 ---
        "Seminar Fakultas Informatika" => ["lokasi" => "Gedung Telkom University Landmark Tower Lantai 2", "waktu" => "09.15 - 10.45 WIB"],
        "Seminar Fakultas Teknik Elektro" => ["lokasi" => "Gedung Telkom University Landmark Tower Lantai 16", "waktu" => "09.15 - 10.45 WIB"],
        "Trial Class 1: Future Preneur - Siap Jadi Pebisnis di Era AI" => ["lokasi" => "Gedung Telkom University Landmark Tower Lantai 16", "waktu" => "09.15 - 10.45 WIB"],
        "Trial Class 2: Decision Making Under Pressure: Jadi Manajer Sehari!" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "09.15 - 10.45 WIB"],
        "Trial Class 3: The Power of Empathy: Seni Memahami Perasaan Orang Lain" => ["lokasi" => "Gedung Telkom University LT 18", "waktu" => "09.15 - 10.45 WIB"],
        "Seminar Parent" => ["lokasi" => "Gedung Telkom University LT 1", "waktu" => "10.00 - 13.00 WIB"],
        "Tel-U Explore" => ["lokasi" => "Gedung Telkom University LT 1", "waktu" => "10.00 - 14.00 WIB"],
        "Campus Tour - Sesi 1" => ["lokasi" => "Gedung Telkom University LT 1", "waktu" => "10.45 - 11.15 WIB"],

        // --- Sesi 2 ---
        "Seminar Fakultas Rekayasa Industri" => ["lokasi" => "Gedung Telkom University LT 2", "waktu" => "10.35 - 12.10 WIB"],
        "Seminar Fakultas Ilmu Terapan" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "10.35 - 12.10 WIB"],
        "Trial Class 1: Media, Mitos, dan Manipulasi: Siapa yang Mengendalikan Narasi?" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "10.35 - 12.10 WIB"],
        "Trial Class 2: Smart Health Revolution: Ketika Teknologi Bertemu Tubuh Manusia" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "10.35 - 12.10 WIB"],
        "Trial Class 3: AI vs Human: Siapa yang Lebih Hebat Membaca Pola?" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "10.35 - 12.10 WIB"],
        "Seminar Double Degree" => ["lokasi" => "Gedung Telkom University LT 18", "waktu" => "10.35 - 12.10 WIB"],
        "Campus Tour - Sesi 2" => ["lokasi" => "Gedung Telkom University LT 1", "waktu" => "11.15 - 11.45 WIB"],

        // --- Sesi 3 ---
        "Seminar Fakultas Ekonomi dan Bisnis" => ["lokasi" => "Gedung Telkom University LT 2", "waktu" => "12.00 - 13.35 WIB"],
        "Seminar Fakultas Industri Kreatif" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "12.00 - 13.35 WIB"],
        "Trial Class 1: AI dan Revolusi Sinema: Ketika Mesin Ikut Berkarya" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "12.00 - 13.35 WIB"],
        "Trial Class 2: Robot Mini Challenge: Kendalikan Dunia dengan Kode!" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "12.00 - 13.35 WIB"],
        "Trial Class 3: Tech Meets Business: Membangun Startup Digital dari Nol" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "12.00 - 13.35 WIB"],
        "Seminar Double Degree Program Sesi 2" => ["lokasi" => "Gedung Telkom University LT 18", "waktu" => "12.00 - 13.35 WIB"],

        // --- Sesi 4 ---
        "Seminar Fakultas Komunikasi dan Ilmu Sosial" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "13.25 - 15.00 WIB"],
        "Trial Class 1: From Human to Machine: Membangun AI..." => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "13.25 - 15.00 WIB"],
        "Trial Class 2: Leisure Leadership..." => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "13.25 - 15.00 WIB"],
        "Trial Class 3: Build Your Own Logistics Startup..." => ["lokasi" => "Gedung Telkom University LT 18", "waktu" => "13.25 - 15.00 WIB"],
        "Seminar Minat Bakat" => ["lokasi" => "Gedung Telkom University LT 16", "waktu" => "13.25 - 15.00 WIB"],

        // OTS
        "Campus Tour - Sesi OTS" => ["lokasi" => "Gedung Telkom LT 1", "waktu" => "11.15 - 11.45 WIB"],
    ];

    /* ===========================================================
       BUILD FINAL KEGIATAN PESERTA
       =========================================================== */

    $kegiatan_list = [];

    // Opening
    $kegiatan_list[] = [
        "Registrasi Awal",
        $kegiatan_map["Registrasi Awal"]["lokasi"] . " - Pukul " . $kegiatan_map["Registrasi Awal"]["waktu"]
    ];

    // Campus Tour
    if ($userData['campus_tour'] !== "Tidak Mengikuti") {
        $judul_ct = "Campus Tour - " . $userData['campus_tour'];

        if (isset($kegiatan_map[$judul_ct])) {
            $lok = $kegiatan_map[$judul_ct]["lokasi"];
            $wkt = $kegiatan_map[$judul_ct]["waktu"];
            $kegiatan_list[] = [$judul_ct, "$lok - Pukul $wkt"];
        }
    }

    // Seminar
    if ($userData['seminar'] !== "Tidak Mengikuti") {
        $titles = explode("#", $userData['seminar']);
        foreach ($titles as $title) {
            $clean = bersihkan_judul($title);
            if (isset($kegiatan_map[$clean])) {
                $lok = $kegiatan_map[$clean]["lokasi"];
                $wkt = $kegiatan_map[$clean]["waktu"];
                $kegiatan_list[] = [$clean, "$lok - Pukul $wkt"];
            }
        }
    }

    // Trial Class
    if ($userData['trial_class'] !== "Tidak Mengikuti") {
        $titles = explode("#", $userData['trial_class']);
        foreach ($titles as $title) {
            $clean = bersihkan_judul($title);
            if (isset($kegiatan_map[$clean])) {
                $lok = $kegiatan_map[$clean]["lokasi"];
                $wkt = $kegiatan_map[$clean]["waktu"];
                $kegiatan_list[] = [$clean, "$lok - Pukul $wkt"];
            }
        }
    }

    // Tel-U Explore
    $lok = $kegiatan_map["Tel-U Explore"]["lokasi"];
    $wkt = $kegiatan_map["Tel-U Explore"]["waktu"];
    $kegiatan_list[] = ["Tel-U Explore", "$lok - Pukul $wkt"];

    /* ===========================================================
       INSERT KEGIATAN PESERTA
       =========================================================== */

    foreach ($kegiatan_list as [$nama_kegiatan, $waktu_kegiatan]) {
        mysqli_query($conn2, "
            INSERT INTO kegiatan_peserta (iduser, nama_peserta, nama_kegiatan, waktu_kegiatan)
            VALUES ('$iduser', '{$userData['nama']}', '$nama_kegiatan', '$waktu_kegiatan')
        ");
    }

    /* ===========================================================
       INSERT PRESENSI
       =========================================================== */
    foreach ($kegiatan_list as [$nama, $waktu]) {

        $get = mysqli_query($conn2, "
            SELECT id_kegiatan FROM kegiatan_peserta
            WHERE iduser='$iduser' AND nama_kegiatan='$nama'
            ORDER BY id_kegiatan DESC LIMIT 1
        ");

        $idk = mysqli_fetch_assoc($get)['id_kegiatan'] ?? null;

        if ($idk) {
            mysqli_query($conn2, "
                INSERT INTO presensi_peserta (iduser, nama, email, nama_kegiatan, id_kegiatan, waktu_presensi, status)
                VALUES ('$iduser', '{$userData['nama']}', '{$userData['email']}', '$nama', '$idk', NULL, 'Belum Hadir')
            ");
        }
    }

    header("Location: register-success.php");
    exit;
}
?>
