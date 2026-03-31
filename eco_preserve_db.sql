-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 12:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eco_preserve_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_reparateur` int(11) DEFAULT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `type_action` enum('vente','reparation') NOT NULL,
  `statut` enum('disponible','en_cours','vendu','repare') DEFAULT 'disponible',
  `prix_estime` decimal(10,2) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_reconditionne` tinyint(1) DEFAULT 0,
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `id_reparateur`, `titre`, `description`, `photo`, `type_action`, `statut`, `prix_estime`, `latitude`, `longitude`, `est_reconditionne`, `contact`) VALUES
(10, 11, 12, 'Télévision plasma fissuré ', 'Ecran un peu fissuré\r\n\r\n---\r\n????️ RECONDITIONNÉ : L\'objet a été entièrement révisé et remis à neuf par un professionnel.', '1773250919_800x800-Acheter-Ds-cette-etat-e-la-garanti-ne-marche-pa-vu-que-je-l-ai.webp', 'vente', 'disponible', 25000.00, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `nom_client` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `montant` int(11) NOT NULL,
  `statut_paiement` enum('en_attente','paye','annule') DEFAULT 'en_attente',
  `date_commande` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `date_envoi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `propositions`
--

CREATE TABLE `propositions` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `reparateur_id` int(11) DEFAULT NULL,
  `offre_prix` decimal(10,2) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `statut` enum('attente','accepte','rejete') DEFAULT 'attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `propositions`
--

INSERT INTO `propositions` (`id`, `article_id`, `reparateur_id`, `offre_prix`, `message`, `statut`) VALUES
(11, 10, 12, 15000.00, 'Je prends votre article', 'accepte');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('reparateur','menage','admin') DEFAULT 'menage',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `est_bloque` tinyint(1) DEFAULT 0,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password`, `role`, `created_at`, `reset_token`, `reset_expires`, `est_bloque`, `date_inscription`) VALUES
(11, 'SIDO', 'sidokra@gmail.com', '$2y$10$mHdEgtec1x95AdG23ez1Au37II0ayqJH9tSKWjYaBxjuNfUUC0lEi', 'menage', '2026-03-11 15:01:29', NULL, NULL, 0, '2026-03-11 16:01:29'),
(12, 'SIUD', 'sidua@gmail.com', '$2y$10$V4C3UptLgEeSml4lKtUOx.YXCU7Iy4mSbPJzdSE1uN8Y3J3T66PJW', 'reparateur', '2026-03-11 15:04:14', NULL, NULL, 0, '2026-03-11 16:04:14'),
(13, 'HOGBATO Houénoukpo Ariel Felipe', 'kayodefelipe@gmail.com', '$2y$10$rRVabLUqL2B3rdoCqbYk5ed/MPAPIBvf7YZ27iltd4KUg5A9BHX3i', 'admin', '2026-03-11 17:57:13', NULL, NULL, 0, '2026-03-11 18:57:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_reparateur` (`id_reparateur`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `propositions`
--
ALTER TABLE `propositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `reparateur_id` (`reparateur_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `propositions`
--
ALTER TABLE `propositions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_reparateur` FOREIGN KEY (`id_reparateur`) REFERENCES `users` (`id`);

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Constraints for table `propositions`
--
ALTER TABLE `propositions`
  ADD CONSTRAINT `propositions_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `propositions_ibfk_2` FOREIGN KEY (`reparateur_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
