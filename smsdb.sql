-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table smsdb.admin_db
CREATE TABLE IF NOT EXISTS `admin_db` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `adminName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userName` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.admin_db: ~0 rows (approximately)
INSERT INTO `admin_db` (`Id`, `adminName`, `userName`, `password`, `photo`, `date_time`) VALUES
	(1001, 'Admin', 'admin', 'admin', NULL, '2024-06-28 07:06:53');

-- Dumping structure for table smsdb.attendances
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` tinyint NOT NULL,
  `course_id` int NOT NULL DEFAULT '0',
  `student_id` int NOT NULL DEFAULT '0',
  `section_id` int NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `date` (`date`,`course_id`,`student_id`,`section_id`),
  KEY `course_id` (`course_id`),
  KEY `student_id` (`student_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.attendances: ~5 rows (approximately)
INSERT INTO `attendances` (`id`, `status`, `course_id`, `student_id`, `section_id`, `date`) VALUES
	(1, 0, 12, 12, 6, '2024-08-03'),
	(2, 1, 11, 12, 5, '2024-08-03'),
	(3, 1, 11, 12, 5, '2024-09-02'),
	(4, 0, 12, 12, 6, '2024-09-25'),
	(5, 1, 11, 12, 5, '2024-09-25');

-- Dumping structure for table smsdb.course_db
CREATE TABLE IF NOT EXISTS `course_db` (
  `c_ID` int NOT NULL AUTO_INCREMENT,
  `c_title` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_hours` int DEFAULT NULL,
  PRIMARY KEY (`c_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.course_db: ~5 rows (approximately)
INSERT INTO `course_db` (`c_ID`, `c_title`, `c_code`, `c_hours`) VALUES
	(6, 'Visual Programming', 'CSC 439', 2),
	(8, 'Computer Graphics', 'CSC 455', 4),
	(10, 'C++', 'CSC 283', 3),
	(11, 'C Programming', 'CSC 183', 3),
	(12, 'Software Engineering', 'CSC 469', 3),
	(13, 'FEDS', 'CSC 231', 4),
	(14, 'Assembly Language', 'CSC169', 3);

-- Dumping structure for table smsdb.grades
CREATE TABLE IF NOT EXISTS `grades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `section_id` int DEFAULT NULL,
  `marks` int DEFAULT NULL,
  `grade` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('completed','in-progress') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'in-progress',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_db` (`s_ID`),
  CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course_db` (`c_ID`),
  CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.grades: ~2 rows (approximately)
INSERT INTO `grades` (`id`, `student_id`, `course_id`, `section_id`, `marks`, `grade`, `status`, `created_at`, `updated_at`) VALUES
	(1, 12, 12, 6, 89, 'A+', 'in-progress', '2024-09-25 09:23:06', '2024-10-19 19:21:39'),
	(2, 12, 11, 5, 60, 'B', 'in-progress', '2024-09-25 10:03:45', '2024-10-19 19:20:49'),
	(3, 12, 14, 13, 68, 'B+', 'in-progress', '2024-10-21 18:58:42', '2024-10-21 18:58:42'),
	(4, 12, 10, 4, 76, 'A', 'in-progress', '2024-10-21 19:08:46', '2024-10-21 19:08:46');

-- Dumping structure for table smsdb.notices
CREATE TABLE IF NOT EXISTS `notices` (
  `id` int NOT NULL DEFAULT '0',
  `section_id` bigint NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.notices: ~0 rows (approximately)
INSERT INTO `notices` (`id`, `section_id`, `title`, `message`, `created_at`) VALUES
	(4, 5, 'HomeWork', 'Do your Home work properly it will be graded for final exam', '2024-09-25 13:08:57');

-- Dumping structure for table smsdb.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `status` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `currency` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table smsdb.orders: ~0 rows (approximately)

-- Dumping structure for table smsdb.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `total_amount` int NOT NULL,
  `paid_amount` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table smsdb.payments: ~1 rows (approximately)
INSERT INTO `payments` (`id`, `student_id`, `total_amount`, `paid_amount`) VALUES
	(1, 12, 42000, 42000);

-- Dumping structure for table smsdb.public_notice_db
CREATE TABLE IF NOT EXISTS `public_notice_db` (
  `pn_id` int NOT NULL AUTO_INCREMENT,
  `pn_title` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pn_message` varchar(600) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pn_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pn_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.public_notice_db: ~2 rows (approximately)
INSERT INTO `public_notice_db` (`pn_id`, `pn_title`, `pn_message`, `pn_date`) VALUES
	(1, 'Tomorrow institute will be closed ', 'During worst Weather.', '2024-06-29 12:08:00'),
	(2, 'Next Week Mid Term', 'All students have to be present their all classes before Mid Term exam.', '2024-06-29 20:03:55');

-- Dumping structure for table smsdb.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_no` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_no` (`room_no`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.rooms: ~6 rows (approximately)
INSERT INTO `rooms` (`id`, `room_no`, `name`, `description`) VALUES
	(1, 1001, 'Classroom', ''),
	(3, 102, 'Admission Room', ''),
	(5, 1006, 'Accounts Room', ''),
	(8, 1010, 'Classroom', ''),
	(9, 702, 'Classroom', '');

-- Dumping structure for table smsdb.sections
CREATE TABLE IF NOT EXISTS `sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `course_id` int NOT NULL,
  `room_id` int DEFAULT NULL,
  `days` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `section` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `room` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `course_id` (`course_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.sections: ~9 rows (approximately)
INSERT INTO `sections` (`id`, `teacher_id`, `course_id`, `room_id`, `days`, `time`, `section`, `room`) VALUES
	(1, 2, 6, NULL, 'Saturday,Monday,Wednesday', '2:15-3:15', 'C', 701),
	(2, 2, 6, NULL, 'Saturday,Sunday,Monday', '10:40-11:40', 'B', 903),
	(3, 3, 10, NULL, 'Saturday,Sunday,Monday', '3:20-4:20', 'D', 401),
	(4, 3, 10, NULL, 'Saturday,Sunday,Monday', '4:25-5:25', 'E', 1001),
	(5, 4, 11, NULL, 'Saturday,Sunday,Monday', '2:15-3:15', 'D', 605),
	(6, 4, 12, NULL, 'Saturday,Sunday,Monday', '9:35-10:35', 'A', 604),
	(8, 2, 8, NULL, 'Saturday,Sunday,Monday', '9:35-10:35', 'A', 1001),
	(10, 1, 8, NULL, 'Tuesday,Wednesday', '10:40-11:40', 'B', 1001),
	(11, 1, 6, NULL, 'Tuesday', '8:30-9:30', 'D', 1010),
	(12, 3, 14, 6, 'Saturday,Sunday,Monday', '10:40-11:40', 'A', NULL),
	(13, 3, 14, 8, 'Wednesday,Thursday', '2:15-3:15', 'B', NULL);

-- Dumping structure for table smsdb.student_course
CREATE TABLE IF NOT EXISTS `student_course` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `section_id` int NOT NULL,
  `student_id` int NOT NULL,
  `status` enum('completed','in-progress') COLLATE utf8mb4_general_ci DEFAULT 'in-progress',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `course_id` (`course_id`),
  KEY `section_id` (`section_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.student_course: ~4 rows (approximately)
INSERT INTO `student_course` (`id`, `course_id`, `section_id`, `student_id`, `status`) VALUES
	(2, 10, 4, 12, 'completed'),
	(3, 11, 5, 12, 'completed'),
	(4, 12, 6, 12, 'completed'),
	(6, 6, 2, 12, 'in-progress'),
	(7, 14, 13, 12, 'completed');

-- Dumping structure for table smsdb.student_db
CREATE TABLE IF NOT EXISTS `student_db` (
  `s_ID` int NOT NULL AUTO_INCREMENT,
  `s_Name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_Email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `s_UserName` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_Password` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_Gender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_DateOfBirth` date DEFAULT NULL,
  `s_ProfilePic` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_FatherName` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_MotherName` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_ContactNo` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_AltContactNo` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_Address` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_Photo` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`s_ID`),
  UNIQUE KEY `s_Email` (`s_Email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.student_db: ~2 rows (approximately)
INSERT INTO `student_db` (`s_ID`, `s_Name`, `s_Email`, `s_UserName`, `s_Password`, `s_Gender`, `s_DateOfBirth`, `s_ProfilePic`, `s_FatherName`, `s_MotherName`, `s_ContactNo`, `s_AltContactNo`, `s_Address`, `s_Photo`) VALUES
	(12, 'Redwan Ahmed', 'redwan@gmail.com', 'redwan101', '123', 'Male', '1999-03-02', NULL, 'Shakil', 'Shakila', '01775200200', '01896651651', 'R-11, Uttara-10', '../images/student_photos/413225695_361046579954388_1957413545747094606_n.jpg');

-- Dumping structure for table smsdb.teacher_db
CREATE TABLE IF NOT EXISTS `teacher_db` (
  `t_ID` int NOT NULL AUTO_INCREMENT,
  `t_Name` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_UserName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_Password` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_Email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_Gender` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_DateOfBirth` date DEFAULT NULL,
  `t_ProfilePic` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_ContactNo` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_AltContactNo` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_Address` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_department` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_designation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`t_ID`),
  UNIQUE KEY `t_Email` (`t_Email`),
  UNIQUE KEY `t_UserName` (`t_UserName`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table smsdb.teacher_db: ~4 rows (approximately)
INSERT INTO `teacher_db` (`t_ID`, `t_Name`, `t_UserName`, `t_Password`, `t_Email`, `t_Gender`, `t_DateOfBirth`, `t_ProfilePic`, `t_ContactNo`, `t_AltContactNo`, `t_Address`, `t_department`, `t_designation`) VALUES
	(1, 'Mushfiq Hasan', 'mushfiq201', '123', 'mushfiq@gmail.com', '', '1980-06-10', NULL, '01775200200', '01775200200', 'R-11, Uttara-10', 'THM', 'Lecturer'),
	(2, 'Tanvir Hasan', 'tanvir85', '12345', 'tanvir.hasan@gmail.com', 'Male', '1985-11-30', NULL, '01953588110', '01953588110', 'R-11, Uttara-10', 'CSE', 'Lecturer'),
	(3, 'AH Rony', 'rony', '123456', 'rony@gmail.com', 'Male', '2000-01-21', NULL, '01234567890', '', 'Uttara Dhaka', 'CSE', 'Lecturer'),
	(4, 'Abid Islam', 'abid', '123456', 'abid@gmail.com', 'Male', '2000-02-29', '../images/teacher_images/415097214_1322000505167242_1485500018141359025_n.png', '01234567890', '', 'Uttara Dhaka', 'CSE', 'Lecturer');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
