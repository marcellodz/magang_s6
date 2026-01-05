<!-- mod/admin/super_content.php -->

<?php
require_once "../../koneksi.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$limit = 10;
$page = $_POST['page'] ?? 1;
$page = max(1, (int) $page);
$offset = ($page - 1) * $limit;

$type = $_GET['type'] ?? '';
$userRole = $_SESSION['role'] ?? '';

// =======================
// PAGINATION GLOBAL
// =======================
$limit = 10;
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// =======================================
// SWITCH CASE
// =======================================
switch ($type) {

  // ====================================================
  // CASE PESERTA — dengan summary kelas + pagination
  // ====================================================
  case 'peserta':

    // === SUMMARY TETAP ADA (tidak dipagination) ===
    $title = "<i class='fas fa-user-graduate'></i> Data Peserta Open House";
    $editable = false;

    echo "<h3 style='margin-top:25px;color:#ff6666;'>
        <i class='fas fa-chart-bar'></i> Distribusi Peserta Berdasarkan Profesi / Kelas
      </h3>";

    $sqlKelas = "
      SELECT 
        COALESCE(NULLIF(kelas, ''), 'Tidak Diketahui') AS kelas, 
        COUNT(*) AS jumlah
      FROM super_user
      GROUP BY kelas
      ORDER BY jumlah DESC
    ";
    $resKelas = $conn2->query($sqlKelas);

    if ($resKelas->num_rows > 0) {
      echo "<table style='width:100%;margin-top:10px;border-collapse:collapse;color:#fff;'>
        <thead style='background:rgba(255,0,0,0.2);'>
        <tr>
          <th style='padding:10px;'>No</th>
          <th style='padding:10px;'>Profesi / Kelas</th>
          <th style='padding:10px;text-align:right;'>Jumlah Peserta</th>
        </tr>
        </thead><tbody>";

      $no = 1;
      while ($row = $resKelas->fetch_assoc()) {
        echo "<tr>
                  <td style='padding:8px;'>$no</td>
                  <td style='padding:8px;'>{$row['kelas']}</td>
                  <td style='padding:8px;text-align:right;'><i class='fas fa-user'></i> {$row['jumlah']}</td>
                  </tr>";
        $no++;
      }
      echo "</tbody></table>";
    }

    // === QUERY PAGINATION ===
    $query = "
      SELECT nama AS 'Nama Lengkap', email AS 'Email', hp AS 'No. WhatsApp',
             kelas AS 'Profesi / Kelas', provinsi AS 'Provinsi', kota AS 'Kota',
             createdAt AS 'Tanggal Daftar'
      FROM super_user
      ORDER BY createdAt DESC
      LIMIT $limit OFFSET $offset
    ";

    // Hitung total data
    $countQuery = "SELECT COUNT(*) AS total FROM super_user";
    $totalData = $conn2->query($countQuery)->fetch_assoc()['total'];
    $totalPage = ceil($totalData / $limit);

    break;


  // ====================================================
  // CASE BOOTH — pagination normal
  // ====================================================
  case 'booth':

    $title = "<i class='fas fa-store'></i> Data Booth Aktif";
    $editable = ($userRole === 'superadmin');

    $query = "
      SELECT idbooth, nama_booth AS 'Nama Booth', kategori AS 'Kategori', lantai AS 'Lantai', qr_code AS 'QR Code'
      FROM booth
      ORDER BY idbooth ASC
      LIMIT $limit OFFSET $offset
    ";

    $countQuery = "SELECT COUNT(*) AS total FROM booth";
    $totalData = $conn2->query($countQuery)->fetch_assoc()['total'];
    $totalPage = ceil($totalData / $limit);

    break;


  // ====================================================
  // CASE STAFF — pagination
  // ====================================================
  case 'staff':

    $title = "<i class='fas fa-users-cog'></i> Data Staff & Admin";
    $editable = true;

    $query = "
      SELECT id_admin, nama_lengkap AS 'Nama Lengkap', username AS 'Username',
             role AS 'Role', last_login AS 'Terakhir Login'
      FROM admin_user
      ORDER BY CASE WHEN role='superadmin' THEN 0 ELSE 1 END, nama_lengkap ASC
      LIMIT $limit OFFSET $offset
    ";

    $countQuery = "SELECT COUNT(*) AS total FROM admin_user";
    $totalData = $conn2->query($countQuery)->fetch_assoc()['total'];
    $totalPage = ceil($totalData / $limit);

    break;


  // ====================================================
  // CASE KUNJUNGAN — pagination
  // ====================================================
  case 'kunjungan':

    $title = "<i class='fas fa-handshake'></i> Data Kunjungan Booth";
    $editable = false;

    $query = "
      SELECT nama_peserta AS 'Nama Peserta', nama_booth AS 'Booth', kategori AS 'Kategori',
             waktu_kunjungan AS 'Waktu Kunjungan'
      FROM booth_kunjungan
      ORDER BY waktu_kunjungan DESC
      LIMIT $limit OFFSET $offset
    ";

    $countQuery = "SELECT COUNT(*) AS total FROM booth_kunjungan";
    $totalData = $conn2->query($countQuery)->fetch_assoc()['total'];
    $totalPage = ceil($totalData / $limit);

    break;


  // ====================================================
  // CASE KEGIATAN PESERTA — belum pagination, masih asli
  // (Kita akan pagination-kan setelah filter ready)
  // ====================================================
  case 'kegiatan_peserta':

    // Tetap pakai query full dulu (pagination menyusul)
    $query = "
    SELECT id_kegiatan, iduser,
           nama_peserta AS 'Nama Peserta',
           nama_kegiatan AS 'Nama Kegiatan',
           waktu_kegiatan AS 'Waktu Kegiatan'
    FROM kegiatan_peserta
    ORDER BY waktu_kegiatan DESC
";

    $title = "<i class='fas fa-clipboard-list'></i> Manajemen Kegiatan Peserta";
    $editable = false;

    // === HITUNG RINGKASAN PER SESI (1–4) ===
    $sesiSummary = [];
    for ($i = 1; $i <= 4; $i++) {
      $where = ($i == 1)
        ? "nama_kegiatan LIKE '%Fakultas Informatika%' OR nama_kegiatan LIKE '%Teknik Elektro%' OR nama_kegiatan LIKE '%Empathy%' OR nama_kegiatan LIKE '%Parent%'"
        : (($i == 2)
          ? "nama_kegiatan LIKE '%Rekayasa Industri%' OR nama_kegiatan LIKE '%Ilmu Terapan%' OR nama_kegiatan LIKE '%Smart Health%' OR nama_kegiatan LIKE '%Data Sains%'"
          : (($i == 3)
            ? "nama_kegiatan LIKE '%Ekonomi Bisnis%' OR nama_kegiatan LIKE '%Industri Kreatif%' OR nama_kegiatan LIKE '%Robot Mini%' OR nama_kegiatan LIKE '%Tech Meets%'"
            : "nama_kegiatan LIKE '%Komunikasi%' OR nama_kegiatan LIKE '%AI%' OR nama_kegiatan LIKE '%Leisure%' OR nama_kegiatan LIKE '%Logistics%'"));

      $sqlRingkas = "
        SELECT 
          COUNT(*) AS total,
          SUM(CASE WHEN nama_kegiatan LIKE '%Seminar%' THEN 1 ELSE 0 END) AS seminar,
          SUM(CASE WHEN nama_kegiatan LIKE '%Trial%' THEN 1 ELSE 0 END) AS trial,
          MIN(waktu_kegiatan) AS waktu
        FROM kegiatan_peserta
        WHERE ($where)";
      $kegiatan_sesi = $conn2->query($sqlRingkas)->fetch_assoc() ?: [];

      $sesiSummary[$i] = [
        'total' => (int) ($kegiatan_sesi['total'] ?? 0),
        'seminar' => (int) ($kegiatan_sesi['seminar'] ?? 0),
        'trial' => (int) ($kegiatan_sesi['trial'] ?? 0),
        'waktu' => $kegiatan_sesi['waktu'] ?? '-',
      ];
    }

    // === RINGKASAN CAMPUS TOUR PER SESI (1–5) ===
    $tourSummary = [];
    for ($i = 1; $i <= 5; $i++) {
      $sqlTour = "
    SELECT 
      COUNT(*) AS total,
      MIN(waktu_kegiatan) AS waktu
    FROM kegiatan_peserta
    WHERE nama_kegiatan LIKE '%Campus Tour%' 
      AND nama_kegiatan LIKE '%Sesi {$i}%'
  ";
      $tour = $conn2->query($sqlTour)->fetch_assoc() ?: [];
      $tourSummary[$i] = [
        'total' => (int) ($tour['total'] ?? 0),
        'waktu' => $tour['waktu'] ?? '-',
      ];
    }

    // === BOX SUMMARY DI ATAS FILTER ===
    echo "
    <style>
      .summary-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:15px;margin:20px 0;
      }
      .summary-box{
        background:linear-gradient(145deg,rgba(255,0,0,.25),rgba(0,0,0,.2));
        border:1px solid rgba(255,0,0,.3);border-radius:12px;padding:16px;
        color:#fff;text-align:left;box-shadow:0 0 12px rgba(255,0,0,.2);
        transition:.3s
      }
      .summary-box:hover{transform:scale(1.03);box-shadow:0 0 18px rgba(255,0,0,.4)}
      .summary-box h3{color:#ff4d4d;font-size:18px;margin:0 0 8px;font-weight:700}
      .summary-box p{margin:4px 0;font-size:14px;color:#ddd}
    </style>

    <div class='summary-grid'>
      <div class='summary-box' id='summary-sesi1'>
        <h3>Sesi 1</h3>
        <p class='total'>Total Pendaftar: {$sesiSummary[1]['total']}</p>
        <p class='seminar'>Seminar: {$sesiSummary[1]['seminar']}</p>
        <p class='trial'>Trial Class: {$sesiSummary[1]['trial']}</p>
        <p class='waktu'>Waktu: {$sesiSummary[1]['waktu']}</p>
      </div>
      <div class='summary-box' id='summary-sesi2'>
        <h3>Sesi 2</h3>
        <p class='total'>Total Pendaftar: {$sesiSummary[2]['total']}</p>
        <p class='seminar'>Seminar: {$sesiSummary[2]['seminar']}</p>
        <p class='trial'>Trial Class: {$sesiSummary[2]['trial']}</p>
        <p class='waktu'>Waktu: {$sesiSummary[2]['waktu']}</p>
      </div>
      <div class='summary-box' id='summary-sesi3'>
        <h3>Sesi 3</h3>
        <p class='total'>Total Pendaftar: {$sesiSummary[3]['total']}</p>
        <p class='seminar'>Seminar: {$sesiSummary[3]['seminar']}</p>
        <p class='trial'>Trial Class: {$sesiSummary[3]['trial']}</p>
        <p class='waktu'>Waktu: {$sesiSummary[3]['waktu']}</p>
      </div>
      <div class='summary-box' id='summary-sesi4'>
        <h3>Sesi 4</h3>
        <p class='total'>Total Pendaftar: {$sesiSummary[4]['total']}</p>
        <p class='seminar'>Seminar: {$sesiSummary[4]['seminar']}</p>
        <p class='trial'>Trial Class: {$sesiSummary[4]['trial']}</p>
        <p class='waktu'>Waktu: {$sesiSummary[4]['waktu']}</p>
      </div>
    </div>
    ";

    echo "<div class='tour-grid'>
  <div class='tour-box'>
    <h3>Campus Tour - Sesi 1</h3>
    <p class='total'>Total Peserta: {$tourSummary[1]['total']}</p>
    <p class='waktu'>Waktu: {$tourSummary[1]['waktu']}</p>
  </div>
  <div class='tour-box'>
    <h3>Campus Tour - Sesi 2</h3>
    <p class='total'>Total Peserta: {$tourSummary[2]['total']}</p>
    <p class='waktu'>Waktu: {$tourSummary[2]['waktu']}</p>
  </div>
    <div class='tour-box'>
    <h3>Campus Tour - Sesi 3</h3>
    <p class='total'>Total Peserta: {$tourSummary[3]['total']}</p>
    <p class='waktu'>Waktu: {$tourSummary[3]['waktu']}</p>
  </div>
  <div class='tour-box'>
    <h3>Campus Tour - Sesi 4</h3>
    <p class='total'>Total Peserta: {$tourSummary[4]['total']}</p>
    <p class='waktu'>Waktu: {$tourSummary[4]['waktu']}</p>
  </div>
    <div class='tour-box'>
    <h3>Campus Tour - Sesi 5</h3>
    <p class='total'>Total Peserta: {$tourSummary[5]['total']}</p>
    <p class='waktu'>Waktu: {$tourSummary[5]['waktu']}</p>
  </div>
</div>
";

    // === FILTER FORM UI (tetap di bawah summary) ===
    echo "
    <div class='filter-container'>
      <div class='filter-box'>
        <div class='filter-label'>
          <i class='fas fa-filter'></i> 
          <span>Filter Berdasarkan:</span>
        </div>

        <select id='filterSesi'>
          <option value='all'>Semua Sesi</option>
          <option value='1'>Sesi 1</option>
          <option value='2'>Sesi 2</option>
          <option value='3'>Sesi 3</option>
          <option value='4'>Sesi 4</option>
        </select>

        <select id='filterKegiatan' disabled>
          <option value='all'>Semua Kegiatan</option>
        </select>

        <button type='button' id='applyFilter' class='btn-filter'>
          <i class='fas fa-search'></i> Terapkan Filter
        </button>
      </div>
    </div>
    <div id='filterSummary' style='margin-top:10px;color:#aaa;font-size:14px;'></div>
    ";

    break;
  // +===========
  // Reward config
  // =============
  case 'reward_config':
    $title = "<i class='fas fa-gift'></i> Pengaturan Target Reward";
    $editable = ($userRole === 'superadmin');
    $configFile = __DIR__ . '/../super/config/reward_config.php';
    if (!file_exists($configFile)) {
      echo "<p style='color:#f55;text-align:center;'>File konfigurasi belum dibuat.</p>";
      exit;
    }
    $config = include $configFile;

    echo "<h2 style='display:flex;align-items:center;gap:10px;'>{$title}</h2>";
    echo "
    <div style='margin:25px auto;max-width:420px;background:rgba(255,255,255,0.05);padding:20px;border-radius:10px;'>
        <form method='POST' action='super/config/reward_config_update.php'>
            <label style='display:block;margin-bottom:6px;color:#ccc;'>🎓 Target Booth Fakultas</label>
            <input type='number' name='facultyTarget' value='{$config['facultyTarget']}' min='0' required
                style='width:100%;padding:10px;border-radius:8px;border:none;background:#222;color:#fff;margin-bottom:12px;'>

            <label style='display:block;margin-bottom:6px;color:#ccc;'>🏪 Target Booth Lainnya</label>
            <input type='number' name='otherTarget' value='{$config['otherTarget']}' min='0' required
                style='width:100%;padding:10px;border-radius:8px;border:none;background:#222;color:#fff;margin-bottom:20px;'>

            <button type='submit' class='btn-add' style='float:none;width:100%;'>
                <i class='fas fa-save'></i> Simpan Perubahan
            </button>
        </form>
    </div>";

    $modeText = ($config['otherTarget'] > 0)
      ? "<span style='color:#00ff99;'>Mode Normal (Fakultas + Lainnya)</span>"
      : "<span style='color:#66ccff;'>Mode Fakultas Saja</span>";

    echo "<p style='text-align:center;margin-top:10px;color:#aaa;'>Status saat ini: {$modeText}</p>";
    exit;


  default:
    echo "<p style='color:#999;text-align:center;'>Tidak ada data.</p>";
    exit;
}

$result = $conn2->query($query);



echo "<h2 style='display:flex;align-items:center;gap:10px;'>{$title}</h2>";

// Tombol tambah untuk staff & booth
if ($editable) {
  if ($type === 'staff') {
    echo "<button class='btn-add' onclick=\"openForm('add')\">
            <i class='fas fa-plus-circle'></i> Tambah Admin/Staff
          </button>";
  } elseif ($type === 'booth') {
    echo "<button class='btn-add' onclick=\"openBoothForm('add')\">
            <i class='fas fa-plus-circle'></i> Tambah Booth
          </button>";
  }
}

// =====================
//     TAMPILKAN DATA
// =====================

// Untuk 'kegiatan_peserta' tabel dikosongkan dan akan diisi oleh JS (frontend pagination)
if ($type === 'kegiatan_peserta') {
  echo "
    <table>
      <thead>
        <tr>
          <th>id_kegiatan</th>
          <th>iduser</th>
          <th>Nama Peserta</th>
          <th>Nama Kegiatan</th>
          <th>Waktu Kegiatan</th>
        </tr>
      </thead>
      <tbody id='kegiatan-body'>
        <!-- akan diisi oleh pagination_kegiatan.js -->
      </tbody>
    </table>

    <!-- container untuk tombol pagination (frontend) -->
    <div id='kegiatan-pagination' style='margin-top:20px;'></div>
    ";
} else {
  // ===== behavior lama untuk type lain (peserta, booth, staff, kunjungan) tetap dipakai =====
  if ($result && $result->num_rows > 0) {
    echo "<table><thead><tr>";

    $fields = $result->fetch_fields();
    foreach ($fields as $f) {
      if ($editable && ($f->name === "id_admin" || $f->name === "idbooth"))
        continue;
      echo "<th>" . htmlspecialchars($f->name) . "</th>";
    }

    if ($editable)
      echo "<th style='text-align:center;'>Aksi</th>";

    echo "</tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
      $role = strtolower($row['Role'] ?? '');
      $rowClass = $role === 'superadmin' ? 'superadmin-row' : '';

      echo "<tr class='{$rowClass}'>";

      foreach ($row as $key => $value) {
        if ($editable && ($key === "id_admin" || $key === "idbooth"))
          continue;

        echo "<td>" . htmlspecialchars($value ?: '-') . "</td>";
      }

      if ($editable) {
        if ($type === 'staff') {
          echo "<td style='text-align:center;'>
                    <button class='btn-action edit'
                      onclick=\"openForm('edit','{$row['id_admin']}','{$row['Nama Lengkap']}','{$row['Username']}','','{$row['Role']}')\">
                      <i class='fas fa-edit'></i>
                    </button>
                    <button class='btn-action delete' onclick=\"deleteUser('{$row['id_admin']}')\">
                      <i class='fas fa-trash-alt'></i>
                    </button>
                  </td>";
        } elseif ($type === 'booth') {
          echo "<td style='text-align:center;'>
                    <button class='btn-action edit'
                      onclick=\"openBoothForm('edit','{$row['idbooth']}','{$row['Nama Booth']}','{$row['Kategori']}','{$row['Lantai']}')\">
                      <i class='fas fa-edit'></i>
                    </button>
                    <button class='btn-action delete' onclick=\"deleteBooth('{$row['idbooth']}')\">
                      <i class='fas fa-trash-alt'></i>
                    </button>
                    <button class='btn-action qr' 
                    onclick=\"window.open('super/generate_qr_booth.php?idbooth={$row['idbooth']}', '_blank')\">
                    <i class='fas fa-qrcode'></i>
                    </button>
                  </td>";
        }
      }

      echo "</tr>";
    }

    echo "</tbody></table>";
  } else {
    echo "<p style='text-align:center;color:#888;'>Belum ada data pada kategori ini.</p>";
  }
}


?>
<!-- 4/11 -->
<?php if ($type === 'kegiatan_peserta'): ?>
  <script>
    window._kegiatanPesertaData = <?php
    $result2 = $conn2->query($query);
    $rows = [];

    while ($r = $result2->fetch_assoc()) {
      $nama = $r['Nama Kegiatan'] ?? '';
      $nama = preg_replace('/^Trial Class\s*-\s*/i', '', $nama);
      $nama = preg_replace('/^Seminar\s*-\s*/i', '', $nama);
      $nama = trim(preg_replace('/\s+/', ' ', $nama));

      $r['Nama Kegiatan'] = $nama;
      $rows[] = $r;
    }

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    ?>;
  </script>
<?php endif; ?>

<?php if ($type === 'kegiatan_peserta'): ?>
  <script src="super/js/kegiatanData.js" defer></script>
  <script src="super/js/pagination_kegiatan.js" defer></script>
  <script src="super/js/filter_kegiatan.js" defer></script>
<?php endif; ?>


<?php if ($type === 'booth'): ?>
  <script src="super/js/booth.js" defer></script>
<?php elseif ($type === 'staff'): ?>
  <script src="super/js/staff.js" defer></script>
<?php endif; ?>

<link rel="stylesheet" href="super/css/content.css">



<?php
// ============== PAGINATION RENDER BARU ==============
if (isset($totalPage) && $totalPage > 1):

  $limitLinks = 3;
  $half = floor($limitLinks / 2);

  // Tentukan range angka tengah
  $start = max(1, $page - $half);
  $end   = min($totalPage, $start + $limitLinks - 1);

  // Jika mentok kanan, geser kiri
  if ($end - $start + 1 < $limitLinks) {
    $start = max(1, $end - $limitLinks + 1);
  }
?>
  <div class="pagination">

    <!-- Tombol << (first) -->
    <?php if ($page > 1): ?>
      <button class="page-btn" data-page="1" data-type="<?= $type ?>">&laquo;</button>
    <?php endif; ?>

    <!-- Tombol < (prev) -->
    <?php if ($page > 1): ?>
      <button class="page-btn" data-page="<?= $page - 1 ?>" data-type="<?= $type ?>">&lsaquo;</button>
    <?php endif; ?>

    <!-- Angka tengah -->
    <?php for ($i = $start; $i <= $end; $i++): ?>
      <button class="page-btn <?= ($i == $page ? 'active' : '') ?>" 
              data-page="<?= $i ?>" 
              data-type="<?= $type ?>">
        <?= $i ?>
      </button>
    <?php endfor; ?>

    <!-- Tombol > (next) -->
    <?php if ($page < $totalPage): ?>
      <button class="page-btn" data-page="<?= $page + 1 ?>" data-type="<?= $type ?>">&rsaquo;</button>
    <?php endif; ?>

    <!-- Tombol >> (last) -->
    <?php if ($page < $totalPage): ?>
      <button class="page-btn" data-page="<?= $totalPage ?>" data-type="<?= $type ?>">&raquo;</button>
    <?php endif; ?>

  </div>

  <style>
    .pagination {
      margin-top: 20px;
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .page-btn {
      padding: 6px 12px;
      background: #222;
      border: 1px solid #444;
      color: #fff;
      border-radius: 6px;
      cursor: pointer;
      transition: .2s;
    }
    .page-btn.active {
      background: #ff4545;
      border-color: #ff6666;
      font-weight: bold;
    }
    .page-btn:hover {
      background: #333;
    }
  </style>
<?php endif; ?>


<script src="super/js/pagination.js" defer></script>