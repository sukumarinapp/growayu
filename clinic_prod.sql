-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: localhost    Database: clinic
-- ------------------------------------------------------
-- Server version	5.7.29-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bill_items`
--

DROP TABLE IF EXISTS `bill_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill_items`
--

LOCK TABLES `bill_items` WRITE;
/*!40000 ALTER TABLE `bill_items` DISABLE KEYS */;
INSERT INTO `bill_items` VALUES (1,1,1,16,1,750.00,37.50),(9,1,5,19,1,2500.00,0.00),(10,1,5,21,1,25000.00,0.00),(11,1,5,19,1,2500.00,0.00),(12,1,5,20,1,9800.00,0.00),(17,1,7,19,1,2500.00,0.00),(18,1,7,19,1,2500.00,0.00),(19,1,7,21,1,25000.00,0.00),(20,1,7,27,1,15000.00,0.00),(21,1,7,22,1,10000.00,0.00);
/*!40000 ALTER TABLE `bill_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billing`
--

DROP TABLE IF EXISTS `billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `billnum` int(11) NOT NULL,
  `bill_date` date NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL DEFAULT '0',
  `total` decimal(10,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billing`
--

LOCK TABLES `billing` WRITE;
/*!40000 ALTER TABLE `billing` DISABLE KEYS */;
INSERT INTO `billing` VALUES (1,1,1,'2020-01-02',1,0,750.00,700.00,50.50),(5,1,2,'2020-01-04',5,18,39800.00,39800.00,0.00),(7,1,3,'2020-01-04',6,20,55000.00,55000.00,0.00);
/*!40000 ALTER TABLE `billing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `doctor_token` varchar(500) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `experience` varchar(500) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` VALUES (18,1,'755603acf07574e49f97def4aec0ef65','Dr R Varadarajulu','9880101778','Male','MBBS(AFMC),MD Medicine, DNB Neurology, FRCP (Edin)','38','m.jpg','Active'),(20,1,'7f9bf334e0c6291191dd33a8cf926a96','Dr. Satish Babu','9901848887','Male','MBBS','','m.jpg','Active'),(21,1,'2de1c6a0f4516b7a2b5f0db676e16a59','Dr. Sumayya','9611353819','Female','MBBS','','f.jpg','Active');
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examination`
--

DROP TABLE IF EXISTS `examination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `examination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `param_name` varchar(50) DEFAULT NULL,
  `param_value` varchar(50000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examination`
--

LOCK TABLES `examination` WRITE;
/*!40000 ALTER TABLE `examination` DISABLE KEYS */;
/*!40000 ALTER TABLE `examination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `health_parameter`
--

DROP TABLE IF EXISTS `health_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `health_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) DEFAULT NULL,
  `param_name` varchar(50) NOT NULL,
  `first_time` int(11) NOT NULL,
  `input_type` varchar(20) NOT NULL,
  `input_value` varchar(500) NOT NULL,
  `speciality` int(11) NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `health_parameter`
--

LOCK TABLES `health_parameter` WRITE;
/*!40000 ALTER TABLE `health_parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `health_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holiday`
--

DROP TABLE IF EXISTS `holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holiday`
--

LOCK TABLES `holiday` WRITE;
/*!40000 ALTER TABLE `holiday` DISABLE KEYS */;
INSERT INTO `holiday` VALUES (2,1,'2018-12-25','Christmas'),(3,2,'2019-12-25','Christmas'),(4,1,'2020-01-01','new year');
/*!40000 ALTER TABLE `holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave`
--

DROP TABLE IF EXISTS `leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave`
--

LOCK TABLES `leave` WRITE;
/*!40000 ALTER TABLE `leave` DISABLE KEYS */;
INSERT INTO `leave` VALUES (1,1,19,'2019-12-29','2020-01-06','out of station');
/*!40000 ALTER TABLE `leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `online_appointments`
--

DROP TABLE IF EXISTS `online_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `status` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_appointments`
--

LOCK TABLES `online_appointments` WRITE;
/*!40000 ALTER TABLE `online_appointments` DISABLE KEYS */;
INSERT INTO `online_appointments` VALUES (1,1,2,'Sukumar A','','9080740586','2019-12-01',1,'09:30','18:00','Pending'),(2,1,0,'Sukumar','','9080740586','2019-12-01',1,'09:30','18:00','Pending'),(3,1,0,'vikas','','9738006878','2019-12-06',17,'14:00','17:00','Pending'),(4,1,0,'vikas','','9080740586','2019-12-30',1,'09:30','18:00','Pending'),(5,1,0,'vikas','','9080740586','2019-12-30',1,'09:30','18:00','Pending'),(6,1,3,'cavous','','7902888899','2019-12-29',17,'14:00','17:00','Pending'),(7,1,3,'cavous','','7902888899','2020-01-04',17,'14:00','17:00','Pending'),(8,1,1,'Vikas D','','9738006878','2020-01-03',18,'17:00','21:00','Pending');
/*!40000 ALTER TABLE `online_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_health_parameter`
--

DROP TABLE IF EXISTS `patient_health_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_health_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `param_id` int(11) NOT NULL,
  `param_value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_health_parameter`
--

LOCK TABLES `patient_health_parameter` WRITE;
/*!40000 ALTER TABLE `patient_health_parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_health_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `card_no` varchar(20) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `height` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `bmi` varchar(10) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,1,'204','Vikas D',36,'Male','','9738006878','','','',''),(2,1,'105','Sukumar A',44,'Male','','9080740586','','','',''),(4,1,'07','prem',40,'Male','#1193, 2nd floor, #21, 25th cross, 5th block, HBR Layout,\r\nWard No 24, Bangalore, Karnataka','9343063471','5','','','A+'),(5,1,'0006','Mr. Essa Ali E B Al Naemi',19,'Male','','','','','',''),(6,1,'0007','Mr. Fathima AL Naemi',15,'Female','','','','','','');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rosters`
--

DROP TABLE IF EXISTS `rosters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rosters` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `applicable_from` date DEFAULT NULL,
  `day_no` varchar(50) DEFAULT NULL COMMENT '0,1,2,3,4,5,6',
  `start_time` varchar(5) DEFAULT NULL,
  `end_time` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rosters`
--

LOCK TABLES `rosters` WRITE;
/*!40000 ALTER TABLE `rosters` DISABLE KEYS */;
INSERT INTO `rosters` VALUES (1,1,1,'2019-11-29','1,2,3,4,5,6,0','09:30','18:00'),(2,1,17,'2019-12-06','1,2,3,4,5,6,0','14:00','17:00'),(3,1,19,'2019-12-28','1,2,4,5,0','09:00','13:00'),(4,1,19,'2019-12-28','1,2,4,5,0','16:00','19:00'),(6,1,18,'2020-01-02','1,2,3,4,5,6','17:00','21:00'),(7,1,20,'2020-01-04','1,2,3,4,5,6','11:00','13:00'),(8,1,20,'2020-01-04','1,2,3,4,5,6','17:00','18:00'),(9,1,21,'2020-01-04','1,2,3,4,5,6','11:00','12:00'),(10,1,21,'2020-01-04','1,2,3,4,5,6','17:00','20:00'),(11,1,18,'2020-01-04','1,2,3,4,5,6','09:00','10:30');
/*!40000 ALTER TABLE `rosters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rx_item`
--

DROP TABLE IF EXISTS `rx_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rx_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `visit_id` int(11) DEFAULT NULL,
  `medicine_name` varchar(100) DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `in_take` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rx_item`
--

LOCK TABLES `rx_item` WRITE;
/*!40000 ALTER TABLE `rx_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `rx_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (8,1,'Topical Fluoride Application',625),(14,1,'health check package',5000),(16,1,'Neuro Consultation',750),(17,1,'physiotherapy',1000),(18,1,'Neuro Consultation NRI',1500),(19,1,'Neuro Consultation N',2500),(20,1,'Toxicology Profile N',9800),(21,1,'MRI Brain-Plain',25000),(22,1,'Bedside long duration Electroencefalograma',10000),(23,1,'MRI Brain-Plain & Contrast',25000),(24,1,'MRI Brain-Epilepsy protocol',10000),(25,1,'MRI Brain with MRA (Stroke Protocol)',10000),(26,1,'MRI CV junction',10000),(27,1,'MRI Cervical Spine',15000),(28,1,'MRI Thoracic Spine',10000),(29,1,'MRI LS Spine',10000);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `message` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms`
--

LOCK TABLES `sms` WRITE;
/*!40000 ALTER TABLE `sms` DISABLE KEYS */;
INSERT INTO `sms` VALUES (1,1,'9080740586','Your appointment at SPANDAN TEST TUBE BABY CENTRE with Dr. Dr. Veena Shinde is confirmed for 30-Nov-2019 between 09:30AM and 06:00PM. Your appointment ID is 0'),(2,1,'9080740586','Your appointment at SPANDAN TEST TUBE BABY CENTRE with Dr. Dr. Veena Shinde is confirmed for 30-Nov-2019 between 09:30AM and 06:00PM. Your appointment ID is 1'),(3,1,'9080740586','Your appointment at SPANDAN TEST TUBE BABY CENTRE with Dr. Dr. Veena Shinde is confirmed for 30-Nov-2019 between 09:30AM and 06:00PM. Your appointment ID is 3'),(4,1,'','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 5'),(5,1,'','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 6'),(6,1,'','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 7'),(7,1,'','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 1'),(8,1,'9080740586','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 3'),(9,1,'9080740586','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 1'),(10,1,'9080740586','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 2'),(11,1,'9738006878','Your appointment at Health Care with Dr. Vikas is confirmed for 06-Dec-2019 between 02:00PM and 05:00PM. Your appointment ID is 3'),(12,1,'9738006878','vikas has booked an appointment wth you at  on 06-Dec-2019 between 02:00PM and 05:00PM. The appointment ID is 3'),(13,1,'9080740586','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 30-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 4'),(14,1,'9080740586','Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 30-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 5'),(15,1,'7902888899','Your appointment at BNC Health Center with Dr. Vikas is confirmed for 29-Dec-2019 between 02:00PM and 05:00PM. Your appointment ID is 6'),(16,1,'9738006878','cavous has booked an appointment wth you at  on 29-Dec-2019 between 02:00PM and 05:00PM. The appointment ID is 6'),(17,1,'7902888899','Your appointment at BNC Health Center with Dr. Vikas is confirmed for 04-Jan-2020 between 02:00PM and 05:00PM. Your appointment ID is 7'),(18,1,'9738006878','cavous has booked an appointment wth you at  on 04-Jan-2020 between 02:00PM and 05:00PM. The appointment ID is 7'),(19,1,'9738006878','Your appointment at BNC Health Center with Dr. Dr R Varadarajulu is confirmed for 03-Jan-2020 between 05:00PM and 09:00PM. Your appointment ID is 8'),(20,1,'9880101778','Vikas D has booked an appointment wth you at 38 on 03-Jan-2020 between 05:00PM and 09:00PM. The appointment ID is 8');
/*!40000 ALTER TABLE `sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialization`
--

DROP TABLE IF EXISTS `specialization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialization`
--

LOCK TABLES `specialization` WRITE;
/*!40000 ALTER TABLE `specialization` DISABLE KEYS */;
INSERT INTO `specialization` VALUES (1,'General Medicine'),(2,'Dentist'),(3,'Dietitian'),(4,'Gynecologist'),(5,'Physiotherapist'),(6,'Ophthalmologist'),(7,'Infertility Specialist'),(8,'Psychiatrist'),(9,'Cardiologist'),(10,'Pediatrician'),(11,'Dermatologist');
/*!40000 ALTER TABLE `specialization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_name` varchar(50) DEFAULT NULL,
  `user_clinic_id` int(11) DEFAULT NULL,
  `speciality` int(11) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `role` varchar(500) DEFAULT NULL,
  `domain_name` varchar(500) DEFAULT NULL,
  `api_url` varchar(50) DEFAULT NULL,
  `clinic_token` varchar(500) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `land_line` varchar(20) DEFAULT NULL,
  `address_line1` varchar(100) DEFAULT NULL,
  `address_line2` varchar(100) DEFAULT NULL,
  `address_line3` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `bill_template` varchar(50) DEFAULT NULL,
  `examination_template` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `gstin` varchar(20) DEFAULT NULL,
  `billed` tinyint(1) DEFAULT NULL,
  `premium_amount` int(11) DEFAULT NULL,
  `max_patient` bigint(11) DEFAULT NULL,
  `sms_balance` int(11) NOT NULL DEFAULT '2000',
  `apk_url` varchar(500) DEFAULT NULL,
  `amount_paid` int(11) NOT NULL DEFAULT '0',
  `modules` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (-1,'Administrator',-1,2,'hctsol','hctsol','super','','clinicassist.in/api/','5836d45ea6fffffffffffadfa51c14da08','8778193926','sukumar.inapp@gmail.com','8778193926','132','Nagercoil','Kanyakumari','629001','Active','hct.png','demo.php','demo.php','2018-01-01','22AAAAA0AAAA1Z5',1,15000,20,20,'',0,''),(1,'Bangalore Neuro & Diabetic Centre',1,2,'admin','admin@2','admin','www.bndc.in','clinicassist.in/api/','5836d45ea6571188bbfadfa51c14da08','7902888899','sukumar.inapp@gmail.com','8778193926','No. 4DC/455, 2nd floor, Behind Banaswadi Club,','6A main road, 4D cross, 2nd block,','HRBR Layout, Bangalore','560043','Active','bnc_logo.png','demo.php','demo.php','2018-01-01','22AAAAA0AAAA1Z5',1,15000,20,20,'',0,'patient,appointment,visit,prescription,billing,user'),(2,'Front Office',1,0,'front','front','user','','','','','','','','','','','Active','','','','0000-00-00','',0,0,0,0,'',0,'patient,appointment,visit'),(3,'vikas',1,NULL,'vikas','vikas','user',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2000,NULL,0,'patient,billing'),(4,'pooja',1,NULL,'poojabnc','123456','user',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2000,NULL,0,'patient,appointment,visit,billing');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visit`
--

DROP TABLE IF EXISTS `visit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visit` (
  `visit_id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `treatment` varchar(4000) DEFAULT NULL,
  `remarks` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`visit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visit`
--

LOCK TABLES `visit` WRITE;
/*!40000 ALTER TABLE `visit` DISABLE KEYS */;
/*!40000 ALTER TABLE `visit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-05  7:33:03
