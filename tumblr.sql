-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 14 Avril 2015 à 12:21
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `tumblr`
--
CREATE DATABASE IF NOT EXISTS `tumblr` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tumblr`;

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `idImage` int(11) NOT NULL AUTO_INCREMENT,
  `nomImage` varchar(255) NOT NULL,
  `captionImage` varchar(255) DEFAULT NULL,
  `real_path` varchar(255) NOT NULL,
  `createdOn` datetime NOT NULL,
  PRIMARY KEY (`idImage`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Contenu de la table `images`
--

INSERT INTO `images` (`idImage`, `nomImage`, `captionImage`, `real_path`, `createdOn`) VALUES
(76, '18.jpg', 'tata', 'df1f41c7ec3dc8037f7620b755282c97.image/jpeg', '2015-04-14 11:58:42'),
(75, '34.jpg', 'titi', 'a1dbb6cb9a53905d27e179672988a56f.image/jpeg', '2015-04-14 09:37:49'),
(74, '24.jpg', 'titi', '6bb03ed2f72579abe6381d5c32e91eb6.image/jpeg', '2015-04-14 09:37:35'),
(73, '24.jpg', 'ttt', '629ddebd9b81967a3a2f24f081abf897.image/jpeg', '2015-04-13 17:04:00'),
(72, '23.jpg', 'tata', '8ed1dcc4097b1d71a24be3f62e4b2662.image/jpeg', '2015-04-13 15:59:24'),
(70, '05.jpg', 'test', '90f16732a9baa7d00bb58450772a1013.image/jpeg', '2015-04-13 15:31:28');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `nomUser` varchar(255) NOT NULL,
  `prenomUser` varchar(255) NOT NULL,
  `passwordUser` varchar(255) NOT NULL,
  `createdOn` datetime NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`idUser`, `login`, `nomUser`, `prenomUser`, `passwordUser`, `createdOn`) VALUES
(7, 'r.root7', 'root', 'root', '$5$ro@tâ‚¬r$frObA19rlAwyuciNUPzXQGG31JGF3e1DWt6dBOj4zP1', '2015-04-14 09:37:09'),
(6, 't.tata6', 'tata', 'tata', '$5$ro@tâ‚¬r$nNBqTb/Z4TaLbzUzCcITIAkf31AQyLgLVmqh36hzHgD', '2015-03-10 16:15:38'),
(5, 't.Toto5', 'Toto', 'toto', '$5$ro@tâ‚¬r$9IFE/YnTh3k/CrZogW.JT1sAp4eYHAoBJAqw5E3yOtD', '2015-03-10 16:02:54');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
