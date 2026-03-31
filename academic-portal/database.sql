-- Academic Portal Database Setup Script
-- Creating the database
CREATE DATABASE IF NOT EXISTS `academic_portal`;
USE `academic_portal`;

-- --------------------------------------------------------
-- Table structure for table `students`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `rollno` varchar(50) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `year` varchar(10) NOT NULL,
  `department` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `rollno` (`rollno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `events`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `description` text NOT NULL,
  `venue` varchar(255) NOT NULL,
  `event_time` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `seats` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `registrations`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `rollno` varchar(50) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `department` varchar(100) NOT NULL,
  `year` varchar(10) NOT NULL,
  `event` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample Data (Optional)
-- --------------------------------------------------------

-- Adding sample events
INSERT INTO `events` (`event_name`, `category`, `event_date`, `description`, `venue`, `event_time`, `image`, `seats`, `status`) VALUES
('Tech Symposium 2026', 'Tech', '2026-04-15', 'A national level technical symposium featuring paper presentations, coding contests, and more.', 'Seminar Hall A', '10:00 AM', 'tech_sympo.png', 100, 'Open'),
('Cultural Fest - Echoes', 'Cultural', '2026-05-20', 'Annual cultural festival with music, dance, and arts competition.', 'Main Auditorium', '09:00 AM', 'cultural_fest.png', 500, 'Open'),
('Workshop on AI/ML', 'Workshop', '2026-04-10', 'Hands-on workshop on Artificial Intelligence and Machine Learning basics.', 'CS Lab 1', '11:00 AM', 'ai_workshop.png', 50, 'Open');

-- Adding sample student (password is md5 of '1234')
INSERT INTO `students` (`name`, `rollno`, `email`, `mobile`, `year`, `department`, `password`) VALUES
('John Doe', '21CS001', 'john@example.com', '9876543210', '3', 'CSE', '81dc9bdb52d04dc20036dbd8313ed055');
