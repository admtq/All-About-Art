-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 01:34 AM
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
-- Database: `allaboutartv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE `active_users` (
  `id_active` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `created_by_user_id` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `caption_post` text NOT NULL,
  `name_post` varchar(50) NOT NULL,
  `harga_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id_post`, `created_by_user_id`, `created_datetime`, `caption_post`, `name_post`, `harga_post`) VALUES
(32, 1, '2024-12-09 15:31:20', 'ini adalah sketsa gambara', 'kartun lucu', 1479702),
(33, 1, '2024-12-09 15:32:14', 'ahahahaha', 'anomali', 10000000),
(35, 2, '2025-01-03 00:10:40', 'seni adalah ledakan duar', 'duarrr', 911000),
(37, 2, '2025-01-03 00:31:08', 'aowkaowkawok1', 'foto fafa', 500000),
(38, 2, '2025-01-03 15:18:51', 'andna', 'foto hitam', 13300),
(39, 2, '2025-01-03 15:19:57', 'adlja;d', 'marmud', 12222),
(40, 8, '2025-01-05 16:59:51', 'Vector Darkness Knight Character Art', 'Darkness Knight', 300111),
(41, 8, '2025-01-05 17:04:42', 'Vector Character Art Spartan', 'Knight Spartan', 300112),
(42, 8, '2025-01-05 17:05:25', 'Just a little tomato', 'Tomato', 200111);

-- --------------------------------------------------------

--
-- Table structure for table `post_media`
--

CREATE TABLE `post_media` (
  `id_post_media` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `media_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_media`
--

INSERT INTO `post_media` (`id_post_media`, `id_post`, `media_file`) VALUES
(27, 32, 'vector_IMG_20231107_174247.jpg'),
(28, 33, 'animasi_WhatsApp Video 2024-12-05 at 14.59.19_162c8bac.mp4'),
(30, 35, 'videografi_explosion meme.mp4'),
(32, 37, 'fotografi_WIN_20240927_15_57_22_Profafa.jpg'),
(33, 38, 'fotografi_program array.jpg'),
(34, 39, 'videografi_Funny animal screaming meme!!.mp4'),
(35, 40, 'vector_darkness-knight-medieval-character-art-game-art-partners-34701-removebg-preview.png'),
(36, 41, 'vector_png-clipart-concept-art-video-games-drawing-character-illustration-2d-character-game-chibi-removebg-preview.png'),
(37, 42, 'vector_tomato.png');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_users_app` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `status_transaksi` varchar(50) NOT NULL,
  `waktu_transaksi` datetime NOT NULL DEFAULT current_timestamp(),
  `jenis_transaksi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_post`, `id_users_app`, `total_bayar`, `status_transaksi`, `waktu_transaksi`, `jenis_transaksi`) VALUES
(3, 37, 2, 500039, 'pending', '2025-01-03 10:27:31', 'pembelian'),
(4, 32, 2, 1479736, 'selesai', '2025-01-03 11:05:57', 'penjualan'),
(5, 33, 2, 10000035, 'pending', '2025-01-03 14:01:05', 'penjualan'),
(6, 39, 8, 12269, 'pending', '2025-01-05 16:46:32', 'penjualan'),
(7, 38, 8, 13346, 'pending', '2025-01-05 16:50:45', 'penjualan');

-- --------------------------------------------------------

--
-- Table structure for table `users_admin`
--

CREATE TABLE `users_admin` (
  `id_admin` int(11) NOT NULL,
  `name_admin` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `notelepon` varchar(50) DEFAULT NULL,
  `foto_admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_admin`
--

INSERT INTO `users_admin` (`id_admin`, `name_admin`, `username`, `password`, `created_datetime`, `notelepon`, `foto_admin`) VALUES
(1, 'SuperAdmin', 'admin', 'admin', '2025-01-08 06:05:43', '085234363927', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_app`
--

CREATE TABLE `users_app` (
  `id_users_app` int(11) NOT NULL,
  `name_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `description_user` text DEFAULT NULL,
  `notelepon` varchar(50) DEFAULT NULL,
  `foto_user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_app`
--

INSERT INTO `users_app` (`id_users_app`, `name_user`, `username`, `password`, `created_datetime`, `description_user`, `notelepon`, `foto_user`) VALUES
(1, 'Tester12', 'test', '123', '2024-12-04 13:37:10', 'aku anomali 21', '085234363927', 'profileusers/1733728967_WIN_20240927_15_57_22_Pro.jpg'),
(2, 'fafasaktigaib', 'vincent', 'vincent', '2024-12-04 16:41:01', 'anomali unesa dari antah berantah', '081234567789', 'profileusers/1735784479_WIN_20240927_15_57_22_Profafa.jpg'),
(7, 'reen', 'abc', 'abc', '2024-12-06 09:05:17', NULL, NULL, NULL),
(8, 'Ramadhani', 'ramaaaa', 'ramadhani', '2025-01-05 16:46:12', 'chill guy', '081123345567', 'profileusers/1736071356_levi-meir-clancy-h2UcC6lXlJs-unsplash.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `visit_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `visit_date`) VALUES
(51, '::1', '2025-01-08 01:01:46'),
(52, '::1', '2025-01-08 01:03:06'),
(53, '::1', '2025-01-08 01:03:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_users`
--
ALTER TABLE `active_users`
  ADD PRIMARY KEY (`id_active`),
  ADD KEY `active_ibfk_1` (`user_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `created_by_user_id` (`created_by_user_id`);

--
-- Indexes for table `post_media`
--
ALTER TABLE `post_media`
  ADD PRIMARY KEY (`id_post_media`),
  ADD KEY `post_id` (`id_post`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi_ibfk_1` (`id_post`),
  ADD KEY `transaksi_ibfk_2` (`id_users_app`);

--
-- Indexes for table `users_admin`
--
ALTER TABLE `users_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `name` (`name_admin`);

--
-- Indexes for table `users_app`
--
ALTER TABLE `users_app`
  ADD PRIMARY KEY (`id_users_app`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `name` (`name_user`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_users`
--
ALTER TABLE `active_users`
  MODIFY `id_active` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `post_media`
--
ALTER TABLE `post_media`
  MODIFY `id_post_media` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_admin`
--
ALTER TABLE `users_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_app`
--
ALTER TABLE `users_app`
  MODIFY `id_users_app` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`created_by_user_id`) REFERENCES `users_app` (`id_users_app`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_media`
--
ALTER TABLE `post_media`
  ADD CONSTRAINT `post_media_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_users_app`) REFERENCES `users_app` (`id_users_app`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
