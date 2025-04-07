-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 26 2023 г., 11:51
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tovary`
--

-- --------------------------------------------------------

--
-- Структура таблицы `eltovary`
--

CREATE TABLE IF NOT EXISTS `eltovary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tname` varchar(30) NOT NULL,
  `kod` varchar(10) NOT NULL,
  `date_p` date NOT NULL,
  `opisanie` tinytext NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `kol_vo` smallint(6) NOT NULL DEFAULT '0',
  `photo` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `eltovary`
--

INSERT INTO `eltovary` (`id`, `tname`, `kod`, `date_p`, `opisanie`, `price`, `kol_vo`, `photo`) VALUES
(1, 'Вентилятор бытовой', 'AF90100023', '2021-02-24', 'Вентилятор бытовой. 3 режима работы', '1205.00', 400, ),
(2, 'Обогреватель масляный бытовой', 'AF90100024', '2021-02-28', 'Обогреватель масляный бытовой\r\n\r\n', '3200.00', 105, ),
(3, 'Электроплитка ', 'AS40500875', '2021-03-02', 'Электроплитка бытовая энергоэкономичная', '1811.80', 132, );
INSERT INTO `eltovary` (`id`, `tname`, `kod`, `date_p`, `opisanie`, `price`, `kol_vo`, `photo`) VALUES
(4, 'Печь микроволновая', 'SR02038777', '2021-03-03', 'Печь микроволновая многофункциональная\r\n', '3200.00', 52, ),
(5, 'Электроплитка', 'FR34008765', '2021-03-14', 'Электроплитка бытовая энергоэкономичная', '1860.00', 50, ),
(6, 'Обогреватель масляный бытовой', 'AS89077145', '2021-04-12', 'Обогреватель масляный бытовой\n', '4050.00', 60, ),
(12, 'Печь микроволновая', 'FD33090872', '2021-04-14', 'Печь микроволновая бытовая', '3800.00', 42, );
INSERT INTO `eltovary` (`id`, `tname`, `kod`, `date_p`, `opisanie`, `price`, `kol_vo`, `photo`) VALUES
(13, 'Чайник электрический', 'AS34000299', '2021-05-25', 'Чайник электрический 2-х литровый', '802.70', 39, ),
(14, 'Электроплитка ', 'TF03024234', '2021-05-27', 'Электроплитка бытовая энергоэкономичная', '1905.00', 127, ),
(20, 'Чайник электрический', 'DT98409120', '2021-06-06', 'Чайник электрический. Объем 1,8 литра', '922.00', 52, );
INSERT INTO `eltovary` (`id`, `tname`, `kod`, `date_p`, `opisanie`, `price`, `kol_vo`, `photo`) VALUES
(21, 'Печь микроволновая бытовая', 'AZ77880123', '2021-06-06', 'Печь микроволновая бытовая универсальная. 6 программных режимов', '3420.00', 770, );
INSERT INTO `eltovary` (`id`, `tname`, `kod`, `date_p`, `opisanie`, `price`, `kol_vo`, `photo`) VALUES
(26, 'Вентилятор бытовой\n', 'GT560012DD', '2021-07-03', 'Вентилятор бытовой. 3 режима работы', '1210.80', 19, ),
(27, 'Лампа настольная', 'DF09089090', '2021-07-03', 'Лампа настольная домашняя эргономичная', '1390.90', 56, );

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
