-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2026 at 11:11 AM
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
-- Database: `capturra`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `photographer_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `photo_id`, `comment`, `created_at`) VALUES
(1, 15, 8, 'Nice..View..', '2026-02-14 05:58:21'),
(2, 15, 9, 'Nicee... Photography...!!', '2026-02-14 05:59:54'),
(3, 15, 6, 'Nice..View..', '2026-02-14 07:05:20'),
(4, 15, 9, 'Nice..View..', '2026-02-14 07:07:05'),
(5, 15, 9, 'love this', '2026-02-14 07:07:15'),
(6, 15, 9, 'happy wedding', '2026-02-14 07:07:25'),
(7, 16, 11, 'love this', '2026-02-24 14:06:27'),
(8, 16, 13, 'love this', '2026-02-24 18:18:39'),
(9, 16, 13, 'not succesfull watch', '2026-02-25 04:05:15'),
(10, 16, 14, 'Nice..View..', '2026-02-25 09:48:38'),
(11, 16, 15, 'Nice..View..', '2026-03-03 08:43:03'),
(12, 16, 15, 'happy wedding', '2026-03-03 08:43:11');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `photo_id`, `created_at`) VALUES
(8, 15, 9, '2026-02-14 05:59:36'),
(9, 15, 6, '2026-02-14 07:06:20'),
(12, 16, 11, '2026-02-24 16:05:58'),
(15, 16, 13, '2026-02-25 04:04:48'),
(18, 16, 14, '2026-02-25 10:22:01'),
(19, 16, 15, '2026-03-03 08:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `photographers`
--

CREATE TABLE `photographers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `price_per_day` int(11) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `total_likes` int(11) DEFAULT 0,
  `rating` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photographers`
--

INSERT INTO `photographers` (`id`, `user_id`, `bio`, `city`, `price_per_day`, `experience_years`, `profile_image`, `total_likes`, `rating`, `created_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-01-18 16:39:02'),
(3, 6, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-01-19 17:40:59'),
(4, 7, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-01-19 17:57:47'),
(5, 8, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-02-11 09:32:42'),
(6, 10, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-02-11 10:18:20'),
(7, 11, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-02-11 10:45:19'),
(8, 14, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-02-11 17:13:05'),
(9, 15, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-02-13 16:24:41'),
(10, 16, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-02-24 14:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `user_id`, `image`, `upload_date`, `likes`) VALUES
(5, 15, 'uploads/1771000649_ef746145bdb70efe829085b027afa20a.gif', '2026-02-13 16:37:29', 0),
(6, 15, 'uploads/1771000993_2 (2).png', '2026-02-13 16:43:13', 0),
(7, 15, 'uploads/1771046725_pic 1.jfif', '2026-02-14 05:25:25', 0),
(8, 15, 'uploads/1771046731_pic 2.jfif', '2026-02-14 05:25:31', 0),
(9, 15, 'uploads/1771048773_pic 3.jfif', '2026-02-14 05:59:33', 0),
(10, 15, 'uploads/1771054894_pic 3.jfif', '2026-02-14 07:41:34', 0),
(11, 16, 'uploads/1771941975_image_adee051c.png', '2026-02-24 14:06:15', 0),
(12, 16, 'uploads/1771942000_image_adee051c.png', '2026-02-24 14:06:40', 0),
(13, 16, 'uploads/1771949175_Gemini_Generated_Image_3jhnx63jhnx63jhn.png', '2026-02-24 16:06:15', 0),
(14, 16, 'uploads/1772012900_ChatGPT Image Feb 24, 2026, 10_46_08 PM.png', '2026-02-25 09:48:20', 0),
(15, 16, 'uploads/1772527369_internal.png', '2026-03-03 08:42:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','photographer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `role`, `created_at`, `bio`) VALUES
(1, 'rashmi', 'u', 'rashmi_u', 'rasss@04gmail.com', '$2y$10$WA9l9KJkkczzG/FDg2LrFefSJ4vZ3vuscGjgVWdm6VnRYaujJ1ZD6', 'photographer', '2026-01-18 16:39:02', NULL),
(5, 'Rudra', 'Umra', 'rudra_umra', 'rudra01umra@gmail.com', '$2y$10$X2IjZrOLmjjZwYKWAJ3wveHqewfr6iAsKBuvN763GC9O9d7S8CEaG', 'client', '2026-01-18 17:57:25', NULL),
(6, 'rush', 'p', 'rush_p', 'abce@gmail.com', '$2y$10$u3CDhQFahTQ8xXUKjxVtD.b7O6TfWecztBC4vP8S.mb6FKjY9jvLK', 'photographer', '2026-01-19 17:40:59', NULL),
(7, 'qwer', 'qwer', 'qwer', 'qwer@gmail.com', '$2y$10$MBlRBvpeIOkLOCd4.H5.aOA2ijUqlMlszhobI/9j7EbO3dAVN7Z1e', 'photographer', '2026-01-19 17:57:47', NULL),
(8, 'qwerty', 'a', 'qwerty_01', 'qwert@gmail.com', '$2y$10$2oY6k5V9p3COZtr2TqtRk.qrhDdaOktfT08ZVSodY95Xa6oZHBej6', 'photographer', '2026-02-11 09:32:42', NULL),
(9, 'patel', 'p', '12345', '1234@gmail.com', '$2y$10$pqn2whQ5inJeZ5jxUZ8gbudFct8wLyA6pBqk1511F8CJ5h7wv4D6C', 'client', '2026-02-11 09:34:10', NULL),
(10, 'fast', 'food', 'fastfood_10', 'fast34@gmail.com', '$2y$10$zLGyIW66r9YHLA3ucaNOVu2xBW4NhUITUu8qlothPtr7z/INfOoV.', 'photographer', '2026-02-11 10:18:19', NULL),
(11, 'pyr', 'abc', 'pyr_23', 'pyr23@gmail.com', '$2y$10$w310NNbxc7OR1rZqB5v5.ulBWDSndJ3lW0wcc/BJc.7qHzxxQCexq', 'photographer', '2026-02-11 10:45:19', NULL),
(12, 'Asd', 'asd', 'asd@yahoo.com', 'asd@gmail.com', '$2y$10$SmtvqrdMoPnZ7Vvqu6pz8u3z0w/m4z.I8yy3bM4L8h2cV3cCnSrIq', 'client', '2026-02-11 15:44:36', NULL),
(13, 'zxc', 'cv', 'zxc@01', 'zxc@gmail.com', '$2y$10$3YPPAnJwflhG9x0cvHEmdOIWJlRG6T6YB5cMAw/vDcN/eHSkqcCi2', 'client', '2026-02-11 16:32:27', 'Hey..!! Goood Morrininggg...!!'),
(14, 'plo', 'i', 'plo@11', 'plo@ebay.com', '$2y$10$6Ec6MCuzIUXYte4NANsuEeOFysmdkBik2ssDot.O/NmVdzZlMN1Pq', 'photographer', '2026-02-11 17:13:04', NULL),
(15, 'asd', 'r', 'asdfg', 'asd01@gmail.com', '$2y$10$eh4dRSPMpvrvJQHa2ds6ZOhtpM5ln9Zwo6O9.xbBPgIS/kKFx1kaS', 'photographer', '2026-02-13 16:24:41', NULL),
(16, 'photo', 'p', 'photographer_11', 'photo@gmail.com', '$2y$10$TUWQY/p2cvG34yFcgpMKmueIoInYLuLO3fhjnlL4AB0a11m5eqXSS', 'photographer', '2026-02-24 14:05:27', NULL),
(17, 'client', 'p', 'client@11', 'client11@gmail.com', '$2y$10$8F8A4oZbnHmOwOIEq1Lb2OCV20uPp51ns9qetnBlAsK1BQwTDUu4C', 'client', '2026-04-04 05:01:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `photographer_id` (`photographer_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`photo_id`);

--
-- Indexes for table `photographers`
--
ALTER TABLE `photographers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `photographers`
--
ALTER TABLE `photographers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`photographer_id`) REFERENCES `photographers` (`id`);

--
-- Constraints for table `photographers`
--
ALTER TABLE `photographers`
  ADD CONSTRAINT `photographers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
