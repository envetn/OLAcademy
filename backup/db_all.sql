-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 12 sep 2015 kl 20:33
-- Serverversion: 5.5.44-0ubuntu0.14.04.1
-- PHP-version: 5.5.9-1ubuntu4.11

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

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `startTime` time NOT NULL,
  `eventName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumpning av Data i tabell `events`
--

INSERT INTO `events` (`id`, `date`, `startTime`, `eventName`, `info`) VALUES
(7, '2015-09-11', '17:30:00', 'BacklÃ¶pning', 'Samling Bryggarberget'),
(8, '2015-09-11', '19:00:00', 'Styrka', 'Samling Gymmet'),
(9, '2015-09-14', '18:00:00', 'Rehab', 'cockpushups');

-- --------------------------------------------------------

--
-- Tabellstruktur `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `author` varchar(100) CHARACTER SET latin1 NOT NULL,
  `added` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumpning av Data i tabell `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `author`, `added`) VALUES
(1, 'test', 'testestetetetn rltjh elkrhl kehkjhg jhgl jdhfljghdf lghfdklh glkdfhg lkjdfhglk jdfhgldkfjhg dkfjhgdklfjhg dklfjhg kldjfhg kdjfhg kjdlfhg lkjdhg', 'Lorem Ipsum', '2015-05-12'),
(2, 'Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae. \r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'Lorem Ipsum', '2015-05-01'),
(5, 'Looooooooooooooorem', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras congue eros elementum diam fermentum sagittis. Nunc odio dolor, efficitur vitae imperdiet sed, maximus sed erat. Nulla sit amet venenatis nisl, in interdum nulla. Maecenas sit amet sapien non arcu fringilla molestie. Quisque ut mattis lorem. Maecenas vitae maximus orci. Aliquam hendrerit sagittis lacus, quis luctus ligula dapibus sit amet. Donec facilisis vel mauris in elementum. ', 'Me', '2015-05-13'),
(6, '123123', '123123121 312 3123 123 123 123 123 123 123 123 123 12 312 3123 123 123 12 3123 123 123 123 123 123 123 1', '23123123123123 123 123 1', '2015-05-20'),
(7, 'sssssssssssssssssss ss  s', 'ssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s vssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s ssssss ss  s v vv ssssss ss  s', 'ssssss ss  s', '2015-05-06'),
(8, 'asd', 'asdasd asdsa', 'asdasdasd', '2015-05-20'),
(9, 'vbadsf', 'assass', '111', '2015-09-11'),
(10, '234', 'sadf', '111', '2015-09-11'),
(11, 'fu', 'fu', '111', '2015-09-11');

-- --------------------------------------------------------

--
-- Tabellstruktur `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=95 ;

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
(92, 'test', 'test', '2015-05-11 15:45:00'),
(94, 'qwer', 'as', '2015-09-11 21:44:02');

-- --------------------------------------------------------

--
-- Tabellstruktur `registered`
--

DROP TABLE IF EXISTS `registered`;
CREATE TABLE IF NOT EXISTS `registered` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `other` varchar(255) NOT NULL,
  `eventId` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=115 ;

--
-- Dumpning av Data i tabell `registered`
--

INSERT INTO `registered` (`id`, `name`, `date`, `other`, `eventId`) VALUES
(113, '123', '2015-09-12', '', 'eventId'),
(114, '123', '2015-09-12', '', '9');

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Privilege` int(11) NOT NULL,
  `regDate` date NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `Privilege`, `regDate`, `token`) VALUES
(10, '111', 'e6e010710c72a7e3dfd02ab27b451f56', '123@123.com', 1, '2015-05-09', '3d97aef83ce05ef37e16ded33b9b24520f39294eb14085f3094e8aa95657d463'),
(11, 'nisse', 'cd523f1e4ca6409ea8f028dc04172f2f', '123@123.com', 2, '2015-05-21', '3561fe9bcb5e1d48f3899589765b9586909687f57438f956be428b1fc6ca542b'),
(13, '123', 'c05a25cb543f8425ce9f74bb650618b5', '', 0, '2015-05-21', 'cbd7bfb2120ea5953f0ecd0bf7b022551e8886b5f9cfd3c037497d63ea6978f3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
