-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 08:42 AM
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
-- Database: `petpuja_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_master`
--

CREATE TABLE `admin_master` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_master`
--

CREATE TABLE `cart_master` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `cart_createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_master`
--

CREATE TABLE `category_master` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_img` varchar(255) NOT NULL,
  `isDelete` int(11) NOT NULL COMMENT '// 0 for delete \r\n// 1 for active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_master`
--

INSERT INTO `category_master` (`category_id`, `category_name`, `category_img`, `isDelete`) VALUES
(1, 'Pizza', 'pizza.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `favorite_master`
--

CREATE TABLE `favorite_master` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `favorite_createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorite_master`
--

INSERT INTO `favorite_master` (`favorite_id`, `user_id`, `menu_id`, `favorite_createdAt`) VALUES
(2, 1, 1, '2025-03-05 06:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `menu_master`
--

CREATE TABLE `menu_master` (
  `menu_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_description` text DEFAULT NULL,
  `menu_price` decimal(10,0) NOT NULL,
  `menu_img` varchar(255) NOT NULL,
  `menu_status` int(11) NOT NULL DEFAULT 1 COMMENT '// 0 for unavalible //1 for avalible',
  `manu_type` int(11) NOT NULL COMMENT '// 0 for veg\r\n//1 for non-veg',
  `isDelete` int(11) NOT NULL DEFAULT 1 COMMENT '// 0 for delete\r\n// 1 for active',
  `menu_createdDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `menu_updatedDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_master`
--

INSERT INTO `menu_master` (`menu_id`, `restaurant_id`, `category_id`, `menu_name`, `menu_description`, `menu_price`, `menu_img`, `menu_status`, `manu_type`, `isDelete`, `menu_createdDate`, `menu_updatedDate`) VALUES
(1, 1, 1, 'margherita pizza', 'Margherita pizza is a classic Italian pizza made with fresh tomatoes, mozzarella cheese, basil, and olive oil', 299, 'pizza.jpg', 1, 0, 1, '2025-03-05 06:30:21', '2025-03-05 06:30:21'),
(2, 1, 1, 'New York-style', 'New York-style pizza is known for its large size, thin crust, and crispy edges', 399, 'pizza.jpg', 1, 1, 1, '2025-03-06 05:50:06', '2025-03-06 05:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `order_item_master`
--

CREATE TABLE `order_item_master` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_item_price` decimal(10,0) NOT NULL,
  `order_item_createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_item_master`
--

INSERT INTO `order_item_master` (`order_item_id`, `order_id`, `menu_id`, `quantity`, `order_item_price`, `order_item_createdAt`) VALUES
(1, 1, 1, 3, 897, '2025-03-06 00:30:28'),
(2, 2, 1, 5, 1495, '2025-03-06 00:36:18'),
(3, 3, 2, 5, 1995, '2025-03-06 01:21:31'),
(4, 3, 2, 5, 1995, '2025-03-06 01:21:31'),
(5, 4, 1, 3, 897, '2025-03-06 01:23:29'),
(6, 4, 2, 5, 1995, '2025-03-06 01:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_master`
--

CREATE TABLE `order_master` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `total_amount` decimal(10,0) NOT NULL,
  `order_status` int(11) NOT NULL DEFAULT 0 COMMENT '// 0 for pending\r\n// 1 for accepted\r\n// 2 for delevered\r\n// 3 for cancelled',
  `order_createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_updatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_master`
--

INSERT INTO `order_master` (`order_id`, `user_id`, `restaurant_id`, `total_amount`, `order_status`, `order_createdAt`, `order_updatedAt`) VALUES
(1, 1, 1, 897, 3, '2025-03-06 00:30:28', '2025-03-06 00:30:28'),
(2, 2, 1, 1495, 0, '2025-03-06 00:36:18', '2025-03-06 00:36:18'),
(3, 2, 1, 3990, 0, '2025-03-06 01:21:31', '2025-03-06 01:21:31'),
(4, 2, 1, 2892, 0, '2025-03-06 01:23:29', '2025-03-06 01:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_master`
--

CREATE TABLE `restaurant_master` (
  `restaurant_id` int(11) NOT NULL,
  `restaurant_name` varchar(255) NOT NULL,
  `restaurant_owner_name` varchar(255) NOT NULL,
  `restaurant_description` text NOT NULL,
  `restaurant_address` text NOT NULL,
  `restaurant_phone` bigint(20) NOT NULL,
  `restaurant_img` varchar(255) NOT NULL,
  `restaurant_status` int(11) NOT NULL DEFAULT 1 COMMENT '// 0 for close //1 for open',
  `isDelete` int(11) NOT NULL DEFAULT 1 COMMENT '// 0 for delete \r\n// 1 for active',
  `restaurant_createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `restaurant_updateAt` timestamp NULL DEFAULT current_timestamp(),
  `restaurant_request_status` int(11) NOT NULL DEFAULT 0 COMMENT '// 0 for pending\r\n// 1 for approved\r\n// 2 for rejected'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant_master`
--

INSERT INTO `restaurant_master` (`restaurant_id`, `restaurant_name`, `restaurant_owner_name`, `restaurant_description`, `restaurant_address`, `restaurant_phone`, `restaurant_img`, `restaurant_status`, `isDelete`, `restaurant_createdAt`, `restaurant_updateAt`, `restaurant_request_status`) VALUES
(1, 'shreeji', 'malhar thakar', 'good food good life', 'ahmedabad', 9874584785, 'shreeji.jpg', 1, 1, '2025-03-05 06:28:33', '2025-03-05 06:28:19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `review_master`
--

CREATE TABLE `review_master` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_comments` int(11) NOT NULL,
  `review_createdAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE `user_master` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` bigint(20) NOT NULL,
  `user_gender` int(11) DEFAULT NULL COMMENT '//1 for male\r\n//2 for female\r\n// 3 for other',
  `user_address` text DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_img` varchar(255) DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 1 COMMENT '// 0 for delete 1 for active',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_master`
--

INSERT INTO `user_master` (`user_id`, `user_name`, `user_email`, `user_phone`, `user_gender`, `user_address`, `user_password`, `user_img`, `is_delete`, `create_date`, `updated_date`) VALUES
(1, 'Darshan Vasoya', 'dsvasoya2002@gmail.com', 9925177657, 1, NULL, '$2y$10$rKIiDQ.f5o4rWl2oDwaPP.xjY0vmayzP0byENZyM/OCdK/n4Q8Qk.', 'user_1_game.jpg', 1, '2025-03-04 04:32:32', '2025-03-05 12:20:29'),
(2, 'manan', 'goldenbloodgamerno.1@gmail.com', 7862856204, NULL, NULL, '$2y$10$RVPOLxGM6VgdxGN7S7Lh8OAHg/rcT7w2k5xwMdOAax9p5okjwRrWi', NULL, 1, '2025-03-04 05:18:40', '0000-00-00 00:00:00'),
(3, 'raj', 'raj@gmail.com', 9898989898, NULL, NULL, '$2y$10$ote6CG28kZsEErdcbziBNek0HR8DFAJj2HSFyFqgw.1z4eJlDxx7C', NULL, 1, '2025-03-04 07:22:40', '0000-00-00 00:00:00'),
(4, 'kajal', 'k@gmail.com', 7898073810, NULL, NULL, '$2y$10$bzvL44uD3/Avt55RNOZ8uuKSFSXCEyVDzROTUWXqPSspdG6OTuDby', NULL, 1, '2025-03-04 07:24:38', '0000-00-00 00:00:00'),
(5, 'Vinay', 'vinay@gmail.com', 9303827687, NULL, NULL, '$2y$10$NRUMXJHFi3AVJs1XGRjfMuEfiN0qNvmQjAzSyvbBCIzJzG7zzH4gq', NULL, 1, '2025-03-05 00:04:23', '0000-00-00 00:00:00'),
(6, 'Darshan', 'd@gmail.com', 7736299918, 1, 'adsfsdffdsfdbf', '$2y$10$kGS9SUp86Ogxu9KMDCcz3ufzRtWeOdE5LHb40r0EXI41J1xiIvJqa', 'user_6_Photo.jpeg', 1, '2025-03-05 05:06:43', '2025-03-06 05:10:32'),
(7, 'vishal', 'vishal1@gmail.com', 9251456318, NULL, NULL, '$2y$10$a7V1psH6DOfNWh/C5MB//ujbe5L0luy7DcIv9Wd0UK0cZTDDwlRPu', NULL, 1, '2025-03-06 00:10:52', '0000-00-00 00:00:00'),
(8, 'team', 'team@gmail.com', 1472580369, NULL, NULL, '$2y$10$P9CQkrUf03mAG21Zp3IAx.DC21xXbfk1yuTChL.9.Jw9DJZGMRXhW', NULL, 1, '2025-03-06 00:16:06', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_master`
--
ALTER TABLE `cart_master`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `category_master`
--
ALTER TABLE `category_master`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `favorite_master`
--
ALTER TABLE `favorite_master`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `menu_master`
--
ALTER TABLE `menu_master`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `order_item_master`
--
ALTER TABLE `order_item_master`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `order_master`
--
ALTER TABLE `order_master`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `restaurant_master`
--
ALTER TABLE `restaurant_master`
  ADD PRIMARY KEY (`restaurant_id`),
  ADD UNIQUE KEY `restaurant_phone` (`restaurant_phone`);

--
-- Indexes for table `review_master`
--
ALTER TABLE `review_master`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `user_master`
--
ALTER TABLE `user_master`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_phone` (`user_phone`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_master`
--
ALTER TABLE `cart_master`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category_master`
--
ALTER TABLE `category_master`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `favorite_master`
--
ALTER TABLE `favorite_master`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_master`
--
ALTER TABLE `menu_master`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_item_master`
--
ALTER TABLE `order_item_master`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_master`
--
ALTER TABLE `order_master`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `restaurant_master`
--
ALTER TABLE `restaurant_master`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review_master`
--
ALTER TABLE `review_master`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_master`
--
ALTER TABLE `user_master`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_master`
--
ALTER TABLE `cart_master`
  ADD CONSTRAINT `menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menu_master` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite_master`
--
ALTER TABLE `favorite_master`
  ADD CONSTRAINT `favorite_master_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorite_master_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu_master` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_master`
--
ALTER TABLE `menu_master`
  ADD CONSTRAINT `menu_master_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_master` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `restaurant_id` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant_master` (`restaurant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_item_master`
--
ALTER TABLE `order_item_master`
  ADD CONSTRAINT `order_item_master_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_master_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu_master` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_master`
--
ALTER TABLE `order_master`
  ADD CONSTRAINT `order_master_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_master_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant_master` (`restaurant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_master`
--
ALTER TABLE `review_master`
  ADD CONSTRAINT `review_master_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_master_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant_master` (`restaurant_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
