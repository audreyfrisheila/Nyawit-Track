-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 05:04 AM
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
-- Database: `nyawit_track`
--

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `budgetID` int(11) NOT NULL,
  `categoriesID` int(11) DEFAULT NULL,
  `budget_limit` int(11) DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`budgetID`, `categoriesID`, `budget_limit`, `bulan`, `userID`) VALUES
(2, 14, 8000000, '2026-06-01', NULL),
(5, 1, 50000, '2026-05-01', NULL),
(6, 1, 300000, '2026-05-01', 1),
(7, 5, 100000, '2026-05-01', 1),
(8, 8, 50000, '2026-05-01', 1),
(9, 5, 100000, '2026-05-01', 12);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoriesID` int(11) NOT NULL,
  `nama_kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoriesID`, `nama_kategori`) VALUES
(1, 'Makan & Minum'),
(2, 'Transportasi'),
(3, 'Belanja Harian'),
(4, 'Tagihan & Listrik'),
(5, 'Internet & Pulsa'),
(6, 'Kesehatan'),
(7, 'Pendidikan'),
(8, 'Hiburan'),
(9, 'Tabungan'),
(10, 'Investasi'),
(11, 'Gaji'),
(12, 'Bonus'),
(13, 'Freelance'),
(14, 'Hadiah'),
(15, 'Donasi'),
(16, 'Lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `goalsID` int(11) NOT NULL,
  `nama_goal` varchar(100) DEFAULT NULL,
  `target_nominal` int(11) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `terkumpul` int(11) NOT NULL DEFAULT 0,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactionID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `categoriesID` int(11) DEFAULT NULL,
  `goalsID` int(11) DEFAULT NULL,
  `budgetID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionID`, `userID`, `jenis`, `jumlah`, `keterangan`, `tanggal`, `created_at`, `categoriesID`, `goalsID`, `budgetID`) VALUES
(2, 15, 'pemasukan', 200000, 'invest bodonk', '2026-05-09', '2026-05-09 14:11:04', 10, NULL, NULL),
(10, 11, 'pemasukan', 500000, 'gaji 1b', '2026-05-12', '2026-05-12 06:16:02', 11, NULL, NULL),
(11, 11, 'pengeluaran', 30000, 'makan', '2026-05-12', '2026-05-12 06:16:23', 1, NULL, NULL),
(12, 1, 'pemasukan', 700000, 'kiriman mamah\r\n', '2026-05-12', '2026-05-12 15:30:12', NULL, NULL, NULL),
(13, 1, 'pengeluaran', 10000, 'makan di mbah jo\r\n', '2026-05-12', '2026-05-12 15:31:36', 1, NULL, NULL),
(14, 1, 'pengeluaran', 100000, 'main sm tmn tmn\r\n', '2026-05-12', '2026-05-12 15:32:20', 1, NULL, NULL),
(15, 1, 'pengeluaran', 190000, 'makannn', '2026-05-12', '2026-05-12 15:32:52', 1, NULL, NULL),
(16, 1, 'pengeluaran', 10000, 'beli eskrim', '2026-05-12', '2026-05-12 15:33:15', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `nama`, `email`) VALUES
(1, 'juliyprst', '123', 'JULIA PRASTIWI', 'prazztiwi@gmail.com'),
(2, 'diselaretakmu', 'lm', NULL, NULL),
(3, 'terpaktsa', 'oke', NULL, NULL),
(5, 'julie', 'ok', NULL, NULL),
(6, 'kayfeb', 'oke', NULL, NULL),
(9, 'julia', '123', NULL, NULL),
(10, 'vantelaz', '123', NULL, NULL),
(11, 'audrey', '123', 'julia', 'senjayaaudrey@gmail.com'),
(12, 'jul', '123', 'JULIA PRASTIWI', 'prazztiwi@gmail.com'),
(13, 'audreyfri', '123', NULL, NULL),
(14, 'aud', '1', NULL, NULL),
(15, 'opo', 'IYO', 'AUDREY FRI SHEILA THIFA SENJAYA', 'senjayaaudrey@gmail.com'),
(17, 'juliaw', 'juli', NULL, NULL),
(18, 'ket', '12', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`budgetID`),
  ADD KEY `fk_use_budgets` (`userID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoriesID`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goalsID`),
  ADD KEY `fk_use_goals` (`userID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `fkiduser` (`userID`),
  ADD KEY `fkidcategories` (`categoriesID`),
  ADD KEY `fkidgoal` (`goalsID`),
  ADD KEY `fkidbudget` (`budgetID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `budgetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoriesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `goalsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `fk_use_budgets` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `fk_use_goals` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fkidbudget` FOREIGN KEY (`budgetID`) REFERENCES `budgets` (`budgetID`),
  ADD CONSTRAINT `fkidcategories` FOREIGN KEY (`categoriesID`) REFERENCES `categories` (`categoriesID`),
  ADD CONSTRAINT `fkidgoal` FOREIGN KEY (`goalsID`) REFERENCES `goals` (`goalsID`),
  ADD CONSTRAINT `fkiduser` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
