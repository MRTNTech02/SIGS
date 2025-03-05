-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2025 at 08:16 PM
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
  `a_profile` longtext NOT NULL DEFAULT '\'admindefault.png\'',
  `a_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`admin_id`, `username`, `a_fname`, `a_mname`, `a_lname`, `a_suffix`, `a_sex`, `a_birthdate`, `a_email`, `a_password`, `a_profile`, `a_status`) VALUES
(1, 'AdminUserTest', 'Jisung', '', 'Han', '', 'Male', '2000-09-14', 'hanjisung@gmail.com', 'Admin123', 'admindefault.png', 'Active');

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
(4, '2025002', 2, 'test', 'Test', '1740839559_DEVELOPMENT-COST-FOR-SIGS.docx', 'Resolved', '2025-03-01 22:32:39'),
(5, '2025002', 2, 'Test', 'test', '1740852874_DEVELOPMENT-COST-FOR-SIGS.docx', 'Open', '2025-03-02 02:14:34');

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

--
-- Dumping data for table `bug_resolution_tbl`
--

INSERT INTO `bug_resolution_tbl` (`resolution_id`, `fk_bug_id`, `fk_admin_id`, `comment`, `insrt_ts`) VALUES
(1, 4, 1, 'test comment', '2025-03-02 11:20:03');

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

--
-- Dumping data for table `e_sigs_tbl`
--

INSERT INTO `e_sigs_tbl` (`signature_id`, `owner_name`, `owner_signature`, `owner_role`, `insrt_ts`, `updt_ts`) VALUES
(5, 'Athena Erish Comillas', 'athena_e_sig.png', 'Faculty', '2025-03-02 01:56:19', NULL);

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
(1, 3, 1, 1, 1, 1, '2024-2025', '2025-02-22 19:58:44', '2025-02-22 19:58:44'),
(2, 1, 2, 3, 2, 2, '2024-2025', '2025-03-01 11:10:54', '2025-03-01 11:10:54'),
(3, 3, 2, 2, 1, 2, '2024-2025', '2025-03-04 01:17:43', NULL);

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
(1, 1, 2, 1, 1, '2024-2025', 'Active', '2025-02-22 18:25:58', '2025-02-22 18:25:58'),
(2, 2, 2, 1, 1, '2024-2025', 'Active', '2025-02-22 18:25:58', '2025-02-22 18:25:58'),
(3, 3, 2, 1, 1, '2024-2025', 'Active', '2025-02-27 18:09:36', '2025-02-27 18:09:36'),
(4, 4, 2, 1, 1, '2024-2025', 'Active', '2025-02-27 18:10:40', '2025-02-27 18:10:40'),
(5, 5, 2, 1, 2, '2024-2025', 'Active', '2025-02-27 18:13:19', '2025-02-27 18:13:19'),
(6, 6, 2, 2, 3, '2024-2025', 'Active', '2025-02-27 18:22:11', '2025-02-27 18:22:11'),
(7, 7, 2, 1, 2, '2024-2025', 'Active', '2025-02-27 18:14:37', '2025-02-27 18:14:37'),
(8, 8, 2, 1, 2, '2024-2025', 'Active', '2025-02-27 18:15:02', '2025-02-27 18:15:02'),
(9, 9, 2, 2, 3, '2024-2025', 'Active', '2025-02-27 18:23:36', '2025-02-27 18:23:36'),
(10, 10, 2, 2, 3, '2024-2025', 'Active', '2025-02-27 18:24:03', '2025-02-27 18:24:03'),
(11, 11, 2, 2, 3, '2024-2025', 'Active', '2025-02-27 18:24:37', '2025-02-27 18:24:37'),
(12, 12, 2, 2, 3, '2024-2025', 'Active', '2025-02-27 18:25:01', '2025-02-27 18:25:01');

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
(1, 1, 1, 'St. Rita', '2025-02-22 18:16:29', NULL),
(2, 2, 1, 'Zeus', '2025-02-27 18:12:16', NULL),
(3, 2, 2, 'Beril', '2025-02-27 18:16:15', NULL),
(4, 2, 1, 'Confucius', '2025-02-27 18:33:12', NULL);

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
(2, 'Accountancy, Business, and Management', 'ABM', '2025-02-22 18:12:00', NULL);

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
(0, '103072070110', 'Edxel Bricks', NULL, 'Olog', NULL, 'Male', '0000-00-00', 'Active', '2025-02-27 18:19:31', NULL),
(1, '103007060002', 'Athena Erish', NULL, 'Comillas', '', 'Female', '2002-05-26', 'Active', '2025-02-19 13:10:46', '2025-02-19 13:10:46'),
(2, '10300706002', 'Seungmin', NULL, 'Kim', NULL, 'Male', '2000-09-22', 'Active', '2025-02-22 18:24:38', NULL),
(3, '103072070110', 'Carl Justine', '', 'Butay', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(4, '103072070110', 'King Jedric', '', 'Dela Cruz', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(5, '103072070110', 'Michael Dhave', '', 'Dizon', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(6, '103072070110', 'Edxel Bricks', NULL, 'Olog', NULL, 'Male', '0000-00-00', 'Active', '2025-02-27 18:21:00', NULL),
(7, '103072070110', 'John Ronald', '', 'Duarte', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(8, '103072070110', 'Anjelo', '', 'Estanoco', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(9, '103072070110', 'France Harvy', '', 'Nuesa', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(10, '103072070110', 'Patrick Armand', '', 'Oliveros', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(11, '103072070110', 'Ashley', '', 'Alindayo', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL),
(12, '103072070110', 'Princess Aira', '', 'Natividad', '', 'Male', '2002-04-02', 'Active', '0000-00-00 00:00:00', NULL);

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
(1, 2, 3, 95, 'Pending', '2025-02-24 13:33:01', NULL);

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
(1, 1, 1, '1', '2024-2025', 'Active', '2025-02-24 13:28:06', NULL),
(2, 2, 1, '1', '2024-2025', 'Active', '2025-02-24 13:28:06', NULL),
(3, 1, 2, '1', '2024-2025', 'Active', '2025-03-02 21:27:18', NULL),
(4, 3, 1, '1', '2024-2025', 'Active', '2025-03-02 22:48:20', NULL),
(5, 2, 2, '1', '2024-2025', 'Active', '2025-03-02 23:03:49', NULL),
(6, 4, 1, '1', '2024-2025', 'Active', '2025-03-04 01:49:22', NULL);

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
(3, 'General Chemistry 2', 'Active', '2025-03-04 00:08:43', NULL);

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
(1, 'AE-204801', 'Athena Erish ', 'Han', 'Comillas', '', 'Female', '2002-05-26', 'athena.yna.05@gmail.com', 'Faculty', 'testerPassword', 'userdefaultprofile.png', 'Active'),
(2, 'BC-201026', 'Christopher', '', 'Bhang', '', 'Male', '1997-10-25', 'bangchan_97@gmail.com', 'Registrar', 'HanJisungPogi@0914', 'profile_2.jpg', 'Active'),
(3, 'EM-200710', 'Ernie Martin', 'Aningat', 'Munar', '', 'Male', '2001-02-18', 'ernie@gmail.com', 'Faculty', 'UserDefaultPassword123', 'userdefaultprofile.png', 'Active');

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
(1, 2, NULL, 'Registrar', 'Logout', '2025-03-01 22:05:38'),
(2, 2, NULL, 'Registrar', 'Login', '2025-03-01 22:06:11'),
(3, 2, NULL, 'Registrar', 'Logout', '2025-03-02 10:56:04'),
(4, NULL, 1, 'Admin', 'Login', '2025-03-02 10:56:26'),
(5, 2, NULL, 'Registrar', 'Login', '2025-03-02 19:07:17'),
(6, NULL, 1, 'Admin', 'Logout', '2025-03-02 21:40:58'),
(7, NULL, 1, 'Admin', 'Login', '2025-03-02 21:41:06'),
(8, 2, NULL, 'Registrar', 'Login', '2025-03-02 22:00:09'),
(9, NULL, 1, 'Admin', 'Logout', '2025-03-03 01:21:53'),
(10, 2, NULL, 'Registrar', 'Login', '2025-03-03 01:22:14'),
(11, 2, NULL, 'Registrar', 'Login', '2025-03-03 01:42:43'),
(12, 2, NULL, 'Registrar', 'Login', '2025-03-03 01:45:56'),
(13, 2, NULL, 'Registrar', 'Logout', '2025-03-03 01:48:37'),
(14, NULL, 1, 'Admin', 'Login', '2025-03-03 01:48:53'),
(15, NULL, 1, 'Admin', 'Logout', '2025-03-03 01:51:22'),
(16, 2, NULL, 'Registrar', 'Login', '2025-03-03 01:51:41'),
(17, 2, NULL, 'Registrar', 'Logout', '2025-03-04 01:51:20'),
(18, NULL, 1, 'Admin', 'Login', '2025-03-04 01:51:34'),
(19, 2, NULL, 'Registrar', 'Login', '2025-03-04 01:58:44'),
(20, 2, NULL, 'Registrar', 'Logout', '2025-03-04 02:30:29'),
(21, NULL, 1, 'Admin', 'Login', '2025-03-04 02:31:00'),
(22, NULL, 1, 'Admin', 'Login', '2025-03-04 02:33:38'),
(23, NULL, 1, 'Admin', 'Login', '2025-03-04 02:34:44'),
(24, NULL, 1, 'Admin', 'Logout', '2025-03-04 03:04:05'),
(25, 3, NULL, 'Faculty', 'Logout', '2025-03-04 03:11:51'),
(26, 3, NULL, 'Faculty', 'Login', '2025-03-04 03:12:04'),
(27, 3, NULL, 'Faculty', 'Login', '2025-03-04 03:12:34');

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
  MODIFY `bug_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bug_resolution_tbl`
--
ALTER TABLE `bug_resolution_tbl`
  MODIFY `resolution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `e_sigs_tbl`
--
ALTER TABLE `e_sigs_tbl`
  MODIFY `signature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faculty_assignments_tbl`
--
ALTER TABLE `faculty_assignments_tbl`
  MODIFY `f_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sc_assignments_tbl`
--
ALTER TABLE `sc_assignments_tbl`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sections_tbl`
--
ALTER TABLE `sections_tbl`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `strands_tbl`
--
ALTER TABLE `strands_tbl`
  MODIFY `strand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students_tbl`
--
ALTER TABLE `students_tbl`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_grades_tbl`
--
ALTER TABLE `student_grades_tbl`
  MODIFY `student_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects_taking_tbl`
--
ALTER TABLE `subjects_taking_tbl`
  MODIFY `s_taking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects_tbl`
--
ALTER TABLE `subjects_tbl`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_logs_tbl`
--
ALTER TABLE `user_logs_tbl`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
