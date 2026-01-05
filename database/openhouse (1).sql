-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jan 2026 pada 20.37
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `openhouse`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_user`
--

CREATE TABLE `admin_user` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('superadmin','staff') DEFAULT 'staff',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_user`
--

INSERT INTO `admin_user` (`id_admin`, `username`, `password`, `nama_lengkap`, `role`, `last_login`) VALUES
(1, 'admin', 'admin123', 'super admin', 'superadmin', '2025-12-18 10:41:18'),
(2, 'staff', 'staff123', 'staff', 'staff', '2025-12-18 12:19:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booth`
--

CREATE TABLE `booth` (
  `idbooth` int(2) NOT NULL,
  `nama_booth` varchar(100) NOT NULL,
  `kategori` enum('Booth Fakultas','Booth Pascasarjana','Booth IUP','Booth Stages','Booth Trial Class','Booth Parent Class','Booth Interaktif') NOT NULL,
  `lantai` int(2) NOT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booth`
--

INSERT INTO `booth` (`idbooth`, `nama_booth`, `kategori`, `lantai`, `qr_code`, `timestamp`) VALUES
(39, 'FTE', 'Booth Fakultas', 1, 'BOOTH-39', '2025-11-02 16:48:45'),
(40, 'FIF', 'Booth Fakultas', 1, 'BOOTH-40', '2025-11-02 16:48:54'),
(41, 'FRI', 'Booth Fakultas', 1, 'BOOTH-41', '2025-11-02 16:49:02'),
(42, 'FIK', 'Booth Fakultas', 1, 'BOOTH-42', '2025-11-02 16:49:09'),
(43, 'FKS', 'Booth Fakultas', 1, 'BOOTH-43', '2025-11-02 16:49:21'),
(44, 'FEB', 'Booth Fakultas', 1, 'BOOTH-44', '2025-11-02 16:49:31'),
(45, 'FIT', 'Booth Fakultas', 1, 'BOOTH-45', '2025-11-02 16:49:45'),
(46, 'Lainnya1', 'Booth Pascasarjana', 2, 'BOOTH-46', '2025-11-02 16:57:33'),
(50, 'Lainnya2', 'Booth Pascasarjana', 2, 'BOOTH-50', '2025-11-07 12:54:53'),
(51, 'rrrrr', 'Booth Fakultas', 4, 'BOOTH-51', '2025-11-17 09:11:52'),
(52, 'eeee', 'Booth Fakultas', 6, NULL, '2025-11-17 09:12:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booth_kunjungan`
--

CREATE TABLE `booth_kunjungan` (
  `id_kunjungan` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `nama_peserta` varchar(100) DEFAULT NULL,
  `idbooth` int(11) DEFAULT NULL,
  `nama_booth` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `lantai` varchar(50) DEFAULT NULL,
  `waktu_kunjungan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `booth_visitor`
--

CREATE TABLE `booth_visitor` (
  `id` int(5) NOT NULL,
  `iduser` int(8) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `idbooth` int(2) NOT NULL,
  `nama_booth` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_peserta`
--

CREATE TABLE `kegiatan_peserta` (
  `id_kegiatan` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `nama_peserta` varchar(255) DEFAULT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `waktu_kegiatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi_peserta`
--

CREATE TABLE `presensi_peserta` (
  `id_presensi` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama_kegiatan` varchar(150) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `waktu_presensi` datetime DEFAULT NULL,
  `status` enum('Belum Hadir','Hadir','Tidak Hadir') DEFAULT 'Belum Hadir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reward_claim`
--

CREATE TABLE `reward_claim` (
  `id` int(10) UNSIGNED NOT NULL,
  `iduser` int(10) UNSIGNED NOT NULL,
  `staff_id` int(10) UNSIGNED DEFAULT NULL,
  `waktu_klaim` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('approved','pending','rejected') NOT NULL DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `super_user`
--

CREATE TABLE `super_user` (
  `iduser` int(8) NOT NULL,
  `sumber_data` varchar(150) NOT NULL,
  `kegiatan` varchar(250) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `kode` text NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `provinsi` varchar(200) NOT NULL,
  `kota` varchar(200) NOT NULL,
  `idkota` int(4) NOT NULL,
  `sekolah` text NOT NULL,
  `sekolah_lainnya` varchar(250) NOT NULL,
  `jurusan_sekarang` varchar(255) DEFAULT NULL,
  `jurusan_tujuan` varchar(255) DEFAULT NULL,
  `jenjang_studi` varchar(50) NOT NULL,
  `campus_tour` varchar(50) NOT NULL,
  `campus_tour_waktu` varchar(100) DEFAULT NULL,
  `seminar` text NOT NULL,
  `seminar_waktu` varchar(100) DEFAULT NULL,
  `trial_class` varchar(100) DEFAULT NULL,
  `trial_class_waktu` varchar(100) DEFAULT NULL,
  `telu_explore` enum('Ya','Tidak') DEFAULT 'Tidak',
  `informasi` text NOT NULL,
  `kampus` enum('Bandung','Jakarta','Surabaya','Purwokerto') DEFAULT NULL,
  `kebijakan_privasi` varchar(20) NOT NULL,
  `tahunsmb` int(4) NOT NULL,
  `broadcast` enum('Belum','Sudah') DEFAULT 'Belum',
  `aktivasi` char(1) NOT NULL DEFAULT 'Y',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `super_user`
--
DELIMITER $$
CREATE TRIGGER `sync_nama_peserta_all` AFTER UPDATE ON `super_user` FOR EACH ROW BEGIN
    -- Sinkronisasi nama ke booth_kunjungan
    IF NEW.nama <> OLD.nama THEN
        UPDATE booth_kunjungan
        SET nama_peserta = NEW.nama
        WHERE iduser = NEW.iduser;
    END IF;

    -- Sinkronisasi nama ke kegiatan_peserta
    IF NEW.nama <> OLD.nama THEN
        UPDATE kegiatan_peserta
        SET nama_peserta = NEW.nama
        WHERE iduser = NEW.iduser;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_points`
--

CREATE TABLE `user_points` (
  `iduser` int(11) NOT NULL,
  `total_point` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_claimed_reward` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `booth`
--
ALTER TABLE `booth`
  ADD PRIMARY KEY (`idbooth`),
  ADD UNIQUE KEY `qr_code` (`qr_code`);

--
-- Indeks untuk tabel `booth_kunjungan`
--
ALTER TABLE `booth_kunjungan`
  ADD PRIMARY KEY (`id_kunjungan`),
  ADD KEY `booth_kunjungan_ibfk_1` (`iduser`);

--
-- Indeks untuk tabel `booth_visitor`
--
ALTER TABLE `booth_visitor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_booth_visitor_aksi_update` (`iduser`,`idbooth`,`email`,`hp`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idbooth` (`idbooth`),
  ADD KEY `email` (`email`),
  ADD KEY `hp` (`hp`);

--
-- Indeks untuk tabel `kegiatan_peserta`
--
ALTER TABLE `kegiatan_peserta`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `iduser` (`iduser`);

--
-- Indeks untuk tabel `presensi_peserta`
--
ALTER TABLE `presensi_peserta`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `id_kegiatan` (`id_kegiatan`);

--
-- Indeks untuk tabel `reward_claim`
--
ALTER TABLE `reward_claim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`iduser`),
  ADD KEY `idx_staff` (`staff_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indeks untuk tabel `super_user`
--
ALTER TABLE `super_user`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `hp` (`hp`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_points`
--
ALTER TABLE `user_points`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `booth`
--
ALTER TABLE `booth`
  MODIFY `idbooth` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `booth_kunjungan`
--
ALTER TABLE `booth_kunjungan`
  MODIFY `id_kunjungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `booth_visitor`
--
ALTER TABLE `booth_visitor`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kegiatan_peserta`
--
ALTER TABLE `kegiatan_peserta`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT untuk tabel `presensi_peserta`
--
ALTER TABLE `presensi_peserta`
  MODIFY `id_presensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT untuk tabel `reward_claim`
--
ALTER TABLE `reward_claim`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `super_user`
--
ALTER TABLE `super_user`
  MODIFY `iduser` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booth_kunjungan`
--
ALTER TABLE `booth_kunjungan`
  ADD CONSTRAINT `booth_kunjungan_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `super_user` (`iduser`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kegiatan_peserta`
--
ALTER TABLE `kegiatan_peserta`
  ADD CONSTRAINT `kegiatan_peserta_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `super_user` (`iduser`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi_peserta`
--
ALTER TABLE `presensi_peserta`
  ADD CONSTRAINT `presensi_peserta_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `super_user` (`iduser`) ON DELETE CASCADE,
  ADD CONSTRAINT `presensi_peserta_ibfk_2` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan_peserta` (`id_kegiatan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
