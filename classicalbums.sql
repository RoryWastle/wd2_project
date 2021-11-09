-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2021 at 01:06 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classicalbums`
--

-- --------------------------------------------------------

--
-- Table structure for table `albumgenre`
--

CREATE TABLE `albumgenre` (
  `albumID` int(11) NOT NULL,
  `genreID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `albumgenre`
--

INSERT INTO `albumgenre` (`albumID`, `genreID`) VALUES
(9, 2),
(10, 8),
(10, 9);

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `albumID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `artist` varchar(30) NOT NULL,
  `year` int(4) DEFAULT NULL,
  `coverURL` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `likes` int(7) NOT NULL DEFAULT 0,
  `postedBy` int(11) DEFAULT NULL,
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`albumID`, `title`, `artist`, `year`, `coverURL`, `description`, `likes`, `postedBy`, `updated`) VALUES
(9, 'Abbey Road', 'The Beatles', 1969, 'abbey-road-5329494f8f038.jpg', 'The final album recorded by The Beatles. Standout tracks include Come Together and Here Comes the Sun.', 0, 1, '2021-11-08'),
(10, 'The Dark Side of the Moon', 'Pink Floyd', 1972, 'the-dark-side-of-the-moon-53afcfaa65a86.jpg', 'Will add later', 0, 1, '2021-11-08');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentID` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `albumID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genreID` int(11) NOT NULL,
  `genre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genreID`, `genre`) VALUES
(1, 'Rock'),
(2, 'Rock & Roll'),
(3, 'British Invasion'),
(4, 'Surf Rock'),
(5, 'Pop Rock'),
(6, 'Folk Rock'),
(7, 'Blues'),
(8, 'Psychedelic'),
(9, 'Progressive'),
(10, 'Hard Rock'),
(11, 'Soft Rock'),
(12, 'Glam/Glitter'),
(13, 'Punk'),
(14, 'New Wave'),
(15, 'Hair Metal'),
(16, 'Alternative'),
(17, 'Grunge'),
(18, 'Miscellaneous');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `password`, `admin`) VALUES
(1, 'admin1', 'AdminPass01', 1),
(2, 'admin1', 'AdminPass01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albumgenre`
--
ALTER TABLE `albumgenre`
  ADD PRIMARY KEY (`albumID`,`genreID`),
  ADD KEY `GENRE_FK` (`genreID`);

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`albumID`),
  ADD KEY `POSTEDBY_FK` (`postedBy`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `ALBUM_COMMENT_FK` (`albumID`),
  ADD KEY `USER_COMMENT_FK` (`userID`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `albumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albumgenre`
--
ALTER TABLE `albumgenre`
  ADD CONSTRAINT `ALBUM_FK` FOREIGN KEY (`albumID`) REFERENCES `albums` (`albumID`) ON DELETE CASCADE,
  ADD CONSTRAINT `GENRE_FK` FOREIGN KEY (`genreID`) REFERENCES `genres` (`genreID`) ON DELETE CASCADE;

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `POSTEDBY_FK` FOREIGN KEY (`postedBy`) REFERENCES `users` (`userID`) ON DELETE SET NULL;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `ALBUM_COMMENT_FK` FOREIGN KEY (`albumID`) REFERENCES `albums` (`albumID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_COMMENT_FK` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
