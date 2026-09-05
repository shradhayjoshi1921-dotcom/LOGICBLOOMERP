-- MySQL dump 10.13  Distrib 8.4.8, for Linux (aarch64)
--
-- Host: localhost    Database: shradhay
-- ------------------------------------------------------
-- Server version	8.4.8-0ubuntu0.25.10.1

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
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `university_id` int DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `principal_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `university_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `principal_id` (`principal_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`principal_id`) REFERENCES `principal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (16,24,11,4,18,'2026-05-20','Present','2026-05-20 09:00:42',NULL),(17,24,11,4,18,'2026-05-30','Present','2026-05-30 10:38:58',NULL),(18,25,11,4,18,'2026-05-30','Present','2026-05-30 10:38:58',NULL),(19,24,12,4,19,'2026-06-02','Present','2026-06-02 07:51:59',NULL),(20,25,12,4,19,'2026-06-02','Absent','2026-06-02 07:51:59',NULL),(21,26,13,4,20,'2026-06-02','Present','2026-06-02 07:53:24',NULL),(22,26,14,4,21,'2026-06-02','Present','2026-06-02 07:55:46',NULL);
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `principal_id` int DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `university_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (8,4,'Computer Engineering ',NULL),(9,4,'Civil Engineering',NULL);
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `principal_id` int DEFAULT NULL,
  `class_name` varchar(100) DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `university_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,8,4,'Sem 1','2023-2026',NULL),(2,9,4,'Sem 1','2023-2026',NULL);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `principal_id` int NOT NULL,
  `subject_id` int DEFAULT NULL,
  `exam_name` varchar(100) NOT NULL,
  `marks_obtained` int NOT NULL,
  `total_marks` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `university_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `principal_id` (`principal_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `marks_ibfk_3` FOREIGN KEY (`principal_id`) REFERENCES `principal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `marks_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marks`
--

LOCK TABLES `marks` WRITE;
/*!40000 ALTER TABLE `marks` DISABLE KEYS */;
INSERT INTO `marks` VALUES (21,24,11,4,18,'Surprise test',15,25,'2026-05-19 13:28:28',NULL),(22,24,11,4,18,'1st class test',15,20,'2026-05-30 10:38:02',NULL),(23,25,11,4,18,'1st class test',15,20,'2026-05-30 10:38:30',NULL),(24,25,11,4,18,'Surprise test',40,50,'2026-05-30 10:38:45',NULL),(25,24,12,4,19,'1st class test',25,30,'2026-06-02 07:51:47',NULL),(26,25,12,4,19,'1st class test',20,30,'2026-06-02 07:51:47',NULL),(27,26,13,4,20,'1st class test',40,50,'2026-06-02 07:53:39',NULL),(28,26,14,4,21,'Surprise test',40,50,'2026-06-02 07:55:34',NULL);
/*!40000 ALTER TABLE `marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,2,'Salary of ₹50000 for March has been credited',0,'2026-03-24 13:18:47'),(2,3,'Salary of ₹50000 for March has been credited',0,'2026-03-25 13:33:42'),(3,3,'Salary of ₹1 for March has been credited',0,'2026-03-25 13:53:48'),(4,3,'Salary of ₹50000 for March has been credited',0,'2026-03-25 13:54:18'),(5,7,'Salary of ₹50000 for April has been credited',0,'2026-04-26 15:56:37'),(6,8,'Salary of ₹50000 for March has been credited',0,'2026-05-16 15:05:55'),(7,11,'Salary of ₹50000 for March has been credited',0,'2026-05-29 06:54:04'),(8,13,'Salary of ₹15000 for March has been credited',0,'2026-06-02 08:41:10'),(9,12,'Salary of ₹50000 for March has been credited',0,'2026-08-08 16:12:40');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `university_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `next_due` date DEFAULT NULL,
  `status` enum('Paid','Pending','Failed') DEFAULT 'Paid',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:02'),(2,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:04'),(3,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:05'),(4,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:06'),(5,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:08'),(6,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:08'),(7,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:08'),(8,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:09'),(9,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:10'),(10,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:11'),(11,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:12'),(12,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:25'),(13,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 13:22:26'),(14,1,999.00,'2026-04-10','2026-05-10','Paid','2026-04-10 14:30:41');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `principal`
--

DROP TABLE IF EXISTS `principal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `principal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `institute_name` varchar(255) DEFAULT NULL,
  `university_id` int DEFAULT NULL,
  `subscription_status` varchar(20) DEFAULT 'active',
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `principal`
--

LOCK TABLES `principal` WRITE;
/*!40000 ALTER TABLE `principal` DISABLE KEYS */;
INSERT INTO `principal` VALUES (4,'Shradhay','sherryjoshi987@gmail.com','$2y$12$9KmaoNObbuNuvjCiAWzHjOGh44aENPobcQF.mXpNnfYsMinMEcTd6','2026-04-01 06:14:09','GOVERNMENT MILLENNIUM POLYTECHNIC CHAMBA',1,'active',NULL),(5,'Shradhay','sherryjoshi9400@gmail.com','$2y$12$xpvK6ZFsJ9nt2ZDlqOpPf.5vrsOSxKelAc95tdftkhr3iRMw9hBhG','2026-04-23 16:27:01','Gmp chamba',1,'active',NULL);
/*!40000 ALTER TABLE `principal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_payments`
--

DROP TABLE IF EXISTS `salary_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salary_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `principal_id` int NOT NULL,
  `month` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_payments`
--

LOCK TABLES `salary_payments` WRITE;
/*!40000 ALTER TABLE `salary_payments` DISABLE KEYS */;
INSERT INTO `salary_payments` VALUES (1,2,50000.00,'2026-03-10','UPI','18181818111818',2,NULL),(2,2,50000.00,'2026-03-24',NULL,NULL,2,'March'),(3,3,50000.00,'2026-03-25',NULL,NULL,2,'March'),(4,3,1.00,'2026-03-25',NULL,NULL,3,'March'),(5,3,50000.00,'2026-03-25',NULL,NULL,3,'March'),(6,7,50000.00,'2026-04-26',NULL,NULL,4,'April'),(7,8,50000.00,'2026-05-16',NULL,NULL,4,'March'),(8,11,50000.00,'2026-05-29',NULL,NULL,4,'March'),(9,13,15000.00,'2026-06-02',NULL,NULL,4,'March'),(10,12,50000.00,'2026-08-08',NULL,NULL,4,'March');
/*!40000 ALTER TABLE `salary_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subscription_price` decimal(10,2) DEFAULT '999.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,999.00);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `principal_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `roll_no` varchar(100) DEFAULT NULL,
  `branch_id` int NOT NULL,
  `class_id` int NOT NULL,
  `university_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_roll` (`roll_no`,`branch_id`,`class_id`),
  KEY `principal_id` (`principal_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `fk_students_class` (`class_id`),
  CONSTRAINT `fk_students_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`principal_id`) REFERENCES `principal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (24,4,11,'Abc','$2y$12$kT/LhxWuGyDaLzqLImyF1evROpsYFWL9B6gDxWeexhrpZKbYn9dem','2026-05-19 11:29:32','2',8,1,NULL),(25,4,11,'Xyz','$2y$12$OdOnn3KKMs8L/C0./eI6Z.41FuyiVX39n2OYvVWelgwVWVU6sksgK','2026-05-30 06:14:28','1',8,1,NULL),(26,4,13,'Mannat','$2y$12$INKlBS5HT0dGFBle3TrSCO1Hr1J6FmQOURQlOlpQS8omlcU0/4cS6','2026-06-02 07:53:14','1',9,2,NULL);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `principal_id` int NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `class_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `principal_id` (`principal_id`),
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`principal_id`) REFERENCES `principal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (18,11,4,'English','2026-05-19 11:30:59',NULL),(19,12,4,'Scripting Language','2026-06-02 07:51:24',NULL),(20,13,4,'Engineering Graphics','2026-06-02 07:52:45',NULL),(21,14,4,'Maths','2026-06-02 07:55:14',NULL),(22,11,4,'Maths','2026-06-12 04:35:09',NULL);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `university_id` int DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` enum('Paid','Pending') DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_assignments`
--

DROP TABLE IF EXISTS `teacher_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `principal_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_assignments`
--

LOCK TABLES `teacher_assignments` WRITE;
/*!40000 ALTER TABLE `teacher_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `teacher_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `principal_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `qualification` varchar(150) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) DEFAULT NULL,
  `identity_number` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `university_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `principal_id` (`principal_id`),
  CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`principal_id`) REFERENCES `principal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (11,4,'Abc','admin123@gmail.com','$2y$12$zbsnLUrAVlo16.A0NEWlEeJXXnffmBFeQfNBVsSvki4u3f/1hNgw.','Diploma',10000.00,'2026-05-19 11:28:56','Abc','292929',1,NULL,8),(12,4,'Shradhay','sherryjoshi@gmail.com','$2y$12$s5/Rjmpm0B/0kF3ffFpJU.bnUvsmKqBdEyuwZeD9lOOThfe96NBmS','Diploma',15000.00,'2026-06-02 07:50:34','Chamba','00998877',1,NULL,8),(13,4,'Shradhay','joshi@gmail.com','$2y$12$mf.METiTdBTT473FlO/eRuPF1z7mXBg4LmfdFoGi/ma61CePlocQ.','Diploma',15000.00,'2026-06-02 07:51:05','Chamba','12345678',1,NULL,9),(14,4,'Mannat','teacher1@example.com','$2y$12$oFACeyLLSq0E.u1e30MXFuYnfHCrcH7PG6ShgL4yhlSkaEJ6gsr9y','Diploma',50000.00,'2026-06-02 07:55:00','Chamba','00998877',1,NULL,9);
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `universities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `university_name` varchar(255) NOT NULL,
  `subscription_status` enum('Active','Paused','Expired') DEFAULT 'Active',
  `subscription_end` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `universities`
--

LOCK TABLES `universities` WRITE;
/*!40000 ALTER TABLE `universities` DISABLE KEYS */;
/*!40000 ALTER TABLE `universities` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-05  7:08:41
