-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Мар 16 2019 г., 01:20
-- Версия сервера: 5.5.25
-- Версия PHP: 5.6.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `u0670074_default`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `id_picture` int(11) NOT NULL,
  `id_author` int(11) NOT NULL,
  `number_of_change` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`id_picture`, `id_author`, `number_of_change`) VALUES
(1, 5, 1),
(1, 5, 2),
(1, 4, 3),
(2, 4, 1),
(1, 4, 4),
(2, 4, 2),
(2, 4, 3),
(2, 4, 4),
(1, 4, 5),
(2, 4, 5),
(1, 4, 6),
(2, 4, 6),
(5, 4, 1),
(5, 4, 2),
(5, 4, 3),
(5, 4, 4),
(5, 4, 5),
(5, 4, 6),
(12, 4, 1),
(12, 4, 2),
(12, 4, 3),
(12, 4, 4),
(12, 4, 5),
(12, 4, 6),
(14, 4, 1),
(14, 4, 2),
(14, 4, 3),
(14, 4, 4),
(14, 4, 5),
(14, 4, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `drawing_sessions`
--

CREATE TABLE IF NOT EXISTS `drawing_sessions` (
  `id_user` int(11) NOT NULL,
  `id_pic` int(11) NOT NULL,
  `date_of_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `number_of_change` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id_picture` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `likes`
--

INSERT INTO `likes` (`id_picture`, `id_user`) VALUES
(1, 4),
(2, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `moderators`
--

CREATE TABLE IF NOT EXISTS `moderators` (
  `moderator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `moderators`
--

INSERT INTO `moderators` (`moderator_id`) VALUES
(4),
(6);

-- --------------------------------------------------------

--
-- Структура таблицы `pics`
--

CREATE TABLE IF NOT EXISTS `pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count_of_changes` int(11) NOT NULL DEFAULT '0',
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `filepath` varchar(60) NOT NULL DEFAULT 'img/0.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Дамп данных таблицы `pics`
--

INSERT INTO `pics` (`id`, `count_of_changes`, `last_update`, `filepath`) VALUES
(1, 6, '2019-03-07 16:18:10', 'img/1_228975c81444229b216.34421826.png'),
(2, 6, '2019-03-07 16:27:54', 'img/2_232185c81468a0bf092.02462343.png'),
(5, 6, '2019-03-07 18:31:17', 'img/5_239435c8163756e9cd4.08412585.png'),
(12, 6, '2019-03-09 19:58:55', 'img/12_238635c841aff9d8a18.44002046.png'),
(14, 6, '2019-03-09 20:34:14', 'img/14_268005c842346bc7a27.10503755.png');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `user_id` int(11) NOT NULL,
  `tokenHash` varchar(65) NOT NULL,
  `submit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`user_id`, `tokenHash`, `submit_date`) VALUES
(4, '8e2c9592232cfb5ee8db9c05432c15b943f955229377de06b9f74147b4108927', '2019-03-15 21:13:16');

-- --------------------------------------------------------

--
-- Структура таблицы `suggestions`
--

CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_pic` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `text_arg_32` varchar(32) NOT NULL,
  `text_arg_text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(32) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`id`, `tag`, `description`) VALUES
(1, 'picture', 'Basic picture tag.'),
(2, 'abstract', 'Some abstract tag.'),
(3, '!', 'The exclamation point is used to depict comical shock or surprise.'),
(4, '!!', 'Double exclamation points often express surprise or shock or sudden pain.'),
(5, '!?', 'A combination of the exclamation and question marks, conveying both surprise and wonder/disbelief at the same time.'),
(6, 'Text', 'A text in the image.'),
(7, 'lego', 'A very popular brand of interlocking bricks originating from Denmark.\r\n\r\nThey were invented by Ole Kirk Christiansen back in the 1940s. The name comes from the Danish word &quot; leg godt&quot; meaning &quot;play well&quot;.\r\n\r\nMany popular themes of Lego have been released throughout the decades, such as Fabuland, Technic, Star Wars and City.\r\n\r\nThey even have their own series of theme parks, called Legoland. The first one opened in Billund and has spread to other locations, such as Windsor, California, and Nagoya.'),
(8, 'sand', 'A loose granular substance, typically pale yellowish brown, resulting from the erosion of siliceous and other rocks and forming a major constituent of beaches, riverbeds, the seabed, and deserts.\r\n');

-- --------------------------------------------------------

--
-- Структура таблицы `tags_of_pics`
--

CREATE TABLE IF NOT EXISTS `tags_of_pics` (
  `id_picture` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tags_of_pics`
--

INSERT INTO `tags_of_pics` (`id_picture`, `id_tag`) VALUES
(1, 1),
(1, 5),
(1, 2),
(2, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `passwordHash` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `passwordHash`) VALUES
(1, 'first_user', ''),
(2, 'second_user', ''),
(3, 'third_user', ''),
(4, 'sec', '$2y$10$vp6.c6KNr3imtKcCiJcNbOY7C9NL3Xo2ApNAGJeMkH9NODWfkG98i'),
(5, 'alter', '$2y$10$Fl1FQWZloN.yuKKomLKjtuS5fJdsyMb6RDEwZz0TZKzHMfpLH1bH.'),
(6, 'third_', '$2y$10$7jSFj67E5K.UUKXFM4LuOeefST5uRr598Y9K/l42YK8HmMpxnS5Ze'),
(7, 'forth', '$2y$10$x0AHlBX2yQLfiC4jNrMomua3PPeTXnO/2054n2/0j/b8LPK3C4FFW');

DELIMITER $$
--
-- События
--
CREATE DEFINER=`root`@`localhost` EVENT `reset_expired_tokens` ON SCHEDULE EVERY 1 DAY STARTS '2019-03-03 18:08:21' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN 

DELETE FROM sessions 
WHERE now()>date_sub(submit_date,interval -1 MONTH); 

END$$

CREATE DEFINER=`root`@`localhost` EVENT `reset_gallery` ON SCHEDULE EVERY 35 SECOND STARTS '2019-03-15 23:03:45' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN 

DELETE FROM pics 
WHERE id in (SELECT id_pic FROM drawing_sessions WHERE now()>date_sub(date_of_start,interval -35 second) and number_of_change = 0); 

DELETE FROM drawing_sessions 
WHERE now()>date_sub(date_of_start,interval -35 second); 

END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
