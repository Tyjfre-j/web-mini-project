-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 11:51 PM
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
-- Database: `db_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `laptop`
--

CREATE TABLE `laptop` (
  `id` int(10) NOT NULL,
  `laptop_name` varchar(50) NOT NULL,
  `laptop_small_description` text NOT NULL,
  `laptop_long_description` text NOT NULL,
  `laptop_price` text NOT NULL,	
  `laptop_img` varchar(500) NOT NULL,
  `laptop_category_name` varchar(50) NOT NULL,
  `laptop_quantity` int(10) DEFAULT 0,
  `laptop_status` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laptop`
--

INSERT INTO `laptop` (
  `id`, 
  `laptop_name`, 
  `laptop_small_description`, 
  `laptop_long_description`, 
  `laptop_price`, 
  `laptop_img`, 
  `laptop_category_name`, 
  `laptop_quantity`, 
  `laptop_status`
) VALUES
(1, 'Dell XPS 13', 'Compact and powerful ultrabook.', 'The Dell XPS 13 features a 13-inch InfinityEdge display, 11th Gen Intel Core processor, and long battery life, making it perfect for students and professionals.', '1200$', './images/productimg/laptop1.jpg', 'student', 10, b'1'),
(2, 'MacBook Pro', 'Professional laptop for business.', 'Apple MacBook Pro with M1 chip, Retina display, and all-day battery life. Ideal for business users and creators.', '1800$', './images/productimg/laptop2.jpg', 'business', 7, b'1'),
(3, 'HP Spectre x360', 'Convertible laptop for flexibility.', 'HP Spectre x360 offers a 2-in-1 design, touchscreen, and powerful performance for students and professionals.', '1400$', './images/productimg/laptop3.jpg', 'student', 5, b'1'),
(4, 'Acer Predator Helios', 'High-performance gaming laptop.', 'Acer Predator Helios is equipped with a high-refresh-rate display, powerful GPU, and advanced cooling for gaming enthusiasts.', '1600$', './images/productimg/laptop4.jpg', 'gaming', 3, b'1');



-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banner_id` int(11) NOT NULL,
  `banner_title` text NOT NULL,
  `banner_text` text  NOT NULL,
  `banner_image` varchar(50) NOT NULL,
  `banner_status` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`banner_id`,`banner_title`, `banner_text`, `banner_image`, `banner_status`) VALUES
(1, 'LIMITED DEALS','check out our latest deals', 'banner-1.jpg', 0x31),
(2, 'NEXT-GEN GAMING LAPTOPS', 'check out our collection of gaming laptops', 'banner-2.jpg', 0x31),
(3, 'GET YOUR CUSTOM PC', 'get professional assembly and 3-year warranty', 'banner-3.jpg', 0x31);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` binary(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `status`) VALUES
(1, 'business', b'1'),
(2, 'student', b'1'),
(3, 'gaming', b'1');


-- --------------------------------------------------------

-- Table structure for table `desktop`
--

CREATE TABLE `desktop` (
  `id` int(10) NOT NULL,
  `desktop_name` varchar(50) NOT NULL,
  `desktop_small_description` text NOT NULL,
  `desktop_long_description` text NOT NULL,
  `desktop_price` text NOT NULL,
  `table_img` varchar(500) NOT NULL,
  `desktop_category_name` varchar(50) NOT NULL,
  `desktop_quantity` int(10) DEFAULT 0,
  `desktop_status` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `desktop`
--
INSERT INTO `desktop` (`id`, `desktop_name`, `desktop_small_description`, `desktop_long_description`, `desktop_price`, `table_img`, `desktop_category_name`, `desktop_quantity`, `desktop_status`) VALUES
(1, 'Dell OptiPlex 3080', 'Reliable business desktop.', 'Dell OptiPlex 3080 offers robust performance and security for business environments.', '900$', './images/productimg/desktop1.jpg', 'business', 8, b'1'),
(2, 'HP Pavilion', 'Versatile desktop for students.', 'HP Pavilion desktop is perfect for students, offering a balance of performance and value.', '700$', './images/productimg/desktop2.jpg', 'student', 5, b'1'),
(3, 'Lenovo ThinkCentre', 'Powerful gaming desktop.', 'Lenovo ThinkCentre is equipped with high-end components for gaming and multitasking.', '1100$', './images/productimg/desktop3.jpg', 'gaming', 12, b'1'),
(4, 'Acer Aspire TC', 'Affordable student desktop.', 'Acer Aspire TC is a budget-friendly desktop for everyday student tasks.', '650$', './images/productimg/desktop4.jpg', 'student', 7, b'0');


-- --------------------------------------------------------

--
-- Table structure for table `customeBuild`
--

CREATE TABLE `customeBuild` (
  `id` int(10) NOT NULL,
  `customeBuild_name` varchar(50) NOT NULL,
  `customeBuild_small_description` text NOT NULL,
  `customeBuild_long_description` text NOT NULL,
  `customeBuild_price` text NOT NULL,
  `table_img` varchar(500) NOT NULL,
  `customeBuild_category_name` varchar(50) NOT NULL,
  `customeBuild_quantity` int(10) DEFAULT 0,
  `customeBuild_status` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customeBuild`
INSERT INTO `customeBuild` (
  `id`, 
  `customeBuild_name`, 
  `customeBuild_small_description`, 
  `customeBuild_long_description`, 
  `customeBuild_price`, 
  `table_img`, 
  `customeBuild_category_name`, 
  `customeBuild_quantity`, 
  `customeBuild_status`
) VALUES
(1, 'Gaming Beast', 'Ultimate gaming custom build.', 'Custom built for gaming with top-tier GPU, CPU, and cooling.', '2000$', './images/productimg/custom1.jpg', 'gaming', 2, b'1'),
(2, 'Office Pro', 'Business custom build.', 'Optimized for business tasks with reliable components and security.', '1500$', './images/productimg/custom2.jpg', 'business', 5, b'1'),
(3, 'Budget Build', 'Affordable student custom build.', 'Entry-level custom build for students and light users.', '800$', './images/productimg/custom3.jpg', 'student', 10, b'0');


-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(100) NOT NULL,
  `customer_fname` varchar(50) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_pwd` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_role` varchar(50) NOT NULL DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_fname`, `customer_email`, `customer_pwd`, `customer_phone`, `customer_address`, `customer_role`) VALUES
(9, 'abouda', 'aboudaha@gmail.com', '123456789', '0550301515', 'alger', 'admin'),
(24, 'mouad', 'mouadmouad@gamil.com', '123456789', '0550304416', 'alger', 'normal'),
(25, 'tamim', 'tamimtamim@gmail.com', '123456789', '0550304415', 'alger', 'normal');

-- --------------------------------------------------------

-- Table structure for table `display`
--

CREATE TABLE `display` (
  `id` int(10) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `display_small_description` text NOT NULL,
  `display_long_description` text NOT NULL,
  `display_price` text NOT NULL,
  `table_img` varchar(500) NOT NULL,
  `display_category_name` varchar(50) NOT NULL,
  `display_quantity` int(10) DEFAULT 0,
  `display_status` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `display`
--

INSERT INTO `display` (`id`, `display_name`, `display_small_description`, `display_long_description`, `display_price`, `table_img`, `display_category_name`, `display_quantity`, `display_status`) VALUES
(1, 'Dell UltraSharp', 'High-resolution business monitor.', 'Dell UltraSharp offers stunning visuals and color accuracy for business use.', '400$', './images/productimg/display1.jpg', 'business', 10, b'1'),
(2, 'Acer Predator', 'Gaming monitor with high refresh rate.', 'Acer Predator delivers smooth gameplay with a high refresh rate and low response time.', '500$', './images/productimg/display2.jpg', 'gaming', 5, b'1'),
(3, 'HP EliteDisplay', 'Student-friendly display.', 'HP EliteDisplay is affordable and reliable, perfect for students.', '250$', './images/productimg/display3.jpg', 'student', 7, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `review` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `review`) VALUES
(1, 'Iqso Fhd', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem ad\r\n            fugiat, itaque dolore culpa ipsa fuga, illum, maxime exercitationem\r\n            commodi nihil nobis nulla similique quibusdam sed expedita provident'),
(2, 'IFAD', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem ad\r\n            fugiat, itaque dolore culpa ipsa fuga, illum, maxime exercitationem\r\n            commodi nihil nobis nulla similique quibusdam sed expedita provident'),
(3, 'Eva Silk', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem ad\r\n            fugiat, itaque dolore culpa ipsa fuga, illum, maxime exercitationem\r\n            commodi nihil nobis nulla similique quibusdam sed expedita provident');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `website_name` varchar(60) NOT NULL,
  `website_logo` varchar(50) NOT NULL,
  `website_footer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`website_name`, `website_logo`, `website_footer`) VALUES
('HCA E-Commerce', 'HCA-E-COMMERCE.png', 'HCA E-Commerce');