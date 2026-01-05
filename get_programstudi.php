<?php
require_once "koneksi.php";

$PS_TABLE = "programstudi";

$action    = $_POST['action']   ?? '';
$type      = $_POST['type']     ?? '';
$fakultas  = $_POST['fakultas'] ?? '';
$rumpun    = $_POST['rumpun']   ?? '';

if ($action === 'fakultas') {
    if (in_array($type, ['Mahasiswa', 'Fresh Graduate', 'Guru'])) {
        $query = "SELECT DISTINCT fakultas FROM $PS_TABLE 
                  WHERE status IN ('Regular','Pasca','Ekstensi','Lanjutan')
                  AND fakultas NOT IN ('', '-')
                  ORDER BY fakultas ASC";
    } elseif ($type === 'Dosen') {
        $query = "SELECT DISTINCT fakultas FROM $PS_TABLE 
                  WHERE status IN ('Pasca','Doktoral')
                  AND fakultas NOT IN ('', '-')
                  ORDER BY fakultas ASC";
    } else {
        $query = "SELECT DISTINCT fakultas FROM $PS_TABLE 
                  WHERE fakultas NOT IN ('', '-')
                  ORDER BY fakultas ASC";
    }

    $result = mysqli_query($conn, $query);
    echo "<option value=''>Pilih Fakultas</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['fakultas']) . "'>" . htmlspecialchars($row['fakultas']) . "</option>";
    }

} elseif ($action === 'rumpun') {
    $fakultas = mysqli_real_escape_string($conn, $fakultas);
    $query = "SELECT DISTINCT rumpun FROM $PS_TABLE 
              WHERE fakultas = '$fakultas' 
              AND rumpun NOT IN ('', '-')
              ORDER BY rumpun ASC";
    $result = mysqli_query($conn, $query);
    echo "<option value=''>Pilih Rumpun</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['rumpun']) . "'>" . htmlspecialchars($row['rumpun']) . "</option>";
    }

} elseif ($action === 'prodi') {
    $fakultas = mysqli_real_escape_string($conn, $fakultas);
    $rumpun   = mysqli_real_escape_string($conn, $rumpun);
    if (in_array($type, ['Mahasiswa', 'Fresh Graduate', 'Guru'])) {
        $query = "SELECT namaprodi FROM $PS_TABLE 
                  WHERE fakultas='$fakultas' AND rumpun='$rumpun' 
                  AND status IN ('Regular','Pasca','Ekstensi','Lanjutan')
                  AND namaprodi NOT IN ('', '-')
                  ORDER BY namaprodi ASC";
    } elseif ($type === 'Dosen') {
        $query = "SELECT namaprodi FROM $PS_TABLE 
                  WHERE fakultas='$fakultas' AND rumpun='$rumpun' 
                  AND status IN ('Pasca','Doktoral')
                  AND namaprodi NOT IN ('', '-')
                  ORDER BY namaprodi ASC";
    } else {
        $query = "SELECT namaprodi FROM $PS_TABLE 
                  WHERE fakultas='$fakultas' AND rumpun='$rumpun'
                  AND namaprodi NOT IN ('', '-')
                  ORDER BY namaprodi ASC";
    }
    $result = mysqli_query($conn, $query);
    echo "<option value=''>Pilih Program Studi</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['namaprodi']) . "'>" . htmlspecialchars($row['namaprodi']) . "</option>";
    }

} elseif ($action === 'get_by_jenjang') {
    $jenjang = strtoupper(trim($_POST['jenjang_sekarang'] ?? ''));

    switch ($jenjang) {
        case 'D3':
            // Ambil program studi lanjutan/pindahan untuk D3
            $query = "
                SELECT DISTINCT namaprodi 
                FROM programstudi 
                WHERE status = 'Pindahan'
                AND namaprodi NOT IN ('', '-')
                ORDER BY namaprodi ASC
            ";
            break;

        case 'S1':
            // Ambil program Pascasarjana (S2)
            $query = "
                SELECT DISTINCT namaprodi 
                FROM programstudi 
                WHERE status = 'Pasca'
                AND namaprodi NOT IN ('', '-')
                ORDER BY namaprodi ASC
            ";
            break;

        case 'S2':
            // Ambil program Doktoral (S3)
            $query = "
                SELECT DISTINCT namaprodi 
                FROM programstudi 
                WHERE status = 'doktoral'
                AND namaprodi NOT IN ('', '-')
                ORDER BY namaprodi ASC
            ";
            break;

        default:
            echo "<option value=''>Pilih Program Studi Tujuan</option>";
            exit;
    }

    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<option value=''>Tidak ada data untuk jenjang $jenjang</option>";
        exit;
    }

    echo "<option value=''>Pilih Program Studi Tujuan</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['namaprodi']) . "'>" . htmlspecialchars($row['namaprodi']) . "</option>";
    }
}

?>
