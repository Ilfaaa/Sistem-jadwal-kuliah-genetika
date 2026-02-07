-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Sep 2024 pada 15.28
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_jadwal_genetika`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `kode_dosen` varchar(30) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nidn` varchar(255) NOT NULL,
  `program_studi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`kode_dosen`, `nama`, `nidn`, `program_studi`, `created_at`, `updated_at`) VALUES
('SIF001', 'pdt. natasya v.l., s.si, m.si', '0016078702', 'sistem informasi', NULL, NULL),
('SIF002', 'irwan soulisa, s.pd., m.pd', '0028118701', 'sistem informasi', NULL, '2023-01-20 17:15:39'),
('SIF003', 'joseph e. lopulalan, s.sos., ma', '198506182019031010', 'sistem informasi', '2023-01-20 17:15:08', '2023-01-20 17:15:08'),
('SIF004', 'adolfina putnarubun, s.pk, m.pdk.', '0001019001', 'sistem informasi', NULL, NULL),
('SIF005', 'prits g.j. rupilele, s.t., m.cs', '0001108901', 'sistem informasi', NULL, NULL),
('SIF006', 'peter manuputty, s.pd., m.pd.', '0017018704', 'sistem informasi', NULL, NULL),
('SIF007', 'charliany hetharia, sp., m.si', '198811078702', 'sistem informasi', NULL, NULL),
('SIF008', 'melda a. manuhutu, s.kom., m.cs', '1122334455', 'sistem informasi', NULL, NULL),
('SIF009', 'matheus s. rumetna, s.kom., m.cs', '2211998812231', 'sistem informasi', NULL, NULL),
('SIF010', 'tirsa n. lina, s.kom., m.cs', '778866115521', 'sistem informasi', NULL, NULL),
('SIF011', 'yerrynaldo loppies, sp., mm', '44997725513', 'sistem informasi', NULL, NULL),
('SIF012', 'iriene s. rajagukguk, s.si., m.cs', '9986655133', 'sistem informasi', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hari`
--

CREATE TABLE `hari` (
  `kode_hari` bigint(20) UNSIGNED NOT NULL,
  `nama_hari` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hari`
--

INSERT INTO `hari` (`kode_hari`, `nama_hari`, `created_at`, `updated_at`) VALUES
(1, 'senin', NULL, NULL),
(2, 'selasa', '2021-12-27 17:59:27', '2021-12-27 17:59:27'),
(3, 'rabu', '2021-12-27 17:59:29', '2021-12-27 17:59:29'),
(4, 'kamis', '2021-12-28 06:08:29', '2021-12-28 06:08:29'),
(5, 'jum\'at', '2021-12-28 06:08:43', '2021-12-28 06:08:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matkul` varchar(255) NOT NULL,
  `dosen` varchar(255) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `jumlah_sks` varchar(10) NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `hari` varchar(255) NOT NULL,
  `jam_masuk` varchar(255) NOT NULL,
  `jam_keluar` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam`
--

CREATE TABLE `jam` (
  `kode_jam` bigint(20) UNSIGNED NOT NULL,
  `jam` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jam`
--

INSERT INTO `jam` (`kode_jam`, `jam`, `created_at`, `updated_at`) VALUES
(1, '16:00', NULL, NULL),
(2, '16:45', NULL, NULL),
(3, '17:30', NULL, NULL),
(4, '18:15', NULL, NULL),
(5, '19:00', NULL, NULL),
(6, '19:45', NULL, NULL),
(7, '20:30', NULL, NULL),
(8, '21:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `kode_kelas` varchar(40) NOT NULL,
  `nama_matkul` varchar(255) NOT NULL,
  `nama_dosen` varchar(255) NOT NULL,
  `kelas` char(10) NOT NULL,
  `kapasitas_kelas` int(10) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `kode_kelas`, `nama_matkul`, `nama_dosen`, `kelas`, `kapasitas_kelas`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, 'SIF0001A', 'ilmu sosial dasar', 'pdt. natasya v.l., s.si, m.si', 'A', 40, '2024/2025', NULL, NULL),
(2, 'SIF0001B', 'ilmu sosial dasar', 'pdt. natasya v.l., s.si, m.si', 'B', 40, '2024/2025', NULL, NULL),
(3, 'SIF0001C', 'ilmu sosial dasar', 'pdt. natasya v.l., s.si, m.si', 'C', 40, '2024/2025', NULL, NULL),
(4, 'SIF0002A', 'bahasa indonesia', 'irwan soulisa, s.pd., m.pd', 'A', 40, '2024/2025', NULL, NULL),
(5, 'SIF0002B', 'bahasa indonesia', 'irwan soulisa, s.pd., m.pd', 'B', 40, '2024/2025', NULL, NULL),
(6, 'SIF0002C', 'bahasa indonesia', 'irwan soulisa, s.pd., m.pd', 'C', 40, '2024/2025', NULL, NULL),
(7, 'SIF0003A', 'pendidikan pancasila', 'joseph e. lopulalan, s.sos., ma', 'A', 40, '2024/2025', NULL, NULL),
(8, 'SIF0003B', 'pendidikan pancasila', 'joseph e. lopulalan, s.sos., ma', 'B', 40, '2024/2025', NULL, NULL),
(9, 'SIF0003C', 'pendidikan pancasila', 'joseph e. lopulalan, s.sos., ma', 'C', 40, '2024/2025', NULL, NULL),
(10, 'SIF0004A', 'pendidikan agama', 'adolfina putnarubun, s.pk, m.pdk.', 'A', 40, '2024/2025', NULL, NULL),
(11, 'SIF0004B', 'pendidikan agama', 'adolfina putnarubun, s.pk, m.pdk.', 'B', 40, '2024/2025', NULL, NULL),
(12, 'SIF0004C', 'pendidikan agama', 'adolfina putnarubun, s.pk, m.pdk.', 'C', 40, '2024/2025', NULL, NULL),
(13, 'SIF0005A', 'pengantar sistem dan tek. informasi', 'prits g.j. rupilele, s.t., m.cs', 'A', 40, '2024/2025', NULL, NULL),
(14, 'SIF0005B', 'pengantar sistem dan tek. informasi', 'prits g.j. rupilele, s.t., m.cs', 'B', 40, '2024/2025', NULL, NULL),
(15, 'SIF0005C', 'pengantar sistem dan tek. informasi', 'prits g.j. rupilele, s.t., m.cs', 'C', 40, '2024/2025', NULL, NULL),
(16, 'SIF0006A', 'bahasa inggris dasar', 'peter manuputty, s.pd., m.pd.', 'A', 40, '2024/2025', NULL, NULL),
(17, 'SIF0006B', 'bahasa inggris dasar', 'peter manuputty, s.pd., m.pd.', 'B', 40, '2024/2025', NULL, NULL),
(18, 'SIF0006C', 'bahasa inggris dasar', 'peter manuputty, s.pd., m.pd.', 'C', 40, '2024/2025', NULL, NULL),
(19, 'SIF0007A', 'matematika', 'charliany hetharia, sp., m.si', 'A', 40, '2024/2025', NULL, NULL),
(20, 'SIF0007B', 'matematika', 'charliany hetharia, sp., m.si', 'B', 40, '2024/2025', NULL, NULL),
(21, 'SIF0007C', 'matematika', 'charliany hetharia, sp., m.si', 'C', 40, '2024/2025', NULL, NULL),
(22, 'SIF0008A', 'dasar pemrograman', 'melda a. manuhutu, s.kom., m.cs', 'A', 40, '2024/2025', NULL, NULL),
(23, 'SIF0008B', 'dasar pemrograman', 'melda a. manuhutu, s.kom., m.cs', 'B', 40, '2024/2025', NULL, NULL),
(24, 'SIF0008C', 'dasar pemrograman', 'melda a. manuhutu, s.kom., m.cs', 'C', 40, '2024/2025', NULL, NULL),
(25, 'SIF0009A', 'pendidikan kewarganegaraan', 'joseph e. lopulalan, s.sos., ma', 'A', 40, '2024/2025', NULL, NULL),
(26, 'SIF0009B', 'pendidikan kewarganegaraan', 'joseph e. lopulalan, s.sos., ma', 'B', 40, '2024/2025', NULL, NULL),
(27, 'SIF0009C', 'pendidikan kewarganegaraan', 'joseph e. lopulalan, s.sos., ma', 'C', 40, '2024/2025', NULL, NULL),
(28, 'SIF0010A', 'etika komputer', 'melda a. manuhutu, s.kom., m.cs', 'A', 40, '2024/2025', NULL, NULL),
(29, 'SIF0010B', 'etika komputer', 'melda a. manuhutu, s.kom., m.cs', 'B', 40, '2024/2025', NULL, NULL),
(30, 'SIF0010C', 'etika komputer', 'melda a. manuhutu, s.kom., m.cs', 'C', 40, '2024/2025', NULL, NULL),
(31, 'SIF0011A', 'etika kristen', 'adolfina putnarubun, s.pk, m.pdk.', 'A', 40, '2024/2025', NULL, NULL),
(32, 'SIF0011B', 'etika kristen', 'adolfina putnarubun, s.pk, m.pdk.', 'B', 40, '2024/2025', NULL, NULL),
(33, 'SIF0011C', 'etika kristen', 'adolfina putnarubun, s.pk, m.pdk.', 'C', 40, '2024/2025', NULL, NULL),
(34, 'SIF0012A', 'bahasa inggris profesi 1', 'peter manuputty, s.pd., m.pd.', 'A', 40, '2024/2025', NULL, NULL),
(35, 'SIF0012B', 'bahasa inggris profesi 1', 'peter manuputty, s.pd., m.pd.', 'B', 40, '2024/2025', NULL, NULL),
(36, 'SIF0012C', 'bahasa inggris profesi 1', 'peter manuputty, s.pd., m.pd.', 'C', 40, '2024/2025', NULL, NULL),
(37, 'SIF0013A', 'algoritma dan struktur data', 'matheus s. rumetna, s.kom., m.cs', 'A', 40, '2024/2025', NULL, NULL),
(38, 'SIF0013B', 'algoritma dan struktur data', 'matheus s. rumetna, s.kom., m.cs', 'B', 40, '2024/2025', NULL, NULL),
(39, 'SIF0013C', 'algoritma dan struktur data', 'matheus s. rumetna, s.kom., m.cs', 'C', 40, '2024/2025', NULL, NULL),
(40, 'SIF0014A', 'pendidikan karakter', 'pdt. natasya v.l., s.si, m.si', 'A', 40, '2024/2025', NULL, NULL),
(41, 'SIF0014B', 'pendidikan karakter', 'pdt. natasya v.l., s.si, m.si', 'B', 40, '2024/2025', NULL, NULL),
(42, 'SIF0014C', 'pendidikan karakter', 'pdt. natasya v.l., s.si, m.si', 'C', 40, '2024/2025', NULL, NULL),
(43, 'SIF0015A', 'matematika diskrit', 'tirsa n. lina, s.kom., m.cs', 'A', 40, '2024/2025', NULL, NULL),
(44, 'SIF0015B', 'matematika diskrit', 'tirsa n. lina, s.kom., m.cs', 'B', 40, '2024/2025', NULL, NULL),
(45, 'SIF0015C', 'matematika diskrit', 'tirsa n. lina, s.kom., m.cs', 'C', 40, '2024/2025', NULL, NULL),
(46, 'SIF0016A', 'organisasi komputer', 'matheus s. rumetna, s.kom., m.cs', 'A', 40, '2024/2025', NULL, NULL),
(47, 'SIF0016B', 'organisasi komputer', 'matheus s. rumetna, s.kom., m.cs', 'B', 40, '2024/2025', NULL, NULL),
(48, 'SIF0016C', 'organisasi komputer', 'matheus s. rumetna, s.kom., m.cs', 'C', 40, '2024/2025', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kuliah`
--

CREATE TABLE `kuliah` (
  `id_kuliah` bigint(20) UNSIGNED NOT NULL,
  `kode_kuliah` varchar(40) NOT NULL,
  `kode_matkul` varchar(40) NOT NULL,
  `kode_dosen` varchar(30) NOT NULL,
  `kode_kelas` varchar(40) NOT NULL,
  `kode_prodi` char(10) NOT NULL,
  `kode_semester` char(10) NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kuliah`
--

INSERT INTO `kuliah` (`id_kuliah`, `kode_kuliah`, `kode_matkul`, `kode_dosen`, `kode_kelas`, `kode_prodi`, `kode_semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, '1', 'SIF0001', 'SIF001', 'SIF0001A', 'SIF', '1', '2024/2025', NULL, NULL),
(2, '2', 'SIF0001', 'SIF001', 'SIF0001B', 'SIF', '1', '2024/2025', NULL, NULL),
(3, '3', 'SIF0001', 'SIF001', 'SIF0001C', 'SIF', '1', '2024/2025', NULL, NULL),
(4, '4', 'SIF0002', 'SIF002', 'SIF0002A', 'SIF', '1', '2024/2025', NULL, NULL),
(5, '5', 'SIF0002', 'SIF002', 'SIF0002B', 'SIF', '1', '2024/2025', NULL, NULL),
(6, '6', 'SIF0002', 'SIF002', 'SIF0002C', 'SIF', '1', '2024/2025', NULL, NULL),
(7, '7', 'SIF0003', 'SIF003', 'SIF0003A', 'SIF', '1', '2024/2025', NULL, NULL),
(8, '8', 'SIF0003', 'SIF003', 'SIF0003B', 'SIF', '1', '2024/2025', NULL, NULL),
(9, '9', 'SIF0003', 'SIF003', 'SIF0003C', 'SIF', '1', '2024/2025', NULL, NULL),
(10, '10', 'SIF0004', 'SIF004', 'SIF0004A', 'SIF', '1', '2024/2025', NULL, NULL),
(11, '11', 'SIF0004', 'SIF004', 'SIF0004B', 'SIF', '1', '2024/2025', NULL, NULL),
(12, '12', 'SIF0004', 'SIF004', 'SIF0004C', 'SIF', '1', '2024/2025', NULL, NULL),
(13, '13', 'SIF0005', 'SIF005', 'SIF0005A', 'SIF', '1', '2024/2025', NULL, NULL),
(14, '14', 'SIF0005', 'SIF005', 'SIF0005B', 'SIF', '1', '2024/2025', NULL, NULL),
(15, '15', 'SIF0005', 'SIF005', 'SIF0005C', 'SIF', '1', '2024/2025', NULL, NULL),
(16, '16', 'SIF0006', 'SIF006', 'SIF0006A', 'SIF', '1', '2024/2025', NULL, NULL),
(17, '17', 'SIF0006', 'SIF006', 'SIF0006B', 'SIF', '1', '2024/2025', NULL, NULL),
(18, '18', 'SIF0006', 'SIF006', 'SIF0006C', 'SIF', '1', '2024/2025', NULL, NULL),
(19, '19', 'SIF0007', 'SIF007', 'SIF0007A', 'SIF', '1', '2024/2025', NULL, NULL),
(20, '20', 'SIF0007', 'SIF007', 'SIF0007B', 'SIF', '1', '2024/2025', NULL, NULL),
(21, '21', 'SIF0007', 'SIF007', 'SIF0007C', 'SIF', '1', '2024/2025', NULL, NULL),
(22, '22', 'SIF0008', 'SIF008', 'SIF0008A', 'SIF', '1', '2024/2025', NULL, NULL),
(23, '23', 'SIF0008', 'SIF008', 'SIF0008B', 'SIF', '1', '2024/2025', NULL, NULL),
(24, '24', 'SIF0008', 'SIF008', 'SIF0008C', 'SIF', '1', '2024/2025', NULL, NULL),
(25, '25', 'SIF0009', 'SIF003', 'SIF0009A', 'SIF', '2', '2024/2025', NULL, NULL),
(26, '26', 'SIF0009', 'SIF003', 'SIF0009B', 'SIF', '2', '2024/2025', NULL, NULL),
(27, '27', 'SIF0009', 'SIF003', 'SIF0009C', 'SIF', '2', '2024/2025', NULL, NULL),
(28, '28', 'SIF0010', 'SIF008', 'SIF0010A', 'SIF', '2', '2024/2025', NULL, NULL),
(29, '29', 'SIF0010', 'SIF008', 'SIF0010B', 'SIF', '2', '2024/2025', NULL, NULL),
(30, '30', 'SIF0010', 'SIF008', 'SIF0010C', 'SIF', '2', '2024/2025', NULL, NULL),
(31, '31', 'SIF0011', 'SIF004', 'SIF0011A', 'SIF', '2', '2024/2025', NULL, NULL),
(32, '32', 'SIF0011', 'SIF004', 'SIF0011B', 'SIF', '2', '2024/2025', NULL, NULL),
(33, '33', 'SIF0011', 'SIF004', 'SIF0011C', 'SIF', '2', '2024/2025', NULL, NULL),
(34, '34', 'SIF0012', 'SIF006', 'SIF0012A', 'SIF', '2', '2024/2025', NULL, NULL),
(35, '35', 'SIF0012', 'SIF006', 'SIF0012B', 'SIF', '2', '2024/2025', NULL, NULL),
(36, '36', 'SIF0012', 'SIF006', 'SIF0012C', 'SIF', '2', '2024/2025', NULL, NULL),
(37, '37', 'SIF0013', 'SIF009', 'SIF0013A', 'SIF', '2', '2024/2025', NULL, NULL),
(38, '38', 'SIF0013', 'SIF009', 'SIF0013B', 'SIF', '2', '2024/2025', NULL, NULL),
(39, '39', 'SIF0013', 'SIF009', 'SIF0013C', 'SIF', '2', '2024/2025', NULL, NULL),
(40, '40', 'SIF0014', 'SIF001', 'SIF0014A', 'SIF', '2', '2024/2025', NULL, NULL),
(41, '41', 'SIF0014', 'SIF001', 'SIF0014B', 'SIF', '2', '2024/2025', NULL, NULL),
(42, '42', 'SIF0014', 'SIF001', 'SIF0014C', 'SIF', '2', '2024/2025', NULL, NULL),
(43, '43', 'SIF0015', 'SIF010', 'SIF0015A', 'SIF', '2', '2024/2025', NULL, NULL),
(44, '44', 'SIF0015', 'SIF010', 'SIF0015B', 'SIF', '2', '2024/2025', NULL, NULL),
(45, '45', 'SIF0015', 'SIF010', 'SIF0015C', 'SIF', '2', '2024/2025', NULL, NULL),
(46, '46', 'SIF0016', 'SIF009', 'SIF0016A', 'SIF', '2', '2024/2025', NULL, NULL),
(47, '47', 'SIF0016', 'SIF009', 'SIF0016B', 'SIF', '2', '2024/2025', NULL, NULL),
(48, '48', 'SIF0016', 'SIF009', 'SIF0016C', 'SIF', '2', '2024/2025', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `matkul`
--

CREATE TABLE `matkul` (
  `id_matkul` bigint(20) UNSIGNED NOT NULL,
  `kode_matkul` varchar(40) NOT NULL,
  `nama_matkul` varchar(255) NOT NULL,
  `sks` varchar(10) NOT NULL,
  `kode_prodi` char(10) NOT NULL,
  `kode_semester` char(10) NOT NULL,
  `perkuliahan_semester` char(10) NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `matkul`
--

INSERT INTO `matkul` (`id_matkul`, `kode_matkul`, `nama_matkul`, `sks`, `kode_prodi`, `kode_semester`, `perkuliahan_semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, 'SIF0001', 'ilmu sosial dasar', '2', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:20:23', '2024-09-29 12:20:23'),
(2, 'SIF0002', 'bahasa indonesia', '2', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:21:07', '2024-09-29 12:21:07'),
(3, 'SIF0003', 'pendidikan pancasila', '2', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:22:03', '2024-09-29 12:22:03'),
(4, 'SIF0004', 'pendidikan agama', '2', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:22:51', '2024-09-29 12:22:51'),
(5, 'SIF0005', 'pengantar sistem dan tek. informasi', '3', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:23:25', '2024-09-29 12:23:25'),
(6, 'SIF0006', 'bahasa inggris dasar', '2', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:24:18', '2024-09-29 12:24:18'),
(7, 'SIF0007', 'matematika', '3', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:25:13', '2024-09-29 12:25:13'),
(8, 'SIF0008', 'dasar pemrograman', '3', 'SIF', '1', '1', '2024/2025', '2024-09-29 12:26:04', '2024-09-29 12:26:04'),
(9, 'SIF0009', 'pendidikan kewarganegaraan', '2', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:01:35', '2024-09-29 13:01:35'),
(10, 'SIF0010', 'etika komputer', '3', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:02:57', '2024-09-29 13:02:57'),
(11, 'SIF0011', 'etika kristen', '2', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:08:41', '2024-09-29 13:08:41'),
(12, 'SIF0012', 'bahasa inggris profesi 1', '2', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:09:26', '2024-09-29 13:09:26'),
(13, 'SIF0013', 'algoritma dan struktur data', '3', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:10:18', '2024-09-29 13:10:18'),
(14, 'SIF0014', 'pendidikan karakter', '2', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:14:38', '2024-09-29 13:14:38'),
(15, 'SIF0015', 'matematika diskrit', '3', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:19:32', '2024-09-29 13:19:32'),
(16, 'SIF0016', 'organisasi komputer', '3', 'SIF', '2', '2', '2024/2025', '2024-09-29 13:20:39', '2024-09-29 13:20:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(23, '2021_09_17_023717_create_prodi_table', 1),
(24, '2021_09_18_020953_create_semester_table', 1),
(35, '2021_09_23_014035_create_hari_table', 1),
(37, '2021_09_23_014235_create_jam_table', 1),
(40, '2021_09_27_011150_create_waktu_table', 1),
(43, '2014_10_12_000000_create_users_table', 1),
(54, '2021_12_25_123735_create_request_waktu_table', 1),
(55, '2021_09_21_091451_create_ruang_table', 1),
(57, '2021_12_23_085311_create_request_ruang_table', 1),
(59, '2021_09_14_140239_create_dosen_table', 1),
(62, '2021_10_27_214907_create_jadwal_table', 1),
(63, '2023_02_12_020411_create_tahun_ajaran_table', 1),
(65, '2021_09_16_044312_create_matkul_table', 1),
(67, '2021_11_17_235257_create_request_kuliah_table', 1),
(68, '2021_09_18_034140_create_kelas_table', 2),
(69, '2021_09_26_024355_create_kuliah_table', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` bigint(20) UNSIGNED NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `kode_prodi` char(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `prodi`
--

INSERT INTO `prodi` (`id_prodi`, `nama_prodi`, `kode_prodi`, `created_at`, `updated_at`) VALUES
(1, 'sistem informasi', 'SIF', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_kuliah`
--

CREATE TABLE `request_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request` varchar(255) NOT NULL,
  `manage` varchar(255) NOT NULL,
  `kode_manage` varchar(40) NOT NULL,
  `nama_manage` varchar(255) NOT NULL,
  `sks` varchar(10) NOT NULL,
  `kode_prodi` char(10) NOT NULL,
  `kode_semester` varchar(255) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `nama_matkul` varchar(255) NOT NULL,
  `nama_dosen` varchar(255) NOT NULL,
  `kapasitas_kelas` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_ruang`
--

CREATE TABLE `request_ruang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request` varchar(255) NOT NULL,
  `kode_ruang` int(11) NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_waktu`
--

CREATE TABLE `request_waktu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request` varchar(255) NOT NULL,
  `manage` varchar(255) NOT NULL,
  `kode_waktu` int(11) NOT NULL,
  `kode_hari` int(11) NOT NULL,
  `nama_hari` varchar(255) NOT NULL,
  `kode_jam` varchar(255) NOT NULL,
  `jam` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruang`
--

CREATE TABLE `ruang` (
  `kode_ruang` bigint(20) UNSIGNED NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ruang`
--

INSERT INTO `ruang` (`kode_ruang`, `nama_ruang`, `nama_prodi`, `created_at`, `updated_at`) VALUES
(1, 'ruang 30', 'sistem informasi', NULL, NULL),
(2, 'ruang 31', 'sistem informasi', NULL, NULL),
(3, 'ruang 32', 'sistem informasi', NULL, NULL),
(4, 'ruang 33', 'sistem informasi', NULL, NULL),
(5, 'ruang 34', 'sistem informasi', NULL, NULL),
(6, 'ruang 35', 'sistem informasi', NULL, NULL),
(7, 'laboratorium 1', 'sistem informasi', NULL, NULL),
(8, 'laboratorium 2', 'sistem informasi', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester`
--

CREATE TABLE `semester` (
  `kode_semester` bigint(20) UNSIGNED NOT NULL,
  `nama_semester` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `semester`
--

INSERT INTO `semester` (`kode_semester`, `nama_semester`, `created_at`, `updated_at`) VALUES
(1, 'ganjil', NULL, NULL),
(2, 'genap', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(2, '2024/2025', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(128) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `is_active` int(10) UNSIGNED NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `name`, `username`, `email`, `image`, `role_id`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', 'default.jpg', 1, 1, NULL, '$2y$10$iMs.oTGdRJFSFp5AFGYvteIQOAdCwKhszFizcQOEb7XGQPeFMrtz2', NULL, '2024-09-17 18:26:49', NULL),
(2, 'Dosen', 'dosen', 'dosen@gmail.com', 'default.jpg', 2, 1, NULL, '$2y$10$4AkWn5.Hhlps0GqSWcjpOuVdo2KHZ2XeITR7QktZqpMkvz5f9GdOe', NULL, '2024-09-29 03:48:39', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `waktu`
--

CREATE TABLE `waktu` (
  `kode_waktu` bigint(20) UNSIGNED NOT NULL,
  `kode_hari` varchar(30) NOT NULL,
  `kode_jam` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `waktu`
--

INSERT INTO `waktu` (`kode_waktu`, `kode_hari`, `kode_jam`, `created_at`, `updated_at`) VALUES
(1, '1', '1', NULL, NULL),
(2, '1', '2', NULL, NULL),
(3, '1', '3', NULL, NULL),
(4, '1', '4', NULL, NULL),
(5, '1', '5', NULL, NULL),
(6, '1', '6', NULL, NULL),
(7, '2', '1', NULL, NULL),
(8, '2', '2', NULL, NULL),
(9, '2', '3', NULL, NULL),
(10, '2', '4', NULL, NULL),
(11, '2', '5', NULL, NULL),
(12, '2', '6', NULL, NULL),
(13, '3', '1', NULL, NULL),
(14, '3', '2', NULL, NULL),
(15, '3', '3', NULL, NULL),
(16, '3', '4', NULL, NULL),
(17, '3', '5', NULL, NULL),
(18, '3', '6', NULL, NULL),
(19, '4', '1', NULL, NULL),
(20, '4', '2', NULL, NULL),
(21, '4', '3', NULL, NULL),
(22, '4', '4', NULL, NULL),
(23, '4', '5', NULL, NULL),
(24, '4', '6', NULL, NULL),
(25, '5', '1', NULL, NULL),
(26, '5', '2', NULL, NULL),
(27, '5', '3', NULL, NULL),
(28, '5', '4', NULL, NULL),
(29, '5', '5', NULL, NULL),
(30, '5', '6', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`kode_dosen`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `hari`
--
ALTER TABLE `hari`
  ADD PRIMARY KEY (`kode_hari`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`kode_jam`),
  ADD UNIQUE KEY `jam_jam_unique` (`jam`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `kuliah`
--
ALTER TABLE `kuliah`
  ADD PRIMARY KEY (`id_kuliah`);

--
-- Indeks untuk tabel `matkul`
--
ALTER TABLE `matkul`
  ADD PRIMARY KEY (`id_matkul`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`),
  ADD UNIQUE KEY `prodi_nama_prodi_unique` (`nama_prodi`),
  ADD UNIQUE KEY `prodi_kode_prodi_unique` (`kode_prodi`),
  ADD UNIQUE KEY `nama_prodi` (`nama_prodi`),
  ADD UNIQUE KEY `nama_prodi_2` (`nama_prodi`);

--
-- Indeks untuk tabel `request_kuliah`
--
ALTER TABLE `request_kuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `request_ruang`
--
ALTER TABLE `request_ruang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `request_waktu`
--
ALTER TABLE `request_waktu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`kode_ruang`);

--
-- Indeks untuk tabel `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`kode_semester`),
  ADD UNIQUE KEY `semester_nama_semester_unique` (`nama_semester`);

--
-- Indeks untuk tabel `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `waktu`
--
ALTER TABLE `waktu`
  ADD PRIMARY KEY (`kode_waktu`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hari`
--
ALTER TABLE `hari`
  MODIFY `kode_hari` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jam`
--
ALTER TABLE `jam`
  MODIFY `kode_jam` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `kuliah`
--
ALTER TABLE `kuliah`
  MODIFY `id_kuliah` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `matkul`
--
ALTER TABLE `matkul`
  MODIFY `id_matkul` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `request_kuliah`
--
ALTER TABLE `request_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `request_ruang`
--
ALTER TABLE `request_ruang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `request_waktu`
--
ALTER TABLE `request_waktu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `ruang`
--
ALTER TABLE `ruang`
  MODIFY `kode_ruang` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `kode_semester` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `waktu`
--
ALTER TABLE `waktu`
  MODIFY `kode_waktu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
