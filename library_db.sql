-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 04:16 AM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `quantity`) VALUES
(1, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 6),
(2, 'The Great Gatsby', 'F. Scott Fitzgerald', 7),
(3, 'Introduction to Programming', 'John Smith', 10),
(4, 'Basic Mathematics', 'James Clark', 5),
(6, 'Database Management Systems', 'Thomas Connolly', 3),
(7, 'Computer Networking', 'Andrew Tanenbaum', 5),
(8, 'Operating System Concepts', 'Abraham Silberschatz', 1),
(9, 'frozen40', 'whilcapretty', 17);

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `borrow_date` date NOT NULL DEFAULT curdate(),
  `return_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'borrowed',
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `username`, `student_name`, `book_title`, `author`, `borrow_date`, `return_date`, `status`, `due_date`) VALUES
(3, 'VENI', '', 'Basic Mathematics', 'James Clark', '2026-04-08', NULL, 'returned', NULL),
(4, 'grace', '', 'Introduction to Programming', 'John Smith', '2026-04-13', NULL, 'borrowed', NULL),
(5, 'grace', '', 'World History', NULL, '2026-04-13', NULL, 'borrowed', NULL),
(6, 'royjohn ', '', 'The Great Gatsby', NULL, '2026-04-13', NULL, 'borrowed', NULL),
(7, 'criza', '', 'frozen', NULL, '2026-04-13', NULL, 'borrowed', NULL),
(8, 'criza', '', 'Introduction to Programming', NULL, '2026-04-13', NULL, 'borrowed', NULL),
(9, 'criza', '', 'The Great Gatsby', NULL, '2026-04-13', NULL, 'borrowed', NULL),
(10, 'criza', '', 'Harry Potter and the Sorcerer\'s Stone', NULL, '2026-04-13', NULL, 'borrowed', NULL),
(11, 'VENI', '', 'frozen', NULL, '2026-04-13', NULL, 'returned', NULL),
(12, 'VENI', '', 'Computer Networking', NULL, '2026-04-13', NULL, 'returned', NULL),
(13, 'VENI', '', 'Introduction to Programming', NULL, '2026-04-13', NULL, 'borrowed', '2026-04-20'),
(14, 'VENI', '', 'Basic Mathematics', NULL, '2026-04-13', NULL, 'borrowed', '2026-04-20'),
(15, 'russel', '', 'frozen', NULL, '2026-04-13', NULL, 'returned', '2026-04-20'),
(16, 'russel', '', 'frozen40', NULL, '2026-04-13', NULL, 'borrowed', '2026-04-20'),
(17, 'VENI', '', 'frozen40', NULL, '2026-04-22', NULL, 'borrowed', '2026-04-29'),
(18, 'royjohn ', '', 'frozen40', NULL, '2026-04-22', NULL, 'borrowed', '2026-04-29'),
(19, 'VENI', '', 'The Great Gatsby', NULL, '2026-04-22', NULL, 'pending', '2026-04-29'),
(20, 'whilca', '', 'Computer Networking', NULL, '2026-04-27', NULL, 'rejected', '2026-05-04'),
(21, 'whilca', '', 'Introduction to Programming', NULL, '2026-04-27', NULL, 'pending', '2026-05-04');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_books`
--

CREATE TABLE `borrow_books` (
  `borrow_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_records1`
--

CREATE TABLE `borrow_records1` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `borrow_date` datetime DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_records1`
--

INSERT INTO `borrow_records1` (`id`, `student_id`, `student_name`, `book_id`, `book_title`, `borrow_date`, `return_date`, `username`, `status`) VALUES
(1, 6, '', 1, '', '2026-04-01 10:00:00', NULL, NULL, 'borrowed'),
(2, 7, '', 2, '', '2026-04-02 11:30:00', NULL, NULL, 'borrowed'),
(3, 14, '', 3, '', '2026-04-03 09:15:00', NULL, NULL, 'borrowed'),
(4, 17, '', 4, '', '2026-04-04 14:45:00', NULL, NULL, 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_summary2`
--

CREATE TABLE `borrow_summary2` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `borrow_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_summary2`
--

INSERT INTO `borrow_summary2` (`id`, `student_name`, `book_title`, `status`, `borrow_date`, `return_date`) VALUES
(1, 'royjohn ', 'Harry Potter and the Sorcerer\'s Stone', 'borrowed', '2026-04-01 10:00:00', NULL),
(2, 'VENI', 'The Great Gatsby', 'borrowed', '2026-04-02 11:30:00', NULL),
(3, 'grace', 'Introduction to Programming', 'borrowed', '2026-04-03 09:15:00', NULL),
(4, 'sharmine', 'Basic Mathematics', 'borrowed', '2026-04-04 14:45:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `returned_books`
--

CREATE TABLE `returned_books` (
  `id` int(11) NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `book_title` varchar(150) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `borrow_date` datetime DEFAULT NULL,
  `returned_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returned_books`
--

INSERT INTO `returned_books` (`id`, `borrow_id`, `username`, `book_title`, `author`, `borrow_date`, `returned_date`, `status`) VALUES
(1, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:52:04', 'returned'),
(2, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:51:12', 'returned'),
(3, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:24:45', 'borrowed'),
(4, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:52:02', 'returned'),
(5, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:53:00', 'returned'),
(6, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:27:57', 'borrowed'),
(7, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 17:28:33', 'returned'),
(8, 1, 'grace', 'The Great Gatsby', '', '2026-04-08 00:00:00', '2026-04-13 11:27:58', 'borrowed'),
(9, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:38:54', 'borrowed'),
(10, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:42:15', 'borrowed'),
(11, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:43:57', 'borrowed'),
(12, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:46:43', 'borrowed'),
(13, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:48:17', 'borrowed'),
(14, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:48:22', 'borrowed'),
(15, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:49:54', 'borrowed'),
(16, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:24', 'borrowed'),
(17, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:24', 'borrowed'),
(18, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:25', 'borrowed'),
(19, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:25', 'borrowed'),
(20, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:25', 'borrowed'),
(21, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:26', 'borrowed'),
(22, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:26', 'borrowed'),
(23, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:26', 'borrowed'),
(24, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:26', 'borrowed'),
(25, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:50:26', 'borrowed'),
(26, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:51:08', 'borrowed'),
(27, NULL, NULL, NULL, 'J.K. Rowling', '2026-04-08 00:00:00', '2026-04-13 11:51:12', 'borrowed'),
(28, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:51:17', 'borrowed'),
(29, NULL, NULL, NULL, 'John Smith', '2026-04-13 00:00:00', '2026-04-13 11:51:20', 'borrowed'),
(30, NULL, NULL, NULL, 'John Smith', '2026-04-13 00:00:00', '2026-04-13 11:51:23', 'borrowed'),
(31, NULL, NULL, NULL, 'John Smith', '2026-04-13 00:00:00', '2026-04-13 11:51:23', 'borrowed'),
(32, NULL, NULL, NULL, 'John Smith', '2026-04-13 00:00:00', '2026-04-13 11:51:24', 'borrowed'),
(33, NULL, NULL, NULL, 'John Smith', '2026-04-13 00:00:00', '2026-04-13 11:51:57', 'borrowed'),
(34, NULL, NULL, NULL, 'John Smith', '2026-04-13 00:00:00', '2026-04-13 11:52:02', 'borrowed'),
(35, NULL, NULL, NULL, 'F. Scott Fitzgerald', '2026-04-08 00:00:00', '2026-04-13 11:52:04', 'borrowed'),
(36, NULL, NULL, NULL, NULL, '2026-04-13 00:00:00', '2026-04-13 11:53:00', 'borrowed'),
(37, NULL, NULL, NULL, NULL, '2026-04-13 00:00:00', '2026-04-13 17:28:32', 'borrowed'),
(38, NULL, NULL, 'Basic Mathematics', 'James Clark', '2026-04-08 00:00:00', '2026-04-13 17:42:17', 'borrowed'),
(39, NULL, NULL, 'frozen', NULL, '2026-04-13 00:00:00', '2026-04-13 18:30:00', 'borrowed'),
(40, NULL, NULL, 'Computer Networking', NULL, '2026-04-13 00:00:00', '2026-04-13 18:36:54', 'borrowed'),
(41, NULL, NULL, 'Computer Networking', NULL, '2026-04-13 00:00:00', '2026-04-13 18:36:57', 'borrowed'),
(42, NULL, NULL, 'frozen', NULL, '2026-04-13 00:00:00', '2026-04-13 18:59:08', 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `gender` enum('male','female','other') DEFAULT NULL,
  `roles` varchar(50) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `gender`, `roles`, `profile_pic`) VALUES
(1, 'admin', '', '12345', 'admin', NULL, '', 'default.png'),
(6, 'royjohn ', 'royjhon@gmail.com', '12345', 'student', 'male', '', 'default.png'),
(7, 'VENI', 'VENI@GMAIL.COM', '12345', 'student', 'other', '', 'default.png'),
(14, 'grace', 'grace@gmail.com', '12345', 'student', 'female', '', 'default.png'),
(15, 'meawhilca', 'meawhilca@gmail.com', '123456', 'librarian', NULL, '', 'default.png'),
(17, 'sharmine', 'maine@gmail.com', '12345', 'student', 'female', '', 'default.png'),
(24, 'criza', 'criza@gmail.com', '12345', 'student', 'female', '', 'default.png'),
(25, 'russel', 'angelshi@gmail.com', '12345', 'student', 'male', '', 'default.png'),
(26, 'whilca', 'whilca@gmail.com', '12345', 'student', 'female', '', 'default.png'),
(27, 'dacsil', 'johnjosephgudelosao16@gmail.com', '12345', 'student', NULL, '', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow_books`
--
ALTER TABLE `borrow_books`
  ADD PRIMARY KEY (`borrow_id`);

--
-- Indexes for table `borrow_records1`
--
ALTER TABLE `borrow_records1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `borrow_summary2`
--
ALTER TABLE `borrow_summary2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returned_books`
--
ALTER TABLE `returned_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `borrow_books`
--
ALTER TABLE `borrow_books`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_records1`
--
ALTER TABLE `borrow_records1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrow_summary2`
--
ALTER TABLE `borrow_summary2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `returned_books`
--
ALTER TABLE `returned_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `borrow_records1`
--
ALTER TABLE `borrow_records1`
  ADD CONSTRAINT `borrow_records1_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_records1_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
