-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 29 2020 г., 18:01
-- Версия сервера: 5.7.18-0ubuntu0.16.04.1
-- Версия PHP: 5.6.30-12~ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_sloteg`
--

-- --------------------------------------------------------

--
-- Структура таблицы `prizegot`
--

CREATE TABLE `prizegot` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `PrizeID` int(11) NOT NULL,
  `Cost` decimal(10,0) DEFAULT NULL,
  `ActionID` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prizegot`
--

INSERT INTO `prizegot` (`ID`, `UserID`, `Date`, `PrizeID`, `Cost`, `ActionID`) VALUES
(1, 1, '2020-06-29 15:09:41', 2, '10', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `prizepraw`
--

CREATE TABLE `prizepraw` (
  `PrizeTypeID` int(11) NOT NULL,
  `Probability` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prizepraw`
--

INSERT INTO `prizepraw` (`PrizeTypeID`, `Probability`) VALUES
(1, 5),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `prizes`
--

CREATE TABLE `prizes` (
  `ID` int(11) NOT NULL,
  `TypeID` tinyint(2) NOT NULL,
  `img` varchar(255) COLLATE utf8_bin NOT NULL,
  `desc` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `CreateDate` datetime NOT NULL,
  `Avail` decimal(10,2) DEFAULT NULL,
  `TheUse` decimal(10,2) DEFAULT NULL,
  `MinCost` decimal(10,2) DEFAULT NULL,
  `MaxCost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prizes`
--

INSERT INTO `prizes` (`ID`, `TypeID`, `img`, `desc`, `CreateDate`, `Avail`, `TheUse`, `MinCost`, `MaxCost`) VALUES
(1, 1, 'lpoints', 'Балы лояльности', '2020-06-29 00:00:00', NULL, '2049.00', '1.00', '100.00'),
(2, 2, 'money', 'Деньги', '2020-06-29 00:00:00', '1000.00', '148.00', '1.00', '10.00'),
(3, 3, 'priz', 'Миксер', '2020-06-29 00:00:00', '10.00', '10.00', NULL, NULL),
(4, 3, 'priz', 'Пылесос', '2020-06-29 00:00:00', '5.00', '5.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `prizetype`
--

CREATE TABLE `prizetype` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prizetype`
--

INSERT INTO `prizetype` (`ID`, `name`) VALUES
(1, 'Балы лояльности'),
(2, 'Денежный приз'),
(3, 'Приз');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(50) COLLATE utf8_bin NOT NULL,
  `authKey` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `accessToken` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Name` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `LastNmae` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `bpoint` decimal(10,2) NOT NULL,
  `Address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `NewUser` tinyint(1) NOT NULL DEFAULT '1',
  `CreateDate` datetime NOT NULL,
  `ChangeDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `authKey`, `accessToken`, `Name`, `LastNmae`, `bpoint`, `Address`, `NewUser`, `CreateDate`, `ChangeDate`) VALUES
(1, 'guvy', '1122', 'fysfdyusfyvhsd9f7vuyhdsfvhusif324', 'yw3yrwe7ydfy6fe9w7r32er3273erydfgyfewry87423r7ye9', 'Vitalii', 'Hura', '925.00', 'Osadni', 0, '2020-06-27 21:46:11', '2020-06-29 15:09:41');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `prizegot`
--
ALTER TABLE `prizegot`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Индексы таблицы `prizes`
--
ALTER TABLE `prizes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TypeID` (`TypeID`,`Avail`,`TheUse`);

--
-- Индексы таблицы `prizetype`
--
ALTER TABLE `prizetype`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `authKey` (`authKey`),
  ADD KEY `accessToken` (`accessToken`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `prizegot`
--
ALTER TABLE `prizegot`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prizes`
--
ALTER TABLE `prizes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `prizetype`
--
ALTER TABLE `prizetype`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
