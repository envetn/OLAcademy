-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 21, 2015 at 12:29 AM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.6.15-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `olacademy`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventDate` date NOT NULL,
  `startTime` time NOT NULL,
  `eventName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `reccurance` tinyint(1) NOT NULL,
  `bus` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=48 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `eventDate`, `startTime`, `eventName`, `info`, `reccurance`, `bus`) VALUES
(26, '2015-11-16', '19:00:00', 'Mellan 10km och 20km', '2015-11-16', 0, 0),
(28, '2015-11-17', '18:30:00', 'Samling efter intervallerna i hallen', '2015-11-16', 1, 0),
(30, '2015-11-30', '20:00:00', 'Intervaller', 'Info', 1, 1),
(33, '2015-11-18', '18:00:00', 'Grillning', 'Tag med eget att grilla, finns grill', 1, 0),
(34, '2015-11-17', '05:20:16', 'Ã…terkommande', 'Detta Ã¤r en Ã¥terkommande trÃ¤ning', 1, 1),
(35, '2015-11-20', '17:00:00', 'Fredags trÃ¤ning', 'TrÃ¤ning pÃ¥ en fredag', 1, 0),
(36, '2015-11-20', '19:00:00', 'Bio', 'Tag med eget snacks', 1, 0),
(38, '2015-11-16', '11:00:20', '123123123', '123', 0, 1),
(39, '2015-11-24', '18:00:00', '7654', '8765', 0, 1),
(40, '2015-11-19', '17:00:00', '123123123', '123', 1, 1),
(41, '2015-11-20', '17:00:00', 'Fredags trÃ¤ning', 'TrÃ¤ning pÃ¥ en fredag', 1, 0),
(47, '2015-11-21', '18:00:00', '123', '2015-11-14', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `author` varchar(100) CHARACTER SET latin1 NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=79 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `author`, `added`) VALUES
(1, 'test', 'testestetetetn rltjh elkrhl kehkjhg jhgl jdhfljghdf lghfdklh glkdfhg lkjdfhglk jdfhgldkfjhg dkfjhgdklfjhg dklfjhg kldjfhg kdjfhg kjdlfhg lkjdhg', 'Lorem Ipsum', '2015-05-12 00:00:00'),
(5, 'Looooooooooooooorem', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras congue eros elementum diam fermentum sagittis. Nunc odio dolor, efficitur vitae imperdiet sed, maximus sed erat. Nulla sit amet venenatis nisl, in interdum nulla. Maecenas sit amet sapien non arcu fringilla molestie. Quisque ut mattis lorem. Maecenas vitae maximus orci. Aliquam hendrerit sagittis lacus, quis luctus ligula dapibus sit amet. Donec facilisis vel mauris in elementum. ', 'Me', '2015-05-13 00:00:00'),
(6, '123123', '123123121 312 3123 123 123 123 123 123 123 123 123 12 312 3123 123 123 12 3123 123 123 123 123 123 123 1', 'Lofie', '2015-05-20 00:00:00'),
(8, 'asd', 'asdasd asdsa', 'asdasdasd', '2015-05-20 00:00:00'),
(11, 'fu', 'fu', '111', '2015-09-11 00:00:00'),
(12, 'Test title0', 'Test content0', 'lofie', '2015-11-13 00:00:00'),
(13, 'Test title1', 'Test content1', 'lofie1', '2015-10-29 00:00:00'),
(14, 'Test title2', 'Test content2', 'lofie2', '2015-10-29 00:00:00'),
(15, 'Test title3', 'Test content3', 'lofie3', '2015-10-29 00:00:00'),
(16, 'Test title4', 'Test content4', 'lofie4', '2015-10-29 00:00:00'),
(17, 'Test title5', 'Test content5', 'lofie5', '2015-10-29 00:00:00'),
(18, 'Test title6', 'Test content6', 'lofie6', '2015-10-29 00:00:00'),
(19, 'Test title7', 'Test content7', 'lofie7', '2015-10-29 00:00:00'),
(20, 'Test title8', 'Test content8', 'lofie8', '2015-10-29 00:00:00'),
(21, 'Test title9', 'Test content9', 'lofie9', '2015-10-29 00:00:00'),
(22, 'Test title10', 'Test content10', 'lofie10', '2015-10-29 00:00:00'),
(23, 'Test title11', 'Test content11', 'lofie11', '2015-10-29 00:00:00'),
(24, 'Test title12', 'Test content12', 'lofie12', '2015-10-29 00:00:00'),
(25, 'Test title13', 'Test content13', 'lofie13', '2015-10-29 00:00:00'),
(26, 'Test title14', 'Test content14', 'lofie14', '2015-10-29 00:00:00'),
(27, 'Test title15', 'Test content15', 'lofie15', '2015-10-29 00:00:00'),
(28, 'Test title16', 'Test content16', 'lofie16', '2015-10-29 00:00:00'),
(29, 'Test title17', 'Test content17', 'lofie17', '2015-10-29 00:00:00'),
(30, 'Test title18', 'Test content18', 'lofie18', '2015-10-29 00:00:00'),
(31, 'Test title19', 'Test content19', 'lofie19', '2015-10-29 00:00:00'),
(32, 'Test title20', 'Test content20', 'lofie20', '2015-10-29 00:00:00'),
(33, 'Test title21', 'Test content21', 'lofie21', '2015-10-29 00:00:00'),
(34, 'Test title22', 'Test content22', 'lofie22', '2015-10-29 00:00:00'),
(35, 'Test title23', 'Test content23', 'lofie23', '2015-10-29 00:00:00'),
(36, 'Test title24', 'Test content24', 'lofie24', '2015-10-29 00:00:00'),
(37, 'Test title25', 'Test content25', 'lofie25', '2015-10-29 00:00:00'),
(38, 'Test title26', 'Test content26', 'lofie26', '2015-10-29 00:00:00'),
(39, 'Test title27', 'Test content27', 'lofie27', '2015-10-29 00:00:00'),
(40, 'Test title28', 'Test content28', 'lofie28', '2015-10-29 00:00:00'),
(41, 'Test title29', 'Test content29', 'lofie29', '2015-10-29 00:00:00'),
(42, 'Test title30', 'Test content30', 'lofie30', '2015-10-29 00:00:00'),
(43, 'Test title31', 'Test content31', 'lofie31', '2015-10-29 00:00:00'),
(44, 'Test title32', 'Test content32', 'lofie32', '2015-10-29 00:00:00'),
(45, 'Test title33', 'Test content33', 'lofie33', '2015-10-29 00:00:00'),
(46, 'Test title34', 'Test content34', 'lofie34', '2015-10-29 00:00:00'),
(47, 'Test title35', 'Test content35', 'lofie35', '2015-10-29 00:00:00'),
(48, 'Test title36', 'Test content36', 'lofie36', '2015-10-29 00:00:00'),
(49, 'Test title37', 'Test content37', 'lofie37', '2015-10-29 00:00:00'),
(50, 'Test title38', 'Test content38', 'lofie38', '2015-10-29 00:00:00'),
(51, 'Test title39', 'Test content39', 'lofie39', '2015-10-29 00:00:00'),
(52, 'Test title40', 'Test content40', 'lofie40', '2015-10-29 00:00:00'),
(53, 'Test title41', 'Test content41', 'lofie41', '2015-10-29 00:00:00'),
(54, 'Test title42', 'Test content42', 'lofie42', '2015-10-29 00:00:00'),
(55, 'Test title43', 'Test content43', 'lofie43', '2015-10-29 00:00:00'),
(56, 'Test title44', 'Test content44', 'lofie44', '2015-10-29 00:00:00'),
(57, 'Test title45', 'Test content45', 'lofie45', '2015-10-29 00:00:00'),
(58, 'Test title46', 'Test content46', 'lofie46', '2015-10-29 00:00:00'),
(59, 'Test title47', 'Test content47', 'lofie47', '2015-10-29 00:00:00'),
(60, 'Test title48', 'Test content48', 'lofie48', '2015-10-29 00:00:00'),
(61, 'Test title49', 'Test content49', 'lofie49', '2015-10-29 00:00:00'),
(64, 'Test title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae. ', 'lofie', '2015-11-14 00:00:00'),
(65, 'test 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14 00:00:00'),
(66, 'test 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14 00:00:00'),
(67, 'test 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14 00:00:00'),
(68, 'test 5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14 00:00:00'),
(69, 'test 5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=111 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name`, `text`, `added`) VALUES
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
(94, 'qwer', 'as', '2015-09-11 21:44:02'),
(95, 'asd', 'asd', '2015-11-08 17:25:17'),
(96, '123', '123', '2015-11-14 17:35:33'),
(97, 'Olof', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', '2015-11-14 17:35:48'),
(98, 'asd', 'asd', '2015-11-15 14:02:05'),
(99, 'asd', 'asd', '2015-11-15 14:03:58'),
(100, 'asd', 'asd', '2015-11-15 14:05:36'),
(101, 'asd', 'asd', '2015-11-15 14:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `registered`
--

DROP TABLE IF EXISTS `registered`;
CREATE TABLE IF NOT EXISTS `registered` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bus` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `eventID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `registered`
--

INSERT INTO `registered` (`id`, `userID`, `name`, `date`, `comment`, `bus`, `eventID`) VALUES
(5, 19, 'lofie', '2015-11-16', '', 'Ja', '30'),
(8, 19, 'lofie', '2015-11-20', '', 'Ja', '35'),
(9, 19, 'lofie', '2015-11-20', '', 'Ja', '36'),
(13, 19, 'lofie', '2015-11-16', '', 'Ja', '26'),
(14, 19, 'lofie', '2015-11-21', '', 'Nej', '22'),
(18, 21, 'adam', '2015-11-17', 'hej', 'Ja', '34'),
(19, 21, 'adam', '2015-11-17', '', 'Nej', '28'),
(20, 19, 'lofie', '2015-11-17', '', 'Nej', '28'),
(21, 19, 'lofie', '2015-11-17', '', 'Ja', '34'),
(34, 19, 'lofie', '2015-11-18', '', 'Nej', '33'),
(35, 20, 'lofielus', '2015-11-18', '', 'Nej', '33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `Privilege`, `regDate`, `token`) VALUES
(28, 'admin', '$2y$12$y9eOKspPsW1O1Tw9XjaK3.EVS25YMy8i5J9uhxTI9O41DjqwpM7ny', 'admin@admin.com', 0, '2015-11-19', ''),
(29, 'Lofielus', '$2y$12$WUwZLD69Q7sR8b1Mf6h.UevSRQDPxNXwrSDVIrbfB/p14EaJ7rUYy', 'lofie@lofie.com', 2, '2015-11-19', '99a3d93c0a722c3ee35435595976f5bbd5b3cc751a10509e9a5eee23ed870a8e'),
(30, 'Adam', '$2y$12$Ix9Oi//nwE.O02hUcvmwROgze.EXwX0q/piLok0UeJld41bJrCw2C', 'adamgeorgsson@gmail.com', 2, '2015-11-21', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
