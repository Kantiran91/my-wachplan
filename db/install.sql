-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 19. Mrz 2016 um 16:20
-- Server Version: 5.5.33a-MariaDB
-- PHP-Version: 5.5.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Logins`
--
-- in Benutzung(#1356 - View 'seepelikan_wp.Logins' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)
-- Fehler beim Lesen der Daten: (#1356 - View 'seepelikan_wp.Logins' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Logins2`
--
-- in Benutzung(#1356 - View 'seepelikan_wp.Logins2' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)
-- Fehler beim Lesen der Daten: (#1356 - View 'seepelikan_wp.Logins2' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wp_access_user_days`
--

CREATE TABLE IF NOT EXISTS `wp_access_user_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `position` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `day_id` (`day_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;


--
-- Tabellenstruktur für Tabelle `wp_days`
--

CREATE TABLE IF NOT EXISTS `wp_days` (
  `id_day` int(2) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id_day`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wp_feedback`
--

CREATE TABLE IF NOT EXISTS `wp_feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `day_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `weather` smallint(20) NOT NULL,
  `happened` smallint(6) NOT NULL,
  `lifeguards` smallint(6) NOT NULL,
  `first_aid(small)` varchar(20) NOT NULL,
  `first_aid(big)` smallint(6) NOT NULL,
  `food` smallint(6) NOT NULL,
  `process` smallint(6) NOT NULL,
  `material` varchar(180) NOT NULL,
  `notice` varchar(180) NOT NULL,
  PRIMARY KEY (`feedback_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


--
-- Tabellenstruktur für Tabelle `wp_log_login`
--

CREATE TABLE IF NOT EXISTS `wp_log_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `datum` datetime NOT NULL,
  `ip` varchar(30) NOT NULL,
  `pw_korrekt` smallint(6) NOT NULL COMMENT '1 wenn richtig',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=294 ;

--
-- Daten für Tabelle `wp_log_login`
--

--
-- Tabellenstruktur für Tabelle `wp_poss_acc`
--

CREATE TABLE IF NOT EXISTS `wp_poss_acc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Mögliche Eintragungen im Wachplan' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wp_user`
--

CREATE TABLE IF NOT EXISTS `wp_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `user_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `hash` varchar(128) NOT NULL,
  `rights` smallint(6) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `geburtsdatum` varchar(10) NOT NULL,
  `abzeichen` varchar(12) NOT NULL,
  `med` varchar(3) NOT NULL,
  `friend` int(11) DEFAULT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Daten für Tabelle `wp_user`
--

INSERT INTO `wp_user` (`id_user`, `email`, `user_name`, `hash`, `rights`, `telephone`, `geburtsdatum`, `abzeichen`, `med`, `friend`, `first_name`, `last_name`) VALUES
(1, 'test@localhost', 'admin', '0b52a1c6f9c755bb0ff0084d58c7ad5f52782cf866a356fa0ff435b6d634841baed50796f03e4755310aacb68cf06b12b2bde5c0d868e47933ed334ad00a109a', 2, '1234', '1901-06-25', 'DRSA Gold', 'san', NULL, 'Max', 'Mustermann');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
