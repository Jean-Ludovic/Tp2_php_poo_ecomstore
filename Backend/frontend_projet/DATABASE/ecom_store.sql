-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 08 août 2024 à 16:39
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecom_store`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$K9ShQj6l6wD0yKQFhBfY3urGumkN3oJjD8eXnhnAeGz9T1nF5GzO6', '2024-07-14 15:31:58'),
(2, 'admin1', 'admin@test.com', 'admin', '2024-07-15 18:29:38'),
(3, 'administrateur', 'administrateur@gmail.com', 'administrateur', '2024-07-22 19:51:28');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` datetime NOT NULL DEFAULT current_timestamp(),
  `statut` enum('fait','pas fait') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `note` int(11) NOT NULL CHECK (`note` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `image`, `prix`, `note`) VALUES
(2, 'Gilet', '../../assets/images/gilet.webp', 10.00, 4),
(3, 'Ak-47', '../../assets/images/ak-47.webp', 200.00, 5),
(6, 'cagoule', 'cagoule.jpeg', 5.00, 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'JeanLudo', 'jeanludo@example.com', '$2y$10$Q9E2S6tOgY3IVvQ1Sc1ZTuxtjmrPLwAdy6Jbc7gE7uF0nIbW9NTYm', '2024-07-14 15:31:58'),
(2, 'test', 'tes@test.com', '$2y$10$n2dc2JkgVlIl8uCb1zp0u.VpOXxWEtmYdIniNiJoYNW4sL8Vd0OYG', '2024-07-15 17:45:54'),
(3, 'maintenant', 'maintenant@gmail.com', '$2y$10$fuNPkQGLxct2qhD3E6JR6eeIGUtF3.Gd50dR7kKHHg8E1bPfASrMe', '2024-07-15 17:47:32'),
(4, 'verification', 'verification@gmail.com', '$2y$10$SaAGELj9uWs7CwHZdgzlM.CJLPRcC91HretaaZYHubrP6eHIii.jW', '2024-07-20 13:37:56'),
(5, 'verification', 'verification@gmail.com', '$2y$10$hNAkxGJ9YZeqjRxREZpgpOAIl10qhx.iOs.T4xNSaAIC7PgpmMOz.', '2024-07-20 13:38:22'),
(6, 'le_user', 'user@user.com', '$2y$10$RK.wpEsIRZHifmvTe8Oj8.7ds5rD0vsxiIAkY1r1ZB/BuZk9FDbGi', '2024-07-20 14:02:35'),
(9, 'Monsieur', 'Monsieur@pfoapf', '$2y$10$R/5jvbQeOKLqZxaffvQj1uRlNbHWvbKqkUKXgOK7OHxrNvb9BsJ5W', '2024-08-02 17:17:40'),
(10, 'PAPA', 'Monsieur@pfoapf', '$2y$10$XAUmfcE5MqtaZaDyGos2BOSAIfwMhJHOBoonbXbAVaJdx/28MWK/2', '2024-08-02 17:26:28'),
(11, 'Aly', 'Aly@gmail.com', '$2y$10$Tjo6m8YiOdBVF7wO2W8iHe4XUx776rScK9npJUQMU68gOAB95/EXi', '2024-08-02 17:33:41'),
(12, 'Mohamed', 'Mohamed@gmail.com', '$2y$10$D/656M1MPcMo5nRuczjNgO4D01GVrxjojPqafIHz0IvwJuCr4s8n6', '2024-08-02 17:36:49'),
(14, 'Juilien_Dussollier', 'goalen.corine@gmail.com', '$2y$10$b/CGlSkilfocsWlSN0sV7uC6XVhmQDWkjmQvcdQZpLxc5ZYOqjzIa', '2024-08-03 15:47:25'),
(16, 'ce_qui_est_sur', 'ce_qui_est_sur@outlook.com', '$2y$10$36Qn0RT.st.4pSqDoSgHsORdLdcb/yFqbl8wknLWP5xpw53rHEahO', '2024-08-07 16:41:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
