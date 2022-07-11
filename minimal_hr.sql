-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2022 at 09:53 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minimal_hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` enum('active','deactive','suspended') NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `password` varchar(100) DEFAULT NULL,
  `recovery_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`employee_id`, `email`, `status`, `is_admin`, `password`, `recovery_token`) VALUES
(11, 'abnetkebede075@gmail.com', 'active', 1, '$2y$10$1b3xwnpaVSj7tNwsV1nH6eztTj6sXxI15VjeKOAVhrkyWfwQEFcKm', NULL),
(12, 'tewodros@gmail.com', 'active', 0, '$2y$10$MSeAR.tnhe4779HT6Eyqc.zyQ7nCXWU8QWtpnV8dCd8ZOVHOvpH9K', NULL),
(17, 'fkraddis@gmail.com', 'active', 0, '$2y$10$9GSEXlnzESEN04Jam14HTulCWWNvqh1/7lsdmW2rlyPdzZo7YKDze', NULL),
(18, 'sharuk075@gmail.com', 'active', 0, '$2y$10$RAWhjPw2SoUNxKqtFC2FeOttOKBwq1dNKwKFa9cwu20Y1V9TOKgIW', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `phone_number` varchar(14) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `sub_city` varchar(50) NOT NULL,
  `place_name` varchar(50) DEFAULT NULL,
  `street_name` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`employee_id`, `phone_number`, `city`, `sub_city`, `place_name`, `street_name`, `id`) VALUES
(11, '0923429834', 'Adama', 'Lugo', 'Kale Hiwet Sefer', 'Urael Street', 2),
(11, '0976342343', 'Addis Ababa', 'Bole', '24 akababi', 'Eritrea Embassy', 4),
(12, '', 'Addis Ababa', 'Bole', '', '', 6),
(15, '', 'Addis Ababa', 'kolfe keranyo', '', '', 9),
(16, '', 'adama', 'gende hara', '', '', 10),
(17, '', 'Addis Ababa', 'Lege tafo lege dadi', '', '', 11),
(18, '+251983475985', 'my city', 'my sub city', 'place name can be empty', 'street name can be empty', 12);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_head` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_head`, `name`) VALUES
(1, 18, 'Information Technology'),
(2, NULL, 'Human Resource'),
(4, NULL, 'StoreDepartment'),
(5, 12, 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `documents` varchar(100) NOT NULL,
  `status` enum('viewed','pending','accepted','rejected') NOT NULL,
  `position` varchar(100) NOT NULL,
  `salary` double UNSIGNED NOT NULL,
  `phone_number` varchar(14) NOT NULL,
  `education_level` enum('ba','bsc','beng','llb','marts','mbiol','mcomp','meng','mmath','mphys','msci','ma','msc','mba','mphil','mres','llm','phd') NOT NULL,
  `employee_department` int(10) UNSIGNED NOT NULL,
  `application_number` int(22) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `full_name`, `email`, `profile_picture`, `documents`, `status`, `position`, `salary`, `phone_number`, `education_level`, `employee_department`, `application_number`) VALUES
(11, 'Abnet Kebede', 'abnetkebede075@gmail.com', 'profile_pic_abnetkebede075@gmail_com.png', 'application_doc_abnetkebede075@gmail_com.zip', 'viewed', 'Juniour Software Engineer', 1500, '+251982353533', 'bsc', 1, 11),
(12, 'Tewodros Kassahun', 'tewodros@gmail.com', 'profile_pic_tewodros@gmail_com.png', 'application_doc_tewodros@gmail_com.zip', 'accepted', 'Recruiter', 5000, '0987234543', 'ba', 2, 12),
(15, 'Shambel Birra', 'shambel@gmail.com', 'profile_pic_shambel@gmail_com.png', 'application_doc_shambel@gmail_com.zip', 'viewed', 'IT Support', 7400, '0973457234', 'beng', 1, 115),
(16, 'Dawit Melese', 'dawit@gmail.com', 'profile_pic_dawit@gmail_com.png', 'application_doc_dawit@gmail_com.zip', 'pending', 'IT Support', 7400, '0973478234', 'beng', 1, 116),
(17, 'Fkraddis Nekatbeb', 'fkraddis@gmail.com', 'profile_pic_fkraddis@gmail_com.png', 'application_doc_fkraddis@gmail_com.zip', 'accepted', 'Secretary', 2996, '0973492345', 'ba', 2, 170),
(18, 'Shahruk Kahan', 'sharuk075@gmail.com', 'profile_pic_sharuk075@gmail_com2.png', 'application_doc_sharuk075@gmail_com2.zip', 'viewed', 'Juniour Software Engineer', 1500, '+251982353533', 'bsc', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `eventdate`
--

CREATE TABLE `eventdate` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `work_start_date` timestamp NULL DEFAULT NULL,
  `prohibition_start_date` timestamp NULL DEFAULT NULL,
  `prohibition_end_date` timestamp NULL DEFAULT NULL,
  `termination_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eventdate`
--

INSERT INTO `eventdate` (`employee_id`, `work_start_date`, `prohibition_start_date`, `prohibition_end_date`, `termination_date`) VALUES
(17, '0000-00-00 00:00:00', NULL, NULL, NULL),
(18, '2022-07-06 05:43:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vacancy`
--

CREATE TABLE `vacancy` (
  `id` int(10) UNSIGNED NOT NULL,
  `position` varchar(100) NOT NULL,
  `salary` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('open','closed') NOT NULL,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='	';

--
-- Dumping data for table `vacancy`
--

INSERT INTO `vacancy` (`id`, `position`, `salary`, `description`, `start_date`, `end_date`, `status`, `updated_by`, `department_id`) VALUES
(1, 'IT Support', 7400, 'IT Support Technician', '2022-06-29 12:51:11', '2022-07-06 22:00:00', 'closed', 11, 1),
(3, 'Store Keeper', 5000, 'you should be good store keeper other wise you will be fired!!!!', '2022-07-08 09:00:00', '2022-07-15 09:00:00', 'open', 11, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD UNIQUE KEY `id_UNIQUE` (`employee_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `employee_address_idx` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_head_UNIQUE` (`department_head`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `application_number_UNIQUE` (`application_number`),
  ADD KEY `emp_department` (`employee_department`);

--
-- Indexes for table `eventdate`
--
ALTER TABLE `eventdate`
  ADD UNIQUE KEY `employee_UNIQUE` (`employee_id`);

--
-- Indexes for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `department_id_UNIQUE` (`department_id`),
  ADD KEY `updated_by_idx` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `employee_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vacancy`
--
ALTER TABLE `vacancy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_owner` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `employee_address` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `depr_head` FOREIGN KEY (`department_head`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `emp_department` FOREIGN KEY (`employee_department`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eventdate`
--
ALTER TABLE `eventdate`
  ADD CONSTRAINT `employee_event` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD CONSTRAINT `updated_by` FOREIGN KEY (`updated_by`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `vacancy_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
