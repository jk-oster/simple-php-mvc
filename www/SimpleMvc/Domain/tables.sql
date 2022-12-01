-- Tabellenstruktur für Tabelle `entry`
CREATE TABLE `entry` (
  -- default columns
  `id` int(11) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  -- model columns
  `createdUser` int(11) DEFAULT '0' NOT NULL,
  `editedUser` int(11) DEFAULT '0' NOT NULL,
  `title` varchar(255) DEFAULT '' NOT NULL,
  `text` varchar(255) DEFAULT '' NULL,
  `highlight` tinyint(1)  DEFAULT '0' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabellenstruktur für Tabelle `user`
CREATE TABLE `user` (
  -- default columns
  `id` int(11) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  -- model columns
  `name` varchar(255) DEFAULT '' NOT NULL,
  `pw` varchar(255) DEFAULT '' NOT NULL,
  `email` varchar(255) DEFAULT '' NOT NULL,
  `role` int(11) DEFAULT '' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;