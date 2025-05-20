SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0; -- Disable foreign key checks

-- Save the original values explicitly with defaults
SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION;
SET NAMES utf8mb4;

--
-- Database: `site_database`
--

-- --------------------------------------------------------------------------------------------------------------
-- NOTE: if u want to rerun the script just uncomment the lines below (it will all tables data) :)              |
-- --------------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `Laptops`; 
DROP TABLE IF EXISTS `Desktops`;
DROP TABLE IF EXISTS `Custom Builds`;
DROP TABLE IF EXISTS `Processors`;
DROP TABLE IF EXISTS `Graphics Cards`;
DROP TABLE IF EXISTS `Keyboards`;
DROP TABLE IF EXISTS `Display Screens`;
DROP TABLE IF EXISTS `banner`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `settings`; 
DROP TABLE IF EXISTS `product_types`;
DROP TABLE IF EXISTS `category`;

-- --------------------------------------------------------------------------------------------------------------
-- Tables Structure                                                                                             |
-- --------------------------------------------------------------------------------------------------------------

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Product_Types`                                                                          |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `product_types` (
`type_id` INT AUTO_INCREMENT PRIMARY KEY,
`type_name` VARCHAR(40) NOT NULL UNIQUE,
`type_display_order` INT NOT NULL DEFAULT 0,
`type_status` BOOLEAN NOT NULL DEFAULT TRUE,
`type_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`type_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Category`                                                                               |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `category` (
`category_id` INT AUTO_INCREMENT PRIMARY KEY,
`category_name` VARCHAR(50) NOT NULL UNIQUE,
`category_status` BOOLEAN NOT NULL DEFAULT TRUE,
`category_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`category_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `customer`                                                                               |
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
-- Structure for table `Laptops`                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Laptops` (
`Laptops_id` INT AUTO_INCREMENT PRIMARY KEY,
`Laptops_name` VARCHAR(40) NOT NULL,
`Laptops_small_description` VARCHAR(40) NOT NULL,
`Laptops_long_description` TEXT NOT NULL,
`Laptops_price` DECIMAL(8,2) NOT NULL,
`Laptops_image_path` TEXT NOT NULL,
`Laptops_category_id` INT NOT NULL,
`Laptops_quantity` INT NOT NULL DEFAULT 0,
`Laptops_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Laptops_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Laptops_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Laptops_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Desktops`                                                                                |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Desktops` (
`Desktops_id` INT AUTO_INCREMENT PRIMARY KEY,
`Desktops_name` VARCHAR(40) NOT NULL,
`Desktops_small_description` VARCHAR(40) NOT NULL,
`Desktops_long_description` TEXT NOT NULL,
`Desktops_price` DECIMAL(8,2) NOT NULL,
`Desktops_image_path` TEXT NOT NULL,
`Desktops_category_id` INT NOT NULL,
`Desktops_quantity` INT NOT NULL DEFAULT 0,
`Desktops_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Desktops_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Desktops_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Desktops_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Custom Builds`                                                                            |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Custom Builds` (
`Custom Builds_id` INT AUTO_INCREMENT PRIMARY KEY,
`Custom Builds_name` VARCHAR(40) NOT NULL,
`Custom Builds_small_description` VARCHAR(40) NOT NULL,
`Custom Builds_long_description` TEXT NOT NULL,
`Custom Builds_price` DECIMAL(8,2) NOT NULL,
`Custom Builds_image_path` TEXT NOT NULL,
`Custom Builds_category_id` INT NOT NULL,
`Custom Builds_quantity` INT NOT NULL DEFAULT 0,
`Custom Builds_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Custom Builds_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Custom Builds_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Custom Builds_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Processors`                                                                                    |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Processors` (
`Processors_id` INT AUTO_INCREMENT PRIMARY KEY,
`Processors_name` VARCHAR(40) NOT NULL,
`Processors_small_description` VARCHAR(40) NOT NULL,
`Processors_long_description` TEXT NOT NULL,
`Processors_price` DECIMAL(8,2) NOT NULL,
`Processors_image_path` TEXT NOT NULL,
`Processors_category_id` INT NOT NULL,
`Processors_quantity` INT NOT NULL DEFAULT 0,
`Processors_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Processors_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Processors_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Processors_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Graphics Cards`                                                                                      |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Graphics Cards` (
`Graphics Cards_id` INT AUTO_INCREMENT PRIMARY KEY,
`Graphics Cards_name` VARCHAR(40) NOT NULL,
`Graphics Cards_small_description` VARCHAR(40) NOT NULL,
`Graphics Cards_long_description` TEXT NOT NULL,
`Graphics Cards_price` DECIMAL(8,2) NOT NULL,
`Graphics Cards_image_path` TEXT NOT NULL,
`Graphics Cards_category_id` INT NOT NULL,
`Graphics Cards_quantity` INT NOT NULL DEFAULT 0,
`Graphics Cards_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Graphics Cards_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Graphics Cards_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Graphics Cards_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Keyboards`                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Keyboards` (
`Keyboards_id` INT AUTO_INCREMENT PRIMARY KEY,
`Keyboards_name` VARCHAR(40) NOT NULL,
`Keyboards_small_description` VARCHAR(40) NOT NULL,
`Keyboards_long_description` TEXT NOT NULL,
`Keyboards_price` DECIMAL(8,2) NOT NULL,
`Keyboards_image_path` TEXT NOT NULL,
`Keyboards_category_id` INT NOT NULL,
`Keyboards_quantity` INT NOT NULL DEFAULT 0,
`Keyboards_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Keyboards_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Keyboards_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Keyboards_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Display Screens`                                                                            |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `Display Screens` (
`Display Screens_id` INT AUTO_INCREMENT PRIMARY KEY,
`Display Screens_name` VARCHAR(40) NOT NULL,
`Display Screens_small_description` VARCHAR(40) NOT NULL,
`Display Screens_long_description` TEXT NOT NULL,
`Display Screens_price` DECIMAL(8,2) NOT NULL,
`Display Screens_image_path` TEXT NOT NULL,
`Display Screens_category_id` INT NOT NULL,
`Display Screens_quantity` INT NOT NULL DEFAULT 0,
`Display Screens_status` BOOLEAN NOT NULL DEFAULT FALSE,
`Display Screens_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Display Screens_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (`Display Screens_category_id`) REFERENCES `category`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `orders`                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `payment_method` ENUM('cash', 'card') NOT NULL,
  `order_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `order_items`                                                                            |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `order_items` (
  `item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_type` VARCHAR(40) NOT NULL,
  `product_name` VARCHAR(40) NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(8,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `Banner`                                                                                   |
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

-- --------------------------------------------------------------------------------------------------------------
-- Structure for table `settings`                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

CREATE TABLE `settings` (
  `website_name` varchar(60) NOT NULL,
  `website_logo` varchar(50) NOT NULL,
  `website_footer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------------------------------------------------
-- Data insertion for tables                                                                                    |
-- --------------------------------------------------------------------------------------------------------------

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Product_Types` data                                                                            |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `product_types` (`type_id`, `type_name`, `type_display_order`, `type_status`) VALUES
(1, 'Laptops', 1, TRUE),
(2, 'Desktops', 2, TRUE),
(3, 'Custom Builds', 3, TRUE),
(4, 'Display Screens', 4, TRUE),
(5, 'Graphics Cards', 5, TRUE),
(6, 'Processors', 6, TRUE),
(7, 'Keyboards', 7, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Category` data                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `category` (`category_id`, `category_name`, `category_status`) VALUES
(1, 'business', TRUE),
(2, 'student', TRUE),
(3, 'gaming', TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Customer` data                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `customer` (`customer_id`, `customer_fname`, `customer_email`, `customer_password`, `customer_phone`, `customer_address`, `customer_role`) VALUES
(1, 'abouda', 'aboudaha@gmail.com', '123456789', '0550301515', 'alger', 'admin'), 
(2, 'mouad', 'mouadmouad@gamil.com', '123456789', '0550304416', 'alger', 'normal'),
(3, 'tamim', 'tamimtamim@gmail.com', '123456789', '0550304415', 'alger', 'normal');

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Laptops` data                                                                                   |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Laptops` (
`Laptops_id`,
`Laptops_name`,
`Laptops_small_description`,
`Laptops_long_description`,
`Laptops_price`,
`Laptops_image_path`,
`Laptops_category_id`,
`Laptops_quantity`,
`Laptops_status`
) VALUES
(1, 'Dell XPS 13', 'Compact ultrabook for students', 'The Dell XPS 13 features a 13-inch InfinityEdge display, 11th Gen Intel Core processor, and long battery life, making it perfect for students and professionals.', 1200.00, './images/products_images/laptops/laptop1.jpg', 2, 10, TRUE),
(2, 'MacBook Pro', 'Professional business laptop', 'Apple MacBook Pro with M1 chip, Retina display, and all-day battery life. Ideal for business users and creators.', 1800.00, './images/products_images/laptops/laptop2.jpg', 1, 7, TRUE),
(3, 'HP Spectre x360', 'Convertible for students', 'HP Spectre x360 offers a 2-in-1 design, touchscreen, and powerful performance for students and professionals.', 1400.00, './images/products_images/laptops/laptop3.jpg', 2, 5, TRUE),
(4, 'Acer Predator Helios', 'High-performance gaming laptop', 'Acer Predator Helios is equipped with a high-refresh-rate display, powerful GPU, and advanced cooling for gaming enthusiasts.', 1600.00, './images/products_images/laptops/laptop4.jpg', 3, 3, TRUE),
(5, 'Lenovo ThinkPad X1', 'Business-class ultrabook', 'Lenovo ThinkPad X1 Carbon offers military-grade durability, exceptional keyboard, and enterprise security features.', 1500.00, './images/products_images/laptops/laptop5.jpg', 1, 8, TRUE),
(6, 'MSI GS66 Stealth', 'Premium gaming laptop', 'MSI GS66 Stealth combines powerful gaming performance with a sleek design and advanced cooling system.', 1900.00, './images/products_images/laptops/laptop6.jpg', 3, 4, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Desktops` data                                                                                  |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Desktops` (`Desktops_id`, `Desktops_name`, `Desktops_small_description`, `Desktops_long_description`, `Desktops_price`, `Desktops_image_path`, `Desktops_category_id`, `Desktops_quantity`, `Desktops_status`) VALUES
(1, 'Dell OptiPlex 3080', 'Reliable business desktop', 'Dell OptiPlex 3080 offers robust performance and security for business environments.', 900.00, './images/products_images/desktops/desktop1.jpg', 1, 8, TRUE),
(2, 'HP Pavilion', 'Versatile student desktop', 'HP Pavilion desktop is perfect for students, offering a balance of performance and value.', 700.00, './images/products_images/desktops/desktop2.jpg', 2, 5, TRUE),
(3, 'Lenovo ThinkCentre', 'Powerful gaming desktop', 'Lenovo ThinkCentre is equipped with high-end components for gaming and multitasking.', 1100.00, './images/products_images/desktops/desktop3.jpg', 3, 12, TRUE),
(4, 'Acer Aspire TC', 'Affordable student desktop', 'Acer Aspire TC is a budget-friendly desktop for everyday student tasks.', 650.00, './images/products_images/desktops/desktop4.jpg', 2, 7, TRUE),
(5, 'Apple iMac', 'All-in-one desktop solution', 'Apple iMac features a stunning 4.5K Retina display and M1 chip for creative professionals.', 1500.00, './images/products_images/desktops/desktop5.jpg', 1, 6, TRUE),
(6, 'MSI MEG Aegis', 'Premium gaming desktop', 'MSI MEG Aegis offers top-tier gaming performance with RGB lighting and liquid cooling.', 2000.00, './images/products_images/desktops/desktop6.jpg', 3, 4, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Custom Builds` data                                                                              |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Custom Builds` (
`Custom Builds_id`,
`Custom Builds_name`,
`Custom Builds_small_description`,
`Custom Builds_long_description`,
`Custom Builds_price`,
`Custom Builds_image_path`,
`Custom Builds_category_id`,
`Custom Builds_quantity`,
`Custom Builds_status`
) VALUES
(1, 'Gaming Beast', 'Ultimate gaming build', 'Custom built for gaming with top-tier GPU, CPU, and cooling.', 2000.00, './images/products_images/custombuilds/custom1.jpg', 3, 2, TRUE),
(2, 'Office Pro', 'Business custom PC', 'Optimized for business tasks with reliable components and security.', 1500.00, './images/products_images/custombuilds/custom2.jpg', 1, 5, TRUE),
(3, 'Budget Build', 'Affordable student PC', 'Entry-level custom build for students and light users.', 800.00, './images/products_images/custombuilds/custom3.jpg', 2, 10, TRUE),
(4, 'Creator Studio', 'Content creation powerhouse', 'Custom build optimized for video editing, 3D rendering, and content creation.', 2500.00, './images/products_images/custombuilds/custom4.jpg', 1, 3, TRUE),
(5, 'Compact Gaming', 'Small form factor gaming PC', 'Powerful gaming performance in a compact ITX case design.', 1800.00, './images/products_images/custombuilds/custom5.jpg', 3, 4, TRUE),
(6, 'Workstation Pro', 'Professional workstation', 'High-end workstation for CAD, 3D modeling, and professional applications.', 3000.00, './images/products_images/custombuilds/custom6.jpg', 1, 2, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Processors` data                                                                                       |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Processors` (`Processors_id`, `Processors_name`, `Processors_small_description`, `Processors_long_description`, `Processors_price`, `Processors_image_path`, `Processors_category_id`, `Processors_quantity`, `Processors_status`) VALUES
(1, 'Intel Core i5-1135G7', 'Quad-core with integrated graphics', '11th Gen Intel Core i5-1135G7 processor with integrated graphics', 250.00, './images/products_images/cpus/cpu1.jpg', 1, 10, TRUE),
(2, 'AMD Ryzen 5 5600X', '6-core processor for gaming', 'AMD Ryzen 5 5600X processor with integrated graphics', 300.00, './images/products_images/cpus/cpu2.jpg', 3, 5, TRUE),
(3, 'Intel Core i7-11700K', '8-core high-performance CPU', 'Intel Core i7-11700K processor with integrated graphics', 400.00, './images/products_images/cpus/cpu3.jpg', 3, 3, TRUE),
(4, 'AMD Ryzen 9 5950X', '16-core high-end processor', 'AMD Ryzen 9 5950X processor for extreme performance and multitasking', 750.00, './images/products_images/cpus/cpu4.jpg', 3, 2, TRUE),
(5, 'Intel Core i9-12900K', 'Flagship 16-core processor', 'Intel Core i9-12900K processor with hybrid architecture', 600.00, './images/products_images/cpus/cpu5.jpg', 3, 4, TRUE),
(6, 'AMD Ryzen 7 5800X', '8-core for gaming & content', 'AMD Ryzen 7 5800X processor for gaming and content creation', 450.00, './images/products_images/cpus/cpu6.jpg', 3, 6, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Graphics Cards` data                                                                                       |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Graphics Cards` (`Graphics Cards_id`, `Graphics Cards_name`, `Graphics Cards_small_description`, `Graphics Cards_long_description`, `Graphics Cards_price`, `Graphics Cards_image_path`, `Graphics Cards_category_id`, `Graphics Cards_quantity`, `Graphics Cards_status`) VALUES
(1, 'NVIDIA GeForce RTX 3060', '12GB GDDR6X Gaming GPU', 'NVIDIA GeForce RTX 3060 with 12GB GDDR6X VRAM', 500.00, './images/products_images/gpus/gpu1.jpg', 3, 10, TRUE),
(2, 'AMD Radeon RX 6800 XT', '16GB GDDR6 Gaming GPU', 'AMD Radeon RX 6800 XT with 16GB GDDR6 VRAM', 700.00, './images/products_images/gpus/gpu2.jpg', 3, 5, TRUE),
(3, 'NVIDIA GeForce RTX 3070', '8GB GDDR6X Gaming GPU', 'NVIDIA GeForce RTX 3070 with 8GB GDDR6X VRAM', 600.00, './images/products_images/gpus/gpu3.jpg', 3, 3, TRUE),
(4, 'NVIDIA GeForce RTX 3080', '10GB GDDR6X Gaming GPU', 'NVIDIA GeForce RTX 3080 with 10GB GDDR6X VRAM', 800.00, './images/products_images/gpus/gpu4.jpg', 3, 4, TRUE),
(5, 'AMD Radeon RX 6900 XT', '16GB GDDR6 Flagship GPU', 'AMD Radeon RX 6900 XT flagship gaming GPU', 1000.00, './images/products_images/gpus/gpu5.jpg', 3, 2, TRUE),
(6, 'NVIDIA GeForce RTX 3090', '24GB GDDR6X Ultimate GPU', 'NVIDIA GeForce RTX 3090 for ultimate gaming and content creation', 1500.00, './images/products_images/gpus/gpu6.jpg', 3, 1, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Keyboards` data                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Keyboards` (`Keyboards_id`, `Keyboards_name`, `Keyboards_small_description`, `Keyboards_long_description`, `Keyboards_price`, `Keyboards_image_path`, `Keyboards_category_id`, `Keyboards_quantity`, `Keyboards_status`) VALUES
(1, 'Logitech G Pro', 'Mechanical gaming keyboard', 'Logitech G Pro gaming keyboard with mechanical switches', 100.00, './images/products_images/keyboards/keyboard1.jpg', 3, 10, TRUE),
(2, 'Razer Huntsman Elite', 'RGB gaming keyboard', 'Razer Huntsman Elite gaming keyboard with Razer Chroma RGB lighting', 150.00, './images/products_images/keyboards/keyboard2.jpg', 3, 5, TRUE),
(3, 'Corsair K95 RGB Platinum', 'Premium gaming keyboard', 'Corsair K95 RGB Platinum gaming keyboard with Corsair iCUE software', 200.00, './images/products_images/keyboards/keyboard3.jpg', 3, 3, TRUE),
(4, 'Ducky One 2 Mini', 'Compact mechanical keyboard', 'Ducky One 2 Mini 60% mechanical keyboard with PBT keycaps', 120.00, './images/products_images/keyboards/keyboard4.jpg', 3, 6, TRUE),
(5, 'SteelSeries Apex Pro', 'Adjustable mechanical keyboard', 'SteelSeries Apex Pro with adjustable actuation switches', 180.00, './images/products_images/keyboards/keyboard5.jpg', 3, 4, TRUE),
(6, 'ASUS ROG Strix Scope', 'Gaming keyboard with Cherry MX', 'ASUS ROG Strix Scope with Cherry MX switches and aluminum frame', 160.00, './images/products_images/keyboards/keyboard6.jpg', 3, 5, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Display Screens` data                                                                            |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `Display Screens` (`Display Screens_id`, `Display Screens_name`, `Display Screens_small_description`, `Display Screens_long_description`, `Display Screens_price`, `Display Screens_image_path`, `Display Screens_category_id`, `Display Screens_quantity`, `Display Screens_status`) VALUES
(1, 'Dell UltraSharp', 'High-res business monitor', 'Dell UltraSharp offers stunning visuals and color accuracy for business use.', 400.00, './images/products_images/displayscreens/display1.jpg', 1, 10, TRUE),
(2, 'Acer Predator', 'Gaming monitor with high refresh', 'Acer Predator delivers smooth gameplay with a high refresh rate and low response time.', 500.00, './images/products_images/displayscreens/display2.jpg', 3, 5, TRUE),
(3, 'HP EliteDisplay', 'Student-friendly display', 'HP EliteDisplay is affordable and reliable, perfect for students.', 250.00, './images/products_images/displayscreens/display3.jpg', 2, 7, TRUE),
(4, 'LG UltraGear', '4K Gaming Monitor with G-Sync', 'LG UltraGear 4K gaming monitor with G-Sync and HDR support', 700.00, './images/products_images/displayscreens/display4.jpg', 3, 4, TRUE),
(5, 'Samsung Odyssey G7', 'Curved 240Hz Gaming Monitor', 'Samsung Odyssey G7 curved gaming monitor with 240Hz refresh rate', 650.00, './images/products_images/displayscreens/display5.jpg', 3, 3, TRUE),
(6, 'ASUS ProArt', 'Professional Calibrated Display', 'ASUS ProArt professional monitor with factory calibration for content creation', 800.00, './images/products_images/displayscreens/display6.jpg', 1, 2, TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `Banner` data                                                                                   |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `banner` (`banner_id`, `banner_title`, `banner_text`, `banner_image_path`, `banner_status`) VALUES
(1, 'LIMITED DEALS','check out our latest deals', './images/banners/banner-1.jpg', TRUE),
(2, 'NEXT-GEN GAMING LAPTOPS', 'check out our collection of gaming laptops', './images/banners/banner-2.jpg', TRUE),
(3, 'GET YOUR CUSTOM PC', 'get professional assembly and 3-year warranty', './images/banners/banner-3.jpg', TRUE);

-- --------------------------------------------------------------------------------------------------------------
-- insert table `settings` data                                                                                 |
-- --------------------------------------------------------------------------------------------------------------

INSERT INTO `settings` (`website_name`, `website_logo`, `website_footer`) VALUES
('PeakGear', 'PeakGear.png', 'PeakGear');


-- --------------------------------------------------------------------------------------------------------------
-- End of File                                                                                                  |
-- --------------------------------------------------------------------------------------------------------------

-- --------------------------------------------------------------------------------------------------------------
-- Added Procedures and Triggers                                                                               |
-- --------------------------------------------------------------------------------------------------------------

DELIMITER //

-- Procedure to display order details when total is paid
CREATE PROCEDURE GetPaidOrderDetails(IN customerId INT, IN orderId INT)
BEGIN
    SELECT o.*, c.customer_fname, c.customer_email, c.customer_phone, c.customer_address
    FROM orders o
    JOIN customer c ON o.customer_id = c.customer_id
    WHERE o.order_id = orderId AND o.customer_id = customerId 
    AND o.order_status IN ('processing', 'shipped', 'delivered');
    
    SELECT oi.*, oi.quantity * oi.price AS item_total
    FROM order_items oi
    WHERE oi.order_id = orderId;
END //

-- Procedure to finalize an order and update its status
CREATE PROCEDURE FinalizeOrder(IN orderId INT, OUT success BOOLEAN)
BEGIN
    DECLARE currentStatus VARCHAR(50);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET success = FALSE;
        ROLLBACK;
    END;
    
    START TRANSACTION;
    
    -- Get current status
    SELECT order_status INTO currentStatus FROM orders WHERE order_id = orderId;
    
    -- Only allow finalizing orders that are in pending status
    IF currentStatus = 'pending' THEN
        -- Update order status to processing
        UPDATE orders SET order_status = 'processing', updated_at = NOW() WHERE order_id = orderId;
        SET success = TRUE;
    ELSE
        SET success = FALSE;
    END IF;
    
    COMMIT;
END //

-- Procedure to get order history for a customer
CREATE PROCEDURE GetCustomerOrderHistory(IN customerId INT)
BEGIN
    SELECT 
        o.order_id, 
        o.order_date, 
        o.total_amount, 
        o.order_status,
        COUNT(oi.item_id) AS total_items
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.customer_id = customerId
    GROUP BY o.order_id
    ORDER BY o.order_date DESC;
END //

-- Trigger to update product stock after order validation
CREATE TRIGGER after_order_validate
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    -- Check if status changed from pending to processing
    IF OLD.order_status = 'pending' AND NEW.order_status = 'processing' THEN
        -- Update the stock for each item in the order
        UPDATE Laptops L
        JOIN order_items oi ON L.Laptops_id = oi.product_id
        SET L.Laptops_quantity = L.Laptops_quantity - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Laptops';
        
        UPDATE Desktops D
        JOIN order_items oi ON D.Desktops_id = oi.product_id
        SET D.Desktops_quantity = D.Desktops_quantity - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Desktops';
        
        UPDATE `Custom Builds` CB
        JOIN order_items oi ON CB.`Custom Builds_id` = oi.product_id
        SET CB.`Custom Builds_quantity` = CB.`Custom Builds_quantity` - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Custom Builds';
        
        UPDATE Processors P
        JOIN order_items oi ON P.Processors_id = oi.product_id
        SET P.Processors_quantity = P.Processors_quantity - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Processors';
        
        UPDATE `Graphics Cards` GC
        JOIN order_items oi ON GC.`Graphics Cards_id` = oi.product_id
        SET GC.`Graphics Cards_quantity` = GC.`Graphics Cards_quantity` - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Graphics Cards';
        
        UPDATE Keyboards K
        JOIN order_items oi ON K.Keyboards_id = oi.product_id
        SET K.Keyboards_quantity = K.Keyboards_quantity - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Keyboards';
        
        UPDATE `Display Screens` DS
        JOIN order_items oi ON DS.`Display Screens_id` = oi.product_id
        SET DS.`Display Screens_quantity` = DS.`Display Screens_quantity` - oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Display Screens';
    END IF;
END //

-- Temporary trigger to prevent order insertion if stock is insufficient
CREATE TRIGGER before_order_item_insert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    DECLARE error_message VARCHAR(255);
    
    -- Check stock based on product type
    CASE NEW.product_type
        WHEN 'Laptops' THEN
            SELECT Laptops_quantity INTO available_stock FROM Laptops WHERE Laptops_id = NEW.product_id;
        WHEN 'Desktops' THEN
            SELECT Desktops_quantity INTO available_stock FROM Desktops WHERE Desktops_id = NEW.product_id;
        WHEN 'Custom Builds' THEN
            SELECT `Custom Builds_quantity` INTO available_stock FROM `Custom Builds` WHERE `Custom Builds_id` = NEW.product_id;
        WHEN 'Processors' THEN
            SELECT Processors_quantity INTO available_stock FROM Processors WHERE Processors_id = NEW.product_id;
        WHEN 'Graphics Cards' THEN
            SELECT `Graphics Cards_quantity` INTO available_stock FROM `Graphics Cards` WHERE `Graphics Cards_id` = NEW.product_id;
        WHEN 'Keyboards' THEN
            SELECT Keyboards_quantity INTO available_stock FROM Keyboards WHERE Keyboards_id = NEW.product_id;
        WHEN 'Display Screens' THEN
            SELECT `Display Screens_quantity` INTO available_stock FROM `Display Screens` WHERE `Display Screens_id` = NEW.product_id;
        ELSE
            SET available_stock = 0;
    END CASE;
    
    -- If insufficient stock, prevent insert with detailed error
    IF NEW.quantity > available_stock THEN
        SET error_message = CONCAT('STOCK_ERROR:', NEW.product_name, ':', NEW.quantity, ':', IFNULL(available_stock, 0));
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = error_message;
    END IF;
END //

-- Trigger to restore stock after order cancellation
CREATE TRIGGER after_order_cancel
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    -- Check if status changed to cancelled
    IF OLD.order_status IN ('pending', 'processing') AND NEW.order_status = 'cancelled' THEN
        -- Restore the stock for each item in the order
        UPDATE Laptops L
        JOIN order_items oi ON L.Laptops_id = oi.product_id
        SET L.Laptops_quantity = L.Laptops_quantity + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Laptops';
        
        UPDATE Desktops D
        JOIN order_items oi ON D.Desktops_id = oi.product_id
        SET D.Desktops_quantity = D.Desktops_quantity + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Desktops';
        
        UPDATE `Custom Builds` CB
        JOIN order_items oi ON CB.`Custom Builds_id` = oi.product_id
        SET CB.`Custom Builds_quantity` = CB.`Custom Builds_quantity` + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Custom Builds';
        
        UPDATE Processors P
        JOIN order_items oi ON P.Processors_id = oi.product_id
        SET P.Processors_quantity = P.Processors_quantity + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Processors';
        
        UPDATE `Graphics Cards` GC
        JOIN order_items oi ON GC.`Graphics Cards_id` = oi.product_id
        SET GC.`Graphics Cards_quantity` = GC.`Graphics Cards_quantity` + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Graphics Cards';
        
        UPDATE Keyboards K
        JOIN order_items oi ON K.Keyboards_id = oi.product_id
        SET K.Keyboards_quantity = K.Keyboards_quantity + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Keyboards';
        
        UPDATE `Display Screens` DS
        JOIN order_items oi ON DS.`Display Screens_id` = oi.product_id
        SET DS.`Display Screens_quantity` = DS.`Display Screens_quantity` + oi.quantity
        WHERE oi.order_id = NEW.order_id AND oi.product_type = 'Display Screens';
    END IF;
END //

-- Create order history table to track annual orders
CREATE TABLE IF NOT EXISTS `order_history` (
  `history_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `order_id` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `order_status` VARCHAR(50) NOT NULL,
  `items_count` INT NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create detailed order history table for analytics and reporting
CREATE TABLE IF NOT EXISTS `detailed_order_history` (
  `detail_id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_type` VARCHAR(50) NOT NULL,
  `product_name` VARCHAR(100) NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(8,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `order_date` TIMESTAMP NOT NULL,
  `order_status` VARCHAR(50) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `day` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`),
  INDEX `idx_product_type` (`product_type`),
  INDEX `idx_product_id` (`product_id`),
  INDEX `idx_date` (`year`, `month`, `day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Trigger to track order history annually
CREATE TRIGGER after_order_completion
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    DECLARE items_count INT;
    
    -- Only track completed or cancelled orders
    IF (OLD.order_status != NEW.order_status) AND 
       (NEW.order_status IN ('delivered', 'cancelled')) THEN
        
        -- Count items
        SELECT COUNT(*) INTO items_count FROM order_items WHERE order_id = NEW.order_id;
        
        -- Insert into history
        INSERT INTO order_history 
            (customer_id, order_id, total_amount, order_status, items_count, year, month)
        VALUES 
            (NEW.customer_id, NEW.order_id, NEW.total_amount, NEW.order_status, 
             items_count, YEAR(NEW.updated_at), MONTH(NEW.updated_at));
    END IF;
END //

-- Trigger to track detailed order history for analytics
CREATE TRIGGER after_order_detail_completion
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    -- Only track completed or cancelled orders
    IF (OLD.order_status != NEW.order_status) AND 
       (NEW.order_status IN ('delivered', 'cancelled')) THEN
        
        -- Insert detailed records for each order item
        INSERT INTO detailed_order_history 
            (order_id, customer_id, product_id, product_type, product_name, 
             quantity, unit_price, subtotal, order_date, order_status, 
             payment_method, year, month, day)
        SELECT 
            oi.order_id,
            NEW.customer_id,
            oi.product_id,
            oi.product_type,
            oi.product_name,
            oi.quantity,
            oi.price,
            oi.subtotal,
            NEW.order_date,
            NEW.order_status,
            NEW.payment_method,
            YEAR(NEW.updated_at),
            MONTH(NEW.updated_at),
            DAY(NEW.updated_at)
        FROM 
            order_items oi
        WHERE 
            oi.order_id = NEW.order_id;
    END IF;
END //

DELIMITER ;

-- --------------------------------------------------------------------------------------------------------------
-- End of Procedures and Triggers                                                                              |
-- --------------------------------------------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS=1; -- Re-enable foreign key checks

-- Restore original values with explicit error handling
SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT;
SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS;
SET COLLATION_CONNECTION = IFNULL(@OLD_COLLATION_CONNECTION, 'utf8mb4_general_ci');
COMMIT;