-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 15 sep. 2025 à 12:38
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dglink_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'Diègue HOUNDOKINNOU ', 'dondiegue21@gmail.com', '$2y$10$KSccaDNwB9xIlekyQbskk.GBunuZWErEWs7KrU/QPGb4m/tDaXAc2', 'super-admin', '2025-09-07 11:26:42'),
(2, 'LOGBO Maurel', 'admin@dglink.com', '$2y$10$.8hQrcKW7m/UMI.Rn/r.Le2FHrKhb1Bk6dtnll9MdbhCzFfk/OLXa', 'utilisateur', '2025-09-07 11:27:37');

-- --------------------------------------------------------

--
-- Structure de la table `project_requests`
--

CREATE TABLE `project_requests` (
  `id` int(11) NOT NULL,
  `entreprise` varchar(255) NOT NULL,
  `type_personne` enum('entreprise','individu') NOT NULL DEFAULT 'entreprise',
  `secteur` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `type_projet` varchar(100) NOT NULL,
  `type_projet_autre` varchar(255) DEFAULT NULL,
  `objectifs` text DEFAULT NULL,
  `fonctionnalites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fonctionnalites`)),
  `budget` varchar(100) DEFAULT NULL,
  `budget_autre` varchar(255) DEFAULT NULL,
  `delai` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `consentement` tinyint(1) NOT NULL DEFAULT 0,
  `document` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','approuve','rejete') DEFAULT 'en_attente',
  `justification` text DEFAULT NULL,
  `justification_file` varchar(255) DEFAULT NULL,
  `date_soumission` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `project_requests`
--
ALTER TABLE `project_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `project_requests`
--
ALTER TABLE `project_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
