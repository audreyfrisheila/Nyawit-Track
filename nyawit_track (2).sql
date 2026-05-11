-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 03:39 PM
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
  `bulan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`budgetID`, `categoriesID`, `budget_limit`, `bulan`) VALUES
(1, 0, 5000, '2026-05-01'),
(3, 3, 50000, '0000-00-00'),
(4, 0, 1000000, '2026-05-01'),
(5, 0, 500000, '2026-05-01'),
(6, 1, 300000, '2026-05-01');

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
  `terkumpul` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`goalsID`, `nama_goal`, `target_nominal`, `deadline`, `terkumpul`) VALUES
(1, 'make up dior', 2000000, '2026-05-09', 1100000),
(4, 'mobil ', 100000, '2026-04-23', 110000),
(5, 'baju', 100000, '2026-05-06', 0),
(6, 'kuliah s2', 200000000, '2026-05-09', 30000),
(7, 'liburan ke jepang', 100000, NULL, 10000),
(8, 'netflix prem seumur hidup', 200000, NULL, 6000),
(9, 'hoi', 67890, NULL, 0);

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
  `categoriesID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionID`, `userID`, `jenis`, `jumlah`, `keterangan`, `tanggal`, `created_at`, `categoriesID`) VALUES
(1, 12, 'pengeluaran', 6000, 'makan geprek', '2026-05-04', '2026-05-04 05:42:34', 1),
(2, 12, 'pengeluaran', 5000, 'beli mirota', '2026-05-04', '2026-05-04 05:44:06', 3),
(3, 12, 'pengeluaran', 100000, 'ok', '2026-05-04', '2026-05-04 06:27:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`) VALUES
(1, 'juliyprst', '123'),
(2, 'diselaretakmu', 'lm'),
(3, 'terpaktsa', 'oke'),
(5, 'julie', 'ok'),
(6, 'kayfeb', 'oke'),
(9, 'julia', '123'),
(10, 'vantelaz', '123'),
(11, 'audrey', '123'),
(12, 'jul', '123'),
(13, 'audreyfri', '123'),
(14, 'aud', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`budgetID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoriesID`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goalsID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`);

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
  MODIFY `budgetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoriesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `goalsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
