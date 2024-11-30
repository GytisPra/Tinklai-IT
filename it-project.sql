-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 04:12 PM
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
-- Database: `it-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `computer_types`
--

CREATE TABLE `computer_types` (
  `id_computer_types` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `computer_types`
--

INSERT INTO `computer_types` (`id_computer_types`, `name`) VALUES
(1, 'Darbinis kompiuteris'),
(2, 'Žaidimų kompiuteris');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `construction_cost` double NOT NULL,
  `fk_created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `name`, `construction_cost`, `fk_created_by`) VALUES
(107, 'Darbinis kompiuteris', 100.5, 22),
(108, 'VISKAS', 200, 22),
(109, 'Dar vienas darbinis kompas', 200, 22);

-- --------------------------------------------------------

--
-- Table structure for table `device_parts`
--

CREATE TABLE `device_parts` (
  `id` int(11) NOT NULL,
  `fk_device_id` int(11) NOT NULL,
  `fk_part_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `device_parts`
--

INSERT INTO `device_parts` (`id`, `fk_device_id`, `fk_part_id`) VALUES
(497, 107, 14),
(498, 107, 18),
(499, 107, 23),
(500, 107, 13),
(501, 107, 22),
(502, 107, 41),
(503, 107, 25),
(504, 107, 16),
(505, 107, 26),
(506, 108, 14),
(507, 108, 15),
(508, 108, 17),
(509, 108, 18),
(510, 108, 19),
(511, 108, 20),
(512, 108, 23),
(513, 108, 24),
(514, 108, 13),
(515, 108, 21),
(516, 108, 22),
(517, 108, 41),
(518, 108, 25),
(519, 108, 27),
(520, 108, 16),
(521, 108, 26),
(522, 109, 14),
(523, 109, 17),
(524, 109, 23),
(525, 109, 13),
(526, 109, 41),
(527, 109, 25),
(528, 109, 16),
(529, 109, 26);

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

CREATE TABLE `part` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `left_in_storage` int(11) NOT NULL,
  `part_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `part`
--

INSERT INTO `part` (`id`, `name`, `price`, `left_in_storage`, `part_type`) VALUES
(13, 'Core i7 14790F', 13, 13, 1),
(14, 'Gigabyte GA-A320M-S2H', 15, 14, 2),
(15, 'Asus Z170 Pro Gaming', 18, 17, 2),
(16, 'LCD FHD 1080p', 200, 11, 3),
(17, '8GB DDR4', 50, 14, 4),
(18, '16GB DDR5', 1113, 13, 4),
(19, '8GB DDR5', 550, 17, 4),
(20, '16GB DDR5', 442, 2, 4),
(21, 'GeForce RTX 4080', 14, 1, 5),
(22, 'GeForce RTX 4090', 178, 5, 5),
(23, 'Crucial T705 2TB', 180, 4, 6),
(24, 'MSI SPATIUM M580 FROZR 2TB', 60, 3, 6),
(25, 'Oras', 80, 8, 7),
(26, 'Windows 11', 180, 9, 8),
(27, 'Skystis', 90, 10, 7),
(41, 'Integruota', 1, 1000, 5);

-- --------------------------------------------------------

--
-- Table structure for table `part_types`
--

CREATE TABLE `part_types` (
  `id_part_types` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `part_types`
--

INSERT INTO `part_types` (`id_part_types`, `name`) VALUES
(1, 'processor'),
(2, 'motherboard'),
(3, 'screen'),
(4, 'memory'),
(5, 'graphics_card'),
(6, 'storage'),
(7, 'cooling'),
(8, 'os');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_role` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_role`, `name`) VALUES
(1, 'admin'),
(2, 'technician'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `createdAt` date NOT NULL,
  `updatedAt` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `lastname`, `phone_number`, `email`, `username`, `password`, `role`, `createdAt`, `updatedAt`) VALUES
(12, 'Gytis', 'Pranauskas', '+37069422844', 'worker@gmail.com', 'gpgytis', '$2y$10$leudoEhMvf547SISu6lkueJVdbUBP0VpPWOZk5kCezaXz60zJ7o5q', 2, '2024-11-19', '2024-11-19'),
(20, 'Jonas', 'Vadybininkas', '+37077777777', 'vadyb@gmail.com', 'vadyb', '$2y$10$ljTXv8kCvn4kndbyDMNIVuz6v2hrgFfxbjdoh3gW/J5WD0wpsfQ9W', 1, '2024-11-27', '2024-11-27'),
(22, 'Jonas', 'Technikas', '+37077777777', 'techn@gamil.com', 'techn', '$2y$10$8ZU6gDOX15g2FpVcddY62u3pXTMqwi3M6Lp93uE2Q1Vbjbmv0Mjbq', 2, '2024-11-27', '2024-11-27'),
(23, 'Jonas', 'Vartotojas', '+37077777777', 'vartot@gmail.com', 'vartot', '$2y$10$7Rh2bHW9b34m7mxd1uQdoOgTuc9EX1jwTimNPFgG.A7torZWgkxa2', 3, '2024-11-27', '2024-11-27'),
(24, 'Gytis', 'Pranauskas', '+37069422844', 'vartot@gmail.com', 'vartot', '$2y$10$oHsUCYracq9pbEcowxMfaeJ8wN8gQxwExZgK.m4lGqLCQMHHEct3K', 3, '2024-11-28', '2024-11-28');

-- --------------------------------------------------------

--
-- Table structure for table `user_assembly`
--

CREATE TABLE `user_assembly` (
  `id` int(11) NOT NULL,
  `fk_belongs_to` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `device_id` int(11) NOT NULL,
  `processor_id` int(11) NOT NULL,
  `motherboard_id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  `memory_id` int(11) NOT NULL,
  `graphics_card_id` int(11) NOT NULL,
  `storage_id` int(11) NOT NULL,
  `cooling_id` int(11) NOT NULL,
  `os_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_assembly`
--

INSERT INTO `user_assembly` (`id`, `fk_belongs_to`, `name`, `price`, `device_id`, `processor_id`, `motherboard_id`, `screen_id`, `memory_id`, `graphics_card_id`, `storage_id`, `cooling_id`, `os_id`) VALUES
(10, 23, 'Mano darbinis kompiuteris', 100.5, 107, 13, 14, 16, 18, 41, 23, 25, 26),
(11, 23, 'Darbinis kompiuteris', 100.5, 107, 13, 14, 16, 18, 22, 23, 25, 26),
(12, 23, 'Darbinis kompiuteris', 100.5, 107, 13, 14, 16, 18, 22, 23, 25, 26),
(13, 23, 'VISKAS', 200, 108, 13, 15, 16, 18, 41, 23, 25, 26),
(14, 23, 'a', 100.5, 107, 13, 14, 16, 18, 22, 23, 25, 26);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `computer_types`
--
ALTER TABLE `computer_types`
  ADD PRIMARY KEY (`id_computer_types`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_ibfk_1` (`fk_created_by`);

--
-- Indexes for table `device_parts`
--
ALTER TABLE `device_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_device_id` (`fk_device_id`),
  ADD KEY `fk_part_id` (`fk_part_id`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_ibfk_1` (`part_type`);

--
-- Indexes for table `part_types`
--
ALTER TABLE `part_types`
  ADD PRIMARY KEY (`id_part_types`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ibfk_1` (`role`);

--
-- Indexes for table `user_assembly`
--
ALTER TABLE `user_assembly`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assembly_ibfk_1` (`fk_belongs_to`),
  ADD KEY `assembly_ibfk_2` (`cooling_id`),
  ADD KEY `assembly_ibfk_3` (`graphics_card_id`),
  ADD KEY `assembly_ibfk_4` (`memory_id`),
  ADD KEY `assembly_ibfk_5` (`motherboard_id`),
  ADD KEY `assembly_ibfk_6` (`os_id`),
  ADD KEY `assembly_ibfk_7` (`processor_id`),
  ADD KEY `assembly_ibfk_8` (`screen_id`),
  ADD KEY `assembly_ibfk_9` (`storage_id`),
  ADD KEY `assembly_ibfk_10` (`device_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `computer_types`
--
ALTER TABLE `computer_types`
  MODIFY `id_computer_types` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `device_parts`
--
ALTER TABLE `device_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=530;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `part_types`
--
ALTER TABLE `part_types`
  MODIFY `id_part_types` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_assembly`
--
ALTER TABLE `user_assembly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `device`
--
ALTER TABLE `device`
  ADD CONSTRAINT `device_ibfk_1` FOREIGN KEY (`fk_created_by`) REFERENCES `user` (`id`);

--
-- Constraints for table `device_parts`
--
ALTER TABLE `device_parts`
  ADD CONSTRAINT `device_parts_ibfk_1` FOREIGN KEY (`fk_device_id`) REFERENCES `device` (`id`),
  ADD CONSTRAINT `device_parts_ibfk_2` FOREIGN KEY (`fk_part_id`) REFERENCES `part` (`id`);

--
-- Constraints for table `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_1` FOREIGN KEY (`part_type`) REFERENCES `part_types` (`id_part_types`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id_role`);

--
-- Constraints for table `user_assembly`
--
ALTER TABLE `user_assembly`
  ADD CONSTRAINT `assembly_ibfk_1` FOREIGN KEY (`fk_belongs_to`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `assembly_ibfk_10` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`),
  ADD CONSTRAINT `assembly_ibfk_2` FOREIGN KEY (`cooling_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_3` FOREIGN KEY (`graphics_card_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_4` FOREIGN KEY (`memory_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_5` FOREIGN KEY (`motherboard_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_6` FOREIGN KEY (`os_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_7` FOREIGN KEY (`processor_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_8` FOREIGN KEY (`screen_id`) REFERENCES `part` (`id`),
  ADD CONSTRAINT `assembly_ibfk_9` FOREIGN KEY (`storage_id`) REFERENCES `part` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
