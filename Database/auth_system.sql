-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 03:25 AM
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
-- Database: `auth_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Technology', '2026-01-23 09:24:43'),
(2, 'Gaming', '2026-01-23 09:24:43'),
(3, 'Art', '2026-01-23 09:24:43'),
(4, 'Music', '2026-01-23 09:24:43'),
(5, 'Life', '2026-01-23 09:24:43'),
(6, 'Food', '2026-01-23 09:24:43'),
(7, 'Travel', '2026-01-23 09:24:43'),
(8, 'Other', '2026-01-23 09:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(4, 15, 10, 'Sounds like some border lines', '2026-01-21 07:48:45'),
(6, 15, 14, 'gasuja', '2026-01-21 11:06:18'),
(7, 38, 12, 'lol', '2026-01-22 10:56:41'),
(9, 40, 16, 'I like chewing gum', '2026-01-22 14:49:38'),
(10, 40, 14, 'boomer?', '2026-01-22 17:22:40'),
(11, 38, 14, 'LMAO', '2026-01-22 17:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(205, 15, 14, '2026-01-19 16:17:37'),
(206, 14, 14, '2026-01-19 16:17:39'),
(250, 15, 15, '2026-01-21 14:14:15'),
(251, 38, 15, '2026-01-21 14:14:46'),
(253, 14, 10, '2026-01-22 05:10:45'),
(269, 38, 12, '2026-01-22 11:13:46'),
(272, 40, 16, '2026-01-22 14:49:17'),
(273, 41, 14, '2026-01-22 17:25:08'),
(274, 40, 14, '2026-01-22 17:25:09'),
(275, 40, 12, '2026-01-23 10:02:27'),
(292, 15, 12, '2026-01-24 07:02:46'),
(298, 43, 10, '2026-01-24 12:01:01'),
(302, 14, 18, '2026-01-25 12:01:39'),
(311, 15, 19, '2026-01-28 11:37:23'),
(315, 43, 12, '2026-01-29 09:20:58'),
(318, 41, 20, '2026-01-30 05:06:15'),
(319, 43, 20, '2026-01-30 07:31:25'),
(320, 15, 10, '2026-01-30 09:26:52'),
(321, 52, 21, '2026-01-30 10:14:55');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `category_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `category_id`, `created_at`) VALUES
(14, 12, '-.. .. ...- .. -. .. - -.-- / .. ... / .- / .-.. .. .', '<p>Forgotten Souls<sub class=\"ql-size-large\"><strong><em> </em></strong></sub>of the <em>nameless grave</em> arise from divine words of <strong>cynical mockeries</strong>, they die again and rise, over and over until <em>eternity had pity on them</em>.</p>', 5, '2026-01-10 07:10:09'),
(15, 10, 'The Margin Lines', 'Where worlds drifted apart, where lights glimmered as if breathing its last breath. Two magicians stand on a boulder, overlooking the cities of the fairy people.', 8, '2026-01-12 06:17:01'),
(38, 15, 'FOGS UNDERRATED', '<p>LOL</p>', 8, '2026-01-21 14:14:40'),
(40, 16, 'Chewing Gum', '<p>I\'m chewing a gum...</p>', 7, '2026-01-22 14:48:57'),
(41, 14, 'watching movie', '<p>someone told me to.  T^T</p>', 5, '2026-01-22 17:24:56'),
(43, 12, 'WHAteVER', '<p>MAYBEe lIFE</p><p><br></p>', 8, '2026-01-23 09:46:03'),
(52, 21, 'hi', '<p>hello dosto</p>', 5, '2026-01-30 10:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'avatar0.png',
  `banner` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `bio`, `avatar`, `banner`, `password`) VALUES
(10, 'Chiu Cheran', 'chiu', 'where@gmail.com', 'Hello web!!', 'avatar8.gif', 'banner6.gif', '$2y$10$EgzPMyN5IrXQCUDOyP9reOlgPSGVzb1taMDWkdhO8OfoKXj9nnXIO'),
(12, 'Lain', 'Serial', 'lain@email.com', 'I am DUMB', 'avatar1.gif', 'banner3.gif', '$2y$10$i.T3K0aqXnPBCAiVuK9Kpea91MWOBhoA33k03wuPJKOQABS8EsQlO'),
(14, 'Jerry', 'Jer', 'jerryshira05@gmail.com', NULL, 'avatar2.gif', NULL, '$2y$10$TBk.Qczs0w5Djvh1/TLYj.pMFjuleWQyHUB1Cn0.odbZ.cVBX0qeu'),
(15, 'Vinli Zirdo', 'mobn', 'vinlizirdo@gmail.com', NULL, 'avatar0.gif', NULL, '$2y$10$3vrSWkZDEoDnwvR/rpSiAu7KGLh9aheYyTKGKlc4NTtEv2BOJDK1O'),
(16, 'Mimi', 'Jelliefish', 'namelessjelliefish@gmail.com', 'I am a gumfish', 'avatar6.gif', NULL, '$2y$10$BH2cT4W/LFaVpRmam8GNWuqEgJbke0OjBzmG.efIS0ERUvVPRLG/K'),
(18, 'Neelakash', 'Neela123', 'Neela@gmail.com', 'Optional', 'avatar9.gif', NULL, '$2y$10$oBZk1Hf9BGTtiUz0lVaPY.fLUT63lQuoq.O9ltmkzbltB10WRx69O'),
(19, 'trail', 'trail', 'trail@email.com', NULL, 'avatar0.gif', 'banner8.gif', '$2y$10$qtiDdkinfkBNrLiidjJrP.IiDcqi4GeAuKOn9/ZpcR8xN7B2B4yke'),
(20, 'YashYR', 'yash', 'yash@gmail.com', NULL, 'avatar5.gif', 'banner7.gif', '$2y$10$YqOFRdQ0qQ0KV5sz4oFXn.cw5V51T.txK8Ha1uWTjX4AYAliAS5fe'),
(21, 'madhusmita', 'mb', 'mb@email.com', NULL, 'avatar2.gif', 'banner5.gif', '$2y$10$13Wx.1F2BZ4oFD4HUsaTq.BdJbTeVhVVBaTyhYTYizTQM1uqd6jUS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
