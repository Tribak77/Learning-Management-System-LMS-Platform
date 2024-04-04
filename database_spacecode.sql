-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2024 at 08:05 PM
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
-- Database: `spacecode`
--

-- --------------------------------------------------------

--
-- Table structure for table `brief`
--

CREATE TABLE `brief` (
  `id_brief` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `attachment` blob DEFAULT NULL,
  `id_trainer` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brief`
--

INSERT INTO `brief` (`id_brief`, `title`, `date_start`, `date_end`, `attachment`, `id_trainer`) VALUES
(18, 'brief 1', '2024-04-04', '2024-04-26', 'empty', 41);

-- --------------------------------------------------------

--
-- Table structure for table `brief_skill`
--

CREATE TABLE `brief_skill` (
  `id_brief` int(11) NOT NULL,
  `id_skill` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brief_skill`
--

INSERT INTO `brief_skill` (`id_brief`, `id_skill`) VALUES
(18, 1),
(18, 2),
(18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `learner`
--

CREATE TABLE `learner` (
  `id_learner` int(11) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `group_` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password_ln` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learner`
--

INSERT INTO `learner` (`id_learner`, `last_name`, `first_name`, `group_`, `email`, `password_ln`) VALUES
(3, 'brihi', 'housam', 'w-1', 'brihi.learner@gmail.com', 'test111'),
(5, 'saroj', 'zaid', 'w-1', 'zaid.learner@gmail.com', 'test111'),
(6, 'bakali', 'hassan', 'w-1', 'bakali.learner@gmail.com', 'test111'),
(7, 'tallal', 'morad', 'w-2', 'tallal.learner@gmail.com', 'test111');

-- --------------------------------------------------------

--
-- Table structure for table `learner_brief`
--

CREATE TABLE `learner_brief` (
  `id_brief` int(11) NOT NULL,
  `id_learner` int(11) NOT NULL,
  `state` varchar(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `id_skill` int(11) NOT NULL,
  `titles` varchar(100) DEFAULT NULL
) ;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`id_skill`, `titles`) VALUES
(1, 'Model an application'),
(2, 'Create a static and adaptable web user interface'),
(3, 'Create a user interface with a content management or e-commerce solution'),
(4, 'Create a database'),
(5, 'Develop a dynamic web user interface'),
(6, 'Develop data access components'),
(7, 'Develop the back-end part of a web or mobile web application'),
(8, 'Develop and implement components in a content management or e-commerce application');

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `id_trainer` int(11) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password_tr` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`id_trainer`, `last_name`, `first_name`, `email`, `password_tr`) VALUES
(1, 'tribak', 'ayoub', 'admin@gmail.com', 'admin2024'),
(41, 'touil', '  reda  ', 'touil.trainer@gmail.com', 'test111'),
(42, 'suirita', ' fahd ', 'fahd.trainer@gmail.com', 'test11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brief`
--
ALTER TABLE `brief`
  ADD PRIMARY KEY (`id_brief`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `id_trainer` (`id_trainer`);

--
-- Indexes for table `brief_skill`
--
ALTER TABLE `brief_skill`
  ADD PRIMARY KEY (`id_brief`,`id_skill`),
  ADD KEY `id_skill` (`id_skill`);

--
-- Indexes for table `learner`
--
ALTER TABLE `learner`
  ADD PRIMARY KEY (`id_learner`);

--
-- Indexes for table `learner_brief`
--
ALTER TABLE `learner_brief`
  ADD PRIMARY KEY (`id_learner`,`id_brief`),
  ADD KEY `id_brief` (`id_brief`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id_skill`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
  ADD PRIMARY KEY (`id_trainer`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brief`
--
ALTER TABLE `brief`
  MODIFY `id_brief` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `learner`
--
ALTER TABLE `learner`
  MODIFY `id_learner` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `id_skill` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer`
--
ALTER TABLE `trainer`
  MODIFY `id_trainer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brief`
--
ALTER TABLE `brief`
  ADD CONSTRAINT `brief_ibfk_1` FOREIGN KEY (`id_trainer`) REFERENCES `trainer` (`id_trainer`);

--
-- Constraints for table `brief_skill`
--
ALTER TABLE `brief_skill`
  ADD CONSTRAINT `brief_skill_ibfk_1` FOREIGN KEY (`id_brief`) REFERENCES `brief` (`id_brief`),
  ADD CONSTRAINT `brief_skill_ibfk_2` FOREIGN KEY (`id_skill`) REFERENCES `skill` (`id_skill`);

--
-- Constraints for table `learner_brief`
--
ALTER TABLE `learner_brief`
  ADD CONSTRAINT `learner_brief_ibfk_1` FOREIGN KEY (`id_learner`) REFERENCES `learner` (`id_learner`),
  ADD CONSTRAINT `learner_brief_ibfk_2` FOREIGN KEY (`id_brief`) REFERENCES `brief` (`id_brief`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
