-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 12:30 AM
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
(112, 'Žaidimų kompiuteris', 249.99, 22),
(113, 'Multimedijos kompiuteris', 289.99, 22);

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
(608, 112, 15),
(609, 112, 20),
(610, 112, 23),
(611, 112, 24),
(612, 112, 13),
(613, 112, 21),
(614, 112, 22),
(615, 112, 25),
(616, 112, 27),
(617, 112, 16),
(618, 112, 26),
(619, 113, 14),
(620, 113, 18),
(621, 113, 20),
(622, 113, 23),
(623, 113, 24),
(624, 113, 13),
(625, 113, 21),
(626, 113, 22),
(627, 113, 27),
(628, 113, 16),
(629, 113, 26);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `ordered_by` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `order_status` int(11) NOT NULL,
  `assembly_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `ordered_by`, `total_price`, `order_status`, `assembly_id`) VALUES
(124, 25, 1127, 2, 18),
(125, 25, 1127, 4, 18);

-- --------------------------------------------------------

--
-- Table structure for table `order_states`
--

CREATE TABLE `order_states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_states`
--

INSERT INTO `order_states` (`id`, `name`) VALUES
(1, 'Vykdomas'),
(2, 'Gautas'),
(3, 'Priimtas'),
(4, 'Atšauktas');

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
(41, 'Integruota', 1, 1000, 5),
(43, 'SSD 256GB', 49, 10, 6),
(56, 'Core i5 14600K', 257, 5, 1);

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
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `lastname`, `phone_number`, `email`, `username`, `password`, `role`) VALUES
(12, 'Gytis', 'Pranauskas', '+37069422844', 'worker@gmail.com', 'gpgytis', '$2y$10$leudoEhMvf547SISu6lkueJVdbUBP0VpPWOZk5kCezaXz60zJ7o5q', 2),
(20, 'Jonas', 'Vadybininkas', '+37077777777', 'vadyb@gmail.com', 'vadyb', '$2y$10$ljTXv8kCvn4kndbyDMNIVuz6v2hrgFfxbjdoh3gW/J5WD0wpsfQ9W', 1),
(22, 'Jonas', 'Technikas', '+37077777777', 'techn@gamil.com', 'techn', '$2y$10$8ZU6gDOX15g2FpVcddY62u3pXTMqwi3M6Lp93uE2Q1Vbjbmv0Mjbq', 2),
(23, 'Jonas', 'Vartotojas', '+37077777777', 'vartot@gmail.com', 'vartot', '$2y$10$7Rh2bHW9b34m7mxd1uQdoOgTuc9EX1jwTimNPFgG.A7torZWgkxa2', 3),
(25, 'Gytis', 'Pranauskas', '+37069422844', 'naujasVartot@gmail.com', 'naujasVartot', '$2y$10$azxU8brNSRA3mUjnNIVsfeIP36DNIVIOAfJ5A4BGs0vuQT/5Rs7O.', 3);

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
(18, 25, 'Mano komplektas', 249.99, 112, 13, 15, 16, 20, 21, 23, 25, 26);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_ibfk_1` (`assembly_id`),
  ADD KEY `order_ibfk_2` (`ordered_by`),
  ADD KEY `order_ibfk_3` (`order_status`);

--
-- Indexes for table `order_states`
--
ALTER TABLE `order_states`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `device_parts`
--
ALTER TABLE `device_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=630;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `order_states`
--
ALTER TABLE `order_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user_assembly`
--
ALTER TABLE `user_assembly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`assembly_id`) REFERENCES `user_assembly` (`id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`ordered_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`order_status`) REFERENCES `order_states` (`id`);

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
