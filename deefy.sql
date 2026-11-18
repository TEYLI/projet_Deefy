SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `playlist2track`;
DROP TABLE IF EXISTS `user2playlist`;
DROP TABLE IF EXISTS `track`;
DROP TABLE IF EXISTS `playlist`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `genre` varchar(30) DEFAULT NULL,
  `duree` int(5) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `artiste_album` varchar(100) DEFAULT NULL,
  `titre_album` varchar(100) DEFAULT NULL,
  `annee_album` int(4) DEFAULT NULL,
  `numero_album` int(11) DEFAULT NULL,
  `auteur_podcast` varchar(100) DEFAULT NULL,
  `date_podcast` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `playlist2track` (
  `id_pl` int(11) NOT NULL,
  `id_track` int(11) NOT NULL,
  `no_piste_dans_liste` int(3) NOT NULL,
  PRIMARY KEY (`id_pl`, `id_track`),
  CONSTRAINT `fk_p2t_playlist` FOREIGN KEY (`id_pl`) REFERENCES `playlist` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_p2t_track` FOREIGN KEY (`id_track`) REFERENCES `track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `passwd` varchar(256) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user2playlist` (
  `id_user` int(11) NOT NULL,
  `id_pl` int(11) NOT NULL,
  PRIMARY KEY (`id_user`, `id_pl`),
  CONSTRAINT `fk_u2p_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_u2p_playlist` FOREIGN KEY (`id_pl`) REFERENCES `playlist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `playlist` (`id`, `nom`) VALUES
(1, 'Best of rock'),
(2, 'Musique classique'),
(3, 'Podcasts Tech');

INSERT INTO `track` (`id`, `titre`, `genre`, `duree`, `filename`, `type`, `artiste_album`, `titre_album`, `annee_album`, `numero_album`, `auteur_podcast`, `date_podcast`) VALUES
(1, 'Wish You Were Here', 'rock', 334, 'audio/pink_floyd_wish.mp3', 'A', 'Pink Floyd', 'Wish You Were Here', 1975, 1, NULL, NULL),
(2, 'Sultans of Swing', 'rock', 348, 'audio/dire_straits_sultans.mp3', 'A', 'Dire Straits', 'Dire Straits', 1978, 6, NULL, NULL),
(3, 'Les enjeux de l''IA en 2026', 'Technologie', 1420, 'audio/podcast_ia_2026.mp3', 'P', NULL, NULL, NULL, NULL, 'France Culture', '2026-10-15');

INSERT INTO `playlist2track` (`id_pl`, `id_track`, `no_piste_dans_liste`) VALUES
(1, 1, 1),
(1, 2, 2),
(3, 3, 1);

SET FOREIGN_KEY_CHECKS = 1;