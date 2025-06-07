@@ -0,0 +1,196 @@
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 11:53 AM
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
-- Database: `flight_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `depart_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `adults` int(11) DEFAULT 0,
  `children` int(11) DEFAULT 0,
  `infants` int(11) DEFAULT 0,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_email`, `origin`, `destination`, `depart_date`, `return_date`, `adults`, `children`, `infants`, `booked_at`) VALUES
(3, 'test@gmail.com', 'Clark (CRK)', 'Cebu', '2025-06-09', '2025-06-12', 1, 0, 0, '2025-06-06 09:03:39'),
(5, 'new@gmail.com', 'Clark (CRK)', 'Basco (Batanes)', '2025-06-20', '2025-06-25', 2, 0, 0, '2025-06-06 09:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `my_admin`
--

CREATE TABLE `my_admin` (
  `ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `my_admin`
--

INSERT INTO `my_admin` (`ID`, `Username`, `Password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `depart_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `adults` int(11) DEFAULT 1,
  `children` int(11) DEFAULT 0,
  `infants` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_email`, `origin`, `destination`, `depart_date`, `return_date`, `adults`, `children`, `infants`, `created_at`) VALUES
(1, 'test@gmail.com', 'Clark (CRK)', 'Caticlan (Boracay)', '2025-06-10', '2025-06-20', 4, 1, 0, '2025-06-06 09:07:17'),
(2, 'test@gmail.com', 'Clark (CRK)', 'General Santos', '2025-06-20', '2025-06-30', 2, 0, 0, '2025-06-06 09:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `role`) VALUES
(2, 'email', 'example', 'example@gmail.com', '$2y$10$WzogtmqlXGqX9Uwh7.0Qr.6hVJ4OaPW0oYbYZ1mrdgY99HvqgrbGG', 'user'),
(3, 'example1@gmail.com', 'gmail', 'example', '$2y$10$O1xHfGmTZaj7n4Wkgp1O2OtlwN0CvT6r2xuw7l6EUuy.CmV.PD2pa', 'user'),
(4, 'dummyaccount123@email.com', 'account', 'dummy', '$2y$10$vEut/F695spa4lckBMFQA.dHilCLYQH1u8ThqazGHdwSjv8kn.tRe', 'user'),
(6, 'dummy123@example.com', 'pogi', 'dummy', '$2y$10$YJ/2kilNsfFTdnEBE7XeG.8dBUiHxr20Cj0IFrXcQvWIMSCtvy596', 'user'),
(7, 'test@gmail.com', 'test', 'test', '$2y$10$2mED5/QcDKCmwStVKnl0wOoLoJngrND16tfOt4rg08OMUMqsX1c.O', 'user'),
(14, 'admin@airlines.org', 'User', 'Administration', '$2y$10$OfNUHJmLVnKJPyXoxcJMeeyg5gYYhaXBVuE6ZDLCuA1jBVt5f.TIy', 'admin'),
(15, 'new@gmail.com', 'new', 'new', '$2y$10$pt2arRNkOJNJNDmCCb632OIFQnWvGyczaTUuSFbv1uPaPjE.8no6u', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `my_admin`
--
ALTER TABLE `my_admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `my_admin`
--
ALTER TABLE `my_admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
Footer
