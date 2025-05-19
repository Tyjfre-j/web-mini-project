SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_COLLATION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `site_database`
--

-- --------------------------------------------------------------------------------------------------------------
-- NOTE: if u want to rerun the script just uncomment the lines below (it will all tables data) :)              |
-- --------------------------------------------------------------------------------------------------------------

/*
DROP TABLE IF EXISTS `laptop`;
DROP TABLE IF EXISTS `desktop`;
DROP TABLE IF EXISTS `custombuild`;
DROP TABLE IF EXISTS `cpu`;
DROP TABLE IF EXISTS `gpu`;
DROP TABLE IF EXISTS `keyboard`;
DROP TABLE IF EXISTS `displayscreen`;
DROP TABLE IF EXISTS `banner`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `category`;
*/

-- --------------------------------------------------------------------------------------------------------------
-- Tables Structure                                                                                             |
-- --------------------------------------------------------------------------------------------------------------

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Category`                                                                               |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `category` (
`category_id` INT AUTO_INCREMENT PRIMARY KEY,
`category_name` VARCHAR(50) NOT NULL UNIQUE,
`category_status` BOOLEAN NOT NULL DEFAULT TRUE,
`category_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`category_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Customer`                                                                               |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `customer` (
`customer_id` INT AUTO_INCREMENT PRIMARY KEY,
`customer_fname` VARCHAR(50) NOT NULL,
`customer_email` VARCHAR(100) NOT NULL UNIQUE,
`customer_password` VARCHAR(100) NOT NULL,
`customer_phone` VARCHAR(15) NOT NULL,
`customer_address` TEXT NOT NULL,
`customer_role` VARCHAR(50) NOT NULL DEFAULT 'normal',
`customer_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`customer_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Laptop`                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `laptop` (
`laptop_id` INT AUTO_INCREMENT PRIMARY KEY,
`laptop_name` VARCHAR(50) NOT NULL,
`laptop_small_description` TEXT NOT NULL,
`laptop_long_description` TEXT NOT NULL,
`laptop_price` DECIMAL(8,2) NOT NULL,
`laptop_image_path` TEXT NOT NULL,
`laptop_category_id` INT NOT NULL,
`laptop_quantity` INT NOT NULL DEFAULT 0,
`laptop_status` BOOLEAN NOT NULL DEFAULT FALSE,
`laptop_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`laptop_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`laptop_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Desktop`                                                                                |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `desktop` (
`desktop_id` INT AUTO_INCREMENT PRIMARY KEY,
`desktop_name` VARCHAR(50) NOT NULL,
`desktop_small_description` TEXT NOT NULL,
`desktop_long_description` TEXT NOT NULL,
`desktop_price` DECIMAL(8,2) NOT NULL,
`desktop_image_path` TEXT NOT NULL,
`desktop_category_id` INT NOT NULL,
`desktop_quantity` INT NOT NULL DEFAULT 0,
`desktop_status` BOOLEAN NOT NULL DEFAULT FALSE,
`desktop_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`desktop_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`desktop_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `CustomBuild`                                                                            |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `custombuild` (
`custombuild_id` INT AUTO_INCREMENT PRIMARY KEY,
`custombuild_name` VARCHAR(50) NOT NULL,
`custombuild_small_description` TEXT NOT NULL,
`custombuild_long_description` TEXT NOT NULL,
`custombuild_price` DECIMAL(8,2) NOT NULL,
`custombuild_image_path` TEXT NOT NULL,
`custombuild_category_id` INT NOT NULL,
`custombuild_quantity` INT NOT NULL DEFAULT 0,
`custombuild_status` BOOLEAN NOT NULL DEFAULT FALSE,
`custombuild_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`custombuild_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`custombuild_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `CPU`                                                                                    |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `cpu` (
`cpu_id` INT AUTO_INCREMENT PRIMARY KEY,
`cpu_name` VARCHAR(100) NOT NULL,
`cpu_small_description` TEXT NOT NULL,
`cpu_long_description` TEXT NOT NULL,
`cpu_price` DECIMAL(8,2) NOT NULL,
`cpu_image_path` TEXT NOT NULL,
`cpu_category_id` INT NOT NULL,
`cpu_quantity` INT NOT NULL DEFAULT 0,
`cpu_status` BOOLEAN NOT NULL DEFAULT FALSE,
`cpu_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`cpu_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`cpu_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `GPU`                                                                                    |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `gpu` (
`gpu_id` INT AUTO_INCREMENT PRIMARY KEY,
`gpu_name` VARCHAR(100) NOT NULL,
`gpu_small_description` TEXT NOT NULL,
`gpu_long_description` TEXT NOT NULL,
`gpu_price` DECIMAL(8,2) NOT NULL,
`gpu_image_path` TEXT NOT NULL,
`gpu_category_id` INT NOT NULL,
`gpu_quantity` INT NOT NULL DEFAULT 0,
`gpu_status` BOOLEAN NOT NULL DEFAULT FALSE,
`gpu_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`gpu_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`gpu_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Keyboard`                                                                               |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `keyboard` (
`keyboard_id` INT AUTO_INCREMENT PRIMARY KEY,
`keyboard_name` VARCHAR(100) NOT NULL,
`keyboard_small_description` TEXT NOT NULL,
`keyboard_long_description` TEXT NOT NULL,
`keyboard_price` DECIMAL(8,2) NOT NULL,
`keyboard_image_path` TEXT NOT NULL,
`keyboard_category_id` INT NOT NULL,
`keyboard_quantity` INT NOT NULL DEFAULT 0,
`keyboard_status` BOOLEAN NOT NULL DEFAULT FALSE,
`keyboard_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`keyboard_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`keyboard_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `DisplayScreen`                                                                          |
-- --------------------------------------------------------------------------------------------------------------
CREATE TABLE `displayscreen` (
`displayscreen_id` INT AUTO_INCREMENT PRIMARY KEY,
`displayscreen_name` VARCHAR(50) NOT NULL,
`displayscreen_small_description` TEXT NOT NULL,
`displayscreen_long_description` TEXT NOT NULL,
`displayscreen_price` DECIMAL(8,2) NOT NULL,
`displayscreen_image_path` TEXT NOT NULL,
`displayscreen_category_id` INT NOT NULL,
`displayscreen_quantity` INT NOT NULL DEFAULT 0,
`displayscreen_status` BOOLEAN NOT NULL DEFAULT FALSE,
`displayscreen_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`displayscreen_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`displayscreen_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Banner`                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `banner` (
`banner_id` INT AUTO_INCREMENT PRIMARY KEY,
`banner_title` TEXT NOT NULL,
`banner_text` TEXT NOT NULL,
`banner_image_path` TEXT NOT NULL,
`banner_status` BOOLEAN NOT NULL DEFAULT FALSE,
`banner_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`banner_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -------------------------------------------------------------------
-- Data insertion for tables
-- -------------------------------------------------------------------

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Customer` data                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `customer` (`customer_id`, `customer_fname`, `customer_email`, `customer_password`, `customer_phone`, `customer_address`, `customer_role`) VALUES
(9, 'abouda', 'aboudaha@gmail.com', '123456789', '0550301515', 'alger', 'normal'), -- Changed role to normal as admin should be managed differently usually
(24, 'mouad', 'mouadmouad@gamil.com', '123456789', '0550304416', 'alger', 'normal'),
(25, 'tamim', 'tamimtamim@gmail.com', '123456789', '0550304415', 'alger', 'normal');

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Laptop` data                                                                                   |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `laptop` (
`laptop_id`,
`laptop_name`,
`laptop_small_description`,
`laptop_long_description`,
`laptop_price`,
`laptop_image_path`,
`laptop_category_id`,
`laptop_quantity`,
`laptop_status`
) VALUES
(1, 'Dell XPS 13', 'Compact and powerful ultrabook.', 'The Dell XPS 13 features a 13-inch InfinityEdge display, 11th Gen Intel Core processor, and long battery life, making it perfect for students and professionals.', 1200.00, './images/products_images/laptops/laptop1.jpg', 2, 10, TRUE),
(2, 'MacBook Pro', 'Professional laptop for business.', 'Apple MacBook Pro with M1 chip, Retina display, and all-day battery life. Ideal for business users and creators.', 1800.00, './images/products_images/laptops/laptop2.jpg', 1, 7, TRUE),
(3, 'HP Spectre x360', 'Convertible laptop for flexibility.', 'HP Spectre x360 offers a 2-in-1 design, touchscreen, and powerful performance for students and professionals.', 1400.00, './images/products_images/laptops/laptop3.jpg', 2, 5, TRUE),
(4, 'Acer Predator Helios', 'High-performance gaming laptop.', 'Acer Predator Helios is equipped with a high-refresh-rate display, powerful GPU, and advanced cooling for gaming enthusiasts.', 1600.00, './images/products_images/laptops/laptop4.jpg', 3, 3, TRUE),
(5, 'Lenovo ThinkPad X1', 'Business-class ultrabook.', 'Lenovo ThinkPad X1 Carbon offers military-grade durability, exceptional keyboard, and enterprise security features.', 1500.00, './images/products_images/laptops/laptop5.jpg', 1, 8, TRUE),
(6, 'MSI GS66 Stealth', 'Premium gaming laptop.', 'MSI GS66 Stealth combines powerful gaming performance with a sleek design and advanced cooling system.', 1900.00, './images/products_images/laptops/laptop6.jpg', 3, 4, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Desktop` data                                                                                  |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `desktop` (`desktop_id`, `desktop_name`, `desktop_small_description`, `desktop_long_description`, `desktop_price`, `desktop_image_path`, `desktop_category_id`, `desktop_quantity`, `desktop_status`) VALUES
(1, 'Dell OptiPlex 3080', 'Reliable business desktop.', 'Dell OptiPlex 3080 offers robust performance and security for business environments.', 900.00, './images/products_images/desktops/desktop1.jpg', 1, 8, TRUE),
(2, 'HP Pavilion', 'Versatile desktop for students.', 'HP Pavilion desktop is perfect for students, offering a balance of performance and value.', 700.00, './images/products_images/desktops/desktop2.jpg', 2, 5, TRUE),
(3, 'Lenovo ThinkCentre', 'Powerful gaming desktop.', 'Lenovo ThinkCentre is equipped with high-end components for gaming and multitasking.', 1100.00, './images/products_images/desktops/desktop3.jpg', 3, 12, TRUE),
(4, 'Acer Aspire TC', 'Affordable student desktop.', 'Acer Aspire TC is a budget-friendly desktop for everyday student tasks.', 650.00, './images/products_images/desktops/desktop4.jpg', 2, 7, FALSE),
(5, 'Apple iMac', 'All-in-one desktop solution.', 'Apple iMac features a stunning 4.5K Retina display and M1 chip for creative professionals.', 1500.00, './images/products_images/desktops/desktop5.jpg', 1, 6, TRUE),
(6, 'MSI MEG Aegis', 'Premium gaming desktop.', 'MSI MEG Aegis offers top-tier gaming performance with RGB lighting and liquid cooling.', 2000.00, './images/products_images/desktops/desktop6.jpg', 3, 4, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `CustomBuild` data                                                                              |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `custombuild` (
`custombuild_id`,
`custombuild_name`,
`custombuild_small_description`,
`custombuild_long_description`,
`custombuild_price`,
`custombuild_image_path`,
`custombuild_category_id`,
`custombuild_quantity`,
`custombuild_status`
) VALUES
(1, 'Gaming Beast', 'Ultimate gaming custom build.', 'Custom built for gaming with top-tier GPU, CPU, and cooling.', 2000.00, './images/products_images/custombuilds/custom1.jpg', 3, 2, TRUE),
(2, 'Office Pro', 'Business custom build.', 'Optimized for business tasks with reliable components and security.', 1500.00, './images/products_images/custombuilds/custom2.jpg', 1, 5, TRUE),
(3, 'Budget Build', 'Affordable student custom build.', 'Entry-level custom build for students and light users.', 800.00, './images/products_images/custombuilds/custom3.jpg', 2, 10, TRUE),
(4, 'Creator Studio', 'Content creation powerhouse.', 'Custom build optimized for video editing, 3D rendering, and content creation.', 2500.00, './images/products_images/custombuilds/custom4.jpg', 1, 3, TRUE),
(5, 'Compact Gaming', 'Small form factor gaming build.', 'Powerful gaming performance in a compact ITX case design.', 1800.00, './images/products_images/custombuilds/custom5.jpg', 3, 4, TRUE),
(6, 'Workstation Pro', 'Professional workstation build.', 'High-end workstation for CAD, 3D modeling, and professional applications.', 3000.00, './images/products_images/custombuilds/custom6.jpg', 1, 2, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `CPU` data                                                                                       |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `cpu` (`cpu_id`, `cpu_name`, `cpu_small_description`, `cpu_long_description`, `cpu_price`, `cpu_image_path`, `cpu_category_id`, `cpu_quantity`, `cpu_status`) VALUES
(1, 'Intel Core i5-1135G7', 'Quad-core processor with integrated graphics', '11th Gen Intel Core i5-1135G7 processor with integrated graphics', 250.00, './images/products_images/cpus/cpu1.jpg', 1, 10, TRUE),
(2, 'AMD Ryzen 5 5600X', '6-core processor with integrated graphics', 'AMD Ryzen 5 5600X processor with integrated graphics', 300.00, './images/products_images/cpus/cpu2.jpg', 3, 5, TRUE),
(3, 'Intel Core i7-11700K', '8-core processor with integrated graphics', 'Intel Core i7-11700K processor with integrated graphics', 400.00, './images/products_images/cpus/cpu3.jpg', 3, 3, TRUE),
(4, 'AMD Ryzen 9 5950X', '16-core high-end processor', 'AMD Ryzen 9 5950X processor for extreme performance and multitasking', 750.00, './images/products_images/cpus/cpu4.jpg', 3, 2, TRUE),
(5, 'Intel Core i9-12900K', '16-core flagship processor', 'Intel Core i9-12900K processor with hybrid architecture', 600.00, './images/products_images/cpus/cpu5.jpg', 3, 4, TRUE),
(6, 'AMD Ryzen 7 5800X', '8-core balanced processor', 'AMD Ryzen 7 5800X processor for gaming and content creation', 450.00, './images/products_images/cpus/cpu6.jpg', 3, 6, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `GPU` data                                                                                       |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `gpu` (`gpu_id`, `gpu_name`, `gpu_small_description`, `gpu_long_description`, `gpu_price`, `gpu_image_path`, `gpu_category_id`, `gpu_quantity`, `gpu_status`) VALUES
(1, 'NVIDIA GeForce RTX 3060', '12GB GDDR6X VRAM', 'NVIDIA GeForce RTX 3060 with 12GB GDDR6X VRAM', 500.00, './images/products_images/gpus/gpu1.jpg', 3, 10, TRUE),
(2, 'AMD Radeon RX 6800 XT', '16GB GDDR6 VRAM', 'AMD Radeon RX 6800 XT with 16GB GDDR6 VRAM', 700.00, './images/products_images/gpus/gpu2.jpg', 3, 5, TRUE),
(3, 'NVIDIA GeForce RTX 3070', '8GB GDDR6X VRAM', 'NVIDIA GeForce RTX 3070 with 8GB GDDR6X VRAM', 600.00, './images/products_images/gpus/gpu3.jpg', 3, 3, TRUE),
(4, 'NVIDIA GeForce RTX 3080', '10GB GDDR6X VRAM', 'NVIDIA GeForce RTX 3080 with 10GB GDDR6X VRAM', 800.00, './images/products_images/gpus/gpu4.jpg', 3, 4, TRUE),
(5, 'AMD Radeon RX 6900 XT', '16GB GDDR6 VRAM', 'AMD Radeon RX 6900 XT flagship gaming GPU', 1000.00, './images/products_images/gpus/gpu5.jpg', 3, 2, TRUE),
(6, 'NVIDIA GeForce RTX 3090', '24GB GDDR6X VRAM', 'NVIDIA GeForce RTX 3090 for ultimate gaming and content creation', 1500.00, './images/products_images/gpus/gpu6.jpg', 3, 1, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Keyboard` data                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `keyboard` (`keyboard_id`, `keyboard_name`, `keyboard_small_description`, `keyboard_long_description`, `keyboard_price`, `keyboard_image_path`, `keyboard_category_id`, `keyboard_quantity`, `keyboard_status`) VALUES
(1, 'Logitech G Pro', 'Mechanical gaming keyboard', 'Logitech G Pro gaming keyboard with mechanical switches', 100.00, './images/products_images/keyboards/keyboard1.jpg', 3, 10, TRUE),
(2, 'Razer Huntsman Elite', 'Razer Huntsman Elite gaming keyboard', 'Razer Huntsman Elite gaming keyboard with Razer Chroma RGB lighting', 150.00, './images/products_images/keyboards/keyboard2.jpg', 3, 5, TRUE),
(3, 'Corsair K95 RGB Platinum', 'Corsair K95 RGB Platinum gaming keyboard', 'Corsair K95 RGB Platinum gaming keyboard with Corsair iCUE software', 200.00, './images/products_images/keyboards/keyboard3.jpg', 3, 3, TRUE),
(4, 'Ducky One 2 Mini', 'Compact mechanical keyboard', 'Ducky One 2 Mini 60% mechanical keyboard with PBT keycaps', 120.00, './images/products_images/keyboards/keyboard4.jpg', 3, 6, TRUE),
(5, 'SteelSeries Apex Pro', 'Adjustable mechanical keyboard', 'SteelSeries Apex Pro with adjustable actuation switches', 180.00, './images/products_images/keyboards/keyboard5.jpg', 3, 4, TRUE),
(6, 'ASUS ROG Strix Scope', 'Premium gaming keyboard', 'ASUS ROG Strix Scope with Cherry MX switches and aluminum frame', 160.00, './images/products_images/keyboards/keyboard6.jpg', 3, 5, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `DisplayScreen` data                                                                            |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `displayscreen` (`displayscreen_id`, `displayscreen_name`, `displayscreen_small_description`, `displayscreen_long_description`, `displayscreen_price`, `displayscreen_image_path`, `displayscreen_category_id`, `displayscreen_quantity`, `displayscreen_status`) VALUES
(1, 'Dell UltraSharp', 'High-resolution business monitor.', 'Dell UltraSharp offers stunning visuals and color accuracy for business use.', 400.00, './images/products_images/displayscreens/display1.jpg', 1, 10, TRUE),
(2, 'Acer Predator', 'Gaming monitor with high refresh rate.', 'Acer Predator delivers smooth gameplay with a high refresh rate and low response time.', 500.00, './images/products_images/displayscreens/display2.jpg', 3, 5, TRUE),
(3, 'HP EliteDisplay', 'Student-friendly display.', 'HP EliteDisplay is affordable and reliable, perfect for students.', 250.00, './images/products_images/displayscreens/display3.jpg', 2, 7, TRUE),
(4, 'LG UltraGear', '4K Gaming Monitor', 'LG UltraGear 4K gaming monitor with G-Sync and HDR support', 700.00, './images/products_images/displayscreens/display4.jpg', 3, 4, TRUE),
(5, 'Samsung Odyssey G7', 'Curved Gaming Monitor', 'Samsung Odyssey G7 curved gaming monitor with 240Hz refresh rate', 650.00, './images/products_images/displayscreens/display5.jpg', 3, 3, TRUE),
(6, 'ASUS ProArt', 'Professional Display', 'ASUS ProArt professional monitor with factory calibration for content creation', 800.00, './images/products_images/displayscreens/display6.jpg', 1, 2, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Banner` data                                                                                  |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `banner` (`banner_id`, `banner_title`, `banner_text`, `banner_image_path`, `banner_status`) VALUES
(1, 'LIMITED DEALS','check out our latest deals', 'banner-1.jpg', TRUE),
(2, 'NEXT-GEN GAMING LAPTOPS', 'check out our collection of gaming laptops', 'banner-2.jpg', TRUE),
(3, 'GET YOUR CUSTOM PC', 'get professional assembly and 3-year warranty', 'banner-3.jpg', TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- End of File                                                                                                  |
-- --------------------------------------------------------------------------------------------------------------
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@@COLLATION_CONNECTION */;