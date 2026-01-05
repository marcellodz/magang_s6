<?php
require_once __DIR__ . '/../../koneksi.php';

$filter_sesi = $_GET['sesi'] ?? 'all';
$filter_kegiatan = $_GET['kegiatan'] ?? 'all';

// Nama file
$sesiNama = ($filter_sesi === 'all') ? 'SemuaSesi' : 'Sesi'.$filter_sesi;
$kegNama  = ($filter_kegiatan === 'all') ? 'SemuaKegiatan' : preg_replace("/[^A-Za-z0-9]/", "", $filter_kegiatan);

$filename = "Kegiatan_{$sesiNama}_{$kegNama}_" . date("Ymd_His") . ".xls";

// Bangun WHERE
$where = "1";

if ($filter_sesi !== "all") {
    $where .= " AND nama_kegiatan LIKE '%Sesi {$filter_sesi}%'";
}

if ($filter_kegiatan !== "all") {
    $where .= " AND nama_kegiatan LIKE '%{$filter_kegiatan}%'";
}

$sql = "
    SELECT 
      nama_peserta AS nama,
      nama_kegiatan AS kegiatan,
      waktu_kegiatan AS waktu
    FROM kegiatan_peserta
    WHERE $where
    ORDER BY waktu_kegiatan DESC
";

$result = $conn2->query($sql);

// Header Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr style='background:#d9534f;color:white;font-weight:bold;'>
        <th>Nama Peserta</th>
        <th>Nama Kegiatan</th>
        <th>Waktu Kegiatan</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td>{$row['kegiatan']}</td>
            <td>{$row['waktu']}</td>
          </tr>";
}

echo "</table>";
exit;
