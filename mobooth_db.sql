-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: mobooth_db
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `donation_transactions`
--

DROP TABLE IF EXISTS `donation_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `donation_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) NOT NULL,
  `donator_name` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation_transactions`
--

LOCK TABLES `donation_transactions` WRITE;
/*!40000 ALTER TABLE `donation_transactions` DISABLE KEYS */;
INSERT INTO `donation_transactions` VALUES (1,'Saweria','WibuGanteng',100000.00,'Gemas banget templatenya!','2026-06-16 13:30:52');
/*!40000 ALTER TABLE `donation_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Booth Utama - Selfie Room','Lobby Lantai 1','Active','Menggunakan kamera DSLR Canon 1500D dan ring light.','2026-06-16 12:41:42'),(2,'Booth Outdoor - Green Screen','Taman Belakang','Maintenance','Perlu kalibrasi pencahayaan outdoor.','2026-06-16 12:41:42');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slots` int NOT NULL DEFAULT '0',
  `width` int NOT NULL DEFAULT '200',
  `height` int NOT NULL DEFAULT '600',
  `status` enum('Active','Draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frame_coordinates` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (15,'clasic','Strip (Vertical)',4,200,600,'Active','','','[{\"id\":1,\"x\":0,\"y\":0,\"width\":200,\"height\":153,\"shape\":\"square\",\"layer\":\"front\"},{\"id\":2,\"x\":0,\"y\":146,\"width\":200,\"height\":153,\"shape\":\"square\",\"layer\":\"front\"},{\"id\":3,\"x\":0,\"y\":296,\"width\":200,\"height\":153,\"shape\":\"square\",\"layer\":\"front\"},{\"id\":4,\"x\":0,\"y\":447,\"width\":200,\"height\":153,\"shape\":\"square\",\"layer\":\"front\"}]','2026-06-16 20:13:44','2026-06-19 15:19:00'),(16,'cute','Strip (Vertical)',3,200,600,'Active','','/mobooth/assets/uploads/1cd1f421ce96eb995e87d81a.png','[{\"id\":1,\"x\":7,\"y\":12,\"width\":183,\"height\":167,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":2,\"x\":3,\"y\":186,\"width\":183,\"height\":167,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":3,\"x\":0,\"y\":359,\"width\":183,\"height\":167,\"shape\":\"square\",\"layer\":\"back\"}]','2026-06-17 07:47:49','2026-06-19 15:17:48'),(17,'ungu','Strip (Vertical)',3,200,600,'Active','','/mobooth/assets/uploads/798e9eeb86c3c5201441d120.png','[{\"id\":1,\"x\":12,\"y\":15,\"width\":171,\"height\":168,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":2,\"x\":13,\"y\":190,\"width\":171,\"height\":168,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":3,\"x\":13,\"y\":371,\"width\":171,\"height\":168,\"shape\":\"square\",\"layer\":\"back\"}]','2026-06-17 08:00:05','2026-06-17 08:01:00'),(18,'pink clasic','4R Grid',6,400,600,'Active','','/mobooth/assets/uploads/4c26d8cab07303ae11bc94a3.png','[{\"id\":1,\"x\":6,\"y\":21,\"width\":186,\"height\":156,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":2,\"x\":203,\"y\":16,\"width\":186,\"height\":156,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":3,\"x\":5,\"y\":186,\"width\":186,\"height\":156,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":4,\"x\":200,\"y\":192,\"width\":186,\"height\":156,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":5,\"x\":6,\"y\":354,\"width\":186,\"height\":156,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":6,\"x\":202,\"y\":360,\"width\":186,\"height\":156,\"shape\":\"square\",\"layer\":\"back\"}]','2026-06-19 11:30:37','2026-06-19 11:32:15'),(19,'kuning clasic','4R Grid',6,400,600,'Active','','/mobooth/assets/uploads/f20baf63abca8ff3bd13a7f0.png','[{\"id\":1,\"x\":5,\"y\":8,\"width\":187,\"height\":161,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":2,\"x\":202,\"y\":9,\"width\":187,\"height\":161,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":3,\"x\":0,\"y\":177,\"width\":187,\"height\":161,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":4,\"x\":197,\"y\":178,\"width\":187,\"height\":161,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":5,\"x\":5,\"y\":335,\"width\":187,\"height\":161,\"shape\":\"square\",\"layer\":\"back\"},{\"id\":6,\"x\":202,\"y\":338,\"width\":187,\"height\":161,\"shape\":\"square\",\"layer\":\"back\"}]','2026-06-19 15:15:08','2026-06-19 15:17:01');
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$12$jYmBgBEk6dpBlf.PAuEBWuWexxO4q.mD18oNf/Q7A4MuU4rbCoPUK','Administrator','Super Admin','2026-06-16 11:51:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Guest',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
INSERT INTO `visitors` VALUES (3,'yuyuyy','yuyuyy@gmail.com','Google','2026-06-16 18:29:23'),(4,'Riska Destiana','riskadesti123@gmail.com','Google','2026-06-16 19:09:23');
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-20 15:10:47
