-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 03:24 PM
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
(112, 'Žaidimų kompiuteris', 299.99, 22),
(113, 'Multimedijos kompiuteris', 289.99, 22),
(114, 'Darbo kompiuteris', 299.59, 22);

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
(705, 112, 14),
(706, 112, 15),
(707, 112, 18),
(708, 112, 20),
(709, 112, 74),
(710, 112, 75),
(711, 112, 56),
(712, 112, 57),
(713, 112, 21),
(714, 112, 22),
(715, 112, 70),
(716, 112, 71),
(717, 112, 72),
(718, 112, 25),
(719, 112, 27),
(720, 112, 16),
(721, 112, 63),
(722, 112, 26),
(723, 114, 62),
(724, 114, 17),
(725, 114, 20),
(726, 114, 75),
(727, 114, 77),
(728, 114, 13),
(729, 114, 56),
(730, 114, 41),
(731, 114, 72),
(732, 114, 73),
(733, 114, 25),
(734, 114, 63),
(735, 114, 76),
(736, 114, 26),
(737, 114, 64),
(738, 113, 14),
(739, 113, 61),
(740, 113, 18),
(741, 113, 20),
(742, 113, 23),
(743, 113, 24),
(744, 113, 74),
(745, 113, 13),
(746, 113, 56),
(747, 113, 21),
(748, 113, 22),
(749, 113, 70),
(750, 113, 25),
(751, 113, 27),
(752, 113, 16),
(753, 113, 60),
(754, 113, 26),
(755, 113, 69);

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
(125, 25, 1127, 4, 18),
(126, 23, 1127, 4, 19),
(127, 23, 2830, 2, 19),
(128, 23, 938, 2, 20),
(129, 23, 715, 2, 21);

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
  `price` double(10,2) NOT NULL,
  `left_in_storage` int(11) NOT NULL,
  `part_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `part`
--

INSERT INTO `part` (`id`, `name`, `price`, `left_in_storage`, `part_type`) VALUES
(13, 'Core i7 14790F', 625.00, 13, 1),
(14, 'Gigabyte GA-A320M-S2H', 105.54, 14, 2),
(15, 'Asus Z170 Pro Gaming', 158.59, 17, 2),
(16, 'ASUS ROG Swift Pro PG248QP 180 Hz', 218.90, 11, 3),
(17, '8GB DDR4', 17.99, 14, 4),
(18, '16GB DDR5', 58.99, 13, 4),
(19, '8GB DDR5', 29.99, 17, 4),
(20, '16GB DDR4', 35.50, 2, 4),
(21, 'GeForce RTX 4080 16GB DDR6', 1190.90, 1, 5),
(22, 'GeForce RTX 4090 24GB DDR6', 2260.20, 5, 5),
(23, 'Crucial T705 2TB', 380.13, 4, 6),
(24, 'MSI SPATIUM M580 FROZR 2TB', 331.31, 3, 6),
(25, 'Oras', 81.00, 8, 7),
(26, 'Windows 11', 139.99, 9, 8),
(27, 'Skystis', 350.59, 10, 7),
(41, 'Integruota', 0.00, 1000, 5),
(56, 'Core i5 14600K', 257.88, 5, 1),
(57, 'AMD Ryzen 7 5800X', 188.00, 15, 1),
(58, 'AMD Ryzen 5 5500', 77.00, 15, 1),
(59, 'Core i3 13100F', 86.00, 9, 1),
(60, 'AOC Q27B3MA 27\' VA, QHD', 149.00, 9, 3),
(61, 'MSI PRO H610M-G', 93.00, 10, 2),
(62, 'GIGABYTE Intel H470 Express LGA1200', 63.36, 10, 2),
(63, 'AOC Gaming 24G4XE 23.8\"', 108.00, 10, 3),
(64, 'Windows 10', 100.55, 10, 8),
(65, 'Ubuntu', 0.00, 1000, 8),
(69, 'macOS', 50.99, 1000, 8),
(70, 'GeForce RTX 3070 8GB DDR6', 435.55, 15, 5),
(71, 'AMD Radeon RX 7900 XTX 24GB GDDR6', 939.19, 15, 5),
(72, 'RTX 4060 8GB DDR6', 201.59, 15, 5),
(73, 'MSI GeForce RTX 3050  6GB DDR6', 179.08, 15, 5),
(74, 'Samsung SSD 980 M.2 1TB', 83.56, 10, 6),
(75, 'Gigabyte Gen4 4000E M.2 1TB', 77.99, 10, 6),
(76, 'Samsung Odyssey G5 165hz', 177.11, 10, 3),
(77, 'Dahua C900 M.2 512GB', 47.11, 10, 6);

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
(18, 25, 'Mano komplektas', 249.99, 112, 13, 15, 16, 20, 21, 23, 25, 26),
(19, 23, 'Mano komplektas', 249.99, 112, 13, 15, 16, 20, 21, 23, 25, 26),
(20, 23, 'Mano žaidimų kompiuteris', 299.99, 112, 57, 14, 63, 20, 72, 75, 25, 26),
(21, 23, 'Mano darbo kompiuteris', 299.59, 114, 56, 62, 63, 17, 41, 77, 25, 26);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `device_parts`
--
ALTER TABLE `device_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=756;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `order_states`
--
ALTER TABLE `order_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
