-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 02:48 PM
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
  `a_profile` longtext NOT NULL DEFAULT '\'admindefault.png\'',
  `a_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`admin_id`, `username`, `a_fname`, `a_mname`, `a_lname`, `a_suffix`, `a_sex`, `a_birthdate`, `a_email`, `a_password`, `a_profile`, `a_status`) VALUES
(1, 'AdminUserTest', 'Ernie ', '', 'Munar', '', 'Male', '2000-09-14', 'hanjisung@gmail.com', 'Admin123', 'admindefault.png', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `bugs_tbl`
--

CREATE TABLE `bugs_tbl` (
  `bug_id` int(11) NOT NULL,
  `bug_ticket` varchar(250) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `short_desc` text NOT NULL,
  `bug_desc` longtext NOT NULL,
  `bug_file` varchar(250) DEFAULT NULL,
  `bug_status` enum('Open','Resolved','In-progress') NOT NULL DEFAULT 'Open',
  `insrt_ts` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bugs_tbl`
--

INSERT INTO `bugs_tbl` (`bug_id`, `bug_ticket`, `fk_user_id`, `short_desc`, `bug_desc`, `bug_file`, `bug_status`, `insrt_ts`) VALUES
(13, '2025001', 9, 'Id No. Duplicate', 'Id No. Duplicate should not be saved.', NULL, 'Open', '2025-03-06 21:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `bug_resolution_tbl`
--

CREATE TABLE `bug_resolution_tbl` (
  `resolution_id` int(11) NOT NULL,
  `fk_bug_id` int(11) NOT NULL,
  `fk_admin_id` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `insrt_ts` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `e_sigs_tbl`
--

CREATE TABLE `e_sigs_tbl` (
  `signature_id` int(11) NOT NULL,
  `owner_name` varchar(250) NOT NULL,
  `owner_signature` varchar(250) NOT NULL,
  `owner_role` enum('Faculty','Registrar') DEFAULT NULL,
  `insrt_ts` datetime NOT NULL DEFAULT current_timestamp(),
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty_assignments_tbl`
--

CREATE TABLE `faculty_assignments_tbl` (
  `f_assignment_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_subject_id` int(11) NOT NULL,
  `fk_section_id` int(11) NOT NULL,
  `fk_year_id` int(11) NOT NULL,
  `fk_strand_id` int(11) NOT NULL,
  `f_academic_year` varchar(20) NOT NULL,
  `insrt_ts` datetime NOT NULL DEFAULT current_timestamp(),
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_assignments_tbl`
--

INSERT INTO `faculty_assignments_tbl` (`f_assignment_id`, `fk_user_id`, `fk_subject_id`, `fk_section_id`, `fk_year_id`, `fk_strand_id`, `f_academic_year`, `insrt_ts`, `updt_ts`) VALUES
(8, 7, 6, 1, 1, 1, '2024-2025', '2025-03-06 20:49:58', NULL),
(9, 9, 2, 1, 1, 1, '2024-2025', '2025-03-06 20:50:35', NULL),
(10, 10, 5, 1, 1, 1, '2024-2025', '2025-03-06 20:54:23', NULL),
(11, 11, 4, 1, 1, 1, '2024-2025', '2025-03-06 20:55:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sc_assignments_tbl`
--

CREATE TABLE `sc_assignments_tbl` (
  `assignment_id` int(11) NOT NULL,
  `fk_student_id` int(11) NOT NULL,
  `fk_year_id` int(11) NOT NULL,
  `fk_strand_id` int(11) NOT NULL,
  `fk_section_id` int(11) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `assignment_status` enum('Active','Completed','Transferred') NOT NULL DEFAULT 'Active',
  `insrt_ts` datetime NOT NULL,
  `updt_ts` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sc_assignments_tbl`
--

INSERT INTO `sc_assignments_tbl` (`assignment_id`, `fk_student_id`, `fk_year_id`, `fk_strand_id`, `fk_section_id`, `academic_year`, `assignment_status`, `insrt_ts`, `updt_ts`) VALUES
(18, 18, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 20, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 21, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 22, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 23, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 24, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 25, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 26, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 27, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 28, 1, 1, 1, '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sections_tbl`
--

CREATE TABLE `sections_tbl` (
  `section_id` int(11) NOT NULL,
  `fk_year_id` int(11) NOT NULL,
  `fk_strand_id` int(11) NOT NULL,
  `section_name` varchar(250) NOT NULL,
  `insrt_ts` datetime NOT NULL,
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections_tbl`
--

INSERT INTO `sections_tbl` (`section_id`, `fk_year_id`, `fk_strand_id`, `section_name`, `insrt_ts`, `updt_ts`) VALUES
(1, 1, 1, 'Aquamarine', '2025-02-22 18:16:29', NULL),
(2, 1, 2, 'Beryl', '2025-02-27 18:12:16', NULL),
(3, 2, 1, 'Confucius', '2025-02-27 18:33:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `strands_tbl`
--

CREATE TABLE `strands_tbl` (
  `strand_id` int(11) NOT NULL,
  `strand_name` varchar(250) NOT NULL,
  `strand_nn` varchar(20) NOT NULL,
  `insrt_ts` datetime NOT NULL,
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `strands_tbl`
--

INSERT INTO `strands_tbl` (`strand_id`, `strand_name`, `strand_nn`, `insrt_ts`, `updt_ts`) VALUES
(1, 'Science, Technology, Engineering, and Mathematics', 'STEM', '2025-02-22 18:12:00', NULL),
(2, 'Accountancy, Business, and Management', 'ABM', '2025-02-22 18:12:00', NULL),
(3, 'Humanities and Social Sciences', 'HUMSS', '0000-00-00 00:00:00', NULL),
(6, 'Technical Vocational Livelihood', 'TVL', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students_tbl`
--

CREATE TABLE `students_tbl` (
  `student_id` int(11) NOT NULL,
  `lrn_number` varchar(250) NOT NULL,
  `s_fname` varchar(50) NOT NULL,
  `s_mname` varchar(50) DEFAULT NULL,
  `s_lname` varchar(50) NOT NULL,
  `s_suffix` varchar(10) DEFAULT NULL,
  `s_sex` enum('Male','Female') NOT NULL,
  `s_birthdate` date NOT NULL,
  `s_status` enum('Active','Inactive') NOT NULL,
  `insrt_ts` datetime NOT NULL,
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_tbl`
--

INSERT INTO `students_tbl` (`student_id`, `lrn_number`, `s_fname`, `s_mname`, `s_lname`, `s_suffix`, `s_sex`, `s_birthdate`, `s_status`, `insrt_ts`, `updt_ts`) VALUES
(18, '103072070110', 'Carl Justine', '', 'Butay', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(20, '103072070111', 'King Jedric', '', 'Dela Cruz', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(21, '103072070112', 'Michael Dhave', '', 'Dizon', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(22, '103072070113', 'John Ronald', '', 'Duarte', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(23, '103072070114', 'Anjelo', '', 'Estanoco', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(24, '103072070120', 'Mary Grace', '', 'Bundoc', '', 'Female', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(25, '103072070121', 'Ashley Jean', '', 'Derrada', '', 'Female', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(26, '103072070122', 'Dorothy Marie', '', 'Diaz', '', 'Female', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(27, '103072070123', 'Angel Ann', '', 'Domingo', '', 'Female', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(28, '103072070124', 'Eliza', NULL, 'Doroni', '', 'Female', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_grades_tbl`
--

CREATE TABLE `student_grades_tbl` (
  `student_grade_id` int(11) NOT NULL,
  `fk_student_subject_id` int(11) NOT NULL,
  `fk_faculty_id` int(11) NOT NULL,
  `student_grade` decimal(10,0) NOT NULL,
  `grade_status` enum('Pending','Approved') NOT NULL DEFAULT 'Approved',
  `insrt_ts` datetime NOT NULL,
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_grades_tbl`
--

INSERT INTO `student_grades_tbl` (`student_grade_id`, `fk_student_subject_id`, `fk_faculty_id`, `student_grade`, `grade_status`, `insrt_ts`, `updt_ts`) VALUES
(25, 14, 9, 95, 'Approved', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects_taking_tbl`
--

CREATE TABLE `subjects_taking_tbl` (
  `s_taking_id` int(11) NOT NULL,
  `fk_assignment_id` int(11) NOT NULL,
  `fk_subject_id` int(11) NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `academic_year` varchar(50) NOT NULL,
  `st_status` enum('Active','Completed') NOT NULL DEFAULT 'Active',
  `insrt_ts` datetime NOT NULL DEFAULT current_timestamp(),
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects_taking_tbl`
--

INSERT INTO `subjects_taking_tbl` (`s_taking_id`, `fk_assignment_id`, `fk_subject_id`, `semester`, `academic_year`, `st_status`, `insrt_ts`, `updt_ts`) VALUES
(13, 18, 1, '1', '2024-2025', 'Active', '2025-03-06 21:06:20', NULL),
(14, 18, 2, '1', '2024-2025', 'Active', '2025-03-06 21:06:29', NULL),
(15, 18, 3, '1', '2024-2025', 'Active', '2025-03-06 21:06:38', NULL),
(16, 20, 2, '1', '2024-2025', 'Active', '2025-03-06 21:14:10', NULL),
(17, 18, 4, '1', '2024-2025', 'Active', '2025-03-06 21:14:24', NULL),
(18, 21, 2, '1', '2024-2025', 'Active', '2025-03-06 21:18:51', NULL),
(19, 22, 2, '1', '2024-2025', 'Active', '2025-03-06 21:19:07', NULL),
(20, 23, 2, '1', '2024-2025', 'Active', '2025-03-06 21:21:54', NULL),
(21, 24, 2, '1', '2024-2025', 'Active', '2025-03-06 21:26:17', NULL),
(22, 25, 2, '1', '2024-2025', 'Active', '2025-03-06 21:26:56', NULL),
(23, 26, 2, '1', '2024-2025', 'Active', '2025-03-06 21:27:16', NULL),
(24, 27, 2, '1', '2024-2025', 'Active', '2025-03-06 21:28:59', NULL),
(25, 28, 2, '1', '2024-2025', 'Active', '2025-03-06 21:29:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects_tbl`
--

CREATE TABLE `subjects_tbl` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(250) NOT NULL,
  `subject_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `insrt_ts` datetime NOT NULL DEFAULT current_timestamp(),
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects_tbl`
--

INSERT INTO `subjects_tbl` (`subject_id`, `subject_name`, `subject_status`, `insrt_ts`, `updt_ts`) VALUES
(1, 'Pre-Calculus', 'Active', '2025-02-22 19:45:14', NULL),
(2, 'Biology', 'Active', '2025-02-27 18:52:55', NULL),
(3, 'General Chemistry', 'Active', '2025-03-04 00:08:43', '2025-03-06 13:44:51'),
(4, 'General Mathematics', 'Active', '2025-03-06 20:45:03', NULL),
(5, 'Pagbasa at Pagsulat', 'Active', '2025-03-06 20:45:44', NULL),
(6, 'Physics 1', 'Active', '2025-03-06 20:46:03', '2025-03-06 13:49:31');

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
(6, '20-1101', 'Dante', '', 'Guerrero', '', 'Male', '2002-04-02', 'danteguerrero@gmail.com', 'Registrar', 'UserDefaultPassword123', 'userdefaultprofile.png', 'Active'),
(7, '20-1102', 'Cristy', '', 'Mercado', '', 'Female', '2002-04-02', 'cmercado@gmail.com', 'Faculty', 'UserDefaultPassword123', 'userdefaultprofile.png', 'Active'),
(9, '20-1103', 'Bryan', '', 'Concepcion', '', 'Male', '2002-04-02', 'bryan@gmail.com', 'Faculty', 'UserDefaultPassword123', 'userdefaultprofile.png', 'Active'),
(10, '20-1104', 'Jumat', '', 'Andres', '', 'Male', '2002-04-02', 'jumat@gmail.com', 'Faculty', 'UserDefaultPassword123', 'userdefaultprofile.png', 'Active'),
(11, '20-1105', 'Ludylyn', '', 'Corpuz', '', 'Female', '2002-04-02', 'ludy@gmail.com', 'Faculty', 'UserDefaultPassword123', 'userdefaultprofile.png', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs_tbl`
--

CREATE TABLE `user_logs_tbl` (
  `log_id` int(11) NOT NULL,
  `fk_user_id` int(11) DEFAULT NULL,
  `fk_admin_id` int(11) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `action` enum('Login','Logout') NOT NULL,
  `log_ts` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs_tbl`
--

INSERT INTO `user_logs_tbl` (`log_id`, `fk_user_id`, `fk_admin_id`, `role`, `action`, `log_ts`) VALUES
(53, NULL, 1, 'Admin', 'Login', '2025-03-06 20:17:20'),
(54, 6, NULL, 'Registrar', 'Login', '2025-03-06 21:21:16'),
(55, 9, NULL, 'Faculty', 'Login', '2025-03-06 21:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `year_levels_tbl`
--

CREATE TABLE `year_levels_tbl` (
  `year_level_id` int(11) NOT NULL,
  `yl_name` varchar(250) NOT NULL,
  `insrt_ts` datetime NOT NULL,
  `updt_ts` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_levels_tbl`
--

INSERT INTO `year_levels_tbl` (`year_level_id`, `yl_name`, `insrt_ts`, `updt_ts`) VALUES
(1, 'Grade 11', '2025-02-22 16:07:14', NULL),
(2, 'Grade 12', '2025-02-22 18:11:37', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bugs_tbl`
--
ALTER TABLE `bugs_tbl`
  ADD PRIMARY KEY (`bug_id`);

--
-- Indexes for table `bug_resolution_tbl`
--
ALTER TABLE `bug_resolution_tbl`
  ADD PRIMARY KEY (`resolution_id`);

--
-- Indexes for table `e_sigs_tbl`
--
ALTER TABLE `e_sigs_tbl`
  ADD PRIMARY KEY (`signature_id`);

--
-- Indexes for table `faculty_assignments_tbl`
--
ALTER TABLE `faculty_assignments_tbl`
  ADD PRIMARY KEY (`f_assignment_id`),
  ADD KEY `fk_section_id` (`fk_section_id`),
  ADD KEY `faculty_assignments_tbl_ibfk_2` (`fk_strand_id`),
  ADD KEY `faculty_assignments_tbl_ibfk_3` (`fk_subject_id`),
  ADD KEY `faculty_assignments_tbl_ibfk_4` (`fk_user_id`),
  ADD KEY `faculty_assignments_tbl_ibfk_5` (`fk_year_id`);

--
-- Indexes for table `sc_assignments_tbl`
--
ALTER TABLE `sc_assignments_tbl`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `fk_section_id` (`fk_section_id`),
  ADD KEY `sc_assignments_tbl_ibfk_2` (`fk_strand_id`),
  ADD KEY `sc_assignments_tbl_ibfk_3` (`fk_student_id`),
  ADD KEY `sc_assignments_tbl_ibfk_4` (`fk_year_id`);

--
-- Indexes for table `sections_tbl`
--
ALTER TABLE `sections_tbl`
  ADD PRIMARY KEY (`section_id`),
  ADD KEY `fk_strand_id` (`fk_strand_id`),
  ADD KEY `fk_year_id` (`fk_year_id`);

--
-- Indexes for table `strands_tbl`
--
ALTER TABLE `strands_tbl`
  ADD PRIMARY KEY (`strand_id`);

--
-- Indexes for table `students_tbl`
--
ALTER TABLE `students_tbl`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `student_grades_tbl`
--
ALTER TABLE `student_grades_tbl`
  ADD PRIMARY KEY (`student_grade_id`),
  ADD KEY `fk_faculty_id` (`fk_faculty_id`),
  ADD KEY `fk_student_subject_id` (`fk_student_subject_id`);

--
-- Indexes for table `subjects_taking_tbl`
--
ALTER TABLE `subjects_taking_tbl`
  ADD PRIMARY KEY (`s_taking_id`),
  ADD KEY `fk_assignment_id` (`fk_assignment_id`),
  ADD KEY `fk_subject_id` (`fk_subject_id`);

--
-- Indexes for table `subjects_tbl`
--
ALTER TABLE `subjects_tbl`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_logs_tbl`
--
ALTER TABLE `user_logs_tbl`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_admin_id` (`fk_admin_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `year_levels_tbl`
--
ALTER TABLE `year_levels_tbl`
  ADD PRIMARY KEY (`year_level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bugs_tbl`
--
ALTER TABLE `bugs_tbl`
  MODIFY `bug_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bug_resolution_tbl`
--
ALTER TABLE `bug_resolution_tbl`
  MODIFY `resolution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `e_sigs_tbl`
--
ALTER TABLE `e_sigs_tbl`
  MODIFY `signature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faculty_assignments_tbl`
--
ALTER TABLE `faculty_assignments_tbl`
  MODIFY `f_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sc_assignments_tbl`
--
ALTER TABLE `sc_assignments_tbl`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `sections_tbl`
--
ALTER TABLE `sections_tbl`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `strands_tbl`
--
ALTER TABLE `strands_tbl`
  MODIFY `strand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students_tbl`
--
ALTER TABLE `students_tbl`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `student_grades_tbl`
--
ALTER TABLE `student_grades_tbl`
  MODIFY `student_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `subjects_taking_tbl`
--
ALTER TABLE `subjects_taking_tbl`
  MODIFY `s_taking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `subjects_tbl`
--
ALTER TABLE `subjects_tbl`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_logs_tbl`
--
ALTER TABLE `user_logs_tbl`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `year_levels_tbl`
--
ALTER TABLE `year_levels_tbl`
  MODIFY `year_level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_assignments_tbl`
--
ALTER TABLE `faculty_assignments_tbl`
  ADD CONSTRAINT `faculty_assignments_tbl_ibfk_1` FOREIGN KEY (`fk_section_id`) REFERENCES `sections_tbl` (`section_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `faculty_assignments_tbl_ibfk_2` FOREIGN KEY (`fk_strand_id`) REFERENCES `strands_tbl` (`strand_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `faculty_assignments_tbl_ibfk_3` FOREIGN KEY (`fk_subject_id`) REFERENCES `subjects_tbl` (`subject_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `faculty_assignments_tbl_ibfk_4` FOREIGN KEY (`fk_user_id`) REFERENCES `users_tbl` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `faculty_assignments_tbl_ibfk_5` FOREIGN KEY (`fk_year_id`) REFERENCES `year_levels_tbl` (`year_level_id`) ON UPDATE CASCADE;

--
-- Constraints for table `sc_assignments_tbl`
--
ALTER TABLE `sc_assignments_tbl`
  ADD CONSTRAINT `sc_assignments_tbl_ibfk_1` FOREIGN KEY (`fk_section_id`) REFERENCES `sections_tbl` (`section_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sc_assignments_tbl_ibfk_2` FOREIGN KEY (`fk_strand_id`) REFERENCES `strands_tbl` (`strand_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sc_assignments_tbl_ibfk_3` FOREIGN KEY (`fk_student_id`) REFERENCES `students_tbl` (`student_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sc_assignments_tbl_ibfk_4` FOREIGN KEY (`fk_year_id`) REFERENCES `year_levels_tbl` (`year_level_id`) ON UPDATE CASCADE;

--
-- Constraints for table `sections_tbl`
--
ALTER TABLE `sections_tbl`
  ADD CONSTRAINT `sections_tbl_ibfk_1` FOREIGN KEY (`fk_strand_id`) REFERENCES `strands_tbl` (`strand_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sections_tbl_ibfk_2` FOREIGN KEY (`fk_year_id`) REFERENCES `year_levels_tbl` (`year_level_id`) ON UPDATE CASCADE;

--
-- Constraints for table `student_grades_tbl`
--
ALTER TABLE `student_grades_tbl`
  ADD CONSTRAINT `student_grades_tbl_ibfk_1` FOREIGN KEY (`fk_faculty_id`) REFERENCES `users_tbl` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `student_grades_tbl_ibfk_2` FOREIGN KEY (`fk_student_subject_id`) REFERENCES `subjects_taking_tbl` (`s_taking_id`) ON UPDATE CASCADE;

--
-- Constraints for table `subjects_taking_tbl`
--
ALTER TABLE `subjects_taking_tbl`
  ADD CONSTRAINT `subjects_taking_tbl_ibfk_1` FOREIGN KEY (`fk_assignment_id`) REFERENCES `sc_assignments_tbl` (`assignment_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `subjects_taking_tbl_ibfk_2` FOREIGN KEY (`fk_subject_id`) REFERENCES `subjects_tbl` (`subject_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
