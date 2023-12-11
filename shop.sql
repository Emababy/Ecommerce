-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2023 at 03:29 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `AllowComment` tinyint(4) NOT NULL DEFAULT 0,
  `AllowAds` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `AllowComment`, `AllowAds`) VALUES
(17, 'Computers', 'High-Performance Desktop PC& Slim and Powerful Laptop ', 1, 0, 0, 0),
(18, 'Men Fashion', 'Men\'s fashion encompasses a wide range of styles, trends, and clothing options. ', 2, 0, 0, 0),
(19, 'Woman Fashion', 'Women\'s fashion is diverse and ever-evolving, encompassing a wide range of styles, trends, and clothing options.', 3, 0, 0, 0),
(20, 'Electronics ', 'Electronics encompass a broad category of devices and systems ', 4, 0, 1, 0),
(21, 'Video Games', ' encompassing a wide variety of genres, platforms, and experiences. ', 5, 0, 0, 0),
(23, 'Stay Tuned', 'Private', 6, 1, 1, 0),
(25, 'Furniture', '', 7, 0, 0, 0),
(26, 'Mobiles ', 'You\'ll Find Here The Most Powerful Mobiles ', 8, 0, 0, 0),
(27, 'Games Consoles ', 'Ps5 Console - Ps4 console - Xbox 360 - Xbox Series X', 9, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `C_ID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Statues` tinyint(4) NOT NULL DEFAULT 0,
  `C_Date` date NOT NULL,
  `Items_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`C_ID`, `Comment`, `Statues`, `C_Date`, `Items_ID`, `User_ID`) VALUES
(2, 'This Very Good Item', 1, '2023-11-24', 4, 32),
(3, 'Very Good Product', 1, '2023-11-24', 8, 29),
(4, 'Very nice product', 1, '2023-11-16', 10, 22);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Statues` varchar(255) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country`, `Image`, `Statues`, `Approve`, `Cat_ID`, `Member_ID`) VALUES
(4, 'PS4', 'PlayStation 4 256GB  ', '150$', '2023-11-23', 'USA', '', '1', 1, 27, 22),
(5, 'Iphone 13 ', '6.1 inches 256GB ', '1000$', '2023-11-23', 'USA', '', '1', 1, 26, 29),
(7, 'PS5', 'PlayStation 5 512GB', '250$', '2023-11-23', 'USA', '', '3', 1, 27, 32),
(8, 'Iphone 13 pro', '6.3 inches 256GB ', '1200$', '2023-11-24', 'Egypt', '', '1', 1, 26, 29),
(9, 'Mac M2', '', '2500$', '2023-11-24', 'USA', '', '1', 1, 17, 22),
(10, 'MacBook Air M1', 'A2337 A2179 A1932 2020 2019 2018 Release, Slim Plastic Matte Hard Cover Compatible Mac Air 13.3 inch with Retina Display Touch ID Transpirant', '3500$', '2023-11-25', 'USA', '', '1', 1, 17, 29);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To identify User',
  `Username` varchar(255) NOT NULL COMMENT 'username to login',
  `Password` varchar(255) NOT NULL COMMENT 'Password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'identify user group',
  `TrustStatues` int(11) NOT NULL DEFAULT 0 COMMENT 'Seller Rank',
  `RegStatues` int(11) NOT NULL DEFAULT 0 COMMENT 'User Approval',
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatues`, `RegStatues`, `Date`) VALUES
(1, 'Ziad', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'ziadembaby10@gmail.com', 'Ziad Embaby', 1, 0, 1, '2023-11-08'),
(22, 'Joumana', 'a66a0b6daf65ed9bda8a26b44e54f6a3ce99c16c', 'JoumanaMohamed20@gmail.com', 'Joumana Mohamed', 0, 0, 1, '2023-11-01'),
(29, 'Ahmed', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'AhmedEzzat10@gmail.com', 'Ahmed Ezzat', 0, 0, 1, '2023-11-20'),
(31, 'Abdallah', '8cb2237d0679ca88db6464eac60da96345513964', 'Abdallah1@gmail.com', 'Abdallah Mohamed', 0, 0, 1, '2023-11-20'),
(32, 'Mohamed', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'MohamedAhmed20@gmail.com', 'Mohamed Ahmed', 0, 0, 1, '2023-11-20'),
(33, 'Hassan', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'hassanmhassan@gmail.com', 'Hassan Mohamed', 0, 0, 1, '2023-11-20'),
(35, 'Hager', '$2y$10$B7e2PDH6Bkqk/T/YCU1QOOyDauy28Ak5yZUpLhhHtt.2.JPJoa8YG', 'HagerMohamed@gmail.com', 'Hager Mohamed', 0, 0, 1, '2023-11-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`C_ID`),
  ADD KEY `items_comment` (`Items_ID`),
  ADD KEY `comment_user` (`User_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `Cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To identify User', AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`Items_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `Cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
