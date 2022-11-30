-- Tabellenstruktur für Tabelle `entry`
CREATE TABLE `entry` (
  `id` int(11) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  `createdUser` int(11) NOT NULL,
  `editedUser` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `highlight` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabellenstruktur für Tabelle `user`
CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  `name` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;