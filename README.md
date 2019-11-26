

show create table pdo.users

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) DEFAULT NULL,
  `prenom` varchar(40) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=140 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `age`) VALUES
(136, 'a1', 'a2', 78),
(137, 'a3', 'a4', 77),
(138, 'aa', 'aa', 11),
(139, 'bb', 'bb', 22);
COMMIT;


Query Statement
Retrieve data based on specific criteria.

Select Expression
Columns and Functions used to build the returning data



