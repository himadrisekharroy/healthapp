-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2021 at 04:46 PM
-- Server version: 5.6.41-84.1
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ritzcybe_healthapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `parent_admin_id` int(11) NOT NULL DEFAULT '0',
  `admin_email` varchar(250) NOT NULL,
  `admin_mobile` varchar(50) DEFAULT NULL,
  `admin_password` varchar(250) NOT NULL,
  `admin_name` varchar(250) NOT NULL,
  `admin_image` varchar(250) NOT NULL,
  `admin_role` int(11) NOT NULL,
  `admin_status` enum('1','0') NOT NULL DEFAULT '1',
  `otp` varchar(10) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `parent_admin_id`, `admin_email`, `admin_mobile`, `admin_password`, `admin_name`, `admin_image`, `admin_role`, `admin_status`, `otp`, `created_on`) VALUES
(1, 0, 'admin', '9830054067', '$2y$11$0123456789abcdefghijkefO.KL55rn6zYCY.d3wXAqBoe3US3f4K', 'Super admin', '', 1, '1', '3167', '2016-06-01 22:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `admin_role`
--

CREATE TABLE `admin_role` (
  `role_id` int(11) NOT NULL,
  `parent_role_id` int(11) NOT NULL DEFAULT '0',
  `role_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_role`
--

INSERT INTO `admin_role` (`role_id`, `parent_role_id`, `role_title`, `status`) VALUES
(1, 0, 'Super Admin', 1),
(29, 1, 'test designation actora', 1);

-- --------------------------------------------------------

--
-- Table structure for table `block`
--

CREATE TABLE `block` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `block`
--

INSERT INTO `block` (`id`, `title`, `content`, `is_active`, `created_on`) VALUES
(3, 'Welcome to Remote Health', '<p>We know that your medical needs don&#39;t stop when office hours are over. Using our help line and our email service, you can get a message to your health team when it is convenient for you</p>', 1, '2019-01-26 01:35:27'),
(4, 'Download our mobile app', '<p>Schedule a visit with one of our experienced doctors or message, manage your appointments, medical history, or send a prescription to the nearest pharmacy once you have consulted with a doctor.</p>', 1, '2019-01-26 01:36:33'),
(5, 'Benefits for individuals and employers', '<div class=\"why_text\">\r\n<p>&ldquo;One digital healthcare solution for you and your employees&rdquo;.</p><p> You can easily participate and provide access to physicians when needed via telemedicine benefits for both traditional medical needs and specialty care. Employees will have access to experienced and board certified physicians with high-quality care that includes lab testing services, prescriptions, and personal care management.</p>\r\n</div>\r\n\r\n<div class=\"why_list\">\r\n<ul><!-- Why List Item -->\r\n	<li class=\"d-flex flex-row align-items-center justify-content-start\">\r\n	<div class=\"icon_container d-flex flex-column align-items-center justify-content-center\">\r\n	<div class=\"icon\"><img alt=\"https://www.flaticon.com/authors/prosymbols\" src=\"images/icon_1.svg\" /></div>\r\n	</div>\r\n\r\n	<div class=\"why_list_content\">\r\n	<div class=\"why_list_title\">QUALITY CARE</div>\r\n\r\n	<div class=\"why_list_text\">Actora provides access to board-certified physicians and licensed specialists through tele visits.</div>\r\n	</div>\r\n	</li>\r\n	<!-- Why List Item -->\r\n	<li class=\"d-flex flex-row align-items-center justify-content-start\">\r\n	<div class=\"icon_container d-flex flex-column align-items-center justify-content-center\">\r\n	<div class=\"icon\"><img alt=\"https://www.flaticon.com/authors/prosymbols\" src=\"images/icon_2.svg\" /></div>\r\n	</div>\r\n\r\n	<div class=\"why_list_content\">\r\n	<div class=\"why_list_title\">AFFORDABLE CARE</div>\r\n\r\n	<div class=\"why_list_text\">Expect a significant ROI with an engagement-driven partnership model. Dependent on your business size and needs, you&rsquo;ll be setup with the package that is best for you.</div>\r\n	</div>\r\n	</li>\r\n	<!-- Why List Item -->\r\n	<li class=\"d-flex flex-row align-items-center justify-content-start\">\r\n	<div class=\"icon_container d-flex flex-column align-items-center justify-content-center\">\r\n	<div class=\"icon\"><img alt=\"https://www.flaticon.com/authors/prosymbols\" src=\"images/icon_3.svg\" /></div>\r\n	</div>\r\n\r\n	<div class=\"why_list_content\">\r\n	<div class=\"why_list_title\">EFFIECIENT ACCESSIBLE CARE</div>\r\n\r\n	<div class=\"why_list_text\">Your employees will have access to urgent and everyday care. A video visit with one of our providers can be made over a smartphone, tablet, or computer.</div>\r\n	</div>\r\n	</li>\r\n</ul>\r\n</div>\r\n\r\n<div class=\"why_text\">\r\n<p>Actora reduces absenteeism, healthcare costs while delivering high quality healthcare to your employees, anytime, anywhere, 365/24/7 days</p>\r\n</div>', 1, '2019-01-26 01:42:21'),
(6, 'A healthy community', '<p>When you choose us, you join a community. We work not just with you but with other member of our community to build a network of people working together for a healthier world.</p>', 1, '2019-01-26 01:51:20'),
(7, 'Experience and Professionalism', '<p>With years of experience, our medicle team will assess and create a custom recovery plan that&#39;s right for you. We understand the importance on educating you on the most effective way to take care of your body, so that you can heal quickly.&nbsp;</p>', 1, '2019-01-26 01:56:01'),
(8, 'Physicians Who Care', '<p>Not Only our doctors treat your exsisting conditions, we also work to prevent pain and illness from occurring. We strive to help you improve your quality of life, achieve your wellness goals, and heal your body to live your best life possible.&nbsp;</p>', 1, '2019-01-26 01:59:46'),
(9, 'Primary Care', '', 1, '2019-01-26 05:04:22'),
(10, 'Wellness Support', '<p>Our team will support you in building a healther you. no matter what your health need are. having a team support you will keep on the path to meeting them. we work together to connect you the service you need</p>', 1, '2019-01-28 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ph` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `msg` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(10) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email_id` varchar(30) NOT NULL,
  `dob` date NOT NULL,
  `sex` enum('m','f','o') NOT NULL,
  `image` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `designation` varchar(255) NOT NULL,
  `certificate` varchar(255) NOT NULL,
  `language_known` text NOT NULL,
  `phy_id` varchar(255) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `about` text NOT NULL,
  `type` enum('d','jd','n') NOT NULL DEFAULT 'd' COMMENT 'd:Doctor, j:Junior Doctor, n:nurse',
  `service_id` int(11) NOT NULL COMMENT 'only for nurse',
  `refered_by` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: inactive; 1:active; 2:otp_verified',
  `create_date` datetime NOT NULL,
  `DDID` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `f_name`, `l_name`, `mobile`, `email_id`, `dob`, `sex`, `image`, `password`, `designation`, `certificate`, `language_known`, `phy_id`, `specialization_id`, `about`, `type`, `service_id`, `refered_by`, `is_active`, `create_date`, `DDID`) VALUES
(11, 'satadal', '', '9999999999', '', '0000-00-00', 'm', '', '$2y$11$EG4dTD20YRWp1mhbGS0/muRZhJazIYiGKkCQMVIXLuzSvtQbsLLb2', '', '', '[\"3\",\"1\",\"2\"]', '', 1, '', 'd', 0, 0, 1, '2020-04-16 09:37:35', ''),
(2, 'Doc1', 'Last', '999999998', 'abc@xyz.com', '0000-00-00', 'm', '', '$2y$11$94xSkKSRZByQHo1ClTin2e1JMqc.DqfdYLrCpNiKypiInhsXCsQX2', 'Srf', '', '[\"1\"]', '567888', 1, 'Neel', 'd', 0, 0, 0, '2020-04-12 16:02:31', NULL),
(13, 'Neel', 'Basu', '9830056107', 'neel.basu.z@gmail.com', '0000-00-00', 'm', '', '$2y$11$73gL4eFRpB6VMtWlQKxOnumPIyDOnBtE4hpfAVluZ61m/sa4Ehkte', '', '', '[\"3\",\"1\"]', '', 1, '', 'd', 0, 0, 1, '2020-04-21 07:43:22', ''),
(14, 'N', 'Mukherjee', '8335049536', '', '0000-00-00', 'f', '', '$2y$11$VICQ9ptboMhPtr2bDuNc9ulMfOPFZj8i4LdBia4EVmImZ1w5QF9a.', '', '', '[\"3\",\"1\",\"2\"]', '', 7, '', 'd', 0, 0, 1, '2020-04-22 09:30:24', ''),
(15, 'Smiti', 'Mistry', '9609337270', 'mistry.sujoy@rediffmail.com', '0000-00-00', 'f', '', '$2y$11$qrvpGRhFCd0XACy9SeS7ruxVu6JdlDWIytxKhHb.GtCqKC8fqo8yG', '', '', '[\"3\",\"1\",\"2\"]', '', 2, '', 'd', 0, 0, 1, '2020-04-27 08:19:16', ''),
(12, 'S', 'Bose', '9230510321', 'bose.shilpi08@gmail.com', '0000-00-00', 'm', '', '$2y$11$kOEySpqkX91OGe2BSmqEzO85jswo5b/juGAXh0E0dP47qIUw.p/US', '', '', '[\"3\",\"1\",\"2\"]', '134567', 7, '', 'd', 0, 0, 1, '2020-04-17 07:43:41', ''),
(16, 'doc', 'roy', '9836464831', '', '0000-00-00', 'm', '', '$2y$11$4X8p2.1.Y2JMFVeoaJ0NI.ztYcrzKo8IwA9Wm3h1eGSsfmEiUMUNS', '', '', '[\"3\",\"1\",\"2\"]', '', 6, '', 'd', 0, 0, 1, '2020-08-06 07:27:41', ''),
(21, 'Nurse', '', '9836464843', 'himadri.1111@gmail.com', '0000-00-00', 'm', '', '$2y$11$VOSW/GGezfMT0pL5Weydn.I7w5MqCUP7shd6wwYP3/MsPJj5yuBo6', '', '', '[\"3\",\"1\",\"2\"]', '', 0, '', 'n', 0, 11, 1, '2020-09-17 16:17:50', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_appointment`
--

CREATE TABLE `doctor_appointment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `month` int(5) NOT NULL,
  `day` int(5) NOT NULL,
  `time` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `app_date_time` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_api_key` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_api_secret` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_session_id` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_token` text COLLATE utf8_unicode_ci NOT NULL,
  `nurse_schedule_time` int(11) NOT NULL DEFAULT '0',
  `relase_nurse` tinyint(1) NOT NULL DEFAULT '0',
  `end_call` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `trans_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status_pay` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctor_appointment`
--

INSERT INTO `doctor_appointment` (`id`, `user_id`, `doc_id`, `year`, `month`, `day`, `time`, `app_date_time`, `create_date`, `note`, `opentok_api_key`, `opentok_api_secret`, `opentok_session_id`, `opentok_token`, `nurse_schedule_time`, `relase_nurse`, `end_call`, `status`, `trans_id`, `status_pay`) VALUES
(10, 3, 11, '2020', 4, 20, '19:01', '2020-04-20 19:01:00', '2020-04-20 07:45:44', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(8, 3, 11, '2020', 4, 20, '13:31', '2020-04-20 13:31:00', '2020-04-20 07:33:21', 'this is test note', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(9, 3, 11, '2020', 4, 20, '17:16', '2020-04-20 17:16:00', '2020-04-20 07:33:21', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(11, 3, 11, '2020', 4, 20, '22:31', '2020-04-20 22:31:00', '2020-04-20 12:46:32', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(12, 3, 11, '2020', 4, 21, '00:32', '2020-04-21 00:32:00', '2020-04-20 14:48:07', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(13, 3, 11, '2020', 4, 21, '01:02', '2020-04-21 01:02:00', '2020-04-20 15:29:28', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(14, 3, 11, '2020', 4, 21, '01:17', '2020-04-21 01:17:00', '2020-04-20 15:45:38', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(15, 3, 11, '2020', 4, 21, '01:47', '2020-04-21 01:47:00', '2020-04-20 16:12:27', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(16, 9, 13, '2020', 4, 21, '17:30', '2020-04-21 17:30:00', '2020-04-21 07:51:27', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(17, 9, 13, '2020', 4, 21, '18:00', '2020-04-21 18:00:00', '2020-04-21 08:23:11', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(18, 3, 13, '2020', 4, 21, '18:15', '2020-04-21 18:15:00', '2020-04-21 08:40:27', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(19, 11, 11, '2020', 5, 4, '19:01', '2020-05-04 19:01:00', '2020-04-26 07:56:11', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(20, 9, 11, '2020', 7, 6, '13:50', '2020-08-06 13:50:00', '2020-08-06 07:18:29', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(21, 12, 11, '2020', 8, 10, '13:31', '2020-08-10 13:31:00', '2020-08-08 08:10:00', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 0, '', 0),
(22, 7, 11, '2020', 8, 27, '13:35', '2020-08-27 13:35:00', '2020-08-08 12:35:25', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(23, 7, 11, '2020', 9, 18, '17:30', '2020-09-18 17:30:00', '2020-09-18 11:57:51', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0),
(24, 7, 11, '2020', 9, 18, '18:15', '2020-09-18 18:15:00', '2020-09-18 12:35:31', '', '46491382', '4d6825e50fe5386203f4ec03ce0a495ed45cb843', '', 'test-token', 0, 0, 0, 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_chat_appointments`
--

CREATE TABLE `doctor_chat_appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `month` int(5) NOT NULL,
  `day` int(5) NOT NULL,
  `time` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `app_date_time` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_api_key` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_api_secret` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_session_id` text COLLATE utf8_unicode_ci NOT NULL,
  `opentok_token` text COLLATE utf8_unicode_ci NOT NULL,
  `nurse_schedule_time` int(11) NOT NULL DEFAULT '0',
  `relase_nurse` tinyint(1) NOT NULL DEFAULT '0',
  `end_call` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_chat_timing`
--

CREATE TABLE `doctor_chat_timing` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctor_chat_timing`
--

INSERT INTO `doctor_chat_timing` (`id`, `doc_id`, `day_id`, `start_time`, `end_time`) VALUES
(6, 11, 0, '09:00:00', '22:00:00'),
(5, 13, 2, '14:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_language_known`
--

CREATE TABLE `doctor_language_known` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctor_language_known`
--

INSERT INTO `doctor_language_known` (`id`, `title`, `is_active`) VALUES
(1, 'English', 1),
(2, 'Hindi', 1),
(3, 'Bengali', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_ph_cl_timing`
--

CREATE TABLE `doctor_ph_cl_timing` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctor_ph_cl_timing`
--

INSERT INTO `doctor_ph_cl_timing` (`id`, `doc_id`, `day_id`, `start_time`, `end_time`) VALUES
(9, 13, 2, '17:00:00', '22:00:00'),
(8, 11, 1, '20:00:00', '22:00:00'),
(7, 11, 0, '07:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_video_timing`
--

CREATE TABLE `doctor_video_timing` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_video_timing`
--

INSERT INTO `doctor_video_timing` (`id`, `doc_id`, `day_id`, `start_time`, `end_time`) VALUES
(1, 11, 1, '13:16:00', '23:00:00'),
(2, 11, 2, '00:17:00', '03:00:00'),
(3, 13, 2, '17:00:00', '22:00:00'),
(4, 11, 0, '13:34:00', '13:35:00'),
(5, 11, 4, '13:20:00', '14:15:00'),
(6, 11, 4, '03:30:00', '16:00:00'),
(7, 11, 5, '17:30:00', '18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_video_timing_slot`
--

CREATE TABLE `doctor_video_timing_slot` (
  `id` int(11) NOT NULL,
  `timimg_id` int(11) NOT NULL,
  `slot` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('u','d') COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `user_type`, `subject`, `comments`, `create_date`) VALUES
(1, 8, 'u', 'tyyyy', 'ggghhh', '2019-02-01 17:30:34'),
(2, 8, 'u', 'Test', 'Awesome', '2019-02-03 11:48:26'),
(3, 8, 'u', 'Test', 'Madhu', '2019-02-07 13:19:43'),
(4, 4, 'd', 'doctor feedback', 'commendt doctor', '2019-02-09 13:36:12'),
(7, 0, '', 'Feedback', '', '2019-12-10 10:41:59'),
(6, 23, 'd', 'Feedback from service', 'Comments - Feedback from service', '2019-12-10 10:38:27'),
(8, 0, '', 'Feedback from service', 'Comments - Feedback from service', '2019-12-10 12:32:57'),
(9, 0, '', 'aditya', 'Actora Mobile app testing', '2019-12-10 12:41:01'),
(10, 0, '', 'Sub - 111219/1', 'Comments - 111219/1', '2019-12-11 06:37:55'),
(11, 0, '', 'Feedback Sub 220120/1', 'Feedback comments 220120/1', '2020-01-22 05:44:04'),
(12, 0, '', 'Feedback Sub 220120/2', 'Feedback comments - 220120/2', '2020-01-22 06:25:45'),
(13, 0, '', '', '', '2020-02-15 03:26:17'),
(14, 0, '', '', '', '2020-02-15 03:28:48'),
(15, 0, '', '', '', '2020-02-15 03:29:29'),
(16, 0, '', '', '', '2020-02-15 23:21:18'),
(17, 0, '', '', '', '2020-02-17 20:21:24'),
(18, 0, '', '', '', '2020-02-19 18:20:34'),
(19, 0, '', '', '', '2020-02-22 05:37:23'),
(20, 0, '', '', '', '2020-02-23 21:02:18'),
(21, 0, '', '', '', '2020-02-25 15:22:21'),
(22, 0, '', '', '', '2020-02-27 06:27:26'),
(23, 0, '', '', '', '2020-02-29 01:59:06'),
(24, 0, '', '', '', '2020-03-04 14:18:31'),
(25, 0, '', '', '', '2020-03-05 14:59:36'),
(26, 0, '', '', '', '2020-03-06 15:41:05'),
(27, 0, '', '', '', '2020-03-07 18:41:08'),
(28, 0, '', '', '', '2020-03-08 20:50:07'),
(29, 0, '', '', '', '2020-03-09 19:11:36'),
(30, 0, '', '', '', '2020-03-10 18:43:00'),
(31, 0, '', '', '', '2020-03-11 17:19:07'),
(32, 0, '', '', '', '2020-03-12 17:33:34'),
(33, 0, '', '', '', '2020-03-13 18:35:24'),
(34, 0, '', '', '', '2020-03-14 17:19:30'),
(35, 0, '', '', '', '2020-03-15 14:57:07');

-- --------------------------------------------------------

--
-- Table structure for table `imageupload`
--

CREATE TABLE `imageupload` (
  `uid` int(11) NOT NULL,
  `image_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `imageupload`
--

INSERT INTO `imageupload` (`uid`, `image_path`, `image_name`) VALUES
(1, 's2/s1/72.jpg', '72'),
(2, 's2/', '72'),
(3, 's2/', '72'),
(4, 's2/72.jpg', '72'),
(5, 's2/72.jpg', '72'),
(6, 's2/20200403000009.jpg', '20200403000009'),
(7, 's2/20200403000437.jpg', '20200403000437');

-- --------------------------------------------------------

--
-- Table structure for table `initiate_chat`
--

CREATE TABLE `initiate_chat` (
  `id` int(11) NOT NULL,
  `user_initiation` int(11) NOT NULL,
  `doc_initiation` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `initiate_chat`
--

INSERT INTO `initiate_chat` (`id`, `user_initiation`, `doc_initiation`) VALUES
(3, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `LabId` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Address` varchar(500) NOT NULL,
  `PinCode` varchar(255) NOT NULL,
  `Telephone` varchar(10) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `License` varchar(255) NOT NULL,
  `Validity` date NOT NULL,
  `Status` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `CreateDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`LabId`, `Name`, `Address`, `PinCode`, `Telephone`, `email`, `mobile`, `password`, `License`, `Validity`, `Status`, `is_active`, `CreateDate`) VALUES
(1, 'shaanz', 'Hyderabad', '5000047', '040-123456', 'sksafdar@gmail.com', '9492875321', '$2y$11$wJXNhWr3sNMbrCFQXT7mMOQ4PGVlNmzblhGJNL2nIxkB/8zvltMKW', 'L1234', '2020-06-20', 1, 1, '2019-12-04'),
(2, 'Safdar', 'Hyderabad', '', '', 'safdar@smtindia.net', '9492875321', '$2y$11$vb8oLrcn.rjU5wHR3Lp8fucN24oCKFYUbOZstzwid4W75kDnJwzuq', 'L0612191', '2019-12-05', 1, 1, '2019-12-06'),
(5, 'LabRegTest', 'Hyderabad', '524305', '9492875321', 'lab@actora.in', '9492875321', '$2y$11$AJTPoSI1Sp.FvCkxtyAIHuqiX/ayQEwA.A5.kOrUfQr0Kt49doxTq', 'L123456', '2020-04-01', 1, 1, '2020-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `mail_content`
--

CREATE TABLE `mail_content` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mail_content`
--

INSERT INTO `mail_content` (`id`, `subject`, `content`) VALUES
(1, 'member registration successful', 'member registration successful \r\n\r\n[[replaceable_text]]'),
(2, 'Doctor registration successful', 'doctor registration successful   [[replaceable_text]]');

-- --------------------------------------------------------

--
-- Table structure for table `message_subscription`
--

CREATE TABLE `message_subscription` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `amount` float NOT NULL,
  `validity` int(11) NOT NULL,
  `trans_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status_pay` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message_subscription`
--

INSERT INTO `message_subscription` (`id`, `user_id`, `sid`, `create_date`, `amount`, `validity`, `trans_id`, `status_pay`) VALUES
(1, 3, 1, '2020-04-14 05:56:04', 20, 30, '', 0),
(2, 1, 1, '2020-04-15 05:51:53', 20, 30, '', 0),
(3, 5, 1, '2020-04-16 01:14:30', 20, 30, '', 0),
(4, 7, 2, '2020-04-16 15:43:46', 200, 365, '', 0),
(5, 9, 1, '2020-04-21 08:09:46', 20, 30, '', 0),
(6, 10, 2, '2020-04-22 09:06:18', 200, 365, '', 0),
(7, 11, 1, '2020-04-26 07:49:04', 20, 30, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(1, 'Admin Role'),
(2, 'Admin Utility'),
(3, 'State'),
(4, 'City'),
(5, 'Location'),
(6, 'Price'),
(7, 'Category'),
(8, 'Vendor'),
(9, 'User'),
(10, 'Role Permission'),
(14, 'Plan'),
(11, 'Bank'),
(12, 'Billing Cycle'),
(13, 'Payment Method'),
(15, 'Billing Plan Type'),
(16, 'Billing'),
(17, 'ecart'),
(18, 'report'),
(19, 'cms'),
(20, 'newsletter'),
(21, 'contact us'),
(22, 'Site information'),
(23, 'Slide'),
(24, 'Doctor'),
(25, 'Block'),
(26, 'Doctors Specialization'),
(27, 'nurse'),
(28, 'nurse services');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email`, `create_date`, `is_active`) VALUES
(1, 'testmail@test.in', '2019-01-01 08:40:26', 0),
(2, 'maxxman_121@hotmail.com', '2019-10-02 23:08:39', 1),
(3, 'smeulies@hotmail.com', '2019-10-03 16:26:10', 1),
(4, 'judahjams@yahoo.com', '2019-10-05 00:05:46', 1),
(5, 'joanmraymond@aol.com', '2019-10-05 01:25:40', 1),
(6, 'may.j.luo@gmail.com', '2019-10-08 16:25:49', 1),
(7, 'jillandshawn@verizon.net', '2019-10-08 17:09:02', 1),
(8, 'bradleechang@gmail.com', '2019-10-08 19:46:12', 1),
(9, 'maturno@gmail.com', '2019-10-08 21:22:48', 1),
(10, 'david@davidgilfix.com', '2019-10-08 21:39:13', 1),
(11, 'ap@planetsun.com', '2019-10-14 18:07:24', 1),
(12, 'info@i-dea.it', '2019-10-18 08:55:22', 1),
(13, 'tomorrowslunch5@gmail.com', '2019-10-18 11:40:23', 1),
(14, 'melissa@wrglaw.com', '2019-10-18 18:13:32', 1),
(15, 'adicola10@comcast.net', '2019-10-19 14:53:27', 1),
(16, 'dominika.tyminska@onet.pl', '2019-10-19 19:46:22', 1),
(17, 'lindsaymckinney331@gmail.com', '2019-10-19 22:30:06', 1),
(18, 'rahhart3@yahoo.com', '2019-10-27 14:08:37', 1),
(19, 'ap@barneyspumps.com', '2019-10-27 15:30:46', 1),
(20, 'flakiyo818@hotmail.com', '2019-10-27 18:41:27', 1),
(21, 'krishna.gopinathan@gmail.com', '2020-04-06 16:24:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_fees`
--

CREATE TABLE `nurse_fees` (
  `id` int(11) NOT NULL,
  `nurse_id` int(11) NOT NULL,
  `book_min` int(11) NOT NULL,
  `fees` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nurse_fees`
--

INSERT INTO `nurse_fees` (`id`, `nurse_id`, `book_min`, `fees`) VALUES
(1, 17, 30, 450),
(2, 17, 60, 875),
(3, 17, 90, 1300),
(4, 17, 120, 1700),
(5, 20, 30, 450),
(6, 20, 60, 875),
(7, 20, 90, 1300),
(8, 20, 120, 1700),
(104, 21, 120, 1700),
(103, 21, 90, 1300),
(102, 21, 60, 875),
(101, 21, 30, 450),
(13, 22, 30, 450),
(14, 22, 60, 875),
(15, 22, 90, 1300),
(16, 22, 120, 1700),
(68, 24, 120, 1700),
(67, 24, 90, 1300),
(66, 24, 60, 875),
(65, 24, 30, 450),
(25, 27, 30, 450),
(26, 27, 60, 875),
(27, 27, 90, 1300),
(28, 27, 120, 1700),
(92, 28, 120, 1700),
(91, 28, 90, 1300),
(90, 28, 60, 875),
(89, 28, 30, 450),
(33, 46, 30, 450),
(34, 46, 60, 875),
(35, 46, 90, 1300),
(36, 46, 120, 1700),
(37, 48, 30, 450),
(38, 48, 60, 875),
(39, 48, 90, 1300),
(40, 48, 120, 1700),
(41, 49, 30, 450),
(42, 49, 60, 875),
(43, 49, 90, 1300),
(44, 49, 120, 1700),
(45, 52, 30, 450),
(46, 52, 60, 875),
(47, 52, 90, 1300),
(48, 52, 120, 1700),
(96, 23, 120, 1700),
(95, 23, 90, 1300),
(94, 23, 60, 875),
(93, 23, 30, 450),
(73, 41, 30, 450),
(74, 41, 60, 875),
(75, 41, 90, 1300),
(76, 41, 120, 1700),
(81, 53, 30, 450),
(82, 53, 60, 875),
(83, 53, 90, 1300),
(84, 53, 120, 1700),
(97, 57, 30, 450),
(98, 57, 60, 875),
(99, 57, 90, 1300),
(100, 57, 120, 1700),
(105, 58, 30, 450),
(106, 58, 60, 875),
(107, 58, 90, 1300),
(108, 58, 120, 1700),
(109, 60, 30, 450),
(110, 60, 60, 875),
(111, 60, 90, 1300),
(112, 60, 120, 1700),
(113, 61, 30, 450),
(114, 61, 60, 875),
(115, 61, 90, 1300),
(116, 61, 120, 1700),
(117, 17, 30, 450),
(118, 17, 60, 875),
(119, 17, 90, 1300),
(120, 17, 120, 1700),
(121, 18, 30, 450),
(122, 18, 60, 875),
(123, 18, 90, 1300),
(124, 18, 120, 1700),
(125, 19, 30, 450),
(126, 19, 60, 875),
(127, 19, 90, 1300),
(128, 19, 120, 1700),
(129, 20, 30, 450),
(130, 20, 60, 875),
(131, 20, 90, 1300),
(132, 20, 120, 1700),
(133, 21, 30, 450),
(134, 21, 60, 875),
(135, 21, 90, 1300),
(136, 21, 120, 1700);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_fees_structure`
--

CREATE TABLE `nurse_fees_structure` (
  `id` int(11) NOT NULL,
  `book_min` int(11) NOT NULL,
  `fees` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nurse_fees_structure`
--

INSERT INTO `nurse_fees_structure` (`id`, `book_min`, `fees`) VALUES
(1, 30, 450),
(2, 60, 875),
(3, 90, 1300),
(4, 120, 1700);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_provided_services`
--

CREATE TABLE `nurse_provided_services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nurse_provided_services`
--

INSERT INTO `nurse_provided_services` (`id`, `title`, `is_active`) VALUES
(4, 'Physiotherapy', 1),
(5, 'Counselling', 1),
(6, 'Injectibles', 1),
(7, 'Diagnostic Lab services', 1),
(8, 'PersonalCaring', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_service_selected`
--

CREATE TABLE `nurse_service_selected` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `nurse_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nurse_service_selected`
--

INSERT INTO `nurse_service_selected` (`id`, `service_id`, `nurse_id`) VALUES
(1, 5, 17),
(2, 5, 20),
(3, 4, 20),
(5, 4, 22);

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `title`, `content`, `is_active`, `created_on`) VALUES
(3, 'Privacy Policy', '<h5 >ACTORA, INC.,</h5>\n			<div><p>At ACTORA, INC, we take your privacy seriously. Our privacy policy and terms of use provide an explanation of what happens to any personal data that you share with us or that we collect from you. We update this policy from time to time, so please review it regularly.</p></div>\n			<h6>We may collect and process the following data about you:</h6>\n			<div>\n				<ul>\n					<li> â€¢ Details of your visits to our website and the resources that you access, including, but not limited to traffic data, location data, weblogs and other communication data</li>\n					<li> â€¢ Information that you provide by filling in forms on our website, such as when you registered</li>\n					<li> â€¢ Information provided to us when you communicate with us for any reason</li>\n				</ul>\n			</div>\n			<h6>Use of Cookies</h6>\n			<div>\n				<p>On occasion, we may gather information about your computer for our services and to provide statistical information regarding the use of our website to our advertisers or others.</p>\n				<p>Such information may identify you personally â€“ it is statistical data or identifiable data about our visitors and their use of our site. This statistical data does not identify any personal details whatsoever. The identifiable data may identify you personally from the information you have provided us.</p>\n				<p>Similarly to the above, we may gather information about your general internet use by using a cookie file. Where used, these cookies are downloaded to your computer automatically. This cookie file is stored on the hard drive of your computer as cookies contain information that is transferred to your computerâ€™s hard drive. They help us to improve our website and the service that we provide to you.</p>\n				<p>All computers have the ability to decline cookies. This can be done by activating the setting on your browser which enables you to decline the cookies. Please note that should you choose to decline cookies, you may be unable to access particular parts of our website. Our advertisers may also use cookies, over which we have no control. Such cookies (if used) would be downloaded once you click on advertisements on our website.</p>\n			</div>\n			<h6>Use of Your Information</h6>\n			<div>\n				<p>\n					The information that we collect and store relating to you is primarily used to enable us to provide our services to you or your patients. In addition, we may use the information for the following purposes:</p>\n					<p>To provide you with information requested from us, relating to our programs or services. To provide information on other programs which we feel may be of interest to you, where you have consented to receive such information. To meet our contractual commitments to you. To notify you about any changes to our website, such as improvements or service/program changes, that may affect our service. To communicate with manufacturers, pharmacies and other third parties to provide services to you or your patients. To provide third parties with statistical information about the users of our website.</p>\n					<p>We will only contact you or allow third parties to contact you when you have provided consent and only by those means you provided consent for.</p>\n					<p>If you do not want us to use your data for our use or third partiesâ€™ use, you will have the opportunity to withhold your consent to this when you provide your details to us on the form on which we collect your data. Data that is provided to us is stored on our secure servers. Details relating to any transactions entered into on our site will be encrypted to ensure its safety.</p>\n					<p>The transmission of information via the internet is not completely secure and therefore we cannot guarantee the security of data sent to us electronically and transmission of such data is therefore entirely at your own risk. Where we have given you (or where you have chosen) a password so that you can access certain parts of our site, you are responsible for keeping this password confidential.\n				</p>\n			</div>\n			<h6>Disclosing Your Information</h6>\n			<div><p>Where applicable, we may disclose your personal information to any member of our group. This includes, where applicable, our subsidiaries, our holding company and its other subsidiaries (if any), as well as third parties involved in the administration and operation of Actoraâ€™s services and your participation therein. We may also disclose your personal information to third parties:</p>\n				<p>Where we sell any or all of our business and/or our assets to a third party. Where we are legally required to disclose your information. Where we believe it will assist you or your patients with additional products or services. When we are contractually obligated to disclose it. When it relates to reimbursement.</p>\n			</div>\n			<h6>Third Party Links</h6>\n			<div>\n				<p>\n					You may find links to third party websites on our website. These websites should have their own privacy policies which you should check. We do not accept any responsibility or liability for their policies whatsoever as we have no control over them.\n				</p>\n			</div>\n\n			<h6>Access To Information</h6>\n			<div>\n				<p>\n					The Data Protection Act 1998 gives you the right to access the information that we hold about you. Should you wish to receive details that we hold about you please contact us using the contact details below.\n				</p>\n			</div>\n			<h6>Changes To Our Privacy Policy</h6>\n			<div>\n				<p>\n					If we decide to change our privacy policy, we will post those changes on this page. This policy was last modified 05/3/2019.\n				</p>\n			</div>\n			<h6>Contacting Us</h6>\n			<div>\n				<p>\n					If there are any questions regarding this policy you may contact us using the information below:<br/>privacy@actora.com\n				</p>\n			</div>', 1, '2018-12-29 18:34:09'),
(2, 'Terms and Condition', '<div>\n				<p>\n					Actora is committed to maintaining the privacy and security of your personal information. This Privacy Statement summarizes how Actora may collect and utilize personally identifiable information about visitors to our site.</p>\n					<p>Actora values individual privacy and we want to give our website visitors the opportunity to know what information we collect about them and how they can limit the use of personally-identifiable information beyond the purposes for which they first provided it. At all times, however, Actora reserves the right to disclose information where required by law or to comply with valid legal process (such as a search warrant, subpoena or court order), to protect Actoraâ€™s rights or property, including without limitation in the event of a transfer of control of Actora or substantially all of its assets, or during emergencies when safety is at risk.</p>\n					<p>You should check this Privacy Policy regularly to see if there have been any changes.</p>\n				</p>\n			</div>\n			<h6>Optional Registration</h6>\n			<div>\n				<p>If you wish to utilize certain services located in our website, you may be asked to register. Those services currently include:</p>\n				<ul>\n					<li> â€¢ Access to dashboards, white papers, brochures, product fact sheets and other publications</li>\n					<li> â€¢ Use of certain Actora products made available online</li>\n					<li> â€¢ E-mail newsletters</li>\n				</ul>\n				<p>The information collected when a user registers may include but is not limited to: full name, e-mail address, physical or mailing address and area of interest information. This information may be stored in an off-line database or in the websiteâ€™s on-line server. If you register on our site, we may use this information to contact you about our services or to follow up with you on information you may have requested through our website.</p>\n			</div>\n			<h6>Childrenâ€™s Privacy</h6>\n			<div>\n				<p>Actoraâ€™s website is intended for adults. Actora does not knowingly collect personal information from children under the age of 13.</p>\n			</div>\n			<h6>Cookies & Computer-Related Information</h6>\n			<div>\n				<p>Actora does not enable â€œcookiesâ€ on our website with one exception. A cookie is used in website system administration to keep track of movement of an individual user from one screen to another. This information may be used by Actora to detect and resolve website problems and to assist with customer support. We do not collect any personally identifiable information about site visitors in this process.</p>\n				<p>Our web server automatically collects information from your computer and navigation patterns when you visit our site, including your Internet Protocol (IP) address, the computerâ€™s operating system, the type of browser you use, and the specific web pages visited during your connection. We may also track data such as the total number of visits to our website and the number of visitors to each page of our website.</p>\n				<p>We may use this information, in aggregate form, for system maintenance and to better understand how our visitors use our site and services so that we can make them better. Actora may also share statistical or demographic information in aggregate form with third parties for marketing or research purposes. This aggregate data will not contain any information that personally identifies you.</p>\n			</div>\n			<h6>How You Can Control and Update Data About You</h6>\n			<div>\n				<p>We want to be sure that we keep only the most accurate and up-to-date information about you in our records. Therefore, whenever you believe that your contact information needs to be updated, you can email us.</p>\n				<p>You may choose at any time to remove your name, telephone and fax numbers, and postal and email addresses from the lists we use to send notices or updates and elect not to receive correspondence from us by emailing us</p>\n				<p>Other Internet sites you visit â€” including those linked from the Actora website â€” may have their own privacy policies or no policy at all. Other websites might use personal information differently than our policy permits. We strongly encourage you to review the privacy policies of any site before providing any personal information.</p>\n			</div>\n			<h6>Opt-Out Procedures</h6>\n			<div>\n				<p>If a user chooses to register with Actora, the user can choose not to receive communications from Actora or third parties who we believe offer products or services of value to the user. In order to opt-out, contact Actora. For more ways to opt-out, see the bottom of this webpage.</p>\n			</div>\n			<h6>Privacy & Security</h6>\n			<div>\n				<p>We use recognized industry safeguards to protect the information you provide from unauthorized access or use. We also have in place privacy protection control contractual obligations with our vendors designed to ensure that personal data is protected from unauthorized access or disclosure. All Actora employees must abide by Actoraâ€™s Privacy Policy. Only authorized employees are permitted to have access to personally identifiable data about website visitors, and that access is limited to what is reasonably needed to perform an employeeâ€™s responsibilities, such as providing updates or notices or customer service. Employees who violate our privacy policies are subject to disciplinary action, up to and including termination.</p>\n				<p>We employ industry recognized security safeguards to protect the personally identifiable information that you have provided to us from loss, misuse and unauthorized alteration. If you are required to transmit sensitive information to us through our website, we will provide you access to our secured server that allows encryption of your data as it is transmitted to us. We will protect personally identifiable information stored on the websiteâ€™s servers from unauthorized access using commercially available computer security products (e.g., firewalls), as well as carefully developed security procedures and practices.</p>\n			</div>\n			<h6>Access</h6>\n			<div>\n				<p>You may update your personal information that you have provided to us. We will take steps to make sure that any updates that you provide us are processed in a timely and complete manner. If we collect personal information through our websites and databases, we will maintain the information and allow you to update it any time except during scheduled downtime for maintenance. We will continue to work on better methods of accessing your information to increase your access to it for update purposes. Please note that some of our websites contain hyperlinks to other websites or resources outside of our control. Be sure to read that websiteâ€™s or resourceâ€™s privacy policy before proceeding to that website or resource.</p>\n			</div>\n			<h6>Customer Service</h6>\n			<div>\n				<p>We will tell you how you can contact us regarding our privacy statement and practices. If you have any questions about this privacy statement, our information handling practices, or any other aspects of your privacy and the security of information, please send an email toÂ PrivacyPolicy@Actora.comÂ Â and ask to be connected to the. We may periodically update our privacy policy to describe how new features may affect our use of your information. Be sure to periodically reread our privacy policy and contact us if your privacy preferences change.</p>\n			</div>\n			<h6>Do Not Track Signals</h6>\n			<div>\n				<p>â€˜Please note that your browser setting may allow you to automatically transmit a â€œDo Not Trackâ€ signal to websites and online service you visit. There is no consensus among industry participants as to what â€œDo Not Trackâ€ means in this context. Like many websites and online services, Actora does not alter its practices when it receives a â€œDo Not Trackâ€ signal from a visitorâ€™s browser. For more information about do not track signals, please visitÂ https://allaboutdnt.com/.</p>\n			</div>\n			<h6>How To Contact Us</h6>\n			<div>\n				<p>For any questions or comments regarding our information or security practices, you may contact us:</p>\n				<ul>\n					<li> â€¢ By E-mail:Â PRIVACY@ACTORA.COM</li>\n				</ul>\n			</div>', 1, '2018-12-29 13:19:46'),
(4, 'Frequently Asked Questions', '<p>What we don&#39;t treat</p>\n\n<div style=\"margin-left:.25in;\">&bull;Our experienced and certified physicians are able to listen to regarding your conditions that are more urgent care related and heal many of the top health symptom for which you need to see a doctor, there are some conditions that we don&rsquo;t treat or can not heal with our being present in person in an office. Please see an in-person doctor or hospital if you experience any of the following:</div>\n\n<div style=\"margin-left:.75in;\">&bull;Fractures of bones</div>\n\n<div style=\"margin-left:.75in;\">&bull; Traumatic injury&nbsp; related to the body</div>\n\n<div style=\"margin-left:.75in;\">&bull; Chest pain and/or numbness</div>\n\n<div style=\"margin-left:.75in;\">&bull; Vomiting or coughing blood</div>\n\n<div style=\"margin-left:.75in;\">&bull; Lacerations</div>\n\n<div style=\"margin-left:.75in;\">&bull; Loss of consciousness</div>\n\n<div style=\"margin-left:.75in;\">&bull;&nbsp; Severe burns</div>\n\n<p>&nbsp;</p>\n\n<div style=\"margin-left:.25in;\">&bull;Also, our physicians are unable to write prescriptions for controlled substances such as codeine or oxycodone. You would need to a physician in person if you require medication classified as a controlled substance.</div>', 1, '2018-12-29 18:34:55'),
(5, 'About Us', '<p>Our experienced medical professionals put your healing needs first. We are proud to provide a high quality level of customer service, medical experience, and commitment to health and wellness to all our patients. Our goal is to make you feel better as quickly as possible</p>', 1, '2018-12-29 18:43:18'),
(6, 'Get in touch with us', '<p>Call today to schedule an appointment.<br />\nPlease contact us directly with any questions, comments, or scheduling inquiries you may have.</p>', 1, '2018-12-29 21:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `PharmId` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Address` varchar(500) NOT NULL,
  `PinCode` varchar(255) NOT NULL,
  `Telephone` varchar(10) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `License` varchar(255) NOT NULL,
  `Validity` date NOT NULL,
  `Status` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `CreateDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pharmacy`
--

INSERT INTO `pharmacy` (`PharmId`, `Name`, `Address`, `PinCode`, `Telephone`, `email`, `mobile`, `password`, `License`, `Validity`, `Status`, `is_active`, `CreateDate`) VALUES
(1, 'shaanz', 'Hyderabad', '5000047', '040-123456', 'sksafdar@gmail.com', '9492875321', '$2y$11$R..YTaJ48es.qu60l8lkQejZ3P67rEZtwrlZos8NpD7JrL4VzhJbe', 'L1234', '2020-06-20', 1, 1, '2019-12-04'),
(5, 'aditya', 'Hyderabad', '', '0401234567', 'msaiaditya29@gmail.com', '7386896084', '$2y$11$s.uEetpBpushjXhMmtT.IO4m6..RyzxDdPeRX3kuRjSB5gndTqnsi', 'a1234', '0000-00-00', 1, 1, '2019-12-05'),
(7, 'PharmTest', 'Hyderabad', '524305', '9492875321', 'prahm@actora.com', '9492875321', '$2y$11$9VPPF9ziyvv7qFuB5A15AugnrJXR/kKTzHNCQ8Z1H0moVruFdSr.S', '12346', '2020-04-23', 1, 1, '2020-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `PharmId` int(11) DEFAULT NULL,
  `LabId` int(11) DEFAULT NULL,
  `create_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `app_id`, `file`, `PharmId`, `LabId`, `create_date`) VALUES
(22, 9, 'pres_9jpg', NULL, NULL, '2020-04-20 09:40:32'),
(28, 10, 'pres_10.jpg', NULL, NULL, '2020-04-20 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_module_permission`
--

CREATE TABLE `role_module_permission` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `view_p` tinyint(1) NOT NULL DEFAULT '0',
  `add_p` tinyint(1) NOT NULL DEFAULT '0',
  `edit_p` tinyint(1) NOT NULL DEFAULT '0',
  `delete_p` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_module_permission`
--

INSERT INTO `role_module_permission` (`id`, `role_id`, `module_id`, `view_p`, `add_p`, `edit_p`, `delete_p`) VALUES
(1, 1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 1, 1),
(3, 1, 3, 1, 1, 1, 1),
(4, 1, 4, 1, 1, 1, 1),
(5, 1, 5, 1, 1, 1, 1),
(6, 1, 6, 1, 1, 1, 1),
(7, 1, 7, 1, 1, 1, 1),
(8, 1, 8, 1, 1, 1, 1),
(9, 1, 9, 1, 1, 1, 1),
(10, 5, 1, 0, 1, 1, 0),
(11, 5, 2, 0, 1, 1, 1),
(12, 5, 6, 0, 0, 0, 1),
(13, 1, 10, 1, 1, 1, 1),
(14, 1, 11, 1, 1, 1, 1),
(15, 1, 12, 1, 1, 1, 1),
(16, 1, 13, 1, 1, 1, 1),
(17, 1, 15, 1, 1, 1, 1),
(18, 1, 16, 1, 1, 1, 1),
(19, 1, 14, 1, 1, 1, 1),
(20, 2, 16, 0, 0, 0, 0),
(21, 1, 17, 1, 1, 1, 1),
(22, 3, 17, 0, 0, 0, 0),
(23, 3, 16, 1, 1, 0, 0),
(24, 3, 15, 0, 0, 0, 0),
(25, 3, 13, 0, 0, 0, 0),
(26, 3, 12, 0, 0, 0, 0),
(27, 3, 11, 0, 0, 0, 0),
(28, 3, 14, 0, 0, 0, 0),
(29, 3, 8, 1, 1, 1, 0),
(30, 1, 18, 1, 1, 1, 1),
(31, 2, 3, 1, 1, 1, 0),
(32, 2, 4, 1, 1, 1, 0),
(33, 2, 5, 1, 1, 1, 0),
(34, 2, 6, 1, 1, 1, 0),
(35, 2, 7, 1, 1, 1, 0),
(36, 2, 8, 1, 1, 1, 0),
(37, 2, 17, 1, 1, 1, 0),
(38, 1, 19, 1, 1, 1, 1),
(39, 1, 20, 1, 0, 0, 0),
(40, 1, 21, 1, 0, 0, 0),
(41, 1, 22, 0, 1, 1, 0),
(42, 1, 23, 1, 1, 1, 1),
(43, 1, 24, 1, 1, 1, 1),
(44, 1, 25, 1, 1, 1, 1),
(45, 1, 26, 1, 1, 1, 1),
(46, 1, 27, 1, 1, 1, 1),
(47, 1, 28, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `share_details`
--

CREATE TABLE `share_details` (
  `id` int(11) NOT NULL,
  `share_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `share_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('d','u') COLLATE utf8_unicode_ci NOT NULL,
  `create_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `share_health_info`
--

CREATE TABLE `share_health_info` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `share_health_info`
--

INSERT INTO `share_health_info` (`id`, `doc_id`, `user_id`) VALUES
(4, 11, 3),
(5, 13, 9),
(6, 11, 7);

-- --------------------------------------------------------

--
-- Table structure for table `site_info`
--

CREATE TABLE `site_info` (
  `id` int(11) NOT NULL,
  `variable_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value_text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` (`id`, `variable_text`, `value_text`) VALUES
(1, 'contact_no', ''),
(2, 'email1', 'remotehealth.ju@gmail.com'),
(3, 'email2', 'himadri.1111@gmail.com'),
(4, 'address1', '188, Raja S C Mallick Road'),
(5, 'address2', 'Kolkata'),
(6, 'latitude', ''),
(7, 'longitude', ''),
(8, 'instagram_link', 'http://instagram.com'),
(9, 'facebook_link', 'http://facebook.com'),
(10, 'twitter_link', 'http://twitter.com'),
(11, 'pinterest_link', 'http://pinterest.com'),
(12, 'dribble_link', 'http://dribble.com'),
(13, 'behance_link', 'http://behance.com'),
(14, 'linkedin_link', 'http://linkedin.com'),
(15, 'personal_health_question', '[\r\n	{\r\n		\"question\":\"Check the conditions that apply to you or to any members of your immediate relatives:\",\r\n		\"type\":\"checkbox\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"Asthma\",\r\n			\"Cancer\",\r\n			\"Cardiac disease\",\r\n			\"Diabetes\",\r\n			\"Hypertension\",\r\n			\"Psychiatric disorder\",\r\n			\"Epilepsy\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Check the symptoms that you have experienced in the PAST 6 WEEKS\",\r\n		\"type\":\"checkbox\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"Fever/Chills\",\r\n			\"Unexplained change in weight\",\r\n			\"Fatigue/Malaise/Generalized weakness\",\r\n			\"Headaches/Migraines\",\r\n			\"Dizziness\",\r\n			\"Sinus Pain/Pressure/Discharge\",\r\n			\"Excessive snoring\",\r\n			\"Wheezing/Chronic Cough\",\r\n			\"Shortness of breath\",\r\n			\"Swelling of hands/feet/ankles\",\r\n			\"Nausea/Vomiting\",\r\n			\"Abdominal pain\",\r\n			\"Heartburn\",\r\n			\"Constipation or diarrhea\",\r\n			\"Stiffness/Pain in joints/muscles\",\r\n			\"Joint swelling\",\r\n			\"Hot flashes\",\r\n			\"Difficulty urinating/Night-time urination\",\r\n			\"Urinary incontinence (leakage)\",\r\n			\"Sexual Difficulties/Painful intercourse\",\r\n			\"Rash\",\r\n			\"Anxiety/Panic Attacks\",\r\n			\"Concentration Difficulty\",\r\n			\"Feelings of Guilt\",\r\n			\"Insomnia/Problems with Sleep\",\r\n			\"Loss of energy\",\r\n			\"Thoughts of harming self or others\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Date of last menstrual period\",\r\n		\"type\":\"date\",\r\n		\"for\":[\"f\"],\r\n		\"options\":[\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Number of pregnancies\",\r\n		\"type\":\"number\",\r\n		\"for\":[\"f\"],\r\n		\"options\":[\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Number of live births\",\r\n		\"type\":\"number\",\r\n		\"for\":[\"f\"],\r\n		\"options\":[\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Are you taking any hormones or birth control?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"f\"],\r\n		\"options\":[\r\n			\"yes\",\r\n			\"No\",\r\n			\"N/A\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Do you have irregular or painful periods?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"f\"],\r\n		\"options\":[\r\n			\"yes\",\r\n			\"No\",\r\n			\"N/A\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Are you currently taking any medication?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"yes\",\r\n			\"No\",\"N/A\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"If so, please list:\",\r\n		\"type\":\"textbox\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Do you have any medication allergies?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"yes\",\r\n			\"No\",\r\n			\"Not Sure\",\r\n			\"N/A\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Do you use or do you have history of using tobacco?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"yes\",\r\n			\"No\",\r\n			\"N/A\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"Do you use or do you have history of using illegal drugs?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"yes\",\r\n			\"No\",\r\n			\"N/A\"\r\n		]\r\n	},\r\n	{\r\n		\"question\":\"How often do you consume alcohol?\",\r\n		\"type\":\"radio\",\r\n		\"for\":[\"m\",\"f\"],\r\n		\"options\":[\r\n			\"Daily\",\r\n			\"Weekly\",\r\n			\"Monthly\",\r\n			\"Occasionally\",\r\n			\"Never\"\r\n		]\r\n	}\r\n]\r\n'),
(16, 'apk', '');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `text1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link1_l` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link2_l` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `text1`, `text2`, `text3`, `text4`, `link1`, `link2`, `link1_l`, `link2_l`, `image`, `is_active`) VALUES
(2, 'Connecting patients, provides and payers', 'To bridge the gap in care', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada lorem maximus mauris scelerisque, at rutrum nulla dictum. Ut ac ligula sapien. Suspendisse cursus faucibus finibus.', '', 'Read More', 'make an appointment', '#', 'javascript:void(0);', 'home_slider_1.jpg', 1),
(3, '', '', '', '', '', '', '', '', '1546451778home_slider.jpg', 1),
(4, '', '', '', '', '', '', '', '', '1546451884home_slider_2.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `per_visit_change` int(11) NOT NULL,
  `provider_percentage` int(11) NOT NULL,
  `free_period_days` int(11) NOT NULL,
  `subscription_charge` int(11) NOT NULL,
  `subscription_charge_year` int(11) NOT NULL,
  `session_timing` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`id`, `title`, `per_visit_change`, `provider_percentage`, `free_period_days`, `subscription_charge`, `subscription_charge_year`, `session_timing`, `is_active`, `created_on`) VALUES
(1, 'Primary Care', 2000, 65, 45, 20, 200, 20, 1, '2019-01-26 05:11:46'),
(2, 'Pediatrician', 2000, 73, 45, 20, 200, 20, 1, '2019-01-26 05:32:32'),
(3, 'Gynecologist', 3000, 74, 45, 20, 200, 20, 1, '2019-01-26 05:32:52'),
(4, 'Dermatology', 2000, 73, 45, 20, 200, 20, 1, '2019-05-03 07:16:07'),
(5, 'Therapy', 200, 73, 45, 20, 200, 20, 1, '2019-05-03 07:16:46'),
(6, 'Dentist', 2000, 73, 45, 20, 200, 20, 1, '2019-05-03 07:17:24'),
(7, 'Orthopedic', 2000, 65, 45, 20, 200, 20, 1, '2019-01-26 05:11:46'),
(8, 'Pulmonologist', 2000, 65, 45, 20, 200, 20, 1, '2019-01-26 05:11:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `sex` enum('m','f','o') NOT NULL,
  `image` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `personal_health_details` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` datetime NOT NULL,
  `PDID` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `mobile`, `email_id`, `dob`, `sex`, `image`, `password`, `personal_health_details`, `is_active`, `create_date`, `PDID`) VALUES
(3, 'arup', '', '9466458722', '', '0000-00-00', '', '', '$2y$11$o/RdHEeidawzgPvaIHeBrOAlua.CJhkKKZ6CGqh23EKWG3rmW68sK', '{\"0\":[\"Cardiac disease\",\"Diabetes\",\"Hypertension\"],\"2\":[\"\"],\"3\":[\"\"],\"4\":[\"\"],\"8\":[\"\"],\"func\":[\"save_personal_health_data\"],\"PHPSESSID\":[\"b68ac1b4b8c6bb0fd29aa9191c299a9b\"],\"general_feel\":\"6\"}', 1, '2020-04-13 08:56:51', ''),
(11, 'Sujoy', 'Mistry', '9732443361', 'sujoy.mtech@gmail.com', '1983-04-26', 'm', '', '$2y$11$C.bHHqKjXybJz0Oa.YpZmusKaM/wSESYaD4aAYcfcnxFRdUpWi2N6', '{\"0\":[\"Asthma\"],\"1\":[\"Fever/Chills\",\"Headaches/Migraines\",\"Wheezing/Chronic Cough\",\"Heartburn\"],\"2\":[\"\"],\"3\":[\"\"],\"4\":[\"\"],\"7\":[\"No\"],\"8\":[\"\"],\"9\":[\"No\"],\"10\":[\"No\"],\"11\":[\"No\"],\"12\":[\"Never\"],\"func\":[\"save_personal_health_data\"],\"PHPSESSID\":[\"60e8149950e1ae2508a19f25966288ff\"],\"general_feel\":\"5\"}', 1, '2020-04-26 07:43:04', ''),
(10, 'Biman', 'Banerjee', '9874222228', '', '0000-00-00', 'm', '', '$2y$11$/ZCuEG3qcbnEGsLLIMGnm.eo4ET4ycXJOZ7Fozh8oCnawxnoIVfse', '', 1, '2020-04-22 09:04:44', ''),
(7, 'rituparna', '', '8888888888', '', '0000-00-00', 'f', '', '$2y$11$aF9XyjF.QI.dSu46hSKbZ.wPxlfiwP.k88OpDREdj8XN2.0ZFjG3K', '', 1, '2020-04-16 15:28:56', ''),
(9, 'Nandini', 'Mukherjee', '9903039536', '', '0000-00-00', 'f', '', '$2y$11$8uF45mhNUIWtvW2eAAAlyeSnhE5N0.eYnnSquzhV8.3nKf998m1MO', '{\"0\":[\"Asthma\"],\"1\":[\"Fever/Chills\"],\"2\":[\"\"],\"3\":[\"\"],\"4\":[\"\"],\"5\":[\"No\"],\"8\":[\"\"],\"9\":[\"No\"],\"10\":[\"No\"],\"11\":[\"No\"],\"12\":[\"Never\"],\"func\":[\"save_personal_health_data\"],\"PHPSESSID\":[\"9a401968321682298927b623a07d10ba\"],\"general_feel\":\"8\"}', 1, '2020-04-21 07:33:53', ''),
(12, 'Himanshu', 'Singhal', '9811166174', 'himanshusinghal21@gmail.com', '1981-10-20', 'm', '', '$2y$11$s5BnDPWD01MvLwqhA0XoB.CQZBG8KZXGDC8/fUNhZkOz0KfWa1uRK', '', 1, '2020-08-08 08:06:24', ''),
(13, 'rrrr', 'Ddddd', '8941988884', 'Sandeepsethi4444@gmail.com', '1970-07-04', 'm', '', '$2y$11$l4KmDlKbTpQTV6Ey5ExvbO8fcXNyOUJLES2Voi3n2ki/teCB8/Ku6', '', 1, '2020-08-09 13:26:34', ''),
(14, 'Poly', 'Sil Sen', '8697972022', 'poly.is.sil@gmail.com', '1977-09-13', 'f', '', '$2y$11$uRjNH1mPBqH3jxPlEVXA.OUzIC9vQN/yx4zofEREhfM7ZXPS1xPeu', '{\"1\":[\"Headaches/Migraines\",\"Shortness of breath\"],\"2\":[\"\"],\"3\":[\"0\"],\"4\":[\"2\"],\"6\":[\"No\"],\"7\":[\"yes\"],\"8\":[\"\"],\"9\":[\"No\"],\"10\":[\"No\"],\"11\":[\"No\"],\"12\":[\"Never\"],\"func\":[\"save_personal_health_data\"],\"general_feel\":\"6\"}', 1, '2020-12-11 15:47:31', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `block`
--
ALTER TABLE `block`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_appointment`
--
ALTER TABLE `doctor_appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_chat_appointments`
--
ALTER TABLE `doctor_chat_appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_chat_timing`
--
ALTER TABLE `doctor_chat_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_language_known`
--
ALTER TABLE `doctor_language_known`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_ph_cl_timing`
--
ALTER TABLE `doctor_ph_cl_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_video_timing`
--
ALTER TABLE `doctor_video_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_video_timing_slot`
--
ALTER TABLE `doctor_video_timing_slot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `imageupload`
--
ALTER TABLE `imageupload`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `initiate_chat`
--
ALTER TABLE `initiate_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`LabId`);

--
-- Indexes for table `mail_content`
--
ALTER TABLE `mail_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_subscription`
--
ALTER TABLE `message_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nurse_fees`
--
ALTER TABLE `nurse_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nurse_fees_structure`
--
ALTER TABLE `nurse_fees_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nurse_provided_services`
--
ALTER TABLE `nurse_provided_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nurse_service_selected`
--
ALTER TABLE `nurse_service_selected`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD PRIMARY KEY (`PharmId`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_module_permission`
--
ALTER TABLE `role_module_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `share_details`
--
ALTER TABLE `share_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `share_health_info`
--
ALTER TABLE `share_health_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_info`
--
ALTER TABLE `site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `block`
--
ALTER TABLE `block`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `doctor_appointment`
--
ALTER TABLE `doctor_appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `doctor_chat_appointments`
--
ALTER TABLE `doctor_chat_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_chat_timing`
--
ALTER TABLE `doctor_chat_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `doctor_language_known`
--
ALTER TABLE `doctor_language_known`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor_ph_cl_timing`
--
ALTER TABLE `doctor_ph_cl_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctor_video_timing`
--
ALTER TABLE `doctor_video_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctor_video_timing_slot`
--
ALTER TABLE `doctor_video_timing_slot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `imageupload`
--
ALTER TABLE `imageupload`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `initiate_chat`
--
ALTER TABLE `initiate_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `LabId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mail_content`
--
ALTER TABLE `mail_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message_subscription`
--
ALTER TABLE `message_subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `nurse_fees`
--
ALTER TABLE `nurse_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `nurse_fees_structure`
--
ALTER TABLE `nurse_fees_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nurse_provided_services`
--
ALTER TABLE `nurse_provided_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nurse_service_selected`
--
ALTER TABLE `nurse_service_selected`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `PharmId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_module_permission`
--
ALTER TABLE `role_module_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `share_details`
--
ALTER TABLE `share_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `share_health_info`
--
ALTER TABLE `share_health_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `site_info`
--
ALTER TABLE `site_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
