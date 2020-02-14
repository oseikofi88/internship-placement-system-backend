-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2018 at 03:09 PM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `IPS_Phinx`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `time_of_comment` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `representative_name` varchar(255) DEFAULT NULL,
  `representative_phone` varchar(255) DEFAULT NULL,
  `representative_email` varchar(255) DEFAULT NULL,
  `order_made` tinyint(1) NOT NULL DEFAULT '0',
  `time_of_registration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `company_sub_department`
--

CREATE TABLE `company_sub_department` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `number_needed` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `concern`
--

CREATE TABLE `concern` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coordinator`
--

CREATE TABLE `coordinator` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coordinator`
--

INSERT INTO `coordinator` (`user_id`, `email`) VALUES
(2, 'akowuahjoe@yahoo.co.uk'),
(3, 'afotey_benjamin@hotmail.com'),
(4, 'dayackom.coe@knust.edu.gh'),
(5, 'obengatuah@yahoo.co.uk'),
(6, 'kwame.agyekum@ymail.com'),
(7, 'adamsglobal@gmail.com'),
(8, 'ekarthur.coe@knust.edu.gh'),
(9, 'aaacheampong.coe@knust.edu.gh'),
(10, 'ssrgidigasu@yahoo.com'),
(11, 'eaowusu328@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `time_of_creation` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` decimal(30,28) NOT NULL,
  `longitude` decimal(30,27) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `main_department`
--

CREATE TABLE `main_department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_department`
--

INSERT INTO `main_department` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Agricultural and Biosystems', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(2, 'Chemical', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(3, 'Computer', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(4, 'Civil', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(5, 'Electrical/Electronics', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(6, 'Geological', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(7, 'Geomatic', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(8, 'Materials', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(9, 'Mechanical', '2018-02-15 15:08:00', '0000-00-00 00:00:00'),
(10, 'Petroleum', '2018-02-15 15:08:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `time_of_send` datetime NOT NULL,
  `is_a_notification` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`) VALUES
(20170712132546, 'CreateUserTypeTable', '2018-02-15 15:06:57', '2018-02-15 15:06:57'),
(20170712141646, 'CreateUserTable', '2018-02-15 15:06:57', '2018-02-15 15:06:58'),
(20170712141647, 'CreateLocationTable', '2018-02-15 15:06:58', '2018-02-15 15:06:58'),
(20170712143939, 'CreateAdminTable', '2018-02-15 15:06:58', '2018-02-15 15:06:59'),
(20170712154701, 'CreateCoordinatorTable', '2018-02-15 15:06:59', '2018-02-15 15:06:59'),
(20170712154840, 'CreateCompanyTable', '2018-02-15 15:06:59', '2018-02-15 15:06:59'),
(20170712155050, 'CreateMainDepartmentTable', '2018-02-15 15:06:59', '2018-02-15 15:07:00'),
(20170712155051, 'CreateSubDepartmentTable', '2018-02-15 15:07:00', '2018-02-15 15:07:00'),
(20170712163505, 'CreateStudentTable', '2018-02-15 15:07:01', '2018-02-15 15:07:01'),
(20170712172326, 'CreatePostTypeTable', '2018-02-15 15:07:01', '2018-02-15 15:07:02'),
(20170712172508, 'CreatePostTable', '2018-02-15 15:07:02', '2018-02-15 15:07:02'),
(20170712184457, 'CreateTextPostTable', '2018-02-15 15:07:02', '2018-02-15 15:07:03'),
(20170712184540, 'CreatePhotoPostTable', '2018-02-15 15:07:03', '2018-02-15 15:07:03'),
(20170712184547, 'CreateVideoPostTable', '2018-02-15 15:07:03', '2018-02-15 15:07:04'),
(20170712185201, 'CreateCommentTable', '2018-02-15 15:07:04', '2018-02-15 15:07:04'),
(20170712185210, 'CreateVoteTable', '2018-02-15 15:07:04', '2018-02-15 15:07:05'),
(20170712185219, 'CreateMessageTable', '2018-02-15 15:07:05', '2018-02-15 15:07:05'),
(20170712193828, 'CreateGroupTable', '2018-02-15 15:07:05', '2018-02-15 15:07:06'),
(20170712194116, 'CreateRecipientTable', '2018-02-15 15:07:06', '2018-02-15 15:07:06'),
(20170712200350, 'CreateUserHasGroupTable', '2018-02-15 15:07:06', '2018-02-15 15:07:07'),
(20170712212804, 'CreateCompanySubDepartmentTable', '2018-02-15 15:07:07', '2018-02-15 15:07:07'),
(20170712212815, 'CreateUserHasUpdatedLocationTable', '2018-02-15 15:07:07', '2018-02-15 15:07:08'),
(20170908154819, 'CreatePlacementStatus', '2018-02-15 15:07:08', '2018-02-15 15:07:08'),
(20180118110633, 'CreateConcernTable', '2018-02-15 15:07:08', '2018-02-15 15:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `photo_post`
--

CREATE TABLE `photo_post` (
  `photo_url` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL,
  `post_type_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `placement_status`
--

CREATE TABLE `placement_status` (
  `id` int(11) NOT NULL,
  `placement_done` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `placement_status`
--

INSERT INTO `placement_status` (`id`, `placement_done`, `created_at`, `updated_at`) VALUES
(1, 0, '2018-02-15 15:08:22', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `post_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_of_post` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_type`
--

CREATE TABLE `post_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recipient`
--

CREATE TABLE `recipient` (
  `id` int(11) NOT NULL,
  `time_of_recieve` datetime NOT NULL,
  `message_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `read` tinyint(1) NOT NULL,
  `time_read` datetime DEFAULT NULL,
  `delivered` tinyint(1) NOT NULL,
  `time_delivered` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `user_id` int(11) NOT NULL,
  `index_number` int(11) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `other_names` varchar(255) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `foreign_student` tinyint(1) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `want_placement` tinyint(1) DEFAULT NULL,
  `time_of_registration` datetime NOT NULL,
  `acceptance_letter_url` varchar(255) DEFAULT NULL,
  `picture_url` varchar(255) DEFAULT NULL,
  `time_of_starting_internship` datetime DEFAULT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `supervisor_contact` varchar(255) DEFAULT NULL,
  `supervisor_email` varchar(255) DEFAULT NULL,
  `registered_company` tinyint(1) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `rejected_placement` tinyint(1) DEFAULT NULL,
  `reason_for_rejection` varchar(2550) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_department`
--

CREATE TABLE `sub_department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `coordinator_id` int(11) DEFAULT NULL,
  `main_department_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_department`
--

INSERT INTO `sub_department` (`id`, `name`, `coordinator_id`, `main_department_id`, `created_at`, `updated_at`) VALUES
(1, 'Agricultural', 2, 1, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(2, 'Chemical', 3, 2, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(3, 'Petrochemical', 3, 2, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(4, 'Biomedical', 4, 3, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(5, 'Computer', 4, 3, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(6, 'Geotech', 5, 4, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(7, 'Highways & Transport', 5, 4, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(8, 'Structures', 5, 4, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(9, 'Water Supply & Drainage', 5, 4, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(10, 'Waste Management', 5, 4, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(11, 'Electrical', 6, 5, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(12, 'Telecom', 6, 5, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(13, 'Geological', 10, 6, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(14, 'Geomatic', 9, 7, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(15, 'Materials', 8, 8, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(16, 'Metallurgical', 8, 8, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(17, 'Aerospace', 7, 9, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(18, 'Mechanical', 7, 9, '2018-02-15 15:08:05', '0000-00-00 00:00:00'),
(19, 'Petroleum', NULL, 10, '2018-02-15 15:08:05', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `text_post`
--

CREATE TABLE `text_post` (
  `post_id` int(11) NOT NULL,
  `post_type_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type_id`, `password`, `created_at`, `updated_at`) VALUES
(1, 4, '$2y$10$R/7zEhXE0YS8ZODZP/MOXeH5wLZ2rmIToOR2dh2h5GDWU9mDTbe3S', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(2, 3, '$2y$10$EIMqk7VEWzrsrQX7PMa/XuSpdVBTYiCOIGCrJllUDj1oekJpNEgv2', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(3, 3, '$2y$10$MwhIrBZsOPZRhaGzQVs9KuM7qKU92ZgFbrUOm9IIlbx7hvs/5rzkW', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(4, 3, '$2y$10$4kKgk6jF.zUNjO.6.S8acexfHcFPW2v9MtwZdC8eaXMTcEOrg48l6', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(5, 3, '$2y$10$YFp5CvG4bsEmV0BvBbEFhOt3L019sL3WjIcGAueUCzks0gf1hZQ52', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(6, 3, '$2y$10$vBRYXDTDT4WNbtF7653ICu4MSv77bRorLm7tNdsuy06Ic6IHLe4Ye', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(7, 3, '$2y$10$E82H359P2kz/BLsrh39zJuUKM0g3oA4O7BxfsW/ZCQkXoemJGSURy', '2018-02-15 15:07:22', '0000-00-00 00:00:00'),
(8, 3, '$2y$10$p3unEmO3HpNjEAknZyNZFuTWaZ1X.dWYeZ0GQFpWrqoiav8h4dtIe', '2018-02-15 15:07:23', '0000-00-00 00:00:00'),
(9, 3, '$2y$10$/uKmi4xOrVrHDw8SN5Yt4Oj.KFFgiRwNaq2NxQezQQRVySYwsU9ee', '2018-02-15 15:07:23', '0000-00-00 00:00:00'),
(10, 3, '$2y$10$Z75XYPFtM6.jpXp3uKm.Cejmc5wRCR/dFoJ.qQByfIe5XnSJXy1fu', '2018-02-15 15:07:23', '0000-00-00 00:00:00'),
(11, 3, '$2y$10$4PIBBr5YiIEeck//loGhce5TmByu7vOc6lPKt8douE3xHxymmlPFe', '2018-02-15 15:07:23', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `userhasgroup`
--

CREATE TABLE `userhasgroup` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `time_of_creation` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_updated_location`
--

CREATE TABLE `user_has_updated_location` (
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'student', '2018-02-15 15:07:18', '0000-00-00 00:00:00'),
(2, 'company', '2018-02-15 15:07:18', '0000-00-00 00:00:00'),
(3, 'coordinator', '2018-02-15 15:07:18', '0000-00-00 00:00:00'),
(4, 'admin', '2018-02-15 15:07:18', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `video_post`
--

CREATE TABLE `video_post` (
  `post_id` int(11) NOT NULL,
  `post_type_id` int(11) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE `vote` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `time_of_vote` datetime NOT NULL,
  `upvote` tinyint(1) DEFAULT NULL,
  `downvote` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `company_sub_department`
--
ALTER TABLE `company_sub_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_department_id` (`sub_department_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `concern`
--
ALTER TABLE `concern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `coordinator`
--
ALTER TABLE `coordinator`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `main_department`
--
ALTER TABLE `main_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `photo_post`
--
ALTER TABLE `photo_post`
  ADD PRIMARY KEY (`post_id`,`post_type_id`),
  ADD KEY `post_type_id` (`post_type_id`);

--
-- Indexes for table `placement_status`
--
ALTER TABLE `placement_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_type_id` (`post_type_id`);

--
-- Indexes for table `post_type`
--
ALTER TABLE `post_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipient`
--
ALTER TABLE `recipient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `index_number` (`index_number`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `sub_department_id` (`sub_department_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `sub_department`
--
ALTER TABLE `sub_department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `coordinator_id` (`coordinator_id`),
  ADD KEY `main_department_id` (`main_department_id`);

--
-- Indexes for table `text_post`
--
ALTER TABLE `text_post`
  ADD PRIMARY KEY (`post_id`,`post_type_id`),
  ADD KEY `post_type_id` (`post_type_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indexes for table `userhasgroup`
--
ALTER TABLE `userhasgroup`
  ADD PRIMARY KEY (`user_id`,`group_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `user_has_updated_location`
--
ALTER TABLE `user_has_updated_location`
  ADD PRIMARY KEY (`user_id`,`location_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `video_post`
--
ALTER TABLE `video_post`
  ADD PRIMARY KEY (`post_id`,`post_type_id`),
  ADD KEY `post_type_id` (`post_type_id`);

--
-- Indexes for table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company_sub_department`
--
ALTER TABLE `company_sub_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `concern`
--
ALTER TABLE `concern`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `main_department`
--
ALTER TABLE `main_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `placement_status`
--
ALTER TABLE `placement_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_type`
--
ALTER TABLE `post_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipient`
--
ALTER TABLE `recipient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sub_department`
--
ALTER TABLE `sub_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `vote`
--
ALTER TABLE `vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `company_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_sub_department`
--
ALTER TABLE `company_sub_department`
  ADD CONSTRAINT `company_sub_department_ibfk_1` FOREIGN KEY (`sub_department_id`) REFERENCES `sub_department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `company_sub_department_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `concern`
--
ALTER TABLE `concern`
  ADD CONSTRAINT `concern_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `coordinator`
--
ALTER TABLE `coordinator`
  ADD CONSTRAINT `coordinator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `photo_post`
--
ALTER TABLE `photo_post`
  ADD CONSTRAINT `photo_post_ibfk_1` FOREIGN KEY (`post_type_id`) REFERENCES `post_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `photo_post_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`post_type_id`) REFERENCES `post_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `recipient`
--
ALTER TABLE `recipient`
  ADD CONSTRAINT `recipient_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `recipient_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `recipient_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`sub_department_id`) REFERENCES `sub_department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `student_ibfk_4` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sub_department`
--
ALTER TABLE `sub_department`
  ADD CONSTRAINT `sub_department_ibfk_1` FOREIGN KEY (`coordinator_id`) REFERENCES `coordinator` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sub_department_ibfk_2` FOREIGN KEY (`main_department_id`) REFERENCES `main_department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `text_post`
--
ALTER TABLE `text_post`
  ADD CONSTRAINT `text_post_ibfk_1` FOREIGN KEY (`post_type_id`) REFERENCES `post_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `text_post_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `userhasgroup`
--
ALTER TABLE `userhasgroup`
  ADD CONSTRAINT `userhasgroup_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `userhasgroup_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_has_updated_location`
--
ALTER TABLE `user_has_updated_location`
  ADD CONSTRAINT `user_has_updated_location_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_has_updated_location_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `video_post`
--
ALTER TABLE `video_post`
  ADD CONSTRAINT `video_post_ibfk_1` FOREIGN KEY (`post_type_id`) REFERENCES `post_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `video_post_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
