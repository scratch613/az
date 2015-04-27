-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 27 2015 г., 21:51
-- Версия сервера: 5.5.43-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `az`
--
CREATE DATABASE IF NOT EXISTS `az` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `az`;

-- --------------------------------------------------------

--
-- Структура таблицы `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Очистить таблицу перед добавлением данных `profiles`
--

TRUNCATE TABLE `profiles`;
--
-- Дамп данных таблицы `profiles`
--

INSERT INTO `profiles` (`id`, `fname`, `mname`, `lname`, `nickname`, `email`, `phone`, `login`, `password`) VALUES
(1, 'Имс', 'Отчество', 'Фамилия', 'Никнейм', 'емейл', 'телефо', 'логин', 'ed7778c1c4a50e030a5ecb7670e689793e5edde6');

-- --------------------------------------------------------

--
-- Структура таблицы `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `external_id` varchar(32) NOT NULL,
  `req_type` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `status_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`req_type`),
  KEY `Owner` (`owner_id`),
  KEY `Status` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Очистить таблицу перед добавлением данных `request`
--

TRUNCATE TABLE `request`;
--
-- Дамп данных таблицы `request`
--

INSERT INTO `request` (`id`, `owner_id`, `external_id`, `req_type`, `nickname`, `created`, `updated`, `status_id`, `comment`) VALUES
(1, 1, '9204', 2, 'Дефиле', '2015-04-27 18:31:11', '2015-04-27 18:31:11', 1, 'Комментарий'),
(2, 1, '9209', 4, '223344', '2015-04-27 18:42:00', '2015-04-27 18:42:00', 1, 'werwerewr');

-- --------------------------------------------------------

--
-- Структура таблицы `req_types`
--

DROP TABLE IF EXISTS `req_types`;
CREATE TABLE IF NOT EXISTS `req_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `req_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `req_types`
--

TRUNCATE TABLE `req_types`;
--
-- Дамп данных таблицы `req_types`
--

INSERT INTO `req_types` (`id`, `req_name`) VALUES
(1, 'Постановка'),
(2, 'Дефиле'),
(3, 'Групповое дефиле'),
(4, 'Прочее');

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `statuses`
--

TRUNCATE TABLE `statuses`;
--
-- Дамп данных таблицы `statuses`
--

INSERT INTO `statuses` (`id`, `status`) VALUES
(1, 'В обработке'),
(2, 'Ожидает пользователя'),
(3, 'Принята'),
(4, 'Отклонена');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`req_type`) REFERENCES `req_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
