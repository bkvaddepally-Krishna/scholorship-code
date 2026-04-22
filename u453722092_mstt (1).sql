-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 22, 2026 at 02:41 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u453722092_mstt`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500'),
(2, 'harish', '32b9e74c8f60958158eba8d1fa372971'),
(3, 'sagar', '81dc9bdb52d04dc20036dbd8313ed055');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `status`, `date`, `created_at`) VALUES
(1, 2, 'Absent', '2026-04-20', '2026-04-20 19:04:45');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`) VALUES
(1, '1st GRADE'),
(2, '2nd GRADE'),
(3, '3rd GRADE'),
(4, '4th GRADE'),
(5, '5th GRADE'),
(6, '6th GRADE'),
(7, '7th GRADE'),
(8, '8th GRADE'),
(9, '9th GRADE');

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_subjects`
--

INSERT INTO `class_subjects` (`id`, `class_id`, `subject_id`) VALUES
(20, 3, 1),
(21, 3, 4),
(22, 3, 6),
(23, 3, 7),
(24, 3, 9),
(25, 4, 1),
(26, 4, 3),
(27, 4, 5),
(28, 4, 6),
(29, 4, 9),
(30, 5, 1),
(31, 5, 3),
(32, 5, 5),
(33, 5, 6),
(34, 5, 9),
(35, 6, 1),
(36, 6, 3),
(37, 6, 5),
(38, 6, 6),
(39, 6, 9),
(40, 7, 1),
(41, 7, 3),
(42, 7, 5),
(43, 7, 6),
(44, 7, 9),
(45, 8, 1),
(46, 8, 3),
(47, 8, 5),
(48, 8, 6),
(49, 8, 9),
(50, 9, 1),
(51, 9, 3),
(52, 9, 5),
(53, 9, 6),
(54, 9, 9),
(60, 1, 1),
(61, 1, 4),
(62, 1, 8),
(63, 1, 10),
(64, 2, 1),
(65, 2, 4),
(66, 2, 8),
(67, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `button_text` varchar(50) DEFAULT NULL,
  `target_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_name`, `subject`, `body`, `button_text`, `target_url`) VALUES
(1, 'Hall Ticket', 'MST 2026: Hall Ticket for {NAME}', 'Dear {NAME}, your Hall Ticket for MS No: {MS_NO} is ready. Child of {FATHER}.', 'DOWNLOAD NOW', 'https://dpss.edu/hall-ticket'),
(2, 'Results', 'MST 2026: Results for {NAME}', 'Dear {NAME}, your results are out. Your percentage: {PERCENT}.', 'VIEW RESULTS', 'https://dpss.edu/results');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `name`, `exam_date`, `status`) VALUES
(1, 'MERIT SCHOLARSHIP TEST', '2026-04-26', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `action`, `created_at`) VALUES
(180, 'SYSTEM_LOGIN: Admin [sagar] accessed the core.', '2026-04-21 11:43:03'),
(181, 'SYSTEM_LOGIN: Admin [harish] accessed the core.', '2026-04-21 11:43:20'),
(182, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:07:36'),
(183, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:07:36'),
(184, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:07:36'),
(185, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:07:36'),
(186, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:07:36'),
(187, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:07:36'),
(188, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:07:36'),
(189, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:07:36'),
(190, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:07:36'),
(191, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:07:36'),
(192, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:07:46'),
(193, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:07:46'),
(194, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:07:46'),
(195, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:07:46'),
(196, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:07:46'),
(197, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:07:46'),
(198, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:07:46'),
(199, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:07:46'),
(200, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:07:46'),
(201, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:07:46'),
(202, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:08:03'),
(203, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:08:03'),
(204, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:08:03'),
(205, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:08:03'),
(206, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:08:03'),
(207, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:08:03'),
(208, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:08:03'),
(209, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:08:03'),
(210, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:08:03'),
(211, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:08:03'),
(212, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:09:11'),
(213, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:09:11'),
(214, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:09:11'),
(215, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:09:11'),
(216, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:09:11'),
(217, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:09:11'),
(218, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:09:11'),
(219, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:09:11'),
(220, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:09:11'),
(221, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:09:11'),
(222, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:09:35'),
(223, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:09:35'),
(224, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:09:35'),
(225, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:09:35'),
(226, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:09:35'),
(227, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:09:35'),
(228, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:09:35'),
(229, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:09:35'),
(230, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:09:35'),
(231, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:09:35'),
(232, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:09:49'),
(233, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:09:49'),
(234, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:09:49'),
(235, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:09:49'),
(236, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:09:49'),
(237, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:09:49'),
(238, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:09:49'),
(239, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:09:49'),
(240, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:09:49'),
(241, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:09:49'),
(242, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:10:02'),
(243, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:10:02'),
(244, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:10:02'),
(245, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:10:02'),
(246, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:10:02'),
(247, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:10:02'),
(248, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:10:02'),
(249, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:10:02'),
(250, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:10:02'),
(251, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:10:02'),
(252, 'CRITICAL: STUDENT_DELETED: ID #57 (Demo) removed from system.', '2026-04-21 12:12:59'),
(253, 'STUDENT_MODIFIED: ID #8 (A Reyansh patel) fields updated.', '2026-04-21 12:17:17'),
(254, 'STUDENT_MODIFIED: ID #8 (A Reyansh patel) fields updated.', '2026-04-21 12:18:42'),
(255, 'STUDENT_MODIFIED: ID #8 (A Reyansh patel) fields updated.', '2026-04-21 12:21:54'),
(256, 'STUDENT_MODIFIED: ID #8 (A Reyansh patel) fields updated.', '2026-04-21 12:26:50'),
(257, 'STUDENT_MODIFIED: ID #71 (Arraboina Hrithvi Sri yadav) fields updated.', '2026-04-21 12:27:56'),
(258, 'STUDENT_MODIFIED: ID #95 (BURRAADHWIKA GOUD) fields updated.', '2026-04-21 12:27:56'),
(259, 'STUDENT_MODIFIED: ID #85 (Busa Kushal) fields updated.', '2026-04-21 12:27:56'),
(260, 'STUDENT_MODIFIED: ID #105 (Garnapally Gahan) fields updated.', '2026-04-21 12:27:56'),
(261, 'STUDENT_MODIFIED: ID #118 (Gudipally hadya reddy) fields updated.', '2026-04-21 12:27:56'),
(262, 'STUDENT_MODIFIED: ID #70 (Ireni Ronith Simha Goud) fields updated.', '2026-04-21 12:27:56'),
(263, 'STUDENT_MODIFIED: ID #123 (LIMMA AISHWARYA) fields updated.', '2026-04-21 12:27:56'),
(264, 'STUDENT_MODIFIED: ID #122 (M.Aadvik Reddy) fields updated.', '2026-04-21 12:27:56'),
(265, 'STUDENT_MODIFIED: ID #72 (SABBENA YASHVARDHAAN) fields updated.', '2026-04-21 12:27:56'),
(266, 'STUDENT_MODIFIED: ID #73 (Thouti Hanvitha) fields updated.', '2026-04-21 12:27:56'),
(267, 'STUDENT_MODIFIED: ID #8 (A Reyansh patel) fields updated.', '2026-04-21 12:28:11'),
(268, 'STUDENT_MODIFIED: ID #87 (Aitha Thanvika) fields updated.', '2026-04-21 12:28:11'),
(269, 'STUDENT_MODIFIED: ID #88 (Aitha Yashvika) fields updated.', '2026-04-21 12:28:11'),
(270, 'STUDENT_MODIFIED: ID #101 (Bandi Jai Goud) fields updated.', '2026-04-21 12:28:11'),
(271, 'STUDENT_MODIFIED: ID #80 (Battu Srimanvi) fields updated.', '2026-04-21 12:28:11'),
(272, 'STUDENT_MODIFIED: ID #81 (Battu Srimanvi) fields updated.', '2026-04-21 12:28:11'),
(273, 'STUDENT_MODIFIED: ID #78 (Gampa lithisha) fields updated.', '2026-04-21 12:28:11'),
(274, 'STUDENT_MODIFIED: ID #117 (juttu manivarshith) fields updated.', '2026-04-21 12:28:11'),
(275, 'STUDENT_MODIFIED: ID #6 (juttu manivarshith) fields updated.', '2026-04-21 12:28:11'),
(276, 'STUDENT_MODIFIED: ID #9 (K Himansh) fields updated.', '2026-04-21 12:28:11'),
(277, 'STUDENT_MODIFIED: ID #111 (K.Aayansh Reddy) fields updated.', '2026-04-21 12:28:11'),
(278, 'STUDENT_MODIFIED: ID #89 (M Manvitha) fields updated.', '2026-04-21 12:28:11'),
(279, 'STUDENT_MODIFIED: ID #125 (M. Ayaan) fields updated.', '2026-04-21 12:28:11'),
(280, 'STUDENT_MODIFIED: ID #114 (Mamindla Aayan) fields updated.', '2026-04-21 12:28:11'),
(281, 'STUDENT_MODIFIED: ID #116 (N.vashistayan reddy) fields updated.', '2026-04-21 12:28:11'),
(282, 'STUDENT_MODIFIED: ID #7 (Paleu Devansh) fields updated.', '2026-04-21 12:28:11'),
(283, 'STUDENT_MODIFIED: ID #5 (Rajuri Riya) fields updated.', '2026-04-21 12:28:11'),
(284, 'STUDENT_MODIFIED: ID #82 (Ridhi sri) fields updated.', '2026-04-21 12:28:11'),
(285, 'STUDENT_MODIFIED: ID #103 (S Shreeyansh) fields updated.', '2026-04-21 12:28:11'),
(286, 'STUDENT_MODIFIED: ID #106 (S Shreeyansh) fields updated.', '2026-04-21 12:28:11'),
(287, 'STUDENT_MODIFIED: ID #79 (Vidhu Nandhan. P) fields updated.', '2026-04-21 12:28:11'),
(288, 'SYSTEM_LOGIN: Admin [admin] accessed the core.', '2026-04-21 12:41:15'),
(289, 'SYSTEM_LOGIN: Admin [harish] accessed the core.', '2026-04-21 13:40:30'),
(290, 'SYSTEM_LOGIN: Admin [harish] accessed the core.', '2026-04-21 13:41:41'),
(291, 'SYSTEM_LOGIN: Admin [harish] accessed the core.', '2026-04-21 13:42:53'),
(292, 'SYSTEM_LOGIN: Admin [admin] accessed the core.', '2026-04-22 01:38:15'),
(293, 'SYSTEM_LOGIN: Admin [admin] accessed the core.', '2026-04-22 02:11:23'),
(294, 'SYSTEM_LOGIN: Admin [admin] accessed the core.', '2026-04-22 02:19:05'),
(295, 'SYSTEM_LOGIN: Admin [admin] accessed the core.', '2026-04-22 02:22:16'),
(296, 'SYSTEM_LOGIN: Admin [admin] accessed the core.', '2026-04-22 02:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `max_marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) DEFAULT 'Delhi Public Secondary School',
  `logo` varchar(255) DEFAULT NULL,
  `result_status` varchar(50) DEFAULT 'draft',
  `hall_ticket_status` varchar(50) DEFAULT 'draft',
  `smtp_host` varchar(255) DEFAULT 'localhost',
  `smtp_user` varchar(255) DEFAULT NULL,
  `smtp_pass` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT 465,
  `smtp_encryption` varchar(10) DEFAULT 'ssl'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `school_name`, `logo`, `result_status`, `hall_ticket_status`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_encryption`) VALUES
(1, 'Delhi Public Secondary School', NULL, 'draft', 'draft', 'localhost', 'admin', 'admin123', 465, 'ssl');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `ms_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `old_school` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `father_contact` varchar(20) DEFAULT NULL,
  `mother_contact` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `last_total` decimal(10,2) DEFAULT 0.00,
  `last_percentage` decimal(10,2) DEFAULT 0.00,
  `last_notified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `ms_no`, `name`, `email`, `phone`, `class_id`, `father_name`, `old_school`, `mother_name`, `previous_school`, `father_contact`, `mother_contact`, `dob`, `address`, `last_total`, `last_percentage`, `last_notified`) VALUES
(5, 'MST2026001', 'Rajuri Riya', 'pradeep.rajuri@gmail.com', '9849345680', 2, 'Pradeep', NULL, 'Sravanthi', 'Delhi Public Secondary School', NULL, NULL, '2019-12-04', 'Flat no :201 kvm jk homes ', 0.00, 0.00, NULL),
(6, 'MST2026002', 'juttu manivarshith', 'jlasya32@gmail.com', '6302709463', 2, 'manesh', NULL, 'sunitha', 'delhi public secondary school siddipet', NULL, NULL, '2019-05-17', 'kallepelly,bejjanki,siddipet', 0.00, 0.00, NULL),
(7, 'MST2026003', 'Paleu Devansh', 'cnu4908@gmail.com', '9182511109', 2, 'Palepu Srinivas', NULL, 'Palepu Shoba', 'DPPS Siddipeta ', NULL, NULL, '2019-09-25', 'Near womens Degree collage \nAyodya nager\nSiddipeta', 0.00, 0.00, NULL),
(8, 'MST2026004', 'A Reyansh patel', 'avkanna.hortico@gmail.com', '9731353663', 2, 'A venkanna ', NULL, 'Manjula K ', 'DPSS', NULL, NULL, '2018-09-20', 'Mythri vanam Siddipet ', 0.00, 0.00, NULL),
(9, 'MST2026005', 'K Himansh', 'sushma233@gmail.com', '9246935889', 2, 'Hariprasad ', NULL, 'Susmitha ', 'Dpss', NULL, NULL, '2020-02-07', 'Siddipet ', 0.00, 0.00, NULL),
(10, 'MST2026006', 'Challaram Nishwanth Reddy', 'challaramsushmitha@gmail.com', '7702866216', 3, 'Challaram Sridhar Reddy', NULL, 'Challaram Sushmitha Reddy', 'Delhi Public School ', NULL, NULL, '2019-04-12', 'Vinayaka nagar colony, road no 1 ', 0.00, 0.00, NULL),
(11, 'MST2026007', 'AEMMA.AARNAV', 'nmamatha1990@gmail.com', '9666971722', 3, 'AEMMA.SRINIVAS', NULL, 'N.MAMATHA', 'DELHI PUBLIC SECONDARY SCHOOL', NULL, NULL, '2019-04-23', '18-18-66/7C/A/1, HARIPRIYA NAGAR, STREET NO 1, SIDDIPET-502103', 0.00, 0.00, NULL),
(12, 'MST2026008', 'AEMMA.AARYAN', 'nmamatha1990@gmail.com', '9666971722', 3, 'AEMMA.SRINIVAS', NULL, 'N.MAMATHA', 'DELHI PUBLIC SECONDARY SCHOOL ', NULL, NULL, '2019-04-23', 'H.no:18-18-66/7C/A/1, HARIPRIYA NAGAR, STREET NO 1, SIDDIPET -502103', 0.00, 0.00, NULL),
(13, 'MST2026009', 'Guda.Mokshagna Reddy', 'gudalavanya0495@gmail.com', '9030877655', 3, 'Guda.Srinivas Reddy', NULL, 'Guda.Lavanya Reddy', 'Delhi Public Secondary School ', NULL, NULL, '2017-05-20', 'Brundhavan colony, Milan garden road, Opposite new bustand, Siddipet, Telangana ', 0.00, 0.00, NULL),
(14, 'MST2026010', 'Rikkala rishan reddy', 'ram1995ya@gmail.com', '9000106680', 3, 'Rajashekhar reddy', NULL, 'Ramya', 'DPSS', NULL, NULL, '2019-02-08', 'Siddipet ', 0.00, 0.00, NULL),
(15, 'MST2026011', 'Sabbena Ganeshu', 'shekarsai7@gmail.com', '8143551817', 3, 'Sabbena Shekhar ', NULL, 'Kuncham manga ', 'DPSS SIDDIPET ', NULL, NULL, '2018-09-14', 'Ayodhya Nagar street no 16 Nearby DPSS SIDDIPET ', 0.00, 0.00, NULL),
(16, 'MST2026012', 'M.Bhuvik chary', 'sandhya.malloji@gmail.com', '8885511137', 3, 'Malloji VenuGopal Chary', NULL, 'Malloji Sandhya', '', NULL, NULL, '2018-07-06', 'Siddipet ', 0.00, 0.00, NULL),
(17, 'MST2026013', 'Arith Palumari', 'srinivas.palumari@gmail.com', '9849750709', 3, 'Srinivas Palumari', NULL, 'Rachana Palumari', '', NULL, NULL, '2019-05-31', 'DS Pride Apartment, Maitrivanam, Siddipet', 0.00, 0.00, NULL),
(18, 'MST2026014', 'Veeramallu adith shiva', 'veeramallusoujanya209@gmail.com', '9849194216', 4, 'Veeramallu kotesh ', NULL, 'Veeramallu sowjanya ', 'Delhi Public Secondary School', NULL, NULL, '0000-00-00', 'Kanchi chaurasta', 0.00, 0.00, NULL),
(19, 'MST2026015', 'Palepu Medha', 'cnu4908@gmail.com', '9182511109', 4, 'Palepu Srinivas', NULL, 'Palepu Shoba', 'DPPS Siddipeta ', NULL, NULL, '2017-09-18', 'Near Womens Degree collage \nAyodya Nager, Siddipeta', 0.00, 0.00, NULL),
(20, 'MST2026016', 'Thouti Saharsh', 'srinivas.thouti@gmail.com', '9949346104', 4, 'Thouti Srinivas', NULL, 'Thouti Revathi', 'DPSS', NULL, NULL, '2016-12-02', 'Siddipet', 0.00, 0.00, NULL),
(21, 'MST2026017', 'Sabbena Rohitha', 'shekarsai7@gmail.com', '8143551817', 4, 'Sabbena Shekhar ', NULL, 'Kuncham manga ', 'DPSS SIDDIPET ', NULL, NULL, '2017-05-28', 'Ayodhya Nagar , street no 16 Near DPSS SIDDIPET ', 0.00, 0.00, NULL),
(22, 'MST2026018', 'SABBENA ROHITHA', 'shekarsai7@gmail.com', '8143551817', 4, 'Sabbena Shekhar ', NULL, 'Kuncham manga ', 'DPSS SIDDIPET ', NULL, NULL, '2017-05-28', 'Ayodhya Nagar street no 16 Near by SIDDIPET ', 0.00, 0.00, NULL),
(23, 'MST2026019', 'Sai Aarush Donthula', 'kalyan8060@gmail.com', '9299998060', 4, 'Donthula kalyan kumar ', NULL, 'Donthula Harika ', 'DPSS, SIDDIPET ', NULL, NULL, '2017-03-30', 'Siddipet ', 0.00, 0.00, NULL),
(24, 'MST2026020', 'Arraboina Riyanshi Yadav', 'kumaryadav607@gmail.com', '9492653253', 4, 'Arraboina Kumar', NULL, 'Chinnaboina Bhavani', 'Sri Chaitanya ', NULL, NULL, '2018-03-04', '18-150/1/D2 Teachers colony Siddipet ', 0.00, 0.00, NULL),
(25, 'MST2026021', 'K Charan tej', 'sushma233@gmail.com', '9246935889', 4, 'Hariprasad ', NULL, 'Susmitha ', '', NULL, NULL, '2018-04-16', 'Siddipet ', 0.00, 0.00, NULL),
(26, 'MST2026022', 'Bairy bhuvansai', 'sindhimanjula@gmail.com', '8500528500', 5, 'Bairy Rajinikanth ', NULL, 'Sindhi Manjula ', 'Delhi public secondary school ', NULL, NULL, '2015-11-24', '1-89 sri Sai nagar 3b Siddipet ', 0.00, 0.00, NULL),
(27, 'MST2026023', 'Ashwitha saraf', 'sandhyapatturi2605@gmail.com', '9866975875', 5, 'Saraf Gangadhar ', NULL, 'Sandhya', 'Delhi public secondary school ', NULL, NULL, '2016-01-07', 'Flot no.505,Thirumala guardania,near Siddhartha school,Srinivasa Nagar, siddipet ', 0.00, 0.00, NULL),
(28, 'MST2026024', 'Linga Karthikeya', 'mounika.kesetty@gmail.com', '9849695544', 5, 'Linga Sudheer Kumar', NULL, 'Linga Mounika', 'DPSS ', NULL, NULL, '2017-03-17', 'Flat no:501, A block,Go Green Grand Apartment,Mythri Vanam,Siddipet ', 0.00, 0.00, NULL),
(29, 'MST2026025', 'K Rakshitha reddy', 'kbreddypaint@gmail.com', '9346782889', 5, 'K Bhaskar reddy ', NULL, 'K ARCHANA reddy', 'Goutham model  school siddipet', NULL, NULL, '2016-07-06', 'Siddipet Ayodhya nagar -14 th colony', 0.00, 0.00, NULL),
(30, 'MST2026026', 'Syed Shahzaib Ahmed', 'syedshakeer0509@gmail.com', '9059555705', 5, 'Syed Shakeer Ahmed ', NULL, 'Sameena Anjum ', 'Iris sakalya', NULL, NULL, '2016-04-06', 'Khaderpura,  Siddipet ', 0.00, 0.00, NULL),
(31, 'MST2026027', 'Votarikari likhithsai', 'sindhimanjula@gmail.com', '9177260170', 5, 'V. Ramu ', NULL, 'S.mamatha ', 'Kendriya vidyalaya ', NULL, NULL, '2018-12-31', '4-4-75 Siddipet ', 0.00, 0.00, NULL),
(32, 'MST2026028', 'V. likhithsai', 'sindhimanjula@gmail.com', '9177260170', 5, 'Ramu.v', NULL, 'Mamatha.s', 'Kendriya vidyalaya ', NULL, NULL, '2015-12-31', '4-4-75 Siddipet ', 0.00, 0.00, NULL),
(33, 'MST2026029', 'A Shruthika', 'manjula.kashetty@gmail.com', '9951364662', 5, 'Amiri Shetty ', NULL, 'Kashetty ', 'Sri chaithanya techno school ', NULL, NULL, '2015-02-21', 'Mythri vanam Siddipet ', 0.00, 0.00, NULL),
(34, 'MST2026030', 'Rajuri Kruthi Bhoomi', 'pradeep.rajuri@gmail.com', '9849345680', 6, 'Pradeep', NULL, 'Sravanthi', 'Delhi Public Secondary School', NULL, NULL, '2016-03-15', 'Flat no:201 kvm jk homes siddipet', 0.00, 0.00, NULL),
(35, 'MST2026031', 'Katthi Tharuni', 'lingamurthykatthewar@gmail.com', '9505382121', 6, 'K Lingamurthy ', NULL, 'K Ruchitha ', 'Dpss', NULL, NULL, '2014-08-27', 'Srinivasa nagar siddipet ', 0.00, 0.00, NULL),
(36, 'MST2026032', 'K Rithvij reddy', 'kamidisurenderreddy@gmail.com', '9849751232', 6, 'K surender reddy', NULL, 'K Madhuri reddy', 'Goutham model School ', NULL, NULL, '2015-09-10', 'Siddipet ayodhya nagar -14 th colony', 0.00, 0.00, NULL),
(37, 'MST2026033', 'K Rithvij Reddy', 'madhurisuri50@gmail.com', '9849751232', 6, 'K surender reddy', NULL, 'K Madhuri reddy', 'Goutham model school ', NULL, NULL, '2015-09-10', 'Siddipet ayodhya nagar -14 th colony ', 0.00, 0.00, NULL),
(38, 'MST2026034', 'Indrapuri. Samanvitha', 'lingamindrapuri@gmail.com', '9666949738', 6, 'Indrapuri. Lingam', NULL, 'Indrapuri. Dhana Laxmi', 'Kakatiya Techno High School', NULL, NULL, '2014-07-13', 'Housing board colony, Siddipet', 0.00, 0.00, NULL),
(39, 'MST2026035', 'Nischith', 'madhavi3755@gmail.com', '9704844701', 6, 'Srinivas ', NULL, 'Madhavi ', 'Pallavi model school ', NULL, NULL, '0000-00-00', 'Housing board colony \n ', 0.00, 0.00, NULL),
(40, 'MST2026036', 'Nischith', 'madhavi3755@gmail.com', '9704844701', 6, 'Srinivas ', NULL, 'Madhavi ', 'Pallavi model school ', NULL, NULL, '2015-06-02', 'Housing board colony near Harish road \nCamp office \n', 0.00, 0.00, NULL),
(41, 'MST2026037', 'Nischith', 'madhavi3755@gmail.com', '9704844701', 6, 'Srinivas ', NULL, 'Madhavi ', 'Pallavi model school ', NULL, NULL, '0000-00-00', 'Housing board colony \n', 0.00, 0.00, NULL),
(42, 'MST2026038', 'Vennela Saithanvi Reddy', 'vennelathanvireddy@gmail.com', '9912370991', 6, 'Vennela Prabhakar Reddy ', NULL, 'Vennela Lavanya Reddy ', 'Rao\'s continental school ', NULL, NULL, '2015-04-09', 'Ankampet, Chinthamadaka, siddipet ', 0.00, 0.00, NULL),
(43, 'MST2026039', 'Aayush Devulapally', 'devulapallyseenu@gmail.com', '9391127332', 6, 'Srinivas Devulapally ', NULL, 'Rajitha Devulapally ', 'St. Vincent Pallotti High School ', NULL, NULL, '2015-02-20', 'Bejjanki ', 0.00, 0.00, NULL),
(44, 'MST2026040', 'M. REYANSH CHARY', 'venu12587@gmail.com', '8885511137', 6, 'Venu gopal', NULL, 'Sandhya', '', NULL, NULL, '2015-06-27', 'Srinagar coloney siddipet', 0.00, 0.00, NULL),
(45, 'MST2026041', 'K.HARINAAKSH CHARY', 'deepikachary57@gmail.com', '9030471820', 6, 'K.RAGHAVENDRA CHARY', NULL, 'K.DEEPIKA', '', NULL, NULL, '2015-04-16', '14-18-170/4/A/1\nBalaji Nagar colony, Siddipet ', 0.00, 0.00, NULL),
(46, 'MST2026042', 'Akira Palumari', 'srinivas.palumari@gmail.com', '9849750709', 6, 'Srinivas Palumari', NULL, 'Rachana Palumari', '', NULL, NULL, '2015-02-25', 'DS Pride Apartment, Maitrivanam, Siddipet', 0.00, 0.00, NULL),
(47, 'MST2026043', 'N.Akshitha', 'glatha43@gmail.com', '9912350437', 7, 'N.venkatesh', NULL, 'N.latha', 'DPSS', NULL, NULL, '2014-01-04', 'Mahashakti nagar-4', 0.00, 0.00, NULL),
(48, 'MST2026044', 'Garima', 'singh1989garima@gmail.com', '7887789888', 7, 'Mahavir ', NULL, 'Pramila ', 'Dpss', NULL, NULL, '2014-08-08', 'Fhfghhghj', 0.00, 0.00, NULL),
(49, 'MST2026045', 'Repaka Rithwik reddy', 'ravinderreddyrepaka@gmail.com', '9951862797', 7, 'Repaka Ravinder reddy ', NULL, 'Repaka sukanya ', 'DPSS siddipet ', NULL, NULL, '2014-06-21', 'Palamakula ', 0.00, 0.00, NULL),
(50, 'MST2026046', 'Indrapuri. Samanvitha', 'lingamindrapuri@gmail.com', '9666949738', 7, 'Indrapuri. Lingam', NULL, 'Indrapuri. Dhana Laxmi', 'Kakatiya Techno High School', NULL, NULL, '2014-07-13', 'Housing board colony, Siddipet', 0.00, 0.00, NULL),
(51, 'MST2026047', 'Bairy Nithya sri', 'sindhimanjula@gmail.com', '8500528500', 8, 'Bairy Rajinikanth ', NULL, 'Sindhi Manjula ', 'Delhi public secondary school ', NULL, NULL, '2013-08-15', '1-89 sri Sai nagar 3b Siddipet ', 0.00, 0.00, NULL),
(52, 'MST2026048', 'Guda.Srilekhya Reddy', 'bombaysrinu99@gmail.com', '9030877655', 8, 'Guda.Srinivas Reddy ', NULL, 'Guda.Lavanya Reddy ', 'Delhi Public Secondary School ', NULL, NULL, '2012-11-22', 'Brundhavan colony,milan garden road, opposite new bustand ', 0.00, 0.00, NULL),
(53, 'MST2026049', 'R. SAIPRATHEEK', 'renikindiprakash@gmail.com', '9848561002', 8, 'R. PRAKASH', NULL, 'R. PRIYADARSHINI', 'Shubodaya', NULL, NULL, '2013-09-01', '5_2_30\nNear Lal Kaman\nParipally street\nSiddipet\n', 0.00, 0.00, NULL),
(54, 'MST2026050', 'Deepthansh Vilasagaram', 'vilasagaramdeepthamsh75@gmail.com', '9666478482', 8, 'Vilasagaram sathyam ', NULL, 'Vilasagaram usha sri', 'Sri chaithanya techno high school ', NULL, NULL, '2013-07-08', 'H.no 9-97/27,vivekananda colony, BEJJANKI ', 0.00, 0.00, NULL),
(55, 'MST2026051', 'Deepthamsh Vilasagaram', 'vilasagaramdeepthamsh75@gmail.com', '9666478482', 8, 'Vilasagaram satyanarayana ', NULL, 'Vilasagaram usha sri', 'Sri chaithanya techno high school, siddipet ', NULL, NULL, '2013-07-08', 'H. No 9-97/27,vivekananda colony, BEJJANKI ', 0.00, 0.00, NULL),
(56, 'MST2026052', 'Dusa Mokshith', 'mounikabpt@gmail.com', '9705091651', 8, 'Dusa Haribabu ', NULL, 'Gunda Mounika ', '', NULL, NULL, '2014-01-01', 'Go green grand apartments, mythri vanam,Siddipet ', 0.00, 0.00, NULL),
(58, 'MST2026054', 'Saraf lokesh', 'ganesh.saraf4@gmail.com', '9866975875', 9, 'Saraf Gangadhar ', NULL, 'Sandhya ', 'DPSS', NULL, NULL, '2012-11-25', 'Flot no 505,Thirumala guardania,near Siddhartha school, Srinivasa Nagar, siddipet ', 0.00, 0.00, NULL),
(59, 'MST2026055', 'Kachickattuveli Alwin Roy', 'abrahamroy749@gmail.com', '9705908214', 9, 'Kachickattuveli Roy Abraham ', NULL, 'Kachickattuveli Shanty Roy ', 'DPSS ', NULL, NULL, '2012-07-02', 'Near Akshaya biriyani center Dubbak ', 0.00, 0.00, NULL),
(60, 'MST2026056', 'Repaka avinash reddy', 'ravinderreddyrepaka@gmail.com', '9951862797', 9, 'Repaka Ravinder reddy ', NULL, 'Repaka sukanya', 'DPSS siddipet', NULL, NULL, '2012-06-18', 'Palamakula', 0.00, 0.00, NULL),
(61, 'MST2026057', 'Sarala', 'sulochanagali12@gmail.com', '7337389345', 9, 'Sathaiah', NULL, 'Sulochana', 'Rao\'s continental school', NULL, NULL, '2013-04-09', 'Kammarlapally\n', 0.00, 0.00, NULL),
(62, 'MST2026058', 'Sarala', 'sulochanagali12@gmail.com', '7337389345', 9, 'Sathaiah', NULL, 'Sulochana', 'Rao\'s continental school ', NULL, NULL, '2013-04-09', 'Siddipet district, kammarlapally \n', 0.00, 0.00, NULL),
(63, 'MST2026059', 'Sulochana Gali', 'sulochanagali12@gmail.com', '7337389345', 9, 'Sathaiah', NULL, 'Sulochana ', 'Rao\'s continental school ', NULL, NULL, '2013-04-09', 'Kammarlapally \n', 0.00, 0.00, NULL),
(64, 'MST2026060', 'Vennela Aaradhya Reddy', 'vennelathanvireddy@gmail.com', '9912370991', 9, 'Vennela Prabhakar Reddy', NULL, 'Vennela Lavanya Reddy ', 'Rao\'s continental school ', NULL, NULL, '2012-05-01', 'Ankampet,Chinthamadaka,siddipet', 0.00, 0.00, NULL),
(65, 'MST2026061', 'GARIPALLY RITHIKA', 'garipally.sagar@gmail.com', '9959895260', 9, 'GARIPALLY VIDYA SAGAR ', NULL, 'Rathnakumari B', 'Raos continental schools', NULL, NULL, '2013-03-24', 'Narsapur, Siddipet ', 0.00, 0.00, NULL),
(66, 'MST2026062', 'GARIPALLY RITHIKA', 'garipally.sagar@gmail.com', '9959895260', 9, 'GARIPALLY VIDYA SAGAR ', NULL, 'B Rathnakumari', 'Raos school ', NULL, NULL, '2013-03-24', 'Narsapur siddipet ', 0.00, 0.00, NULL),
(67, 'MST2026063', 'B.SHAANVI REDDY', 'soujanyasavvi376@gmail.com', '9908323732', 9, 'B.GURUVA REDDY', NULL, 'B.SOWJANYA ', 'Sri chaitanya ', NULL, NULL, '2013-10-09', 'Maruthi lezend apartment near ssc city channel and near sri chaitanya branch 1', 0.00, 0.00, NULL),
(68, 'MST2026064', 'Arjun Devulapally', 'devulapallyseenu@gmail.com', '9391127332', 9, 'Srinivas Devulapally ', NULL, 'Rajitha Devulapally ', 'St. Vincent Pallotti High School ', NULL, NULL, '2011-10-13', 'Bejjanki ', 0.00, 0.00, NULL),
(69, 'MST2026065', 'A shreinika', 'manjula.kashetty@gmail.com', '9731353663', 4, 'Venkanna  A', NULL, 'Manjula K', 'Delhi public secondary school ', NULL, NULL, '2017-01-22', 'Mythri vanam Siddipet ', 0.00, 0.00, NULL),
(70, 'MST2026066', 'Ireni Ronith Simha Goud', 'akhilasaiteja1997@gmail.com', '9885887435', 1, 'Ireni Saiteja Goud', NULL, 'Ireni Akhila Goud ', 'Delhi public secondary school Siddipet ', NULL, NULL, '2019-07-11', 'Dubbaka village, Dubbaka, Siddipet ', 0.00, 0.00, NULL),
(71, 'MST2026067', 'Arraboina Hrithvi Sri yadav', 'kumaryadav607@gmail.com', '9492653253', 1, 'Arraboina Kumar ', NULL, 'Chinnaboina Bhavani ', 'DPSS', NULL, NULL, '2020-09-26', '18-150/1/D2 Teachers colony Siddipet ', 0.00, 0.00, NULL),
(72, 'MST2026068', 'SABBENA YASHVARDHAAN', 'shekarsai7@gmail.com', '8143551817', 1, 'Sabbena Shekhar ', NULL, 'Kuncham manga ', 'DPSS SIDDIPET ', NULL, NULL, '2020-06-05', 'Ayodhya Nagar street no 16 Nearby DPSS SIDDIPET ', 0.00, 0.00, NULL),
(73, 'MST2026069', 'Thouti Hanvitha', 'srinivas.thouti@gmail.com', '9949346104', 1, 'Thouti Srinivas ', NULL, 'Thouti Revathi ', '', NULL, NULL, '2019-08-21', 'H.no 17-149/1,Sri nagar colony,siddipet', 0.00, 0.00, NULL),
(74, 'MST2026070', 'Katta Rohith Kumar', 'sampathkumarkatta456@gmail.com', '9010082047', 5, 'katta Sampath Kumar', NULL, 'Lavanya', 'oxford school', NULL, NULL, '2016-12-03', 'Mytrivanam, Siddipet', 0.00, 0.00, NULL),
(75, 'MST2026071', 'Katta Roshan Kumar', 'sampathkumarkatta456@gmail.com', '9010082047', 3, 'Katta Sampath Kumar', NULL, 'Lavanya', 'Oxford School', NULL, NULL, '2018-05-07', 'Mytrivanam, Siddipet', 0.00, 0.00, NULL),
(76, 'MST2026072', 'Mohammad Aadam', 'moinf7@gmail.com', '9291316686', 5, 'Mohd.khaja Moinuddin ', NULL, 'Sana banu', 'Dpss', NULL, NULL, '2015-06-12', 'Shabari castle,flat no 405, haripriya nagar Siddipet ', 0.00, 0.00, NULL),
(77, 'MST2026073', 'Iqra banu', 'moinf7@gmail.com', '9291316686', 7, 'Mohd khaja Moinuddin ', NULL, 'Sana banu', 'Dpss', NULL, NULL, '2014-02-09', 'Shabari castle, Flat no 405, haripriya nagar, Siddipet ', 0.00, 0.00, NULL),
(78, 'MST2026074', 'Gampa lithisha', 'sharanya.kura@gmail.com', '8096255555', 2, 'Gampa Sai Pavan ', NULL, 'Gampa Sharanya ', 'Delhi Public Secondary School', NULL, NULL, '2020-02-10', '4-1-53,Nehru Road,Siddipet,Telangana State,Pincode :-502103', 0.00, 0.00, NULL),
(79, 'MST2026075', 'Vidhu Nandhan. P', 'sathish2848@gmail.com', '9701686198', 2, 'Satheesh', NULL, 'Sreeja', 'DPSC', NULL, NULL, '2019-07-13', 'Houseing board colony, Siddipet ', 0.00, 0.00, NULL),
(80, 'MST2026076', 'Battu Srimanvi', 'battulakshminagaraju@gmail.com', '9666555460', 2, 'Battu Nagaraju', NULL, 'Pallati Lakshmi', 'DPSS', NULL, NULL, '2019-05-15', 'H.No.21-2-42/1\nTHR Nagar Siddipet (Indur College Back Side)', 0.00, 0.00, NULL),
(81, 'MST2026077', 'Battu Srimanvi', 'battulakshminagaraju@gmail.com', '9666555460', 2, 'Battu Nagaraju', NULL, 'Pallati Lakshmi', 'DPSS', NULL, NULL, '2019-05-15', 'H.No.21-2-42/1\nT.H.R.Nagar (Indur College Back Side)\nSiddipet ', 0.00, 0.00, NULL),
(82, 'MST2026078', 'Ridhi sri', 'ranjithkumar466@gmail.com', '9440623084', 2, 'Ranjithkumar ', NULL, 'Sai latha ', 'Iris ', NULL, NULL, '2020-02-27', 'Hno 18-23/1 Shivaji Nagar siddipet ', 0.00, 0.00, NULL),
(83, 'MST2026079', 'ADEPU SHANMUKH', 'sridharadepu2007@gmail.com', '9666124105', 3, 'ADEPU SRIDHAR', NULL, 'KUCHANA NAGAVENI', 'DELHI PUBLIC SECONDARY SCHOOL', NULL, NULL, '2019-05-21', 'SHIVAJI NAGAR, SIDDIPET', 0.00, 0.00, NULL),
(84, 'MST2026080', 'BACHAPELLY AARADHYA DIMPUL', 'anilmunna121@gmail.com', '7799773435', 7, 'BACHAPELLY THIRUPATHI', NULL, 'BACHAPELLY SUMALATHA', 'DELHI PUBLIC SECONDARY SCHOOL SIDDIPET ', NULL, NULL, '2015-07-01', '6-182/C/1, JILLELLA, THANGALLAPALLY, RAJANNA SIRCILLA DISTRICT, TELANGANA, PIN COAD 505405.', 0.00, 0.00, NULL),
(85, 'MST2026081', 'Busa Kushal', 'praveenkumarbusa@gmail.com', '9866665244', 1, 'Busa Praveen Kumar', NULL, 'Boppa Maheshwari', '', NULL, NULL, '2019-07-29', 'Siddipet', 0.00, 0.00, NULL),
(86, 'MST2026082', 'VELUGALA ASHWITH', 'rameshvelugala123@gmail.com', '9849615117', 8, 'RAMESH', NULL, 'MADHAVI', 'Presentation high school ', NULL, NULL, '2013-08-27', '21-86,Rajeev nagar,CHERIAL-506223,Dist: Siddipet TG', 0.00, 0.00, NULL),
(87, 'MST2026083', 'Aitha Thanvika', 'nagakrishna_aitha@yahoo.co.in', '9663621122', 2, 'Aitha Nagakrishna', NULL, 'Aitha Saisree', 'DPSS', NULL, NULL, '2019-10-08', 'H:No:5-2-101, Paripally Street, Near Lal Kaman, Siddipet', 0.00, 0.00, NULL),
(88, 'MST2026084', 'Aitha Yashvika', 'nagakrishna_aitha@yahoo.co.in', '9663621122', 2, 'Aitha NagaKrishna', NULL, 'Aitha Saisree', 'DPSS', NULL, NULL, '2019-10-08', 'H:No:5-2-101, Paripally Street, Near Lal Kaman, Siddipet', 0.00, 0.00, NULL),
(89, 'MST2026085', 'M Manvitha', 'prasanna_lakshmi12@yahoo.com', '9959035300', 2, 'Manideep Rao M', NULL, 'Prasanna Lakshmi G', 'Dpss', NULL, NULL, '2019-05-09', 'Villa number 60 \nBhuvi villas \nPonnala \nSiddipet ', 0.00, 0.00, NULL),
(90, 'MST2026086', 'NAMBI SHREYANSH NANDAN', 'pravn_2205@yahoo.com', '9160187995', 3, 'N PRAVEEN KUMAR', NULL, 'SESHABHAVANI', 'MERIDIAN HIGH SCHOOL', NULL, NULL, '2018-11-18', 'HNO: 18-17-186/5/A/1,\nSRINAGAR COLONY,\nNEAR PANCHAMUKHA HANUMAN TEMPLE,\nSIDDIPET,\nTELANGANA-502103', 0.00, 0.00, NULL),
(91, 'MST2026087', 'Sirikonda charunya', 'sirikondalavanya29@gmail.com', '9618646078', 9, 'Sirikonda kishanchary ', NULL, 'Sirikonda lavanya ', 'ST. VINCENT PALLOTTI HIGH SCHOOL ', NULL, NULL, '2012-11-07', '(VILLAGE ) thangallapalli (mandal) koheda (district ) siddipet ', 0.00, 0.00, NULL),
(92, 'MST2026088', 'T.Saanvi', 'sujathasrinivas3123@gmail.com', '9866355301', 7, 'T.Srinivas', NULL, 'T.Sujatha ', 'DPSS', NULL, NULL, '2014-11-20', 'Housing board ,Siddipet', 0.00, 0.00, NULL),
(93, 'MST2026089', 'T.advait', 'sujathasrinivas3123@gmail.com', '9866355301', 4, 'T.srinivas', NULL, 'Sujatha Thummanapally', 'Dpss', NULL, NULL, '2018-07-17', 'Housing board, siddipet ', 0.00, 0.00, NULL),
(94, 'MST2026090', 'BURRA SHREE AYAAN GOUD', 'bharathimotors.sdpt@gmail.com', '8500704009', 8, 'BURRA UDAY ', NULL, 'BURRA VAISHNAVI', 'CHAITHANYA SCHOOL', NULL, NULL, '2014-08-22', 'FLOT NO:202,BALAJI HOMES,KUSHAL NAGAR ROAD,NEAR NEW BUSSTAND,SIDDIPET', 0.00, 0.00, NULL),
(95, 'MST2026091', 'BURRAADHWIKA GOUD', 'bharathiautofinance@gmail.com', '8500704009', 1, 'BURRA UDAY GOUD', NULL, 'BURRA VAISHNAVI', 'MERIDIAN HIGH SCHOOL', NULL, NULL, '2020-11-06', 'FLOT NO:202,BALAJI HOMES KUSHAL NAGAR ROAD NEAR NEW BUS STAND SIDDIPET', 0.00, 0.00, NULL),
(96, 'MST2026092', 'Muramshetty . Sri Varsha', 'saisudhamuramshetty@gmail.com', '9299995090', 8, 'Muramshetty. Santhosh', NULL, 'Muramshetty. Sai Sudha ', 'Delhi public secondary school ', NULL, NULL, '0000-00-00', '7-1-68\nsubhash road', 0.00, 0.00, NULL),
(97, 'MST2026093', 'Sri Valli Muramshetty', 'saisudhamuramshetty@gmail.com', '9299995090', 4, 'Santosh Muramshetty ', NULL, 'Sai Sudha Muramshetty ', 'Delhi public secondary School', NULL, NULL, '2026-07-09', '7-1-68\nsubhash road', 0.00, 0.00, NULL),
(98, 'MST2026094', 'Yamsani Sudheeksha', 'mum.mouni@gmail.com', '9845181766', 3, 'Yamsani Sandeep', NULL, 'Uma Mounica', 'DPSS', NULL, NULL, '2019-05-10', 'Shivaji Nagar,siddipet ', 0.00, 0.00, NULL),
(99, 'MST2026095', 'Sri Valli Muramshetty', 'saisudhamuramshetty@gmail.com', '9299995090', 4, 'Santhosh Muramshetty ', NULL, 'Sai Sudha Muramshetty ', 'Delhi public secondary school ', NULL, NULL, '2026-07-09', '7-1-68\nsubhash road', 0.00, 0.00, NULL),
(100, 'MST2026096', 'N. Druvitha sri charani', 'supraja.nimma@gmail.com', '9676830083', 6, 'Nimma rajeev kumar reddy ', NULL, 'Nimma supraja', 'Delhi public secondary school ', NULL, NULL, '2015-11-18', 'House no. 1-8/3, road no. 4, srinagar colony, cherial, mandal:cherial, Dist:Siddipet ', 0.00, 0.00, NULL),
(101, 'MST2026097', 'Bandi Jai Goud', 'bngoud2904@gmail.com', '9059450506', 2, 'Bandi Naresh', NULL, 'Nirosha', 'Oak valley st Paul school ', NULL, NULL, '2018-06-04', 'Siddipet', 0.00, 0.00, NULL),
(102, 'MST2026098', 'S.Nihaansh', 'spramnutri@gmail.com', '9491882504', 5, 'S Parashuramulu ', NULL, 'Niharika ', 'Delhi Public Secondary School ', NULL, NULL, '2017-04-15', 'Shivaji Nagar, Siddipet ', 0.00, 0.00, NULL),
(103, 'MST2026099', 'S Shreeyansh', 'kalwalaniharika@gmail.com', '9491882504', 2, 'S Parashuramulu ', NULL, 'Niharika ', 'Delhi Public Secondary school ', NULL, NULL, '2020-02-13', 'Shivaji Nagar ', 0.00, 0.00, NULL),
(104, 'MST2026100', 'Garnapally. Bruhan', 'garnapallykavitha@gmail.com', '9866227149', 3, 'Garnapally Ravikumar ', NULL, 'Garnapally kavitha ', 'Delhi public secondary school ', NULL, NULL, '2018-02-15', 'Gurjakunta x road, cherial, siddipet, Telangana ', 0.00, 0.00, NULL),
(105, 'MST2026101', 'Garnapally Gahan', 'garnapallykavitha@gmail.com', '9866227149', 1, 'Garnapally r', NULL, 'GARNAPALLY Ravikumar ', 'Oakley International school, cherial ', NULL, NULL, '2020-08-31', 'Gurjakunta x road, cherial, siddipet, Telangana ', 0.00, 0.00, NULL),
(106, 'MST2026102', 'S Shreeyansh', 'spramnutri@gmail.com', '9491882504', 2, 'S Parashuramulu ', NULL, 'Niharika ', 'Delhi Public Secondary School ', NULL, NULL, '2020-02-13', 'Shivaji Nagar Siddipet ', 0.00, 0.00, NULL),
(107, 'MST2026103', 'Gaje Amith patel', 'chittampallyravali119@gmail.com', '9000446484', 3, 'Gaje srinivas ', NULL, 'Gaje Ravali ', 'Dpss ', NULL, NULL, '2018-03-01', 'Bejjenki crossing ', 0.00, 0.00, NULL),
(108, 'MST2026104', 'Gaje Amith patel', 'chittampallyravali119@gmail.com', '9000446484', 4, 'Gaje srinivas ', NULL, 'Chittampally Ravali', 'Dpss', NULL, NULL, '2018-03-01', 'Bejjanki crossing ', 0.00, 0.00, NULL),
(109, 'MST2026105', 'Srinithya reddy', 's78260545@gmail.com', '8790997689', 9, 'Srikanth reddy ', NULL, 'Anitha ', 'Sri chaitanya ', NULL, NULL, '2012-06-04', 'Brindavan colony ', 0.00, 0.00, NULL),
(110, 'MST2026106', 'E.Datta Shanmuki', 'bhanuchander8985@gmail.com', '9642829297', 4, 'E.Bhanuchander', NULL, 'S.Sudha kumari', 'Delhi Public Secondary School ', NULL, NULL, '2018-03-08', 'Sudha Nilayam, Ayodhyanagar Road No-03, Beside Police Convention Hall, Ponnala', 0.00, 0.00, NULL),
(111, 'MST2026107', 'K.Aayansh Reddy', 'neerajareddy.ias@gmail.com', '7989750498', 2, 'K.Gopal Reddy', NULL, 'K.Neeraja', 'Vikas high school ', NULL, NULL, '2019-05-29', 'Plot no-8\nGreenland colony \nBehind doodi mallareddy function hall \nSiddipet ', 0.00, 0.00, NULL),
(112, 'MST2026108', 'Neerati Aryan', 'neeratijyothi375@gmail.com', '7702228552', 7, 'Neerati Raju', NULL, 'Neerati Jyothi ', 'Delhi police school Siddipet ', NULL, NULL, '2014-03-05', 'Venkatraopally M) Mustabad D)Rajanna siricila ', 0.00, 0.00, NULL),
(113, 'MST2026109', 'Neerati Rohan', 'neeratijyothi375@gmail.com', '7702228552', 6, 'Neerati Raju ', NULL, 'Neerati Jyothi ', 'Delhi public school Siddipet ', NULL, NULL, '2015-10-02', 'V) Venkatraopally M) Mustabad D)Rajanna siricila ', 0.00, 0.00, NULL),
(114, 'MST2026110', 'Mamindla Aayan', 'thiru9193@gmail.com', '8978274258', 2, 'Thirupathi ', NULL, 'Aparna ', 'ST, vincent pallotti high school', NULL, NULL, '2019-09-11', 'Village :kallepalli \nMandal:Bejjanki \nDist:siddipet ', 0.00, 0.00, NULL),
(115, 'MST2026111', 'Bandi Sri Deeksha', 'bngoid2904@gmail.com', '9059450506', 6, 'Bandi Naresh ', NULL, 'Nirosha', 'Sri Chaitanya Techno school ', NULL, NULL, '2015-07-12', 'Siddipet', 0.00, 0.00, NULL),
(116, 'MST2026112', 'N.vashistayan reddy', 'supraja.nimma@gmail.com', '9676830083', 2, 'N. Rajeev kumar reddy ', NULL, 'N. Supraja ', 'DPSS', NULL, NULL, '2019-11-05', 'House no.1-8/3, street no. 4, srinagar colony, cherial, mon. Cherial, district. Siddipet. ', 0.00, 0.00, NULL),
(117, 'MST2026113', 'juttu manivarshith', 'jlasya32@gmail.com', '6302709463', 2, 'mahesh', NULL, 'sunitha', 'delhi publick secondary school siddipet', NULL, NULL, '2019-05-17', 'kallepelli, bejjanki, siddipet', 0.00, 0.00, NULL),
(118, 'MST2026114', 'Gudipally hadya reddy', 'gudipally.nareshreddy@gmail.com', '9494943828', 1, 'Gudipally Naresh reddy', NULL, 'battu shirisha', 'sahasraa high school ', NULL, NULL, '2020-05-28', 'brundavan colony siddipet', 0.00, 0.00, NULL),
(119, 'MST2026115', 'Aniya rathan', 'clemi.mbbs@gmail.com', '8977106049', 4, 'Clement manohar', NULL, 'Madhulika reddy', 'Dpss siddpet', NULL, NULL, '2018-07-03', 'C/o Ashwath children hospital siddpet', 0.00, 0.00, NULL),
(120, 'MST2026116', 'ANVIYA rathan', 'clemi.mbbs@gmail.com', '8977106049', 3, 'Clement manohar', NULL, 'Madhulika reddy', 'Dpss', NULL, NULL, '2019-09-21', 'Ashwath children?s Hospital siddipet', 0.00, 0.00, NULL),
(121, 'MST2026117', 'Veeramallu adith shiva', 'koteshveeramallu@gmail.com', '9849194216', 5, 'V.Kotesh ', NULL, 'V.Sowjanya', 'Iris Florets Sakalya  School', NULL, NULL, '2016-06-08', 'Kanchit Chowrasth, Medak Road ,siddipet', 0.00, 0.00, NULL),
(122, 'MST2026118', 'M.Aadvik Reddy', 'srinuvasreddy7malreddy@gmail.com', '9701044898', 1, 'M.Srinu', NULL, 'Vijitha', '', NULL, NULL, '2021-02-02', 'Srinagar Colony,Siddipet ', 0.00, 0.00, NULL),
(123, 'MST2026119', 'LIMMA AISHWARYA', 'lcharansbh@gmail.com', '7382276168', 1, 'LIMMA CHARAN', NULL, 'LIMMA VINUSHA', 'NA', NULL, NULL, '2020-11-21', 'MythriVanam opposite siddipet', 0.00, 0.00, NULL),
(124, 'MST2026120', 'Kamalla pratheeksha', 'jyothisrikamalla@gmail.com', '9290099948', 9, 'Kamalla srinivas ', NULL, 'Kamalla jyothi', 'Delhi public secondary school ', NULL, NULL, '2012-06-28', '5-40 Repaka \nMandal:- Ellanthakunta\nDistrict:- Rajannasircilla \nPincode:- 505528', 0.00, 0.00, NULL),
(125, 'MST2026121', 'M. Ayaan', 'rameshmustyala14@gmail.com', '9959992475', 2, 'M. Ramesh', NULL, 'T Swathi', 'DPSS SIDDIPET', NULL, NULL, '2019-08-14', 'Mythrivanam siddipet', 0.00, 0.00, NULL),
(126, 'MST2026122', 'Bandi Srivarshini', 'penkarlaashok1995@gmail.com', '8688180712', 6, 'Bandi Ramesh ', NULL, 'Bandi Sandhya ', 'Geetha Heigh school Bejjanki ', NULL, NULL, '2014-09-14', 'Village Bejjanki, Mondal Bejjanki ', 0.00, 0.00, NULL),
(127, 'MST2026123', 'Advay Sanka', 'radhika.akarapu@gmail.com', '9700525959', 8, 'Sridhar Sanka', NULL, 'Radhika Sanka', 'DPSS', NULL, NULL, '2013-07-21', 'Flat no. 303, Vishnupriya Apartment, Near Lalkaman, Main Road, Siddipet ', 0.00, 0.00, NULL);

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `log_student_delete` AFTER DELETE ON `students` FOR EACH ROW BEGIN
    INSERT INTO logs (action) 
    VALUES (CONCAT('CRITICAL: STUDENT_DELETED: ID #', OLD.id, ' (', OLD.name, ') removed from system.'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_student_insert` AFTER INSERT ON `students` FOR EACH ROW BEGIN
    INSERT INTO logs (action) 
    VALUES (CONCAT('NEW_STUDENT_ADDED: ID #', NEW.id, ' (', NEW.name, ') by Admin'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_student_update` AFTER UPDATE ON `students` FOR EACH ROW BEGIN
    INSERT INTO logs (action) 
    VALUES (CONCAT('STUDENT_MODIFIED: ID #', OLD.id, ' (', OLD.name, ') fields updated.'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`) VALUES
(1, 'MATHAMATICS'),
(2, 'SCIENCE'),
(3, 'SCIENCE'),
(4, 'ENGLISH'),
(5, 'ENGLISH'),
(6, 'G.K'),
(7, 'SCIENCE'),
(8, 'E.V.S'),
(9, 'SOCIAL'),
(10, 'LOGICAL & REASONING');

-- --------------------------------------------------------

--
-- Table structure for table `system_notices`
--

CREATE TABLE `system_notices` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT 'GENERAL',
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_notices`
--

INSERT INTO `system_notices` (`id`, `category`, `message`, `created_at`) VALUES
(1, 'URGENT', 'Merit Scholarship Test Registration is now LIVE.', '2026-04-20 16:26:26'),
(4, 'EXAM', 'Merit Scholarship Test Registration is now LIVE 2026 - 27', '2026-04-21 11:21:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`date`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_class` (`class_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_notices`
--
ALTER TABLE `system_notices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_notices`
--
ALTER TABLE `system_notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
