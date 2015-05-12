-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 11 maj 2015 kl 16:36
-- Serverversion: 5.6.17
-- PHP-version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `olacademy`
--
CREATE DATABASE IF NOT EXISTS `olacademy` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `olacademy`;

-- --------------------------------------------------------

--
-- Tabellstruktur `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `startTime` time NOT NULL,
  `eventName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumpning av Data i tabell `events`
--

INSERT INTO `events` (`id`, `date`, `startTime`, `eventName`, `info`) VALUES
(7, '2015-05-11', '17:30:00', 'BacklÃ¶pning', 'Samling Bryggarberget'),
(8, '2015-05-11', '19:00:00', 'Styrka', 'Samling Gymmet');

-- --------------------------------------------------------

--
-- Tabellstruktur `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=94 ;

--
-- Dumpning av Data i tabell `posts`
--

INSERT INTO `posts` (`id`, `name`, `text`, `date`) VALUES
(74, 'ojh', 'jb', '0000-00-00 00:00:00'),
(75, 'dsf', 'dsf', '0000-00-00 00:00:00'),
(76, 'dsf', 'dsf', '0000-00-00 00:00:00'),
(77, 'dsf', 'dsf', '2015-05-09 22:46:24'),
(78, 'asd', 'asd', '2015-05-09 22:46:39'),
(81, 'hej', 'dsf', '2015-05-09 22:51:58'),
(82, 'hej', 'dsf', '0000-00-00 00:00:00'),
(83, 'hej', 'dsf', '2015-05-09 23:00:30'),
(84, 'hej', 'dsf', '2015-05-09 23:01:57'),
(85, 'wer', 'df', '2015-05-09 23:07:12'),
(86, 'wer', 'df', '2015-05-09 23:07:33'),
(87, 'wer', 'df', '2015-05-09 23:09:48'),
(88, 'wer', 'df', '2015-05-09 23:10:05'),
(89, 'wer', 'df', '2015-05-09 23:11:11'),
(90, 'wer', 'df', '2015-05-09 23:13:44'),
(91, 'Olof', 'Jag Ã¤r trÃ¶tt\r\n', '2015-05-10 00:46:23'),
(92, 'test', 'test', '2015-05-11 15:45:00');

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Privilege` int(11) NOT NULL,
  `regDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `Privilege`, `regDate`) VALUES
(1, 'Nisse', '123', '123@123.com', 1, '2015-05-13');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
