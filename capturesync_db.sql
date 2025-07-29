-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2025 at 05:06 AM
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
-- Database: `capturesync_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user`, `action`, `timestamp`) VALUES
(1, 'System', 'Activity log table created', '2025-04-29 02:06:07'),
(2, 'Admin', 'Added new photographer: Remegio Obeda', '2025-04-29 02:15:08'),
(3, 'Admin', 'Added new photographer: Hanz', '2025-04-29 03:01:06'),
(4, 'Admin', 'Added new photographer: Kian', '2025-05-13 13:19:09'),
(5, 'Admin', 'Deleted photographer: Kian', '2025-05-13 13:30:08'),
(6, 'Admin', 'Added new photographer: Hanzo', '2025-05-13 17:24:41'),
(7, 'Admin', 'Added new photographer: hansak', '2025-05-13 17:31:58'),
(8, 'Admin', 'Added new photographer: Kraevee Vaulfer Ramada', '2025-05-13 17:36:38'),
(9, 'Admin', 'Updated photographer: hansak', '2025-05-19 10:47:00'),
(10, 'Admin', 'Updated photographer: hansak', '2025-05-19 10:47:07'),
(11, 'Admin', 'Updated photographer: Kraevee Vaulfer Ramada', '2025-05-19 10:47:22'),
(12, 'Admin', 'Updated photographer: Kraevee Vaulfer Ramada', '2025-05-19 10:47:31'),
(13, 'Admin', 'Updated photographer: Kraevee Vaulfer Ramada', '2025-05-19 10:47:38'),
(14, 'Admin', 'Updated photographer: Kraevee Vaulfer Ramada', '2025-05-19 10:47:47'),
(15, 'Admin', 'Updated photographer: Hanzo', '2025-05-19 10:50:39'),
(16, 'Admin', 'Updated photographer: Kraevee Vaulfer Ramada', '2025-05-19 10:56:15'),
(17, 'Admin', 'Updated photographer: hansak', '2025-05-19 10:56:50'),
(18, 'Admin', 'Updated photographer: Kraevee Vaulfer Ramada', '2025-05-22 06:08:06'),
(19, 'Admin', 'Deleted photographer: newphotographer', '2025-05-24 09:19:47'),
(20, 'Admin', 'Deleted photographer: jjj', '2025-05-24 09:40:24'),
(21, 'Admin', 'Deleted photographer: Kraevee Vaulfer Ramada', '2025-05-24 09:40:36'),
(22, 'Admin', 'Deleted photographer: Kraevee Vaulfer Ramada', '2025-05-24 09:41:02'),
(23, 'Admin', 'Updated photographer: Photo', '2025-06-02 13:16:04'),
(24, 'Admin', 'Logged in', '2025-07-16 09:50:30'),
(25, 'Admin', 'Logged in', '2025-07-20 21:53:27'),
(26, 'tester', 'Logged in', '2025-07-20 22:07:36'),
(27, 'p.tester', 'Logged in', '2025-07-20 22:44:12'),
(28, 'tester', 'Logged in', '2025-07-20 22:45:21'),
(29, 'p.tester', 'Logged in', '2025-07-20 22:45:55'),
(30, 'tester', 'Logged in', '2025-07-20 22:57:04'),
(31, 'p.tester', 'Logged in', '2025-07-20 22:57:48'),
(32, 'tester', 'Logged in', '2025-07-20 23:01:53'),
(33, 'p.tester', 'Logged in', '2025-07-20 23:03:04'),
(34, 'tester', 'Logged in', '2025-07-20 23:03:49'),
(35, 'Admin', 'Logged in', '2025-07-22 14:02:24'),
(36, 'Admin', 'Logged in', '2025-07-23 06:53:22'),
(37, 'Admin', 'Logged in', '2025-07-28 20:36:19'),
(38, 'rem', 'Logged in', '2025-07-28 20:40:43'),
(39, 'tester', 'Logged in', '2025-07-28 20:42:21'),
(40, 'tester', 'Logged in', '2025-07-28 20:43:37'),
(41, 'tester', 'Logged in', '2025-07-28 20:43:54'),
(42, 'kraev', 'Logged in', '2025-07-28 20:47:42'),
(43, 'tester', 'Logged in', '2025-07-28 20:48:28'),
(44, 'kraev', 'Logged in', '2025-07-28 20:49:20'),
(45, 'kraev', 'Uploaded final output for booking #16', '2025-07-28 20:57:44'),
(46, 'tester', 'Logged in', '2025-07-28 21:11:08'),
(47, 'tester', 'Booking #15 has been declined by Client', '2025-07-28 21:13:23'),
(48, 'tester', 'Added a 5-star review for booking #16', '2025-07-28 21:29:47'),
(49, 'Admin', 'Logged in', '2025-07-28 22:02:07'),
(50, 'Admin', 'Updated photographer: kraev', '2025-07-28 22:02:32'),
(51, 'tester', 'Logged in', '2025-07-28 22:05:39'),
(52, 'tester', 'Logged in', '2025-07-28 22:10:38'),
(53, 'tester', 'Set status to online', '2025-07-28 22:10:40'),
(54, 'Admin', 'Logged in', '2025-07-28 22:11:35'),
(55, 'Admin', 'Updated photographer: kraev', '2025-07-28 22:11:52'),
(56, 'kraev', 'Logged in', '2025-07-28 22:12:11'),
(57, 'kraev', 'Set status to online', '2025-07-28 22:12:21'),
(58, 'tester', 'Logged in', '2025-07-28 22:12:36'),
(59, 'tester', 'Set status to online', '2025-07-28 22:54:42'),
(60, 'tester', 'Set status to offline', '2025-07-28 22:54:42'),
(61, 'Admin', 'Logged in', '2025-07-28 22:55:09'),
(62, 'tester', 'Logged in', '2025-07-28 22:55:32'),
(63, 'tester', 'Logged in', '2025-07-29 00:47:59'),
(64, 'tester', 'Set status to online', '2025-07-29 00:48:09'),
(65, 'tester', 'Set status to offline', '2025-07-29 00:48:09'),
(66, 'tester', 'Set status to online', '2025-07-29 00:48:10'),
(67, 'tester', 'Set status to offline', '2025-07-29 00:48:11'),
(68, 'tester', 'Set status to online', '2025-07-29 00:48:11'),
(69, 'Admin', 'Logged in', '2025-07-29 00:48:48'),
(70, 'Gio', 'Logged in', '2025-07-29 02:02:00'),
(71, 'Gio', 'Logged in', '2025-07-29 02:09:27'),
(72, 'Gio', 'Set status to online', '2025-07-29 02:27:49'),
(73, 'Gio', 'Set status to offline', '2025-07-29 02:27:50'),
(74, 'Gio', 'Set status to online', '2025-07-29 02:27:51'),
(75, 'kraev', 'Logged in', '2025-07-29 02:39:15'),
(76, 'kraev', 'Booking #17 has been accepted by Photographer', '2025-07-29 02:41:43'),
(77, 'Admin', 'Logged in', '2025-07-29 02:47:34'),
(78, 'tester', 'Logged in', '2025-07-29 02:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `photographer_id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_phone` varchar(30) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','declined','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `photographer_id`, `client_name`, `client_email`, `client_phone`, `event_date`, `event_time`, `location`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 'Krae', 'kraevee123@gmail.com', '09279381279', '2025-05-30', '11:52:52', 'asafafs', 'asfafs', 'pending', '2025-05-23 21:54:13', '2025-05-23 22:58:37'),
(2, 0, 2, 'Kraevee Ramada', 'kraevee123@gmail.com', '09279381279', '2025-05-30', '12:30:00', 'Sabin Resort Hotel', 'asdaasdad', 'completed', '2025-05-23 21:57:13', '2025-05-23 22:58:55'),
(3, 0, 2, 'Kraevee Ramada', 'kraevee123@gmail.com', '09279381279', '2025-06-02', '22:30:00', 'Pilar', 'Kraevee Gwapo', 'declined', '2025-05-23 23:11:27', '2025-05-23 23:37:15'),
(4, 0, 2, 'Kraevee Ramada', 'kraevee123@gmail.com', '09279381279', '2025-05-19', '10:00:00', 'Pilar', 'asdafasfa', 'declined', '2025-05-23 23:14:16', '2025-05-23 23:37:10'),
(5, 3, 2, 'Kraevee Ramada', 'kraevee123@gmail.com', '09279381279', '2025-05-29', '15:30:00', 'Pilar', 'adsewe', 'completed', '2025-05-23 23:34:22', '2025-05-23 23:37:51'),
(6, 3, 2, 'Kraevee Ramada', 'kraevee123@gmail.com', '09279381279', '2025-06-04', '16:30:00', 'Pilar', 'asfsafqg3', 'completed', '2025-05-24 05:41:51', '2025-05-24 05:42:45'),
(7, 3, 2, 'Gio', 'kraevee123@gmail.com', '09279381279', '2025-05-31', '18:30:00', 'Pilar', 'APOFPEfqqwr', 'completed', '2025-05-24 06:43:23', '2025-05-24 06:44:50'),
(8, 12, 12, 'newclient', 'newclient@gmail.com', '09279381279', '2025-05-30', '10:30:00', 'Pilar', 'I want a grand birthday', 'completed', '2025-05-24 09:33:24', '2025-05-24 09:36:21'),
(9, 14, 14, 'Bro', 'bro@gmail.com', '09279381279', '2025-06-30', '22:30:00', 'Pilar, Camotes', 'I want a grand birthday', 'completed', '2025-06-02 13:26:40', '2025-06-02 13:29:33'),
(10, 13, 13, 'asf', 'photographer@gmail.com', 'sfF', '2025-06-18', '10:30:00', 'Ormoc', 'Test', 'completed', '2025-06-04 03:52:19', '2025-06-04 04:00:43'),
(11, 16, 14, 'kakaa', 'admin@capturesync.com', '09279381279', '2025-07-22', '22:45:00', 'Pilar', 'asfaf', 'pending', '2025-07-20 22:43:06', '2025-07-20 22:43:06'),
(12, 16, 15, 'awqwr', 'kraevee123@gmail.com', '09279381279', '2025-07-30', '11:45:00', 'Pilar', 'qwrwqr', '', '2025-07-20 22:45:41', '2025-07-20 22:56:39'),
(13, 16, 15, 'Kraevee', 'kraevee123@gmail.com', '09279381279', '2025-07-23', '00:00:00', 'Pilar', 'qwrqr', '', '2025-07-20 23:02:37', '2025-07-20 23:03:20'),
(14, 16, 15, 'qwqwr', 'kraevee123@gmail.com', 'qwrqwr', '2025-07-30', '10:45:00', 'qwrq', 'qwrqwr', 'pending', '2025-07-28 20:42:48', '2025-07-28 20:42:48'),
(15, 16, 14, 'qwrqr', 'kraevee123@gmail.com', '09279381279', '2025-07-30', '10:45:00', 'qwrqr', 'qwrqr', 'declined', '2025-07-28 20:44:11', '2025-07-28 21:13:23'),
(16, 16, 18, 'asaf', 'admin@capturesync.com', '09279381279', '2025-07-30', '10:45:00', 'asf', 'asf', 'completed', '2025-07-28 20:48:58', '2025-07-28 20:57:44'),
(17, 17, 18, 'Gio', 'tester2@gmail.com', '09279381279', '2025-07-30', '10:30:00', 'Ormoc', 'gdafhte', 'accepted', '2025-07-29 02:38:01', '2025-07-29 02:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `booking_files`
--

CREATE TABLE `booking_files` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_files`
--

INSERT INTO `booking_files` (`id`, `booking_id`, `file_name`, `file_path`, `file_type`, `uploaded_at`) VALUES
(1, 2, 'ClientUseCase.drawio.png', 'booking_outputs/2/6830fdaf13062_1748041135.png', 'image/png', '2025-05-23 22:58:55'),
(2, 5, 'ClientUseCase.drawio.png', 'booking_outputs/5/683106cf19659_1748043471.png', 'image/png', '2025-05-23 23:37:51'),
(3, 6, 'cc.png', 'booking_outputs/6/68315c55873ac_1748065365.png', 'image/png', '2025-05-24 05:42:45'),
(4, 7, 'cc.png', 'booking_outputs/7/68316ae20f60b_1748069090.png', 'image/png', '2025-05-24 06:44:50'),
(5, 8, 'cc.png', 'booking_outputs/8/683193152ef74_1748079381.png', 'image/png', '2025-05-24 09:36:21'),
(6, 9, 'ChatGPT Image May 27, 2025, 01_08_53 PM.png', 'booking_outputs/9/683da73db0539_1748870973.png', 'image/png', '2025-06-02 13:29:33'),
(7, 10, '1_v-model.png', 'booking_outputs/10/683fc4ebe8109_1749009643.png', 'image/png', '2025-06-04 04:00:43'),
(8, 12, '1_v-model.png', 'booking_outputs/12/output_687d72f11d1d51.76856081.png', 'image/png', '2025-07-20 22:51:29'),
(9, 12, '1_v-model.png', 'booking_outputs/12/output_687d73109447b8.31044644.png', 'image/png', '2025-07-20 22:52:00'),
(10, 12, 'DFDPre.drawio.png', 'booking_outputs/12/output_687d7427858e71.64252789.png', 'image/png', '2025-07-20 22:56:39'),
(11, 13, 'nature-vs-nurture-e5e6a177e2.jpg', 'booking_outputs/13/output_687d75b86dcbf7.79618022.jpg', 'image/jpeg', '2025-07-20 23:03:20'),
(12, 16, '1_v-model.png', 'booking_outputs/16/6887e448b8676_1753736264.png', 'image/png', '2025-07-28 20:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `photographers`
--

CREATE TABLE `photographers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `experience` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `portfolio` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'active',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 1500.00,
  `is_visible` tinyint(1) DEFAULT 0,
  `about_me` text DEFAULT NULL,
  `online_status` enum('online','offline') DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photographers`
--

INSERT INTO `photographers` (`id`, `fullname`, `email`, `password`, `experience`, `specialty`, `location`, `portfolio`, `created_at`, `status`, `updated_at`, `profile_pic`, `cover_photo`, `price`, `is_visible`, `about_me`, `online_status`) VALUES
(1, 'Kraevee Vaulfer Ramada', 'kraevee123@gmail.com', '$2y$10$yhJwC/ALUoUDifTtevaP7.3sc01bjpt.oGy/8tf8.7NdJekdvIKXK', '10', 'Wedding', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-04-27 17:07:22', 'active', NULL, NULL, NULL, 1500.00, 0, NULL, 'offline'),
(2, 'Kraevee Vaulfer Ramada', 'veeramada69@gmail.com', '$2y$10$2bxb.SHQVJAGpRC8LaFozOu.uK4Njw3yjiXzHO6DGOYWjswQ68KvG', '69', 'Birthday', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-04-29 01:09:06', 'active', '2025-05-23 18:47:19', 'profile_photos/photographer_2_1747655497.jpg', 'cover_photos/cover_2_1747657926.jpg', 6900.00, 1, 'I am Groot!', 'offline'),
(3, 'Remegio Obeda', 'gio@gmail.com', '$2y$10$WrGmsuE5Db20yhzRrl5OJOs/BFnjI3e/mP1UiayECUYpDv6AOnVyC', '10', 'Portrait', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-04-29 02:15:08', 'active', NULL, NULL, NULL, 1500.00, 0, NULL, 'offline'),
(4, 'Hanz', 'Hanz@gmail.com', '$2y$10$uMgfh8BlQz6nM1nVA/sYjOvYax8QdXM6jR6eTjZkKGkm0g1yX2JIa', '10', 'Portrait', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-04-29 03:01:06', 'active', NULL, NULL, NULL, 1500.00, 0, NULL, 'offline'),
(6, 'Hanzo', 'hanzo@gmail.com', '$2y$10$lii/nDWmF7O1p5s7vmzWb.4UK3JP2zOBfEcD7o95Ifs6/UMUr6rPW', '1000000', 'Portrait', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-05-13 17:24:41', 'active', '2025-05-19 10:50:39', NULL, NULL, 1500.00, 0, NULL, 'offline'),
(7, 'hansak', 'hansak@gmail.com', '$2y$10$KJ53UBcwWRxLnDMlyGxdFed1MQyx4MNVf.SMGDZrfzdzM2JVC.sli', '1000', 'Momp', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-05-13 17:31:58', 'active', '2025-05-19 10:56:50', NULL, NULL, 1500.00, 0, NULL, 'offline'),
(12, 'newphotographer', 'newphotographer@gmail.com', '$2y$10$Co.ssDMJkuywkPJYleUQCOpj6hhNyiLYvwFmBzc0f6XkF6s8IQa..', '0', 'Another Specialty', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-05-24 09:22:03', 'active', '2025-05-24 09:29:17', 'profile_photos/photographer_12_1748078741.jpg', 'cover_photos/cover_12_1748078770.jpg', 500.00, 1, 'Computer Science', 'offline'),
(13, 'Photographer1', 'photographer@gmail.com', '$2y$10$JMscTBVYPIm2belv6.V2We5ApEwZSVRdE62mMLRw46CaCNpOmfZta', '10', 'Portrait', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-06-02 12:57:50', 'active', '2025-06-04 03:49:23', 'profile_photos/photographer_13_1749008602.png', 'cover_photos/cover_13_1749008610.png', 1500.00, 1, 'I am Groot!', 'offline'),
(14, 'Photo', 'p1@gmail.com', '$2y$10$0.hYz2erSEvPb2Z9Uw9ejuwNBjwSmL2pziuOH7anFYhxS.EyaG1Tm', '10', 'Portrait', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-06-02 13:12:16', 'active', '2025-06-02 13:21:24', 'profile_photos/photographer_14_1748870380.png', 'cover_photos/cover_14_1748870391.png', 5000.00, 1, 'I am Groot!', 'offline'),
(15, 'p.tester', 'ptester@gmail.com', '$2y$10$ThY7s6k5IMpyo8TxDGhTC.zq1hJLjqeM5rANxbnriGc.vXuzcY69a', '20', 'Portrait', 'Ormoc', 'https://web.facebook.com/Kraezy69', '2025-07-16 08:27:10', 'active', '2025-07-20 22:58:18', 'profile_photos/photographer_15_1753052281.jpg', 'cover_photos/cover_15_1753052298.jpg', 1500.00, 1, NULL, 'offline'),
(16, 'Krae', 'k@gmail.com', '$2y$10$UYOycysMaZFLuwf/.wHD7ePWsl2PWPevK5ziIO.qMJbARjtBFjXHu', '11', 'Landscape,Aerial', 'Pilar', 'https://web.facebook.com/Kraezy69', '2025-07-16 08:37:14', 'active', NULL, NULL, NULL, 1500.00, 0, NULL, 'offline'),
(17, 'rem', 'rem@gmail.com', '$2y$10$LNKhnN6hB.g3cVtf4dqQauRHD5e9HVM8509TZjOeR2pnDVtvqGt1O', '10', 'Commercial', 'Pilar', 'https://chatgpt.com/?model=auto', '2025-07-28 20:40:20', 'active', '2025-07-28 20:41:19', 'profile_photos/photographer_17_1753735279.png', 'cover_photos/cover_17_1753735268.png', 1500.00, 0, NULL, 'offline'),
(18, 'kraev', 'kraev@gmail.com', '$2y$10$kiO8psDFD4rKkB7txqvGY.B0yc3ug4ZW38hbIyU6X9HeMWVUX5H1O', '12', 'Commercial', 'asffqf', 'https://chatgpt.com/?model=auto', '2025-07-28 20:47:10', 'active', '2025-07-28 22:12:21', 'profile_photos/photographer_18_1753735680.png', 'cover_photos/cover_18_1753736885.png', 1500.00, 1, NULL, 'online');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `photographer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `client_id`, `photographer_id`, `rating`, `comment`, `created_at`) VALUES
(1, 16, 16, 18, 5, 'asfaasf', '2025-07-28 21:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `location` varchar(255) NOT NULL,
  `user_type` enum('Admin','Client','Photographer','') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `online_status` enum('online','offline') DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `contact`, `location`, `user_type`, `created_at`, `online_status`) VALUES
(1, 'admin', '', 'admin123', '', '', 'Admin', '2025-04-19 23:26:03', 'offline'),
(2, 'krae', '', '$2y$10$NkXLAfc8KWLYcVB0YDs1T.IPmRwxXgStm4Sup4HHtJLY5zDD7RJom', '', '', 'Admin', '2025-04-20 00:29:13', 'offline'),
(3, 'Kraevee Vaulfer Ramada', 'kraevee123@gmail.com', '$2y$10$p0ymoCpFTlrAJQtEIb3SduKUEhRcITAGfe3lC6v5qBFXPO7oRDbzm', '09279381279', 'Ormoc', 'Client', '2025-04-29 09:00:13', 'offline'),
(4, 'Admin', 'admin@capturesync.com', '$2y$10$ofv0N3vXyjnST4X1puOHrOrv3kIyJXm0tBEyNPVz15FTg1bQ.itn2', '09279381279', 'Ormoc', 'Admin', '2025-04-29 09:43:43', 'offline'),
(5, '', 'admin@cs.com', 'admin123', '09279381279', 'Ormoc', 'Admin', '2025-04-29 10:02:05', 'offline'),
(6, 'GIO', 'gio@gmail.com', '$2y$10$z5yRH6WbpmFcxKaYlFmsNuONdbsaEXUrGqviO0Fls1oz.gGExBAPy', '09279381279', 'Ormoc', 'Client', '2025-04-29 11:32:16', 'offline'),
(7, 'John Daryl', 'jd14@gmail.com', '$2y$10$NFlgFpKixEbMYTsKIiyXRuGitNg9fLYvsrRLZX0fqVdDdiUMo5vIW', '09279381279', 'Ormoc', 'Client', '2025-05-19 18:04:19', 'offline'),
(8, 'Curt@gmail.com', 'curt@gmail.com', '$2y$10$tC4.6nBKm7JGxYs4dXWYLO9ItbA1V4SIK5mDh5GTelHr8.Amftdey', '09279381279', 'Ormoc', 'Client', '2025-05-24 15:32:26', 'offline'),
(9, 'Kraevee Vaulfer Ramada', 'jd@gmail.com', '$2y$10$hDSzfxWBlCth73OLVAxI9eh2GAz89CAPfQDvC72Cib39Qh1kWI0B2', '09279381279', 'Ormoc', 'Client', '2025-05-24 15:35:40', 'offline'),
(10, 'Kraevee Vaulfer Ramada', 'bb@gmail.com', '$2y$10$pHCX8j63BaOkpx5rPhKs5uCQAamRI/H7jEEwhe98hXmXfs.7n9lQy', '09279381279', 'Pilar', 'Client', '2025-05-24 15:40:57', 'offline'),
(11, 'bonex', 'newcl@gmail.com', '$2y$10$bLuIOrmRpiz1kFYFNygJW.npUlBE5Q0ZiDABAOheHI7cy30WBG3iq', '09279381279', 'Ormoc', 'Client', '2025-05-24 16:47:14', 'offline'),
(12, 'newclient', 'newclient@gmail.com', '$2y$10$PFQFFUBYpb/5UnVCEmHbveS7.boPoO3axKuc6evgWwtstLDnIDcHG', '09279381279', 'Ormoc', 'Client', '2025-05-24 17:21:25', 'offline'),
(13, 'Client', 'client1@gmail.com', '$2y$10$zAUVdBZFbP07JZ76l7f0NespB7pMT8SZS4hj2qeYKHUHZPEg84yYi', '09279381279', 'Ormoc', 'Client', '2025-06-02 20:56:14', 'offline'),
(14, 'Brother', 'bro@gmail.com', '$2y$10$OQ3VxUFdWBaAWnheDtkidOQ4i0IgWsXovFDaSFQPZ2TkO3eNgmAX2', '09279381279', 'Pilar', 'Client', '2025-06-02 21:10:19', 'offline'),
(15, 'tester', 't@gmail.com', '$2y$10$OkNpjiUaVL46bWsLMxcBieyKUQ4U1M6J6zpq.K2qelqMqMPSNNJCC', '', '', 'Client', '2025-07-15 02:38:00', 'offline'),
(16, 'tester', 'usertester@gmail.com', '$2y$10$bu2ORLs3YE4txTbm2najLeQxrrRSGI4vg2qXFHXJoT0LFaHGIQ7mO', '09279381279', 'Pilar, Camotes', 'Client', '2025-07-15 03:30:17', 'online'),
(17, 'Gio', 'tester2@gmail.com', '$2y$10$uMy7Ppz6Hr17KBe0XkXPfuXmK4cX0qyuvoOmkYKAI.2vicvq/BRRC', '09279381279', 'Ormoc', 'Client', '2025-07-29 10:01:46', 'online');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photographer_id` (`photographer_id`);

--
-- Indexes for table `booking_files`
--
ALTER TABLE `booking_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `photographers`
--
ALTER TABLE `photographers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `photographer_id` (`photographer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `booking_files`
--
ALTER TABLE `booking_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `photographers`
--
ALTER TABLE `photographers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`photographer_id`) REFERENCES `photographers` (`id`),
  ADD CONSTRAINT `fk_client_id` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_files`
--
ALTER TABLE `booking_files`
  ADD CONSTRAINT `booking_files_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`photographer_id`) REFERENCES `photographers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
