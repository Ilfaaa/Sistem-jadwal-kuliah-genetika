-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 03:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `blocking_jadwal_dosen`
--

CREATE TABLE `blocking_jadwal_dosen` (
  `id` int(11) NOT NULL,
  `kode_dosen` varchar(30) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocking_jadwal_dosen`
--

INSERT INTO `blocking_jadwal_dosen` (`id`, `kode_dosen`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(3, 'SIF001', 'Senin', '08:00:00', '09:00:00', '2026-04-19 11:06:11', '2026-04-19 11:06:11'),
(17, 'SIF002', 'Senin', '08:00:00', '09:00:00', '2026-04-19 11:20:35', '2026-04-19 11:20:35'),
(47, 'SIF001', 'Senin', '10:00:00', '11:00:00', '2026-04-21 08:19:28', '2026-04-21 08:19:28'),
(48, 'SIF002', 'Senin', '10:00:00', '11:00:00', '2026-04-21 08:19:31', '2026-04-21 08:19:31'),
(49, 'SIF001', 'Selasa', '10:00:00', '11:00:00', '2026-04-21 08:19:35', '2026-04-21 08:19:35'),
(50, 'SIF002', 'Selasa', '10:00:00', '11:00:00', '2026-04-21 08:19:40', '2026-04-21 08:19:40'),
(51, 'SIF005', 'Selasa', '09:00:00', '10:00:00', '2026-04-21 08:19:45', '2026-04-21 08:19:45'),
(52, 'SIF001', 'Selasa', '09:00:00', '10:00:00', '2026-04-21 08:19:48', '2026-04-21 08:19:48'),
(53, 'ABP', 'Selasa', '08:00:00', '09:00:00', '2026-05-02 07:22:23', '2026-05-02 07:22:23');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
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
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`kode_dosen`, `nama`, `nidn`, `program_studi`, `created_at`, `updated_at`) VALUES
('ABP', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', '197106061995121003', 'teknik komputer', NULL, '2026-04-27 03:01:08'),
('ADF', 'Adnan Fauzi, S.T., M.Kom.', 'H.7.198101272018071001', 'teknik komputer', '2026-04-27 03:27:24', '2026-04-27 03:27:24'),
('AFR', 'Prof. Dr. Adian Fatchur Rochim, S.T., M.T.', '197302261998021001', 'teknik komputer', NULL, '2026-04-27 02:58:23'),
('ASN', 'Arseto Satriyo Nugroho, S.T., M.Eng.', 'H.7.198610182022101001', 'teknik komputer', '2026-04-27 03:31:03', '2026-04-27 03:31:03'),
('BCP', 'Bellia Dwi Cahya Putri, S.T., M.T.', 'H.7.199210142022102001', 'teknik komputer', '2026-04-27 03:28:51', '2026-04-27 03:28:51'),
('BIR', 'Bambang Irawanto, S.Si., M.Si.', '196707291994031001', 'teknik komputer', '2026-04-27 05:05:21', '2026-04-27 05:05:21'),
('DED', 'Dania Eridani, S.T., M.Eng.', '198910132015042002', 'teknik komputer', NULL, '2026-04-27 03:23:28'),
('DEL', 'Dr. Delphi Hanggoro, S.T., M.T.', '199506270125011043', 'teknik komputer', NULL, '2026-04-27 03:22:38'),
('DMK', 'Dinar Mutiara Kusumo Nugraheni, S.T., M.InfoTech.(Comp)., Ph.D.', '197601102009122002', 'teknik komputer', '2026-04-27 03:34:40', '2026-04-27 03:36:21'),
('DNB', 'Damar Nurwahyu Bima, S.Si., M.Si.', '199504212019031014', 'teknik komputer', '2026-04-27 05:07:43', '2026-04-27 05:07:43'),
('EDW', 'Eko Didik Widianto, S.T., M.T.', '197705262010121001', 'teknik komputer', NULL, '2026-04-27 03:25:02'),
('ERA', 'Erwin Adriono, S.T., M.T.', 'H.7.199212262022101001', 'teknik komputer', '2026-04-27 03:30:18', '2026-04-27 03:30:18'),
('IFH', 'Ilmam Fauzi Hashbil Alim, S.T., M.Kom.', 'H.7.199611182022101001', 'teknik komputer', '2026-04-27 03:29:39', '2026-04-27 03:29:39'),
('IPW', 'Ike Pertiwi Windasari, S.T., M.T.', '198412062010122008', 'teknik komputer', NULL, '2026-04-27 03:24:03'),
('JWT', 'Johanes Wahyu Tris Murdani S.S.', '10000386', 'teknik komputer', '2026-04-27 08:03:13', '2026-04-27 08:41:43'),
('KAN', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D.', 'H.7.199109042018071001', 'teknik komputer', NULL, '2026-04-27 03:02:50'),
('KTM', 'Kurniawan Teguh Martono, S.T., M.T.', '198303192010121002', 'teknik komputer', NULL, '2026-04-27 03:25:41'),
('MAS', 'Mas Ut, S.Ag., M.Si.', '196803081999031001', 'teknik komputer', '2026-04-27 05:06:34', '2026-04-27 07:28:40'),
('NBA', 'Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D.', '198112022005011002', 'teknik komputer', '2026-04-27 03:38:20', '2026-04-27 03:38:20'),
('NFN', 'Dr. Dra. Niken Fatimah Nurhayati, M.Pd.', '012345', 'teknik komputer', '2026-04-27 05:05:59', '2026-04-27 05:05:59'),
('ODN', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', '197910022009122001', 'teknik komputer', '2023-01-20 17:15:08', '2026-04-27 02:59:18'),
('PEM', 'Patricia Evericho Mountaines, S.T., M.Cs.', 'H.7.199203222022042001', 'teknik komputer', '2026-04-27 03:28:15', '2026-04-27 03:28:15'),
('PYN', 'Priyati Ningsih S.ag,.M.Pd.B.', '10000903', 'teknik komputer', '2026-04-27 08:11:43', '2026-04-27 08:11:43'),
('RHT', 'Dr. Redemtus Heru Tjahjana, S.Si., M.Si.', '197407172000121001', 'teknik komputer', '2026-04-27 03:32:43', '2026-04-27 03:32:43'),
('RKL', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', '197706152008011011', 'teknik komputer', NULL, '2026-04-27 03:02:03'),
('RZI', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', '197007272000121001', 'teknik komputer', NULL, '2026-04-27 03:18:04'),
('SLS', 'Dr. Drs. Slamet Subekti, M.Hum.', '196401011990031008', 'teknik komputer', '2026-04-27 05:07:01', '2026-04-27 05:07:01'),
('UDH', 'Dr.Eng. Udi Harmoko, S.Si., M.Si.', '197108101999031001', 'teknik komputer', '2026-04-27 03:32:04', '2026-04-27 03:32:04'),
('VIM', 'Drs. Pdt. Victor Imanuel Latuputty M.th', '10000147', 'teknik komputer', '2026-04-27 08:09:10', '2026-04-27 08:12:26'),
('YEW', 'Yudi Eko Windarto, S.T., M.Kom.', 'H.7.198906042018071001', 'teknik komputer', NULL, '2026-04-27 03:20:05'),
('ZNM', 'Zaenul Muhlisin, S.Si., M.Si., F.Med.', '197806082003121001', 'teknik komputer', '2026-04-27 05:04:51', '2026-04-27 05:04:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `hari`
--

CREATE TABLE `hari` (
  `kode_hari` bigint(20) UNSIGNED NOT NULL,
  `nama_hari` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hari`
--

INSERT INTO `hari` (`kode_hari`, `nama_hari`, `created_at`, `updated_at`) VALUES
(1, 'senin', NULL, NULL),
(2, 'selasa', '2021-12-27 17:59:27', '2021-12-27 17:59:27'),
(3, 'rabu', '2021-12-27 17:59:29', '2021-12-27 17:59:29'),
(4, 'kamis', '2021-12-28 06:08:29', '2021-12-28 06:08:29'),
(5, 'Jumat', '2021-12-28 06:08:43', '2021-12-28 06:08:43'),
(6, 'sabtu', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matkul` varchar(255) NOT NULL,
  `dosen` text NOT NULL,
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

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `matkul`, `dosen`, `kelas`, `jumlah_sks`, `nama_ruang`, `hari`, `jam_masuk`, `jam_keluar`, `semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1133, 'teknik mikroprosessor dan antarmuka', 'ERA, ODN', 'A', '2', 'ruang a202 teknik komputer', 'senin', '10:40', '12:10', 'ganjil', '2025/2026', NULL, NULL),
(1134, 'praktikum fisika dasar 2', 'UDH, BCP', 'A', '1', 'laboratorium jarkom', 'Jumat', '08:30', '09:15', 'ganjil', '2025/2026', NULL, NULL),
(1135, 'sinyal dan sistem', 'ASN, ZNM', 'A', '2', 'ruang a201 teknik komputer', 'kamis', '07:00', '08:30', 'ganjil', '2025/2026', NULL, NULL),
(1136, 'proyek desain capstone 1', 'PEM, SLS', 'A', '2', 'ruang dexlite 401', 'selasa', '10:40', '12:10', 'ganjil', '2025/2026', NULL, NULL),
(1137, 'manajemen proyek ti', 'AFR, PYN', 'A', '2', 'ruang dexlite 401', 'senin', '11:30', '13:00', 'ganjil', '2025/2026', NULL, NULL),
(1138, 'sistem terintegrasi pilihan', 'EDW, PYN', 'A', '2', 'ruang a201 teknik komputer', 'selasa', '08:00', '09:30', 'ganjil', '2025/2026', NULL, NULL),
(1139, 'kecerdasan buatan', 'RZI, UDH', 'A', '2', 'ruang b201', 'senin', '12:20', '13:50', 'ganjil', '2025/2026', NULL, NULL),
(1140, 'bahasa pemrograman rakitan', 'DEL, UDH', 'A', '2', 'ruang b201', 'selasa', '08:50', '10:20', 'ganjil', '2025/2026', NULL, NULL),
(1141, 'aljabar linear', 'AFR, IFH', 'A', '3', 'ruang a201 teknik komputer', 'rabu', '07:30', '09:45', 'ganjil', '2025/2026', NULL, NULL),
(1142, 'audit si / ti pilihan', 'RKL, JWT', 'A', '2', 'ruang dexlite 401', 'kamis', '09:20', '10:50', 'ganjil', '2025/2026', NULL, NULL),
(1143, 'switching, routing, dan jaringan nirkabel', 'NFN, AFR', 'A', '2', 'ruang a202 teknik komputer', 'rabu', '11:30', '13:00', 'ganjil', '2025/2026', NULL, NULL),
(1144, 'interaksi manusia dan komputer', 'BCP, YEW', 'A', '2', 'ruang b201', 'rabu', '09:40', '11:10', 'ganjil', '2025/2026', NULL, NULL),
(1145, 'pemrograman perangkat bergerak', 'BIR, JWT', 'A', '2', 'ruang dexlite 401', 'senin', '09:40', '11:10', 'ganjil', '2025/2026', NULL, NULL),
(1146, 'infrastruktur awan pilihan', 'DEL, RHT', 'A', '2', 'ruang b201', 'senin', '08:50', '10:20', 'ganjil', '2025/2026', NULL, NULL),
(1147, 'bahasa inggris 3', 'DNB, AFR', 'A', '1', 'ruang a202 teknik komputer', 'selasa', '09:00', '09:45', 'ganjil', '2025/2026', NULL, NULL),
(1148, 'agama islam', 'MAS, ZNM', 'B', '2', 'ruang a202 teknik komputer', 'kamis', '08:50', '10:20', 'ganjil', '2025/2026', NULL, NULL),
(1149, 'struktur data', 'DMK, ZNM', 'A', '2', 'ruang dexlite 401', 'Jumat', '09:30', '11:00', 'ganjil', '2025/2026', NULL, NULL),
(1150, 'perencanaan strategis si / ti pilihan', 'DEL, ERA', 'A', '3', 'ruang a201 teknik komputer', 'kamis', '09:20', '11:35', 'ganjil', '2025/2026', NULL, NULL),
(1151, 'pemrograman jaringan', 'ERA, KAN', 'A', '2', 'ruang a201 teknik komputer', 'Jumat', '08:45', '10:15', 'ganjil', '2025/2026', NULL, NULL),
(1152, 'agama budha', 'DED, NBA', 'A', '1', 'ruang a202 teknik komputer', 'senin', '07:00', '07:45', 'ganjil', '2025/2026', NULL, NULL),
(1153, 'jaringan syaraf tiruan pilihan', 'JWT, RZI', 'A', '2', 'ruang a202 teknik komputer', 'Jumat', '09:00', '10:30', 'ganjil', '2025/2026', NULL, NULL),
(1154, 'kewarganegaraan', 'ADF, ASN', 'A', '2', 'ruang a201 teknik komputer', 'selasa', '12:20', '13:50', 'ganjil', '2025/2026', NULL, NULL),
(1155, 'kewirausahaan', 'NBA, EDW', 'A', '2', 'ruang a201 teknik komputer', 'senin', '10:40', '12:10', 'ganjil', '2025/2026', NULL, NULL),
(1156, 'kimia', 'RZI, IFH', 'A', '2', 'ruang b201', 'kamis', '07:30', '09:00', 'ganjil', '2025/2026', NULL, NULL),
(1157, 'agama katolik', 'PYN, NBA', 'A', '1', 'ruang dexlite 401', 'rabu', '08:50', '09:35', 'ganjil', '2025/2026', NULL, NULL),
(1158, 'sistem informasi pilihan', 'MAS, ZNM', 'A', '2', 'ruang a202 teknik komputer', 'senin', '08:00', '09:30', 'ganjil', '2025/2026', NULL, NULL),
(1159, 'internet of things', 'DNB, BIR', 'A', '2', 'ruang dexlite 401', 'rabu', '11:30', '13:00', 'ganjil', '2025/2026', NULL, NULL),
(1160, 'praktikum sistem digital', 'RHT, NFN', 'A', '1', 'laboratorium fisika, fakultas sains dan matematika', 'selasa', '09:20', '10:05', 'ganjil', '2025/2026', NULL, NULL),
(1161, 'keamanan jaringan komputer', 'PYN, ODN', 'A', '2', 'ruang a202 teknik komputer', 'rabu', '10:00', '11:30', 'ganjil', '2025/2026', NULL, NULL),
(1162, 'multimedia', 'KAN, DED', 'A', '2', 'ruang dexlite 401', 'kamis', '11:00', '12:30', 'ganjil', '2025/2026', NULL, NULL),
(1163, 'rangkaian listrik', 'KTM, DMK', 'A', '2', 'ruang dexlite 401', 'rabu', '07:00', '08:30', 'ganjil', '2025/2026', NULL, NULL),
(1164, 'sistem digital lanjut', 'AFR, DMK', 'A', '2', 'ruang a202 teknik komputer', 'selasa', '07:00', '08:30', 'ganjil', '2025/2026', NULL, NULL),
(1165, 'fisika dasar 1', 'KTM, MAS', 'A', '3', 'ruang a202 teknik komputer', 'selasa', '10:40', '12:55', 'ganjil', '2025/2026', NULL, NULL),
(1166, 'sistem operasi waktu nyata', 'PEM, ADF', 'A', '2', 'ruang b201', 'selasa', '07:00', '08:30', 'ganjil', '2025/2026', NULL, NULL),
(1167, 'metode numerik', 'BCP, RKL', 'A', '2', 'ruang dexlite 401', 'kamis', '07:30', '09:00', 'ganjil', '2025/2026', NULL, NULL),
(1168, 'praktikum multimedia', 'MAS, DEL', 'A', '1', 'laboratorium fisika, fakultas sains dan matematika', 'Jumat', '07:00', '07:45', 'ganjil', '2025/2026', NULL, NULL),
(1169, 'teknologi informasi', 'PEM, DMK', 'A', '3', 'ruang b201', 'Jumat', '12:20', '14:35', 'ganjil', '2025/2026', NULL, NULL),
(1170, 'pemrograman berorientasi objek', 'AFR, EDW', 'A', '2', 'ruang a201 teknik komputer', 'Jumat', '11:30', '13:00', 'ganjil', '2025/2026', NULL, NULL),
(1171, 'praktikum pemrograman dasar', 'EDW, RKL', 'A', '1', 'laboratorium software', 'rabu', '11:30', '12:15', 'ganjil', '2025/2026', NULL, NULL),
(1172, 'praktikum switching, routing, dan jaringan nirkabel', 'DEL, ABP', 'A', '1', 'laboratorium multimedia', 'rabu', '07:00', '07:45', 'ganjil', '2025/2026', NULL, NULL),
(1173, 'agama islam', 'MAS, NBA', 'A', '2', 'ruang b101', 'rabu', '07:00', '08:30', 'ganjil', '2025/2026', NULL, NULL),
(1174, 'pancasila', 'KAN, JWT', 'A', '2', 'ruang a202 teknik komputer', 'Jumat', '11:10', '12:40', 'ganjil', '2025/2026', NULL, NULL),
(1175, 'etika profesi', 'BCP, ERA', 'A', '2', 'ruang b201', 'senin', '07:00', '08:30', 'ganjil', '2025/2026', NULL, NULL),
(1176, 'praktikum elektronika dasar', 'ZNM, RZI', 'A', '1', 'laboratorium embeded', 'kamis', '11:00', '11:45', 'ganjil', '2025/2026', NULL, NULL),
(1177, 'pemrograman game pilihan', 'YEW, DNB', 'A', '2', 'ruang a202 teknik komputer', 'kamis', '11:30', '13:00', 'ganjil', '2025/2026', NULL, NULL),
(1178, 'kecakapan antar personal', 'IPW, SLS', 'A', '2', 'ruang b201', 'Jumat', '08:50', '10:20', 'ganjil', '2025/2026', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_dosen`
--

CREATE TABLE `jadwal_dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_id` bigint(20) UNSIGNED NOT NULL,
  `kode_dosen` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_dosen`
--

INSERT INTO `jadwal_dosen` (`id`, `jadwal_id`, `kode_dosen`, `created_at`, `updated_at`) VALUES
(1441, 804, 'MAS', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1442, 804, 'JWT', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1445, 806, 'RHT', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1446, 806, 'IFH', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1469, 818, 'ASN', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1470, 818, 'KTM', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1491, 829, 'PYN', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1492, 829, 'KTM', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1507, 837, 'NBA', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1508, 837, 'RKL', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1515, 841, 'ADF', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1516, 841, 'JWT', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1529, 848, 'EDW', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1530, 848, 'ODN', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1539, 853, 'UDH', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(1540, 853, 'ODN', '2026-05-05 14:22:03', '2026-05-05 14:22:03'),
(2099, 1133, 'ERA', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2100, 1133, 'ODN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2101, 1134, 'UDH', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2102, 1134, 'BCP', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2103, 1135, 'ASN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2104, 1135, 'ZNM', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2105, 1136, 'PEM', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2106, 1136, 'SLS', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2107, 1137, 'AFR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2108, 1137, 'PYN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2109, 1138, 'EDW', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2110, 1138, 'PYN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2111, 1139, 'RZI', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2112, 1139, 'UDH', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2113, 1140, 'DEL', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2114, 1140, 'UDH', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2115, 1141, 'AFR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2116, 1141, 'IFH', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2117, 1142, 'RKL', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2118, 1142, 'JWT', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2119, 1143, 'NFN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2120, 1143, 'AFR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2121, 1144, 'BCP', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2122, 1144, 'YEW', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2123, 1145, 'BIR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2124, 1145, 'JWT', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2125, 1146, 'DEL', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2126, 1146, 'RHT', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2127, 1147, 'DNB', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2128, 1147, 'AFR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2129, 1148, 'MAS', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2130, 1148, 'ZNM', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2131, 1149, 'DMK', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2132, 1149, 'ZNM', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2133, 1150, 'DEL', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2134, 1150, 'ERA', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2135, 1151, 'ERA', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2136, 1151, 'KAN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2137, 1152, 'DED', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2138, 1152, 'NBA', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2139, 1153, 'JWT', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2140, 1153, 'RZI', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2141, 1154, 'ADF', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2142, 1154, 'ASN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2143, 1155, 'NBA', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2144, 1155, 'EDW', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2145, 1156, 'RZI', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2146, 1156, 'IFH', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2147, 1157, 'PYN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2148, 1157, 'NBA', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2149, 1158, 'MAS', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2150, 1158, 'ZNM', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2151, 1159, 'DNB', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2152, 1159, 'BIR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2153, 1160, 'RHT', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2154, 1160, 'NFN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2155, 1161, 'PYN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2156, 1161, 'ODN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2157, 1162, 'KAN', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2158, 1162, 'DED', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2159, 1163, 'KTM', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2160, 1163, 'DMK', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2161, 1164, 'AFR', '2026-05-20 08:05:06', '2026-05-20 08:05:06'),
(2162, 1164, 'DMK', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2163, 1165, 'KTM', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2164, 1165, 'MAS', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2165, 1166, 'PEM', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2166, 1166, 'ADF', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2167, 1167, 'BCP', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2168, 1167, 'RKL', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2169, 1168, 'MAS', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2170, 1168, 'DEL', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2171, 1169, 'PEM', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2172, 1169, 'DMK', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2173, 1170, 'AFR', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2174, 1170, 'EDW', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2175, 1171, 'EDW', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2176, 1171, 'RKL', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2177, 1172, 'DEL', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2178, 1172, 'ABP', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2179, 1173, 'MAS', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2180, 1173, 'NBA', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2181, 1174, 'KAN', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2182, 1174, 'JWT', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2183, 1175, 'BCP', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2184, 1175, 'ERA', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2185, 1176, 'ZNM', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2186, 1176, 'RZI', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2187, 1177, 'YEW', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2188, 1177, 'DNB', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2189, 1178, 'IPW', '2026-05-20 08:05:07', '2026-05-20 08:05:07'),
(2190, 1178, 'SLS', '2026-05-20 08:05:07', '2026-05-20 08:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `jam`
--

CREATE TABLE `jam` (
  `kode_jam` bigint(20) UNSIGNED NOT NULL,
  `jam` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam`
--

INSERT INTO `jam` (`kode_jam`, `jam`, `created_at`, `updated_at`) VALUES
(2, '07:00', NULL, NULL),
(3, '07:30', NULL, NULL),
(4, '08:00', NULL, NULL),
(5, '08:30', NULL, NULL),
(6, '08:45', NULL, NULL),
(7, '08:50', NULL, NULL),
(8, '09:00', NULL, NULL),
(9, '09:20', NULL, NULL),
(10, '09:30', NULL, NULL),
(11, '09:40', NULL, NULL),
(12, '10:00', NULL, NULL),
(13, '10:40', NULL, NULL),
(14, '11:00', NULL, NULL),
(15, '11:10', NULL, NULL),
(16, '11:30', NULL, NULL),
(17, '12:20', NULL, NULL),
(18, '12:30', NULL, NULL),
(19, '13:00', NULL, NULL),
(20, '13:20', NULL, NULL),
(21, '13:30', NULL, NULL),
(22, '14:00', NULL, NULL),
(23, '14:10', NULL, NULL),
(24, '14:20', NULL, NULL),
(25, '14:30', NULL, NULL),
(26, '14:50', NULL, NULL),
(27, '15:00', NULL, NULL),
(28, '15:10', NULL, NULL),
(29, '16:00', NULL, NULL),
(30, '16:10', NULL, NULL),
(31, '17:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `kode_kelas` varchar(40) NOT NULL,
  `nama_matkul` varchar(255) NOT NULL,
  `nama_dosen` text NOT NULL,
  `kelas` char(10) NOT NULL,
  `kapasitas_kelas` int(10) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `kode_kelas`, `nama_matkul`, `nama_dosen`, `kelas`, `kapasitas_kelas`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(49, 'TK0001A', 'pemrograman dasar', 'Mas Ut, S.Ag., M.Si., Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(50, 'TK0001B', 'pemrograman dasar', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(51, 'TK0001C', 'pemrograman dasar', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(52, 'TK0001D', 'pemrograman dasar', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(53, 'TK0002A', 'agama islam', 'Damar Nurwahyu Bima, S.Si., M.Si., Mas Ut, S.Ag., M.Si.', 'A', 80, '2025/2026', NULL, NULL),
(54, 'TK0002B', 'agama islam', 'Mas Ut, S.Ag., M.Si.', 'B', 80, '2025/2026', NULL, NULL),
(55, 'TK0002C', 'agama islam', 'Mas Ut, S.Ag., M.Si.', 'C', 40, '2025/2026', NULL, NULL),
(56, 'TK0003A', 'rangkaian listrik', 'Arseto Satriyo Nugroho, S.T., M.Eng., Bellia Dwi Cahya Putri, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(57, 'TK0003B', 'rangkaian listrik', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(58, 'TK0003C', 'rangkaian listrik', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(59, 'TK0003D', 'rangkaian listrik', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(60, 'TK0005A', 'olahraga', 'Dr. Delphi Hanggoro, S.T., M.T., Dinar Mutiara Kusumo Nugraheni, S.T., M.InfoTech.(Comp)., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(61, 'TK0005B', 'olahraga', 'Dr. Dra. Niken Fatimah Nurhayati, M.Pd.', 'B', 40, '2025/2026', NULL, NULL),
(62, 'TK0005C', 'olahraga', 'Dr. Dra. Niken Fatimah Nurhayati, M.Pd.', 'C', 40, '2025/2026', NULL, NULL),
(63, 'TK0005D', 'olahraga', 'Dr. Dra. Niken Fatimah Nurhayati, M.Pd.', 'D', 40, '2025/2026', NULL, NULL),
(64, 'TK0004A', 'teknologi informasi', 'Bambang Irawanto, S.Si., M.Si., Dania Eridani, S.T., M.Eng.', 'A', 40, '2025/2026', NULL, NULL),
(65, 'TK0004B', 'teknologi informasi', 'Bellia Dwi Cahya Putri, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(66, 'TK0004C', 'teknologi informasi', 'Bellia Dwi Cahya Putri, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(67, 'TK0004D', 'teknologi informasi', 'Bellia Dwi Cahya Putri, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(68, 'TK0006A', 'kalkulus 1', 'Damar Nurwahyu Bima, S.Si., M.Si., Eko Didik Widianto, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(69, 'TK0006B', 'kalkulus 1', 'Bambang Irawanto, S.Si., M.Si.', 'B', 40, '2025/2026', NULL, NULL),
(70, 'TK0006C', 'kalkulus 1', 'Bambang Irawanto, S.Si., M.Si.', 'C', 40, '2025/2026', NULL, NULL),
(71, 'TK0006D', 'kalkulus 1', 'Bambang Irawanto, S.Si., M.Si.', 'D', 40, '2025/2026', NULL, NULL),
(72, 'TK0007A', 'kimia', 'Erwin Adriono, S.T., M.T., Ilmam Fauzi Hashbil Alim, S.T., M.Kom.', 'A', 40, '2025/2026', NULL, NULL),
(73, 'TK0007B', 'kimia', 'Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(74, 'TK0007C', 'kimia', 'Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(75, 'TK0007D', 'kimia', 'Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(76, 'TK0008A', 'fisika dasar 1', 'Ike Pertiwi Windasari, S.T., M.T., Johanes Wahyu Tris Murdani S.S.', 'A', 40, '2025/2026', NULL, NULL),
(77, 'TK0008B', 'fisika dasar 1', 'Dr.Eng. Udi Harmoko, S.Si., M.Si.', 'B', 40, '2025/2026', NULL, NULL),
(78, 'TK0008C', 'fisika dasar 1', 'Dr.Eng. Udi Harmoko, S.Si., M.Si.', 'C', 40, '2025/2026', NULL, NULL),
(79, 'TK0008D', 'fisika dasar 1', 'Dr.Eng. Udi Harmoko, S.Si., M.Si.', 'D', 40, '2025/2026', NULL, NULL),
(80, 'TK0009A', 'agama budha', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D., Kurniawan Teguh Martono, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(81, 'TK0010A', 'agama katolik', 'Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D., Dr. Dra. Niken Fatimah Nurhayati, M.Pd.', 'A', 40, '2025/2026', NULL, NULL),
(82, 'TK0011A', 'agama kristen', 'Dr. Oky Dwi Nurhayati, S.T., M.T., Patricia Evericho Mountaines, S.T., M.Cs.', 'A', 40, '2025/2026', NULL, NULL),
(83, 'TK0021A', 'praktikum pemrograman dasar', 'Eko Didik Widianto, S.T., M.T., Erwin Adriono, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(84, 'TK0021B', 'praktikum pemrograman dasar', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(85, 'TK0021C', 'praktikum pemrograman dasar', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(86, 'TK0021D', 'praktikum pemrograman dasar', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(87, 'TK0013A', 'kecerdasan buatan', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng., Dr. Drs. Slamet Subekti, M.Hum.', 'A', 40, '2025/2026', NULL, NULL),
(88, 'TK0013B', 'kecerdasan buatan', 'Kurniawan Teguh Martono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(89, 'TK0013C', 'kecerdasan buatan', 'Kurniawan Teguh Martono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(90, 'TK0013D', 'kecerdasan buatan', 'Kurniawan Teguh Martono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(91, 'TK0014A', 'struktur data', 'Dr.Eng. Udi Harmoko, S.Si., M.Si., Drs. Pdt. Victor Imanuel Latuputty M.th', 'A', 40, '2025/2026', NULL, NULL),
(92, 'TK0014B', 'struktur data', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(93, 'TK0014C', 'struktur data', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(94, 'TK0014D', 'struktur data', 'Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(95, 'TK0015A', 'multimedia', 'Yudi Eko Windarto, S.T., M.Kom., Zaenul Muhlisin, S.Si., M.Si., F.Med.', 'A', 40, '2025/2026', NULL, NULL),
(96, 'TK0015B', 'multimedia', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(97, 'TK0015C', 'multimedia', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(98, 'TK0015D', 'multimedia', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(99, 'TK0016A', 'sistem digital lanjut', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D., Adnan Fauzi, S.T., M.Kom.', 'A', 40, '2025/2026', NULL, NULL),
(100, 'TK0016B', 'sistem digital lanjut', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(101, 'TK0016C', 'sistem digital lanjut', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(102, 'TK0016D', 'sistem digital lanjut', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(103, 'TK0017A', 'aljabar linear', 'Prof. Dr. Adian Fatchur Rochim, S.T., M.T., Arseto Satriyo Nugroho, S.T., M.Eng.', 'A', 40, '2025/2026', NULL, NULL),
(104, 'TK0017B', 'aljabar linear', 'Dr. Redemtus Heru Tjahjana, S.Si., M.Si.', 'B', 40, '2025/2026', NULL, NULL),
(105, 'TK0017C', 'aljabar linear', 'Dr. Redemtus Heru Tjahjana, S.Si., M.Si.', 'C', 40, '2025/2026', NULL, NULL),
(106, 'TK0017D', 'aljabar linear', 'Dr. Redemtus Heru Tjahjana, S.Si., M.Si.', 'D', 40, '2025/2026', NULL, NULL),
(107, 'TK0018A', 'bahasa inggris 3', 'Bellia Dwi Cahya Putri, S.T., M.T., Bambang Irawanto, S.Si., M.Si.', 'A', 40, '2025/2026', NULL, NULL),
(108, 'TK0018B', 'bahasa inggris 3', 'Dania Eridani, S.T., M.Eng.', 'B', 40, '2025/2026', NULL, NULL),
(109, 'TK0018C', 'bahasa inggris 3', 'Dania Eridani, S.T., M.Eng.', 'C', 40, '2025/2026', NULL, NULL),
(110, 'TK0019A', 'switching, routing, dan jaringan nirkabel', 'Dania Eridani, S.T., M.Eng., Dr. Delphi Hanggoro, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(111, 'TK0019B', 'switching, routing, dan jaringan nirkabel', 'Adnan Fauzi, S.T., M.Kom.', 'B', 40, '2025/2026', NULL, NULL),
(112, 'TK0019C', 'switching, routing, dan jaringan nirkabel', 'Adnan Fauzi, S.T., M.Kom.', 'C', 40, '2025/2026', NULL, NULL),
(113, 'TK0019D', 'switching, routing, dan jaringan nirkabel', 'Adnan Fauzi, S.T., M.Kom.', 'D', 40, '2025/2026', NULL, NULL),
(114, 'TK0020A', 'interaksi manusia dan komputer', 'Dinar Mutiara Kusumo Nugraheni, S.T., M.InfoTech.(Comp)., Ph.D., Damar Nurwahyu Bima, S.Si., M.Si.', 'A', 40, '2025/2026', NULL, NULL),
(115, 'TK0020B', 'interaksi manusia dan komputer', 'Kurniawan Teguh Martono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(116, 'TK0020C', 'interaksi manusia dan komputer', 'Kurniawan Teguh Martono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(117, 'TK0020D', 'interaksi manusia dan komputer', 'Kurniawan Teguh Martono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(118, 'TK0022A', 'praktikum switching, routing, dan jaringan nirkabel', 'Ilmam Fauzi Hashbil Alim, S.T., M.Kom., Ike Pertiwi Windasari, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(119, 'TK0022B', 'praktikum switching, routing, dan jaringan nirkabel', 'Adnan Fauzi, S.T., M.Kom.', 'B', 40, '2025/2026', NULL, NULL),
(120, 'TK0022C', 'praktikum switching, routing, dan jaringan nirkabel', 'Adnan Fauzi, S.T., M.Kom.', 'C', 40, '2025/2026', NULL, NULL),
(121, 'TK0022D', 'praktikum switching, routing, dan jaringan nirkabel', 'Adnan Fauzi, S.T., M.Kom.', 'D', 40, '2025/2026', NULL, NULL),
(122, 'TK0023A', 'praktikum multimedia', 'Johanes Wahyu Tris Murdani S.S., Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(123, 'TK0023B', 'praktikum multimedia', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(124, 'TK0023C', 'praktikum multimedia', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(125, 'TK0023D', 'praktikum multimedia', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(126, 'TK0024A', 'praktikum elektronika dasar', 'Kurniawan Teguh Martono, S.T., M.T., Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(127, 'TK0024B', 'praktikum elektronika dasar', 'Erwin Adriono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(128, 'TK0024C', 'praktikum elektronika dasar', 'Erwin Adriono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(129, 'TK0024D', 'praktikum elektronika dasar', 'Erwin Adriono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(130, 'TK0025A', 'praktikum sistem digital', 'Dr. Dra. Niken Fatimah Nurhayati, M.Pd., Dr. Oky Dwi Nurhayati, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(131, 'TK0025B', 'praktikum sistem digital', 'Eko Didik Widianto, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(132, 'TK0025C', 'praktikum sistem digital', 'Eko Didik Widianto, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(133, 'TK0025D', 'praktikum sistem digital', 'Eko Didik Widianto, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(134, 'TK0026A', 'manajemen proyek ti', 'Patricia Evericho Mountaines, S.T., M.Cs., Priyati Ningsih S.ag,.M.Pd.B.', 'A', 40, '2025/2026', NULL, NULL),
(135, 'TK0026B', 'manajemen proyek ti', 'Dr. Delphi Hanggoro, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(136, 'TK0026C', 'manajemen proyek ti', 'Dr. Delphi Hanggoro, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(137, 'TK0026D', 'manajemen proyek ti', 'Dr. Delphi Hanggoro, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(138, 'TK0027A', 'teknik mikroprosessor dan antarmuka', 'Dr. Redemtus Heru Tjahjana, S.Si., M.Si., Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'A', 40, '2025/2026', NULL, NULL),
(139, 'TK0027B', 'teknik mikroprosessor dan antarmuka', 'Erwin Adriono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(140, 'TK0027C', 'teknik mikroprosessor dan antarmuka', 'Erwin Adriono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(141, 'TK0027D', 'teknik mikroprosessor dan antarmuka', 'Erwin Adriono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(142, 'TK0028A', 'pemrograman berorientasi objek', 'Dr. Drs. Slamet Subekti, M.Hum., Dr.Eng. Udi Harmoko, S.Si., M.Si.', 'A', 40, '2025/2026', NULL, NULL),
(143, 'TK0028B', 'pemrograman berorientasi objek', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(144, 'TK0028C', 'pemrograman berorientasi objek', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(145, 'TK0028D', 'pemrograman berorientasi objek', 'Dr. Oky Dwi Nurhayati, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(146, 'TK0029A', 'pemrograman perangkat bergerak', 'Drs. Pdt. Victor Imanuel Latuputty M.th, Yudi Eko Windarto, S.T., M.Kom.', 'A', 40, '2025/2026', NULL, NULL),
(147, 'TK0029B', 'pemrograman perangkat bergerak', 'Kurniawan Teguh Martono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(148, 'TK0029C', 'pemrograman perangkat bergerak', 'Kurniawan Teguh Martono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(149, 'TK0029D', 'pemrograman perangkat bergerak', 'Kurniawan Teguh Martono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(150, 'TK0030A', 'metode numerik', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D., Zaenul Muhlisin, S.Si., M.Si., F.Med.', 'A', 40, '2025/2026', NULL, NULL),
(151, 'TK0030B', 'metode numerik', 'Eko Didik Widianto, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(152, 'TK0030C', 'metode numerik', 'Eko Didik Widianto, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(153, 'TK0030D', 'metode numerik', 'Eko Didik Widianto, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(154, 'TK0031A', 'bahasa pemrograman rakitan', 'Adnan Fauzi, S.T., M.Kom., Prof. Dr. Adian Fatchur Rochim, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(155, 'TK0031B', 'bahasa pemrograman rakitan', 'Erwin Adriono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(156, 'TK0031C', 'bahasa pemrograman rakitan', 'Erwin Adriono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(157, 'TK0031D', 'bahasa pemrograman rakitan', 'Erwin Adriono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(158, 'TK0032A', 'sinyal dan sistem', 'Arseto Satriyo Nugroho, S.T., M.Eng., Bellia Dwi Cahya Putri, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(159, 'TK0032B', 'sinyal dan sistem', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(160, 'TK0032C', 'sinyal dan sistem', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(161, 'TK0032D', 'sinyal dan sistem', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(162, 'TK0033A', 'pancasila', 'Bambang Irawanto, S.Si., M.Si., Dania Eridani, S.T., M.Eng.', 'A', 40, '2025/2026', NULL, NULL),
(163, 'TK0033B', 'pancasila', 'Dr. Drs. Slamet Subekti, M.Hum.', 'B', 40, '2025/2026', NULL, NULL),
(164, 'TK0034A', 'pemrograman game pilihan', 'Dr. Delphi Hanggoro, S.T., M.T., Dinar Mutiara Kusumo Nugraheni, S.T., M.InfoTech.(Comp)., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(165, 'TK0035A', 'audit si / ti pilihan', 'Damar Nurwahyu Bima, S.Si., M.Si., Eko Didik Widianto, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(166, 'TK0036A', 'jaringan syaraf tiruan pilihan', 'Erwin Adriono, S.T., M.T., Ilmam Fauzi Hashbil Alim, S.T., M.Kom.', 'A', 40, '2025/2026', NULL, NULL),
(167, 'TK0037A', 'sistem informasi pilihan', 'Ike Pertiwi Windasari, S.T., M.T., Johanes Wahyu Tris Murdani S.S.', 'A', 40, '2025/2026', NULL, NULL),
(168, 'TK0038A', 'sistem terintegrasi pilihan', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D., Kurniawan Teguh Martono, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(169, 'TK0039A', 'infrastruktur awan pilihan', 'Mas Ut, S.Ag., M.Si., Nor Basid Adiwibawa Prasetya, S.Si., M.Sc., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(170, 'TK0040A', 'perencanaan strategis si / ti pilihan', 'Dr. Dra. Niken Fatimah Nurhayati, M.Pd., Dr. Oky Dwi Nurhayati, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(171, 'TK0041A', 'kecakapan antar personal', 'Patricia Evericho Mountaines, S.T., M.Cs., Priyati Ningsih S.ag,.M.Pd.B.', 'A', 40, '2025/2026', NULL, NULL),
(172, 'TK0041B', 'kecakapan antar personal', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'B', 40, '2025/2026', NULL, NULL),
(173, 'TK0041C', 'kecakapan antar personal', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'C', 40, '2025/2026', NULL, NULL),
(174, 'TK0041D', 'kecakapan antar personal', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'D', 40, '2025/2026', NULL, NULL),
(175, 'TK0042A', 'internet of things', 'Dr. Redemtus Heru Tjahjana, S.Si., M.Si., Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'A', 40, '2025/2026', NULL, NULL),
(176, 'TK0042B', 'internet of things', 'Erwin Adriono, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(177, 'TK0042C', 'internet of things', 'Erwin Adriono, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(178, 'TK0042D', 'internet of things', 'Erwin Adriono, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(179, 'TK0043A', 'etika profesi', 'Dr. Drs. Slamet Subekti, M.Hum., Dr.Eng. Udi Harmoko, S.Si., M.Si.', 'A', 40, '2025/2026', NULL, NULL),
(180, 'TK0043B', 'etika profesi', 'Ike Pertiwi Windasari, S.T., M.T.', 'B', 40, '2025/2026', NULL, NULL),
(181, 'TK0043C', 'etika profesi', 'Ike Pertiwi Windasari, S.T., M.T.', 'C', 40, '2025/2026', NULL, NULL),
(182, 'TK0043D', 'etika profesi', 'Ike Pertiwi Windasari, S.T., M.T.', 'D', 40, '2025/2026', NULL, NULL),
(183, 'TK0044A', 'sistem operasi waktu nyata', 'Drs. Pdt. Victor Imanuel Latuputty M.th, Yudi Eko Windarto, S.T., M.Kom.', 'A', 40, '2025/2026', NULL, NULL),
(184, 'TK0044B', 'sistem operasi waktu nyata', 'Dania Eridani, S.T., M.Eng.', 'B', 40, '2025/2026', NULL, NULL),
(185, 'TK0044C', 'sistem operasi waktu nyata', 'Dania Eridani, S.T., M.Eng.', 'C', 40, '2025/2026', NULL, NULL),
(186, 'TK0044D', 'sistem operasi waktu nyata', 'Dania Eridani, S.T., M.Eng.', 'D', 40, '2025/2026', NULL, NULL),
(187, 'TK0045A', 'kewarganegaraan', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D., Zaenul Muhlisin, S.Si., M.Si., F.Med.', 'A', 80, '2025/2026', NULL, NULL),
(188, 'TK0045B', 'kewarganegaraan', 'Dr. Drs. Slamet Subekti, M.Hum.', 'B', 80, '2025/2026', NULL, NULL),
(189, 'TK0046A', 'keamanan jaringan komputer', 'Adnan Fauzi, S.T., M.Kom., Prof. Dr. Adian Fatchur Rochim, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(190, 'TK0046B', 'keamanan jaringan komputer', 'Adnan Fauzi, S.T., M.Kom.', 'B', 40, '2025/2026', NULL, NULL),
(191, 'TK0046C', 'keamanan jaringan komputer', 'Adnan Fauzi, S.T., M.Kom.', 'C', 40, '2025/2026', NULL, NULL),
(192, 'TK0046D', 'keamanan jaringan komputer', 'Adnan Fauzi, S.T., M.Kom.', 'D', 40, '2025/2026', NULL, NULL),
(193, 'TK0047A', 'pemrograman jaringan', 'Arseto Satriyo Nugroho, S.T., M.Eng., Bellia Dwi Cahya Putri, S.T., M.T.', 'A', 40, '2025/2026', NULL, NULL),
(194, 'TK0047B', 'pemrograman jaringan', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(195, 'TK0047C', 'pemrograman jaringan', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(196, 'TK0047D', 'pemrograman jaringan', 'Agung Budi Prasetijo, S.T., M.I.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL),
(197, 'TK0048A', 'kewirausahaan', 'Bambang Irawanto, S.Si., M.Si., Dania Eridani, S.T., M.Eng.', 'A', 40, '2025/2026', NULL, NULL),
(198, 'TK0048B', 'kewirausahaan', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'B', 40, '2025/2026', NULL, NULL),
(199, 'TK0048C', 'kewirausahaan', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'C', 40, '2025/2026', NULL, NULL),
(200, 'TK0048D', 'kewirausahaan', 'Prof. Dr. Ir. R. Rizal Isnanto, S.T., M.M., M.T., IPU., ASEAN Eng.', 'D', 40, '2025/2026', NULL, NULL),
(201, 'TK0049A', 'proyek desain capstone 1', 'Dr. Delphi Hanggoro, S.T., M.T., Dinar Mutiara Kusumo Nugraheni, S.T., M.InfoTech.(Comp)., Ph.D.', 'A', 40, '2025/2026', NULL, NULL),
(202, 'TK0049B', 'proyek desain capstone 1', 'Kuntoro Adi Nugroho, S.T., M.Eng., Ph.D.', 'B', 40, '2025/2026', NULL, NULL),
(203, 'TK0012A', 'agama konghucu', 'Priyati Ningsih S.ag,.M.Pd.B., Dr. Redemtus Heru Tjahjana, S.Si., M.Si.', 'A', 40, '2025/2026', NULL, NULL),
(204, 'TKO-2025-A', 'pemrograman dasar', 'Damar Nurwahyu Bima, S.Si., M.Si., Mas Ut, S.Ag., M.Si.', 'A', 35, '2025/2026', NULL, NULL),
(205, 'TKO-2025-B', 'pemrograman dasar', 'Ike Pertiwi Windasari, S.T., M.T., Mas Ut, S.Ag., M.Si.', 'B', 35, '2025/2026', NULL, NULL),
(206, 'TKO-2025-C', 'pemrograman dasar', 'Erwin Adriono, S.T., M.T., Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'C', 40, '2025/2026', NULL, NULL),
(207, 'TKO-2025-D', 'pemrograman dasar', 'Ilmam Fauzi Hashbil Alim, S.T., M.Kom., Rinta Kridalukmana, S.Kom., M.T., Ph.D.', 'D', 40, '2025/2026', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas_matkul`
--

CREATE TABLE `kelas_matkul` (
  `id_kelas` int(11) NOT NULL,
  `kode_matkul` varchar(50) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `kode_rombel` varchar(50) NOT NULL,
  `jumlah_mahasiswa` int(11) NOT NULL DEFAULT 0,
  `kode_semester` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `kode_prodi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_matkul`
--

INSERT INTO `kelas_matkul` (`id_kelas`, `kode_matkul`, `nama_kelas`, `kode_rombel`, `jumlah_mahasiswa`, `kode_semester`, `tahun_ajaran`, `kode_prodi`, `created_at`, `updated_at`) VALUES
(5, 'TK0002', 'A', 'TKO-2025-A', 35, 1, '2025/2026', 'TKO', NULL, NULL),
(6, 'TK0002', 'B', 'TKO-2025-B', 40, 1, '2025/2026', 'TKO', NULL, NULL),
(23, 'TK0003', 'A', 'TK0003A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(24, 'TK0004', 'A', 'TK0004A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(27, 'TK0007', 'A', 'TK0007A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(28, 'TK0008', 'A', 'TK0008A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(29, 'TK0009', 'A', 'TK0009A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(30, 'TK0010', 'A', 'TK0010A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(33, 'TK0013', 'A', 'TK0013A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(34, 'TK0014', 'A', 'TK0014A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(35, 'TK0015', 'A', 'TK0015A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(36, 'TK0016', 'A', 'TK0016A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(37, 'TK0017', 'A', 'TK0017A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(38, 'TK0018', 'A', 'TK0018A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(39, 'TK0019', 'A', 'TK0019A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(40, 'TK0020', 'A', 'TK0020A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(41, 'TK0021', 'A', 'TK0021A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(42, 'TK0022', 'A', 'TK0022A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(43, 'TK0023', 'A', 'TK0023A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(44, 'TK0024', 'A', 'TK0024A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(45, 'TK0025', 'A', 'TK0025A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(46, 'TK0026', 'A', 'TK0026A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(47, 'TK0027', 'A', 'TK0027A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(48, 'TK0028', 'A', 'TK0028A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(49, 'TK0029', 'A', 'TK0029A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(50, 'TK0030', 'A', 'TK0030A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(51, 'TK0031', 'A', 'TK0031A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(52, 'TK0032', 'A', 'TK0032A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(53, 'TK0033', 'A', 'TK0033A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(54, 'TK0034', 'A', 'TK0034A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(55, 'TK0035', 'A', 'TK0035A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(56, 'TK0036', 'A', 'TK0036A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(57, 'TK0037', 'A', 'TK0037A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(58, 'TK0038', 'A', 'TK0038A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(59, 'TK0039', 'A', 'TK0039A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(60, 'TK0040', 'A', 'TK0040A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(61, 'TK0041', 'A', 'TK0041A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(62, 'TK0042', 'A', 'TK0042A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(63, 'TK0043', 'A', 'TK0043A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(64, 'TK0044', 'A', 'TK0044A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(65, 'TK0045', 'A', 'TK0045A', 80, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(66, 'TK0046', 'A', 'TK0046A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(67, 'TK0047', 'A', 'TK0047A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(68, 'TK0048', 'A', 'TK0048A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(69, 'TK0049', 'A', 'TK0049A', 40, 1, '2025/2026', 'TK', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(70, 'TK0050', 'A', 'TK0050A', 40, 1, '2025/2026', 'TK', '2026-05-19 08:44:13', '2026-05-19 08:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_matkul_dosen`
--

CREATE TABLE `kelas_matkul_dosen` (
  `id` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `kode_dosen` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_matkul_dosen`
--

INSERT INTO `kelas_matkul_dosen` (`id`, `id_kelas`, `kode_dosen`, `created_at`, `updated_at`) VALUES
(33, 15, 'KTM', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(34, 15, 'NBA', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(35, 16, 'NFN', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(36, 16, 'ODN', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(37, 17, 'PEM', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(38, 17, 'PYN', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(39, 18, 'RHT', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(40, 18, 'RZI', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(41, 19, 'SLS', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(42, 19, 'UDH', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(43, 20, 'VIM', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(44, 20, 'YEW', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(45, 21, 'ZNM', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(46, 21, 'ABP', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(47, 22, 'ADF', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(48, 22, 'AFR', '2026-05-05 11:02:36', '2026-05-05 11:02:36'),
(359, 7, 'ABP', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(360, 7, 'ADF', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(361, 8, 'AFR', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(362, 8, 'ASN', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(363, 9, 'BCP', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(364, 9, 'BIR', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(365, 10, 'DED', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(366, 10, 'DEL', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(367, 11, 'DMK', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(368, 11, 'DNB', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(369, 12, 'EDW', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(370, 12, 'ERA', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(371, 13, 'IFH', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(372, 13, 'IPW', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(373, 14, 'JWT', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(374, 14, 'KAN', '2026-05-05 13:03:12', '2026-05-05 13:03:12'),
(1753, 1, 'MAS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1754, 1, 'RKL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1755, 2, 'EDW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1756, 2, 'RKL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1757, 3, 'ERA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1758, 3, 'RKL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1759, 4, 'IFH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1760, 4, 'RKL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1761, 5, 'DNB', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1762, 5, 'MAS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1763, 6, 'IPW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1764, 6, 'MAS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1765, 23, 'ASN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1766, 23, 'BCP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1767, 24, 'BIR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1768, 24, 'DED', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1769, 25, 'DEL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1770, 25, 'DMK', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1771, 26, 'DNB', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1772, 26, 'EDW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1773, 27, 'ERA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1774, 27, 'IFH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1775, 28, 'IPW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1776, 28, 'JWT', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1777, 29, 'KAN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1778, 29, 'KTM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1779, 30, 'NBA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1780, 30, 'NFN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1781, 31, 'ODN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1782, 31, 'PEM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1783, 32, 'PYN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1784, 32, 'RHT', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1785, 33, 'RZI', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1786, 33, 'SLS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1787, 34, 'UDH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1788, 34, 'VIM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1789, 35, 'YEW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1790, 35, 'ZNM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1791, 36, 'ABP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1792, 36, 'ADF', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1793, 37, 'AFR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1794, 37, 'ASN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1795, 38, 'BCP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1796, 38, 'BIR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1797, 39, 'DED', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1798, 39, 'DEL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1799, 40, 'DMK', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1800, 40, 'DNB', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1801, 41, 'EDW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1802, 41, 'ERA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1803, 42, 'IFH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1804, 42, 'IPW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1805, 43, 'JWT', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1806, 43, 'KAN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1807, 44, 'KTM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1808, 44, 'NBA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1809, 45, 'NFN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1810, 45, 'ODN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1811, 46, 'PEM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1812, 46, 'PYN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1813, 47, 'RHT', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1814, 47, 'RZI', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1815, 48, 'SLS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1816, 48, 'UDH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1817, 49, 'VIM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1818, 49, 'YEW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1819, 50, 'ABP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1820, 50, 'ZNM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1821, 51, 'ADF', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1822, 51, 'AFR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1823, 52, 'ASN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1824, 52, 'BCP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1825, 53, 'BIR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1826, 53, 'DED', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1827, 54, 'DEL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1828, 54, 'DMK', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1829, 55, 'DNB', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1830, 55, 'EDW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1831, 56, 'ERA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1832, 56, 'IFH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1833, 57, 'IPW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1834, 57, 'JWT', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1835, 58, 'KAN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1836, 58, 'KTM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1837, 59, 'MAS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1838, 59, 'NBA', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1839, 60, 'NFN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1840, 60, 'ODN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1841, 61, 'PEM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1842, 61, 'PYN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1843, 62, 'RHT', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1844, 62, 'RZI', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1845, 63, 'SLS', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1846, 63, 'UDH', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1847, 64, 'VIM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1848, 64, 'YEW', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1849, 65, 'ABP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1850, 65, 'ZNM', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1851, 66, 'ADF', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1852, 66, 'AFR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1853, 67, 'ASN', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1854, 67, 'BCP', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1855, 68, 'BIR', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1856, 68, 'DED', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1857, 69, 'DEL', '2026-05-05 14:02:44', '2026-05-05 14:02:44'),
(1858, 69, 'DMK', '2026-05-05 14:02:44', '2026-05-05 14:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `kuliah`
--

CREATE TABLE `kuliah` (
  `id_kuliah` bigint(20) UNSIGNED NOT NULL,
  `kode_kuliah` varchar(40) NOT NULL,
  `kode_matkul` varchar(40) NOT NULL,
  `kode_dosen` varchar(255) NOT NULL,
  `kode_kelas` varchar(40) NOT NULL,
  `kode_prodi` char(10) NOT NULL,
  `kode_semester` char(10) NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kuliah`
--

INSERT INTO `kuliah` (`id_kuliah`, `kode_kuliah`, `kode_matkul`, `kode_dosen`, `kode_kelas`, `kode_prodi`, `kode_semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(4200, '5', 'TK0002', '', 'TK0002A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4201, '6', 'TK0002', 'MAS', 'TK0002B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4202, '7', 'TK0002', 'MAS', 'TK0002C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4203, '8', 'TK0003', '', 'TK0003A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4204, '9', 'TK0003', 'ABP', 'TK0003B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4205, '10', 'TK0003', 'ABP', 'TK0003C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4206, '11', 'TK0003', 'ABP', 'TK0003D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4207, '12', 'TK0004', '', 'TK0004A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4208, '13', 'TK0004', 'BCP', 'TK0004B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4209, '14', 'TK0004', 'BCP', 'TK0004C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4210, '15', 'TK0004', 'BCP', 'TK0004D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4219, '24', 'TK0007', '', 'TK0007A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4220, '25', 'TK0007', 'NBA', 'TK0007B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4221, '26', 'TK0007', 'NBA', 'TK0007C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4222, '27', 'TK0007', 'NBA', 'TK0007D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4223, '28', 'TK0008', '', 'TK0008A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4224, '29', 'TK0008', 'UDH', 'TK0008B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4225, '30', 'TK0008', 'UDH', 'TK0008C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4226, '31', 'TK0008', 'UDH', 'TK0008D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4227, '32', 'TK0009', '', 'TK0009A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4228, '33', 'TK0010', '', 'TK0010A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4231, '36', 'TK0013', '', 'TK0013A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4232, '37', 'TK0013', 'KTM', 'TK0013B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4233, '38', 'TK0013', 'KTM', 'TK0013C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4234, '39', 'TK0013', 'KTM', 'TK0013D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4235, '40', 'TK0014', '', 'TK0014A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4236, '41', 'TK0014', 'RKL', 'TK0014B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4237, '42', 'TK0014', 'RKL', 'TK0014C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4238, '43', 'TK0014', 'RKL', 'TK0014D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4239, '44', 'TK0015', '', 'TK0015A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4240, '45', 'TK0015', 'ODN', 'TK0015B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4241, '46', 'TK0015', 'ODN', 'TK0015C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4242, '47', 'TK0015', 'ODN', 'TK0015D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4243, '48', 'TK0016', '', 'TK0016A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4244, '49', 'TK0016', 'ABP', 'TK0016B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4245, '50', 'TK0016', 'ABP', 'TK0016C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4246, '51', 'TK0016', 'ABP', 'TK0016D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4247, '52', 'TK0017', '', 'TK0017A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4248, '53', 'TK0017', 'RHT', 'TK0017B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4249, '54', 'TK0017', 'RHT', 'TK0017C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4250, '55', 'TK0017', 'RHT', 'TK0017D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4251, '56', 'TK0018', '', 'TK0018A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4252, '57', 'TK0018', 'DED', 'TK0018B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4253, '58', 'TK0018', 'DED', 'TK0018C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4254, '59', 'TK0019', '', 'TK0019A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4255, '60', 'TK0019', 'ADF', 'TK0019B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4256, '61', 'TK0019', 'ADF', 'TK0019C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4257, '62', 'TK0019', 'ADF', 'TK0019D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4258, '63', 'TK0020', '', 'TK0020A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4259, '64', 'TK0020', 'KTM', 'TK0020B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4260, '65', 'TK0020', 'KTM', 'TK0020C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4261, '66', 'TK0020', 'KTM', 'TK0020D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4262, '67', 'TK0021', '', 'TK0021A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4263, '68', 'TK0021', 'RKL', 'TK0021B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4264, '69', 'TK0021', 'RKL', 'TK0021C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4265, '70', 'TK0021', 'RKL', 'TK0021D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4266, '71', 'TK0022', '', 'TK0022A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4267, '72', 'TK0022', 'ADF', 'TK0022B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4268, '73', 'TK0022', 'ADF', 'TK0022C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4269, '74', 'TK0022', 'ADF', 'TK0022D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4270, '75', 'TK0023', '', 'TK0023A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4271, '76', 'TK0023', 'ODN', 'TK0023B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4272, '77', 'TK0023', 'ODN', 'TK0023C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4273, '78', 'TK0023', 'ODN', 'TK0023D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4274, '79', 'TK0024', '', 'TK0024A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4275, '80', 'TK0024', 'ERA', 'TK0024B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4276, '81', 'TK0024', 'ERA', 'TK0024C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4277, '82', 'TK0024', 'ERA', 'TK0024D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4278, '83', 'TK0025', '', 'TK0025A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4279, '84', 'TK0025', 'EDW', 'TK0025B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4280, '85', 'TK0025', 'EDW', 'TK0025C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4281, '86', 'TK0025', 'EDW', 'TK0025D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4282, '87', 'TK0026', '', 'TK0026A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4283, '88', 'TK0026', 'DEL', 'TK0026B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4284, '89', 'TK0026', 'DEL', 'TK0026C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4285, '90', 'TK0026', 'DEL', 'TK0026D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4286, '91', 'TK0027', '', 'TK0027A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4287, '92', 'TK0027', 'ERA', 'TK0027B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4288, '93', 'TK0027', 'ERA', 'TK0027C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4289, '94', 'TK0027', 'ERA', 'TK0027D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4290, '95', 'TK0028', '', 'TK0028A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4291, '96', 'TK0028', 'ODN', 'TK0028B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4292, '97', 'TK0028', 'ODN', 'TK0028C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4293, '98', 'TK0028', 'ODN', 'TK0028D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4294, '99', 'TK0029', '', 'TK0029A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4295, '100', 'TK0029', 'KTM', 'TK0029B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4296, '101', 'TK0029', 'KTM', 'TK0029C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4297, '102', 'TK0029', 'KTM', 'TK0029D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4298, '103', 'TK0030', '', 'TK0030A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4299, '104', 'TK0030', 'EDW', 'TK0030B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4300, '105', 'TK0030', 'EDW', 'TK0030C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4301, '106', 'TK0030', 'EDW', 'TK0030D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4302, '107', 'TK0031', '', 'TK0031A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4303, '108', 'TK0031', 'ERA', 'TK0031B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4304, '109', 'TK0031', 'ERA', 'TK0031C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4305, '110', 'TK0031', 'ERA', 'TK0031D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4306, '111', 'TK0032', '', 'TK0032A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4307, '112', 'TK0032', 'KAN', 'TK0032B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4308, '113', 'TK0032', 'KAN', 'TK0032C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4309, '114', 'TK0032', 'KAN', 'TK0032D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4310, '115', 'TK0033', '', 'TK0033A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4311, '116', 'TK0033', 'SLS', 'TK0033B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4312, '117', 'TK0034', '', 'TK0034A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4313, '118', 'TK0035', '', 'TK0035A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4314, '119', 'TK0036', '', 'TK0036A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4315, '120', 'TK0037', '', 'TK0037A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4316, '121', 'TK0038', '', 'TK0038A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4317, '122', 'TK0039', '', 'TK0039A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4318, '123', 'TK0040', '', 'TK0040A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4319, '124', 'TK0041', '', 'TK0041A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4320, '125', 'TK0041', 'RZI', 'TK0041B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4321, '126', 'TK0041', 'RZI', 'TK0041C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4322, '127', 'TK0041', 'RZI', 'TK0041D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4323, '128', 'TK0042', '', 'TK0042A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4324, '129', 'TK0042', 'ERA', 'TK0042B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4325, '130', 'TK0042', 'ERA', 'TK0042C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4326, '131', 'TK0042', 'ERA', 'TK0042D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4327, '132', 'TK0043', '', 'TK0043A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4328, '133', 'TK0043', 'IPW', 'TK0043B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4329, '134', 'TK0043', 'IPW', 'TK0043C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4330, '135', 'TK0043', 'IPW', 'TK0043D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4331, '136', 'TK0044', '', 'TK0044A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4332, '137', 'TK0044', 'DED', 'TK0044B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4333, '138', 'TK0044', 'DED', 'TK0044C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4334, '139', 'TK0044', 'DED', 'TK0044D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4335, '140', 'TK0045', '', 'TK0045A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4336, '141', 'TK0045', 'SLS', 'TK0045B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4337, '142', 'TK0046', '', 'TK0046A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4338, '143', 'TK0046', 'ADF', 'TK0046B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4339, '144', 'TK0046', 'ADF', 'TK0046C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4340, '145', 'TK0046', 'ADF', 'TK0046D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4341, '146', 'TK0047', '', 'TK0047A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4342, '147', 'TK0047', 'ABP', 'TK0047B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4343, '148', 'TK0047', 'ABP', 'TK0047C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4344, '149', 'TK0047', 'ABP', 'TK0047D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4345, '150', 'TK0048', '', 'TK0048A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4346, '151', 'TK0048', 'RZI', 'TK0048B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4347, '152', 'TK0048', 'RZI', 'TK0048C', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4348, '153', 'TK0048', 'RZI', 'TK0048D', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4349, '154', 'TK0049', '', 'TK0049A', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4350, '155', 'TK0049', 'KAN', 'TK0049B', 'TK', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4351, '156', 'TKO-2025-', '', 'TKO-2025-A', 'TKO-2', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4352, '157', 'TKO-2025-', '', 'TKO-2025-B', 'TKO-2', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4353, '158', 'TKO-2025-', '', 'TKO-2025-C', 'TKO-2', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36'),
(4354, '159', 'TKO-2025-', '', 'TKO-2025-D', 'TKO-2', '1', '2025/2026', '2026-05-05 14:22:36', '2026-05-05 14:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `matkul`
--

CREATE TABLE `matkul` (
  `id_matkul` bigint(20) UNSIGNED NOT NULL,
  `kode_matkul` varchar(40) NOT NULL,
  `nama_matkul` varchar(255) NOT NULL,
  `sks` varchar(10) NOT NULL,
  `jenis_matkul` enum('teori','praktikum') NOT NULL DEFAULT 'teori',
  `kode_prodi` char(10) NOT NULL,
  `kode_semester` char(10) NOT NULL,
  `perkuliahan_semester` char(10) NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `matkul`
--

INSERT INTO `matkul` (`id_matkul`, `kode_matkul`, `nama_matkul`, `sks`, `jenis_matkul`, `kode_prodi`, `kode_semester`, `perkuliahan_semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(18, 'TK0002', 'agama islam', '2', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:17:30', '2026-04-27 06:17:30'),
(19, 'TK0003', 'rangkaian listrik', '2', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:18:31', '2026-04-27 06:18:31'),
(20, 'TK0004', 'teknologi informasi', '3', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:19:00', '2026-04-27 06:19:00'),
(23, 'TK0007', 'kimia', '2', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:23:32', '2026-04-27 06:23:32'),
(24, 'TK0008', 'fisika dasar 1', '3', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:24:25', '2026-04-27 06:24:25'),
(25, 'TK0009', 'agama budha', '1', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:25:58', '2026-04-27 06:25:58'),
(26, 'TK0010', 'agama katolik', '1', 'teori', 'TK', '1', '1', '2025/2026', '2026-04-27 06:27:05', '2026-04-27 06:27:05'),
(29, 'TK0013', 'kecerdasan buatan', '2', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:29:11', '2026-04-27 06:29:11'),
(30, 'TK0014', 'struktur data', '2', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:30:17', '2026-04-27 06:30:17'),
(31, 'TK0015', 'multimedia', '2', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:30:58', '2026-04-27 06:30:58'),
(32, 'TK0016', 'sistem digital lanjut', '2', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:31:36', '2026-04-27 06:31:36'),
(33, 'TK0017', 'aljabar linear', '3', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:32:05', '2026-04-27 06:32:05'),
(34, 'TK0018', 'bahasa inggris 3', '1', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:32:39', '2026-04-27 06:32:39'),
(35, 'TK0019', 'switching, routing, dan jaringan nirkabel', '2', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:33:23', '2026-04-27 06:33:23'),
(36, 'TK0020', 'interaksi manusia dan komputer', '2', 'teori', 'TK', '1', '3', '2025/2026', '2026-04-27 06:34:12', '2026-04-27 06:34:12'),
(37, 'TK0021', 'praktikum pemrograman dasar', '1', 'praktikum', 'TK', '1', '1', '2025/2026', '2026-04-27 06:36:04', '2026-04-27 06:36:04'),
(38, 'TK0022', 'praktikum switching, routing, dan jaringan nirkabel', '1', 'praktikum', 'TK', '1', '3', '2025/2026', '2026-04-27 06:37:35', '2026-04-27 06:37:35'),
(39, 'TK0023', 'praktikum multimedia', '1', 'praktikum', 'TK', '1', '3', '2025/2026', '2026-04-27 06:38:08', '2026-04-27 06:38:08'),
(40, 'TK0024', 'praktikum elektronika dasar', '1', 'praktikum', 'TK', '1', '3', '2025/2026', '2026-04-27 06:38:35', '2026-04-27 06:38:35'),
(41, 'TK0025', 'praktikum sistem digital', '1', 'praktikum', 'TK', '1', '3', '2025/2026', '2026-04-27 06:39:34', '2026-04-27 06:39:34'),
(42, 'TK0026', 'manajemen proyek ti', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:41:38', '2026-04-27 06:41:38'),
(43, 'TK0027', 'teknik mikroprosessor dan antarmuka', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:42:03', '2026-04-27 06:42:03'),
(44, 'TK0028', 'pemrograman berorientasi objek', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:42:29', '2026-04-27 06:42:29'),
(45, 'TK0029', 'pemrograman perangkat bergerak', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:45:26', '2026-04-27 06:45:26'),
(46, 'TK0030', 'metode numerik', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:46:07', '2026-04-27 06:46:07'),
(47, 'TK0031', 'bahasa pemrograman rakitan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:46:29', '2026-04-27 06:46:29'),
(48, 'TK0032', 'sinyal dan sistem', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:46:50', '2026-04-27 06:46:50'),
(49, 'TK0033', 'pancasila', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 06:48:03', '2026-04-27 06:48:03'),
(50, 'TK0034', 'pemrograman game pilihan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:09:17', '2026-04-27 07:09:17'),
(51, 'TK0035', 'audit si / ti pilihan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:09:49', '2026-04-27 07:09:49'),
(52, 'TK0036', 'jaringan syaraf tiruan pilihan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:11:30', '2026-04-27 07:11:30'),
(53, 'TK0037', 'sistem informasi pilihan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:12:49', '2026-04-27 07:12:49'),
(54, 'TK0038', 'sistem terintegrasi pilihan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:13:30', '2026-04-27 07:13:30'),
(55, 'TK0039', 'infrastruktur awan pilihan', '2', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:13:52', '2026-04-27 07:13:52'),
(56, 'TK0040', 'perencanaan strategis si / ti pilihan', '3', 'teori', 'TK', '1', '5', '2025/2026', '2026-04-27 07:14:55', '2026-04-27 07:14:55'),
(57, 'TK0041', 'kecakapan antar personal', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:15:38', '2026-04-27 07:15:38'),
(58, 'TK0042', 'internet of things', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:16:06', '2026-04-27 07:16:06'),
(59, 'TK0043', 'etika profesi', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:17:01', '2026-04-27 07:17:01'),
(60, 'TK0044', 'sistem operasi waktu nyata', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:17:32', '2026-04-27 07:17:32'),
(61, 'TK0045', 'kewarganegaraan', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:18:05', '2026-04-27 07:18:05'),
(62, 'TK0046', 'keamanan jaringan komputer', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:18:29', '2026-04-27 07:18:29'),
(63, 'TK0047', 'pemrograman jaringan', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:19:39', '2026-04-27 07:19:39'),
(64, 'TK0048', 'kewirausahaan', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:20:10', '2026-04-27 07:20:10'),
(65, 'TK0049', 'proyek desain capstone 1', '2', 'teori', 'TK', '1', '7', '2025/2026', '2026-04-27 07:21:05', '2026-04-27 07:21:05'),
(66, 'TK0050', 'praktikum fisika dasar 2', '1', 'praktikum', 'TK', '1', '3', '2025/2026', '2026-05-19 08:40:48', '2026-05-19 08:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `matkul_dosen`
--

CREATE TABLE `matkul_dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_matkul` varchar(40) NOT NULL,
  `kode_dosen` varchar(30) NOT NULL,
  `tahun_ajaran` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `matkul_dosen`
--

INSERT INTO `matkul_dosen` (`id`, `kode_matkul`, `kode_dosen`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, 'TK0005', 'NFN', '2025/2026', '2026-05-19 03:21:26', '2026-05-19 03:21:26'),
(2, 'TK0002', 'MAS', '2025/2026', '2026-05-19 03:21:43', '2026-05-19 03:21:43'),
(4, 'TK0050', 'UDH', '2025/2026', '2026-05-19 08:41:22', '2026-05-19 08:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
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
(69, '2021_09_26_024355_create_kuliah_table', 3),
(70, '2026_05_05_000001_update_multi_dosen_columns', 4),
(71, '2026_05_08_000001_create_matkul_dosen_table', 5),
(72, '2026_05_20_000001_add_tipe_ruang_and_jenis_matkul_columns', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
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
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` bigint(20) UNSIGNED NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `kode_prodi` char(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id_prodi`, `nama_prodi`, `kode_prodi`, `created_at`, `updated_at`) VALUES
(1, 'teknik komputer', 'TK', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_kuliah`
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
  `nama_dosen` text NOT NULL,
  `kapasitas_kelas` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_ruang`
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
-- Table structure for table `request_waktu`
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
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `kode_ruang` bigint(20) UNSIGNED NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 0,
  `tipe_ruang` enum('reguler','laboratorium') NOT NULL DEFAULT 'reguler'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruang`
--

INSERT INTO `ruang` (`kode_ruang`, `nama_ruang`, `nama_prodi`, `created_at`, `updated_at`, `kapasitas`, `tipe_ruang`) VALUES
(1, 'ruang a202 teknik komputer', 'teknik komputer', NULL, '2026-05-19 08:15:18', 50, 'reguler'),
(2, 'ruang a201 teknik komputer', 'teknik komputer', NULL, '2026-05-19 08:15:34', 50, 'reguler'),
(3, 'ruang b101', 'teknik komputer', NULL, NULL, 35, 'reguler'),
(4, 'ruang b102', 'teknik komputer', NULL, NULL, 35, 'reguler'),
(5, 'ruang b201', 'teknik komputer', NULL, NULL, 50, 'reguler'),
(6, 'ruang dexlite 401', 'teknik komputer', NULL, NULL, 50, 'reguler'),
(7, 'laboratorium jarkom', 'teknik komputer', NULL, NULL, 30, 'laboratorium'),
(8, 'laboratorium software', 'teknik komputer', NULL, NULL, 30, 'laboratorium'),
(9, 'laboratorium multimedia', 'teknik komputer', NULL, NULL, 30, 'laboratorium'),
(10, 'laboratorium embeded', 'teknik komputer', NULL, NULL, 30, 'laboratorium'),
(11, 'laboratorium fisika, fakultas sains dan matematika', 'teknik komputer', '2026-05-19 08:43:58', '2026-05-19 09:22:44', 35, 'laboratorium');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `kode_semester` bigint(20) UNSIGNED NOT NULL,
  `nama_semester` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`kode_semester`, `nama_semester`, `created_at`, `updated_at`) VALUES
(1, 'ganjil', NULL, NULL),
(2, 'genap', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(5, '2025/2026', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `name`, `username`, `email`, `image`, `role_id`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', 'default.jpg', 1, 1, NULL, '$2y$10$iMs.oTGdRJFSFp5AFGYvteIQOAdCwKhszFizcQOEb7XGQPeFMrtz2', NULL, '2024-09-17 18:26:49', NULL),
(2, 'Dosen', 'dosen', 'dosen@gmail.com', 'default.jpg', 2, 1, NULL, '$2y$10$4AkWn5.Hhlps0GqSWcjpOuVdo2KHZ2XeITR7QktZqpMkvz5f9GdOe', NULL, '2024-09-29 03:48:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `waktu`
--

CREATE TABLE `waktu` (
  `kode_waktu` bigint(20) UNSIGNED NOT NULL,
  `kode_hari` varchar(30) NOT NULL,
  `kode_jam` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `waktu`
--

INSERT INTO `waktu` (`kode_waktu`, `kode_hari`, `kode_jam`, `created_at`, `updated_at`) VALUES
(2, '1', '2', NULL, NULL),
(3, '1', '3', NULL, NULL),
(4, '1', '4', NULL, NULL),
(5, '1', '5', NULL, NULL),
(6, '1', '6', NULL, NULL),
(8, '2', '2', NULL, NULL),
(9, '2', '3', NULL, NULL),
(10, '2', '4', NULL, NULL),
(11, '2', '5', NULL, NULL),
(12, '2', '6', NULL, NULL),
(14, '3', '2', NULL, NULL),
(15, '3', '3', NULL, NULL),
(16, '3', '4', NULL, NULL),
(17, '3', '5', NULL, NULL),
(18, '3', '6', NULL, NULL),
(20, '4', '2', NULL, NULL),
(21, '4', '3', NULL, NULL),
(22, '4', '4', NULL, NULL),
(23, '4', '5', NULL, NULL),
(24, '4', '6', NULL, NULL),
(26, '5', '2', NULL, NULL),
(27, '5', '3', NULL, NULL),
(28, '5', '4', NULL, NULL),
(29, '5', '5', NULL, NULL),
(30, '5', '6', NULL, NULL),
(31, '5', '7', NULL, NULL),
(32, '5', '8', NULL, NULL),
(33, '5', '9', NULL, NULL),
(34, '5', '10', NULL, NULL),
(35, '5', '11', NULL, NULL),
(36, '5', '12', NULL, NULL),
(37, '5', '13', NULL, NULL),
(38, '5', '14', NULL, NULL),
(39, '5', '15', NULL, NULL),
(40, '5', '16', NULL, NULL),
(41, '5', '17', NULL, NULL),
(42, '5', '18', NULL, NULL),
(43, '5', '19', NULL, NULL),
(44, '5', '20', NULL, NULL),
(45, '5', '21', NULL, NULL),
(46, '5', '22', NULL, NULL),
(47, '5', '23', NULL, NULL),
(48, '5', '24', NULL, NULL),
(49, '5', '25', NULL, NULL),
(50, '5', '26', NULL, NULL),
(51, '5', '27', NULL, NULL),
(52, '5', '28', NULL, NULL),
(53, '5', '29', NULL, NULL),
(54, '5', '30', NULL, NULL),
(62, '1', '7', NULL, NULL),
(63, '2', '7', NULL, NULL),
(64, '3', '7', NULL, NULL),
(65, '4', '7', NULL, NULL),
(66, '1', '8', NULL, NULL),
(67, '2', '8', NULL, NULL),
(68, '3', '8', NULL, NULL),
(69, '4', '8', NULL, NULL),
(70, '1', '9', NULL, NULL),
(71, '2', '9', NULL, NULL),
(72, '3', '9', NULL, NULL),
(73, '4', '9', NULL, NULL),
(74, '1', '10', NULL, NULL),
(75, '2', '10', NULL, NULL),
(76, '3', '10', NULL, NULL),
(77, '4', '10', NULL, NULL),
(78, '1', '11', NULL, NULL),
(79, '2', '11', NULL, NULL),
(80, '3', '11', NULL, NULL),
(81, '4', '11', NULL, NULL),
(82, '1', '12', NULL, NULL),
(83, '2', '12', NULL, NULL),
(84, '3', '12', NULL, NULL),
(85, '4', '12', NULL, NULL),
(86, '1', '13', NULL, NULL),
(87, '2', '13', NULL, NULL),
(88, '3', '13', NULL, NULL),
(89, '4', '13', NULL, NULL),
(90, '1', '14', NULL, NULL),
(91, '2', '14', NULL, NULL),
(92, '3', '14', NULL, NULL),
(93, '4', '14', NULL, NULL),
(94, '1', '15', NULL, NULL),
(95, '2', '15', NULL, NULL),
(96, '3', '15', NULL, NULL),
(97, '4', '15', NULL, NULL),
(98, '1', '16', NULL, NULL),
(99, '2', '16', NULL, NULL),
(100, '3', '16', NULL, NULL),
(101, '4', '16', NULL, NULL),
(102, '1', '17', NULL, NULL),
(103, '2', '17', NULL, NULL),
(104, '3', '17', NULL, NULL),
(105, '4', '17', NULL, NULL),
(106, '1', '18', NULL, NULL),
(107, '2', '18', NULL, NULL),
(108, '3', '18', NULL, NULL),
(109, '4', '18', NULL, NULL),
(110, '1', '19', NULL, NULL),
(111, '2', '19', NULL, NULL),
(112, '3', '19', NULL, NULL),
(113, '4', '19', NULL, NULL),
(114, '1', '20', NULL, NULL),
(115, '2', '20', NULL, NULL),
(116, '3', '20', NULL, NULL),
(117, '4', '20', NULL, NULL),
(118, '1', '21', NULL, NULL),
(119, '2', '21', NULL, NULL),
(120, '3', '21', NULL, NULL),
(121, '4', '21', NULL, NULL),
(122, '1', '22', NULL, NULL),
(123, '2', '22', NULL, NULL),
(124, '3', '22', NULL, NULL),
(125, '4', '22', NULL, NULL),
(126, '1', '23', NULL, NULL),
(127, '2', '23', NULL, NULL),
(128, '3', '23', NULL, NULL),
(129, '4', '23', NULL, NULL),
(130, '1', '24', NULL, NULL),
(131, '2', '24', NULL, NULL),
(132, '3', '24', NULL, NULL),
(133, '4', '24', NULL, NULL),
(134, '1', '25', NULL, NULL),
(135, '2', '25', NULL, NULL),
(136, '3', '25', NULL, NULL),
(137, '4', '25', NULL, NULL),
(138, '1', '26', NULL, NULL),
(139, '2', '26', NULL, NULL),
(140, '3', '26', NULL, NULL),
(141, '4', '26', NULL, NULL),
(142, '1', '27', NULL, NULL),
(143, '2', '27', NULL, NULL),
(144, '3', '27', NULL, NULL),
(145, '4', '27', NULL, NULL),
(146, '1', '28', NULL, NULL),
(147, '2', '28', NULL, NULL),
(148, '3', '28', NULL, NULL),
(149, '4', '28', NULL, NULL),
(150, '1', '29', NULL, NULL),
(151, '2', '29', NULL, NULL),
(152, '3', '29', NULL, NULL),
(153, '4', '29', NULL, NULL),
(154, '1', '30', NULL, NULL),
(155, '2', '30', NULL, NULL),
(156, '3', '30', NULL, NULL),
(157, '4', '30', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blocking_jadwal_dosen`
--
ALTER TABLE `blocking_jadwal_dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`kode_dosen`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hari`
--
ALTER TABLE `hari`
  ADD PRIMARY KEY (`kode_hari`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jadwal_dosen_jadwal_id` (`jadwal_id`),
  ADD KEY `idx_jadwal_dosen_kode_dosen` (`kode_dosen`);

--
-- Indexes for table `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`kode_jam`),
  ADD UNIQUE KEY `jam_jam_unique` (`jam`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `kelas_matkul`
--
ALTER TABLE `kelas_matkul`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `kelas_matkul_dosen`
--
ALTER TABLE `kelas_matkul_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kelas_dosen` (`id_kelas`,`kode_dosen`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `kode_dosen` (`kode_dosen`);

--
-- Indexes for table `kuliah`
--
ALTER TABLE `kuliah`
  ADD PRIMARY KEY (`id_kuliah`);

--
-- Indexes for table `matkul`
--
ALTER TABLE `matkul`
  ADD PRIMARY KEY (`id_matkul`);

--
-- Indexes for table `matkul_dosen`
--
ALTER TABLE `matkul_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matkul_dosen_unique` (`kode_matkul`,`kode_dosen`,`tahun_ajaran`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`),
  ADD UNIQUE KEY `prodi_nama_prodi_unique` (`nama_prodi`),
  ADD UNIQUE KEY `prodi_kode_prodi_unique` (`kode_prodi`),
  ADD UNIQUE KEY `nama_prodi` (`nama_prodi`),
  ADD UNIQUE KEY `nama_prodi_2` (`nama_prodi`);

--
-- Indexes for table `request_kuliah`
--
ALTER TABLE `request_kuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_ruang`
--
ALTER TABLE `request_ruang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_waktu`
--
ALTER TABLE `request_waktu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`kode_ruang`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`kode_semester`),
  ADD UNIQUE KEY `semester_nama_semester_unique` (`nama_semester`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `waktu`
--
ALTER TABLE `waktu`
  ADD PRIMARY KEY (`kode_waktu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocking_jadwal_dosen`
--
ALTER TABLE `blocking_jadwal_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hari`
--
ALTER TABLE `hari`
  MODIFY `kode_hari` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1179;

--
-- AUTO_INCREMENT for table `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2191;

--
-- AUTO_INCREMENT for table `jam`
--
ALTER TABLE `jam`
  MODIFY `kode_jam` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `kelas_matkul`
--
ALTER TABLE `kelas_matkul`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `kelas_matkul_dosen`
--
ALTER TABLE `kelas_matkul_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1859;

--
-- AUTO_INCREMENT for table `kuliah`
--
ALTER TABLE `kuliah`
  MODIFY `id_kuliah` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4355;

--
-- AUTO_INCREMENT for table `matkul`
--
ALTER TABLE `matkul`
  MODIFY `id_matkul` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `matkul_dosen`
--
ALTER TABLE `matkul_dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_kuliah`
--
ALTER TABLE `request_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `request_ruang`
--
ALTER TABLE `request_ruang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_waktu`
--
ALTER TABLE `request_waktu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `kode_ruang` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `kode_semester` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `waktu`
--
ALTER TABLE `waktu`
  MODIFY `kode_waktu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
