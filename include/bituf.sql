-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2015 at 12:38 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bituf`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
`ID` int(15) NOT NULL,
  `heading` varchar(256) NOT NULL,
  `smallHeading` varchar(100) NOT NULL,
  `date` date DEFAULT NULL,
  `content1` varchar(30000) DEFAULT NULL,
  `content2` varchar(30000) DEFAULT NULL,
  `descript` varchar(500) DEFAULT NULL,
  `kwords` varchar(50) DEFAULT NULL,
  `checkimg` tinyint(1) DEFAULT NULL,
  `commentcount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `article_to_author`
--

CREATE TABLE IF NOT EXISTS `article_to_author` (
  `article_id` int(11) NOT NULL DEFAULT '0',
  `author_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
`author_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `author_name` varchar(30) NOT NULL,
  `about_author` varchar(1000) DEFAULT NULL,
  `author_status` tinyint(1) DEFAULT '1',
  `article_count` int(11) DEFAULT '0',
  `rumour_count` int(11) DEFAULT '0',
  `profile_pic` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE IF NOT EXISTS `clubs` (
`id` int(11) NOT NULL,
  `link` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mission` varchar(200) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `club_contacts`
--

CREATE TABLE IF NOT EXISTS `club_contacts` (
  `club_id` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `number` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
`ID` int(11) NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `articleID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `upvote` int(11) NOT NULL DEFAULT '0',
  `downvote` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
`ID` int(11) NOT NULL,
  `articleID` int(11) DEFAULT NULL,
  `heading` varchar(500) NOT NULL,
  `address` varchar(10000) NOT NULL,
  `title` varchar(10000) NOT NULL,
  `alt` varchar(10000) DEFAULT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `membasic`
--

CREATE TABLE IF NOT EXISTS `membasic` (
`ID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `emailVerif` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(100) NOT NULL,
  `joinTime` datetime DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `profilePic` varchar(255) DEFAULT '0',
  `profilePicExt` varchar(32) DEFAULT NULL,
  `score` int(11) NOT NULL DEFAULT '100',
  `comments` int(11) NOT NULL DEFAULT '0',
  `upvote` int(11) NOT NULL DEFAULT '0',
  `downvote` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `newpass`
--

CREATE TABLE IF NOT EXISTS `newpass` (
`ID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `requesttime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `news_sub`
--

CREATE TABLE IF NOT EXISTS `news_sub` (
`sub_id` int(11) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_mem`
--

CREATE TABLE IF NOT EXISTS `new_mem` (
`member_id` int(11) NOT NULL,
  `rand` varchar(13) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `s_id` int(11) NOT NULL DEFAULT '0',
  `sub_id` int(11) NOT NULL DEFAULT '0',
  `internals` int(2) DEFAULT NULL,
  `externals` int(3) DEFAULT NULL,
  `tot` int(3) DEFAULT NULL,
  `pass_fail` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
`s_id` int(11) NOT NULL,
  `s_name` varchar(150) NOT NULL,
  `s_coll` varchar(3) DEFAULT NULL,
  `s_year` int(2) DEFAULT NULL,
  `s_sem` int(1) NOT NULL,
  `s_branch` varchar(2) DEFAULT NULL,
  `s_roll` int(3) DEFAULT NULL,
  `s_res` varchar(50) NOT NULL,
  `s_total` int(3) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=852 ;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
`sub_id` int(11) NOT NULL,
  `sub_year` int(2) DEFAULT NULL,
  `sub_branch` varchar(3) DEFAULT NULL,
  `sub_code` int(3) DEFAULT NULL,
  `sub_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3970 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `article_to_author`
--
ALTER TABLE `article_to_author`
 ADD PRIMARY KEY (`article_id`,`author_id`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
 ADD PRIMARY KEY (`author_id`), ADD UNIQUE KEY `user_id` (`user_id`), ADD UNIQUE KEY `author_name` (`author_name`);

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD UNIQUE KEY `link` (`link`);

--
-- Indexes for table `club_contacts`
--
ALTER TABLE `club_contacts`
 ADD PRIMARY KEY (`club_id`,`number`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `membasic`
--
ALTER TABLE `membasic`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `newpass`
--
ALTER TABLE `newpass`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `news_sub`
--
ALTER TABLE `news_sub`
 ADD PRIMARY KEY (`sub_id`), ADD UNIQUE KEY `email_id` (`email_id`);

--
-- Indexes for table `new_mem`
--
ALTER TABLE `new_mem`
 ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
 ADD PRIMARY KEY (`s_id`,`sub_id`), ADD KEY `fk_sub_res` (`sub_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
 ADD PRIMARY KEY (`s_id`), ADD UNIQUE KEY `st_unique` (`s_coll`,`s_year`,`s_branch`,`s_roll`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
 ADD PRIMARY KEY (`sub_id`), ADD UNIQUE KEY `sub_u_key` (`sub_year`,`sub_branch`,`sub_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
MODIFY `ID` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `membasic`
--
ALTER TABLE `membasic`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `newpass`
--
ALTER TABLE `newpass`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_sub`
--
ALTER TABLE `news_sub`
MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `new_mem`
--
ALTER TABLE `new_mem`
MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=852;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3970;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `result`
--
ALTER TABLE `result`
ADD CONSTRAINT `result_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`),
ADD CONSTRAINT `result_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject` (`sub_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
