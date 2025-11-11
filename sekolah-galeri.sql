-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 07:23 AM
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
-- Database: `sekolah-galeri`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `foto`
--

CREATE TABLE `foto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `galery_id` bigint(20) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foto`
--

INSERT INTO `foto` (`id`, `galery_id`, `file`, `judul`, `created_at`, `updated_at`) VALUES
(1, 1, '1756438214_IMG_9426.JPG', 'Foto 1 - Pembukaan Tahun Ajaran Baru 2024/2025', '2025-08-28 17:41:07', '2025-08-28 20:30:14'),
(2, 2, '1756438225_IMG_9414.JPG', 'Foto 2 - Pembukaan Tahun Ajaran Baru 2024/2025', '2025-08-28 17:41:07', '2025-08-28 20:30:25'),
(3, 2, '1756438238_IMG_8783.JPG', 'Foto 3 - Pembukaan Tahun Ajaran Baru 2024/2025', '2025-08-28 17:41:07', '2025-08-28 20:30:38'),
(4, 1, '1756438250_IMG_8762.JPG', 'Foto 4 - Pembukaan Tahun Ajaran Baru 2024/2025', '2025-08-28 17:41:07', '2025-08-28 20:30:50'),
(5, 2, 'seni_budaya.jpg', 'Foto 1 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(6, 2, 'kegiatan_belajar_1.jpg', 'Foto 2 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(7, 2, 'upacara_bendera.jpg', 'Foto 3 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(8, 2, 'rapat_guru.jpg', 'Foto 4 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(9, 2, 'upacara_bendera.jpg', 'Foto 5 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(10, 3, 'seni_budaya.jpg', 'Foto 1 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(11, 3, 'perpustakaan.jpg', 'Foto 2 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(12, 3, 'kegiatan_belajar_2.jpg', 'Foto 3 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(13, 3, 'perpustakaan.jpg', 'Foto 4 - Kegiatan Pramuka Minggu Ini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(14, 4, 'kegiatan_belajar_2.jpg', 'Foto 1 - Foto Kegiatan Belajar Mengajar', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(15, 4, 'kantin_sekolah.jpg', 'Foto 2 - Foto Kegiatan Belajar Mengajar', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(16, 4, 'olahraga.jpg', 'Foto 3 - Foto Kegiatan Belajar Mengajar', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(17, 5, 'pramuka_1.jpg', 'Foto 1 - Foto Kegiatan Belajar Mengajar', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(18, 5, 'seni_budaya.jpg', 'Foto 2 - Foto Kegiatan Belajar Mengajar', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(19, 5, 'ekstrakurikuler_2.jpg', 'Foto 3 - Foto Kegiatan Belajar Mengajar', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(20, 6, 'seni_budaya.jpg', 'Foto 1 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(21, 6, 'perpustakaan.jpg', 'Foto 2 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(22, 6, 'kegiatan_belajar_2.jpg', 'Foto 3 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(23, 6, 'pramuka_2.jpg', 'Foto 4 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(24, 6, 'kunjungan_industri.jpg', 'Foto 5 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(25, 7, 'pramuka_1.jpg', 'Foto 1 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(26, 7, 'upacara_bendera.jpg', 'Foto 2 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(27, 7, 'olahraga.jpg', 'Foto 3 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(28, 7, 'rapat_guru.jpg', 'Foto 4 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(29, 7, 'ekstrakurikuler_2.jpg', 'Foto 5 - Jadwal Ujian Tengah Semester', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(30, 8, 'ekstrakurikuler_1.jpg', 'Foto 1 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(31, 8, 'pramuka_2.jpg', 'Foto 2 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(32, 8, 'upacara_bendera.jpg', 'Foto 3 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(33, 8, 'ekstrakurikuler_2.jpg', 'Foto 4 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(34, 8, 'upacara_bendera.jpg', 'Foto 5 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(35, 9, 'kantin_sekolah.jpg', 'Foto 1 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(36, 9, 'praktek_lab.jpg', 'Foto 2 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(37, 10, 'praktek_lab.jpg', 'Foto 1 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(38, 10, 'kunjungan_industri.jpg', 'Foto 2 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(39, 10, 'ekstrakurikuler_2.jpg', 'Foto 3 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(40, 10, 'pramuka_2.jpg', 'Foto 4 - Kegiatan Ekstrakurikuler Seni', '2025-08-28 17:41:07', '2025-08-28 17:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `galery`
--

CREATE TABLE `galery` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `position` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galery`
--

INSERT INTO `galery` (`id`, `post_id`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(2, 2, 1, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(3, 2, 2, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(4, 3, 1, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(5, 3, 2, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(6, 4, 1, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(7, 4, 2, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(8, 5, 1, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(9, 5, 2, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(10, 5, 3, 'active', '2025-08-28 17:41:07', '2025-08-28 17:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `judul`, `created_at`, `updated_at`) VALUES
(1, 'Informasi Terkini', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(2, 'Galery Sekolah', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(3, 'Agenda Sekolah', '2025-08-28 17:41:07', '2025-08-28 17:41:07');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_kategori_table', 1),
(5, '2024_01_01_000004_create_petugas_table', 1),
(6, '2024_01_01_000005_create_posts_table', 1),
(7, '2024_01_01_000006_create_galery_table', 1),
(8, '2024_01_01_000007_create_foto_table', 1),
(9, '2024_01_01_000008_create_profile_table', 1),
(10, '2025_08_26_012638_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
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
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(8, 'App\\Models\\User', 1, 'web-token', '29c319f936c01f8e02c0e776969cae284aafa5c5264a1c0b2a596ad17fecd993', '[\"*\"]', NULL, NULL, '2025-08-28 22:00:13', '2025-08-28 22:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$h/9wbOJHt8k/2EgAtuoazOUOAzDuD5xeXbvloY8F1IBwJC/XADTUe', '2025-08-28 17:41:07', '2025-08-28 17:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori_id` bigint(20) UNSIGNED DEFAULT NULL,
  `isi` text NOT NULL,
  `petugas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `judul`, `kategori_id`, `isi`, `petugas_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pembukaan Tahun Ajaran Baru 2024/2025', 1, 'Selamat datang di tahun ajaran baru 2024/2025. Semoga semua siswa dan guru dapat menjalankan kegiatan belajar mengajar dengan semangat dan dedikasi yang tinggi.', 1, 'published', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(2, 'Kegiatan Pramuka Minggu Ini', 3, 'Kegiatan pramuka akan dilaksanakan pada hari Sabtu pukul 07.00 WIB. Semua anggota pramuka wajib hadir dengan seragam lengkap.', 1, 'published', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(3, 'Foto Kegiatan Belajar Mengajar', 2, 'Berikut adalah dokumentasi kegiatan belajar mengajar di kelas yang menunjukkan antusiasme siswa dalam menimba ilmu.', 1, 'published', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(4, 'Jadwal Ujian Tengah Semester', 1, 'Ujian Tengah Semester akan dilaksanakan pada tanggal 15-20 Oktober 2024. Semua siswa diharapkan mempersiapkan diri dengan baik.', 1, 'published', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(5, 'Kegiatan Ekstrakurikuler Seni', 3, 'Ekstrakurikuler seni akan mengadakan pertunjukan pada akhir bulan ini. Semua siswa yang berminat dapat mendaftar di ruang guru.', 1, 'draft', '2025-08-28 17:41:07', '2025-08-28 17:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `judul`, `isi`, `created_at`, `updated_at`) VALUES
(1, 'Visi Sekolah', 'Menjadi sekolah unggulan yang menghasilkan lulusan berkualitas, berakhlak mulia, dan siap menghadapi tantangan global dengan mengembangkan potensi siswa secara optimal melalui pendidikan yang berkualitas dan berwawasan lingkungan.', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(2, 'Misi Sekolah', '1. Menyelenggarakan pendidikan yang berkualitas dan berwawasan global\\n2. Mengembangkan potensi siswa secara optimal melalui kegiatan akademik dan non-akademik\\n3. Menanamkan nilai-nilai karakter dan akhlak mulia\\n4. Mewujudkan lingkungan sekolah yang nyaman, aman, dan kondusif\\n5. Mengembangkan kerjasama dengan berbagai pihak untuk meningkatkan kualitas pendidikan', '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(3, 'Sejarah Sekolah', 'Sekolah ini didirikan pada tahun 1985 dengan visi untuk memberikan pendidikan berkualitas kepada masyarakat. Berawal dari gedung sederhana dengan beberapa ruang kelas, sekolah ini telah berkembang menjadi institusi pendidikan yang diakui keunggulannya di tingkat regional maupun nasional.', '2025-08-28 17:41:07', '2025-08-28 17:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('63C5GTRRFwV5BKRSGefCn3C0PTlY8gjF73Hpee2G', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0thZ1czUUhUbmdWY1JuN3JnN25PUGY4T1ZSU2pCc0NNell2dXlsYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYWxlcmkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756439661),
('aCzgn3jpd8k4fLddZcWMkEQvXrkg5dNmhTxQoB5f', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHVmTmVLTFpwQVdPYVM3MDM3R0xZOGxpUEFUT010dVBJUTNZN3VOcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYWxlcmkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756439527),
('PRaOdbLx9f4O3hUy9xEK7hlCPZgOtsWFWfgWeAS8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVkhLM3VnYlYzTlhJbWRJckZqRWZ2Q0lkeGJCRjk1OHJ1MkkyQldUVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9maWxlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTE6ImFkbWluX3Rva2VuIjtzOjUwOiI4fFp6emxsZjBCRlJMY2NYQkFDY05VR01QODVId1gwalF1Z3ZWTFBER2NjYThmMTk1ZCI7czoxMzoiYWRtaW5fdXNlcl9pZCI7aToxO3M6MTA6ImFkbWluX3VzZXIiO2E6Mzp7czoyOiJpZCI7aToxO3M6NDoibmFtZSI7czo1OiJhZG1pbiI7czo1OiJlbWFpbCI7czoxNzoiYWRtaW5AZXhhbXBsZS5jb20iO319', 1756443624);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '2025-08-28 17:41:07', '$2y$12$WH5OHRdbbOR6K29Z0/1./eQkMSIR1fN/t75zimwZXv.m0olTUzBZK', NULL, '2025-08-28 17:41:07', '2025-08-28 17:41:07'),
(2, 'user', 'user@example.com', '2025-08-28 17:41:07', '$2y$12$e36if3OrqAPs3UXkIemqdenwBjDU79GFqZgD/bEKqUXx3nA9oQdCi', NULL, '2025-08-28 17:41:07', '2025-08-28 17:41:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foto_galery_id_foreign` (`galery_id`);

--
-- Indexes for table `galery`
--
ALTER TABLE `galery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galery_post_id_foreign` (`post_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `petugas_username_unique` (`username`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_kategori_id_foreign` (`kategori_id`),
  ADD KEY `posts_petugas_id_foreign` (`petugas_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto`
--
ALTER TABLE `foto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `galery`
--
ALTER TABLE `galery`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_galery_id_foreign` FOREIGN KEY (`galery_id`) REFERENCES `galery` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galery`
--
ALTER TABLE `galery`
  ADD CONSTRAINT `galery_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
