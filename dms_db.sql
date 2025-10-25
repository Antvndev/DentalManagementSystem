-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2025 at 01:42 PM
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
-- Database: `dms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `dental_chart`
--

CREATE TABLE `dental_chart` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `tooth_number` varchar(3) NOT NULL,
  `condition` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dental_chart`
--

INSERT INTO `dental_chart` (`id`, `patient_id`, `tooth_number`, `condition`, `note`, `updated_at`) VALUES
(1, 1, '18', 'caries', NULL, '2025-10-13 13:30:39'),
(2, 1, '17', 'pulpitis', NULL, '2025-10-13 13:30:39'),
(3, 1, '16', 'periodontitis', NULL, '2025-10-13 13:30:39'),
(4, 1, '15', 'periodontitis_gum', NULL, '2025-10-13 13:30:39'),
(5, 1, '14', 'root', NULL, '2025-10-13 13:30:39'),
(6, 1, '13', 'obturate', NULL, '2025-10-13 13:30:39'),
(7, 1, '12', 'implant', NULL, '2025-10-13 13:30:39'),
(8, 1, '11', 'fracture', NULL, '2025-10-13 13:30:39'),
(9, 1, '21', 'missing', NULL, '2025-10-13 13:30:39'),
(10, 1, '22', 'crown', NULL, '2025-10-13 13:30:39'),
(11, 1, '23', 'filling', NULL, '2025-10-13 13:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `egn` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `age` int(3) NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `middle_name`, `last_name`, `egn`, `gender`, `age`, `phone`) VALUES
(1, 'test1', 'test', 'test', '1970022093', 'Male', 34, '1234567890'),
(2, 'test2', 'test', 'test', '1960031232', 'Male', 23, '1234567890'),
(3, 'test3', 'test', 'test', '1939012528', 'Male', 76, '1234567890'),
(4, 'test4', 'test', 'test', '1983050576', 'Female', 35, '1234567890'),
(5, 'test5', 'test', 'test', '1938082666', 'Female', 54, '1234567890'),
(6, 'test6', 'test', 'test', '1960011530', 'Male', 76, '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `visitations`
--

CREATE TABLE `visitations` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL DEFAULT curdate(),
  `tooth_number` varchar(10) DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `treatment` varchar(255) DEFAULT NULL,
  `procedure_used` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visitations`
--

INSERT INTO `visitations` (`id`, `patient_id`, `visit_date`, `tooth_number`, `diagnosis`, `treatment`, `procedure_used`, `notes`, `created_at`) VALUES
(1, 1, '2025-10-13', '38', 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.', 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.', 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.', 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.', '2025-10-13 13:32:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dental_chart`
--
ALTER TABLE `dental_chart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dental_patient` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitations`
--
ALTER TABLE `visitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_visitations_patient` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dental_chart`
--
ALTER TABLE `dental_chart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `visitations`
--
ALTER TABLE `visitations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dental_chart`
--
ALTER TABLE `dental_chart`
  ADD CONSTRAINT `fk_dental_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visitations`
--
ALTER TABLE `visitations`
  ADD CONSTRAINT `fk_visitations_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
