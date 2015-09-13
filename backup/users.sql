-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 13 sep 2015 kl 22:21
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `Privilege`, `regDate`, `token`) VALUES
(10, '111', 'e6e010710c72a7e3dfd02ab27b451f56', '123@123.com', 1, '2015-05-09', '3d97aef83ce05ef37e16ded33b9b24520f39294eb14085f3094e8aa95657d463'),
(11, 'nisse', 'cd523f1e4ca6409ea8f028dc04172f2f', '123@123.com', 2, '2015-05-21', '3561fe9bcb5e1d48f3899589765b9586909687f57438f956be428b1fc6ca542b'),
(13, '123', 'c05a25cb543f8425ce9f74bb650618b5', '', 0, '2015-05-21', 'cbd7bfb2120ea5953f0ecd0bf7b022551e8886b5f9cfd3c037497d63ea6978f3'),
(14, 'Adam', 'c05a25cb543f8425ce9f74bb650618b5', 'adamgeorgsson@gmail.com', 2, '2015-09-13', ''),
(15, 'admin', '13591c006fe70ac237bbcc8e6fb467d7', 'admin@admin', 2, '2015-09-13', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
