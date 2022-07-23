-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2020 at 04:50 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `billnum` int(11) NOT NULL,
  `bill_date` date NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `clinic_id`, `billnum`, `bill_date`, `patient_id`, `doctor_id`, `total`, `net_amount`, `discount_amount`) VALUES
(18, 1, 1, '2019-12-29', 1, 0, '5000.00', '5000.00', '0.00'),
(19, 1, 2, '2019-12-29', 1, 0, '3850.00', '3850.00', '0.00'),
(20, 1, 3, '2020-01-19', 1, 0, '3475.00', '3475.00', '0.00'),
(21, 1, 4, '2020-01-20', 2, 0, '575.00', '575.00', '0.00'),
(22, 1, 5, '2020-01-20', 2, 0, '3475.00', '3475.00', '0.00'),
(23, 1, 6, '2020-01-20', 2, 0, '375.00', '375.00', '0.00'),
(24, 1, 7, '2020-02-05', 4, 1, '6950.00', '6950.00', '0.00'),
(25, 1, 8, '2020-02-05', 2, 0, '8475.00', '8128.00', '0.00'),
(26, 1, 9, '2020-06-10', 2, 0, '3475.00', '3475.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `clinic_id`, `bill_id`, `item_id`, `quantity`, `amount`, `discount`) VALUES
(19, 1, 18, 14, 1, '5000.00', '0.00'),
(20, 1, 19, 3, 1, '3475.00', '0.00'),
(21, 1, 19, 9, 1, '375.00', '0.00'),
(22, 1, 20, 3, 1, '3475.00', '0.00'),
(23, 1, 21, 7, 1, '575.00', '0.00'),
(24, 1, 22, 3, 1, '3475.00', '0.00'),
(25, 1, 23, 9, 1, '375.00', '0.00'),
(26, 1, 24, 3, 1, '3475.00', '0.00'),
(27, 1, 24, 3, 1, '3475.00', '0.00'),
(28, 1, 25, 14, 1, '5000.00', '0.00'),
(29, 1, 25, 3, 1, '3475.00', '347.50'),
(30, 1, 26, 3, 1, '3475.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `doctor_token` varchar(500) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `experience` varchar(500) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `clinic_id`, `doctor_token`, `full_name`, `mobile`, `gender`, `qualification`, `experience`, `photo`, `status`) VALUES
(1, 1, 'd97fba7fa9e1143dec13f54bd44bc4a9', 'Dr. Veena Shinde', '', 'Female', 'MBBS, MD', '', '1.jpg', 'Active'),
(15, 2, 'a5f2edf991bbcdb9a495df1bcfb36977', 'Sukumar', NULL, 'Male', 'MBBS', '', '15.jpg', 'Active'),
(16, 2, '841314f3e539620185fff3e356bcd93d', 'Sathish', NULL, 'Male', 'MD', '', 'm.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `param_name` varchar(50) DEFAULT NULL,
  `param_value` varchar(50000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `health_parameter`
--

CREATE TABLE `health_parameter` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  `param_name` varchar(50) NOT NULL,
  `first_time` int(11) NOT NULL,
  `input_type` varchar(20) NOT NULL,
  `input_value` varchar(500) NOT NULL,
  `speciality` int(11) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `holiday`
--

INSERT INTO `holiday` (`id`, `clinic_id`, `date`, `description`) VALUES
(2, 1, '2018-12-25', 'Christmas'),
(3, 2, '2019-12-25', 'Christmas');

-- --------------------------------------------------------

--
-- Table structure for table `leave`
--

CREATE TABLE `leave` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `online_appointments`
--

CREATE TABLE `online_appointments` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `online_appointments`
--

INSERT INTO `online_appointments` (`id`, `clinic_id`, `patient_id`, `full_name`, `email`, `mobile`, `date`, `doctor_id`, `start_time`, `end_time`, `status`) VALUES
(1, 1, 1, 'aaa', '', '9080740586', '2019-11-30', 1, '09:30', '18:00', 'Pending'),
(2, 1, 2, 'aaaaaaaaaaaa', '', '', '2019-11-30', 1, '09:30', '18:00', 'Pending'),
(3, 1, 0, '9080740586', '', '9080740586', '2019-11-30', 1, '09:30', '18:00', 'Pending'),
(4, 1, 2, 'aaaaaaaaaaaa', '', '', '2019-11-30', 1, '09:30', '18:00', 'Pending'),
(5, 1, 0, 'Sukumar', '', '9080740586', '2019-12-01', 1, '09:30', '18:00', 'Pending'),
(6, 1, 2, 'aaaaaaaaaaaa', '', '', '2019-12-01', 1, '09:30', '18:00', 'Pending'),
(7, 1, 0, 'Sukumar', '', '9080740586', '2019-12-01', 1, '09:30', '18:00', 'Pending'),
(8, 1, 0, 'Sukumar', '', '9080740586', '2019-12-01', 1, '09:30', '18:00', 'Pending'),
(9, 1, 0, 'Sukumar', '', '9080740586', '2019-12-01', 1, '09:30', '18:00', 'Pending'),
(10, 1, 2, 'Binu', '', '', '2019-12-29', 1, '09:30', '18:00', 'Pending'),
(11, 1, 2, 'Binu', '', '', '2019-12-29', 1, '09:30', '18:00', 'Pending'),
(12, 1, 2, 'Binu', '', '', '2019-12-29', 1, '09:30', '18:00', 'Pending'),
(13, 1, 2, 'Binu', '', '9738006878', '2019-12-29', 1, '09:30', '18:00', 'Pending'),
(14, 1, 0, 'Sukumar', '', '8778193926', '2020-01-01', 1, '09:30', '18:00', 'Pending'),
(15, 1, 0, 'Sukumar', '', '9738006878', '2020-01-01', 1, '09:30', '18:00', 'Pending'),
(16, 1, 0, 'Sukumar', '', '9738006878', '2020-01-01', 1, '09:30', '18:00', 'Pending'),
(17, 1, 2, 'Binu', '', '7904464598', '2020-02-01', 1, '09:30', '18:00', 'Pending'),
(18, 1, 2, 'Binu', '', '9738006878', '2020-02-05', 1, '09:30', '18:00', 'Pending'),
(19, 1, 2, 'Binu', '', '9738006878', '2020-02-05', 1, '09:30', '18:00', 'Pending'),
(20, 1, 1, 'Raju', '', '8778193926', '2020-02-05', 1, '09:30', '18:00', 'Pending'),
(21, 1, 2, 'Binu', '', '8778193926', '2020-02-12', 1, '09:30', '18:00', 'Pending'),
(22, 1, 2, 'Binu', '', '9738006878', '2020-06-10', 1, '03:15', '15:15', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
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
  `nationality` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `clinic_id`, `card_no`, `full_name`, `age`, `sex`, `address`, `mobile`, `height`, `weight`, `bmi`, `blood_group`, `nationality`) VALUES
(1, 1, '11', 'Raju', 11, 'Male', '', '8778193926', '', '', '', '', 'Indian'),
(2, 1, '111', 'Binu', 11, 'Male', '', '9738006878', '', '', '', '', 'Indian'),
(3, 1, '111', 'qqq', 11, 'Male', '', '', '', '', '', '', 'Indian'),
(4, 1, '00003', 'erqrew', 424, 'Male', '', 'ereww', '', '', '', '', 'Indian');

-- --------------------------------------------------------

--
-- Table structure for table `patient_health_parameter`
--

CREATE TABLE `patient_health_parameter` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `param_id` int(11) NOT NULL,
  `param_value` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rosters`
--

CREATE TABLE `rosters` (
  `id` int(10) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `applicable_from` date DEFAULT NULL,
  `day_no` varchar(50) DEFAULT NULL COMMENT '0,1,2,3,4,5,6',
  `start_time` varchar(5) DEFAULT NULL,
  `end_time` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rosters`
--

INSERT INTO `rosters` (`id`, `clinic_id`, `doctor_id`, `applicable_from`, `day_no`, `start_time`, `end_time`) VALUES
(1, 1, 1, '2019-11-29', '1,2,3,4,5,6,0', '09:30', '18:00'),
(2, 1, 1, '2020-02-01', '1,2', '10:00', '12:00'),
(3, 1, 1, '2020-02-01', '1,2', '17:00', '20:00'),
(4, 1, 1, '2020-06-10', '1,2,3,4,5,6,0', '03:15', '15:15');

-- --------------------------------------------------------

--
-- Table structure for table `rx_item`
--

CREATE TABLE `rx_item` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `visit_id` int(11) DEFAULT NULL,
  `medicine_name` varchar(100) DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `in_take` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `clinic_id`, `description`, `doctor_id`, `code`, `service_type`, `nationality`, `price`) VALUES
(3, 1, 'Root Canal Treatment', NULL, NULL, 'Diagnosis', 'Indian', 3475),
(5, 1, 'GIC filling', NULL, NULL, 'Diagnosis', 'Indian', 525),
(6, 1, 'Teeth Cleaning', NULL, NULL, 'Diagnosis', 'Indian', 525),
(7, 1, 'Root pieces Extraction', NULL, NULL, 'Diagnosis', 'Indian', 575),
(8, 1, 'Topical Fluoride Application', NULL, NULL, 'Diagnosis', 'Indian', 625),
(9, 1, 'Tooth Extraction', NULL, NULL, 'Diagnosis', 'Indian', 375),
(12, 1, 'Crown', NULL, NULL, 'Diagnosis', 'Indian', 6375),
(13, 1, 'ortho consultation', NULL, NULL, 'Diagnosis', 'Indian', 50),
(14, 1, 'health check package', NULL, NULL, 'Diagnosis', 'Indian', 5000),
(15, 1, 'CBC', NULL, NULL, 'Diagnosis', 'Indian', 480),
(16, 1, 'blood', 0, '4555', 'Diagnosis', 'NRI', 500),
(17, 1, 'ghfdhfd', 0, '454', 'Diagnosis', 'Indian', 56),
(18, 1, '444', 0, '444', 'Diagnosis', 'Indian', 444),
(19, 1, '555', 1, '', 'Consultation', 'Indian', 555),
(20, 1, 'aaa', 0, 'aaa', 'Diagnosis', 'Indian', 111),
(21, 1, 'ewrer', 0, '666', 'Diagnosis', 'Indian', 34),
(22, 1, 'erewr', 1, '', 'Consultation', 'Indian', 4324),
(23, 1, 'Test Lab Blood', 0, '500', 'Diagnosis', 'Indian', 800);

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms`
--

INSERT INTO `sms` (`id`, `clinic_id`, `mobile`, `message`) VALUES
(1, 1, '9080740586', 'Your appointment at SPANDAN TEST TUBE BABY CENTRE with Dr. Dr. Veena Shinde is confirmed for 30-Nov-2019 between 09:30AM and 06:00PM. Your appointment ID is 0'),
(2, 1, '9080740586', 'Your appointment at SPANDAN TEST TUBE BABY CENTRE with Dr. Dr. Veena Shinde is confirmed for 30-Nov-2019 between 09:30AM and 06:00PM. Your appointment ID is 1'),
(3, 1, '9080740586', 'Your appointment at SPANDAN TEST TUBE BABY CENTRE with Dr. Dr. Veena Shinde is confirmed for 30-Nov-2019 between 09:30AM and 06:00PM. Your appointment ID is 3'),
(4, 1, '9080740586', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 5'),
(5, 1, '9080740586', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 7'),
(6, 1, '9080740586', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 8'),
(7, 1, '9080740586', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 9'),
(8, 1, '9738006878', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 29-Dec-2019 between 09:30AM and 06:00PM. Your appointment ID is 13'),
(9, 1, '8778193926', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Jan-2020 between 09:30AM and 06:00PM. Your appointment ID is 14'),
(10, 1, '9738006878', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Jan-2020 between 09:30AM and 06:00PM. Your appointment ID is 15'),
(11, 1, '9738006878', 'Your appointment at Health Care with Dr. Dr. Veena Shinde is confirmed for 01-Jan-2020 between 09:30AM and 06:00PM. Your appointment ID is 16'),
(12, 1, '7904464598', 'Your appointment at BNC with Dr. Dr. Veena Shinde is confirmed for 01-Feb-2020 between 09:30AM and 06:00PM. Your appointment ID is 17'),
(13, 1, '9738006878', 'Your appointment at BNC with Dr. Dr. Veena Shinde is confirmed for 05-Feb-2020 between 09:30AM and 06:00PM. Your appointment ID is 18'),
(14, 1, '9738006878', 'Your appointment at BNC with Dr. Dr. Veena Shinde is confirmed for 05-Feb-2020 between 09:30AM and 06:00PM. Your appointment ID is 19'),
(15, 1, '8778193926', 'Your appointment at BNC with Dr. Dr. Veena Shinde is confirmed for 05-Feb-2020 between 09:30AM and 06:00PM. Your appointment ID is 20'),
(16, 1, '8778193926', 'Your appointment at BNC with Dr. Dr. Veena Shinde is confirmed for 12-Feb-2020 between 09:30AM and 06:00PM. Your appointment ID is 21'),
(17, 1, '9738006878', 'Your appointment at BNC with Dr. Dr. Veena Shinde is confirmed for 10-Jun-2020 between 03:15AM and 03:15PM. Your appointment ID is 22');

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`id`, `description`) VALUES
(1, 'General Medicine'),
(2, 'Dentist'),
(3, 'Dietitian'),
(4, 'Gynecologist'),
(5, 'Physiotherapist'),
(6, 'Ophthalmologist'),
(7, 'Infertility Specialist'),
(8, 'Psychiatrist'),
(9, 'Cardiologist'),
(10, 'Pediatrician'),
(11, 'Dermatologist');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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
  `sms_balance` int(11) NOT NULL DEFAULT 2000,
  `apk_url` varchar(500) DEFAULT NULL,
  `amount_paid` int(11) NOT NULL DEFAULT 0,
  `modules` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `clinic_name`, `user_clinic_id`, `speciality`, `user_name`, `password`, `role`, `domain_name`, `api_url`, `clinic_token`, `mobile`, `email`, `land_line`, `address_line1`, `address_line2`, `address_line3`, `pincode`, `status`, `logo`, `bill_template`, `examination_template`, `start_date`, `gstin`, `billed`, `premium_amount`, `max_patient`, `sms_balance`, `apk_url`, `amount_paid`, `modules`) VALUES
(-1, 'Administrator', -1, 2, 'hctsol', 'hctsol', 'super', '', 'clinicassist.in/api/', '5836d45ea6fffffffffffadfa51c14da08', '8778193926', 'sukumar.inapp@gmail.com', '8778193926', '132', 'Nagercoil', 'Kanyakumari', '629001', 'Active', 'hct.png', 'demo.php', 'demo.php', '2018-01-01', '22AAAAA0AAAA1Z5', 1, 15000, 20, 20, '', 0, ''),
(1, 'BNC', 1, 2, 'admin', 'admin', 'admin', 'www.bnc.com', 'clinicassist.in/api/', '5836d45ea6571188bbfadfa51c14da08', '8888888888', 'sukumar.inapp@gmail.com', '8778193926', '', 'Mumbai', 'Maharashtra', '23424', 'Active', 'hct.png', 'demo.php', 'demo.php', '2018-01-01', '22AAAAA0AAAA1Z5', 1, 15000, 20, 20, '', 0, 'patient,appointment,visit,prescription,billing,user,delete_bill,services,broadcast,holiday'),
(2, 'Front Office', 1, 0, 'front', 'front', 'user', '', '', '', '', '', '', '', '', '', '', 'Active', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', 0, 'patient,appointment,visit'),
(3, 'aaa', 1, NULL, '111', '111', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2000, NULL, 0, 'patient,appointment'),
(4, 'aa', 1, NULL, 'aa', 'aa', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2000, NULL, 0, 'patient,appointment,billing,delete_bill'),
(6, 'bb', 1, NULL, 'bb', 'bb', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2000, NULL, 0, 'patient,appointment,billing,delete_bill'),
(7, 'vikas', 1, NULL, 'vikas', 'vikas', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2000, NULL, 0, 'patient,appointment,billing'),
(8, 'suku', 1, NULL, 'suku', 'suku', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2000, NULL, 0, 'patient,appointment,billing,delete_bill');

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `visit_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `treatment` varchar(4000) DEFAULT NULL,
  `remarks` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`visit_id`, `clinic_id`, `patient_id`, `doctor_id`, `visit_date`, `treatment`, `remarks`) VALUES
(1, 1, 2, 1, '2019-12-28', 'erew', 'ewrew');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `health_parameter`
--
ALTER TABLE `health_parameter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave`
--
ALTER TABLE `leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_appointments`
--
ALTER TABLE `online_appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_health_parameter`
--
ALTER TABLE `patient_health_parameter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rosters`
--
ALTER TABLE `rosters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rx_item`
--
ALTER TABLE `rx_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`visit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `health_parameter`
--
ALTER TABLE `health_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leave`
--
ALTER TABLE `leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `online_appointments`
--
ALTER TABLE `online_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patient_health_parameter`
--
ALTER TABLE `patient_health_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rosters`
--
ALTER TABLE `rosters`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rx_item`
--
ALTER TABLE `rx_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
