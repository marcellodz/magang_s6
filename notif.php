  	<!-- popup -->
	<script src="libpopup2/js/jquery-2.1.3.min.js"></script>
	<script src="libpopup2/js/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="libpopup2/css/sweetalert.css">
	<!-- akhir popup -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  
  <?php
	

	//$err=explode("=",$dec);
						/*
						if(empty($err[1])){
							$errdata=explode("#err=",$iddata);
							$err[1]=$errdata[1];
						}
						*/
						//$segments = explode('/', $_SERVER['REQUEST_URI_PATH']);
						//$domains=$segments[1];
						$segments = explode('/', $_SERVER['REQUEST_URI']);
						
						$domains=explode('?', $segments[1]);
						$domains2=base64_decode($domains[1]);
						$errdata=explode("err=",$domains2);
						$err2=$errdata[1];
						
						if($err2=='kodedaftarsalah'){
							//echo"<p style='color:#ff0000;'>Username dan Password Anda salah.</p>";
							$err="error";
							$alererr="Kode isian salah, silahkan coba kembali.";
							$link = "https://idnextleader.smbbtelkom.ac.id/register";
						}elseif($err2=='pendaftaranberhasil'){
							$err="success";
							$alererr="Hi, Top Students, pendaftaran berhasil. Silahkan login pada website ini";
							$link = "https://idnextleader.smbbtelkom.ac.id/";
						}elseif($err2=='loginkembali'){
							$err="error";
							$alererr="Silahkan Login Untuk mengakses Halaman ini.";
							$link = "https://openhouse.smbbtelkom.ac.id/";
						}elseif($err2=='simpansuksesbio'){
							$err="success";
							$alererr="Data telah tersimpan";
							$link = "../mod/?biodata";
							//echo"<p style='color:#ff0000;'>Akun Anda telah di nonaktifkan. Silahkan hubungi IT Service SMB Telkom University.</p>";
						}elseif($err2=='simpansuksessekolah'){
							$err="success";
							$alererr="Data Sekolah telah tersimpan";
							$link = "../mod/?school";
							
						}elseif($err2=='deletesekolahsukses'){
							$err="success";
							$alererr="Data Sekolah berhasil di reset";
							$link = "../mod/?school";
							
						}elseif($err2=='insertraportsukses'){
							$err="success";
							$alererr="Data raport telah tersimpan";
							$link = "../mod/?nilairaport";
							
						}elseif($err2=='deleteraportsukses'){
							$err="success";
							$alererr="Data Raport berhasil di reset";
							$link = "../mod/?nilairaport";
							
						}elseif($err2=='inputprestasisukses'){
							$err="success";
							$alererr="Data prestasi berhasil di simpan";
							$link = "../mod/?prestasi";
							
						}elseif($err2=='deleteprestasisukses'){
							$err="success";
							$alererr="Data prestasi berhasil di Hapus";
							$link = "../mod/?prestasi";
							
						}elseif($err2=='tambahsosialsukses'){
							$err="success";
							$alererr="Data Social Media berhasil di simpan";
							$link = "../mod/?socialmedia";
							
						}elseif($err2=='deletesosialsukses'){
							$err="success";
							$alererr="Data Social Media berhasil di Hapus";
							$link = "../mod/?socialmedia";
							
						}elseif($err2=='filelimit'){
							$err="error";
							$alererr="File melewati batas upload, Max 1 MB";
							$link = "../mod/?academic";
							
						}elseif($err2=='extlimit'){
							$err="error";
							$alererr="Ekstensi file tidak sesuai, ekstensi yang di izinkan : png/jpg/jpeg/pdf";
							$link = "../mod/?fileupload";
							
						}elseif($err2=='uploadsukses'){
							$err="success";
							$alererr="File berhasil di upload";
							$link = "../mod/?fileupload";
							
						}elseif($err2=='deletefilesukses'){
							$err="success";
							$alererr="File berhasil di hapus";
							$link = "../mod/?fileupload";
							
						}elseif($err2=='deletefilesertifikat'){
							$err="success";
							$alererr="File berhasil di hapus";
							$link = "../mod/?fileupload";
							
						}elseif($err2=='deleteprodi'){
							$err="success";
							$alererr="Prodi berhasil di reset";
							$link = "../mod/?prodi";
							
						}elseif($err2=='prodisubmit'){
							$err="success";
							$alererr="Prodi berhasil di submit";
							$link = "../mod/?prodi";
							
						}elseif($err2=='pesertasubmit'){
							$err="success";
							$alererr="Submit berhasil";
							$link = "../mod/?home";
							
						}elseif($err2=='loginerror'){
							$err="error";
							$alererr="Username / Password Salah";
							$link = "https://openhouse.smbbtelkom.ac.id/";
							
						}elseif($err2=='loginerroraktivasi'){
							$err="error";
							$alererr="Akun Belum di aktivasi, Periksa kembali email anda";
							$link = "https://idnextleader.smbbtelkom.ac.id/";
							
						}elseif($err2=='aksesdenied'){
							$err="error";
							$alererr="Silahkan Login Terlebih dahulu";
							$link = "https://idnextleader.smbbtelkom.ac.id/";
							
						}elseif($err2=='aktivasisukses'){
							$err="success";
							$alererr="Aktivasi akun berhasil, silahkan login menggunakan username/email dan password";
							$link = "https://idnextleader.smbbtelkom.ac.id/";
							
						}elseif($err2=='pinerror'){
							$err="error";
							$alererr="Kode Voucher tidak sesuai, silahkan periksa kembali kode voucher anda";
							$link = "../mod/?aktivasivoucher";
							
						}elseif($err2=='pinused'){
							$err="error";
							$alererr="Kode Voucher telah di gunakan oleh account lain, silahkan hubungi petugas";
							$link = "../mod/?aktivasivoucher";
							
						}elseif($err2=='pinsuccess'){
							$err="success";
							$alererr="Aktivasi PIN berhasil, silahkan melanjutkan proses pendaftaran";
							$link = "../mod/?home";
							
						}elseif($err2=='emailganda'){
							$err="error";
							$alererr="Email telah di gunakan";
							$link = "https://idnextleader.smbbtelkom.ac.id/register";
							
						}elseif($err2=='inputorganisasisukses'){
							$err="success";
							$alererr="organisasi telah ditambahkan";
							$link = "../mod/?organisasi";
							
						}elseif($err2=='deleteorganisasisukses'){
							$err="success";
							$alererr="organisasi telah di hapus";
							$link = "../mod/?organisasi";
							
						}elseif($err2=='koderecoverysalah'){
							$err="error";
							$alererr="Kode recovery account tidak dikenal";
							$link = "../mod/?organisasi";
							
						}elseif($err2=='referralsuccess'){
							$err="success";
							$alererr="Input Referral code success";
							$link = "../mod/?home";
							
						}elseif($err2=='referralfailed'){
							$err="error";
							$alererr="Referral code not found";
							$link = "../mod/?home";
							
						}elseif($err2=='kuisionersubmit'){
							$err="success";
							$alererr="Kuisioner berhasil disimpan";
							$link = "../mod/?biodata";
							
						}elseif($err2=='referralskip'){
							$err="success";
							$alererr="Skip Input Referral Code";
							$link = "../mod/?home";
							
						}elseif($err2=='recoveryerror'){
							$err="error";
							$alererr="Email Tidak ditemukan, silahkan register kembali";
							$link = "https://idnextleader.smbbtelkom.ac.id/register";
							
						}elseif($err2=='recoverysuccess'){
							$err="success";
							$alererr="Perubahan password berhasil dilakukan";
							$link = "https://idnextleader.smbbtelkom.ac.id";
							
						}elseif($err2=='aksesdenied'){
							$err="error";
							$alererr="Silahkan login kembali";
							$link = "https://idnextleader.smbbtelkom.ac.id";
							
						}elseif($err2=='emailnotfound'){
							$err="error";
							$alererr="Email belum terdaftar";
							$link = "https://idnextleader.smbbtelkom.ac.id";
							
						}elseif($err2=='simpandatapendidikansukses'){
							$err="success";
							$alererr="Simpan Data Berhasil";
							$link = "../mod/?academic";
							
						}elseif($err2=='resetdatapendidikansukses'){
							$err="success";
							$alererr="Reset Data Berhasil";
							$link = "../mod/?academic";
							
						}elseif($err2=='resetdatapekerjaansukses'){
							$err="success";
							$alererr="Reset Data Pekerjaan Berhasil";
							$link = "../mod/?employment";
							
						}elseif($err2=='insertdatapekerjaansukses'){
							$err="success";
							$alererr="Simpan Data Pekerjaan Berhasil";
							$link = "../mod/?employment";
							
						}elseif($err2=='simpandataprogramstudi'){
							$err="success";
							$alererr="Simpan Data Program Studi Berhasil";
							$link = "../mod/?study";
							
						}elseif($err2=='resetdataprogramstudi'){
							$err="success";
							$alererr="Reset Data Berhasil";
							$link = "../mod/?study";
							
						}elseif($err2=='uploadphotosukses'){
							$err="success";
							$alererr="Upload Photo Berhasil";
							$link = "../mod/?home";
							
						}
						
						if(!empty($err2)){
							
							
							
						?>
									<div class="showcase">
							</div>
						<script>
						document.querySelector('.showcase')
						swal("<?php echo $err;?>", "<?php echo $alererr;?>", "<?php echo $err;?>");
						window.setTimeout(function(){ window.location = "<?php echo $link ?>"; },2000);
						</script>
						
	<?php
		} 
		
		
		
	?>
						
			