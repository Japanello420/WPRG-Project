-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2024 at 03:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `Username` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Name` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Second_Name` varchar(30) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Surname` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `E_mail` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Password` varchar(200) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Country` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `City` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `Gender` enum('male','female','other') NOT NULL DEFAULT 'other',
  `dark_mode` tinyint(1) DEFAULT 0,
  `username_changed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `Username`, `Name`, `Second_Name`, `Surname`, `E_mail`, `Password`, `Date_of_birth`, `Country`, `City`, `Created_at`, `bio`, `profile_picture`, `Gender`, `dark_mode`, `username_changed_at`) VALUES
(2, 'WielkiKutas', 'Cezary', 'Jan', 'Krenke', 'krenke.czarek@wp.pl', '$2y$10$lrDTeq8U1H84jLtola0wreCV4N254tDp5wz.Q9Lheey4R7hEHMwyK', '2004-07-06', 'Polska', 'Graniczna Wieś', '2024-06-10 21:05:42', 'ogóreke', 'uploads/img7156685_75181dcad275061c.jpg', 'other', 0, '2024-06-17 16:46:57'),
(23, 'lola', 'Ania', 'Kasia', 'Łopian', 'lola@lola.pl', '$2y$10$SZWMJMT0NMh.x3lUzpXI9uGA0yA.kt4W7R6JFJmjBj1FOusw4d8xi', '2003-07-06', 'Polska', 'Gdańsk', '2024-06-17 15:36:52', 'wędkara', 'uploads/258867563_302051045254064_8878497552217744254_n.jpg', 'female', 0, '2024-06-24 23:22:54'),
(24, 'Pawelek', 'Paweł', '', 'Gostomski', 'pawel@wp.pl', '$2y$10$USA.a5o/OOBl/bhLAySCz.vGVSJeiPZxlVwVsLnE8k5fC0mgZScv6', '2003-05-06', 'Polska', 'Gdańsk', '2024-06-21 12:34:58', NULL, NULL, 'male', 0, NULL),
(25, 'Okoń', 'Wojtek', '', 'Sokół', 'sokol@wp.pl', '$2y$10$8bNmMJqEmLKoRsAXCe2PueLC.Q.DiNUh1k3V4V/eVQjFLzP7Vtnzy', '1999-11-26', 'Polska', 'Warszawa', '2024-06-24 18:13:04', 'wędkarz', 'uploads/avatr.png', 'male', 0, '2024-06-24 23:27:18'),
(26, 'Radar', 'Radosław', '', 'Markiewicz', 'radek@wp.pl', '$2y$10$//C/BYg1uwt0zKDOz5oFIuV0sdR.H1/2JG9WdyTN9r8k1WoIlAz.C', '2003-10-31', 'Polska', 'Gdańsk', '2024-06-24 21:46:50', NULL, NULL, 'male', 0, NULL),
(27, 'Blajok', 'Jakub', '', 'Błażejczyk', 'bajojajo@wp.pl', '$2y$10$j3sbVbNHN8aQfcM8aFeCt.UlLpjqVFXn8EFq0qY9lrxXo3a1PhXc.', '2004-03-27', 'Polska', 'Iława', '2024-06-24 21:52:18', NULL, NULL, 'male', 0, NULL),
(28, 'Rafix', 'Rafał', 'Stanisław', 'Skarżyński', 'rafix@wp.pl', '$2y$10$T0CvjpQ8YcBXWWcCOWBeROJfbZpNEnU3yBdgzUDOpNeWcuHtpCplq', '2004-07-06', 'Polska', 'Gdańsk', '2024-06-24 21:55:13', NULL, NULL, 'male', 0, NULL),
(29, 'Macias', 'Maciej', 'Nikodem', 'Klikowicz', 'macias@gmail.com', '$2y$10$xXUFzSaB4IFLD7/5A66KQeEXxMSohWOSBnaJBtKcj9dmV23m1xYGe', '2003-12-17', 'Polska', 'Gdańsk', '2024-06-24 21:59:44', NULL, NULL, 'male', 0, NULL),
(30, 'Luigi', 'Łukasz', '', 'Berliński', 'berlin@wp.pl', '$2y$10$atDgu1ljHRBOf5qHxaSoIuDKO8MQfWR0N2arL4QY743MCHE4GsN/.', '2003-12-06', 'Niemcy', 'Berlin', '2024-06-24 22:01:37', NULL, NULL, 'male', 0, NULL),
(31, 'Bocian', 'Paweł', 'Gaweł', 'Bocian', 'bociek@pw.pl', '$2y$10$K3r/Wv6h921agJKRSPPqW.o7dT6GyfZB.0XH8d3kBDp6u5hjx.lzG', '2004-07-06', 'Polska', 'Gdańsk', '2024-06-24 22:11:59', NULL, NULL, 'male', 0, NULL),
(32, 'Kotek', 'Kotek', 'Kotek', 'Kotek', 'Kotek@wp.pl', '$2y$10$noc0w7Y19zHj0lKdpIOXqupS0CLA7LeA91aZ2AQadz0coqA1pClkS', '2004-07-06', 'Kotek', 'Kotek', '2024-06-24 22:58:42', NULL, NULL, 'male', 0, NULL),
(33, 'Zienek', 'Kacper', 'Aleksander', 'Zienkiewicz', 'kacperzienkiewicz112', '$2y$10$FStmW8N6zi/wMGxb.K7kC.gK0bKfLhEmnMUV4a.S0KfcQZEVJ57Uq', '2003-07-14', 'Afganistan', 'Gdańsk', '2024-06-24 23:01:20', 'Jebać Bartka', 'uploads/Messenger_creation_a6c40d93-d222-4fdc-af27-fb0b47892336.jpg', 'male', 0, '2024-06-25 01:02:08');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `since` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `user1_id`, `user2_id`, `since`) VALUES
(1, 2, 23, '2024-06-24 20:32:21'),
(3, 24, 23, '2024-06-24 21:39:15'),
(6, 25, 23, '2024-06-24 21:45:43'),
(7, 2, 33, '2024-06-24 23:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `invited_user_id` int(11) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`id`, `sender_user_id`, `invited_user_id`, `sent_at`, `status`) VALUES
(4, 2, 23, '2024-06-24 20:32:08', 'accepted'),
(11, 24, 23, '2024-06-24 21:39:04', 'accepted'),
(13, 25, 23, '2024-06-24 21:45:32', 'accepted'),
(16, 2, 33, '2024-06-24 23:03:39', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(38, 2, 23, 'v', '2024-06-25 00:51:28'),
(39, 2, 23, 'jajko\r\n', '2024-06-25 00:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `recipient_user_id` int(11) NOT NULL,
  `notification_type` enum('friend_request','invitation','other') NOT NULL,
  `related_id` int(11) NOT NULL,
  `notification_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL,
  `sender_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `E_mail` (`E_mail`),
  ADD KEY `idx_username` (`Username`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_friendship` (`user1_id`,`user2_id`),
  ADD KEY `fk_user2_id` (`user2_id`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_user_id` (`sender_user_id`),
  ADD KEY `invited_user_id` (`invited_user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`),
  ADD KEY `related_id` (`related_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `fk_user1_id` FOREIGN KEY (`user1_id`) REFERENCES `data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user2_id` FOREIGN KEY (`user2_id`) REFERENCES `data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `data` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `data` (`id`);

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`sender_user_id`) REFERENCES `data` (`id`),
  ADD CONSTRAINT `invitations_ibfk_2` FOREIGN KEY (`invited_user_id`) REFERENCES `data` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_receiver_user` FOREIGN KEY (`receiver_id`) REFERENCES `data` (`id`),
  ADD CONSTRAINT `fk_sender_user` FOREIGN KEY (`sender_id`) REFERENCES `data` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`recipient_user_id`) REFERENCES `data` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`related_id`) REFERENCES `friends` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`related_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
