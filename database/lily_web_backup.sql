-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: lily_web
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `lily_web`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `lily_web` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `lily_web`;

--
-- Table structure for table `lilyweb_audit_logs`
--

DROP TABLE IF EXISTS `lilyweb_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` varchar(100) DEFAULT NULL,
  `details_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_json`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_action_created` (`action`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_audit_logs`
--

LOCK TABLES `lilyweb_audit_logs` WRITE;
/*!40000 ALTER TABLE `lilyweb_audit_logs` DISABLE KEYS */;
INSERT INTO `lilyweb_audit_logs` VALUES (1,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:38:50'),(2,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:41:24'),(3,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:43:18'),(4,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:45:13'),(5,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:47:07'),(6,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:49:03'),(7,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:50:18'),(8,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:51:58'),(9,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:53:43'),(10,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:54:57'),(11,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:55:49'),(12,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:57:08'),(13,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:57:49'),(14,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:58:57'),(15,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 18:59:47'),(16,'admin','create_backup','database','lilyweb_db_backup_2026-08-31_185947.sql',NULL,'127.0.0.1','2026-08-31 18:59:47'),(17,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:00:37'),(18,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:00:47'),(19,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:01:02'),(20,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:04:10'),(21,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:07:34'),(22,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:20:16'),(23,'admin','delete','media_asset','26',NULL,'127.0.0.1','2026-08-31 19:24:45'),(24,'admin','delete','media_asset','31',NULL,'127.0.0.1','2026-08-31 19:24:48'),(25,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:57:35'),(26,'admin','login','user','1',NULL,'127.0.0.1','2026-08-31 19:57:51'),(27,'admin','login','user','1',NULL,'::1','2026-08-31 20:19:56'),(28,'admin','login','user','1',NULL,'::1','2026-08-31 20:20:23'),(29,'admin','update','service','1',NULL,'127.0.0.1','2026-08-31 20:37:05'),(30,'admin','login','user','1',NULL,'::1','2026-08-31 20:44:13'),(31,'admin','login','user','1',NULL,'::1','2026-08-31 20:57:37'),(32,'admin','create','faq','6',NULL,'::1','2026-08-31 20:57:37'),(33,'admin','update','faq','6',NULL,'::1','2026-08-31 20:57:37'),(34,'admin','delete','faq','6',NULL,'::1','2026-08-31 20:57:37'),(35,'admin','login','user','1',NULL,'::1','2026-08-31 20:58:58'),(36,'admin','login','user','1',NULL,'::1','2026-08-31 20:59:21'),(37,'admin','upload','media_asset','33',NULL,'::1','2026-08-31 20:59:21'),(38,'admin','update','media_asset','33',NULL,'::1','2026-08-31 20:59:21'),(39,'admin','delete','media_asset','33',NULL,'::1','2026-08-31 20:59:21'),(40,'admin','login','user','1',NULL,'::1','2026-08-31 21:00:07'),(41,'admin','update','settings','general_settings',NULL,'::1','2026-08-31 21:00:07'),(42,'admin','update','settings','general_settings',NULL,'::1','2026-08-31 21:00:08'),(43,'admin','update','seo_settings','seo_engine',NULL,'::1','2026-08-31 21:00:08'),(44,'admin','update','seo_settings','seo_engine',NULL,'::1','2026-08-31 21:00:08'),(45,'admin','login','user','1',NULL,'::1','2026-08-31 21:02:27'),(46,'admin','restore_database','database','lilyweb_db_backup_2026-08-31_210228.sql',NULL,'::1','2026-08-31 21:02:28'),(47,'admin','delete_backup','backup','4',NULL,'::1','2026-08-31 21:02:28'),(48,'admin','login','user','1',NULL,'::1','2026-08-31 21:03:50'),(49,'admin','create_backup','database','lilyweb_db_backup_2026-08-31_210350.sql',NULL,'::1','2026-08-31 21:03:50'),(50,'admin','delete_backup','backup','lilyweb_db_backup_2026-08-31_210350.sql',NULL,'::1','2026-08-31 21:03:50'),(51,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 13:26:23'),(52,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 13:30:50'),(53,'admin','delete_backup','backup','lilyweb_full_2026-08-31_203315.tar.gz',NULL,'127.0.0.1','2026-09-02 13:48:39'),(54,'admin','delete_backup','backup','lilyweb_db_backup_2026-08-31_185947.sql',NULL,'127.0.0.1','2026-09-02 13:48:41'),(55,'admin','delete_backup','backup','lilyweb_full_2026-08-31_203440.tar.gz',NULL,'127.0.0.1','2026-09-02 13:48:43'),(56,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 13:49:32'),(57,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:13:59'),(58,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:14:34'),(59,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:21:48'),(60,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:26:46'),(61,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:27:50'),(62,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:33:43'),(63,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:42:04'),(64,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 14:57:45'),(65,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:03:15'),(66,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:05:07'),(67,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:06:57'),(68,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:13:56'),(69,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:24:38'),(70,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:25:46'),(71,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:27:37'),(72,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:32:51'),(73,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:39:54'),(74,'admin','logout','user','1',NULL,'127.0.0.1','2026-09-02 15:45:16'),(75,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:45:18'),(76,'admin','logout','user','1',NULL,'127.0.0.1','2026-09-02 15:45:20'),(77,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:45:34'),(78,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 15:46:27'),(79,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 16:14:20'),(80,'admin','login','user','1',NULL,'127.0.0.1','2026-09-02 16:21:47'),(81,'admin','delete','contact_lead','1',NULL,'127.0.0.1','2026-09-02 16:52:09');
/*!40000 ALTER TABLE `lilyweb_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_backup_records`
--

DROP TABLE IF EXISTS `lilyweb_backup_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_backup_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `storage_path` varchar(255) NOT NULL,
  `size_bytes` bigint(20) unsigned NOT NULL DEFAULT 0,
  `checksum_sha256` char(64) DEFAULT NULL,
  `created_by_user` varchar(60) NOT NULL DEFAULT 'Owner',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_backup_records`
--

LOCK TABLES `lilyweb_backup_records` WRITE;
/*!40000 ALTER TABLE `lilyweb_backup_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `lilyweb_backup_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_contact_submissions`
--

DROP TABLE IF EXISTS `lilyweb_contact_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_contact_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email_address` varchar(190) DEFAULT NULL,
  `service_slug` varchar(100) DEFAULT NULL,
  `project_location` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','contacted','in_progress','closed','archived') NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status_created` (`status`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_contact_submissions`
--

LOCK TABLES `lilyweb_contact_submissions` WRITE;
/*!40000 ALTER TABLE `lilyweb_contact_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `lilyweb_contact_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_faqs`
--

DROP TABLE IF EXISTS `lilyweb_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` enum('general','process','pricing','warranty','turnkey') NOT NULL DEFAULT 'general',
  `question_en` text NOT NULL,
  `question_bn` text DEFAULT NULL,
  `answer_en` longtext NOT NULL,
  `answer_bn` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cat_active` (`category`,`is_active`,`sort_order`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_faqs`
--

LOCK TABLES `lilyweb_faqs` WRITE;
/*!40000 ALTER TABLE `lilyweb_faqs` DISABLE KEYS */;
INSERT INTO `lilyweb_faqs` VALUES (1,'process','How long does a typical interior design project take?','লিলি ইন্টেরিয়র্সের প্রজেক্ট বাস্তবায়নে কেমন সময় লাগে?','Project timelines depend on the square footage and custom scope. Typically, a standard residential apartment takes between 45 to 75 working days from design approval to final handover.','প্রজেক্টের পরিধি এবং সাইজের উপর নির্ভর করে সময় নির্ধারিত হয়। সাধারণত একটি স্ট্যান্ডার্ড অ্যাপার্টমেন্ট ইন্টেরিয়র সম্পন্ন করতে ৪৫ থেকে ৭৫ কার্যদিবস সময় লাগে।',1,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(2,'turnkey','Do you provide end-to-end Turnkey solutions?','আপনারা কি সম্পূর্ণ টার্নকি (Turnkey) সল্যুশন প্রদান করেন?','Yes, we provide full turnkey interior solutions covering 3D spatial design, civil alterations, custom woodwork, lighting installation, procurement, and handover.','হ্যাঁ, আমরা প্রাথমিক থ্রিডি ডিজাইন থেকে শুরু করে সিভিল নির্মাণ, কাস্টম ফার্নিচার তৈরি, লাইটিং এবং শতভাগ প্রস্তুত করে চাবি হস্তান্তর পর্যন্ত সম্পূর্ণ টার্নকি দায়িত্ব পালন করি।',2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(3,'general','Is the initial consultation free of charge?','ডিজাইন কনসালটেশন কি সম্পূর্ণ ফ্রি?','Yes, the initial consultation at our Banani/Dhanmondi studio or via virtual meeting is complimentary. Our senior architects will assess your requirements and discuss layout possibilities.','হ্যাঁ, আমাদের প্রাথমিক অন-সাইট বা ইন-স্টুডিও পরামর্শ সম্পূর্ণ ফ্রি। আমাদের অভিজ্ঞ আর্কিটেক্ট আপনার চাহিদা পর্যালোচনা করে প্রাথমিক বাজেট এবং লেআউট আইডিয়া প্রদান করবেন।',3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(4,'general','Do you execute projects outside of Dhaka?','আপনারা কি ঢাকার বাইরে কাজ করেন?','We primarily operate across Dhaka (Gulshan, Banani, Dhanmondi, Uttara, Bashundhara, Mirpur). However, we undertake select premium projects in Chittagong, Sylhet, and other major cities upon request.','আমরা প্রধানত ঢাকা মেট্রোপলিটনে কাজ করি, তবে গ্রাহকের বিশেষ অনুরোধে চট্টগ্রাম, সিলেট ও নারায়ণগঞ্জসহ দেশের প্রধান শহরগুলোতেও নির্বাচিত প্রজেক্ট পরিচালনা করে থাকি।',4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(5,'warranty','Do you offer post-handover warranty and maintenance?','প্রজেক্ট শেষ হওয়ার পর কি কোনো আফটার-সার্ভিস ওয়ারেন্টি আছে?','Absolutely. We provide up to a 10-year warranty on Blum and premium cabinetry hardware, along with 1-year complimentary post-handover maintenance support.','অবশ্যই। আমরা আমাদের কাঠ ও ক্যাবিনেট্রি হার্ডওয়্যারে সর্বোচ্চ ১০ বছরের ওয়ারেন্টি এবং হ্যান্ডওভার পরবর্তী ১ বছর পর্যন্ত ফ্রি মেইনটেন্যান্স সাপোর্ট প্রদান করি।',5,1,'2026-08-31 18:26:58','2026-08-31 18:26:58');
/*!40000 ALTER TABLE `lilyweb_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_hero_slides`
--

DROP TABLE IF EXISTS `lilyweb_hero_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_hero_slides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `step_number` varchar(10) NOT NULL DEFAULT '01',
  `kicker_en` varchar(100) NOT NULL,
  `kicker_bn` varchar(100) DEFAULT NULL,
  `title_prefix_en` varchar(150) NOT NULL,
  `title_prefix_bn` varchar(150) DEFAULT NULL,
  `title_highlight_en` varchar(100) NOT NULL,
  `title_highlight_bn` varchar(100) DEFAULT NULL,
  `title_suffix_en` varchar(150) NOT NULL,
  `title_suffix_bn` varchar(150) DEFAULT NULL,
  `subtitle_en` text NOT NULL,
  `subtitle_bn` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `badge_room_en` varchar(100) NOT NULL,
  `badge_room_bn` varchar(100) DEFAULT NULL,
  `badge_location_en` varchar(100) NOT NULL,
  `badge_location_bn` varchar(100) DEFAULT NULL,
  `cta_text_en` varchar(100) NOT NULL,
  `cta_text_bn` varchar(100) DEFAULT NULL,
  `cta_url` varchar(255) NOT NULL DEFAULT '#portfolio',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_hero_slides`
--

LOCK TABLES `lilyweb_hero_slides` WRITE;
/*!40000 ALTER TABLE `lilyweb_hero_slides` DISABLE KEYS */;
INSERT INTO `lilyweb_hero_slides` VALUES (1,'01','DESIGN • CREATE • INSPIRE','ডিজাইন • ক্রিয়েট • ইন্সপায়ার','We Design Spaces That','আমরা এমন স্পেস ডিজাইন করি যা','Inspire','অনুপ্রাণিত','Life','করে জীবনকে','Timeless interior design solutions that combine functionality, creativity and elegance tailored to your lifestyle.','আপনার জীবনযাত্রার সাথে মানানসই কার্যকারিতা, সৃজনশীলতা এবং আভিজাত্যের সমন্বয়ে গঠিত অনন্য ইন্টেরিয়র ডিজাইন সল্যুশন।','/assets/img/hero-living-room.webp','Modern Living Room','মডার্ন লিভিং রুম','Mirpur, Dhaka','মিরপুর, ঢাকা','Explore Projects →','প্রজেক্ট দেখুন →','#portfolio',1,1,'2026-08-31 18:26:58','2026-08-31 19:32:24'),(2,'02','LUXURY • CRAFTSMANSHIP • ELEGANCE','লাক্সারি • ক্রাফটসম্যানশিপ • আভিজাত্য','Crafting Bespoke Interiors with','অনন্য শৈলী ও নিখুঁত','Precision','পরিকল্পনায়','& Passion','বাস্তবায়ন','Transforming high-end residences in Gulshan and Banani into breathtaking architectural masterworks.','গুলশান ও বনানীর আধুনিক অ্যাপার্টমেন্টগুলোকে নান্দনিক শৈলী ও বিশ্বমানের উপাদানে রূপান্তর।','/assets/img/hero-slide-2.webp','Executive Master Suite','মাস্টার বেডরুম সুইট','Gulshan 2, Dhaka','গুলশান ২, ঢাকা','View Portfolio →','পোর্টফোলিও দেখুন →','#portfolio',2,1,'2026-08-31 18:26:58','2026-08-31 19:32:24'),(3,'03','INNOVATION • HARMONY • SPACE','উদ্ভাবন • নান্দনিকতা • স্পেস','Seamless Harmony of Space &','কার্যকারিতা ও আধুনিকতার','Function','নিখুঁত','Excellence','সমন্বয়','Ergonomic, sustainable, and smart spatial planning engineered for modern corporate headquarters.','আধুনিক কর্পোরেট অফিস ও আধুনিক কর্মক্ষেত্রের জন্য স্মার্ট স্পেস প্ল্যানিং ও শব্দহীন শান্ত পরিবেশ।','/assets/img/hero-slide-3.webp','Contemporary Dining Lounge','মডার্ন ডাইনিং লাউঞ্জ','Dhanmondi, Dhaka','ধানমন্ডি, ঢাকা','Our Services →','সার্ভিসসমূহ দেখুন →','#services',3,1,'2026-08-31 18:26:58','2026-08-31 19:32:24'),(4,'04','ARCHITECTURE • LUXURY • VISION','আর্কিটেকচার • লাক্সারি • ভিশন','Executive Duplexes &','রাজকীয় ডুপ্লেক্স ও','Luxury','লাক্সারি','Penthouses','পেন্টহাউস','Turnkey architectural transformations creating extraordinary living environments across Dhaka.','ঢাকা জুড়ে প্রিমিয়াম টার্নকি আর্কিটেকচারাল ইন্টেরিয়র রূপান্তর।','/assets/img/hero-slide-4.webp','Penthouse Lounge','পেন্টহাউস লাউঞ্জ','Baridhara, Dhaka','বারিধারা, ঢাকা','Discover Duplexes →','ডুপ্লেক্স প্রজেক্ট দেখুন →','#portfolio',4,1,'2026-08-31 18:26:58','2026-08-31 19:32:24'),(5,'05','INSPIRATION • DETAIL • HARMONY','অনুপ্রেরণা • নিখুঁত ডিটেইল • আভিজাত্য','Impeccable Finishing with','সেরা উপাদানে গঠিত','German','জার্মান','Hardware','হার্ডওয়্যার','Exclusive Blum fittings, sustainable oak cabinetry, and customized ambient illumination systems.','জার্মান ব্লুম ফিটিংস, টেকসই ওক ক্যাবিনেট্রি এবং কাস্টম অ্যাম্বিয়েন্ট লাইটিং সিস্টেম।','/assets/img/hero-slide-5.webp','Modular Gourmet Kitchen','মডিউলার কিচেন','Uttara Sector 4, Dhaka','উত্তরা সেক্টর ৪, ঢাকা','Book Consultation →','কনসালটেশন বুক করুন →','#contact',5,1,'2026-08-31 18:26:58','2026-08-31 19:32:24');
/*!40000 ALTER TABLE `lilyweb_hero_slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_media_assets`
--

DROP TABLE IF EXISTS `lilyweb_media_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_media_assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `original_name` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `storage_path` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `size_bytes` bigint(20) unsigned NOT NULL DEFAULT 0,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `alt_en` varchar(255) DEFAULT NULL,
  `alt_bn` varchar(255) DEFAULT NULL,
  `hash_sha256` char(64) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `storage_path` (`storage_path`),
  KEY `idx_mime` (`mime_type`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_media_assets`
--

LOCK TABLES `lilyweb_media_assets` WRITE;
/*!40000 ALTER TABLE `lilyweb_media_assets` DISABLE KEYS */;
INSERT INTO `lilyweb_media_assets` VALUES (1,'about-1.jpg','about-1.jpg','/assets/img/about-1.jpg','image/jpeg',68308,800,450,'About 1 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(2,'about-2.jpg','about-2.jpg','/assets/img/about-2.jpg','image/jpeg',57642,800,534,'About 2 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(3,'abstract-wave-dark.jpg','abstract-wave-dark.jpg','/assets/img/abstract-wave-dark.jpg','image/jpeg',575626,1376,768,'Abstract Wave Dark by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(4,'abstract-wave-light.jpg','abstract-wave-light.jpg','/assets/img/abstract-wave-light.jpg','image/jpeg',482570,1376,768,'Abstract Wave Light by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(5,'contact-vase.jpg','contact-vase.jpg','/assets/img/contact-vase.jpg','image/jpeg',52392,600,400,'Contact Vase by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(6,'footer-arch.jpg','footer-arch.jpg','/assets/img/footer-arch.jpg','image/jpeg',42406,600,529,'Footer Arch by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(7,'hero-living-room.jpg','hero-living-room.jpg','/assets/img/hero-living-room.jpg','image/jpeg',199916,1200,900,'Hero Living Room by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(8,'hero-slide-2.jpg','hero-slide-2.jpg','/assets/img/hero-slide-2.jpg','image/jpeg',302749,1200,1200,'Hero Slide 2 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(9,'hero-slide-3.jpg','hero-slide-3.jpg','/assets/img/hero-slide-3.jpg','image/jpeg',148073,1200,800,'Hero Slide 3 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(10,'hero-slide-4.jpg','hero-slide-4.jpg','/assets/img/hero-slide-4.jpg','image/jpeg',165443,1200,816,'Hero Slide 4 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(11,'project-1.jpg','project-1.jpg','/assets/img/project-1.jpg','image/jpeg',77916,800,422,'Project 1 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(12,'project-2.jpg','project-2.jpg','/assets/img/project-2.jpg','image/jpeg',132869,800,800,'Project 2 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(13,'project-3.jpg','project-3.jpg','/assets/img/project-3.jpg','image/jpeg',80745,800,533,'Project 3 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(14,'project-4.jpg','project-4.jpg','/assets/img/project-4.jpg','image/jpeg',75708,800,533,'Project 4 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(15,'project-5.jpg','project-5.jpg','/assets/img/project-5.jpg','image/jpeg',244666,800,1422,'Project 5 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(16,'project-6.jpg','project-6.jpg','/assets/img/project-6.jpg','image/jpeg',80480,800,544,'Project 6 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(17,'lily-logo-square.png','lily-logo-square.png','/assets/img/lily-logo-square.png','image/png',50526,1000,1000,'Lily Logo Square by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(18,'lily-logo.png','lily-logo.png','/assets/img/lily-logo.png','image/png',32892,1024,204,'Lily Logo by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(19,'about-1.svg','about-1.svg','/assets/img/about-1.svg','image/svg+xml',1354,NULL,NULL,'About 1 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(20,'about-2.svg','about-2.svg','/assets/img/about-2.svg','image/svg+xml',1034,NULL,NULL,'About 2 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(21,'contact-vase.svg','contact-vase.svg','/assets/img/contact-vase.svg','image/svg+xml',1153,NULL,NULL,'Contact Vase by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(22,'footer-arch.svg','footer-arch.svg','/assets/img/footer-arch.svg','image/svg+xml',1759,NULL,NULL,'Footer Arch by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(23,'founder-signature.svg','founder-signature.svg','/assets/img/founder-signature.svg','image/svg+xml',423,NULL,NULL,'Founder Signature by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(24,'hero-living-room.svg','hero-living-room.svg','/assets/img/hero-living-room.svg','image/svg+xml',4229,NULL,NULL,'Hero Living Room by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(25,'lily-logo-white.svg','lily-logo-white.svg','/assets/img/lily-logo-white.svg','image/svg+xml',1534,NULL,NULL,'Lily Logo White by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(27,'project-1.svg','project-1.svg','/assets/img/project-1.svg','image/svg+xml',865,NULL,NULL,'Project 1 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(28,'project-2.svg','project-2.svg','/assets/img/project-2.svg','image/svg+xml',1126,NULL,NULL,'Project 2 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(29,'project-3.svg','project-3.svg','/assets/img/project-3.svg','image/svg+xml',1123,NULL,NULL,'Project 3 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(30,'project-4.svg','project-4.svg','/assets/img/project-4.svg','image/svg+xml',1014,NULL,NULL,'Project 4 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49'),(32,'project-6.svg','project-6.svg','/assets/img/project-6.svg','image/svg+xml',1046,NULL,NULL,'Project 6 by Lily Interiors',NULL,NULL,'2026-08-31 18:55:49');
/*!40000 ALTER TABLE `lilyweb_media_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_page_views`
--

DROP TABLE IF EXISTS `lilyweb_page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_page_views` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `view_date` date NOT NULL,
  `page_path` varchar(255) NOT NULL,
  `unique_visitors` int(10) unsigned NOT NULL DEFAULT 1,
  `total_pageviews` int(10) unsigned NOT NULL DEFAULT 1,
  `device_mobile_count` int(10) unsigned NOT NULL DEFAULT 0,
  `device_desktop_count` int(10) unsigned NOT NULL DEFAULT 0,
  `referrer_group` varchar(100) NOT NULL DEFAULT 'Direct',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_date_page` (`view_date`,`page_path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_page_views`
--

LOCK TABLES `lilyweb_page_views` WRITE;
/*!40000 ALTER TABLE `lilyweb_page_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `lilyweb_page_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_pages`
--

DROP TABLE IF EXISTS `lilyweb_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) NOT NULL,
  `title_en` varchar(200) NOT NULL,
  `title_bn` varchar(200) DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `content_bn` longtext DEFAULT NULL,
  `meta_desc_en` varchar(300) DEFAULT NULL,
  `meta_desc_bn` varchar(300) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug_active` (`slug`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_pages`
--

LOCK TABLES `lilyweb_pages` WRITE;
/*!40000 ALTER TABLE `lilyweb_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `lilyweb_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_process_steps`
--

DROP TABLE IF EXISTS `lilyweb_process_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_process_steps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `step_number` varchar(10) NOT NULL,
  `title_en` varchar(150) NOT NULL,
  `title_bn` varchar(150) DEFAULT NULL,
  `description_en` text NOT NULL,
  `description_bn` text DEFAULT NULL,
  `icon_svg` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `step_number` (`step_number`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_process_steps`
--

LOCK TABLES `lilyweb_process_steps` WRITE;
/*!40000 ALTER TABLE `lilyweb_process_steps` DISABLE KEYS */;
INSERT INTO `lilyweb_process_steps` VALUES (1,'01','Consultation','কনসালটেশন','Understanding your vision, space requirements, lifestyle and budget expectations.','আপনার চাহিদা, বাজেট ও স্থান নিরীক্ষণ করে প্রাথমিক পরিকল্পনা নির্ধারণ।','<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z\"/></svg>',1,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(2,'02','Design','থ্রিডি ডিজাইন','Developing 3D layouts, realistic renderings, and selecting premium finishes.','বিস্তারিত 3D ভিজ্যুয়ালাইজেশন, স্পেস প্ল্যানিং ও ম্যাটেরিয়াল নির্বাচন।','<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><polygon points=\"12 2 2 7 12 12 22 7 12 2\"/><polyline points=\"2 17 12 22 22 17\"/><polyline points=\"2 12 12 17 22 12\"/></svg>',2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(3,'03','Execution','দক্ষ বাস্তবায়ন','Skilled site craftsmanship, structural precision, and meticulous project oversight.','অভিজ্ঞ সিভিল ইঞ্জিনিয়ার ও দক্ষ কারিগরদের তত্ত্বাবধানে নিখুঁত নির্মাণ।','<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z\"/></svg>',3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(4,'04','Handover','হ্যান্ডওভার','Quality inspection, final defect checks, and on-schedule celebratory handover.','শতভাগ গুণমান যাচাই শেষে নির্দিষ্ট সময়ে স্বপ্নের স্পেস বুঝিয়ে দেওয়া।','<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><polyline points=\"12 6 12 12 14 14\"/></svg>',4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58');
/*!40000 ALTER TABLE `lilyweb_process_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_project_categories`
--

DROP TABLE IF EXISTS `lilyweb_project_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_project_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_type` enum('room_type','property_type') NOT NULL DEFAULT 'room_type',
  `name_en` varchar(100) NOT NULL,
  `name_bn` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_type_active` (`category_type`,`is_active`,`sort_order`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_project_categories`
--

LOCK TABLES `lilyweb_project_categories` WRITE;
/*!40000 ALTER TABLE `lilyweb_project_categories` DISABLE KEYS */;
INSERT INTO `lilyweb_project_categories` VALUES (1,'room_type','Living Room','লিভিং রুম','living_room',1,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(2,'room_type','Bedroom','বেডরুম','bedroom',2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(3,'room_type','Kitchen','কিচেন','kitchen',3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(4,'room_type','Office','অফিস','office',4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(5,'room_type','Others','অন্যান্য','others',5,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(6,'property_type','All Projects','সকল প্রজেক্ট','all',1,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(7,'property_type','Residential','আবাসিক অ্যাপার্টমেন্ট','residential',2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(8,'property_type','Commercial','বাণিজ্যিক ও কর্পোরেট','commercial',3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(9,'property_type','Turnkey Duplex','টার্নকি ডুপ্লেক্স','turnkey-duplex',4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(10,'property_type','Renovation','রিনোভেশন ও রিমডেলিং','renovation',5,1,'2026-08-31 18:26:58','2026-08-31 18:26:58');
/*!40000 ALTER TABLE `lilyweb_project_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_projects`
--

DROP TABLE IF EXISTS `lilyweb_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) NOT NULL,
  `title_en` varchar(200) NOT NULL,
  `title_bn` varchar(200) DEFAULT NULL,
  `summary_en` text NOT NULL,
  `summary_bn` text DEFAULT NULL,
  `description_en` longtext DEFAULT NULL,
  `description_bn` longtext DEFAULT NULL,
  `location_en` varchar(150) NOT NULL,
  `location_bn` varchar(150) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `completion_year` varchar(20) NOT NULL DEFAULT '2025',
  `area_sqft` varchar(50) NOT NULL DEFAULT '2,800 sqft',
  `room_details_en` varchar(100) NOT NULL DEFAULT '4 Bed, 5 Bath, Living, Dining',
  `room_details_bn` varchar(100) DEFAULT NULL,
  `design_style_en` varchar(100) NOT NULL DEFAULT 'Modern Luxury Minimalism',
  `design_style_bn` varchar(100) DEFAULT NULL,
  `project_status` enum('Completed','In Progress','Concept') NOT NULL DEFAULT 'Completed',
  `cover_image` varchar(255) NOT NULL,
  `features_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_json`)),
  `highlights_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`highlights_json`)),
  `materials_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`materials_json`)),
  `gallery_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_json`)),
  `proposal_pdf` varchar(255) DEFAULT NULL,
  `room_type_key` varchar(50) NOT NULL DEFAULT 'living_room',
  `property_type_key` varchar(50) NOT NULL DEFAULT 'residential',
  `show_on_home` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_home_active` (`show_on_home`,`is_active`,`sort_order`),
  KEY `idx_room_key` (`room_type_key`),
  KEY `idx_prop_key` (`property_type_key`),
  KEY `idx_active_home` (`is_active`,`show_on_home`,`is_featured`,`sort_order`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_projects`
--

LOCK TABLES `lilyweb_projects` WRITE;
/*!40000 ALTER TABLE `lilyweb_projects` DISABLE KEYS */;
INSERT INTO `lilyweb_projects` VALUES (1,'modern-luxury-apartment','Modern Luxury Apartment','মডার্ন লাক্সারি অ্যাপার্টমেন্ট','A magnificent 2,800 sqft apartment in Gulshan 2 featuring open-plan living, custom acoustic wall paneling, and imported Italian marble finishes.','গুলশান ২-এ অবস্থিত ২,৮০০ স্কয়ারফিটের এই অ্যাপার্টমেন্টটিতে ওপেন-প্ল্যান লিভিং, কাস্টম অ্যাকোস্টিক ওয়াল প্যানেলিং এবং ইতালীয় মার্বেল ফিনিশিং রয়েছে।','This modern luxury apartment in Gulshan 2 is designed for high-profile residential living. The design emphasizes natural illumination, open-plan architectural flow, and bespoke craftsmanship. Featuring imported Italian Statuario marble floors, custom European Oak wall cladding, integrated smart circadian mood lighting, and concealed storage throughout.','গুলশান ২-এর এই আধুনিক বিলাসবহুল অ্যাপার্টমেন্টটি আভিজাত্য ও স্বাচ্ছন্দ্যের অনন্য মেলবন্ধন। পর্যাপ্ত প্রাকৃতিক আলো, ইতালীয় স্ট্যাচুয়ারিও মার্বেল, ইউরোপীয় ওক কাঠের প্যানেলিং ও স্মার্ট সার্কাডিয়ান লাইটিংয়ের মাধ্যমে পুরো স্পেসে তৈরি হয়েছে এক রাজকীয় পরিবেশ।','Gulshan 2, Dhaka','গুলশান ২, ঢাকা','Mr. & Mrs. Chowdhury','2025','2,800 sqft','4 Bed, 5 Bath, Living, Dining, Family Lounge','৪ বেড, ৫ বাথ, লিভিং, ডাইনিং, ফ্যামিলি লাউঞ্জ','Modern Luxury Minimalism','মডার্ন লাক্সারি মিনিমালিজম','Completed','/assets/img/project-1.webp','[\"Open-plan layout with maximum natural illumination\",\"Custom acoustic fluted wall cladding in European Oak\",\"Integrated smart circadian LED ambient lighting channels\",\"Concealed storage cabinetry with Blum push-to-open hardware\",\"Master ensuite with freestanding soaking tub & rain shower\",\"Imported Italian Statuario marble floor tiles\"]','[{\"title\":\"Custom Joinery & Woodwork\",\"icon\":\"sparkle\"},{\"title\":\"Concealed Ambient Lighting\",\"icon\":\"gem\"},{\"title\":\"Italian Statuario Marble\",\"icon\":\"layout\"},{\"title\":\"Smart Home Automation Integration\",\"icon\":\"award\"}]','[\"European White Oak veneer paneling\",\"Italian Statuario marble and quartz countertops\",\"German Blum motorized soft-close hardware\",\"Architectural Saint-Gobain fluted acoustic glass\",\"Premium low-VOC matte Jotun architectural coatings\"]','[\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/hero-living-room.jpg\",\"\\/assets\\/img\\/hero-slide-2.jpg\",\"\\/assets\\/img\\/hero-slide-3.jpg\",\"\\/assets\\/img\\/hero-slide-4.jpg\",\"\\/assets\\/img\\/hero-slide-5.jpg\",\"\\/assets\\/img\\/project-2.jpg\",\"\\/assets\\/img\\/project-3.jpg\"]',NULL,'living_room','residential',1,1,1,1,'2026-08-31 18:26:58','2026-09-02 16:53:45'),(2,'corporate-office-headquarters','Corporate Office Headquarters','কর্পোরেট অফিস হেডকোয়ার্টার্স','State-of-the-art 4,500 sqft corporate workspace in Banani with acoustic meeting pods, executive boardrooms, and ergonomic workstations.','বনানীতে অবস্থিত ৪,৫০০ স্কয়ারফিটের আধুনিক কর্পোরেট অফিস, যাতে রয়েছে সাউন্ডপ্রুফ মিটিং পড, এক্সিকিউটিভ বোর্ডরুম এবং এরগোনোমিক ওয়ার্কস্টেশন।','Engineered for a premier corporate enterprise in Banani. This 4,500 sqft headquarters blends brand identity with productive spatial zoning. Incorporating double-glazed acoustic glass conference suites, executive director lounges, natural oak sound baffles, and energy-efficient intelligent architectural lighting systems.','বনানীর এই আধুনিক কর্পোরেট হেডকোয়ার্টারে টিম কোলাবোরেশন ও এক্সিকিউটিভ প্রাইভেসি উভয় বিষয়কে প্রাধান্য দিয়ে সাউন্ডপ্রুফ গ্লাস বোর্ডরুম, সিইও ডিরেক্টর স্যুট ও আরামদায়ক এরগোনোমিক ওয়ার্কস্টেশন তৈরি করা হয়েছে।','Banani, Dhaka','বনানী, ঢাকা','Apex Global Holdings Ltd.','2024','4,500 sqft','Executive Floor, Boardroom, 4 Meeting Pods, Open Office','এক্সিকিউটিভ ফ্লোর, বোর্ডরুম, ৪টি মিটিং পড, ওপেন অফিস','Contemporary Corporate Architecture','কনটেম্পোরারি কর্পোরেট আর্কিটেকচার','Completed','/assets/img/project-2.webp','[\"Acoustic double-glazed glass conference boardrooms\",\"Custom executive director desk with concealed cable channels\",\"Ergonomic workstation pods with high-durability task seating\",\"Biophilic indoor air-purifying green planter partition walls\",\"High-CRI anti-glare architectural panel lighting\",\"Executive lounge with refreshment beverage counter\"]','[{\"title\":\"Productive Workspace Zoning\",\"icon\":\"sparkle\"},{\"title\":\"Acoustic Soundproofing dB38\",\"icon\":\"gem\"},{\"title\":\"Biophilic Natural Elements\",\"icon\":\"layout\"},{\"title\":\"On-Time Project Handover\",\"icon\":\"award\"}]','[\"Double-Glazed Aluminum Glass Acoustic Systems\",\"Natural Oak Veneer Desk Architecture\",\"Modular Heavy-Duty Commercial Carpet Tiles\",\"Acoustic Felt Ceiling Baffles\"]','[\"\\/assets\\/img\\/project-2.jpg\",\"\\/assets\\/img\\/project-4.jpg\",\"\\/assets\\/img\\/hero-slide-4.jpg\",\"\\/assets\\/img\\/about-2.jpg\",\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/hero-slide-3.jpg\"]',NULL,'office','commercial',1,1,2,1,'2026-08-31 18:26:58','2026-09-02 16:53:45'),(3,'executive-master-bedroom-suite','Executive Master Bedroom Suite','এক্সিকিউটিভ মাস্টার বেডরুম সুইট','Serene 650 sqft master bedroom suite in Dhanmondi featuring fluted acoustic headboards, concealed walk-in wardrobe, and dimmable cove lighting.','ধানমন্ডিতে অবস্থিত ৬৫০ স্কয়ারফিটের মাস্টার বেডরুম স্যুট, যাতে রয়েছে ফ্লুটেড কাঠের হেডবোর্ড, হিডেন ওয়াক-ইন ওয়ারড্রোব ও শান্ত মুড লাইটিং।','Located in Dhanmondi, this master bedroom suite focuses on uncluttered calm, organic textures, and peaceful harmony. Features custom fluted European oak headboard architecture, concealed walk-in wardrobe with bronze-tinted glass, motorized linen drapery, and warm circadian lighting systems tailored for supreme relaxation.','ধানমন্ডির এই মাস্টার বেডরুমটি মানসিক প্রশান্তি ও বিশ্রামের জন্য নিখুঁতভাবে তৈরি। ফ্লুটেড কাঠের হেডবোর্ড, ব্রোঞ্জ গ্লাসের ওয়াক-ইন ওয়ারড্রোব এবং অটোমেটিক ড্র্যাপারির ব্যবহারে পুরো কক্ষে তৈরি হয়েছে প্রাকৃতিক স্নিগ্ধতা।','Dhanmondi, Dhaka','ধানমন্ডি, ঢাকা','Dr. Tareq & Family','2025','650 sqft','Master Suite, Walk-in Closet, Ensuite Bath, Balcony','মাস্টার স্যুট, ওয়াক-ইন ক্লোজেট, এনসুইট বাথ, ব্যালকনি','Minimalist Luxury Zen','মিনিমালিস্ট লাক্সারি জেন','Completed','/assets/img/project-3.webp','[\"Custom acoustic fluted headboard architectural wall\",\"Concealed walk-in wardrobe with bronze-tinted glass & sensor LEDs\",\"Motorized smart blackout linen drapery systems\",\"Warm indirect architectural cove lighting (2700K)\",\"Floating nightstands with integrated wireless phone charging\",\"Engineered German Oak hardwood flooring\"]','[{\"title\":\"Acoustic Soundproofing\",\"icon\":\"sparkle\"},{\"title\":\"Bespoke Fluted Woodwork\",\"icon\":\"gem\"},{\"title\":\"Smart Circadian Lighting\",\"icon\":\"layout\"},{\"title\":\"100% Quality Execution\",\"icon\":\"award\"}]','[\"Engineered Light Oak Hardwood\",\"Textured Acoustic Fabric Wall Panels\",\"Tinted Tempered Glass Sliding Partitions\",\"Dimmable 2700K Warm LED Systems\"]','[\"\\/assets\\/img\\/project-3.jpg\",\"\\/assets\\/img\\/hero-slide-2.jpg\",\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/about-1.jpg\",\"\\/assets\\/img\\/project-5.jpg\",\"\\/assets\\/img\\/hero-living-room.jpg\"]',NULL,'bedroom','residential',1,1,3,1,'2026-08-31 18:26:58','2026-09-02 16:53:45'),(4,'bespoke-modular-gourmet-kitchen','Bespoke Modular Gourmet Kitchen','মডিউলার গোরমেট কিচেন','High-efficiency 450 sqft contemporary kitchen in Uttara with seamless handleless acrylic shutters, quartz waterfall island, and Blum hardware.','উত্তরায় অবস্থিত ৪৫০ স্কয়ারফিটের আধুনিক মডিউলার কিচেন, যাতে রয়েছে কোয়ার্টজ ওয়াটারফল আইল্যান্ড, হ্যান্ডেললেস ম্যাট শাটার ও জার্মান ব্লুম ফিটিংস।','A high-efficiency contemporary culinary space in Uttara Sector 4. Engineered with Blum soft-close German hardware, anti-fingerprint matte acrylic shutters, stain-proof composite quartz countertops, and built-in modern appliances designed for both everyday gourmet cooking and social family gatherings.','উত্তরার এই মডার্ন কিচেনে ব্যবহার করা হয়েছে জার্মান ব্লুম ফিটিংস, স্ক্র্যাচ-প্রুফ ম্যাট এক্রিলিক এবং দাগ-প্রতিরোধী কোয়ার্টজ টপ, যা রান্নার অভিজ্ঞতাকে করে তোলে সহজ ও আনন্দদায়ক।','Uttara, Dhaka','উত্তরা, ঢাকা','Engr. M. Rahman','2025','450 sqft','Gourmet Kitchen, Breakfast Bar, Walk-in Pantry','গোরমেট কিচেন, ব্রেকফাস্ট বার, ওয়াক-ইন প্যান্ট্রি','Contemporary European Minimalism','কনটেম্পোরারি ইউরোপিয়ান মিনিমালিজম','Completed','/assets/img/project-4.webp','[\"Quartz waterfall breakfast island with undermount sink\",\"Handleless matte anti-fingerprint acrylic shutters\",\"German Blum tandembox pullout organizers & pantry larder\",\"Under-cabinet task & ambient mood illumination\",\"Concealed heavy-duty exhaust ducting with booster motor\",\"Integrated built-in oven and microwave appliance tower\"]','[{\"title\":\"Ergonomic Kitchen Triangle\",\"icon\":\"sparkle\"},{\"title\":\"Scratch & Stain Proof Surfaces\",\"icon\":\"gem\"},{\"title\":\"Maximized Pantry Storage\",\"icon\":\"layout\"},{\"title\":\"10-Year Hardware Warranty\",\"icon\":\"award\"}]','[\"Seamless Composite Calacatta Quartz Slabs\",\"German Blum Tandembox Hardware & Hinges\",\"Marine-Grade Anti-Bacterial Substrate Boards\",\"Stainless Steel 304 Pullout Cutlery Baskets\"]','[\"\\/assets\\/img\\/project-4.jpg\",\"\\/assets\\/img\\/hero-slide-3.jpg\",\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/about-2.jpg\",\"\\/assets\\/img\\/project-3.jpg\"]',NULL,'kitchen','residential',1,1,4,1,'2026-08-31 18:26:58','2026-09-02 16:53:45'),(5,'penthouse-entertainment-lounge','Penthouse Entertainment Lounge','পেন্টহাউস এন্টারটেইনমেন্ট লাউঞ্জ','Spectacular 1,800 sqft penthouse entertainment floor in Baridhara Diplomatic Zone featuring acoustic media wall, cocktail bar, and terrace lounge.','বারিধারা ডিপ্লোম্যাটিক জোনে অবস্থিত ১,৮০০ স্কয়ারফিটের পেন্টহাউস লাউঞ্জ, যাতে রয়েছে অ্যাকোস্টিক সিনেমা ওয়াল, ককটেল বার ও রুফটপ টেরেস।','An ultra-exclusive penthouse lounge in Baridhara Diplomatic Zone designed for executive entertaining and private hospitality. Featuring an acoustic 98-inch 4K theater wall with bronze metal inlays, a solid marble cocktail bar counter, architectural LED backlighting, and seamless outdoor terrace connectivity.','বারিধারার এই পেন্টহাউস লাউঞ্জটি ভিআইপি অতিথি আপ্যায়ন ও রিল্যাক্সেশনের জন্য তৈরি। এতে রয়েছে মার্বেল ককটেল বার, ৯৮ ইঞ্চি হোম থিয়েটার ওয়াল, অ্যাকোস্টিক প্যানেলিং এবং টেরেসের সাথে নান্দনিক সংযোগ।','Baridhara, Dhaka','বারিধারা, ঢাকা','Foreign Diplomat Residence','2024','1,800 sqft','Media Lounge, Cocktail Bar, Cigar Room, Open Terrace','মিডিয়া লাউঞ্জ, ককটেল বার, সিগার রুম, ওপেন টেরেস','Modern Opulent Luxury','মডার্ন অপুলেন্ট লাক্সারি','Completed','/assets/img/project-5.webp','[\"Acoustic 98-inch 4K theater wall with bronze metal inlays\",\"Custom solid marble waterfall cocktail bar counter\",\"Integrated wine chilling cellar and glassware display\",\"Plush custom upholstered curved velvet sectional seating\",\"Architectural dimmable mood lighting scenes with automation\",\"Seamless panoramic sliding glass doors to rooftop terrace\"]','[{\"title\":\"VIP Hospitality Zoning\",\"icon\":\"sparkle\"},{\"title\":\"Bespoke Marble Cocktail Bar\",\"icon\":\"gem\"},{\"title\":\"Smart Audio & Lighting Control\",\"icon\":\"layout\"},{\"title\":\"100% Client Satisfaction\",\"icon\":\"award\"}]','[\"Italian Black Marquina Marble\",\"Brushed Brass Metal Trim & Channels\",\"High-Density Acoustic Fluted Oak Wood\",\"Imported Italian Velvet Upholstery\"]','[\"\\/assets\\/img\\/project-5.jpg\",\"\\/assets\\/img\\/hero-slide-4.jpg\",\"\\/assets\\/img\\/project-6.jpg\",\"\\/assets\\/img\\/hero-living-room.jpg\",\"\\/assets\\/img\\/about-1.jpg\"]',NULL,'living_room','turnkey-duplex',1,1,5,1,'2026-08-31 18:26:58','2026-09-02 16:53:45'),(6,'luxury-duplex-villa-interior','Luxury Duplex Villa Interior','লাক্সারি ডুপ্লেক্স ভিলা ইন্টেরিয়র','Double-height grand duplex villa in Bashundhara R/A spanning 5,200 sqft with architectural glass staircase, custom chandelier, and private suites.','বসুন্ধরা আবাসিক এলাকায় ৫,২০০ স্কয়ারফিটের রাজকীয় ডুপ্লেক্স ভিলা, যাতে রয়েছে ডাবল-হাইট সিলিং, ভাসমান কাঁচের সিঁড়ি ও বিশাল ক্রিস্টাল ঝাড়বাতি।','A statement architectural turnkey duplex villa in Bashundhara R/A. Features a breathtaking 24-foot double-height living room with grand vertical wooden louvers, custom crystal chandelier focal point, cantilevered floating staircase with tempered glass railings, and expansive private family suites.','বসুন্ধরার এই রাজকীয় ডুপ্লেক্সে ডাবল-হাইট সিলিংয়ে বিশাল ঝাড়বাতি, ভাসমান সিঁড়ি এবং মার্বেল মেঝের নিখুঁত আর্কিটেকচার স্থানটিকে দিয়েছে অনন্য উচ্চতা।','Bashundhara, Dhaka','বসুন্ধরা, ঢাকা','Brigadier General (Retd.) S. Ahmed','2025','5,200 sqft','5 Bed, 6 Bath, Double-Height Living, Dining, Home Theater, Prayer Room','৫ বেড, ৬ বাথ, ডাবল-হাইট লিভিং, ডাইনিং, হোম থিয়েটার, প্রেয়ার রুম','Grand Architectural Luxury','গ্র্যান্ড আর্কিটেকচারাল লাক্সারি','Completed','/assets/img/project-6.webp','[\"24-foot double-height ceiling with custom grand crystal chandelier\",\"Cantilevered floating staircase with warm LED step under-glow\",\"Frameless 12mm laminated safety tempered glass balustrades\",\"Automated centralized smart home lighting & climate system\",\"Private upper mezzanine library, study, and family lounge\",\"Floor-to-ceiling panoramic soundproof curtain wall glass windows\"]','[{\"title\":\"Grand Architectural Scale\",\"icon\":\"sparkle\"},{\"title\":\"Floating Glass Staircase\",\"icon\":\"gem\"},{\"title\":\"Central Smart Automation\",\"icon\":\"layout\"},{\"title\":\"Full Turnkey Execution\",\"icon\":\"award\"}]','[\"Italian Botticino & Statuario Marble\",\"Frameless Laminated Safety Tempered Glass\",\"Natural Burma Teak Architectural Louvers\",\"Custom Brass Metal Inlays & Trim\"]','[\"\\/assets\\/img\\/project-6.jpg\",\"\\/assets\\/img\\/hero-slide-4.jpg\",\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/about-2.jpg\",\"\\/assets\\/img\\/project-5.jpg\",\"\\/assets\\/img\\/project-2.jpg\"]',NULL,'living_room','turnkey-duplex',1,1,6,1,'2026-08-31 18:26:58','2026-09-02 16:53:45'),(7,'creative-tech-studio-workspace','Creative Tech Studio Workspace','ক্রিয়েটিভ টেক স্টুডিও ওয়ার্কস্পেস','Vibrant 3,200 sqft collaborative software agency interior in Mohakhali DOHS with hot-desking, acoustic gaming lounge, and focus zones.','মহাখালী ডিওএইচএস-এ অবস্থিত ৩,২০০ স্কয়ারফিটের সফটওয়্যার স্টুডিও, যাতে রয়েছে কোলাবোরেটিভ হট-ডেস্ক, অ্যাকোস্টিক গেমিং লাউঞ্জ ও ফোকাস রুম।','A dynamic tech studio designed for creative software and digital product teams in Mohakhali DOHS. Features industrial loft aesthetics with exposed ceilings, acoustic hexagonal wall panels, agile stand-up scrum areas, high-speed power routing, and a recreational café zone.','মহাখালী ডিওএইচএস-এর এই ক্রিয়েটিভ টেক স্টুডিওতে সফটওয়্যার ডেভেলপার ও ডিজাইনারদের কাজের সুবিধার জন্য আধুনিক ইন্ডাস্ট্রিয়াল আর্কিটেকচার, সাউন্ডপ্রুফ ফোকাস পড এবং ক্যাফে লাউঞ্জ তৈরি করা হয়েছে।','Mohakhali DOHS, Dhaka','মহাখালী ডিওএইচএস, ঢাকা','Bytecraft Digital Ltd.','2024','3,200 sqft','Open Agile Floor, 3 Scrum Pods, Café Lounge, Gaming Room','ওপেন এজাইল ফ্লোর, ৩টি স্ক্রাম পড, ক্যাফে লাউঞ্জ, গেমিং রুম','Modern Industrial Loft','মডার্ন ইন্ডাস্ট্রিয়াল লফ্ট','Completed','/assets/img/project-2.webp','[\"Exposed industrial ceiling with suspended linear LED architectural luminaires\",\"Hexagonal acoustic felt wall tiles for echo suppression\",\"Hot-desking pods with motorized height-adjustable standing desks\",\"Integrated high-density data and power channels\",\"Glass whiteboard sprint ideation walls\",\"Recreation barista caf\\u00e9 and informal breakout lounge\"]','[{\"title\":\"Agile Team Productivity\",\"icon\":\"sparkle\"},{\"title\":\"Acoustic Sound Dampening\",\"icon\":\"gem\"},{\"title\":\"Ergonomic Standing Desks\",\"icon\":\"layout\"},{\"title\":\"Turnkey Fitout Execution\",\"icon\":\"award\"}]','[\"Matte Black Powder-Coated Steel Truss Structures\",\"Natural Birch Plywood Joinery & Seating\",\"Commercial Acoustic Polyethylene Felt Baffles\",\"Polished Industrial Concrete Flooring\"]','[\"\\/assets\\/img\\/project-2.jpg\",\"\\/assets\\/img\\/project-4.jpg\",\"\\/assets\\/img\\/hero-slide-4.jpg\",\"\\/assets\\/img\\/about-2.jpg\",\"\\/assets\\/img\\/project-1.jpg\"]',NULL,'office','commercial',1,0,7,1,'2026-08-31 18:26:58','2026-09-02 16:54:18'),(8,'minimalist-zen-bedroom-haven','Minimalist Zen Bedroom Haven','মিনিমালিস্ট জেন বেডরুম','Tranquil 520 sqft Scandinavian-style bedroom in Dhanmondi focusing on raw oak textures, linen headboard, and hidden vanity storage.','ধানমন্ডিতে শান্ত ও মনোরম ৫২০ স্কয়ারফিটের স্ক্যান্ডিনেভিয়ান বেডরুম, যাতে রয়েছে প্রাকৃতিক ওক কাঠ, লিনেন হেডবোর্ড ও হিডেন ভ্যানিটি।','A masterclass in peaceful Scandinavian simplicity located in Dhanmondi. Prioritizes natural morning light, organic linen textures, concealed custom wardrobe joinery, and soothing muted neutral tones that evoke mindfulness and restful rejuvenation.','ধানমন্ডির এই জেন বেডরুমটি প্রাকৃতিক স্নিগ্ধতা ও আরামের প্রতীক। প্রাকৃতিক ওক কাঠের ফার্নিচার, লিনেন ওয়াল ক্ল্যাডিং এবং নরম আলোর সংমিশ্রণ একে করে তুলেছে দারুণ আরামদায়ক।','Dhanmondi, Dhaka','ধানমন্ডি, ঢাকা','Mrs. Farzana Karim','2025','520 sqft','Bedroom, Dressing Area, Attached Bath','বেডরুম, ড্রেসিং এরিয়া, অ্যাটাচড বাথ','Japandi & Scandinavian Zen','জাপান্ডি ও স্ক্যান্ডিনেভিয়ান জেন','Completed','/assets/img/project-3.webp','[\"Natural Japanese Cedar and Oak slatted headboard wall\",\"Full-height minimalist wardrobes with hidden touch latches\",\"Concealed dressing vanity with backlit daylight mirror\",\"Organic Belgian linen drapery with motorized track\",\"Floating low-profile platform bed with integrated LEDs\",\"Solid natural matte polyurethane wood floor finish\"]','[{\"title\":\"Mindful Zen Atmosphere\",\"icon\":\"sparkle\"},{\"title\":\"Organic Natural Materials\",\"icon\":\"gem\"},{\"title\":\"Seamless Hidden Storage\",\"icon\":\"layout\"},{\"title\":\"Flawless Carpentry Finish\",\"icon\":\"award\"}]','[\"Natural White American Oak Lumber\",\"Belgian Textured Organic Linen\",\"Low-VOC Eco Matte Architectural Paint\",\"Dimmable High-CRI 2700K Warm LEDs\"]','[\"\\/assets\\/img\\/project-3.jpg\",\"\\/assets\\/img\\/hero-slide-2.jpg\",\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/about-1.jpg\",\"\\/assets\\/img\\/project-5.jpg\"]',NULL,'bedroom','residential',1,0,8,1,'2026-08-31 18:26:58','2026-09-02 16:54:18'),(9,'architectural-duplex-renovation','Architectural Duplex Renovation','ডুপ্লেক্স রিনোভেশন ও সংস্কার','Comprehensive 4,100 sqft structural & interior remodeling of a vintage duplex in Uttara Sector 7 into a modern energy-efficient luxury home.','উত্তরা সেক্টর ৭-এ অবস্থিত ৪,১০০ স্কয়ারফিটের পুরাতন ডুপ্লেক্সকে সম্পূর্ণ আধুনিকায়ন, ওপেন-প্ল্যান স্পেস ও এনার্জি-এফিশিয়েন্ট লাক্সারি হোমে রূপান্তর।','A structural and interior transformation of an aging 4,100 sqft duplex residence in Uttara Sector 7. Obsolete partitions were removed to introduce dramatic double-height light wells, smart thermal glazing, custom teak architectural woodwork, and open luxury entertainment zones.','উত্তরার এই ডুপ্লেক্স রিনোভেশন প্রজেক্টে পুরাতন ও অন্ধকার স্পেসকে ভেঙে নতুন ডাবল-হাইট সিলিং, আলো-বাতাস চলাচলের জন্য বড় গ্লাস উইন্ডো ও সেগুন কাঠের কারুকাজে আধুনিক রূপ দেওয়া হয়েছে।','Uttara Sector 7, Dhaka','উত্তরা সেক্টর ৭, ঢাকা','Dr. & Mrs. K. Alam','2024','4,100 sqft','4 Bed, 5 Bath, Open Living-Dining, Family Mezzanine, Rooftop Garden','৪ বেড, ৫ বাথ, ওপেন লিভিং-ডাইনিং, ফ্যামিলি মেজানাইন, রুফটপ গার্ডেন','Modern Heritage Architectural Revival','মডার্ন হেরিটেজ আর্কিটেকচারাল রিভাইভাল','Completed','/assets/img/project-1.webp','[\"Structural wall removal to create open-plan double-height light atrium\",\"Double-glazed thermal break UPVC windows for energy conservation\",\"Custom seasoned Burma Teak entry door with smart digital lock\",\"Restored terrazzo and imported porcelain tile flooring\",\"Integrated smart home HVAC and multi-zone lighting controls\",\"Architectural landscaped rooftop garden and pergola lounge\"]','[{\"title\":\"Structural Space Optimization\",\"icon\":\"sparkle\"},{\"title\":\"Energy Efficient Remodeling\",\"icon\":\"gem\"},{\"title\":\"Heritage Teak Wood Craftsmanship\",\"icon\":\"layout\"},{\"title\":\"Turnkey Renovation Handover\",\"icon\":\"award\"}]','[\"Solid Seasoned Burma Teak Wood\",\"Thermal-Break Low-E Double Glazed Windows\",\"Polished Porcelain Slabs (1200x600mm)\",\"Structural Steel Beam Reinforcements\"]','[\"\\/assets\\/img\\/project-1.jpg\",\"\\/assets\\/img\\/hero-slide-4.jpg\",\"\\/assets\\/img\\/project-6.jpg\",\"\\/assets\\/img\\/hero-living-room.jpg\",\"\\/assets\\/img\\/about-2.jpg\"]',NULL,'living_room','renovation',1,0,9,1,'2026-08-31 18:26:58','2026-09-02 16:54:18');
/*!40000 ALTER TABLE `lilyweb_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_services`
--

DROP TABLE IF EXISTS `lilyweb_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `title_en` varchar(150) NOT NULL,
  `title_bn` varchar(150) DEFAULT NULL,
  `tag_badge_en` varchar(100) NOT NULL,
  `tag_badge_bn` varchar(100) DEFAULT NULL,
  `summary_en` text NOT NULL,
  `summary_bn` text DEFAULT NULL,
  `description_en` longtext DEFAULT NULL,
  `description_bn` longtext DEFAULT NULL,
  `icon_svg` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_services`
--

LOCK TABLES `lilyweb_services` WRITE;
/*!40000 ALTER TABLE `lilyweb_services` DISABLE KEYS */;
INSERT INTO `lilyweb_services` VALUES (1,'interior-design','Interior Design','ইন্টেরিয়র ডিজাইন','Bespoke 9D','আর্কিটেকচারাল','Comprehensive conceptual layout, spatial planning and aesthetic styling for premium modern living.','আবাসিক ও বাণিজ্যিক স্পেসের জন্য আধুনিক ও সময়োপযোগী আর্কিটেকচারাল স্পেস প্ল্যানিং ও থ্রিডি ডিজাইন।',NULL,NULL,'<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z\"/><polyline points=\"9 22 9 12 15 12 15 22\"/></svg>',1,1,'2026-08-31 18:26:58','2026-08-31 20:37:05'),(2,'renovation','Renovation','রিনোভেশন ও সংস্কার','Makeover','স্ট্রাকচারাল','Full structural makeover and interior modernization tailored to elevate your existing property.','পুরাতন বাসা বা অফিস স্পেসকে আধুনিক স্ট্রাকচার, প্রিমিয়াম টাইলস ও নতুন ডিজাইনে রূপান্তর।',NULL,NULL,'<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z\"/></svg>',2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(3,'modular-kitchen','Modular Kitchen','মডিউলার কিচেন','Modular Blum','জার্মান ফিটিংস','Smart European-grade modular kitchens engineered with Blum hardware and moisture-resistant finishes.','জার্মান ব্লুম হার্ডওয়্যার ও ওয়াটারপ্রুফ অ্যাক্রিলিক ফিনিশে তৈরি আধুনিক মডিউলার কিচেন সল্যুশন।',NULL,NULL,'<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\"/><path d=\"M3 9h18\"/><path d=\"M9 21V9\"/></svg>',3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(4,'false-ceiling','False Ceiling','ফলস সিলিং ও লাইটিং','Circadian LED','অ্যাম্বিয়েন্ট লাইট','Architectural Gypsum false ceiling systems integrated with circadian LED ambient illumination.','আধুনিক জিপসাম সিলিং, শ্যাডো লাইন কোভ এবং আর্কিটেকচারাল সার্কাডিয়ান অ্যাম্বিয়েন্ট লাইটিং।',NULL,NULL,'<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5\"/></svg>',4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(5,'office-interior','Office Interior','কর্পোরেট অফিস ইন্টেরিয়র','Commercial','সাউন্ডপ্রুফ','Corporate workspace planning focused on employee productivity, acoustic privacy, and executive style.','কর্মদক্ষতা বৃদ্ধি, সাউন্ডপ্রুফ প্রাইভেসি এবং লাক্সারি এক্সিকিউটিভ কেবিন বিশিষ্ট অফিস স্পেস।',NULL,NULL,'<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><rect x=\"2\" y=\"7\" width=\"20\" height=\"14\" rx=\"2\" ry=\"2\"/><path d=\"M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16\"/></svg>',5,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(6,'turnkey-project','Turnkey Project','টার্নকি প্রজেক্ট হ্যান্ডওভার','Turnkey 100%','এন্ড-টু-এন্ড','End-to-end design, civil execution, procurement and on-time flawless handover of your dream space.','নকশা থেকে শুরু করে সিভিল নির্মাণ, ম্যাটেরিয়াল ক্রয় ও অন-টাইম ত্রুটিহীন চাবি সমর্পণ।',NULL,NULL,'<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><path d=\"M12 8l4 4-4 4M8 12h8\"/></svg>',6,1,'2026-08-31 18:26:58','2026-08-31 18:26:58');
/*!40000 ALTER TABLE `lilyweb_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_site_settings`
--

DROP TABLE IF EXISTS `lilyweb_site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext DEFAULT NULL,
  `setting_group` enum('general','contact','social','legal','seo') NOT NULL DEFAULT 'general',
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_group` (`setting_group`),
  KEY `idx_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_site_settings`
--

LOCK TABLES `lilyweb_site_settings` WRITE;
/*!40000 ALTER TABLE `lilyweb_site_settings` DISABLE KEYS */;
INSERT INTO `lilyweb_site_settings` VALUES (1,'brand_name','Lily Interiors','general',1,'2026-08-31 18:26:57'),(2,'tagline_en','We Design Spaces That Inspire Life','general',1,'2026-08-31 18:26:57'),(3,'tagline_bn','নান্দনিক ও আধুনিক ইন্টেরিয়র সল্যুশন','general',1,'2026-08-31 18:26:57'),(4,'footer_bio_en','Premier luxury interior design and architectural execution firm delivering timeless residential and corporate transformations across Dhaka.','general',1,'2026-08-31 18:26:57'),(5,'footer_bio_bn','ঢাকা জুড়ে নান্দনিক ও আভিজাত্যপূর্ণ আবাসিক ও বাণিজ্যিক ইন্টেরিয়র ডিজাইন এবং শতভাগ নিখুঁত টার্নকি বাস্তবায়নকারী বিশ্বস্ত প্রতিষ্ঠান।','general',1,'2026-08-31 18:26:57'),(6,'phone_number','+88 01734182694','contact',1,'2026-09-02 16:53:45'),(7,'whatsapp_url','https://wa.me/8801734182694','contact',1,'2026-09-02 16:53:45'),(8,'email_address','lilyinteriorsbd@gmail.com','contact',1,'2026-09-02 16:53:45'),(9,'studio_address_en','Level 4, House 12, Road 4, Sector 3, Uttara, Dhaka - 1230, Bangladesh','contact',1,'2026-09-02 16:53:45'),(10,'studio_address_bn','লেভেল ৪, বাড়ি ১২, রোড ৪, সেক্টর ৩, উত্তরা, ঢাকা - ১২৩০, বাংলাদেশ','contact',1,'2026-09-02 16:53:45'),(11,'office_hours_en','Saturday – Thursday: 10:00 AM – 8:00 PM (Friday Closed)','contact',1,'2026-09-02 16:53:45'),(12,'office_hours_bn','শনিবার – বৃহস্পতিবার: সকাল ১০:০০ – রাত ৮:০০ (শুক্রবার বন্ধ)','contact',1,'2026-09-02 16:53:45'),(13,'google_maps_iframe','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14602.700311747805!2d90.3952!3d23.7937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c15ea1de1%3A0xf62511300742b835!2sBanani%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1700000000000','contact',1,'2026-08-31 18:26:57'),(14,'social_whatsapp','https://wa.me/8801734182694','social',1,'2026-09-02 16:53:45'),(15,'social_facebook','https://www.facebook.com/LilyInteriorsbd/','social',1,'2026-09-02 16:53:45'),(16,'social_instagram','https://www.instagram.com/lilyinteriors/','social',1,'2026-09-02 16:53:45'),(17,'social_pinterest','https://www.pinterest.com/lilyinteriors/','social',1,'2026-09-02 16:53:45'),(18,'social_youtube','https://www.youtube.com/channel/UCkLkpvteAlhSFUkgzQy8p_g/videos','social',1,'2026-09-02 16:53:45'),(19,'google_site_verification','','seo',1,'2026-08-31 18:26:57'),(20,'google_analytics_id','','seo',1,'2026-08-31 18:26:57'),(21,'seo_meta_title_en','Lily Interiors — Architectural Interior Design & Turnkey Solutions in Dhaka','seo',1,'2026-08-31 21:01:37'),(22,'seo_meta_title_bn','লিলি ইন্টেরিয়র্স — ঢাকায় প্রিমিয়াম আর্কিটেকচারাল ইন্টেরিয়র ডিজাইন ও টার্নকি সল্যুশন','seo',1,'2026-08-31 21:01:37'),(23,'seo_meta_desc_en','Award-winning interior design and architecture studio in Dhaka specializing in luxury residences, penthouses, duplexes, modular kitchens, and corporate offices.','seo',1,'2026-08-31 21:01:37'),(24,'seo_meta_desc_bn','ঢাকায় প্রিমিয়াম আর্কিটেকচারাল ইন্টেরিয়র ডিজাইন, মডিউলার কিচেন, ডুপ্লেক্স রিনোভেশন এবং টার্নকি সল্যুশন।','seo',1,'2026-08-31 21:01:37'),(25,'privacy_policy_en','# Privacy Policy\\n\\nAt Lily Interiors, your privacy is our top priority. We respect and protect the confidentiality of our clients\' architectural projects, floor plans, and personal inquiries.','legal',1,'2026-08-31 18:26:57'),(26,'privacy_policy_bn','# প্রাইভেসি পলিসি\\n\\nলিলি ইন্টেরিয়র্সে আপনার তথ্যের সুরক্ষা আমাদের সর্বোচ্চ অগ্রাধিকার। গ্রাহকদের প্রজেক্ট এবং ব্লুপ্রিন্টের সর্বোচ্চ গোপনীয়তা রক্ষা করা হয়।','legal',1,'2026-08-31 18:26:57'),(27,'terms_of_service_en','# Terms of Service\\n\\nBy engaging with Lily Interiors, you agree to our standard project engagement terms, design consultation protocols, and warranty conditions.','legal',1,'2026-08-31 18:26:57'),(28,'terms_of_service_bn','# ব্যবহারের শর্তাবলী\\n\\nলিলি ইন্টেরিয়র্সের সেবা গ্রহণের মাধ্যমে আপনি আমাদের পেশাদার আর্কিটেকচারাল কনসালটেশন ও ওয়ারেন্টি শর্তাবলীতে সম্মত হচ্ছেন।','legal',1,'2026-08-31 18:26:58'),(29,'site_name','Lily Interiors','general',1,'2026-08-31 19:08:07'),(30,'site_tagline','Architectural Interior Design & Turnkey Solutions','general',1,'2026-09-02 16:53:45'),(31,'phone_primary','+88 01734182694','general',1,'2026-08-31 19:08:07'),(32,'phone_secondary','+88 0172345678','general',1,'2026-09-02 16:53:45'),(33,'email_primary','lilyinteriorsbd@gmail.com','general',1,'2026-08-31 19:08:07'),(34,'email_support','info@lilyinteriorsbd.com','general',1,'2026-09-02 16:53:45'),(35,'whatsapp_number','8801734182694','general',1,'2026-08-31 21:00:07'),(36,'whatsapp_message','Hello','general',1,'2026-08-31 21:00:07'),(37,'address_en','Level 4, House 12, Road 4, Sector 3, Uttara, Dhaka - 1230, Bangladesh','general',1,'2026-09-02 16:53:45'),(38,'address_bn','লেভেল ৪, বাড়ি ১২, রোড ৪, সেক্টর ৩, উত্তরা, ঢাকা - ১২৩০, বাংলাদেশ','general',1,'2026-09-02 16:53:45'),(39,'business_hours_en','Saturday – Thursday: 10:00 AM – 8:00 PM (Friday Closed)','general',1,'2026-09-02 16:53:45'),(40,'business_hours_bn','শনিবার – বৃহস্পতিবার: সকাল ১০:০০ – রাত ৮:০০ (শুক্রবার বন্ধ)','general',1,'2026-09-02 16:53:45'),(43,'social_linkedin','https://www.linkedin.com/company/lilyinteriors/','general',1,'2026-09-02 16:53:45'),(46,'google_maps_embed','','general',1,'2026-08-31 21:00:07'),(67,'floating_whatsapp_enabled','1','general',1,'2026-08-31 21:00:07'),(93,'seo_keywords','interior design dhaka, best interior company in bangladesh, luxury duplex interior, turnkey interior design, modular kitchen dhaka, office interior design, bangladesh interior architecture','seo',1,'2026-09-02 16:53:45'),(95,'bing_site_verification','','seo',1,'2026-08-31 21:00:08'),(97,'google_tag_manager_id','','seo',1,'2026-08-31 21:00:08'),(98,'og_image_url','/assets/img/hero-living-room.jpg','seo',1,'2026-08-31 21:00:08'),(99,'ai_company_synopsis','Lily Interiors is Dhaka premier architectural interior design practice, established by licensed architects and master engineers. Specialized in luxury turnkey residences, executive duplexes in Gulshan, Banani, Dhanmondi, Uttara and corporate headquarters.','seo',1,'2026-09-02 16:53:45'),(100,'ai_target_locations','Gulshan, Banani, Dhanmondi, Uttara, Bashundhara, Baridhara, Mirpur, Dhaka, Bangladesh','seo',1,'2026-08-31 21:01:37'),(101,'schema_price_range','$$$','seo',1,'2026-08-31 21:00:08'),(102,'schema_rating_val','4.9','seo',1,'2026-08-31 21:00:08'),(103,'schema_review_count','150','seo',1,'2026-08-31 21:00:08'),(104,'custom_head_scripts','','seo',1,'2026-08-31 21:00:08'),(105,'custom_body_scripts','','seo',1,'2026-08-31 21:00:08');
/*!40000 ALTER TABLE `lilyweb_site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_stats`
--

DROP TABLE IF EXISTS `lilyweb_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stat_key` varchar(50) NOT NULL,
  `value_number` int(11) NOT NULL DEFAULT 0,
  `suffix` varchar(20) NOT NULL DEFAULT '+',
  `label_en` varchar(100) NOT NULL,
  `label_bn` varchar(100) DEFAULT NULL,
  `icon_svg` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_key` (`stat_key`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_stats`
--

LOCK TABLES `lilyweb_stats` WRITE;
/*!40000 ALTER TABLE `lilyweb_stats` DISABLE KEYS */;
INSERT INTO `lilyweb_stats` VALUES (1,'stat_1',250,'+','Projects Completed','বাস্তবায়িত প্রজেক্ট','<svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M22 11.08V12a10 10 0 1 1-5.93-9.14\"/><polyline points=\"22 4 12 14.01 9 11.01\"/></svg>',1,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(2,'stat_2',180,'+','Happy Homeowners','সন্তুষ্ট ক্লায়েন্ট','<svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"/><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"/></svg>',2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(3,'stat_3',8,'+','Years of Experience','বছরের অভিজ্ঞতা','<svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><polyline points=\"12 6 12 12 16 14\"/></svg>',3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(4,'stat_4',15,'+','Expert Architects','দক্ষ আর্কিটেক্ট','<svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><polygon points=\"12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2\"/></svg>',4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(5,'stat_5',100,'%','Turnkey On-Time Handover','ত্রুটিহীন চাবি হস্তান্তর','<svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#C8102E\" stroke-width=\"2\"><path d=\"M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z\"/><polyline points=\"9 12 11 14 15 10\"/></svg>',5,1,'2026-08-31 18:26:58','2026-08-31 18:26:58');
/*!40000 ALTER TABLE `lilyweb_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_system_logs`
--

DROP TABLE IF EXISTS `lilyweb_system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_system_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_level` enum('info','warning','error','critical') NOT NULL DEFAULT 'info',
  `error_code` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `stack_trace` longtext DEFAULT NULL,
  `request_uri` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_level_resolved` (`log_level`,`is_resolved`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_system_logs`
--

LOCK TABLES `lilyweb_system_logs` WRITE;
/*!40000 ALTER TABLE `lilyweb_system_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `lilyweb_system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_testimonials`
--

DROP TABLE IF EXISTS `lilyweb_testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `author_name` varchar(150) NOT NULL,
  `author_role_en` varchar(150) NOT NULL,
  `author_role_bn` varchar(150) DEFAULT NULL,
  `author_location_en` varchar(150) NOT NULL,
  `author_location_bn` varchar(150) DEFAULT NULL,
  `project_tag_en` varchar(150) NOT NULL,
  `project_tag_bn` varchar(150) DEFAULT NULL,
  `author_initials` varchar(10) NOT NULL,
  `content_en` text NOT NULL,
  `content_bn` text DEFAULT NULL,
  `rating_score` decimal(2,1) NOT NULL DEFAULT 5.0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_testimonials`
--

LOCK TABLES `lilyweb_testimonials` WRITE;
/*!40000 ALTER TABLE `lilyweb_testimonials` DISABLE KEYS */;
INSERT INTO `lilyweb_testimonials` VALUES (1,'Barrister Rafiqul Islam','Owner, Luxury Duplex','মালিক, লাক্সারি ডুপ্লেক্স','Gulshan 2, Dhaka','গুলশান ২, ঢাকা','Gulshan 2 Duplex • 3,200 sqft','গুলশান ২ ডুপ্লেক্স • ৩,২০০ স্কয়ারফিট','RI','Lily Interiors transformed our Gulshan duplex into an absolute architectural masterpiece. The precision in woodwork, seamless ceiling lighting, and on-time handover exceeded all expectations.','লিলি ইন্টেরিয়র্স আমাদের গুলশানের ডুপ্লেক্সটিকে একটি অসাধারণ নান্দনিক মাস্টারপিসে পরিণত করেছে। উডওয়ার্কের ফিনিশিং এবং সময়মতো হ্যান্ডওভার আমাদের প্রত্যাশার চেয়েও চমৎকার ছিল।',5.0,1,1,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(2,'Farzana Chowdhury','Managing Director, Zenith Tech','ম্যানেজিং ডিরেক্টর, জেনিথ টেক','Banani, Dhaka','বনানী, ঢাকা','Banani Corporate HQ • 4,500 sqft','বনানী হেডকোয়ার্টার • ৪,৫০০ স্কয়ারফিট','FC','Exceptional attention to functional spatial planning and acoustic design for our corporate headquarters. Our entire executive leadership is thoroughly impressed with their professionalism.','আমাদের কর্পোরেট অফিসের স্পেস প্ল্যানিং এবং সাউন্ডপ্রুফ এক্সিকিউটিভ কেবিনের কাজ এক কথায় অসাধারণ। লিলি ইন্টেরিয়র্সের টিম অত্যন্ত প্রফেশনাল এবং আন্তরিক।',5.0,1,2,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(3,'Dr. Mahfuzur Rahman','Senior Consultant, Square Hospital','সিনিয়র কনসালটেন্ট, স্কয়ার হাসপাতাল','Dhanmondi, Dhaka','ধানমন্ডি, ঢাকা','Dhanmondi Turnkey • 2,400 sqft','ধানমন্ডি অ্যাপার্টমেন্ট • ২,৪০০ স্কয়ারফিট','MR','Their turnkey execution was completely hassle-free. From 3D visualization to the final handover, every detail matched the approved 3D renders perfectly.','টার্নকি হ্যান্ডওভার সম্পূর্ণ ঝামেলাহীন ছিল। থ্রিডি ডিজাইনে যেমন দেখেছিলাম, বাস্তবে হুবহু তাই পেয়েছি। তাদের ব্যবহৃত ব্লুম ফিটিংস এবং মার্বেল কোয়ালিটি অত্যন্ত প্রিমিয়াম।',5.0,1,3,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(4,'Tanvir Ahmed','CEO, CloudScale Inc.','সিইও, ক্লাউডস্কেল ইনক.','Uttara Sector 4, Dhaka','উত্তরা সেক্টর ৪, ঢাকা','Uttara Penthouse • 3,800 sqft','উত্তরা পেন্টহাউস • ৩,৮০০ স্কয়ারফিট','TA','The customized ambient lighting and minimalist modular kitchen crafted for our penthouse created a truly world-class living space. Highly recommended for luxury projects.','আমাদের পেন্টহাউসের অ্যাম্বিয়েন্ট লাইটিং এবং ইউরোপিয়ান মডিউলার কিচেন সত্যিকার অর্থেই বিশ্বমানের হয়েছে। লাক্সারি ইন্টেরিয়রের জন্য লিলি ইন্টেরিয়র্স সেরা।',5.0,1,4,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(5,'Syeda Zakia Sultana','Villa Owner','ভিলা মালিক','Bashundhara R/A, Dhaka','বসুন্ধরা আবাসিক এলাকা, ঢাকা','Bashundhara Villa • 5,200 sqft','বসুন্ধরা ভিলা • ৫,২০০ স্কয়ারফিট','ZS','The creative vision and material quality delivered by Lily Interiors is unmatched in Dhaka. Their architects listened carefully to our family needs and crafted our dream home.','লিলি ইন্টেরিয়র্সের ডিজাইনাররা আমাদের পরিবারের চাহিদা খুব মনোযোগ দিয়ে শুনেছেন এবং অত্যন্ত রুচিশীলভাবে আমাদের স্বপ্নের বাড়িটি তৈরি করে দিয়েছেন।',5.0,1,5,1,'2026-08-31 18:26:58','2026-08-31 18:26:58'),(6,'Engr. Nayeem Khan','Director, Prime Infrastructure','পরিচালক, প্রাইম ইনফ্রাস্ট্রাকচার','Baridhara DOHS, Dhaka','বারিধারা ডিওএইচএস, ঢাকা','Baridhara Residence • 3,600 sqft','বারিধারা অ্যাপার্টমেন্ট • ৩,৬০০ স্কয়ারফিট','NK','Flawless project management, premium German fittings, and zero compromises on durability. Handover was executed precisely within the agreed 60-day schedule.','নিখুঁত প্রজেক্ট ম্যানেজমেন্ট এবং নির্ধারিত ৬০ দিনের মধ্যে শতভাগ প্রস্তুত করে হ্যান্ডওভার প্রদান করেছে। কাঠ ও ক্যাবিনেটের স্থায়িত্ব সত্যিই প্রশংসনীয়।',5.0,1,6,1,'2026-08-31 18:26:58','2026-08-31 18:26:58');
/*!40000 ALTER TABLE `lilyweb_testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lilyweb_users`
--

DROP TABLE IF EXISTS `lilyweb_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lilyweb_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) NOT NULL DEFAULT 'Owner',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lilyweb_users`
--

LOCK TABLES `lilyweb_users` WRITE;
/*!40000 ALTER TABLE `lilyweb_users` DISABLE KEYS */;
INSERT INTO `lilyweb_users` VALUES (1,'admin','admin@lilyinteriorsbd.com','$2y$12$yHFpZVDumnRa6oYs5AYff.zslZGQStQuvI5bWFOhL3tsF4wZR.mbG','Lily Interiors Owner',1,'2026-09-02 16:21:47',NULL,'2026-08-31 18:26:57','2026-09-02 16:21:47');
/*!40000 ALTER TABLE `lilyweb_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-04 11:10:22
