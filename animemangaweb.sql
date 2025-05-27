-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 27, 2025 lúc 04:54 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `animemangaweb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `anime`
--

CREATE TABLE `anime` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `score` float NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('Airing','Completed','Upcoming','') NOT NULL DEFAULT 'Completed',
  `episodes` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `members` int(11) DEFAULT 0,
  `season_id` int(11) DEFAULT NULL,
  `director_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `anime`
--

INSERT INTO `anime` (`id`, `title`, `score`, `image`, `description`, `status`, `episodes`, `created_at`, `members`, `season_id`, `director_id`) VALUES
(1, 'Attack on Titan', 9.1, 'https://via.placeholder.com/300x400', '', 'Completed', 0, '2025-04-22 15:03:19', 0, NULL, NULL),
(2, 'One Piece', 8.9, 'https://via.placeholder.com/300x400', '', 'Completed', 0, '2025-04-22 15:03:19', 0, NULL, NULL),
(3, 'Naruto', 8.2, 'https://via.placeholder.com/300x400', '', 'Completed', 0, '2025-04-22 15:03:19', 0, NULL, NULL),
(4, 'Demon Slayer', 8.7, 'https://via.placeholder.com/300x400', '', 'Completed', 0, '2025-04-22 15:03:19', 0, NULL, NULL),
(5, 'Attack on Titan', 9.1, 'https://via.placeholder.com/300x400', 'Humanity fights Titans.', 'Completed', 75, '2025-04-22 15:05:49', 0, NULL, NULL),
(6, 'One Piece', 9, 'https://via.placeholder.com/300x400', 'Pirates seeking treasure.', 'Airing', 1000, '2025-04-22 15:05:49', 0, NULL, NULL),
(7, 'Naruto', 8.2, 'https://via.placeholder.com/300x400', 'Ninja story.', 'Completed', 500, '2025-04-22 15:05:49', 0, NULL, NULL),
(8, 'Attack on Titan', 9.1, 'https://via.placeholder.com/300x400', 'Humanity fights Titans.', 'Completed', 75, '2025-04-22 15:06:02', 0, NULL, NULL),
(9, 'One Piece', 8.9, 'https://via.placeholder.com/300x400', 'Pirates seeking treasure.', 'Airing', 1000, '2025-04-22 15:06:02', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `anime_episodes`
--

CREATE TABLE `anime_episodes` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `episode_number` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `video_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `anime_episodes`
--

INSERT INTO `anime_episodes` (`id`, `anime_id`, `episode_number`, `title`, `video_url`) VALUES
(1, 1, 1, 'Tập 1', 'https://www.youtube.com/embed/VIDEO_ID_1'),
(2, 1, 2, 'Tập 2', 'https://www.youtube.com/embed/VIDEO_ID_2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `anime_favorites`
--

CREATE TABLE `anime_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `anime_favorites`
--

INSERT INTO `anime_favorites` (`id`, `user_id`, `anime_id`, `created_at`) VALUES
(3, 1, 6, '2025-05-06 14:29:30'),
(6, 1, 1, '2025-05-10 21:48:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `anime_genre`
--

CREATE TABLE `anime_genre` (
  `anime_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `anime_genre`
--

INSERT INTO `anime_genre` (`anime_id`, `genre_id`) VALUES
(1, 1),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'Lưu chí Tam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `anime_id`, `user_id`, `parent_id`, `content`, `created_at`) VALUES
(1, 2, 1, NULL, 'Hi', '2025-05-06 02:49:09'),
(2, 2, 1, 1, 'Hi', '2025-05-06 02:49:17'),
(3, 1, 1, NULL, 'da', '2025-05-11 04:48:48'),
(4, 6, 1, NULL, 'sa', '2025-05-27 00:35:29'),
(5, 6, 1, NULL, 'Hi', '2025-05-27 01:40:29'),
(6, 8, 1, NULL, 'Hi', '2025-05-27 02:05:16'),
(7, 8, 1, 6, 'hi', '2025-05-27 02:05:22'),
(8, 8, 1, 6, 'Hi', '2025-05-27 02:05:29'),
(9, 8, 1, NULL, 'Hi', '2025-05-27 02:05:37'),
(10, 1, 1, NULL, 'Hi', '2025-05-27 02:44:03'),
(11, 1, 1, NULL, 'hi', '2025-05-27 02:44:07'),
(12, 6, 1, NULL, 'Hi', '2025-05-27 02:44:18'),
(13, 6, 1, 12, 'Hi', '2025-05-27 02:44:23'),
(14, 1, 1, NULL, 'Hi', '2025-05-27 02:44:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `directors`
--

CREATE TABLE `directors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `genre`
--

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(6, 'Comedy'),
(4, 'Drama'),
(3, 'Fantasy'),
(15, 'ftgh'),
(5, 'Romance'),
(16, 'tewqds');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `manga`
--

CREATE TABLE `manga` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `score` float NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Publishing','Completed','Upcoming') DEFAULT 'Completed',
  `chapters` int(11) DEFAULT 0,
  `volumes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `manga`
--

INSERT INTO `manga` (`id`, `title`, `score`, `image`, `description`, `status`, `chapters`, `volumes`, `created_at`, `author_id`) VALUES
(1, 'One Piece', 8, 'https://via.placeholder.com/300x400', 'Pirate adventure manga.', 'Publishing', 1100, 105, '2025-04-22 15:09:18', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `manga_comments`
--

CREATE TABLE `manga_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `manga_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `manga_comments`
--

INSERT INTO `manga_comments` (`id`, `user_id`, `manga_id`, `parent_id`, `content`, `created_at`) VALUES
(1, 1, 1, NULL, 'hi', '2025-05-27 01:55:14'),
(2, 1, 1, NULL, 'Hi', '2025-05-27 02:47:31'),
(3, 1, 1, 2, 'Hay', '2025-05-27 02:56:15'),
(4, 1, 1, NULL, 'Hay', '2025-05-27 02:57:36'),
(5, 1, 1, 4, 'Tôi thấy cũng hay', '2025-05-27 02:57:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `manga_favorites`
--

CREATE TABLE `manga_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `manga_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `manga_favorites`
--

INSERT INTO `manga_favorites` (`id`, `user_id`, `manga_id`, `created_at`) VALUES
(2, 1, 1, '2025-05-27 01:49:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `manga_genre`
--

CREATE TABLE `manga_genre` (
  `manga_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `manga_genre`
--

INSERT INTO `manga_genre` (`manga_id`, `genre_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `seasons`
--

CREATE TABLE `seasons` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `seasons`
--

INSERT INTO `seasons` (`id`, `name`) VALUES
(1, 'Winter'),
(2, 'Spring'),
(3, 'Summer'),
(4, 'Fall');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `avatar`, `role`) VALUES
(1, 'HenTsun', '$2y$10$5lTroeT22QyzqDvnDmtUlOrzQpbC7unxsqjKUWD1mnA5IXD4Qcpsa', 'damtrongj0956@gmail.com', '2025-05-02 09:35:54', './assets/images/avatars/mei.png', 'user'),
(3, 'admin', '$2y$10$S4LLMjhAZgpZpa4rbuYhhuaug3VDGEBDIdzU0MQ0EpHAwwiJ9VcaC', 'damtrongj095@gmail.com', '2025-05-10 21:09:31', NULL, 'admin'),
(6, 'minh', '$2y$10$fGlXcNbI14UqdnHPikb5BOzmsUbHAHxOBLsLYPqGKIEhZRZdCfh9e', 'minhnguyen@gmail.com', '2025-05-10 21:23:00', NULL, 'user'),
(7, 'tung', '$2y$10$5RsInbTnLWneHKzhp3O41e.g5vx1tTuClNg6Gymwi74k2DNURbpyC', 'letung@gmail.com', '2025-05-10 21:55:00', NULL, 'user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_anime`
--

CREATE TABLE `user_anime` (
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `status` enum('Watching','Completed','Plan to Watch','Dropped') DEFAULT 'Watching',
  `score_given` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_anime`
--

INSERT INTO `user_anime` (`user_id`, `anime_id`, `status`, `score_given`) VALUES
(1, 6, 'Watching', 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_manga`
--

CREATE TABLE `user_manga` (
  `user_id` int(11) NOT NULL,
  `manga_id` int(11) NOT NULL,
  `status` enum('Reading','Completed','Plan to Read','Dropped') DEFAULT 'Reading',
  `score_given` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_manga`
--

INSERT INTO `user_manga` (`user_id`, `manga_id`, `status`, `score_given`) VALUES
(1, 1, 'Reading', 8);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_season` (`season_id`),
  ADD KEY `fk_director` (`director_id`);

--
-- Chỉ mục cho bảng `anime_episodes`
--
ALTER TABLE `anime_episodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Chỉ mục cho bảng `anime_favorites`
--
ALTER TABLE `anime_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_anime` (`user_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Chỉ mục cho bảng `anime_genre`
--
ALTER TABLE `anime_genre`
  ADD PRIMARY KEY (`anime_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Chỉ mục cho bảng `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `manga`
--
ALTER TABLE `manga`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `manga_comments`
--
ALTER TABLE `manga_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `manga_id` (`manga_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `manga_favorites`
--
ALTER TABLE `manga_favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `manga_id` (`manga_id`);

--
-- Chỉ mục cho bảng `manga_genre`
--
ALTER TABLE `manga_genre`
  ADD PRIMARY KEY (`manga_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Chỉ mục cho bảng `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_anime`
--
ALTER TABLE `user_anime`
  ADD PRIMARY KEY (`user_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Chỉ mục cho bảng `user_manga`
--
ALTER TABLE `user_manga`
  ADD PRIMARY KEY (`user_id`,`manga_id`),
  ADD KEY `manga_id` (`manga_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `anime`
--
ALTER TABLE `anime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `anime_episodes`
--
ALTER TABLE `anime_episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `anime_favorites`
--
ALTER TABLE `anime_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `directors`
--
ALTER TABLE `directors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `manga`
--
ALTER TABLE `manga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `manga_comments`
--
ALTER TABLE `manga_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `manga_favorites`
--
ALTER TABLE `manga_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `anime`
--
ALTER TABLE `anime`
  ADD CONSTRAINT `fk_director` FOREIGN KEY (`director_id`) REFERENCES `directors` (`id`),
  ADD CONSTRAINT `fk_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`);

--
-- Các ràng buộc cho bảng `anime_episodes`
--
ALTER TABLE `anime_episodes`
  ADD CONSTRAINT `anime_episodes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `anime_favorites`
--
ALTER TABLE `anime_favorites`
  ADD CONSTRAINT `anime_favorites_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`);

--
-- Các ràng buộc cho bảng `anime_genre`
--
ALTER TABLE `anime_genre`
  ADD CONSTRAINT `anime_genre_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anime_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `manga_comments`
--
ALTER TABLE `manga_comments`
  ADD CONSTRAINT `manga_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manga_comments_ibfk_2` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manga_comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `manga_comments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `manga_favorites`
--
ALTER TABLE `manga_favorites`
  ADD CONSTRAINT `manga_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manga_favorites_ibfk_2` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `manga_genre`
--
ALTER TABLE `manga_genre`
  ADD CONSTRAINT `manga_genre_ibfk_1` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manga_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_anime`
--
ALTER TABLE `user_anime`
  ADD CONSTRAINT `user_anime_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_anime_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_manga`
--
ALTER TABLE `user_manga`
  ADD CONSTRAINT `user_manga_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_manga_ibfk_2` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
