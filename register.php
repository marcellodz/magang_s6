<!-- openhouse.smbbtelkom.ac.id/register.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House Telkom University</title>

    <!-- Font & Icon -->
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link href="templatemo-electric-xtra.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/telu-logo.png" type="image/x-icon">

    <!-- Load jQuery dulu (WAJIB sebelum Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Script lokal repo -->
    <!-- <script src="js/survey_func.js"></script>
    <script src="script.js" defer></script> -->

    <!-- Template Info -->
    <!--
    TemplateMo 596 Electric Xtra
    https://templatemo.com/tm-596-electric-xtra
    -->
</head>


<body>
    <!-- Animated Grid Background -->
    <div class="grid-bg"></div>
    <div class="gradient-overlay"></div>
    <div class="scanlines"></div>

    <!-- Animated Shapes -->
    <div class="shapes-container">
        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-square"></div>
    </div>

    <!-- Floating Particles -->
    <div id="particles"></div>

    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="index" class="logo-link">
                <!--<svg class="logo-svg" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#e74646;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#00B2FF;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <polygon points="20,2 38,14 38,26 20,38 2,26 2,14" fill="none" stroke="url(#logoGradient)" stroke-width="2"/>
                    <polygon points="20,8 32,16 32,24 20,32 8,24 8,16" fill="url(#logoGradient)" opacity="0.3"/>
                    <circle cx="20" cy="20" r="3" fill="url(#logoGradient)"/>
                </svg>-->
                <img src="images/asset-telu.png" alt="" class="logo-svg">
                <span class="logo-text">OPEN HOUSE TELKOM UNIVERSITY</span>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#features" class="nav-link">Features</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>


    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2 class="section-title">Pendaftaran Open House Telkom University</h2>
        <div class="contact-container">
            <div class="contact-form">
                <form action="register-action" method="post" class="contactForm">
                    <!-- form nama -->
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap Anda" required />
                    </div>
                    <!-- form no hp -->
                    <div class="form-group">
                        <label for="hp">No. WhatsApp</label>
                        <!-- 03/11 penambahan js untuk pesan error WA -->
                        <small id="hp-error" style="color:#ff5c5c;font-size:13px;display:none;">Nomor ini sudah
                            terdaftar.</small>
                        <script>
                            $(document).ready(function () {
                                // js untuk no hp/wa cek duplikat
                                $("#hp").on("blur", function () {
                                    const val = $(this).val().trim();
                                    if (!val) return;
                                    $.post("check_duplicate.php", { type: "hp", value: val }, function (res) {
                                        if (res.exists) {
                                            $("#hp-error").show().text("Nomor WhatsApp ini sudah terdaftar.");
                                            $("#hp").addClass("invalid-input");
                                        } else {
                                            $("#hp-error").hide();
                                            $("#hp").removeClass("invalid-input");
                                        }
                                    }, "json");
                                });
                                //JS untuk cek duplikat email juga langsung disini
                                $("#email").on("blur", function () {
                                    const val = $(this).val().trim();
                                    if (!val) return;
                                    $.post("check_duplicate.php", { type: "email", value: val }, function (res) {
                                        if (res.exists) {
                                            $("#email-error").show().text("Email ini sudah digunakan.");
                                            $("#email").addClass("invalid-input");
                                        } else {
                                            $("#email-error").hide();
                                            $("#email").removeClass("invalid-input");
                                        }
                                    }, "json");
                                });
                            });
                        </script>
                        <style>
                            .invalid-input {
                                border: 1px solid #ff3333 !important;
                                background: rgba(255, 0, 0, 0.05);
                            }
                        </style>

                        <input type="tel" name="hp" id="hp" placeholder="Format: 628xxxxxxxxxx"
                            pattern="^62[0-9]{8,15}$" inputmode="numeric" required
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
                    </div>
                    <!-- form email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <small id="email-error" style="color:#ff5c5c;font-size:13px;display:none;">Email ini sudah digunakan.</small>
                        <input type="email" name="email" id="email" placeholder="Masukkan email Anda" required />
                    </div>
                    <!-- form password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" name="password" id="password"
                                placeholder="Masukkan password untuk akun Open House Anda" required />
                            <!-- icon mata -->
                            <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                    </div>
                    <!-- js untuk pengkondisian password -->
                    <script type="text/javascript">
                        document.addEventListener('DOMContentLoaded', function () {
                            const togglePassword = document.getElementById('togglePassword');
                            const password = document.getElementById('password');

                            togglePassword.addEventListener('click', function (e) {
                                // Toggle tipe antara 'password' dan 'text'
                                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                password.setAttribute('type', type);

                                // Toggle ikon mata (eye-slash untuk tersembunyi, eye untuk terlihat)
                                this.classList.toggle('fa-eye');
                                this.classList.toggle('fa-eye-slash');
                            });
                        });
                    </script>

                    <!-- form saya merupakan -->
                    <div class="form-group">
                        <label for="kelas">Saya merupakan</label>
                        <select class="form-control" name="kelas" id="kelas_select" onchange="showForm2()">
                            <option value="">Pilih</option>
                            <option value="12">Siswa Kelas 12</option>
                            <option value="11">Siswa Kelas 11</option>
                            <option value="10">Siswa Kelas 10</option>
                            <option value="Gap Year">Alumni SMA(Gap Year)</option>
                            <option value="Guru">Guru</option>
                            <option value="Orang Tua">Orang Tua Calon Mahasiswa</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Fresh Graduate">Fresh Graduate</option>
                            <option value="Karyawan">Karyawan</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Entrepreneur">Wiraswasta atau Entrepreneur</option>
                        </select>
                    </div>
                    <!-- JS 31 OKTOBER, untuk kondisi ketika default nya form sekolah/institusi di hiden, ketika memilih selain orangtua di munculkan -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const kelasSelect = document.getElementById("kelas_select");
                            const formSekolah = document.getElementById("form_sekolah_wrapper");
                            const sekolahSelect = document.getElementById("sekolah_select");
                            const formSekolahLainnya = document.getElementById("f2");
                            const inputSekolahLainnya = document.getElementById("sekolah_lainnya");

                            function toggleFormSekolah() {
                                const value = kelasSelect.value;

                                if (value && value !== "Orang Tua") {
                                    // Tampilkan form sekolah
                                    formSekolah.style.display = "block";
                                    sekolahSelect.setAttribute("required", "true");
                                } else {
                                    // Sembunyikan form sekolah & form lainnya
                                    formSekolah.style.display = "none";
                                    sekolahSelect.removeAttribute("required");
                                    sekolahSelect.value = "";

                                    if (formSekolahLainnya) formSekolahLainnya.style.display = "none";
                                    if (inputSekolahLainnya) inputSekolahLainnya.value = "";
                                }
                            }

                            // Jalankan saat pertama kali halaman dimuat (untuk kasus reload)
                            toggleFormSekolah();

                            // Jalankan tiap kali user ubah “Saya merupakan”
                            kelasSelect.addEventListener("change", toggleFormSekolah);
                        });
                    </script>


                    <!-- FORM PROVINSI -->
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <select class="form-control" name="provinsi" id="provinsi" required>
                            <option value="">Pilih Provinsi</option>
                            <?php
                            include 'koneksi.php';
                            $sql = "SELECT DISTINCT provinsi FROM porsi_sma ORDER BY provinsi ASC";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . htmlspecialchars($row['provinsi']) . "'>" . htmlspecialchars($row['provinsi']) . "</option>";
                            }
                            mysqli_close($conn);
                            ?>
                        </select>
                        <small class="text-danger" id="error-provinsi"></small>
                    </div>

                    <!-- FORM KOTA -->
                    <div class="form-group">
                        <label for="kota">Kota/Kabupaten</label>
                        <div class="dropdown-wrapper">
                            <select class="form-control" name="kota" id="kota" required>
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                            <small class="text-danger" id="error-kota"></small>
                        </div>
                    </div>
                    <!-- 31 Oktober perubahaan pada kondisi form sekolah, default nya hilang, selain orangtua muncul -->
                    <!-- FORM SEKOLAH -->
                    <div class="form-group" id="form_sekolah_wrapper" style="display:none;">
                        <label for="sekolah">Sekolah/Instansi</label>
                        <div class="dropdown-wrapper">
                            <select class="form-control" name="sekolah" id="sekolah_select" onchange="showForm()">
                                <option value="">Pilih Sekolah/Instansi</option>
                            </select>
                            <small class="text-danger" id="error-sekolah"></small>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function () {

                            // Menampilkan pesan error halus
                            function showError(target, message) {
                                const el = $(target);
                                el.text(message).hide().fadeIn(200);
                                setTimeout(() => { el.fadeOut(400, () => el.text('')); }, 3500);
                            }
                            // Bersihkan pesan error
                            function clearErrors() {
                                $('#error-provinsi, #error-kota, #error-sekolah').text('');
                            }

                            // Saat provinsi berubah
                            $('#provinsi').change(function () {
                                clearErrors();
                                const provinsi = $(this).val();

                                if (!provinsi) {
                                    $('#kota').html('<option value="">Pilih Kota/Kabupaten</option>');
                                    $('#sekolah_select').html('<option value="">Pilih Sekolah/Instansi</option>');
                                    $('#kota, #sekolah_select').attr('data-locked', 'true');
                                    //Tutup form sekolah lainnya kalau provinsi dikosongkan
                                    $('#f2').hide();
                                    $('#sekolah_lainnya').val('');
                                    return;
                                }

                                $('#kota, #sekolah_select').attr('data-locked', 'false'); // buka akses dropdown
                                $('#f2').hide(); // Tutup juga setiap kali ganti provinsi
                                $('#sekolah_lainnya').val(''); // reset input “lainnya”

                                // Ambil data kota via AJAX
                                $.ajax({
                                    url: 'get_kota.php',
                                    type: 'POST',
                                    data: { provinsi: provinsi },
                                    success: function (data) {
                                        $('#kota').html(data);
                                        $('#sekolah_select').html('<option value="">Pilih Sekolah/Instansi</option>');
                                    },
                                    error: function () {
                                        showError('#error-kota', '❌ Gagal memuat data kota.');
                                    }
                                });
                            });

                            // Cegah dropdown kota dibuka kalau provinsi belum dipilih
                            $('#kota').on('mousedown', function (e) {
                                const provinsi = $('#provinsi').val();
                                if (!provinsi) {
                                    e.preventDefault();
                                    showError('#error-kota', '⚠️ Pilih provinsi terlebih dahulu.');
                                }
                            });

                            // Ketika kota dipilih, baru load sekolah
                            $('#kota').change(function () {
                                clearErrors();
                                const kota = $(this).val();
                                const provinsi = $('#provinsi').val();
                                if (!provinsi) {
                                    showError('#error-kota', '⚠️ Pilih provinsi terlebih dahulu.');
                                    return;
                                }
                                if (!kota) {
                                    // Reset dropdown sekolah ke default
                                    $('#sekolah_select').html('<option value="">Pilih Sekolah/Instansi</option>');
                                    $('#sekolah_select').attr('data-locked', 'true');
                                    // Tutup form "sekolah lainnya" jika terbuka
                                    $('#f2').hide();
                                    return;
                                }
                                $('#sekolah_select').attr('data-locked', 'false'); // buka akses dropdown sekolah
                                $.ajax({
                                    url: 'get_sekolah.php',
                                    type: 'POST',
                                    data: { kota: kota },
                                    success: function (data) {
                                        $('#sekolah_select').html(data);
                                        // pastikan form sekolah lainnya disembunyikan setiap ganti kota
                                        $('#f2').hide();
                                    },
                                    error: function () {
                                        showError('#error-sekolah', '❌ Gagal memuat data sekolah.');
                                    }
                                });
                            });
                            // Cegah dropdown sekolah dibuka kalau kota belum dipilih
                            $('#sekolah_select').on('mousedown', function (e) {
                                const kota = $('#kota').val();
                                if (!kota) {
                                    e.preventDefault();
                                    showError('#error-sekolah', '⚠️ Pilih kota terlebih dahulu.');
                                }
                            });
                        });
                        // Input untuk sekolah lainnya
                        function showForm() {
                            const sekolah = document.getElementById("sekolah_select").value;
                            document.getElementById("f2").style.display = (sekolah === "Lainnya") ? "block" : "none";
                        }
                    </script>

                    <style>
                        .error-text {
                            color: #e63946;
                            font-size: 13px;
                            margin-top: 5px;
                            display: inline-block;
                            font-weight: 500;
                        }
                    </style>
                    <!-- form instansi/sekolah LAINNYA-->
                    <div class="form-group" style="display:none" id="f2">
                        <label for="sekolah_lainnya">Sekolah/Instansi Lainnya</label>
                        <input type="text" id="sekolah_lainnya" name="sekolah_lainnya"
                            placeholder="Masukkan nama sekolah/instansi Anda. Jika tidak ada, isi -">
                    </div>
                    <!-- JS untuk kondisi "Saya merupakan" -->
                    <script type="text/javascript">
                        function showForm2() {
                            const value = document.getElementById("kelas_select").value;
                            const f3 = document.getElementById("f3"); // siswa
                            const f5 = document.getElementById("f5"); // mahasiswa/fresh graduate/guru/dosen/karyawan/wiraswasta

                            const prodiSekarang = document.getElementById("prodi_sekarang");
                            const prodiTujuan = document.getElementById("prodi_tujuan");

                            // Reset semua
                            f3.style.display = "none";
                            f5.style.display = "none";

                            // Matikan required dulu semua
                            if (prodiSekarang) prodiSekarang.removeAttribute("required");
                            if (prodiTujuan) prodiTujuan.removeAttribute("required");

                            // Kondisi per role
                            if (["10", "11", "12", "Gap Year"].includes(value)) {
                                // siswa SMA
                                f3.style.display = "block";
                            }
                            else if (["Mahasiswa", "Fresh Graduate", "Guru", "Dosen", "Karyawan", "Entrepreneur"].includes(value)) {
                                // kuliah & profesi
                                f5.style.display = "block";
                                if (prodiSekarang) prodiSekarang.setAttribute("required", "true");
                                if (prodiTujuan) prodiTujuan.setAttribute("required", "true");
                            }
                        }
                    </script>
                    <!-- form jurusan sekolah siswa -->
                    <div class="form-group" style="display:none" id="f3">
                        <label for="jurusan_sekolah">Jurusan Sekolah</label>
                        <select class="form-control" name="jurusan_sekolah" id="jurusan_sekolah">
                            <option value="">Jurusan</option>
                            <option value="IPA">IPA</option>
                            <option value="IPS">IPS</option>
                            <option value="Bahasa">Bahasa</option>
                            <option value="Agama">Agama</option>
                            <option value="SMK Teknik">SMK Teknik</option>
                            <option value="SMK Non-Teknik">SMK Non-Teknik</option>
                            <option value="Kurikulum Merdeka">Kurikulum Merdeka</option>
                        </select>
                        <br><br>
                        <label for="jurusan_minat">Jurusan Yang Diminati</label>
                        <select class="form-control" name="jurusan_minat" id="jurusan_minat">
                            <option value="">Jurusan Yang Diminati</option>
                            <option value="Akuntansi & Keuangan">Akuntansi & Keuangan</option>
                            <option value="Seni">Seni</option>
                            <option value="Game & App Development">Game & App Development</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Energi Terbarukan">Energi Terbarukan</option>
                            <option value="Perhotelan & Pariwisata">Perhotelan & Pariwisata</option>
                            <option value="Teknik Industri & Logistik">Teknik Industri & Logistik</option>
                            <option value="Psikologi">Psikologi</option>
                            <option value="Teknologi Informasi">Teknologi Informasi</option>
                            <option value="Komunikasi & Media">Komunikasi & Media</option>
                            <option value="Bisnis">Bisnis</option>
                        </select>
                    </div>
                    <!--  mahasiswa / fresh graduate / guru / dosen / karyawan / wiraswasta -->
                    <div class="form-group" style="display:none" id="f5">
                        <!-- Jenjang Pendidikan Terakhir -->
                        <label for="prodi_sekarang">Jenjang Pendidikan Terakhir</label>
                        <select class="form-control" name="prodi_sekarang" id="prodi_sekarang" required>
                            <option value="">Pilih Jenjang Pendidikan Terakhir</option>
                            <option value="D3">Diploma (D3)</option>
                            <option value="S1">Sarjana (S1)</option>
                            <option value="S2">Magister (S2)</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <small id="error-prodi-sekarang" class="text-danger" style="display:none;"></small>
                        <br><br>

                        <!-- form minat lanjut -->
                        <div class="form-group">
                            <label for="jenjang_studi">Apakah Anda berminat melanjutkan studi di Telkom
                                University?</label>
                            <select class="form-control" name="jenjang_studi" id="jenjang_studi">
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <!-- Jurusan Tujuan (hidden default) -->
                        <div class="form-group" id="prodi_tujuan_group" style="display:none;">
                            <label for="prodi_tujuan">Program Studi Tujuan di Telkom University</label>
                            <select class="form-control" name="prodi_tujuan" id="prodi_tujuan">
                                <option value="">Pilih program studi tujuan...</option>
                            </select>
                            <small id="error-prodi-tujuan" class="text-danger" style="display:none;"></small>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function () {
                            const jenjangSelect = $("#prodi_sekarang");
                            const jenjangStudiGroup = $("#jenjang_studi").closest(".form-group");
                            const prodiTujuanGroup = $("#prodi_tujuan_group");
                            const selectProdiTujuan = $("#prodi_tujuan");

                            // === Saat user pilih Jenjang Pendidikan ===
                            jenjangSelect.on("change", function () {
                                const val = $(this).val();

                                if (val === "Lainnya" || !val) {
                                    // Sembunyikan kolom minat lanjut & prodi tujuan
                                    jenjangStudiGroup.slideUp(200, function () {
                                        $("#jenjang_studi").prop("required", false); // matikan required
                                    });
                                    prodiTujuanGroup.slideUp(200, function () {
                                        selectProdiTujuan.prop("required", false).val("");
                                    });
                                    $("#jenjang_studi").val(""); // reset value
                                } else {
                                    // Munculkan pertanyaan minat lanjut
                                    jenjangStudiGroup.slideDown(200, function () {
                                        $("#jenjang_studi").prop("required", true); // aktifkan kembali required
                                    });
                                }

                                // Jika “Ya” sudah dipilih sebelumnya dan user ganti jenjang, reload data
                                if ($("#jenjang_studi").val() === "Ya" && val) {
                                    loadProdiTujuan(val);
                                }
                            });

                            // === Toggle tampil/hidden "Program Studi Tujuan" ===
                            $("#jenjang_studi").on("change", function () {
                                const val = $(this).val();

                                if (val === "Ya") {
                                    prodiTujuanGroup.slideDown(200);
                                    selectProdiTujuan.attr("required", true);

                                    const jenjangSekarang = jenjangSelect.val();
                                    if (jenjangSekarang) {
                                        loadProdiTujuan(jenjangSekarang);
                                    } else {
                                        // Kalau belum pilih jenjang, tampilkan default kosong
                                        $("#prodi_tujuan").html("<option value=''>Pilih program studi tujuan...</option>");
                                    }
                                } else {
                                    prodiTujuanGroup.slideUp(200, function () {
                                        selectProdiTujuan.removeAttr("required").val("");
                                    });
                                }
                            });

                            // === Jika user klik dropdown "Program Studi" tanpa pilih jenjang ===
                            $("#prodi_tujuan").on("mousedown", function (e) {
                                const jenjang = jenjangSelect.val();
                                if (!jenjang) {
                                    e.preventDefault(); // cegah dropdown terbuka
                                    showError("#error-prodi-tujuan", " Pilih jenjang pendidikan terakhir terlebih dahulu.");
                                }
                            });
                        });

                        // === Fungsi menampilkan error kecil dengan efek halus ===
                        function showError(target, message) {
                            const el = $(target);
                            el.stop(true, true)
                                .text(message)
                                .fadeIn(200)
                                .delay(2500)
                                .fadeOut(400);
                        }

                        // === Fungsi load daftar prodi via AJAX ===
                        function loadProdiTujuan(jenjangSekarang) {
                            if (!jenjangSekarang || jenjangSekarang === "Lainnya") return;

                            $.ajax({
                                url: "get_programstudi.php",
                                type: "POST",
                                data: { action: "get_by_jenjang", jenjang_sekarang: jenjangSekarang },
                                beforeSend: function () {
                                    $("#prodi_tujuan").html("<option>Memuat data...</option>");
                                },
                                success: function (data) {
                                    if (!data.trim()) {
                                        $("#prodi_tujuan").html("<option value=''>⚠️ Tidak ada program studi untuk jenjang ini</option>");
                                        return;
                                    }
                                    $("#prodi_tujuan").html(data);
                                },
                                error: function () {
                                    $("#prodi_tujuan").html("<option value=''>⚠️ Gagal memuat data</option>");
                                }
                            });
                        }
                    </script>
                    <style>
                        #f5 label {
                            color: #ff6363;
                            font-weight: 600;
                            text-transform: uppercase;
                            font-size: 13px;
                            letter-spacing: 0.4px;
                            margin-bottom: 6px;
                        }

                        #f5 {
                            animation: fadeIn 0.4s ease-in-out;
                            margin-top: 10px;
                        }

                        #f5 .form-control {
                            margin-bottom: 16px;
                        }

                        @keyframes fadeIn {
                            from {
                                opacity: 0;
                                transform: translateY(8px);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    </style>

                    <!-- ================= KEGIATAN PER SESI ================= -->
                    <div class="form-group">
                        <label><b>Kegiatan yang ingin diikuti (opsional)</b></label>
                        <p style="color:#aaa;font-size:13px;margin-top:4px;">
                            Pilih sesi yang ingin kamu ikuti atau tidak mengikuti, anda bisa memnuat beberapa pilihan.
                            <br>
                            Klik pada pilihan sesi yang sama untuk menutup kolom pilihan kegiatan pada sesi tersebut.
                        </p>

                        <div id="sesi_checkbox_list" class="sesi-grid">
                            <label class="sesi-card" data-value="1|09.15 - 10.45">Sesi 1 (09.15 - 10.45)</label>
                            <label class="sesi-card" data-value="2|10.35 - 12.10">Sesi 2 (10.35 - 12.10)</label>
                            <label class="sesi-card" data-value="3|12.00 - 13.35">Sesi 3 (12.00 - 13.35)</label>
                            <label class="sesi-card" data-value="4|13.25 - 15.00">Sesi 4 (13.25 - 15.00)</label>
                            <label class="sesi-card" data-value="none|Tidak Mengikuti">Tidak Mengikuti Kegiatan</label>
                        </div>
                    </div>

                    <div id="multi_kegiatan_container" style="display:none;"></div>

                    <div class="form-group" id="selected_sessions" style="display:none;">
                        <label><b>Kegiatan yang Sudah Kamu Pilih</b></label>
                        <div id="chosenList" class="chosen-box"></div>
                    </div>

                    <script>
                        const kegiatanData = {
                            1: [
                                "Seminar Fakultas Informatika",
                                "Seminar Fakultas Teknik Elektro",
                                "Trial Class 1: Future Preneur - Siap Jadi Pebisnis di Era AI",
                                "Trial Class 2: Decision Making Under Pressure - Jadi Manajer Sehari!",
                                "Trial Class 3: The Power of Empathy - Seni Memahami Perasaan Orang Lain",
                                "Seminar Parent"
                            ],
                            2: [
                                "Seminar Fakultas Rekayasa Industri",
                                "Seminar Fakultas Ilmu Terapan",
                                "Trial Class 1: Media, Mitos, dan Manipulasi - Siapa yang Mengendalikan Narasi?",
                                "Trial Class 2: Smart Health Revolution - Ketika Teknologi Bertemu Tubuh Manusia",
                                "Trial Class 3: Data Sains",
                                "Seminar Double Degree Program"
                            ],
                            3: [
                                "Seminar Fakultas Ekonomi Bisnis",
                                "Seminar Fakultas Industri Kreatif",
                                "Trial Class 1: AI dan Revolusi Sinema - Ketika Mesin Ikut Berkarya",
                                "Trial Class 2: Robot Mini Challenge - Kendalikan Dunia dengan Kode!",
                                "Trial Class 3: Tech Meets Business - Membangun Startup Digital dari Nol",
                            ],
                            4: [
                                "Seminar Fakultas Komunikasi dan Ilmu Sosial",
                                "Trial Class 1: From Human to Machine - Membangun AI yang Bisa Berpikir",
                                "Trial Class 2: Leisure Leadership - Managing People, Places, and Emotions",
                                "Trial Class 3: Build Your Own Logistics Startup - Inovasi di Dunia Pengiriman",
                                "Seminar Minat Bakat"
                            ]
                        };

                        // ===================== LOAD KUOTA KEGIATAN =====================
                        let limitStatus = {};
                        let kuotaLoaded = false;

                        async function loadKegiatanLimit() {
                            try {
                                const res = await fetch('get_kegiatan_limit.php', { cache: "no-store" });
                                if (!res.ok) throw new Error("HTTP " + res.status);
                                const data = await res.json();
                                limitStatus = data;
                                kuotaLoaded = true;
                                console.log("📊 Kuota kegiatan diterima:", limitStatus);
                            } catch (err) {
                                console.warn("❌ Gagal memuat kuota kegiatan:", err);
                            }
                        }

                        // Panggil segera, tapi kita tunggu selesai sebelum render dropdown
                        loadKegiatanLimit();

                        // ===================== FUNGSI CEK STATUS =====================
                        function getKegiatanStatus(name) {
                            const cleanName = name.trim().toLowerCase();
                            for (const key in limitStatus) {
                                if (cleanName.includes(key.trim().toLowerCase())) {
                                    return limitStatus[key]?.status || "tersedia";
                                }
                            }
                            return "tersedia";
                        }

                        // ===================== SISTEM PILIHAN =====================
                        const sesiCards = document.querySelectorAll('.sesi-card');
                        const multiContainer = document.getElementById('multi_kegiatan_container');
                        const chosenList = document.getElementById('chosenList');
                        const selectedContainer = document.getElementById('selected_sessions');
                        const kelasSelect = document.getElementById('kelas_select');

                        window.selectedSessions = {};
                        const renderedSessions = {};
                        const activeSesi = new Set();

                        function isSMA() {
                            const kelas = kelasSelect.value;
                            return ["10", "11", "12"].includes(kelas);
                        }

                        // === Render dropdown dengan status kuota ===
                        async function renderKegiatanDropdown(sesi, waktu) {
                            if (!kuotaLoaded) {
                                await loadKegiatanLimit();
                            }

                            //  31 Oktober nambahin seminar parent cuma bisa dipilih role orang tua ( sampai baris 714 )
                            const fullList = kegiatanData[sesi] || [];
                            const kelas = kelasSelect.value;

                            // 🔹 Filter kegiatan sesuai role
                            let filteredList;

                            if (isSMA()) {
                                // Siswa bisa lihat semua (Trial Class boleh)
                                filteredList = fullList.filter(k => {
                                    // tapi Seminar Parent hanya muncul kalau role = Orang Tua
                                    if (kelas !== "Orang Tua" && k.toLowerCase().includes("seminar parent")) return false;
                                    return true;
                                });
                            } else {
                                // Non siswa: sembunyikan Trial Class + sembunyikan Seminar Parent kalau bukan orang tua
                                filteredList = fullList.filter(k => {
                                    const lower = k.toLowerCase();
                                    if (lower.includes("trial class")) return false;
                                    if (kelas !== "Orang Tua" && lower.includes("seminar parent")) return false;
                                    return true;
                                });
                            }

                            const block = document.createElement('div');
                            block.className = 'form-group fade-in';
                            block.id = `session_block_${sesi}`;

                            // 🔹 generate opsi kegiatan
                            const optionsHTML = filteredList.map(k => {
                                const status = getKegiatanStatus(k);
                                let label = k;
                                let style = "";
                                let disabled = "";

                                if (status === "penuh") {
                                    label += " (Penuh)";
                                    style = "color:#999;";
                                    disabled = "disabled";
                                } else if (status === "hampir") {
                                    label += " (Hampir Penuh)";
                                    style = "color:#ffb84d;font-weight:600;";
                                }

                                return `<option value="${waktu}|${k}" ${disabled} style="${style}">${label}</option>`;
                            }).join('');

                            block.innerHTML = `
        <label><b>Sesi ${sesi} (${waktu})</b></label>
        <select class="form-control kegiatan-dropdown" data-sesi="${sesi}">
            <option value="">Pilih kegiatan...</option>
            ${optionsHTML}
        </select>
    `;
                            // 31 oktober filter milih sesi 4,3,2 -> di rapiin jadi 2,3,4 agar rapi sampai line 794
                            // === SISIPKAN BLOK SESUAI URUTAN SESI (1→2→3→4)
                            const existingBlocks = Array.from(multiContainer.children);
                            const currentNum = parseInt(sesi);

                            if (existingBlocks.length === 0) {
                                // belum ada sesi lain
                                multiContainer.appendChild(block);
                            } else {
                                let inserted = false;
                                for (let i = 0; i < existingBlocks.length; i++) {
                                    const existingNum = parseInt(existingBlocks[i].id.replace("session_block_", ""));
                                    if (currentNum < existingNum) {
                                        multiContainer.insertBefore(block, existingBlocks[i]);
                                        inserted = true;
                                        break;
                                    }
                                }
                                if (!inserted) {
                                    multiContainer.appendChild(block);
                                }
                            }

                            // === Setelah block disisipkan, baru daftarkan event listener
                            const selectEl = block.querySelector('select');

                            // 🔹 Tampilkan toast saat klik kegiatan penuh
                            selectEl.addEventListener('mousedown', function (e) {
                                const opt = e.target;
                                if (opt && opt.disabled) {
                                    e.preventDefault();
                                    showToast(`❌ Kuota untuk "${opt.textContent.replace('(Penuh)', '').trim()}" sudah penuh.`);
                                }
                            });

                            // 🔹 Update daftar pilihan setiap kali user ubah dropdown
                            selectEl.addEventListener('change', function () {
                                const sesiNum = this.dataset.sesi;
                                const val = this.value;
                                if (val) {
                                    window.selectedSessions[sesiNum] = val;
                                } else {
                                    delete window.selectedSessions[sesiNum];
                                }
                                updateChosenList();
                            });

                            // Simpan ke daftar sesi yang sudah dirender
                            renderedSessions[sesi] = block;

                        }

                        //  fungsi toast notifikasi (pakai gaya sama kaya bawah halaman)
                        function showToast(msg) {
                            const toast = document.createElement("div");
                            toast.className = "toast-limit";
                            toast.textContent = msg;
                            document.body.appendChild(toast);

                            setTimeout(() => toast.classList.add("show"), 50);
                            setTimeout(() => toast.classList.remove("show"), 2800);
                            setTimeout(() => toast.remove(), 3500);
                        }

                        // === Klik sesi ===
                        sesiCards.forEach(card => {
                            card.addEventListener('click', async function () {
                                const [sesi, waktu] = this.dataset.value.split('|');

                                if (sesi === "none") {
                                    activeSesi.clear();
                                    Object.keys(window.selectedSessions).forEach(k => delete window.selectedSessions[k]);
                                    multiContainer.innerHTML = '';
                                    sesiCards.forEach(c => c.classList.remove('active'));
                                    this.classList.add('active');
                                    multiContainer.style.display = "none";
                                    selectedContainer.style.display = "none";
                                    window.selectedSessions = { none: "Tidak Mengikuti Kegiatan" };
                                    return;
                                }

                                const noneCard = document.querySelector('.sesi-card[data-value="none|Tidak Mengikuti"]');
                                if (noneCard) noneCard.classList.remove('active');
                                delete window.selectedSessions["none"];

                                if (activeSesi.has(sesi)) {
                                    activeSesi.delete(sesi);
                                    this.classList.remove('active');
                                    if (renderedSessions[sesi]) renderedSessions[sesi].remove();
                                    delete renderedSessions[sesi];
                                } else {
                                    activeSesi.add(sesi);
                                    this.classList.add('active');
                                    await renderKegiatanDropdown(sesi, waktu);
                                }

                                multiContainer.style.display = activeSesi.size > 0 ? 'block' : 'none';
                                updateChosenList();
                            });
                        });

                        // === Daftar kegiatan terpilih ===
                        function updateChosenList() {
                            const sesiKeys = Object.keys(window.selectedSessions);
                            chosenList.innerHTML = '';

                            if (sesiKeys.length === 0 || (sesiKeys.length === 1 && sesiKeys.includes("none"))) {
                                selectedContainer.style.display = 'none';
                                return;
                            }

                            selectedContainer.style.display = 'block';
                            sesiKeys.forEach(sesi => {
                                const val = window.selectedSessions[sesi];
                                const div = document.createElement('div');
                                div.className = 'chosen-item fade-in';
                                div.innerHTML = `
            <div class="chosen-header">
                <b>Sesi ${sesi}</b>
                <button type="button" class="remove-btn" data-sesi="${sesi}">×</button>
            </div>
            <div><span class="activity-tag">${val}</span></div>
        `;
                                chosenList.appendChild(div);
                            });

                            document.querySelectorAll('.remove-btn').forEach(btn => {
                                btn.addEventListener('click', e => {
                                    const sesi = e.currentTarget.dataset.sesi;
                                    delete window.selectedSessions[sesi];
                                    if (renderedSessions[sesi]) renderedSessions[sesi].remove();
                                    const card = document.querySelector(`.sesi-card[data-value^="${sesi}|"]`);
                                    if (card) card.classList.remove('active');
                                    activeSesi.delete(sesi);
                                    updateChosenList();
                                });
                            });
                        }
                    </script>

                    <style>
                        .sesi-grid {
                            display: flex;
                            flex-direction: column;
                            gap: 8px;
                            margin-top: 8px;
                        }

                        .sesi-card {
                            display: block;
                            background: rgba(255, 255, 255, 0.03);
                            border: 1px solid rgba(255, 255, 255, 0.08);
                            border-radius: 10px;
                            padding: 10px 14px;
                            color: #e6f7ff;
                            cursor: pointer;
                            transition: 0.25s;
                            user-select: none;
                        }

                        .sesi-card:hover {
                            background: rgba(0, 178, 255, 0.12);
                            border-color: rgba(0, 178, 255, 0.4);
                        }

                        .sesi-card.active {
                            background: rgba(0, 178, 255, 0.18);
                            border-color: rgba(0, 178, 255, 0.6);
                            box-shadow: 0 0 8px rgba(0, 178, 255, 0.3);
                        }

                        .kegiatan-grid {
                            display: flex;
                            flex-direction: column;
                            gap: 8px;
                            margin-top: 10px;
                        }

                        .kegiatan-card {
                            display: flex;
                            align-items: center;
                            background: rgba(255, 255, 255, 0.03);
                            border: 1px solid rgba(255, 255, 255, 0.08);
                            border-radius: 10px;
                            padding: 10px 14px;
                            cursor: pointer;
                            color: #e6f7ff;
                            transition: 0.3s;
                        }

                        .kegiatan-card:hover {
                            background: rgba(0, 178, 255, 0.12);
                            border-color: rgba(0, 178, 255, 0.4);
                        }

                        .kegiatan-card input {
                            display: none;
                        }

                        .kegiatan-card input:checked+.kegiatan-label {
                            color: #00b2ff;
                            font-weight: 600;
                            text-shadow: 0 0 8px rgba(0, 178, 255, 0.6);
                        }

                        .chosen-box {
                            background: rgba(255, 255, 255, 0.05);
                            border-radius: 12px;
                            padding: 10px 14px;
                            border: 1px solid rgba(255, 255, 255, 0.1);
                        }

                        .chosen-item {
                            background: rgba(0, 178, 255, 0.08);
                            border: 1px solid rgba(0, 178, 255, 0.2);
                            padding: 10px 12px;
                            border-radius: 10px;
                            margin-bottom: 8px;
                        }

                        .chosen-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }

                        .remove-btn {
                            background: none;
                            border: none;
                            color: #ff6363;
                            cursor: pointer;
                            font-size: 16px;
                        }

                        .activity-tag {
                            color: #e3f6ff;
                        }

                        .skip-tag {
                            color: #ff6363;
                        }

                        .fade-in {
                            animation: fadeIn 0.3s ease;
                        }

                        @keyframes fadeIn {
                            from {
                                opacity: 0;
                                transform: translateY(4px);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    </style>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const form = document.querySelector('.contactForm');

                            form.addEventListener('submit', function (e) {
                                let pesanError = [];

                                // --- Validasi umum
                                const nama = document.getElementById('nama').value.trim();
                                const hp = document.getElementById('hp').value.trim();
                                const email = document.getElementById('email').value.trim();
                                const password = document.getElementById('password').value.trim();
                                const provinsi = document.getElementById('provinsi').value;
                                const kota = document.getElementById('kota').value;
                                const sekolah = document.getElementById('sekolah_select').value;
                                const ikutTour = document.getElementById('ikut_tour').value;
                                const campusTourVal = document.getElementById('campus_tour').value;

                                if (!nama) pesanError.push("Nama lengkap wajib diisi.");
                                if (!/^628\d{7,12}$/.test(hp)) pesanError.push("Nomor WhatsApp harus diawali dengan 628 dan berisi 9–14 digit.");
                                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) pesanError.push("Format email tidak valid.");
                                if (password.length < 6) pesanError.push("Password minimal 6 karakter.");
                                if (!provinsi) pesanError.push("Pilih provinsi terlebih dahulu.");
                                if (!kota) pesanError.push("Pilih kota/kabupaten terlebih dahulu.");
                                // 31 OKTOBER penyesuaian ketika user memilih orangtua, maka pesan error sekolah dihilangkan. hanya muncul ketika
                                // user selain orangtua
                                const kelas = document.getElementById('kelas_select').value;
                                if (kelas && kelas !== "Orang Tua" && !sekolah) {
                                    pesanError.push("Pilih sekolah/instansi terlebih dahulu.");
                                }


                                // --- Validasi kegiatan
                                const sesiKeys = Object.keys(window.selectedSessions || {});
                                const hasNone = sesiKeys.includes("none");

                                if (sesiKeys.length === 0) {
                                    pesanError.push("Pilih minimal satu kegiatan atau pilih 'Tidak Mengikuti Kegiatan'.");
                                } else if (!hasNone) {
                                    sesiKeys.forEach(sesi => {
                                        const val = window.selectedSessions[sesi];
                                        if (!val || val === "Tidak Mengikuti Kegiatan") {
                                            pesanError.push(`Pilih kegiatan untuk Sesi ${sesi}.`);
                                        }
                                    });
                                }

                                // --- Validasi Campus Tour
                                if (ikutTour === "ya" && !campusTourVal) {
                                    pesanError.push("Pilih sesi Campus Tour jika ingin ikut.");
                                }

                                // --- Tampilkan error
                                if (pesanError.length > 0) {
                                    e.preventDefault();
                                    let errorBox = document.getElementById('error-box');
                                    if (!errorBox) {
                                        errorBox = document.createElement('div');
                                        errorBox.id = 'error-box';
                                        errorBox.style.background = '#ffeaea';
                                        errorBox.style.color = '#b60000';
                                        errorBox.style.padding = '15px';
                                        errorBox.style.borderRadius = '8px';
                                        errorBox.style.marginBottom = '15px';
                                        errorBox.style.fontWeight = '500';
                                        document.querySelector('.contact-form').prepend(errorBox);
                                    }
                                    errorBox.innerHTML = "<b>⚠️ Mohon periksa kembali data berikut:</b><br>" +
                                        pesanError.map(p => "• " + p).join("<br>");
                                    errorBox.scrollIntoView({ behavior: "smooth" });
                                    return;
                                }

                                // --- Hapus input kegiatan lama
                                document.querySelectorAll("input[name='kegiatan[]']").forEach(el => el.remove());

                                // --- Tambahkan hidden input kegiatan[] ke form
                                if (sesiKeys.length === 0) {
                                    const hidden = document.createElement('input');
                                    hidden.type = 'hidden';
                                    hidden.name = 'kegiatan[]';
                                    hidden.value = 'none|Tidak Mengikuti Kegiatan';
                                    form.appendChild(hidden);
                                } else {
                                    sesiKeys.forEach(sesi => {
                                        const val = window.selectedSessions[sesi];
                                        const hidden = document.createElement('input');
                                        hidden.type = 'hidden';
                                        hidden.name = 'kegiatan[]';
                                        hidden.value = val || 'none|Tidak Mengikuti Kegiatan';
                                        form.appendChild(hidden);
                                    });
                                }

                                console.log("✅ Kegiatan dikirim ke server:", window.selectedSessions);
                            });
                        });
                    </script>

                    <!-- FORM CAMPUS TOUR -->
                    <div class="form-group">
                        <label for="ikut_tour">Ikut Campus Tour?</label>
                        <select class="form-control" name="ikut_tour" id="ikut_tour" required>
                            <option value="">Pilih</option>
                            <option value="ya">Ya</option>
                            <option value="tidak">Tidak</option>
                        </select>
                    </div>

                    <!-- Field sesi Campus Tour (tersembunyi dulu) -->
                    <div class="form-group" id="campus_tour_wrapper" style="display: none;">
                        <label for="campus_tour">Pilih Sesi Campus Tour</label>
                        <select class="form-control" name="campus_tour" id="campus_tour">
                            <option value="">Pilih</option>
                            <option value="10.45 - 11.15|Sesi 1">Sesi 1 (10.45 - 11.15)</option>
                            <option value="11.15 - 11.45|Sesi 2">Sesi 2 (11.15 - 11.45)</option>
                            <option value="13.15 - 13.45|Sesi 3">Sesi 3 (13.15 - 13.45)</option>
                            <option value="13.45 - 14.15|Sesi 4">Sesi 4 (13.45 - 14.15)</option>
                            <option value="14.15 - 14.45|Sesi 5">Sesi 5 (14.15 - 14.45)</option>
                        </select>
                    </div>
                    <!-- Js untuk campus_tour -->
                    <script>
                        const ikutTour = document.getElementById('ikut_tour');
                        const campusTourWrapper = document.getElementById('campus_tour_wrapper');
                        const campusTourSelect = document.getElementById('campus_tour');

                        ikutTour.addEventListener('change', function () {
                            if (this.value === 'ya') {
                                campusTourWrapper.style.display = 'block';
                                campusTourSelect.required = true; // wajib isi kalau ikut
                            } else {
                                campusTourWrapper.style.display = 'none';
                                campusTourSelect.required = false;
                                campusTourSelect.value = ''; // reset pilihan kalau user ganti pikiran
                            }
                        });
                    </script>
                    <!-- FORM TEL-U EXPLORE -->
                    <div class="form-group">
                        <label for="telu_explore">Apakah kamu ingin mengikuti <b>Tel-U Explore</b>?</label>
                        <select class="form-control" name="telu_explore" id="telu_explore" required>
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>

                        <!-- Pesan info -->
                        <div id="info_telu_explore" style="
        display: none;
        margin-top: 10px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(0,178,255,0.4);
        border-left: 4px solid #00b2ff;
        padding: 12px 16px;
        border-radius: 10px;
        color: #e3f6ff;
        font-size: 14px;
        line-height: 1.6;
        box-shadow: 0 0 10px rgba(0,178,255,0.2);
        transition: all 0.3s ease;">
                            <i class="fas fa-clock" style="color:#00b2ff; margin-right:8px;"></i>
                            <b>Informasi:</b> Lakukan registrasi pada pukul
                            <b style="color:#00b2ff;">10.00 - 13.00</b>
                            Jangan lupa datang tepat waktu ya!
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const teluExplore = document.getElementById("telu_explore");
                            const infoBox = document.getElementById("info_telu_explore");

                            teluExplore.addEventListener("change", function () {
                                if (this.value === "Ya") {
                                    infoBox.style.display = "block";
                                    infoBox.style.opacity = 0;
                                    setTimeout(() => {
                                        infoBox.style.opacity = 1;
                                        infoBox.style.transform = "translateY(0)";
                                    }, 50);
                                } else {
                                    infoBox.style.opacity = 0;
                                    setTimeout(() => {
                                        infoBox.style.display = "none";
                                    }, 250);
                                }
                            });
                        });
                    </script>
                    <!-- form informasi yang ingin didapatkan di hilangkan
                    <div class="form-group">
                        <label for="informasi">
                            <b>Informasi yang ingin didapatkan</b> (dapat memilih lebih dari satu)
                        </label>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="info1" name="informasi[]"
                                value="Informasi Kegiatan Open House Telkom University" checked>
                            <label for="info1">Informasi Kegiatan Open House Telkom University</label>
                        </div>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="info2" name="informasi[]" value="Informasi Pengenalan Fakultas">
                            <label for="info2">Informasi Pengenalan Fakultas</label>
                        </div>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="info3" name="informasi[]" value="Penerimaan Mahasiswa Baru 2026">
                            <label for="info3">Penerimaan Mahasiswa Baru 2026</label>
                        </div>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="info4" name="informasi[]" value="Penerimaan Mahasiswa Baru 2027">
                            <label for="info4">Penerimaan Mahasiswa Baru 2027</label>
                        </div>
                    </div> -->
                    <!-- kebijakan privasi -->
                    <div class="form-group">
                        <div class="form-group-checkbox">
                            <input name="kebijakan_privasi" type="checkbox" id="kebijakan_privasi" value="Setuju"
                                required>
                            <label for="kebijakan_privasi" style="font-size:12px;"><b>Saya telah membaca dan menyetujui
                                    <a href="https://smb.telkomuniversity.ac.id/kebijakan-privasi-telkom-university/"
                                        target="_blank" style="color:#ff6363">Kebijakan Privasi</a> yang diberikan oleh
                                    Telkom University</b></label>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="submit-btn">Daftar</button>
                </form>
            </div>

            <div class="contact-info">
                <h3>Connect With Us</h3>
                <div class="info-item">
                    <div class="info-icon">📧</div>
                    <div class="info-details">
                        <h4>Email</h4>
                        <p>contact@electricxtra.tech</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📱</div>
                    <div class="info-details">
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-details">
                        <h4>Location</h4>
                        <p>Neo Tokyo, Sector 7</p>
                    </div>
                </div>
                <div class="map-container">
                    <div class="map-placeholder">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.3389366293327!2d107.62558207572332!3d-6.9692819930313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9bc3974981d%3A0x613eec0feec9fcf7!2sTelkom%20University%20Landmark%20Tower%20(TULT)!5e0!3m2!1sen!2sid!4v1759571561672!5m2!1sen!2sid"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="map-overlay"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/main.js"></script> -->

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#privacy">Privacy Policy</a>
                <a href="#terms">Terms of Service</a>
                <a href="#careers">Careers</a>
            </div>
            <p class="copyright">© 2025 ELECTRIC XTRA. All rights reserved. Building tomorrow, today. | Design: <a
                    href="https://templatemo.com" target="_blank" rel="nofollow noopener">TemplateMo</a></p>
        </div>
    </footer>
    <script src="templatemo-electric-scripts.js"></script>

    <!-- JS untuk validasi form sebelum submit -->
    <script>
        /* === Global Variabel untuk Simpan Pilihan User === */
        window.selectedSessions = {};

        /* === Event: Pilih Sesi === */
        document.getElementById('sesi_kegiatan').addEventListener('change', function () {
            const [sesi, waktu] = this.value.split('|');
            const kegiatanContainer = document.getElementById('kegiatan_container');
            const listKegiatan = document.getElementById('list_kegiatan');

            // Jika "Tidak Mengikuti"
            if (sesi === "none") {
                kegiatanContainer.style.display = "none";
                window.selectedSessions = { none: "Tidak Mengikuti Kegiatan" };
                updateChosenList();
                return;
            }

            // Hapus data "Tidak Mengikuti" jika user memilih sesi lagi
            if (window.selectedSessions["none"]) delete window.selectedSessions["none"];

            if (!sesi) {
                kegiatanContainer.style.display = 'none';
                return;
            }

            // Tampilkan kegiatan dari sesi terpilih
            listKegiatan.innerHTML = '';
            const kegiatanList = kegiatanData[sesi] || [];
            kegiatanList.forEach((item, i) => {
                const id = `keg_${sesi}_${i}`;
                listKegiatan.innerHTML += `
      <label class="kegiatan-card fade-in">
        <input type="radio" name="kegiatan_sesi_${sesi}" id="${id}" value="${waktu}|${item}">
        <span class="kegiatan-label">${item}</span>
      </label>`;
            });

            kegiatanContainer.style.display = 'block';

            listKegiatan.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    window.selectedSessions[sesi] = radio.value;
                    updateChosenList();
                });
            });
        });

        /* === Update Daftar Pilihan yang Sudah Dipilih === */
        function updateChosenList() {
            const selectedContainer = document.getElementById('selected_sessions');
            const chosenList = document.getElementById('chosenList');
            const sesiList = Object.keys(window.selectedSessions);
            chosenList.innerHTML = '';

            if (sesiList.length === 0) {
                selectedContainer.style.display = 'none';
                document.getElementById('sesi_kegiatan').value = "";
                return;
            }

            selectedContainer.style.display = 'block';

            sesiList.forEach(sesi => {
                const val = window.selectedSessions[sesi];
                const isNone = sesi === "none";
                const tag = isNone
                    ? `<span class="skip-tag">${val}</span>`
                    : `<span class="activity-tag">${val}</span>`;

                const box = document.createElement('div');
                box.className = 'chosen-item fade-in';
                box.innerHTML = `
      <div class="chosen-header">
        <b>${isNone ? "Tidak Mengikuti" : "Sesi " + sesi}</b>
        <button type="button" class="remove-btn" data-sesi="${sesi}">×</button>
      </div>
      <div>${tag}</div>`;
                chosenList.appendChild(box);
            });

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const sesi = e.currentTarget.dataset.sesi;
                    delete window.selectedSessions[sesi];
                    updateChosenList();
                });
            });
        }

        /* === Validasi dan Sinkronisasi Kegiatan === */
        document.querySelector('.contactForm').addEventListener('submit', function (e) {
            let pesanError = [];

            // --- Validasi umum
            const nama = document.getElementById('nama').value.trim();
            const hp = document.getElementById('hp').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const provinsi = document.getElementById('provinsi').value;
            const kota = document.getElementById('kota').value;
            const sekolah = document.getElementById('sekolah_select').value;
            const ikutTour = document.getElementById('ikut_tour').value;
            const campusTourVal = document.getElementById('campus_tour').value;

            if (!nama) pesanError.push("Nama lengkap wajib diisi.");
            if (!/^628\d{7,12}$/.test(hp)) pesanError.push("Nomor WhatsApp harus diawali dengan 628 dan berisi 9–14 digit.");
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) pesanError.push("Format email tidak valid.");
            if (password.length < 6) pesanError.push("Password minimal 6 karakter.");
            if (!provinsi) pesanError.push("Pilih provinsi terlebih dahulu.");
            if (!kota) pesanError.push("Pilih kota/kabupaten terlebih dahulu.");
            if (!sekolah) pesanError.push("Pilih sekolah/instansi terlebih dahulu.");

            // --- Validasi kegiatan
            const sesiKeys = Object.keys(window.selectedSessions || {});
            const hasNone = sesiKeys.includes("none");

            if (sesiKeys.length === 0) {
                pesanError.push("Pilih minimal satu kegiatan atau pilih 'Tidak Mengikuti Kegiatan'.");
            } else if (!hasNone) {
                sesiKeys.forEach(sesi => {
                    const val = window.selectedSessions[sesi];
                    if (!val || val === "Tidak Mengikuti Kegiatan") {
                        pesanError.push(`Pilih kegiatan untuk Sesi ${sesi}.`);
                    }
                });
            }

            // --- Validasi Campus Tour
            if (ikutTour === "ya" && !campusTourVal) {
                pesanError.push("Pilih sesi Campus Tour jika ingin ikut.");
            }

            // --- Tampilkan error
            if (pesanError.length > 0) {
                e.preventDefault();
                let errorBox = document.getElementById('error-box');
                if (!errorBox) {
                    errorBox = document.createElement('div');
                    errorBox.id = 'error-box';
                    errorBox.style.background = '#ffeaea';
                    errorBox.style.color = '#b60000';
                    errorBox.style.padding = '15px';
                    errorBox.style.borderRadius = '8px';
                    errorBox.style.marginBottom = '15px';
                    errorBox.style.fontWeight = '500';
                    document.querySelector('.contact-form').prepend(errorBox);
                }
                errorBox.innerHTML = "<b>⚠️ Mohon periksa kembali data berikut:</b><br>" +
                    pesanError.map(p => "• " + p).join("<br>");
                errorBox.scrollIntoView({ behavior: "smooth" });
                return; // stop submit
            }

            // --- Tambahkan hidden input sebelum submit
            document.querySelectorAll("input[name='kegiatan[]']").forEach(el => el.remove());
            const form = this;

            sesiKeys.forEach(sesi => {
                const val = window.selectedSessions[sesi];
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'kegiatan[]';
                hidden.value = val || "Tidak Mengikuti Kegiatan";
                form.appendChild(hidden);
            });
        });
    </script>

    <style>
        .disabled-activity {
            cursor: not-allowed !important;
            filter: grayscale(1);
            opacity: 0.55;
            transition: 0.2s;
        }

        .disabled-activity:hover {
            opacity: 0.75;
        }

        .toast-limit {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #222;
            color: #fff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 14px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s ease;
        }

        .toast-limit.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <!-- JS untuk limit kegiatan -->
    <script>
        $(document).ready(function () {
            // 🔹 Toast notification
            function showToast(msg) {
                const toast = $('<div class="toast-limit"></div>').text(msg);
                $('body').append(toast);
                setTimeout(() => toast.addClass('show'), 50);
                setTimeout(() => toast.removeClass('show'), 2800);
                setTimeout(() => toast.remove(), 3500);
            }

            // 🔹 Ambil data limit dari server
            $.getJSON("get_kegiatan_limit.php", function (data) {
                console.log("📊 Data limit diterima:", data);

                // ====== SEMINAR ======
                $(".seminar-checkbox").each(function () {
                    const label = $(`label[for='${this.id}']`);
                    const name = label.text().split(":").pop().trim(); // ambil nama setelah waktu

                    if (!data[name]) return; // skip kalau ga ada data

                    if (data[name].status === "penuh") {
                        $(this).prop("disabled", true).addClass("disabled-activity");
                        label.css({ color: "#888", textDecoration: "line-through" })
                            .append(" <span style='color:#e74c3c'>(Penuh)</span>");
                        label.on("click", e => {
                            e.preventDefault();
                            showToast("❌ Kuota untuk '" + name + "' sudah penuh.");
                        });
                    } else if (data[name].status === "hampir") {
                        label.append(" <span style='color:#ff9f00'>(Hampir Penuh)</span>");
                    }
                });

                // ====== TRIAL CLASS ======
                $(".trial-checkbox").each(function () {
                    const label = $(`label[for='${this.id}']`);
                    const name = label.text().split(":").pop().trim();

                    if (!data[name]) return;

                    if (data[name].status === "penuh") {
                        $(this).prop("disabled", true).addClass("disabled-activity");
                        label.css({ color: "#888", textDecoration: "line-through" })
                            .append(" <span style='color:#e74c3c'>(Penuh)</span>");
                        label.on("click", e => {
                            e.preventDefault();
                            showToast("❌ Kuota untuk '" + name + "' sudah penuh.");
                        });
                    } else if (data[name].status === "hampir") {
                        label.append(" <span style='color:#ff9f00'>(Hampir Penuh)</span>");
                    }
                });

                // ====== CAMPUS TOUR ======
                $("#campus_tour option").each(function () {
                    const rawText = $(this).text().trim();
                    const match = rawText.match(/Sesi\s*\d+/i);
                    if (!match) return;

                    const sessionName = match[0].trim();
                    const fullName = `Campus Tour - ${sessionName}`;

                    // Coba cari data dengan 2 kemungkinan: dengan prefix dan tanpa prefix
                    const kegiatanData = data[fullName] || data[sessionName];
                    if (!kegiatanData) return;

                    const status = kegiatanData.status;
                    if (status === "penuh") {
                        $(this)
                            .prop("disabled", true)
                            .css("color", "#999")
                            .text(`${rawText} (Penuh)`);
                    } else if (status === "hampir") {
                        $(this)
                            .css("color", "#d9822b")
                            .text(`${rawText} (Hampir Penuh)`);
                    }
                });
                //buat debug 
            }).fail(function () {
                console.warn(" Gagal memuat data dari get_kegiatan_limit.php");
            });
        });
    </script>
<!-- 3/11 cek duplicate email dan no hp  -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".contactForm");
  const emailInput = document.getElementById("email");
  const hpInput = document.getElementById("hp");

  // ==== Inline Error (realtime) ====
  function showInlineError(input, message) {
    let err = input.parentNode.querySelector(".inline-error");
    if (!err) {
      err = document.createElement("small");
      err.className = "inline-error";
      input.parentNode.appendChild(err);
    }
    input.classList.add("input-error");
    err.textContent = message;
  }

  function clearInlineError(input) {
    const err = input.parentNode.querySelector(".inline-error");
    if (err) err.remove();
    input.classList.remove("input-error");
  }

  // ==== Cek ke server ====
  async function checkDuplicate(field, value) {
    try {
      const formData = new FormData();
      formData.append(field, value);
      const res = await fetch("check_duplicate.php", { method: "POST", body: formData });
      const data = await res.json();
      return data;
    } catch (e) {
      console.warn("Error checking duplicate:", e);
      return { success: true };
    }
  }

  // ==== Tampilkan pop-up warning ====
  function showPopup(message) {
    alert(message);//sementar pakai yg gampang dulu, bisa di styling nanti
  }

  // ==== Realtime cek Email ====
  if (emailInput) {
    emailInput.addEventListener("input", async () => {
      const val = emailInput.value.trim();
      clearInlineError(emailInput);
      if (val === "") return;
      const res = await checkDuplicate("email", val);
      if (!res.success && res.field === "email") {
        showInlineError(emailInput, res.message || "Email ini sudah digunakan.");
      }
    });
  }

  // ==== Realtime cek HP ====
  if (hpInput) {
    hpInput.addEventListener("input", async () => {
      const val = hpInput.value.trim();
      clearInlineError(hpInput);
      if (val === "") return;
      const res = await checkDuplicate("hp", val);
      if (!res.success && res.field === "hp") {
        showInlineError(hpInput, res.message || "Nomor WhatsApp ini sudah terdaftar.");
      }
    });
  }

  // ==== Saat tombol daftar ditekan ====
  form.addEventListener("submit", async (e) => {
    const email = emailInput.value.trim();
    const hp = hpInput.value.trim();

    // Jika masih ada inline error aktif
    const emailErr = emailInput.parentNode.querySelector(".inline-error");
    const hpErr = hpInput.parentNode.querySelector(".inline-error");
    if (emailErr || hpErr) {
      e.preventDefault();
      showPopup("Periksa kembali: Email atau nomor WhatsApp sudah digunakan.");
      return;
    }

    // Double check sebelum submit (biar aman)
    if (email) {
      const res = await checkDuplicate("email", email);
      if (!res.success && res.field === "email") {
        e.preventDefault();
        showPopup(res.message || "Email ini sudah digunakan.");
        return;
      }
    }

    if (hp) {
      const res = await checkDuplicate("hp", hp);
      if (!res.success && res.field === "hp") {
        e.preventDefault();
        showPopup(res.message || "Nomor WhatsApp ini sudah terdaftar.");
        return;
      }
    }
  });
});
</script>



</body>

</html>