-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Фев 01 2023 г., 13:03
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `bd_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mysystem`
--

CREATE TABLE IF NOT EXISTS `mysystem` (
  `id_sys` int(5) NOT NULL DEFAULT '0',
  `master` char(2) NOT NULL,
  `slovo1` char(10) NOT NULL,
  `slovo2` char(10) NOT NULL,
  `comment` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mysystem`
--

INSERT INTO `mysystem` (`id_sys`, `master`, `slovo1`, `slovo2`, `comment`) VALUES
(1, '', 'NewLogin', 'NewPass', 'New-Комментарий');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
