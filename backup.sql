Enter password: 
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.2.2-MariaDB, for Android (aarch64)
--
-- Host: localhost    Database: perpus_digital
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `anggota`
--

DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota` (
  `id_anggota` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `nim_nis` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_anggota`),
  UNIQUE KEY `id_user` (`id_user`),
  CONSTRAINT `anggota_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `anggota` WRITE;
/*!40000 ALTER TABLE `anggota` DISABLE KEYS */;
INSERT INTO `anggota` VALUES
(1,2,'','','',NULL,'2026-04-14 11:36:06'),
(2,3,'','','',NULL,'2026-04-14 11:46:10');
/*!40000 ALTER TABLE `anggota` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku` (
  `id_buku` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) unsigned DEFAULT NULL,
  `judul_buku` varchar(200) NOT NULL,
  `pengarang` varchar(150) NOT NULL,
  `penerbit` varchar(150) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL DEFAULT 'no-cover.png',
  `stok` int(11) NOT NULL DEFAULT 1,
  `dipinjam` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_buku`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `buku_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `buku_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `buku` WRITE;
/*!40000 ALTER TABLE `buku` DISABLE KEYS */;
INSERT INTO `buku` VALUES
(1,1,'KITAB MASAKAN','Radit','Gramedia',2026,NULL,'Buku Panduan Memasak','1776166964_8d0b771875c2ac32f345.jpg',10,1,'2026-04-14 11:42:44','2026-04-14 11:52:37'),
(2,2,'Belajar Pemrograman Web untuk Pemula','Riel','Gramedia',2026,NULL,'Belajar Coding','1776167102_7768a3728bc7f1ab6251.jpg',10,1,'2026-04-14 11:45:02','2026-04-14 11:52:11');
/*!40000 ALTER TABLE `buku` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `id_kategori` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES
(1,'Panduan Praktis','Step by step dalam melakukan suatu hal','2026-04-14 11:39:18'),
(2,'Pemrograman','Step By Step Membuat Program','2026-04-14 11:43:04');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2026-04-10-135123','App\\Database\\Migrations\\CreateUsersTable','default','App',1776166446,1),
(2,'2026-04-10-135148','App\\Database\\Migrations\\CreateAnggotaTable','default','App',1776166446,1),
(3,'2026-04-10-135200','App\\Database\\Migrations\\CreateKategoriTable','default','App',1776166446,1),
(4,'2026-04-10-135212','App\\Database\\Migrations\\CreateBukuTable','default','App',1776166446,1),
(5,'2026-04-10-135227','App\\Database\\Migrations\\CreatePeminjamanTable','default','App',1776166446,1),
(6,'2026-04-10-135242','App\\Database\\Migrations\\CreateNotifikasiTable','default','App',1776166446,1),
(7,'2026-04-10-135255','App\\Database\\Migrations\\CreateUlasanTable','default','App',1776166446,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pesan` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_notifikasi`),
  KEY `notifikasi_id_user_foreign` (`id_user`),
  CONSTRAINT `notifikasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES
(1,1,'Pengajuan Peminjaman Baru','Radit mengajukan peminjaman buku \"Belajar Pemrograman Web untuk Pemula\".','/admin/loans',0,'2026-04-14 11:47:16'),
(2,1,'Pengajuan Peminjaman Baru','Radit mengajukan peminjaman buku \"KITAB MASAKAN\".','/admin/loans',0,'2026-04-14 11:47:25'),
(3,1,'Pengajuan Peminjaman Baru','Riel Thomas mengajukan peminjaman buku \"KITAB MASAKAN\".','/admin/loans',0,'2026-04-14 11:49:07'),
(4,1,'Pengajuan Peminjaman Baru','Riel Thomas mengajukan peminjaman buku \"Belajar Pemrograman Web untuk Pemula\".','/admin/loans',0,'2026-04-14 11:49:37'),
(5,2,'Peminjaman Disetujui','Peminjaman buku \"Belajar Pemrograman Web untuk Pemula\" telah disetujui. Silakan ambil buku.','/user/loans',0,'2026-04-14 11:52:11'),
(6,2,'Peminjaman Ditolak','Peminjaman buku \"KITAB MASAKAN\" ditolak. Alasan: Malas','/user/loans',0,'2026-04-14 11:52:29'),
(7,3,'Peminjaman Disetujui','Peminjaman buku \"KITAB MASAKAN\" telah disetujui. Silakan ambil buku.','/user/loans',0,'2026-04-14 11:52:37'),
(8,3,'Peminjaman Ditolak','Peminjaman buku \"Belajar Pemrograman Web untuk Pemula\" ditolak. Alasan: Malas','/user/loans',0,'2026-04-14 11:52:42');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_anggota` int(11) unsigned NOT NULL,
  `id_buku` int(11) unsigned NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali_rencana` date NOT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dikembalikan') NOT NULL DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL,
  `diproses_oleh` int(11) unsigned DEFAULT NULL,
  `tanggal_proses` datetime DEFAULT NULL,
  `denda` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_peminjaman`),
  KEY `peminjaman_id_anggota_foreign` (`id_anggota`),
  KEY `peminjaman_id_buku_foreign` (`id_buku`),
  KEY `peminjaman_diproses_oleh_foreign` (`diproses_oleh`),
  CONSTRAINT `peminjaman_diproses_oleh_foreign` FOREIGN KEY (`diproses_oleh`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `peminjaman_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `peminjaman_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES
(1,2,2,'2026-04-14','2026-04-15',NULL,'ditolak','Malas',1,'2026-04-14 11:52:42',0.00,'2026-04-14 11:47:16'),
(2,2,1,'2026-04-14','2026-04-15',NULL,'disetujui',NULL,1,'2026-04-14 11:52:37',0.00,'2026-04-14 11:47:25'),
(3,1,1,'2026-04-14','2026-04-15',NULL,'ditolak','Malas',1,'2026-04-14 11:52:29',0.00,'2026-04-14 11:49:07'),
(4,1,2,'2026-04-14','2026-04-15',NULL,'disetujui',NULL,1,'2026-04-14 11:52:11',0.00,'2026-04-14 11:49:37');
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ulasan`
--

DROP TABLE IF EXISTS `ulasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ulasan` (
  `id_ulasan` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) unsigned NOT NULL,
  `id_user` int(11) unsigned NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `komentar` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ulasan`),
  UNIQUE KEY `id_buku_id_user` (`id_buku`,`id_user`),
  KEY `ulasan_id_user_foreign` (`id_user`),
  CONSTRAINT `ulasan_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ulasan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulasan`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ulasan` WRITE;
/*!40000 ALTER TABLE `ulasan` DISABLE KEYS */;
INSERT INTO `ulasan` VALUES
(1,1,3,5,'Bagusas','2026-04-14 11:46:44'),
(2,2,3,1,'JELEKKKK','2026-04-14 11:46:58'),
(3,1,2,1,'Jelek','2026-04-14 11:48:36'),
(4,2,2,5,'Bagus','2026-04-14 11:49:25');
/*!40000 ALTER TABLE `ulasan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) NOT NULL DEFAULT 'default.png',
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=aktif, 0=nonaktif',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'admin','admin@perpus.id','Administrator','$2y$12$NLX//KTE5Mi/UpcPAnxivOP3vnSEAT8zBiHmRif0lo6qKxHCh4Bt6','1776167614_2eaeacff07f6fb291635.png','admin',1,'2026-04-14 11:51:58',NULL,'2026-04-14 11:53:34'),
(2,'Riel','rielthomas0710@gmail.com','Riel Thomas','$2y$12$xMVJJ6MUoqnO43QtbjvH3u5ba3XQxhA6ROfi/WviOeLP9l9I.KwFW','1776167327_922205f1bc5d143444de.jpg','user',1,'2026-04-14 11:48:28','2026-04-14 11:36:06','2026-04-14 11:48:47'),
(3,'Ditt','dit@gmail.com','Radit','$2y$12$1jfeS6asNjHgLX/AYQRouuqDG8rl6vI06QSDzZoZIbxKCHhkRvzEi','1776167295_c7df7d5ce689ceb17262.png','user',1,'2026-04-14 11:46:27','2026-04-14 11:46:10','2026-04-14 11:48:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-04-14 19:55:26
