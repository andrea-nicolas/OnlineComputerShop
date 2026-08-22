-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2026 at 08:49 PM
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
-- Database: `onlinecomputershop`
--

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `category_id`, `created_at`) VALUES
(2, 'Intel', 9, '2026-08-22 19:04:56'),
(3, 'AMD', 9, '2026-08-22 19:04:56'),
(4, 'ASUS', 10, '2026-08-22 19:06:37'),
(5, 'Sapphire', 10, '2026-08-22 19:06:37'),
(6, 'Colorful', 10, '2026-08-22 19:06:37'),
(7, 'Powercolor', 10, '2026-08-22 19:06:37'),
(8, 'NVIDIA', 10, '2026-08-22 19:06:37'),
(9, 'ASUS', 11, '2026-08-22 19:08:09'),
(10, 'Colorful', 11, '2026-08-22 19:08:09'),
(11, 'GIGABYTE', 11, '2026-08-22 19:08:09'),
(12, 'MSI', 11, '2026-08-22 19:08:09'),
(13, 'Colorful', 12, '2026-08-22 19:09:36'),
(14, 'Corsair', 12, '2026-08-22 19:09:36'),
(15, 'Kingston', 12, '2026-08-22 19:09:36'),
(16, 'V-color', 12, '2026-08-22 19:09:36'),
(17, 'Toshiba', 13, '2026-08-22 19:10:48'),
(18, 'Seagate', 13, '2026-08-22 19:10:48'),
(19, 'Transcend', 14, '2026-08-22 19:10:48'),
(20, 'Corsair', 14, '2026-08-22 19:10:48'),
(21, 'GIGABYTE', 14, '2026-08-22 19:10:48'),
(22, 'Colorful', 14, '2026-08-22 19:10:48'),
(23, 'KINGSTON', 14, '2026-08-22 19:10:48'),
(24, 'Corsair', 15, '2026-08-22 19:12:40'),
(25, 'ASUS', 15, '2026-08-22 19:12:40'),
(26, 'GIGABYTE', 15, '2026-08-22 19:12:40'),
(27, 'MSI', 15, '2026-08-22 19:12:40'),
(28, 'Gamdias', 15, '2026-08-22 19:12:40'),
(29, 'Gamdias', 16, '2026-08-22 19:12:40'),
(30, 'Corsair', 16, '2026-08-22 19:12:40'),
(31, 'GIGABYTE', 15, '2026-08-22 19:12:40'),
(32, 'ASUS', 16, '2026-08-22 19:12:40'),
(33, 'MSI', 16, '2026-08-22 19:12:40');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
