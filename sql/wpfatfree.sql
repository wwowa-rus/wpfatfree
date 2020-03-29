-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 17 2020 г., 09:03
-- Версия сервера: 5.5.60-MariaDB-cll-lve
-- Версия PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `wpfatfree`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent`) VALUES
(1, 'miscellanea', 'miscellanea', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no_image.jpeg',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_type` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `name`, `title`, `alt`, `img_type`) VALUES
(1, 'no_image.jpg', 'no_image title', 'no_image alt', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` blob,
  `type` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `name`, `content`, `type`) VALUES
(1, 'primary', 0x5b7b226964223a226c6973745f31222c2276616c7565223a22d0a1d182d180d0b0d0bdd0b8d186d0b0222c226f72646572223a307d5d, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL DEFAULT '0001-01-01',
  `thrumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no_image.jpeg',
  `content` text COLLATE utf8mb4_unicode_ci,
  `exerpt` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed` date NOT NULL DEFAULT '0001-01-01',
  `comment_status` tinyint(4) NOT NULL DEFAULT '1',
  `comment_count` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `user_id`, `title`, `date`, `thrumb`, `content`, `exerpt`, `type`, `slug`, `status`, `pass`, `changed`, `comment_status`, `comment_count`) VALUES
(1, 1, 'Страница', '2020-03-11', 'no_image.jpg', '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus, adipisci? Officiis quasi at magnam sit unde optio consequatur earum, qui quaerat natus doloribus distinctio! Quibusdam iure dicta temporibus. Rerum, enim.</p>\r\n<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus ipsam reprehenderit voluptatibus nulla necessitatibus&nbsp;</p>\r\n<p>&nbsp;<img style="display: block; margin-left: auto; margin-right: auto;" title="no_image title" src="http://baytheway.ru/uploads/thrumb_m/no_image.jpg" alt="no_image alt" /> &nbsp;</p>\r\n<p>eius maiores, magni amet eum ipsa quod voluptate optio labore omnis iure ratione provident suscipit odit?<br /><br /></p>', NULL, '1', 'stranica', 1, NULL, '2020-03-13', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `cat_id` int(11) DEFAULT '0',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL DEFAULT '0001-01-01',
  `thrumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no_image.jpeg',
  `content` text COLLATE utf8mb4_unicode_ci,
  `exerpt` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed` date NOT NULL DEFAULT '0001-01-01',
  `comment_status` tinyint(4) NOT NULL DEFAULT '1',
  `comment_count` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `cat_id`, `title`, `date`, `thrumb`, `content`, `exerpt`, `type`, `slug`, `status`, `pass`, `changed`, `comment_status`, `comment_count`) VALUES
(1, 1, 1, 'Пост', '2020-03-13', 'no_image.jpg', '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus, adipisci? Officiis quasi at magnam sit unde optio consequatur earum, qui quaerat natus doloribus distinctio! Quibusdam iure dicta temporibus. Rerum, enim.</p>\r\n<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus ipsam reprehenderit voluptatibus nulla necessitatibus&nbsp;</p>\r\n<p>&nbsp;<img style="display: block; margin-left: auto; margin-right: auto;" title="no_image title" src="http://baytheway.ru/uploads/thrumb_m/no_image.jpg" alt="no_image alt" /> &nbsp;</p>\r\n<p>eius maiores, magni amet eum ipsa quod voluptate optio labore omnis iure ratione provident suscipit odit?<br /><br /></p>\r\n<p>&nbsp;<img src="http://baytheway.ru/uploads/Screenshot_1.png" alt="" /> &nbsp;</p>', NULL, '2', 'post', 1, NULL, '2020-03-13', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`, `autoload`) VALUES
(1, 'blog_charset', 'UTF-8', 1),
(2, 'blogname', 'blogname', 1),
(3, 'blogdescription', 'blogdescription', 1),
(4, 'keywords', 'cms fatfree сайт php', 1),
(5, 'title', 'title', 1),
(6, 'description', 'description', 1),
(7, 'admin_email', '', 1),
(8, 'smtp_email', '', 1),
(9, 'smtp_port', '465', 1),
(10, 'smtp_pass', '', 1),
(11, 'tls_ssl', 'ssl', 1),
(12, 'post_match', '4', 1),
(13, 'is_home', 'blog', 1),
(14, 'debug', '3', 1),
(15, 'cache', 'TRUE', 1),
(16, 'wslid_cat', '1', 1),
(17, 'wslid_ex', '200', 1),
(18, 'wscat1', '1', 1),
(19, 'wscat2', '1', 1),
(20, 'wscat_num', '8', 1),
(21, 'wscat_ex', '100', 1),
(22, 'wrec_cat', '1', 1),
(23, 'wrec_ex', '100', 1),
(24, 'wslid_show', '', 1),
(25, 'wscat_show', '', 1),
(26, 'wrec_show', '', 1),
(27, 'wslid_allcat', 'on', 1),
(28, 'wrec_allcat', 'on', 1),
(29, 'headblock_show', 'on', 1),
(30, 'sidebar1_show', 'on', 1),
(31, 'sidebar2_show', 'on', 1),
(32, 'app_name', 'app_name', 1),
(33, 'app_version', '1.01', 1),
(34, 'author', 'author', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nicename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date NOT NULL DEFAULT '0001-01-01',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registered` date NOT NULL DEFAULT '0001-01-01',
  `activation_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '3'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `nicename`, `name`, `lastname`, `birthday`, `email`, `url`, `registered`, `activation_key`, `status`) VALUES
(1, 'admin', '$2y$10$YS4bZV6MlQlFiqH9/JJ4Peaq9hjAvj/jYBMRu.BtewvaOBjGb7j6C', NULL, NULL, NULL, '0001-01-01', 'admin@admin.ru', NULL, '2020-01-01', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `widget`
--

INSERT INTO `widget` (`id`, `name`, `value`) VALUES
(1, 'widget_header', '  <img src="/views/theme/img/baner468x60.jpg"  class = "img-fluid">'),
(2, 'widget_sidebar1', ' <img src="/views/theme/img/block.jpg" class = "img-fluid">'),
(3, 'widget_sidebar2', ' <img src="/views/theme/img/block.jpg" class = "img-fluid align-middle">');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_catid` (`cat_id`) USING BTREE,
  ADD KEY `post_userid` (`user_id`) USING BTREE;

--
-- Индексы таблицы `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `widget`
--
ALTER TABLE `widget`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `setting`
--
ALTER TABLE `setting`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `widget`
--
ALTER TABLE `widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
