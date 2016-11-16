-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 16, 2016 at 08:03 PM
-- Server version: 5.5.53-0+deb8u1
-- PHP Version: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `olacademy`
--
CREATE DATABASE IF NOT EXISTS `olacademy` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `olacademy`;

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

DROP TABLE IF EXISTS `about`;
CREATE TABLE IF NOT EXISTS `about` (
`id` int(11) NOT NULL,
  `generalInfo` text COLLATE utf8_unicode_ci NOT NULL,
  `offerInfo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `additionalInfo` text COLLATE utf8_unicode_ci NOT NULL,
  `externalLinks` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `generalInfo`, `offerInfo`, `additionalInfo`, `externalLinks`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vel consectetur augue. Pellentesque scelerisque auctor felis. Maecenas tellus diam, imperdiet in aliquam a, venenatis eu justo. Cras lorem nulla, tempus nec consectetur quis, suscipit ac nibh. Vivamus non libero rutrum, dignissim nibh eget, ultrices neque. Vestibulum convallis pellentesque dolor, eget tempus ex sodales quis. Donec ac libero non sem efficitur sollicitudin. Curabitur posuere tellus sapien, et accumsan enim mollis et. Nam dictum vitae quam sed lacinia. Donec fermentum vehicula suscipit. Aliquam vel tincidunt elit. In quis erat felis. Curabitur tincidunt, enim at dapibus dapibus, libero turpis luctus dolor, ut malesuada velit ipsum nec urna. ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. @Vestibulum et ligula laoreet, ullamcorper libero et, sodales ante. @Ut sit amet ligula eget nibh efficitur consectetur sed vitae tellus.@Sed pharetra nunc vel eros hendrerit, at suscipit lacus moll', ' Duis sed ipsum sed ante condimentum iaculis sed ac augue. Sed bibendum venenatis dui, id finibus mauris fringilla ut. Donec sollicitudin sem ut justo ornare, pharetra imperdiet mauris interdum. Quisque porta sodales velit, eu rhoncus mi iaculis vitae. Suspendisse ut augue nec sapien luctus interdum vitae non tellus. Donec dapibus euismod lorem at ultrices. Ut pharetra vel purus a euismod. In quis elit in dui pharetra accumsan at vitae diam. Sed augue mi, sollicitudin et venenatis sed, pharetra id lacus. Praesent lobortis dolor rutrum, facilisis orci in, dapibus tortor. Quisque purus leo, blandit congue mauris ut, sodales venenatis est. Curabitur auctor sodales nulla sit amet interdum. Sed pretium sit amet lectus eu vehicula. Phasellus eu finibus lorem. Curabitur congue aliquet enim a venenatis. Sed dui neque, efficitur eu nunc euismod, finibus luctus mi. ', 'www.google.se@www.google.se@www.google.se');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
`id` int(11) NOT NULL,
  `eventDate` date NOT NULL,
  `startTime` time NOT NULL,
  `eventName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `reccurance` tinyint(1) NOT NULL,
  `bus` tinyint(1) NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `eventDate`, `startTime`, `eventName`, `info`, `reccurance`, `bus`, `createdBy`) VALUES
(56, '2016-11-19', '11:59:03', '123', '123123', 1, 0, 0),
(57, '2016-11-21', '12:04:52', '123123123 ', '123 12312 312 3123 123 12', 1, 1, 34),
(58, '2016-11-18', '12:38:49', 'Intervaller, lÃ¶pteknik och styrka', 'Fredagsmys med Martin pÃ¥ VÃ¤stra Mark.', 1, 0, 34),
(59, '2016-11-18', '07:36:56', 'tes', 'hohohohho', 1, 0, 37),
(60, '2016-11-15', '14:54:17', 'Intz', 'kom', 0, 0, 37);

-- --------------------------------------------------------

--
-- Table structure for table `ip_block`
--

DROP TABLE IF EXISTS `ip_block`;
CREATE TABLE IF NOT EXISTS `ip_block` (
`id` int(11) NOT NULL,
  `ipAddress` varchar(100) NOT NULL,
  `blockedUntill` date NOT NULL,
  `nameUsed` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
`id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `author` varchar(100) CHARACTER SET latin1 NOT NULL,
  `added` datetime NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `author`, `added`, `image`) VALUES
(1, 'test', 'testestetetetn rltjh elkrhl kehkjhg jhgl jdhfljghdf lghfdklh glkdfhg lkjdfhglk jdfhgldkfjhg dkfjhgdklfjhg dklfjhg kldjfhg kdjfhg kjdlfhg lkjdhg', 'Lorem Ipsum', '2015-05-12 00:00:00', ''),
(5, 'Looooooooooooooorem', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras congue eros elementum diam fermentum sagittis. Nunc odio dolor, efficitur vitae imperdiet sed, maximus sed erat. Nulla sit amet venenatis nisl, in interdum nulla. Maecenas sit amet sapien non arcu fringilla molestie. Quisque ut mattis lorem. Maecenas vitae maximus orci. Aliquam hendrerit sagittis lacus, quis luctus ligula dapibus sit amet. Donec facilisis vel mauris in elementum. ', 'Me', '2015-05-13 00:00:00', ''),
(6, '123123', '123123121 312 3123 123 123 123 123 123 123 123 123 12 312 3123 123 123 12 3123 123 123 123 123 123 123 1', 'Lofie', '2015-05-20 00:00:00', ''),
(8, 'asd', 'asdasd asdsa', 'asdasdasd', '2015-05-20 00:00:00', ''),
(11, 'fu', 'fu', '111', '2015-09-11 00:00:00', ''),
(12, 'Test title0', 'Test content0', 'lofie', '2015-11-13 00:00:00', ''),
(13, 'Test title1', 'Test content1', 'lofie1', '2015-10-29 00:00:00', ''),
(14, 'Test title2', 'Test content2', 'lofie2', '2015-10-29 00:00:00', ''),
(15, 'Test title3', 'Test content3', 'lofie3', '2015-10-29 00:00:00', ''),
(16, 'Test title4', 'Test content4', 'lofie4', '2015-10-29 00:00:00', ''),
(17, 'Test title5', 'Test content5', 'lofie5', '2015-10-29 00:00:00', ''),
(18, 'Test title6', 'Test content6', 'lofie6', '2015-10-29 00:00:00', ''),
(19, 'Test title7', 'Test content7', 'lofie7', '2015-10-29 00:00:00', ''),
(20, 'Test title8', 'Test content8', 'lofie8', '2015-10-29 00:00:00', ''),
(21, 'Test title9', 'Test content9', 'lofie9', '2015-10-29 00:00:00', ''),
(22, 'Test title10', 'Test content10', 'lofie10', '2015-10-29 00:00:00', ''),
(23, 'Test title11', 'Test content11', 'lofie11', '2015-10-29 00:00:00', ''),
(24, 'Test title12', 'Test content12', 'lofie12', '2015-10-29 00:00:00', ''),
(25, 'Test title13', 'Test content13', 'lofie13', '2015-10-29 00:00:00', ''),
(26, 'Test title14', 'Test content14', 'lofie14', '2015-10-29 00:00:00', ''),
(27, 'Test title15', 'Test content15', 'lofie15', '2015-10-29 00:00:00', ''),
(28, 'Test title16', 'Test content16', 'lofie16', '2015-10-29 00:00:00', ''),
(29, 'Test title17', 'Test content17', 'lofie17', '2015-10-29 00:00:00', ''),
(30, 'Test title18', 'Test content18', 'lofie18', '2015-10-29 00:00:00', ''),
(31, 'Test title19', 'Test content19', 'lofie19', '2015-10-29 00:00:00', ''),
(32, 'Test title20', 'Test content20', 'lofie20', '2015-10-29 00:00:00', ''),
(33, 'Test title21', 'Test content21', 'lofie21', '2015-10-29 00:00:00', ''),
(34, 'Test title22', 'Test content22', 'lofie22', '2015-10-29 00:00:00', ''),
(35, 'Test title23', 'Test content23', 'lofie23', '2015-10-29 00:00:00', ''),
(36, 'Test title24', 'Test content24', 'lofie24', '2015-10-29 00:00:00', ''),
(37, 'Test title25', 'Test content25', 'lofie25', '2015-10-29 00:00:00', ''),
(38, 'Test title26', 'Test content26', 'lofie26', '2015-10-29 00:00:00', ''),
(39, 'Test title27', 'Test content27', 'lofie27', '2015-10-29 00:00:00', ''),
(40, 'Test title28', 'Test content28', 'lofie28', '2015-10-29 00:00:00', ''),
(41, 'Test title29', 'Test content29', 'lofie29', '2015-10-29 00:00:00', ''),
(42, 'Test title30', 'Test content30', 'lofie30', '2015-10-29 00:00:00', ''),
(43, 'Test title31', 'Test content31', 'lofie31', '2015-10-29 00:00:00', ''),
(44, 'Test title32', 'Test content32', 'lofie32', '2015-10-29 00:00:00', ''),
(45, 'Test title33', 'Test content33', 'lofie33', '2015-10-29 00:00:00', ''),
(46, 'Test title34', 'Test content34', 'lofie34', '2015-10-29 00:00:00', ''),
(47, 'Test title35', 'Test content35', 'lofie35', '2015-10-29 00:00:00', ''),
(48, 'Test title36', 'Test content36', 'lofie36', '2015-10-29 00:00:00', ''),
(49, 'Test title37', 'Test content37', 'lofie37', '2015-10-29 00:00:00', ''),
(50, 'Test title38', 'Test content38', 'lofie38', '2015-10-29 00:00:00', ''),
(51, 'Test title39', 'Test content39', 'lofie39', '2015-10-29 00:00:00', ''),
(52, 'Test title40', 'Test content40', 'lofie40', '2015-10-29 00:00:00', ''),
(53, 'Test title41', 'Test content41', 'lofie41', '2015-10-29 00:00:00', ''),
(54, 'Test title42', 'Test content42', 'lofie42', '2015-10-29 00:00:00', ''),
(55, 'Test title43', 'Test content43', 'lofie43', '2015-10-29 00:00:00', ''),
(56, 'Test title44', 'Test content44', 'lofie44', '2015-10-29 00:00:00', ''),
(57, 'Test title45', 'Test content45', 'lofie45', '2015-10-29 00:00:00', ''),
(58, 'Test title46', 'Test content46', 'lofie46', '2015-10-29 00:00:00', ''),
(59, 'Test title47', 'Test content47', 'lofie47', '2015-10-29 00:00:00', ''),
(60, 'Test title48', 'Test content48', 'lofie48', '2015-10-29 00:00:00', ''),
(61, 'Test title49', 'Test content49', 'lofie49', '2015-10-29 00:00:00', ''),
(70, '123', '123', 'Lofie', '2015-11-23 20:24:10', ''),
(71, 'test 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae. Sed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'Lofie', '2015-11-23 20:24:29', ''),
(72, 'test 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper mi vel maximus rutrum. Proin condimentum porta pellentesque. Sed mattis massa id placerat aliquet. Etiam ultrices nulla semper ante elementum, nec congue mi venenatis. Aenean imperdiet maximus erat sed dictum. Donec mollis leo ut odio feugiat iaculis. Aliquam sed sodales purus. Nunc in maximus tellus. Donec vestibulum lorem risus, at aliquam enim rhoncus vitae. Sed gravida justo suscipit, scelerisque lacus nec, semper ante. Ut euismod congue enim eu faucibus. Duis justo nulla, aliquam a sagittis vel, tristique ac felis. In consectetur mollis sapien a convallis. Nulla a elit nulla. Praesent molestie nec neque et molestie. Donec nec accumsan enim. Nam vulputate tellus nulla, ac accumsan sem lacinia non. Proin aliquam ante ac eros venenatis sagittis. ', 'Lofie', '2015-11-23 20:24:33', ''),
(73, '1231231', '123123 asd as\r\nd a  \r\n\r\n\r\nsd\r\nas\r\nd asd', 'Nisse', '2016-09-12 21:32:38', ''),
(74, 'Nya sidan', 'VÃ¤lkomna till OL-Academys nya cyberspace!\r\nDen gamla sidan bÃ¶rjade bli vÃ¤ldigt fÃ¶rÃ¥ldrad och det var svÃ¥rt att underhÃ¥lla den. Det var dags fÃ¶r en ny hemsida med vissa nya features och nÃ¥gra borttagna. Vi har analyserat vad hemsidan anvÃ¤ndes till och funktioner som kartarkiv och rss-flÃ¶de prioriterades bort medan Ã¶kad anvÃ¤ndarvÃ¤nlighet rÃ¶rande anmÃ¤lan till trÃ¤ningar lyftes fram.\r\n\r\nFÃ¶rsta gÃ¥ngen man ska anmÃ¤la sig kommer man behÃ¶va skapa ett konto men nÃ¤r man vÃ¤l har gjort det kan man vÃ¤lja att fÃ¶rbli inloggad om man kryssar i "kom ihÃ¥g mig" under inloggning. FÃ¶rdelen med att ha ett personligt konto Ã¤r att man slipper fylla i namn och kontrollkod under anmÃ¤lningen. Det enda som krÃ¤vs nu Ã¤r att man trycker pÃ¥ anmÃ¤lningsknappen och eventuell extra kommentar.\r\n\r\nEn annan ny grej Ã¤r att alla ska kunna skapa egna trÃ¤ningar. Om du tÃ¤nker dra ut pÃ¥ ett lÃ¥ngpass kan du vÃ¤l lika gÃ¤rna lÃ¥ta andra fÃ¥ veta genom att lÃ¤gga upp en trÃ¤ning hÃ¤r sÃ¥ kanske du fÃ¥r sÃ¤llskap!\r\n\r\nOm du hittar nÃ¥gra buggar eller har synpunkter pÃ¥ den nya hemsidan fÃ¥r du gÃ¤rna hÃ¶ra av dig till info@olacademy.com eller prata med Adam. Vi som har skapat sidan heter Olof Christensson, Adam Georgsson och Fredrik Berntsson.', 'Nisse', '2016-09-24 14:54:20', '');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name`, `text`, `added`) VALUES
(76, 'dsf', 'dsf', '0000-00-00 00:00:00'),
(82, 'hej', 'dsf', '0000-00-00 00:00:00'),
(109, 'Lofie', 'asd', '2016-01-11 18:13:45'),
(110, 'Lofie', 'asd', '2016-01-11 18:13:46'),
(111, 'Lofie', 'asd', '2016-01-11 18:13:47'),
(112, 'Lofie', 'asd', '2016-01-11 18:13:48'),
(113, 'Lofie', 'dasdas', '2016-01-11 18:13:49'),
(114, 'Lofie', 'asdasdasdas', '2016-01-11 18:13:52'),
(115, 'Lofie', 'asdasd', '2016-01-14 21:04:48'),
(116, ' 123          ', '123', '2016-09-21 22:12:04'),
(117, ' 123123123 ', '123123123', '2016-09-21 22:14:11'),
(118, ' 123  ', '123', '2016-09-21 22:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `registered`
--

DROP TABLE IF EXISTS `registered`;
CREATE TABLE IF NOT EXISTS `registered` (
`id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bus` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `eventID` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `registered`
--

INSERT INTO `registered` (`id`, `userID`, `name`, `date`, `comment`, `bus`, `eventID`) VALUES
(1, 37, 'Adam Georgsson', '2016-11-18', '', 'Nej', '58'),
(68, 1, 'Test', '2016-11-18', '', '', '58'),
(69, 37, 'Adam Georgsson', '2016-11-18', '', 'Nej', '59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Privilege` int(11) NOT NULL,
  `regDate` date NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `changePassword` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `Privilege`, `regDate`, `token`, `lastname`, `changePassword`) VALUES
(28, 'admin', '$2y$12$d5IemwsuVU90qF0ja.jKUeKF1zxwi7FzBdtyGszHRmdPPPS2qEkv.', 'admin@admin.com', 0, '2015-11-19', '', '', 0),
(33, 'Test', '$2y$12$psEATvnr5L93SGhUcf0Ltum0ASySuCNeNw5O9Fq3CUQubBIfVnEuW', 'test@test.com', 1, '2016-01-16', 'ae1e6f2b33aa9c35a44ed0f1f4f62dac99d0c8f6accbe110baa1191a51090c57', 'Testsson', 0),
(34, 'Nisse', '$2y$12$7T..PkzrcCz.UNt4sGPdIeX0NWs2X4.EATdJFXR0duKvH3Fo2mYrq', 'nisse@hult.com', 2, '2016-09-10', 'fee018b3996ac1b9e9d3ec89d4a471fc363714ff45bb79bb22a6f85b70be7d75', 'Hult', 0),
(35, 'Olof', '$2y$12$FKHVC8gR2JOrc2IimZVW0eIy7ihE3x.JYKFUsMC1A25tFHO8nqvni', 'olle.ch@hotmail.com', 1, '2016-09-12', '', 'Christensson', 0),
(36, '123', '$2y$12$kNHb9LIdL73sn/Ef6TormeHLLDPVVeFcNZxBWRd..E/4RULNzb9/6', '123@123.com', 1, '2016-09-12', '', '123', 0),
(37, 'Adam', '$2y$12$nkSMzlNE.mXtAdb2HamRy.hdua/LyRg3y7mUKzMUAGHmsvkNDog5S', 'adamgeorgsson@gmail.com', 2, '2016-11-14', '087a8091148f1bc3923ae9dd70f9c437c5f5f69b177c9603cffc905cb2b824a2', 'Georgsson', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_block`
--
ALTER TABLE `ip_block`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registered`
--
ALTER TABLE `registered`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `ip_block`
--
ALTER TABLE `ip_block`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=119;
--
-- AUTO_INCREMENT for table `registered`
--
ALTER TABLE `registered`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
