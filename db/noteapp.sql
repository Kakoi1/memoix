-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2024 at 12:34 PM
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
-- Database: `noteapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `reset_token` mediumint(50) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `email`, `password`, `reset_token`, `status`) VALUES
(73, 'roland', 'rshan0418@gmail.com', 'e466e3494f1d4ce6fb8bd678d45cd801ac10b9ba', 0, 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `n_id` int(10) NOT NULL,
  `n_title` varchar(20) NOT NULL,
  `n_description` longtext NOT NULL,
  `n_date` date NOT NULL,
  `star` int(10) NOT NULL,
  `archive` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`n_id`, `n_title`, `n_description`, `n_date`, `star`, `archive`) VALUES
(21, 'sad11', 'dsada', '2024-04-07', 1, 0),
(22, ' sadsadasd11', 'dsada', '2024-04-07', 0, 0),
(23, 'dasdasd', 'adssadasd', '2024-04-07', 0, 0),
(24, 'gwapo ka?', 'ooooooooooooooooooooooooooooooooooooooooooooooooooooooooo', '2024-04-07', 0, 0),
(25, 'uig8yrtf7i6er76rt9gi', 'k;ljhuiohuioghui9gguy', '2024-04-07', 0, 0),
(28, 'roland', 'roland', '2024-04-07', 0, 0),
(30, '3123', '3121312', '2024-04-07', 0, 0),
(33, 'dasdas', 'dasdasdsad', '2024-04-07', 0, 0),
(34, 'dasd12', 'dada12', '2024-04-07', 0, 0),
(37, 'bayot ', 'bayot daw si kean', '2024-04-07', 0, 0),
(38, 'dsa', 'dsad', '2024-04-07', 0, 0),
(39, 'das', 'das', '2024-04-07', 0, 0),
(40, 'rorororo', 'rorororororororor', '2024-04-07', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`n_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `n_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
