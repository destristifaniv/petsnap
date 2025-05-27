-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for petsnap
CREATE DATABASE IF NOT EXISTS `petsnap` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `petsnap`;

-- Dumping structure for table petsnap.akuns
CREATE TABLE IF NOT EXISTS `akuns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('pemilik','dokter') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `akuns_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.akuns: ~3 rows (approximately)
DELETE FROM `akuns`;
INSERT INTO `akuns` (`id`, `nama`, `email`, `password`, `foto`, `role`, `created_at`, `updated_at`) VALUES
	(1, 'ci', 'ci@gmail.com', '$2y$12$m5/eOf4NGO7fb72R1nkxa.6O.0dguUixyfyu1KuuxBuTIhvb5SfLC', NULL, 'pemilik', '2025-04-29 19:33:01', '2025-05-06 15:08:29'),
	(2, 'han', 'han@gmail.com', '$2y$12$.O0TEzU2Xk1zByRSxLVVxelluS64rNH4Wh84LgojAkO9/bPzxjina', 'akuns/01JTKTXDBNX5Z2HFQJZ6NBKEAF.png', 'dokter', '2025-04-29 20:03:47', '2025-05-06 15:19:27'),
	(4, 'fani', 'fani@gmail.com', '$2y$12$tY4XuEkfwyfNcril2qvVkOzqyXaQxTjMKv57giwWCPjC6NDu59EGe', NULL, 'pemilik', '2025-05-05 13:25:28', '2025-05-05 13:25:28');

-- Dumping structure for table petsnap.diagnosas
CREATE TABLE IF NOT EXISTS `diagnosas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hewan_id` bigint unsigned NOT NULL,
  `dokter_id` bigint unsigned NOT NULL,
  `tanggal_diagnosa` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diagnosas_hewan_id_foreign` (`hewan_id`),
  KEY `diagnosas_dokter_id_foreign` (`dokter_id`),
  CONSTRAINT `diagnosas_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `diagnosas_hewan_id_foreign` FOREIGN KEY (`hewan_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.diagnosas: ~2 rows (approximately)
DELETE FROM `diagnosas`;
INSERT INTO `diagnosas` (`id`, `hewan_id`, `dokter_id`, `tanggal_diagnosa`, `catatan`, `created_at`, `updated_at`) VALUES
	(2, 5, 2, '2025-05-05', 'sakit panas', '2025-05-05 05:28:28', '2025-05-05 05:28:28'),
	(3, 6, 2, '2025-05-06', 'bulu rontok karena salah sampoo', '2025-05-05 13:30:34', '2025-05-05 13:30:34');

-- Dumping structure for table petsnap.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table petsnap.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.migrations: ~13 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_04_30_014700_create_akuns_table', 2),
	(6, '2025_04_30_020129_create_akuns_table', 3),
	(7, '2025_04_30_023748_create_pets_table', 4),
	(8, '2025_04_30_041952_create_diagnosas_table', 5),
	(9, '2025_04_30_042934_create_obats_table', 6),
	(10, '2025_05_05_073710_add_foto_to_pets_table', 7),
	(11, '2025_05_05_125225_add_foto_to_pets_table', 8),
	(12, '2025_05_06_212023_add_warna_to_pets_table', 9),
	(13, '2025_05_06_221406_add_foto_to_akuns_table', 10);

-- Dumping structure for table petsnap.obats
CREATE TABLE IF NOT EXISTS `obats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `diagnosa_id` bigint unsigned NOT NULL,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `obats_diagnosa_id_foreign` (`diagnosa_id`),
  CONSTRAINT `obats_diagnosa_id_foreign` FOREIGN KEY (`diagnosa_id`) REFERENCES `diagnosas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.obats: ~2 rows (approximately)
DELETE FROM `obats`;
INSERT INTO `obats` (`id`, `diagnosa_id`, `nama_obat`, `dosis`, `catatan`, `created_at`, `updated_at`) VALUES
	(2, 2, 'Meloxikam', '1,5mg', 'berikan 1,5mg setelah makan', '2025-05-05 05:28:47', '2025-05-05 21:07:23'),
	(3, 3, 'Fish O Plus', '1 kapsul', '1 kapsul / kg berat badan. \nbb leo 4kg perlu 4 butir perhari.', '2025-05-05 13:36:41', '2025-05-05 13:36:41');

-- Dumping structure for table petsnap.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table petsnap.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table petsnap.pets
CREATE TABLE IF NOT EXISTS `pets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usia` int NOT NULL,
  `kondisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemilik_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pets_pemilik_id_foreign` (`pemilik_id`),
  CONSTRAINT `pets_pemilik_id_foreign` FOREIGN KEY (`pemilik_id`) REFERENCES `akuns` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.pets: ~2 rows (approximately)
DELETE FROM `pets`;
INSERT INTO `pets` (`id`, `nama`, `jenis`, `warna`, `usia`, `kondisi`, `pemilik_id`, `created_at`, `updated_at`, `foto`) VALUES
	(5, 'ruby', 'kucing persia', 'putih', 1, 'sakit', 1, '2025-05-05 01:37:30', '2025-05-06 14:44:40', 'pets/01JTG849T0DR35TF92HKHGTZ6N.jpg'),
	(6, 'leo', 'ragdoll', 'mited', 6, 'perlu perawatan', 4, '2025-05-05 13:29:34', '2025-05-06 14:47:47', 'pets/01JTH27G9C39XB18JYA3BRZVS2.jpg');

-- Dumping structure for table petsnap.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table petsnap.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Adminklinik', 'adminklinik@gmail.com', NULL, '$2y$12$OhIYJDnZ1JQIKdeQOs6LvOOpiWVeqcKClDLA8hF1cZL2UWAjcZBhS', NULL, '2025-04-29 18:40:58', '2025-04-29 18:40:58'),
	(2, 'ci', 'ci@gmail.com', NULL, '$2y$12$124y/N9WL5Iq/AXEqB7IHO94IR7P.8d3DI03gFI.2RUViqnUknDEG', NULL, '2025-05-06 21:41:37', '2025-05-11 20:26:40');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
