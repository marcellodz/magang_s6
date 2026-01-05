<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Registrasi</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
      <h3 class="text-center mb-4">Form Registrasi</h3>

      <form method="post" action="register_process.php">
        
        <div class="mb-3">
          <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
          <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label for="status" class="form-label">Saya merupakan</label>
          <select id="status" name="status" class="form-select" required>
            <option value="">--Pilih--</option>
            <option value="Siswa">Siswa Sekolah</option>
            <option value="Guru">Guru</option>
            <option value="Umum">Umum</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="asal_sekolah" class="form-label">Asal Sekolah/Lembaga</label>
          <input type="text" id="asal_sekolah" name="asal_sekolah" class="form-control" required>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="asal_provinsi" class="form-label">Asal Provinsi</label>
            <input type="text" id="asal_provinsi" name="asal_provinsi" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="asal_kota" class="form-label">Asal Kota/Kabupaten</label>
            <input type="text" id="asal_kota" name="asal_kota" class="form-control" required>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="rencana_jenjang" class="form-label">Rencana Jenjang Studi</label>
          <select name="rencana_jenjang" id="rencana_jenjang" class="form-select">
            <option value="D3">Diploma/D-3</option>
            <option value="S1">Sarjana/S-1</option>
            <option value="S2">Magister/S-2</option>
            <option value="S3">Doktoral/S-3</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="info_iup" class="form-label">Apakah tertarik informasi program International Tel-U?</label>
          <select name="info_iup" id="info_iup" class="form-select">
            <option value="ya">Ya</option>
            <option value="tidak">Tidak</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="fakultas_tujuan" class="form-label">Fakultas Tujuan</label>
          <input type="text" id="fakultas_tujuan" name="fakultas_tujuan" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Informasi yang ingin didapatkan</label>
          <div class="form-check">
            <input type="checkbox" id="info_didapat1" name="info_didapat[]" value="Informasi Kegiatan Open House Tel-U" class="form-check-input">
            <label for="info_didapat1" class="form-check-label">Informasi Kegiatan Open House Tel-U</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="info_didapat2" name="info_didapat[]" value="Informasi Pengenalan Fakultas" class="form-check-input">
            <label for="info_didapat2" class="form-check-label">Informasi Pengenalan Fakultas</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="info_didapat3" name="info_didapat[]" value="Penerimaan Mahasiswa Baru 2026" class="form-check-input">
            <label for="info_didapat3" class="form-check-label">Penerimaan Mahasiswa Baru 2026</label>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="info_wa" class="form-label">Apakah anda bersedia kami berikan informasi melalui pesan Whatsapp?</label>
          <select name="info_wa" id="info_wa" class="form-select">
            <option value="ya">Ya</option>
            <option value="tidak">Tidak</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="no_wa" class="form-label">Nomor Whatsapp</label>
          <input type="text" id="no_wa" name="no_wa" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label for="info_email" class="form-label">Apakah anda bersedia kami berikan informasi melalui Email?</label>
          <select name="info_email" id="info_email" class="form-select">
            <option value="ya">Ya</option>
            <option value="tidak">Tidak</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" id="username" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary btn-lg">Daftar</button>
        </div>

      </form>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
