-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 14, 2026 at 05:16 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qr_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$ORd.eWoCnU/o2PRTtinw.eloDd6tirHll8bSpqscU6fTxOcIgoZMG');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(20) DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `date`, `time`, `status`) VALUES
(3, 'AUMC-SHS-TIC-67-6969', '2026-07-10', '12:04:50', 'Present'),
(4, 'AUMC-SHS-TIC-67-6969', '2026-07-11', '06:09:52', 'Present'),
(5, 'AUMC-SHS-TIC-111-6969', '2026-07-11', '06:32:33', 'Present'),
(6, 'AUMC-SHS-TIC-11-555-111', '2026-07-13', '03:09:03', 'Present'),
(7, 'AUMC-SHS-TIC-67-6969', '2026-07-13', '03:09:46', 'Present'),
(8, 'AUMC-SHS-TIC-111-6969', '2026-07-13', '03:09:58', 'Present'),
(9, 'AUMC-SHS-TIC-25-00919', '2026-07-14', '02:09:11', 'Present'),
(10, 'AUMC-SHS-TIC-111-6969', '2026-07-14', '02:09:32', 'Present'),
(11, 'Test', '2026-07-14', '02:10:50', 'Present'),
(12, 'AUMC-SHS-TIC-11-555-111', '2026-07-14', '02:11:57', 'Present'),
(13, 'AUMC-SHS-TIC-67-6969', '2026-07-14', '02:13:40', 'Present'),
(14, 'Test2', '2026-07-14', '02:17:26', 'Present'),
(15, 'Test4', '2026-07-14', '03:11:01', 'Present'),
(16, 'TANDA', '2026-07-16', '09:47:40', 'Present'),
(17, 'AUMC-SHS-TIC-111-6969', '2026-10-10', '05:56:21', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(30) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `section` varchar(50) NOT NULL,
  `strand` varchar(50) DEFAULT NULL,
  `qr_value` varchar(100) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `full_name`, `grade`, `section`, `strand`, `qr_value`, `date_created`) VALUES
('AUMC-SHS-TIC-11-555-111', 'John Carlo Rodriguez', '12', 'B', 'ICT', 'STU-AUMC-SHS-TIC-11-555-111', '2026-07-13 09:08:13'),
('AUMC-SHS-TIC-111-6969', 'Josen Niel O Samuele', '12', 'B', 'ICT', 'STU-AUMC-SHS-TIC-111-6969', '2026-07-11 12:30:39'),
('AUMC-SHS-TIC-25-00919', 'Brix Petes Deps', '11', 'B', 'ABM', 'STU-AUMC-SHS-TIC-25-00919', '2026-07-14 08:08:35'),
('AUMC-SHS-TIC-67-6969', 'Test Student', '12', 'B', 'ICT', 'STU-AUMC-SHS-TIC-67-6969', '2026-07-10 18:03:20'),
('TANDA', 'Tan Lororo', '13', 'B', 'ABM', 'STU-TANDA', '2026-07-16 15:47:12'),
('Test', 'Cerro', '12', 'B', 'Stem', 'STU-Test', '2026-07-14 08:10:22'),
('Test2', 'Test 3', '12', 'B', 'ICT', 'STU-Test2', '2026-07-14 08:17:02'),
('Test4', 'Darwin', '12', 'B', 'ICT', 'STU-Test4', '2026-07-14 09:10:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `unique_attendance_per_day` (`student_id`,`date`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `qr_value` (`qr_value`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
