-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Jun 2022 um 16:33
-- Server-Version: 10.4.22-MariaDB
-- PHP-Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `swp4_ue05_osterberger`
--

-- --------------------------------------------------------

-- Tabellenstruktur für Tabelle `entry`
CREATE TABLE `entry` (
  `id` int(11) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  `createdUser` int(11) DEFAULT '0' NOT NULL,
  `editedUser` int(11) DEFAULT '0' NOT NULL,
  `title` varchar(255) DEFAULT '' NOT NULL,
  `text` varchar(255) DEFAULT '' NULL,
  `highlight` tinyint(1)  DEFAULT '0' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabellenstruktur für Tabelle `user`
CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  `name` varchar(255) DEFAULT '' NOT NULL,
  `pw` varchar(255) DEFAULT '' NOT NULL,
  `email` varchar(255) DEFAULT '' NOT NULL,
  `role` int(11) DEFAULT '' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `entry`
--

INSERT INTO `entry` (`id`, `created`, `edited`, `createdUser`, `editedUser`, `title`, `text`, `highlight`) VALUES
(6, '2022-06-01 17:14:57', '2022-06-17 12:31:57', 1, 1, 'Ich bin ein anderer Titel', 'Extra Notice here yes I have been changed', 0),
(7, '2022-06-01 18:26:23', '2022-06-08 17:35:40', 1, 6, 'This is a better title', 'i am a text', 0),
(8, '2022-06-01 18:42:52', '2022-06-08 17:12:04', 5, 6, 'Some better title here', '', 0),
(9, '2022-06-01 18:44:19', '2022-06-08 17:13:52', 5, 6, 'Ein bearbeiteter Titel', 'kein Text mehr :((', 1),
(10, '2022-06-01 18:44:42', '2022-06-08 17:15:08', 5, 6, 'I am a really weird Title', 'adawdawdaw', 1),
(13, '2022-06-08 16:47:22', '2022-06-08 17:14:46', 6, 6, 'Lol this is a new Test entry', 'From the tutor', 0),
(15, '2022-06-08 16:48:31', '2022-06-08 16:48:31', 6, 6, 'Lol this is a new Test entry', 'From the tutor', 0),
(17, '2022-06-08 16:50:24', '2022-06-08 17:10:55', 6, 6, 'This is a new Post', 'lol', 0),
(18, '2022-06-08 16:57:46', '2022-06-08 16:57:46', 6, 6, 'Ein neuer eintrag', 'wuhuuu', 0),
(34, '2022-06-10 01:24:19', '2022-06-13 16:45:53', 1, 1, 'okay lets see, ok', 'I need some text', 0),
(36, '2022-06-13 11:57:49', '2022-06-13 12:51:58', 7, 7, 'Hallo ich bin Rudi', '', 0),
(38, '2022-06-17 16:29:59', '2022-06-17 16:29:59', 1, 1, 'API inserted Title', 'Whatever', 0),
(39, '2022-06-17 16:28:29', '2022-06-17 16:28:29', 1, 1, 'API inserted Title', 'Whatever', 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `name`, `pw`, `email`, `created`, `role`) VALUES
(1, 'test', '$2y$10$zXboihJH0AEqsGGgpKE8/OdgYIgt4szW2kZPcfv4kCIwujznQLrUO', 'test@test.at', '2022-06-01 13:02:32', 1),
(5, 'admin', '$2y$10$x/2rbS1L/zivDd.v9336Julf0mIglQ.ZwyftLOYm5tRtVvZVxRJQq', 'admin@admin.at', '2022-06-01 18:41:48', 0),
(6, 'tutor', '$2y$10$S2ZAjCKg7uD413dcFeyKWeAoc3PsbitXhQEfd0nEXU1mf8xxt/5ym', 'tutor@tutor.at', '2022-06-01 18:47:34', 0),
(7, 'rudi', '$2y$10$yj.dHzfcArXpxnQCIfi.seL666g0Veg6NIVG8tIgd2j5ISnavDDa.', 'rudi@rudi.at', '2022-06-08 13:48:15', 1),
(8, 'heinz', '$2y$10$iU2nA4CleFPcQ8.B/qctYOLHeihZHT3AZaGjDIp5lp/fBMnB4B6gi', 'heinz@heinz.at', '2022-06-09 14:07:25', 1),
(9, 'user', '$2y$10$ExTk9CszlY7k9Jygk8msHehaxsODb6wzKDPOqoqU4tRRY2BtQfM7u', 'user@user.at', '2022-06-09 14:08:11', 1),
(10, 'gexi', '$2y$10$46qVKBlKhrE5Qtz4WWxt4.kWZmYsCL2tM0OCZC4MT5YsryYlIsy8C', 'gex@gex.at', '2022-06-10 01:22:22', 1),
(11, 'uwei', '$2y$10$yEaVwwBWWX.YxA73BapIv.BKtcDJawj0QfQVkNrPDJmbKBXNlsVT6', 'uwei@adawd.at', '2022-06-10 01:24:46', 1),
(12, 'lolo', '$2y$10$BA.lm4wOLimEQj7ipNFG2eycZGmHT8f3OCnnri8d3iem/TElH1BMK', 'lolo@adawd.at', '2022-06-10 01:25:40', 1),
(13, 'alex', '$2y$10$pUL6t0WrAeZ/qcIHH8uaguTDR4dzbT7VOiTZsA9DAFWUg1XfUkonu', 'alex@alex.at', '2022-06-13 16:51:34', 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `entry`
--
ALTER TABLE `entry`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `entry`
--
ALTER TABLE `entry`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
