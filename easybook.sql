-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2024 at 11:08 AM
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
-- Database: `easybook`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `booking_date`, `start_time`, `end_time`) VALUES
(6, 6, 1, '2024-09-24', '15:00:00', '16:00:00'),
(12, 6, 5, '2024-09-24', '15:00:00', '16:00:00'),
(13, 6, 2, '2024-09-24', '14:37:00', '14:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_image` varchar(255) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `floor_number` int(11) NOT NULL,
  `status` enum('available','unavailable') NOT NULL DEFAULT 'available',
  `has_smartboard` tinyint(1) NOT NULL DEFAULT 0,
  `has_projector` tinyint(1) NOT NULL DEFAULT 0,
  `has_ac` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_image`, `room_number`, `capacity`, `floor_number`, `status`, `has_smartboard`, `has_projector`, `has_ac`) VALUES
(1, '../images/room1.jpeg', '101', 30, 1, 'unavailable', 1, 1, 1),
(2, '../images/room2.jpeg', '102', 25, 1, 'unavailable', 0, 1, 0),
(3, '../images/room3.jpeg', '201', 40, 2, 'available', 1, 0, 1),
(4, '../images/room4.jpeg', '202', 20, 2, 'available', 0, 1, 1),
(5, '../images/room5.jpeg', '301', 35, 3, 'unavailable', 1, 1, 1),
(6, '../images/room6.jpeg', '302', 30, 3, 'available', 0, 0, 0),
(7, '../images/room7.jpeg', '401', 50, 4, 'available', 1, 1, 0),
(8, '../images/room8.jpeg', '402', 15, 4, 'available', 0, 0, 1),
(9, '../images/room9.jpeg', '403', 20, 4, 'available', 1, 0, 1),
(10, '../images/room10.jpeg', '404', 30, 4, 'available', 0, 1, 0),
(12, '../images/room11.jpeg', '505', 40, 3, 'available', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','professor','admin') NOT NULL,
  `status` enum('approved','pending') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `department`, `email`, `phone_number`, `password`, `role`, `status`) VALUES
(1, 'Admin', 'Administration', 'admin@example.com', '', '0192023a7bbd73250516f069df18b500', 'admin', 'approved'),
(2, 'Rajesh Yadav', 'Computer Science', 'rajesh.yadav@gmail.com', '0123456789', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', 'approved'),
(3, 'Jane Smith', 'Mathematics', 'janesmith@example.com', '', '5f4dcc3b5aa765d61d8327deb882cf99', 'professor', 'approved'),
(4, 'Alice Brown', 'Physics', 'alicebrown@example.com', '', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', 'approved'),
(5, 'Bob White', 'Chemistry', 'bobwhite@example.com', '', '5f4dcc3b5aa765d61d8327deb882cf99', 'professor', 'approved'),
(6, 'Omkar Talkar', 'Computer Science', 'omkar.talkar29@gmail.com', '8976380688', '2ec24238e659ce667c487cc259c0b8d4', 'student', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `clean_expired_bookings` ON SCHEDULE EVERY 5 MINUTE STARTS '2024-09-24 06:42:20' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM bookings 
    WHERE (booking_date < CURDATE()) 
    OR (booking_date = CURDATE() AND end_time < CURTIME());
END$$

CREATE DEFINER=`root`@`localhost` EVENT `update_room_status` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-09-24 11:20:16' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Update room status to 'available' where no booking exists with unavailable status
    UPDATE rooms r
    SET r.status = 'available'
    WHERE r.status = 'unavailable'
    AND NOT EXISTS (
        SELECT 1
        FROM bookings b
        WHERE b.room_id = r.id
        AND b.booking_date = CURRENT_DATE()
        AND b.end_time > CURRENT_TIME()
    );
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
