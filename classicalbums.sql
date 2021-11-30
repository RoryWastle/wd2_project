-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2021 at 10:52 PM
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
(10, 9),
(14, 5),
(14, 11),
(15, 12),
(16, 6),
(16, 7),
(16, 10),
(17, 10),
(17, 24),
(18, 10),
(18, 24),
(19, 5),
(19, 11),
(20, 2),
(20, 10),
(21, 13),
(22, 5),
(22, 14),
(27, 8),
(28, 7),
(28, 8),
(28, 10),
(29, 1),
(30, 1),
(30, 14),
(31, 10),
(31, 15);

-- --------------------------------------------------------

--
-- Table structure for table `albumlikes`
--

CREATE TABLE `albumlikes` (
  `albumID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `albumlikes`
--

INSERT INTO `albumlikes` (`albumID`, `userID`) VALUES
(9, 2),
(9, 3),
(9, 6),
(10, 2),
(10, 3),
(10, 4),
(10, 6),
(16, 6),
(28, 6);

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `albumID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
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
(9, 'Abbey Road', 'The Beatles', 1969, '4-1637008262-abbey-road-5329494f8f038.jpg', '<p>The final album recorded by The Beatles. Stand out tracks<strong> </strong>include <em>Come Together</em> and <em>Here Comes the Sun</em>.</p>', 3, 4, '2021-11-15'),
(10, 'The Dark Side of the Moon', 'Pink Floyd', 1972, '4-1637008410-the-dark-side-of-the-moon-53afcfaa65a86.jpg', '<p>One of the best selling albums of all time.</p>\r\n<p style=\"padding-left: 40px;\"><strong>Tracklist:</strong></p>\r\n<p style=\"padding-left: 40px;\">Speak to Me</p>\r\n<p style=\"padding-left: 40px;\">Breathe</p>\r\n<p style=\"padding-left: 40px;\">On the Run</p>\r\n<p style=\"padding-left: 40px;\">Time</p>\r\n<p style=\"padding-left: 40px;\">The Great Gig in the Sky</p>\r\n<p style=\"padding-left: 40px;\">Money</p>\r\n<p style=\"padding-left: 40px;\">Us and Them</p>\r\n<p style=\"padding-left: 40px;\">Any Colour You Like</p', 4, 3, '2021-11-29'),
(14, 'Rumours', 'Fleetwood Mac', 1977, NULL, '<p>The eleventh studio album by British-American band Fleetwood Mac, the second of which to include the Stevie Nicks-Lindsey Buckingham lineup. Became one of the best selling albums of all time, including songwriting credits from all five band members (Mick Fleetwood, John McVie, Christine McVie, Lindsey Buckingham, Stevie Nicks).</p>', 0, 6, '2021-11-30'),
(15, 'The Rise and Fall of Ziggy Stardust and the Spiders From Mars', 'David Bowie', 1972, NULL, '<p>David Bowie\'s concept album, intruducing the character \"Ziggy Stardust\". One of the first glam rock albums to have success in America.</p>', 0, 6, '2021-11-30'),
(16, '[Led Zeppelin IV]', 'Led Zeppelin', 1971, NULL, '<p>Led Zeppelin\'s fourth (untitled) album. Also called <strong>ZOSO</strong> or <strong>Four Symbols</strong>.</p>\r\n<p>Standout tracks:</p>\r\n<p style=\"padding-left: 40px;\">Stairway to Heaven</p>\r\n<p style=\"padding-left: 40px;\">Black Dog</p>\r\n<p style=\"padding-left: 40px;\">Rock and Roll</p>\r\n<p style=\"padding-left: 40px;\">When the Levee Breaks</p>\r\n<p style=\"padding-left: 40px;\">Going to California</p>\r\n<p style=\"padding-left: 40px;\">Battle of Evermore</p>', 1, 6, '2021-11-30'),
(17, 'Machine Head', 'Deep Purple', 1972, NULL, '<p>Deep Purple\'s most successful album, bridging the gap between Hard Rock and Heavy Metal. Includes classic song \"Smoke on the Water\".</p>', 0, 6, '2021-11-30'),
(18, 'Paranoid', 'Black Sabbath', 1971, NULL, '<p>The second album by pioneering heavy metal band Black Sabbath. Includes <em>Paranoid</em> and <em>Iron Man</em>.</p>', 0, 6, '2021-11-30'),
(19, 'Goodbye Yellow Brick Road', 'Elton John', 1973, NULL, '<p>The best selling album by Sir Elton John. , with lyrics by Bernie Taupin. Includes hits <strong>Goodbye Yellow Brick Road</strong>, <strong>Candle in the Wind</strong>, <strong>Bennie and the Jets</strong>, and <strong>Saturday Night\'s Alright (For Fighting)</strong>.</p>', 0, 6, '2021-11-30'),
(20, 'Who&#039;s Next', 'The Who', 1971, NULL, '<p>The Who\'s 1971 album. Beginning life as a follow up rock opera to 1969\'s <em>Tommy</em>, guitarist/songwriter Pete Townshend and manager Kit Lambert slaved songs from the failed project to create the album we know today.</p>', 0, 6, '2021-11-30'),
(21, 'London Calling', 'The Clash', 1979, NULL, '<p>Evolving from their early punk sound, British band The Clash showcases a new mix of genres including reggae and post-punk on this 1979 album. Some tracks include \"London Calling\", \"Train in Vain (Stand by Me)\", and \"Guns of Brixton\".</p>', 0, 6, '2021-11-30'),
(22, 'The Cars', 'The Cars', 1978, NULL, '<p>Debut album by new wave band The Cars. Tracks include <em>Just What I Needed, My Best Friend\'s Girl, Let the Good Times Roll, </em>and <em>Bye Bye Love</em>.</p>', 0, 6, '2021-11-30'),
(27, 'Sgt. Pepper&#039;s Lonely Hearts Club Band', 'The Beatles', 1967, NULL, '<p>A landmark album by The Beatles. Moving away from their pop/rock &amp; roll predecessors, the band along with producer George Martin took a more studio-focused/experimental approach to recording the album. For example, it took more time to record the closing track <em>A Day in the Life</em> than recording the entirity of The Beatle\'s first album, <em>Please Please Me</em> (1963).</p>', 0, 6, '2021-11-30'),
(28, 'Are You Experienced?', 'The Jimi Hendrix Experience', 1967, NULL, '<p>The debut album by The Jimi Hendrix Experience</p>', 1, 3, '2021-11-29'),
(29, 'War', 'U2', 1983, NULL, '<p>U2\'s third studio album. Includes <em>Sunday Bloody Sunday</em>, <em>New Year\'s Day</em>, and <em>Seconds</em>.</p>', 0, 3, '2021-11-29'),
(30, 'Synchronicity', 'The Police', 1983, NULL, '<p>The final studio album by The Police.</p>\r\n<p><strong>Standout Tracks:</strong></p>\r\n<p style=\"padding-left: 40px;\">Every Breath You Take</p>\r\n<p style=\"padding-left: 40px;\">King of Pain</p>\r\n<p style=\"padding-left: 40px;\">Synchronicity II</p>\r\n<p style=\"padding-left: 40px;\">Tea in the Sahara</p>', 0, 3, '2021-11-29'),
(31, 'Pyromania', 'Def Leppard', 1983, NULL, '<p>The third studio album by british band Def Leppard. Featured a more polished hard rock sound as opposed to the band\'s heavy metal predecessors. Produced by Mutt Lange.</p>', 0, 3, '2021-11-29');

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

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentID`, `comment`, `albumID`, `userID`) VALUES
(2, 'Great songs on this album. John, Paul, George, and Ringo created a masterpiece.', 9, 4),
(5, 'Amazing album! Love The Beatles!', 9, 3),
(9, 'Amazing album', 10, 3);

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
(2, 'Rock &amp; Roll'),
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
(18, 'Miscellaneous'),
(21, 'Jazz'),
(24, 'Heavy Metal');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `password`, `admin`) VALUES
(2, 'admin1', '$2y$10$Tw1iif1g1OAezBEAzUzMI.rptKwFuOhHLkHyYwlo.l8VQig5bBQZu', 1),
(3, 'Rory', '$2y$10$OHjgocvM2HeAx0ovjAMLDO.2IhO5MqxwP.E673Pu7peW4S4/sQTam', 0),
(4, 'alan', '$2y$10$l5pdH28sjGIIFSEZyitRau0YR7kevcmI9lKqz00qPKSqDEKSitIXa', 0),
(6, 'admin2', '$2y$10$pv3Rmb1fLQq9JqeL2RvnROpMPe8s44tj601pvsAE5kx3LqzoNBFZi', 1);

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
-- Indexes for table `albumlikes`
--
ALTER TABLE `albumlikes`
  ADD PRIMARY KEY (`albumID`,`userID`) USING BTREE,
  ADD KEY `Like_User_FK` (`userID`);

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
  MODIFY `albumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `albumlikes`
--
ALTER TABLE `albumlikes`
  ADD CONSTRAINT `Like_Album_FK` FOREIGN KEY (`albumID`) REFERENCES `albums` (`albumID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Like_User_FK` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

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
