-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 11:42 PM
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
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Country` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `City` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `Username`, `Name`, `Second_Name`, `Surname`, `E_mail`, `Password`, `Date_of_birth`, `Country`, `City`, `Created_at`) VALUES
(1, 'WielkiKutas', 'Cezary', 'Jan', 'Krenke', 'krenke.czarek@wp.pl', '$2y$10$lrDTeq8U1H84jLtola0wreCV4N254tDp5wz.Q9Lheey4R7hEHMwyK', '2004-07-06', 'Polska', 'Graniczna Wieś', '2024-06-10 21:05:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `E_mail` (`E_mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
