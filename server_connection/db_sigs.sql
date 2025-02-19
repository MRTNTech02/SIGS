-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 19, 2025 at 08:00 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sigs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `a_fname` varchar(50) NOT NULL,
  `a_mname` varchar(50) DEFAULT NULL,
  `a_lname` varchar(50) NOT NULL,
  `a_suffix` varchar(10) DEFAULT NULL,
  `a_sex` enum('Male','Female') NOT NULL,
  `a_birthdate` date NOT NULL,
  `a_email` varchar(50) NOT NULL,
  `a_password` longtext NOT NULL,
  `a_profile` longtext NOT NULL,
  `a_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`admin_id`, `username`, `a_fname`, `a_mname`, `a_lname`, `a_suffix`, `a_sex`, `a_birthdate`, `a_email`, `a_password`, `a_profile`, `a_status`) VALUES
(1, 'AdminUserTest', 'Jisung', NULL, 'Han', NULL, 'Male', '2000-09-14', 'hanjisung@gmail.com', 'Admin123', 'admindefault.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `user_id` int(11) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `u_fname` varchar(50) NOT NULL,
  `u_mname` varchar(50) DEFAULT NULL,
  `u_lname` varchar(50) NOT NULL,
  `u_suffix` varchar(10) DEFAULT NULL,
  `u_sex` enum('Male','Female') NOT NULL,
  `u_birthdate` date NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `role` enum('Faculty','Registrar') NOT NULL,
  `user_password` longtext NOT NULL,
  `user_profile` mediumtext NOT NULL,
  `user_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`user_id`, `id_number`, `u_fname`, `u_mname`, `u_lname`, `u_suffix`, `u_sex`, `u_birthdate`, `user_email`, `role`, `user_password`, `user_profile`, `user_status`) VALUES
(1, 'AE-204801', 'Athena Erish ', 'Han', 'Comillas', '', 'Female', '2002-05-26', 'athena.yna.05@gmail.com', 'Faculty', 'testerPassword', 'userdefaultprofile.jpg', 'Active'),
(2, 'BC-201026', 'Christopher', '', 'Bhang', '', 'Male', '1997-10-25', 'bangchan_97@gmail.com', 'Registrar', 'UserDefaultPassword123', 'userdefaultprofile.jpg', 'Active'),
(3, 'EM-200710', 'Ernie Martin', 'Aningat', 'Munar', '', 'Male', '2001-02-18', 'ernie@gmail.com', 'Faculty', 'UserDefaultPassword123', 'userdefaultprofile.jpg', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
