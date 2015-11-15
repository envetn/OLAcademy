-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 15, 2015 at 06:02 PM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.5.9-1ubuntu4.14

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
  `date` date NOT NULL,
  `startTime` time NOT NULL,
  `eventName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `reccurance` tinyint(1) NOT NULL,
  `bus` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `date`, `startTime`, `eventName`, `info`, `reccurance`, `bus`) VALUES
(22, '2015-11-21', '18:00:00', '123', '2015-11-14', 1, 0),
(26, '0000-00-00', '19:00:00', 'Mellan 10km och 20km', '2015-11-16', 0, 0),
(28, '0000-00-00', '18:30:00', 'Samling efter intervallerna i hallen', '2015-11-16', 1, 0),
(30, '2015-11-30', '20:00:00', 'Intervaller', 'Info', 1, 1),
(33, '2015-11-18', '18:00:00', 'Grillning', 'Tag med eget att grilla, finns grill', 1, 0),
(34, '2015-11-17', '05:20:16', 'Ã…terkommande', 'Detta Ã¤r en Ã¥terkommande trÃ¤ning', 1, 1),
(35, '2015-11-20', '17:00:00', 'Fredags trÃ¤ning', 'TrÃ¤ning pÃ¥ en fredag', 1, 0),
(36, '2015-11-20', '19:00:00', 'Bio', 'Tag med eget snacks', 1, 0);

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
  `added` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=70 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `author`, `added`) VALUES
(1, 'test', 'testestetetetn rltjh elkrhl kehkjhg jhgl jdhfljghdf lghfdklh glkdfhg lkjdfhglk jdfhgldkfjhg dkfjhgdklfjhg dklfjhg kldjfhg kdjfhg kjdlfhg lkjdhg', 'Lorem Ipsum', '2015-05-12'),
(2, 'Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae. \r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14'),
(5, 'Looooooooooooooorem', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras congue eros elementum diam fermentum sagittis. Nunc odio dolor, efficitur vitae imperdiet sed, maximus sed erat. Nulla sit amet venenatis nisl, in interdum nulla. Maecenas sit amet sapien non arcu fringilla molestie. Quisque ut mattis lorem. Maecenas vitae maximus orci. Aliquam hendrerit sagittis lacus, quis luctus ligula dapibus sit amet. Donec facilisis vel mauris in elementum. ', 'Me', '2015-05-13'),
(6, '123123', '123123121 312 3123 123 123 123 123 123 123 123 123 12 312 3123 123 123 12 3123 123 123 123 123 123 123 1', 'Lofie', '2015-05-20'),
(8, 'asd', 'asdasd asdsa', 'asdasdasd', '2015-05-20'),
(11, 'fu', 'fu', '111', '2015-09-11'),
(12, 'Test title0', 'Test content0', 'lofie', '2015-11-13'),
(13, 'Test title1', 'Test content1', 'lofie1', '2015-10-29'),
(14, 'Test title2', 'Test content2', 'lofie2', '2015-10-29'),
(15, 'Test title3', 'Test content3', 'lofie3', '2015-10-29'),
(16, 'Test title4', 'Test content4', 'lofie4', '2015-10-29'),
(17, 'Test title5', 'Test content5', 'lofie5', '2015-10-29'),
(18, 'Test title6', 'Test content6', 'lofie6', '2015-10-29'),
(19, 'Test title7', 'Test content7', 'lofie7', '2015-10-29'),
(20, 'Test title8', 'Test content8', 'lofie8', '2015-10-29'),
(21, 'Test title9', 'Test content9', 'lofie9', '2015-10-29'),
(22, 'Test title10', 'Test content10', 'lofie10', '2015-10-29'),
(23, 'Test title11', 'Test content11', 'lofie11', '2015-10-29'),
(24, 'Test title12', 'Test content12', 'lofie12', '2015-10-29'),
(25, 'Test title13', 'Test content13', 'lofie13', '2015-10-29'),
(26, 'Test title14', 'Test content14', 'lofie14', '2015-10-29'),
(27, 'Test title15', 'Test content15', 'lofie15', '2015-10-29'),
(28, 'Test title16', 'Test content16', 'lofie16', '2015-10-29'),
(29, 'Test title17', 'Test content17', 'lofie17', '2015-10-29'),
(30, 'Test title18', 'Test content18', 'lofie18', '2015-10-29'),
(31, 'Test title19', 'Test content19', 'lofie19', '2015-10-29'),
(32, 'Test title20', 'Test content20', 'lofie20', '2015-10-29'),
(33, 'Test title21', 'Test content21', 'lofie21', '2015-10-29'),
(34, 'Test title22', 'Test content22', 'lofie22', '2015-10-29'),
(35, 'Test title23', 'Test content23', 'lofie23', '2015-10-29'),
(36, 'Test title24', 'Test content24', 'lofie24', '2015-10-29'),
(37, 'Test title25', 'Test content25', 'lofie25', '2015-10-29'),
(38, 'Test title26', 'Test content26', 'lofie26', '2015-10-29'),
(39, 'Test title27', 'Test content27', 'lofie27', '2015-10-29'),
(40, 'Test title28', 'Test content28', 'lofie28', '2015-10-29'),
(41, 'Test title29', 'Test content29', 'lofie29', '2015-10-29'),
(42, 'Test title30', 'Test content30', 'lofie30', '2015-10-29'),
(43, 'Test title31', 'Test content31', 'lofie31', '2015-10-29'),
(44, 'Test title32', 'Test content32', 'lofie32', '2015-10-29'),
(45, 'Test title33', 'Test content33', 'lofie33', '2015-10-29'),
(46, 'Test title34', 'Test content34', 'lofie34', '2015-10-29'),
(47, 'Test title35', 'Test content35', 'lofie35', '2015-10-29'),
(48, 'Test title36', 'Test content36', 'lofie36', '2015-10-29'),
(49, 'Test title37', 'Test content37', 'lofie37', '2015-10-29'),
(50, 'Test title38', 'Test content38', 'lofie38', '2015-10-29'),
(51, 'Test title39', 'Test content39', 'lofie39', '2015-10-29'),
(52, 'Test title40', 'Test content40', 'lofie40', '2015-10-29'),
(53, 'Test title41', 'Test content41', 'lofie41', '2015-10-29'),
(54, 'Test title42', 'Test content42', 'lofie42', '2015-10-29'),
(55, 'Test title43', 'Test content43', 'lofie43', '2015-10-29'),
(56, 'Test title44', 'Test content44', 'lofie44', '2015-10-29'),
(57, 'Test title45', 'Test content45', 'lofie45', '2015-10-29'),
(58, 'Test title46', 'Test content46', 'lofie46', '2015-10-29'),
(59, 'Test title47', 'Test content47', 'lofie47', '2015-10-29'),
(60, 'Test title48', 'Test content48', 'lofie48', '2015-10-29'),
(61, 'Test title49', 'Test content49', 'lofie49', '2015-10-29'),
(63, '123', '123', 'lofie', '2015-11-14'),
(64, 'Test title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae. ', 'lofie', '2015-11-14'),
(65, 'test 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14'),
(66, 'test 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14'),
(67, 'test 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14'),
(68, 'test 5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14'),
(69, 'test 5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae.\r\n\r\nSed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'lofie', '2015-11-14');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=102 ;

--
-- Dumping data for table `posts`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `registered`
--

INSERT INTO `registered` (`id`, `userID`, `name`, `date`, `comment`, `bus`, `eventID`) VALUES
(4, 19, 'lofie', '2015-11-16', '', 'Ja', '28'),
(5, 19, 'lofie', '2015-11-16', '', 'Ja', '30'),
(7, 19, 'lofie', '2015-11-18', '', 'Ja', '33'),
(8, 19, 'lofie', '2015-11-20', '', 'Ja', '35'),
(9, 19, 'lofie', '2015-11-20', '', 'Ja', '36'),
(13, 19, 'lofie', '2015-11-16', '', 'Ja', '26'),
(14, 19, 'lofie', '2015-11-21', '', 'Nej', '22');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `Privilege`, `regDate`, `token`) VALUES
(17, 'Derpeus', '7b1d38174cc8c3c6176c41dd0caf8cce', 'lofie2@lofie.com', 0, '2015-10-26', ''),
(18, 'lofie3', '948d3a9e76a39ad77c1019eaad618f15', 'lofie3@lofie.com', 1, '2015-10-26', ''),
(19, 'lofie', 'e72d98c95b368f83ada8608ed12fc178', 'lofie@lofie.com', 2, '2015-10-26', ''),
(20, 'lofie4', '055c555208e5212ec8f9678c663a5d69', 'lofie4@lofie.com', 1, '2015-10-26', 'be3a750d22cb23ce91b8c4958e2fc89b4cf8203dad4c0c0d9d8a488df8681a07'),
(21, 'adam', 'c05a25cb543f8425ce9f74bb650618b5', 'damgeorgsson@gmail.com ', 2, '2015-11-15', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
