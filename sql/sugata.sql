-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 21 2019 г., 19:51
-- Версия сервера: 5.6.37
-- Версия PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sugata`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin_logs`
--

CREATE TABLE IF NOT EXISTS `admin_logs` (
  `log_id` int(11) NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_type` text,
  `log_old_value` text,
  `log_new_value` text,
  `log_ref_id` int(11) unsigned NOT NULL,
  `log_user_id` int(11) NOT NULL,
  `log_ip` char(42) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_posts`
--

CREATE TABLE IF NOT EXISTS `admin_posts` (
  `admin_post_id` int(11) unsigned NOT NULL,
  `admin_user_id` int(11) unsigned NOT NULL,
  `admin_user_login` char(32) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_sections`
--

CREATE TABLE IF NOT EXISTS `admin_sections` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `admin_sections`
--

INSERT INTO `admin_sections` (`id`, `name`, `created_at`) VALUES
(1, 'admin_users', '2019-04-21 19:44:51'),
(2, 'admin_logs', '2019-04-21 19:44:51'),
(3, 'comment_reports', '2019-04-21 19:44:51'),
(4, 'strikes', '2019-04-21 19:44:51'),
(5, 'hostname', '2019-04-21 19:44:51'),
(6, 'punished_hostname', '2019-04-21 19:44:51'),
(7, 'email', '2019-04-21 19:44:51'),
(8, 'ip', '2019-04-21 19:44:51'),
(9, 'words', '2019-04-21 19:44:51'),
(10, 'noaccess', '2019-04-21 19:44:51'),
(11, 'preguntame', '2019-04-21 19:44:51'),
(12, 'sponsors', '2019-04-21 19:44:51'),
(13, 'mafia', '2019-04-21 19:44:51');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  `section_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `admin_users`
--

INSERT INTO `admin_users` (`id`, `created_at`, `admin_id`, `section_id`) VALUES
(1, '2019-04-21 19:44:51', 1, 1),
(2, '2019-04-21 19:44:51', 1, 2),
(3, '2019-04-21 19:44:51', 1, 3),
(4, '2019-04-21 19:44:51', 1, 4),
(5, '2019-04-21 19:44:51', 1, 5),
(6, '2019-04-21 19:44:51', 1, 6),
(7, '2019-04-21 19:44:51', 1, 7),
(8, '2019-04-21 19:44:51', 1, 8),
(9, '2019-04-21 19:44:51', 1, 9),
(10, '2019-04-21 19:44:51', 1, 10),
(11, '2019-04-21 19:44:51', 1, 11),
(12, '2019-04-21 19:44:51', 1, 12),
(13, '2019-04-21 19:44:51', 1, 13);

-- --------------------------------------------------------

--
-- Структура таблицы `annotations`
--

CREATE TABLE IF NOT EXISTS `annotations` (
  `annotation_key` char(64) NOT NULL,
  `annotation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `annotation_expire` timestamp NULL DEFAULT NULL,
  `annotation_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `annotations`
--

INSERT INTO `annotations` (`annotation_key`, `annotation_time`, `annotation_expire`, `annotation_text`) VALUES
('log-2', '2019-04-21 19:47:16', '2019-05-21 19:47:16', 'a:7:{s:3:"url";s:15:"https://toxu.ru";s:6:"status";s:6:"queued";s:5:"title";s:85:"Toxu — обмен знаниями и опытом - вопросы и ответы";s:4:"tags";s:4:"toxu";s:3:"uri";s:46:"toxu-obmen-znaniyami-i-opytom-voprosy-i-otvety";s:7:"content";s:71:"Сайт поддержки, вступаем в группу Sugata...";s:6:"sub_id";s:1:"1";}'),
('sub_preferences_1', '2019-04-21 19:51:12', NULL, '{"intro_min_len":51,"message":"\\u041e\\u043f\\u0438\\u0441\\u0430\\u043d\\u0438\\u0435 \\u044d\\u0442\\u043e\\u0433\\u043e \\u0441\\u043e\\u043e\\u0431\\u0449\\u0435\\u0441\\u0442\\u0432\\u0430 (1)...","post_html":"\\u041e\\u043f\\u0438\\u0441\\u0430\\u043d\\u0438\\u0435 \\u044d\\u0442\\u043e\\u0433\\u043e \\u0441\\u043e\\u043e\\u0431\\u0449\\u0435\\u0441\\u0442\\u0432\\u0430 (2)..."}');

-- --------------------------------------------------------

--
-- Структура таблицы `auths`
--

CREATE TABLE IF NOT EXISTS `auths` (
  `user_id` int(10) unsigned NOT NULL,
  `service` char(32) NOT NULL,
  `uid` decimal(24,0) unsigned NOT NULL,
  `username` char(32) NOT NULL DEFAULT '''''',
  `token` char(64) NOT NULL DEFAULT '''''',
  `secret` char(64) NOT NULL DEFAULT '''''',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `avatars`
--

CREATE TABLE IF NOT EXISTS `avatars` (
  `avatar_id` int(11) NOT NULL,
  `avatar_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `avatar_image` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `backups`
--

CREATE TABLE IF NOT EXISTS `backups` (
  `id` int(11) unsigned NOT NULL,
  `contents` text COLLATE utf8_spanish_ci,
  `related_table` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `related_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` char(42) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `backups`
--

INSERT INTO `backups` (`id`, `contents`, `related_table`, `related_id`, `created_at`, `ip`, `user_id`) VALUES
(1, '{"id":"1","author":"1","blog":"1","username":"Admin","randkey":"767057","karma":"0.00","valid":false,"date":"1555875931","sent_date":"1555875931","published_date":"1555875931","modified":"1555875931","url":"https:\\/\\/toxu.ru","url_title":"Toxu \\u2014 \\u043e\\u0431\\u043c\\u0435\\u043d \\u0437\\u043d\\u0430\\u043d\\u0438\\u044f\\u043c\\u0438 \\u0438 \\u043e\\u043f\\u044b\\u0442\\u043e\\u043c - \\u0432\\u043e\\u043f\\u0440\\u043e\\u0441\\u044b \\u0438 \\u043e\\u0442\\u0432\\u0435\\u0442\\u044b","url_description":"","encoding":false,"status":"discard","type":"","votes":"0","anonymous":"0","votes_avg":"0","negatives":"0","title":"","tags":"","uri":"","thumb_url":false,"content":"","content_type":"text","ip":"127.0.0.1","html":false,"read":"1","voted":null,"banned":false,"thumb_status":"unknown","clicks":null,"is_sub":"0","sub_id":"1","sub_name":"sugata","total_votes":"0","avatar":"1553497037","image":null,"best_comments":[],"poll":null,"nsfw":"0","sub_status":"discard","sub_status_id":"1","sub_date":"1555875931","comments":"0","sub_karma":"0.00","email":"aaa@aaaa.ru","user_karma":"17.54","user_level":"god","user_adcode":"","user_adchannel":"","server_name":"sugata","sub_owner":"1","base_url":"","created_from":"0","allow_main_link":"1","sub_status_origen":"discard","sub_date_origen":"1555875931","sub_color1":"","sub_color2":"#4267b2","page_mode":"threads","favorite":null,"favorite_readed":null,"media_size":null,"media_mime":null,"media_extension":null,"media_access":null,"media_date":null,"sponsored":null,"is_new":true}', 'links', 1, '2019-04-21 19:46:28', '127.0.0.1', 1),
(2, '{"id":"1","author":"1","blog":"1","username":"Admin","randkey":"767057","karma":"18.00","valid":false,"date":"1555876008","sent_date":"1555876008","published_date":"1555875931","modified":"1555876008","url":"https:\\/\\/toxu.ru","url_title":"Toxu \\u2014 \\u043e\\u0431\\u043c\\u0435\\u043d \\u0437\\u043d\\u0430\\u043d\\u0438\\u044f\\u043c\\u0438 \\u0438 \\u043e\\u043f\\u044b\\u0442\\u043e\\u043c - \\u0432\\u043e\\u043f\\u0440\\u043e\\u0441\\u044b \\u0438 \\u043e\\u0442\\u0432\\u0435\\u0442\\u044b","url_description":"","encoding":false,"status":"queued","type":"","votes":"1","anonymous":"0","votes_avg":"0","negatives":"0","title":"Toxu \\u2014 \\u043e\\u0431\\u043c\\u0435\\u043d \\u0437\\u043d\\u0430\\u043d\\u0438\\u044f\\u043c\\u0438 \\u0438 \\u043e\\u043f\\u044b\\u0442\\u043e\\u043c - \\u0432\\u043e\\u043f\\u0440\\u043e\\u0441\\u044b \\u0438 \\u043e\\u0442\\u0432\\u0435\\u0442\\u044b","tags":"toxu","uri":"toxu-obmen-znaniyami-i-opytom-voprosy-i-otvety","thumb_url":false,"content":"\\u0421\\u0430\\u0439\\u0442 \\u043f\\u043e\\u0434\\u0434\\u0435\\u0440\\u0436\\u043a\\u0438, \\u0432\\u0441\\u0442\\u0443\\u043f\\u0430\\u0435\\u043c \\u0432 \\u0433\\u0440\\u0443\\u043f\\u043f\\u0443 Sugata...","content_type":"text","ip":"127.0.0.1","html":false,"read":"1","voted":"18","banned":false,"thumb_status":"unknown","clicks":null,"is_sub":"0","sub_id":"1","sub_name":"sugata","total_votes":"1","avatar":"1553497037","image":null,"best_comments":[],"poll":null,"nsfw":"0","sub_status":"queued","sub_status_id":"1","sub_date":"1555876008","comments":"0","sub_karma":"0.00","email":"aaa@aaaa.ru","user_karma":"17.54","user_level":"god","user_adcode":"","user_adchannel":"","server_name":"sugata","sub_owner":"1","base_url":"","created_from":"0","allow_main_link":"1","sub_status_origen":"queued","sub_date_origen":"1555876008","sub_color1":"","sub_color2":"#4267b2","page_mode":"threads","favorite":null,"favorite_readed":null,"media_size":null,"media_mime":null,"media_extension":null,"media_access":null,"media_date":null,"sponsored":null,"is_new":false}', 'links', 1, '2019-04-21 19:47:16', '127.0.0.1', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `ban_id` int(10) unsigned NOT NULL,
  `ban_type` enum('email','hostname','punished_hostname','ip','words','proxy','noaccess') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ban_text` char(64) NOT NULL,
  `ban_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ban_expire` timestamp NULL DEFAULT NULL,
  `ban_comment` char(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `blog_id` int(20) NOT NULL,
  `blog_key` char(35) COLLATE utf8_spanish_ci DEFAULT NULL,
  `blog_type` enum('normal','blog','noiframe','redirector','aggregator') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'normal',
  `blog_rss` varchar(64) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `blog_rss2` varchar(64) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `blog_atom` varchar(64) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `blog_url` varchar(64) COLLATE utf8_spanish_ci DEFAULT NULL,
  `blog_feed` char(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `blog_feed_checked` timestamp NULL DEFAULT NULL,
  `blog_feed_read` timestamp NULL DEFAULT NULL,
  `blog_title` char(128) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `blogs`
--

INSERT INTO `blogs` (`blog_id`, `blog_key`, `blog_type`, `blog_rss`, `blog_rss2`, `blog_atom`, `blog_url`, `blog_feed`, `blog_feed_checked`, `blog_feed_read`, `blog_title`) VALUES
(1, 'adb73430802acdd9e82aa438b473c135', 'noiframe', '', 'https://toxu.ru/posts.rss', '', 'https://toxu.ru', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category__auto_id` int(11) NOT NULL,
  `category_lang` char(4) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'es',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `category_parent` int(11) NOT NULL DEFAULT '0',
  `category_name` char(32) COLLATE utf8_spanish_ci NOT NULL,
  `category_uri` char(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `category_calculated_coef` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`category__auto_id`, `category_lang`, `category_id`, `category_parent`, `category_name`, `category_uri`, `category_calculated_coef`) VALUES
(1, 'es', 1, 100, 'software libre', NULL, 1.04252),
(4, 'es', 4, 100, 'internet', 'internet', 1.04252),
(6, 'es', 6, 103, 'blogs', NULL, 0.787306),
(38, 'es', 38, 102, 'sociedad', 'sociedad', 1.2186),
(14, 'es', 8, 100, 'hardware', NULL, 1.04252),
(16, 'es', 22, 101, 'ciencia', NULL, 1.06563),
(19, 'es', 13, 100, 'diseño', NULL, 1.04252),
(22, 'es', 11, 100, 'software', NULL, 1.04252),
(70, 'es', 64, 102, 'sucesos', 'sucesos', 1.2186),
(29, 'es', 23, 100, 'juegos', NULL, 1.04252),
(33, 'es', 32, 103, 'friqui', NULL, 0.787306),
(57, 'es', 28, 103, 'podcast', 'podcast', 0.787306),
(35, 'es', 35, 103, 'curiosidades', NULL, 0.787306),
(36, 'es', 36, 101, 'derechos', NULL, 1.06563),
(37, 'es', 37, 100, 'seguridad', NULL, 1.04252),
(39, 'es', 5, 103, 'TV', 'tv', 0.787306),
(40, 'es', 100, 0, 'tecnología', 'tecnologia', 1.04252),
(41, 'es', 101, 0, 'cultura', 'cultura', 1.06563),
(42, 'es', 102, 0, 'actualidad', 'actualidad', 1.2186),
(43, 'es', 7, 102, 'empresas', NULL, 1.2186),
(44, 'es', 9, 101, 'música', NULL, 1.06563),
(45, 'es', 10, 103, 'vídeos', 'videos', 0.787306),
(46, 'es', 12, 103, 'espectáculos', 'espectaculos', 0.787306),
(47, 'es', 15, 101, 'historia', 'historia', 1.06563),
(48, 'es', 16, 101, 'literatura', 'literatura', 1.06563),
(49, 'es', 17, 102, 'américas', 'americas', 1.2186),
(50, 'es', 18, 102, 'europa', 'europa', 1.2186),
(51, 'es', 20, 102, 'internacional', 'internacional', 1.2186),
(53, 'es', 24, 102, 'política', 'politica', 1.2186),
(54, 'es', 25, 102, 'economía', 'economía', 1.2186),
(56, 'es', 27, 103, 'deportes', 'deportes', 0.787306),
(58, 'es', 29, 101, 'educación', 'educación', 1.06563),
(59, 'es', 39, 100, 'medicina', 'medicina', 1.04252),
(60, 'es', 40, 100, 'energía', 'energia', 1.04252),
(61, 'es', 41, 101, 'arte', 'arte', 1.06563),
(62, 'es', 42, 100, 'novedades', 'novedades-tec', 1.04252),
(63, 'es', 43, 100, 'medioambiente', 'medioambiente', 1.04252),
(64, 'es', 44, 102, 'personalidades', 'personalidades', 1.2186),
(65, 'es', 45, 101, 'prensa', 'prensa', 1.06563),
(66, 'es', 103, 0, 'ocio', 'ocio', 0.787306),
(67, 'es', 60, 101, 'fotografía', 'fotografia', 1.06563),
(68, 'es', 61, 101, 'divulgación', 'divulgacion', 1.06563),
(69, 'es', 62, 101, 'cine', 'cine', 1.06563);

-- --------------------------------------------------------

--
-- Структура таблицы `chats`
--

CREATE TABLE IF NOT EXISTS `chats` (
  `chat_time` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `chat_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `chat_room` enum('all','friends','admin') NOT NULL DEFAULT 'all',
  `chat_user` char(32) NOT NULL,
  `chat_text` char(255) NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8 MAX_ROWS=1000;

-- --------------------------------------------------------

--
-- Структура таблицы `clones`
--

CREATE TABLE IF NOT EXISTS `clones` (
  `clon_from` int(10) unsigned NOT NULL,
  `clon_to` int(10) unsigned NOT NULL,
  `clon_ip` char(48) NOT NULL DEFAULT '',
  `clon_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(20) NOT NULL,
  `comment_type` enum('normal','admin','private') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'normal',
  `comment_randkey` int(11) NOT NULL DEFAULT '0',
  `comment_parent` int(20) DEFAULT '0',
  `comment_link_id` int(20) NOT NULL DEFAULT '0',
  `comment_user_id` int(20) NOT NULL DEFAULT '0',
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_ip_int` decimal(39,0) NOT NULL,
  `comment_ip` varbinary(42) DEFAULT NULL,
  `comment_order` smallint(6) NOT NULL DEFAULT '0',
  `comment_votes` smallint(4) NOT NULL DEFAULT '0',
  `comment_karma` smallint(6) NOT NULL DEFAULT '0',
  `comment_content` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `conversations`
--

CREATE TABLE IF NOT EXISTS `conversations` (
  `conversation_user_to` int(10) unsigned NOT NULL,
  `conversation_type` enum('comment','post','link') NOT NULL,
  `conversation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `conversation_from` int(10) unsigned NOT NULL,
  `conversation_to` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `counts`
--

CREATE TABLE IF NOT EXISTS `counts` (
  `key` char(64) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `counts`
--

INSERT INTO `counts` (`key`, `count`, `date`) VALUES
('1.published', 0, '2019-04-21 19:43:36');

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `favorite_user_id` int(10) unsigned NOT NULL,
  `favorite_type` enum('link','post','comment') NOT NULL DEFAULT 'link',
  `favorite_link_id` int(10) unsigned NOT NULL,
  `favorite_link_readed` int(1) unsigned NOT NULL DEFAULT '0',
  `favorite_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `friend_type` enum('affiliate','manual','hide','affinity') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'affiliate',
  `friend_from` int(10) NOT NULL DEFAULT '0',
  `friend_to` int(10) NOT NULL DEFAULT '0',
  `friend_value` smallint(3) NOT NULL DEFAULT '0',
  `friend_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_links`
--

CREATE TABLE IF NOT EXISTS `geo_links` (
  `geo_id` int(11) NOT NULL,
  `geo_text` char(80) DEFAULT NULL,
  `geo_pt` point NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_users`
--

CREATE TABLE IF NOT EXISTS `geo_users` (
  `geo_id` int(11) NOT NULL,
  `geo_text` char(80) DEFAULT NULL,
  `geo_pt` point NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `html_images_seen`
--

CREATE TABLE IF NOT EXISTS `html_images_seen` (
  `hash` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(64) COLLATE utf8_spanish_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `league`
--

CREATE TABLE IF NOT EXISTS `league` (
  `id` int(10) unsigned NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `league_matches`
--

CREATE TABLE IF NOT EXISTS `league_matches` (
  `id` int(10) unsigned NOT NULL,
  `league_id` int(10) unsigned NOT NULL,
  `local` int(10) unsigned NOT NULL,
  `visitor` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vote_starts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votes_local` int(20) DEFAULT '0',
  `votes_visitor` int(20) DEFAULT '0',
  `votes_tied` int(20) DEFAULT '0',
  `score_local` int(2) DEFAULT NULL,
  `score_visitor` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `league_teams`
--

CREATE TABLE IF NOT EXISTS `league_teams` (
  `id` int(10) unsigned NOT NULL,
  `shortname` char(5) DEFAULT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `league_terms`
--

CREATE TABLE IF NOT EXISTS `league_terms` (
  `user_id` int(20) NOT NULL,
  `vendor` enum('nivea') NOT NULL DEFAULT 'nivea'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `league_votes`
--

CREATE TABLE IF NOT EXISTS `league_votes` (
  `match_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `ip` decimal(39,0) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `link_id` int(20) NOT NULL,
  `link_author` int(20) NOT NULL DEFAULT '0',
  `link_blog` int(20) DEFAULT '0',
  `link_status` char(20) CHARACTER SET utf8 NOT NULL DEFAULT 'discard',
  `link_randkey` int(20) NOT NULL DEFAULT '0',
  `link_votes` int(20) NOT NULL DEFAULT '0',
  `link_negatives` int(11) NOT NULL DEFAULT '0',
  `link_anonymous` int(10) unsigned NOT NULL DEFAULT '0',
  `link_votes_avg` float NOT NULL DEFAULT '0',
  `link_comments` int(11) unsigned NOT NULL DEFAULT '0',
  `link_karma` decimal(10,2) NOT NULL DEFAULT '0.00',
  `link_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `link_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_sent_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_published_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_category` int(11) NOT NULL DEFAULT '0',
  `link_lang` char(2) CHARACTER SET utf8 NOT NULL DEFAULT 'es',
  `link_ip_int` decimal(39,0) NOT NULL,
  `link_ip` varbinary(42) DEFAULT NULL,
  `link_content_type` char(12) COLLATE utf8_spanish_ci DEFAULT NULL,
  `link_uri` char(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `link_url` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `link_thumb_status` enum('unknown','checked','error','local','remote','deleted') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'unknown',
  `link_thumb_x` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `link_thumb_y` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `link_thumb` tinytext COLLATE utf8_spanish_ci,
  `link_url_title` text COLLATE utf8_spanish_ci,
  `link_title` text COLLATE utf8_spanish_ci NOT NULL,
  `link_content` text COLLATE utf8_spanish_ci NOT NULL,
  `link_tags` text COLLATE utf8_spanish_ci,
  `link_nsfw` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `links`
--

INSERT INTO `links` (`link_id`, `link_author`, `link_blog`, `link_status`, `link_randkey`, `link_votes`, `link_negatives`, `link_anonymous`, `link_votes_avg`, `link_comments`, `link_karma`, `link_modified`, `link_date`, `link_sent_date`, `link_published_date`, `link_category`, `link_lang`, `link_ip_int`, `link_ip`, `link_content_type`, `link_uri`, `link_url`, `link_thumb_status`, `link_thumb_x`, `link_thumb_y`, `link_thumb`, `link_url_title`, `link_title`, `link_content`, `link_tags`, `link_nsfw`) VALUES
(1, 1, 1, 'published', 767057, 1, 0, 0, 0, 0, '18.00', '2019-04-21 19:47:16', '2019-04-21 19:46:48', '2019-04-21 19:46:48', '2019-04-21 19:45:31', 0, 'es', '2130706433', 0x3132372e302e302e31, 'text', 'toxu-obmen-znaniyami-i-opytom-voprosy-i-otvety', 'https://toxu.ru', 'checked', 0, 0, NULL, 'Toxu — обмен знаниями и опытом - вопросы и ответы', 'Toxu — обмен знаниями и опытом - вопросы и ответы', 'Сайт поддержки, вступаем в группу Sugata...', 'toxu', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `link_clicks`
--

CREATE TABLE IF NOT EXISTS `link_clicks` (
  `id` int(10) unsigned NOT NULL,
  `counter` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `link_commons`
--

CREATE TABLE IF NOT EXISTS `link_commons` (
  `link` int(10) unsigned NOT NULL,
  `value` float NOT NULL,
  `n` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `log_id` int(11) NOT NULL,
  `log_sub` int(11) DEFAULT '1',
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_type` enum('link_new','comment_new','link_publish','link_discard','comment_edit','link_edit','post_new','post_edit','login_failed','spam_warn','link_geo_edit','user_new','user_delete','link_depublished','user_depublished_vote') NOT NULL,
  `log_ref_id` int(11) unsigned NOT NULL,
  `log_user_id` int(11) NOT NULL,
  `log_ip_int` decimal(39,0) NOT NULL,
  `log_ip` char(42) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `logs`
--

INSERT INTO `logs` (`log_id`, `log_sub`, `log_date`, `log_type`, `log_ref_id`, `log_user_id`, `log_ip_int`, `log_ip`) VALUES
(1, 1, '2019-04-21 19:46:48', 'link_new', 1, 1, '2130706433', '127.0.0.1'),
(2, 1, '2019-04-21 19:47:16', 'link_edit', 1, 1, '2130706433', '127.0.0.1');

-- --------------------------------------------------------

--
-- Структура таблицы `log_pos`
--

CREATE TABLE IF NOT EXISTS `log_pos` (
  `host` varchar(60) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `log_file` varchar(32) DEFAULT NULL,
  `log_pos` int(11) DEFAULT NULL,
  `master_host` varchar(60) DEFAULT NULL,
  `master_log_file` varchar(32) DEFAULT NULL,
  `master_log_pos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `type` char(12) NOT NULL DEFAULT '',
  `id` int(10) unsigned NOT NULL,
  `version` tinyint(3) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL DEFAULT '0',
  `access` enum('restricted','public','friends','private') NOT NULL DEFAULT 'restricted',
  `mime` char(32) NOT NULL,
  `extension` char(6) NOT NULL DEFAULT 'jpg',
  `size` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dim1` smallint(5) unsigned NOT NULL,
  `dim2` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `user` int(10) unsigned NOT NULL,
  `type` char(12) NOT NULL,
  `counter` int(10) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pageloads`
--

CREATE TABLE IF NOT EXISTS `pageloads` (
  `date` date NOT NULL,
  `type` enum('html','ajax','other','rss','image','api','sneaker','bot','geo') NOT NULL DEFAULT 'html',
  `counter` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(11) unsigned NOT NULL,
  `question` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `duration` smallint(3) NOT NULL DEFAULT '0',
  `votes` smallint(7) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_at` datetime NOT NULL,
  `link_id` int(20) DEFAULT NULL,
  `post_id` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `polls_options`
--

CREATE TABLE IF NOT EXISTS `polls_options` (
  `id` int(11) unsigned NOT NULL,
  `option` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `votes` smallint(7) unsigned NOT NULL DEFAULT '0',
  `karma` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `poll_id` int(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) unsigned NOT NULL,
  `post_randkey` int(11) NOT NULL DEFAULT '0',
  `post_src` enum('web','api','im','mobile','phone') CHARACTER SET utf8 NOT NULL DEFAULT 'web',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_user_id` int(11) unsigned NOT NULL,
  `post_visible` enum('all','friends') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'all',
  `post_ip_int` decimal(39,0) DEFAULT NULL,
  `post_votes` smallint(4) NOT NULL DEFAULT '0',
  `post_karma` smallint(6) NOT NULL DEFAULT '0',
  `post_content` text COLLATE utf8_spanish_ci NOT NULL,
  `post_is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `prefs`
--

CREATE TABLE IF NOT EXISTS `prefs` (
  `pref_user_id` int(11) NOT NULL,
  `pref_key` char(16) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `pref_value` int(8) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefs`
--

INSERT INTO `prefs` (`pref_user_id`, `pref_key`, `pref_value`) VALUES
(1, 'sub_follow', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `preguntame`
--

CREATE TABLE IF NOT EXISTS `preguntame` (
  `id` int(11) NOT NULL,
  `title` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `subtitle` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `link` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `start_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL,
  `sponsored` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `privates`
--

CREATE TABLE IF NOT EXISTS `privates` (
  `id` int(10) unsigned NOT NULL,
  `randkey` int(11) NOT NULL DEFAULT '0',
  `user` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `report_id` int(11) NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report_type` text,
  `report_reason` text,
  `report_user_id` int(11) NOT NULL,
  `report_ref_id` int(11) NOT NULL,
  `report_status` text,
  `report_modified` timestamp NULL DEFAULT NULL,
  `report_revised_by` int(11) DEFAULT NULL,
  `report_ip` char(42) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rss`
--

CREATE TABLE IF NOT EXISTS `rss` (
  `blog_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link_id` int(10) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_parsed` timestamp NULL DEFAULT NULL,
  `url` char(250) NOT NULL,
  `title` char(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sneakers`
--

CREATE TABLE IF NOT EXISTS `sneakers` (
  `sneaker_id` char(24) NOT NULL,
  `sneaker_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sneaker_user` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 MAX_ROWS=1000;

-- --------------------------------------------------------

--
-- Структура таблицы `sph_counter`
--

CREATE TABLE IF NOT EXISTS `sph_counter` (
  `counter_id` int(11) NOT NULL,
  `max_doc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sponsors`
--

CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(11) unsigned NOT NULL,
  `external` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `banner` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `banner_mobile` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `css` text COLLATE utf8_spanish_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `link` int(20) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `strikes`
--

CREATE TABLE IF NOT EXISTS `strikes` (
  `strike_id` int(11) NOT NULL,
  `strike_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `strike_type` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `strike_reason` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `strike_user_id` int(11) NOT NULL,
  `strike_report_id` int(11) DEFAULT '0',
  `strike_admin_id` int(11) NOT NULL,
  `strike_karma_old` decimal(4,2) unsigned NOT NULL,
  `strike_karma_new` decimal(4,2) unsigned NOT NULL,
  `strike_karma_restore` decimal(4,2) unsigned NOT NULL,
  `strike_hours` tinyint(3) NOT NULL,
  `strike_expires_at` datetime NOT NULL,
  `strike_comment` text COLLATE utf8_spanish_ci,
  `strike_ip` char(42) COLLATE utf8_spanish_ci DEFAULT NULL,
  `strike_restored` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `subs`
--

CREATE TABLE IF NOT EXISTS `subs` (
  `id` int(11) NOT NULL,
  `name` char(12) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  `server_name` varchar(32) DEFAULT NULL,
  `base_url` varchar(32) DEFAULT NULL,
  `name_long` char(40) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `sub` tinyint(1) DEFAULT '0',
  `meta` tinyint(1) DEFAULT '0',
  `owner` int(11) NOT NULL DEFAULT '0',
  `nsfw` tinyint(1) DEFAULT '0',
  `created_from` int(11) NOT NULL DEFAULT '0',
  `allow_main_link` tinyint(1) DEFAULT '1',
  `color1` char(7) DEFAULT NULL,
  `color2` char(7) DEFAULT NULL,
  `private` tinyint(1) DEFAULT '0',
  `show_admin` tinyint(1) NOT NULL DEFAULT '0',
  `page_mode` enum('best-comments','threads','best-threads','interview','answered','standard') DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Basic data for every sub site';

--
-- Дамп данных таблицы `subs`
--

INSERT INTO `subs` (`id`, `name`, `enabled`, `parent`, `server_name`, `base_url`, `name_long`, `visible`, `sub`, `meta`, `owner`, `nsfw`, `created_from`, `allow_main_link`, `color1`, `color2`, `private`, `show_admin`, `page_mode`) VALUES
(1, 'sugata', 1, 0, 'sugata', '', 'sugata', 0, 0, 0, 1, 0, 0, 1, '#ffffff', '#e35614', 1, 0, 'threads');

-- --------------------------------------------------------

--
-- Структура таблицы `subs_copy`
--

CREATE TABLE IF NOT EXISTS `subs_copy` (
  `src` int(11) NOT NULL,
  `dst` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sub_categories`
--

CREATE TABLE IF NOT EXISTS `sub_categories` (
  `id` smallint(5) unsigned NOT NULL,
  `category` smallint(5) unsigned NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `import` tinyint(1) NOT NULL DEFAULT '1',
  `export` tinyint(1) NOT NULL DEFAULT '0',
  `calculated_coef` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store categories available for each sub site';

-- --------------------------------------------------------

--
-- Структура таблицы `sub_statuses`
--

CREATE TABLE IF NOT EXISTS `sub_statuses` (
  `id` int(11) unsigned NOT NULL,
  `status` enum('discard','queued','published','abuse','duplicated','autodiscard','metapublished') NOT NULL DEFAULT 'discard',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` int(10) NOT NULL,
  `origen` int(11) NOT NULL,
  `karma` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store the status for each link in every sub site';

--
-- Дамп данных таблицы `sub_statuses`
--

INSERT INTO `sub_statuses` (`id`, `status`, `date`, `link`, `origen`, `karma`) VALUES
(1, 'published', '2019-04-21 19:46:48', 1, 1, '18.00');

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_link_id` int(11) NOT NULL DEFAULT '0',
  `tag_lang` char(4) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'es',
  `tag_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tag_words` char(40) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `key` char(32) NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `trackbacks`
--

CREATE TABLE IF NOT EXISTS `trackbacks` (
  `trackback_id` int(10) unsigned NOT NULL,
  `trackback_link_id` int(11) NOT NULL DEFAULT '0',
  `trackback_user_id` int(11) NOT NULL DEFAULT '0',
  `trackback_type` enum('in','out') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'in',
  `trackback_status` enum('ok','pendent','error') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'pendent',
  `trackback_date` timestamp NULL DEFAULT NULL,
  `trackback_ip_int` int(10) unsigned NOT NULL DEFAULT '0',
  `trackback_link` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `trackback_url` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `trackback_title` text COLLATE utf8_spanish_ci,
  `trackback_content` text COLLATE utf8_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(20) NOT NULL,
  `user_login` char(32) COLLATE utf8_spanish_ci NOT NULL,
  `user_level` enum('autodisabled','disabled','normal','special','blogger','admin','god') CHARACTER SET utf8 NOT NULL DEFAULT 'normal',
  `user_avatar` int(10) unsigned NOT NULL DEFAULT '0',
  `user_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_validated_date` timestamp NULL DEFAULT NULL,
  `user_ip` char(42) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_pass` char(128) COLLATE utf8_spanish_ci NOT NULL,
  `user_email` char(64) COLLATE utf8_spanish_ci NOT NULL,
  `user_names` char(60) COLLATE utf8_spanish_ci NOT NULL,
  `user_login_register` char(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_email_register` char(64) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_lang` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `user_comment_pref` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `user_karma` decimal(10,2) DEFAULT '6.00',
  `user_public_info` char(64) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_url` char(128) COLLATE utf8_spanish_ci NOT NULL,
  `user_adcode` char(24) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_adchannel` char(12) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_phone` char(16) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_level`, `user_avatar`, `user_modification`, `user_date`, `user_validated_date`, `user_ip`, `user_pass`, `user_email`, `user_names`, `user_login_register`, `user_email_register`, `user_lang`, `user_comment_pref`, `user_karma`, `user_public_info`, `user_url`, `user_adcode`, `user_adchannel`, `user_phone`) VALUES
(1, 'Admin', 'god', 1553497037, '2019-04-20 11:09:27', '2019-03-23 03:10:19', '2019-03-23 03:11:19', '127.0.0.1', 'sha256:nMgzJb0TMXD51oYbRYpKwSo+wV51hB4s:2ed0b13f52955c4aab3e10a0bd72171d331928763f45e8dc614dfe299cb11039', 'aaa@aaaa.ru', 'Евгений', 'Admin', 'aaa@aaaa.ru', 1, 0, '17.54', '', 'http://aaaa.ru', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `users_similarities`
--

CREATE TABLE IF NOT EXISTS `users_similarities` (
  `minor` int(10) unsigned NOT NULL,
  `major` int(10) unsigned NOT NULL,
  `value` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `vote_id` int(20) NOT NULL,
  `vote_type` enum('links','comments','posts','polls','users','sites','ads') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'links',
  `vote_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vote_link_id` int(20) NOT NULL DEFAULT '0',
  `vote_user_id` int(20) NOT NULL DEFAULT '0',
  `vote_value` smallint(11) NOT NULL DEFAULT '1',
  `vote_ip_int` decimal(39,0) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci PACK_KEYS=0;

--
-- Дамп данных таблицы `votes`
--

INSERT INTO `votes` (`vote_id`, `vote_type`, `vote_date`, `vote_link_id`, `vote_user_id`, `vote_value`, `vote_ip_int`) VALUES
(1, 'links', '2019-04-21 19:46:48', 1, 1, 18, '2130706433');

-- --------------------------------------------------------

--
-- Структура таблицы `votes_summary`
--

CREATE TABLE IF NOT EXISTS `votes_summary` (
  `votes_year` smallint(4) NOT NULL,
  `votes_month` tinyint(2) NOT NULL,
  `votes_type` char(10) NOT NULL,
  `votes_maxid` int(11) NOT NULL,
  `votes_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_date` (`log_date`);

--
-- Индексы таблицы `admin_posts`
--
ALTER TABLE `admin_posts`
  ADD KEY `admin_post` (`admin_post_id`,`admin_user_id`);

--
-- Индексы таблицы `admin_sections`
--
ALTER TABLE `admin_sections`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_users_admin_id` (`admin_id`),
  ADD KEY `fk_admin_users_section_id` (`section_id`);

--
-- Индексы таблицы `annotations`
--
ALTER TABLE `annotations`
  ADD PRIMARY KEY (`annotation_key`),
  ADD KEY `annotation_expire` (`annotation_expire`);

--
-- Индексы таблицы `auths`
--
ALTER TABLE `auths`
  ADD UNIQUE KEY `service` (`service`,`uid`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_2` (`service`,`username`);

--
-- Индексы таблицы `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`avatar_id`);

--
-- Индексы таблицы `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `related` (`related_table`,`related_id`),
  ADD KEY `fk_backups_user_id` (`user_id`);

--
-- Индексы таблицы `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`ban_id`),
  ADD UNIQUE KEY `ban_type` (`ban_type`,`ban_text`),
  ADD KEY `expire` (`ban_expire`);

--
-- Индексы таблицы `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`),
  ADD UNIQUE KEY `key` (`blog_key`),
  ADD KEY `blog_url` (`blog_url`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category__auto_id`),
  ADD UNIQUE KEY `category_lang` (`category_lang`,`category_id`),
  ADD UNIQUE KEY `id` (`category_id`);

--
-- Индексы таблицы `chats`
--
ALTER TABLE `chats`
  ADD KEY `chat_time` (`chat_time`) USING BTREE;

--
-- Индексы таблицы `clones`
--
ALTER TABLE `clones`
  ADD PRIMARY KEY (`clon_from`,`clon_to`,`clon_ip`),
  ADD KEY `to_date` (`clon_to`,`clon_date`),
  ADD KEY `from_date` (`clon_from`,`clon_date`),
  ADD KEY `clon_date` (`clon_date`),
  ADD KEY `clon_ip` (`clon_ip`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_link_id_2` (`comment_link_id`,`comment_date`),
  ADD KEY `comment_date` (`comment_date`),
  ADD KEY `comment_user_id` (`comment_user_id`,`comment_date`),
  ADD KEY `comment_link_id` (`comment_link_id`,`comment_order`);

--
-- Индексы таблицы `conversations`
--
ALTER TABLE `conversations`
  ADD KEY `conversation_type` (`conversation_type`,`conversation_from`),
  ADD KEY `conversation_time` (`conversation_time`),
  ADD KEY `conversation_type_2` (`conversation_type`,`conversation_to`),
  ADD KEY `conversation_user_to` (`conversation_user_to`,`conversation_type`,`conversation_time`),
  ADD KEY `conversation_type_3` (`conversation_type`,`conversation_user_to`);

--
-- Индексы таблицы `counts`
--
ALTER TABLE `counts`
  ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD UNIQUE KEY `favorite_user_id_2` (`favorite_user_id`,`favorite_type`,`favorite_link_id`),
  ADD KEY `favorite_type` (`favorite_type`,`favorite_link_id`);

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD UNIQUE KEY `friend_type` (`friend_type`,`friend_from`,`friend_to`),
  ADD KEY `friend_type_3` (`friend_type`,`friend_to`,`friend_date`);

--
-- Индексы таблицы `geo_links`
--
ALTER TABLE `geo_links`
  ADD UNIQUE KEY `geo_id` (`geo_id`);

--
-- Индексы таблицы `geo_users`
--
ALTER TABLE `geo_users`
  ADD UNIQUE KEY `geo_id` (`geo_id`);

--
-- Индексы таблицы `html_images_seen`
--
ALTER TABLE `html_images_seen`
  ADD PRIMARY KEY (`hash`);

--
-- Индексы таблицы `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`language_id`),
  ADD UNIQUE KEY `language_name` (`language_name`);

--
-- Индексы таблицы `league`
--
ALTER TABLE `league`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `league_matches`
--
ALTER TABLE `league_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `league_id` (`league_id`),
  ADD KEY `league_id_2` (`league_id`,`date`);

--
-- Индексы таблицы `league_teams`
--
ALTER TABLE `league_teams`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `league_terms`
--
ALTER TABLE `league_terms`
  ADD PRIMARY KEY (`user_id`,`vendor`);

--
-- Индексы таблицы `league_votes`
--
ALTER TABLE `league_votes`
  ADD UNIQUE KEY `match_id` (`match_id`,`user_id`),
  ADD KEY `sort_index` (`match_id`,`date`);

--
-- Индексы таблицы `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_url` (`link_url`),
  ADD KEY `link_uri` (`link_uri`),
  ADD KEY `link_blog` (`link_blog`),
  ADD KEY `link_author` (`link_author`,`link_date`),
  ADD KEY `link_date` (`link_date`);

--
-- Индексы таблицы `link_clicks`
--
ALTER TABLE `link_clicks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `link_commons`
--
ALTER TABLE `link_commons`
  ADD UNIQUE KEY `link` (`link`),
  ADD KEY `created` (`created`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_date` (`log_date`),
  ADD KEY `log_type` (`log_type`,`log_ref_id`),
  ADD KEY `log_type_2` (`log_type`,`log_date`);

--
-- Индексы таблицы `log_pos`
--
ALTER TABLE `log_pos`
  ADD PRIMARY KEY (`host`);

--
-- Индексы таблицы `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`type`,`id`,`version`),
  ADD KEY `user` (`user`,`type`,`date`),
  ADD KEY `type` (`type`,`version`,`date`),
  ADD KEY `user_2` (`user`,`date`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`user`,`type`);

--
-- Индексы таблицы `pageloads`
--
ALTER TABLE `pageloads`
  ADD PRIMARY KEY (`date`,`type`);

--
-- Индексы таблицы `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_polls_link_id` (`link_id`),
  ADD KEY `fk_polls_post_id` (`post_id`);

--
-- Индексы таблицы `polls_options`
--
ALTER TABLE `polls_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_polls_options_poll_id` (`poll_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_date` (`post_date`),
  ADD KEY `post_user_id` (`post_user_id`,`post_date`);

--
-- Индексы таблицы `prefs`
--
ALTER TABLE `prefs`
  ADD KEY `pref_user_id` (`pref_user_id`,`pref_key`);

--
-- Индексы таблицы `preguntame`
--
ALTER TABLE `preguntame`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `privates`
--
ALTER TABLE `privates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`,`date`),
  ADD KEY `to_2` (`to`,`read`),
  ADD KEY `to` (`to`,`date`),
  ADD KEY `date` (`date`);

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `report_date` (`report_date`);

--
-- Индексы таблицы `rss`
--
ALTER TABLE `rss`
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `date` (`date`),
  ADD KEY `blog_id` (`blog_id`,`date`),
  ADD KEY `user_id` (`user_id`,`date`);

--
-- Индексы таблицы `sneakers`
--
ALTER TABLE `sneakers`
  ADD UNIQUE KEY `sneaker_id` (`sneaker_id`);

--
-- Индексы таблицы `sph_counter`
--
ALTER TABLE `sph_counter`
  ADD PRIMARY KEY (`counter_id`);

--
-- Индексы таблицы `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sponsors_link` (`link`),
  ADD KEY `fk_sponsors_admin_id` (`admin_id`);

--
-- Индексы таблицы `strikes`
--
ALTER TABLE `strikes`
  ADD PRIMARY KEY (`strike_id`),
  ADD KEY `strike_date` (`strike_date`);

--
-- Индексы таблицы `subs`
--
ALTER TABLE `subs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `owner` (`owner`);

--
-- Индексы таблицы `subs_copy`
--
ALTER TABLE `subs_copy`
  ADD UNIQUE KEY `uni` (`src`,`dst`),
  ADD KEY `dst_i` (`dst`);

--
-- Индексы таблицы `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD UNIQUE KEY `category_id` (`category`,`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `sub_statuses`
--
ALTER TABLE `sub_statuses`
  ADD UNIQUE KEY `link_id` (`link`,`id`),
  ADD KEY `date_status_id` (`date`,`status`,`id`),
  ADD KEY `id_status_date` (`id`,`status`,`date`);

--
-- Индексы таблицы `tags`
--
ALTER TABLE `tags`
  ADD UNIQUE KEY `tag_link_id` (`tag_link_id`,`tag_lang`,`tag_words`),
  ADD KEY `tag_lang` (`tag_lang`,`tag_date`);

--
-- Индексы таблицы `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`key`,`id`);

--
-- Индексы таблицы `trackbacks`
--
ALTER TABLE `trackbacks`
  ADD PRIMARY KEY (`trackback_id`),
  ADD UNIQUE KEY `trackback_link_id_2` (`trackback_link_id`,`trackback_type`,`trackback_link`),
  ADD KEY `trackback_link_id` (`trackback_link_id`),
  ADD KEY `trackback_url` (`trackback_url`),
  ADD KEY `trackback_date` (`trackback_date`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_login` (`user_login`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `user_karma` (`user_karma`),
  ADD KEY `user_public_info` (`user_public_info`),
  ADD KEY `user_phone` (`user_phone`),
  ADD KEY `user_date` (`user_date`),
  ADD KEY `user_modification` (`user_modification`),
  ADD KEY `user_email_register` (`user_email_register`),
  ADD KEY `user_url` (`user_url`);

--
-- Индексы таблицы `users_similarities`
--
ALTER TABLE `users_similarities`
  ADD UNIQUE KEY `minor` (`minor`,`major`),
  ADD KEY `date` (`date`);

--
-- Индексы таблицы `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `vote_type` (`vote_type`,`vote_link_id`,`vote_user_id`,`vote_ip_int`),
  ADD KEY `vote_type_4` (`vote_type`,`vote_date`,`vote_user_id`),
  ADD KEY `vote_ip_int` (`vote_ip_int`),
  ADD KEY `vote_type_2` (`vote_type`,`vote_user_id`,`vote_date`);

--
-- Индексы таблицы `votes_summary`
--
ALTER TABLE `votes_summary`
  ADD UNIQUE KEY `votes_year` (`votes_year`,`votes_month`,`votes_type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `admin_sections`
--
ALTER TABLE `admin_sections`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `bans`
--
ALTER TABLE `bans`
  MODIFY `ban_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category__auto_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `languages`
--
ALTER TABLE `languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `league`
--
ALTER TABLE `league`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `league_matches`
--
ALTER TABLE `league_matches`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `league_teams`
--
ALTER TABLE `league_teams`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `links`
--
ALTER TABLE `links`
  MODIFY `link_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `polls_options`
--
ALTER TABLE `polls_options`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `preguntame`
--
ALTER TABLE `preguntame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `privates`
--
ALTER TABLE `privates`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `strikes`
--
ALTER TABLE `strikes`
  MODIFY `strike_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `subs`
--
ALTER TABLE `subs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `trackbacks`
--
ALTER TABLE `trackbacks`
  MODIFY `trackback_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin_users`
--
ALTER TABLE `admin_users`
  ADD CONSTRAINT `fk_admin_users_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_admin_users_section_id` FOREIGN KEY (`section_id`) REFERENCES `admin_sections` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `fk_backups_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `fk_polls_link_id` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_polls_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `polls_options`
--
ALTER TABLE `polls_options`
  ADD CONSTRAINT `fk_polls_options_poll_id` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sponsors`
--
ALTER TABLE `sponsors`
  ADD CONSTRAINT `fk_sponsors_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sponsors_link` FOREIGN KEY (`link`) REFERENCES `links` (`link_id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `subs_copy`
--
ALTER TABLE `subs_copy`
  ADD CONSTRAINT `subs_copy_ibfk_1` FOREIGN KEY (`src`) REFERENCES `subs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subs_copy_ibfk_2` FOREIGN KEY (`dst`) REFERENCES `subs` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sub_statuses`
--
ALTER TABLE `sub_statuses`
  ADD CONSTRAINT `sub_statuses_ibfk_1` FOREIGN KEY (`link`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
