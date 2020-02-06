-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 05, 2020 at 07:47 AM
-- Server version: 5.7.23-log
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phonebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `phone_type` varchar(15) DEFAULT NULL,
  `note` text,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phones`
--

INSERT INTO `phones` (`id`, `user_id`, `phone`, `phone_type`, `note`, `updated_at`) VALUES
(1, 1, 9138752587, 'phone', 'Ø´Ù…Ø§Ø±Ù‡ Ø§ØµÙ„ÛŒ Ø§Ø³Øª', '2020-02-04 16:59:01'),
(2, 1, 3137754153, 'mobile', 'Ø¯ÙØªØ± Ù…Ø±Ú©Ø²ÛŒ', '2020-02-04 16:59:02'),
(3, 1, 2132456963, 'fax', 'ÙÚ©Ø³ Ø§ØµÙ„ÛŒ Ø¯ÙØªØ± Ø§Ø³Øª. ÙÙ‚Ø· Ø³Ø§Ø¹Øª 9 ØªØ§ 16 Ù¾Ø§Ø³Ø®Ú¯Ùˆ Ù‡Ø³ØªÙ†Ø¯.', '2020-02-04 16:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `soundfile` text CHARACTER SET utf8,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `gender`, `soundfile`, `created_at`) VALUES
(1, 'Ù…Ø³Ø¹ÙˆØ¯', 'ÙˆØ­ÛŒØ¯', 1, '2020Feb04-044755-13713.mp3', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
