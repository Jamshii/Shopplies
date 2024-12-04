-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 12:37 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopplies_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Adhesive', 'Products used for sticking materials together.', '2024-11-29 18:55:34', NULL),
(2, 'Bags', 'Various types of bags and containers.', '2024-11-29 18:55:34', NULL),
(3, 'Crafts', 'Items for arts and crafting activities.', '2024-11-29 18:55:34', NULL),
(4, 'Cutting', 'Tools and materials for cutting.', '2024-11-29 18:55:34', NULL),
(5, 'Electronics', 'Electronic devices for various uses.', '2024-11-29 18:55:34', NULL),
(6, 'Fastening', 'Products for fastening materials.', '2024-11-29 18:55:34', NULL),
(7, 'Measurement', 'Tools for measuring properties.', '2024-11-29 18:55:34', NULL),
(8, 'Notebook', 'Notebooks for writing or taking notes.', '2024-11-29 18:55:34', NULL),
(9, 'Organizer', 'Products for organizing belongings.', '2024-11-29 18:55:34', NULL),
(10, 'Paper', 'Various types of paper for printing, or writing.', '2024-11-29 18:55:34', NULL),
(11, 'Writing', 'Writing instruments and correction tools.', '2024-11-29 18:55:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `name`, `phone_number`, `email`, `message`, `date_sent`) VALUES
(1, 'James Fanio', '09999999999', 'jefanio718@gmail.com', 'Hi po', '2024-12-04 19:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int UNSIGNED NOT NULL,
  `customer_id` int UNSIGNED NOT NULL,
  `order_token` varchar(255) DEFAULT NULL,
  `order_status` enum('Pending','Confirmed','Completed','Cancelled','Refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_address` text,
  `delivery_date` date NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_token`, `order_status`, `order_date`, `total_amount`, `shipping_address`, `delivery_date`, `updated_at`) VALUES
(1, 1, '445d94858a17407fd0ab5f9ce2cbd257', 'Confirmed', '2024-12-04 12:11:37', 35.00, NULL, '2024-12-09', '2024-12-04 19:15:39');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 18, 1, 35.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `category_id` int UNSIGNED DEFAULT NULL,
  `image` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Glue Gun (Violet)', 'Electric Heating Melt Glue Gun (Violet)\r\nColor: Violet\r\nRated voltage: 240V\r\nRated frequency: 60Hz\r\nInput power: 40W\r\nGlue output 7.48g\r\nApplicable glue stick size: 7mm', 130.00, 100, 1, 'IMG_9291.jpeg', '2024-12-03 01:34:36', '2024-12-03 11:43:23'),
(2, 'Glue Gun (Beige)', 'Electric Heating Melt Glue Gun (Beige)\r\nColor: Beige\r\nRated voltage: 240V\r\nRated frequency: 60Hz\r\nInput power: 40W\r\nGlue output 7.48g\r\nApplicable glue stick size: 7mm', 130.00, 100, 1, 'IMG_9292.jpeg', '2024-12-03 01:36:42', '2024-12-03 01:37:32'),
(3, 'Glue Gun (Green)', 'Electric Heating Melt Glue Gun (Green)\r\nColor: Green\r\nRated voltage: 240V\r\nRated frequency: 60Hz\r\nInput power: 40W\r\nGlue output 7.48g\r\nApplicable glue stick size: 7mm', 130.00, 100, 1, 'IMG_9293.jpeg', '2024-12-03 01:38:20', NULL),
(4, 'Glue Gun (Blue)', 'Electric Heating Melt Glue Gun (Blue)\r\nColor: Blue\r\nRated voltage: 240V\r\nRated frequency: 60Hz\r\nInput power: 40W\r\nGlue output 7.48g\r\nApplicable glue stick size: 7mm', 130.00, 100, 1, 'IMG_9294.jpeg', '2024-12-03 01:48:38', NULL),
(5, 'Glue Stick (10pcs)', '10pcs Hot Melt Glue Stick (7mm x 190mm)\r\nQuantity: 10pcs\r\nSize: 7mm x 190mm\r\nMaterial: EVA adhesive\r\nQuick and strong bonding performance', 22.00, 100, 1, 'IMG_9290.jpeg', '2024-12-03 01:50:55', NULL),
(6, 'Transparent Tape', 'Material: Clear adhesive plastic\r\nWidth: 18mm\r\nLength: 30 yards\r\nUses: General-purpose sealing, crafting, and repairs', 23.00, 100, 1, 'IMG_9000.jpeg', '2024-12-03 01:56:44', NULL),
(7, 'Masking Tape', 'Material: Paper adhesive\r\n Width: 1 inch\r\n Length: 20 yards\r\n Uses: Painting, labeling, and crafting', 45.00, 100, 1, 'IMG_9206.jpeg', '2024-12-03 01:57:53', NULL),
(8, 'Pink Backpack', 'Anti-theft and Waterproof Backpack (Pink)\r\n Color: Pink\r\n Material: Waterproof nylon\r\n Features: Anti-theft design, USB charging port\r\n Capacity: Fits a 15.6\" laptop', 179.00, 100, 2, 'IMG_9145.jpeg', '2024-12-03 02:00:09', NULL),
(9, 'Black Backpack', 'Anti-theft and Waterproof Backpack (Black)\r\n Color: Black\r\n Material: Waterproof nylon\r\n Features: Anti-theft design, USB charging port\r\n Capacity: Fits a 15.6\" laptop', 179.00, 100, 2, 'IMG_9150.jpeg', '2024-12-03 02:00:45', NULL),
(10, 'Violet Backpack', 'Anti-theft and Waterproof Backpack (Violet)\r\n Color: Violet\r\n Material: Waterproof nylon\r\n Features: Anti-theft design, USB charging port\r\n Capacity: Fits a 15.6\" laptop', 179.00, 100, 2, 'IMG_9127.jpeg', '2024-12-03 02:01:20', NULL),
(11, 'Blue Backpack', 'Anti-theft and Waterproof Backpack (Blue)\r\n Color: Blue\r\n Material: Waterproof nylon\r\n Features: Anti-theft design, USB charging port\r\n Capacity: Fits a 15.6\" laptop', 179.00, 100, 2, 'IMG_9151.jpeg', '2024-12-03 02:02:05', NULL),
(12, 'Beige Backpack', 'Anti-theft and Waterproof Backpack (Beige)\r\n Color: Beige\r\n Material: Waterproof nylon\r\n Features: Anti-theft design, USB charging port\r\n Capacity: Fits a 15.6\" laptop', 179.00, 100, 2, 'IMG_9149.jpeg', '2024-12-03 02:02:48', NULL),
(13, 'Pencil Case Pouch (Green)', 'Color: Green\r\n Material: PU leather\r\n Features: Durable and waterproof\r\n Capacity: Fits up to 20 pens or pencils', 114.00, 100, 2, 'IMG_9073.jpeg', '2024-12-03 02:03:50', NULL),
(14, 'Pencil Case Pouch (Brown)', 'Color: Brown\r\n Material: PU leather\r\n Features: Durable and waterproof\r\n Capacity: Fits up to 20 pens or pencils', 114.00, 100, 2, 'IMG_9079.jpeg', '2024-12-03 02:04:50', NULL),
(15, '24 Colors Acrylic Paint', '24 Multiple Colors 100ml Acrylic Paint\r\n Colors: 24 vibrant colors\r\n Volume: 100ml per tube\r\n Material: Non-toxic acrylic paint\r\n Features: Fast-drying and water-resistant', 235.00, 100, 3, 'IMG_9297.jpeg', '2024-12-03 02:06:28', NULL),
(16, '15 Colors Glitter', '15pcs Glitters Metallic Color\r\n Quantity: 15pcs\r\n Material: Fine metallic glitter\r\n Uses: DIY crafts, nail art, and decoration', 155.00, 100, 3, 'IMG_9300.jpeg', '2024-12-03 02:07:57', NULL),
(17, 'Pink Cutter', 'Portable Cute Retractable Cutter (Pink)\r\n Color: Pink\r\n Material: Plastic body, stainless steel blade\r\n Features: Compact and retractable design\r\n Size: Pocket-friendly', 35.00, 100, 4, 'IMG_9285.jpeg', '2024-12-03 02:09:27', NULL),
(18, 'White Cutter', 'Portable Cute Retractable Cutter (White)\r\n Color: White\r\n Material: Plastic body, stainless steel blade\r\n Features: Compact and retractable design\r\n Size: Pocket-friendly', 35.00, 100, 4, 'IMG_9286.jpeg', '2024-12-03 02:10:04', NULL),
(19, 'Black Cutter', 'Portable Cute Retractable Cutter (Black)\r\n Color: Black\r\n Material: Plastic body, stainless steel blade\r\n Features: Compact and retractable design\r\n Size: Pocket-friendly', 35.00, 100, 4, 'IMG_9287.jpeg', '2024-12-03 02:10:53', NULL),
(20, 'Scissors', 'Stainless Steel White Scissor\r\n Color: White\r\n Material: Stainless steel blades, plastic handles\r\n Size: 8 inches\r\n Features: Sharp and durable for everyday use', 29.00, 100, 4, 'IMG_9001.jpg', '2024-12-03 02:12:53', NULL),
(21, '12-digit Calculator (White)', 'Color: White\r\nSize: 11cm*7cm\r\nQuality: Plastic\r\nElectricity: AA Battery\r\nSales Point 1: 12 Digits\r\nSales Point 2: Clear Display', 114.00, 100, 5, 'IMG_9179.jpeg', '2024-12-03 02:14:40', '2024-12-03 13:57:51'),
(22, 'Scientific Calculator (Pink)', 'Color: Pink\r\nBrand: Casio\r\nDisplay Type: 62 x 192 Full Dots\r\nLogic Input Algebraic: Natural V.P.A.M.\r\nNumber of Characters/Lines: 16/1+10/1\r\nBasic Mathematics\r\nFunctions: 55\r\nScientific Constants: 40', 234.00, 100, 5, 'IMG_9180.jpeg', '2024-12-03 02:15:58', '2024-12-03 13:58:14'),
(23, '120pcs Paper Clip Set Multifunctional', 'Color: Multiple Color\r\nMetal Paper Clip\r\nSize: 28mm', 29.00, 100, 6, 'IMG_9107.jpeg', '2024-12-03 02:18:38', '2024-12-03 13:59:19'),
(24, '50pcs Binder Clip Set Multifunctional', 'Color:  Multiple Color\r\nMetal Binder Clips\r\nSize: 19mm - can hold 75 sheets\r\n', 35.00, 100, 6, 'IMG_9131.jpeg', '2024-12-03 02:19:34', '2024-12-03 13:59:41'),
(25, 'Double Hole Metal Puncher (Black)', 'Color: Black\r\nCapacity: 10 sheets of paper\r\nSize: approx. 4x2x1.6 inches\r\nWeight: approx 110g\r\nHole Distance: 70mm', 55.00, 100, 6, 'IMG_9276.jpeg', '2024-12-03 02:20:53', '2024-12-03 14:01:37'),
(26, 'Double Hole Metal Puncher (White)', 'Color: White\r\nCapacity: 10 sheets of paper\r\nSize: approx. 4x2x1.6 inches\r\nWeight: approx 110g\r\nHole Distance: 70mm', 55.00, 100, 6, 'IMG_9277.jpeg', '2024-12-03 02:21:27', '2024-12-03 14:01:56'),
(27, 'Double Hole Metal Puncher (Violet)', 'Color: Violet\r\nCapacity: 10 sheets of paper\r\nSize: approx. 4x2x1.6 inches\r\nWeight: approx 110g\r\nHole Distance: 70mm', 55.00, 100, 6, 'IMG_9278.jpeg', '2024-12-03 02:22:27', '2024-12-03 14:02:18'),
(28, 'Portable Mini Stapler (Black)', 'Color: Black\r\nType: Mini Stapler\r\nNet Weight: 18g\r\nSize: 5*3.5*2.2cm\r\nIt can bind 15 sheets of paper \r\nMaterial: Stainless Steel', 35.00, 100, 6, 'IMG_9188.jpeg', '2024-12-03 02:23:15', '2024-12-03 14:03:27'),
(29, 'Portable Mini Stapler (Violet)', 'Color: Violet\r\nType: Mini Stapler\r\nNet Weight: 18g\r\nSize: 5*3.5*2.2cm\r\nIt can bind 15 sheets of paper \r\nMaterial: Stainless Steel', 35.00, 100, 6, 'IMG_9189.jpeg', '2024-12-03 02:23:55', '2024-12-03 14:02:56'),
(30, 'Portable Mini Stapler (Pink)', 'Color: Pink\r\nType: Mini Stapler\r\nNet Weight: 18g\r\nSize: 5*3.5*2.2cm\r\nIt can bind 15 sheets of paper \r\nMaterial: Stainless Steel', 35.00, 100, 6, 'IMG_9190.jpeg', '2024-12-03 02:24:22', '2024-12-03 14:02:36'),
(31, 'Transparent Plastic Ruler', 'Material: Plastic\r\nSize: 40*11.5*5.5cm\r\nWeight: 47g\r\nThe 30cm Combo Set include:\r\n30cm ruler (30.7x2.8/11.5g)\r\n180 degree protractor(10cm)\r\n45 degree triangle ruler (13cm)\r\n60 degree triangle ruler (18cm)\r\nFeatures: 4 types of ruler in a set', 50.00, 100, 7, 'IMG_9204.jpeg', '2024-12-03 12:02:46', '2024-12-03 14:05:24'),
(32, 'Tape Measure (Pink)', 'Tape Measure/Body Measuring/Tailoring Measuring Tool (Pink)\r\nColor: Pink\r\nLength: 60 inches\r\nSize: 150x1cm \r\nMaterial: Plastic', 14.00, 100, 7, 'IMG_9279.jpeg', '2024-12-03 12:03:50', '2024-12-03 14:06:02'),
(33, 'Tape Measure (White)', 'Tape Measure/Body Measuring/Tailoring Measuring Tool (White)\r\nColor: White\r\nLength: 60 inches\r\nSize: 150x1cm \r\nMaterial: Plastic', 14.00, 100, 7, 'IMG_9280.jpeg', '2024-12-03 12:04:26', '2024-12-03 14:06:18'),
(34, 'Tape Measure (Yellow)', 'Tape Measure/Body Measuring/Tailoring Measuring Tool (Yellow)\r\nColor: Yellow\r\nLength: 60 inches\r\nSize: 150x1cm \r\nMaterial: Plastic\r\n', 14.00, 100, 7, 'IMG_9281.jpeg', '2024-12-03 12:05:40', '2024-12-03 14:06:29'),
(35, 'Tape Measure (Black)', 'Tape Measure/Body Measuring/Tailoring Measuring Tool (Black)\r\nColor: Black\r\nLength: 60 inches\r\nSize: 150x1cm \r\nMaterial: Plastic\r\n', 14.00, 100, 7, 'IMG_9282.jpeg', '2024-12-03 12:06:15', '2024-12-03 14:06:41'),
(36, 'A5 Spiral Notebook 80 sheets (Peach)', 'Color: Peach\r\nSize: A5\r\nThickness: 90Gsm\r\nWeight: 350g\r\nLeaves: 80 sheets\r\nMaterial: Beige Daolin Paper', 55.00, 100, 8, 'IMG_9198.jpeg', '2024-12-03 12:07:59', '2024-12-03 14:10:12'),
(37, 'A5 Spiral Notebook 80 sheets (White)', 'Color: White\r\nSize: A5\r\nThickness: 90Gsm\r\nWeight: 350g\r\nLeaves: 80 sheets\r\nMaterial: Beige Daolin Paper\r\n', 55.00, 100, 8, 'IMG_9199.jpeg', '2024-12-03 12:08:24', '2024-12-03 14:11:00'),
(38, 'A5 Spiral Notebook 80 sheets (Violet)', 'Color: Violet\r\nSize: A5\r\nThickness: 90Gsm\r\nWeight: 350g\r\nLeaves: 80 sheets\r\nMaterial: Beige Daolin Paper\r\n', 55.00, 100, 8, 'IMG_9200.jpeg', '2024-12-03 12:09:09', '2024-12-03 14:10:51'),
(39, 'Planner (Brown)', 'Weekly/Monthly Planner Sheet (Brown)\r\nColor: Brown\r\nSize: 22*15cm\r\nEstimated Weight:193g\r\nMaterial: Paper\r\nNo. of Sheet: 60 sheets\r\nTypes Available: Weekly/Monthly\r\nLanguage: English', 49.00, 100, 8, 'IMG_9065.png', '2024-12-03 13:29:12', '2024-12-03 13:37:48'),
(40, 'Planner (Black)', 'Weekly/Monthly Planner Sheet (Black)\r\nColor: Black\r\nSize: 22*15cm\r\nEstimated Weight:193g\r\nMaterial: Paper\r\nNo. of Sheet: 60 sheets\r\nTypes Available: Weekly/Monthly\r\nLanguage: English', 49.00, 100, 8, 'IMG_9066.png', '2024-12-03 13:29:52', '2024-12-03 13:38:19'),
(41, 'Planner (Pink)', 'Weekly/Monthly Planner Sheet (Pink)\r\nColor: Pink\r\nSize: 22*15cm\r\nEstimated Weight:193g\r\nMaterial: Paper\r\nNo. of Sheet: 60 sheets\r\nTypes Available: Weekly/Monthly\r\nLanguage: English', 49.00, 100, 8, 'IMG_9067.png', '2024-12-03 13:30:27', '2024-12-03 13:37:15'),
(42, 'Planner (Red)', 'Weekly/Monthly Planner Sheet (Red)\r\nColor: Red\r\nSize: 22*15cm\r\nEstimated Weight:193g\r\nMaterial: Paper\r\nNo. of Sheet: 60 sheets\r\nTypes Available: Weekly/Monthly\r\nLanguage: English\r\n', 49.00, 100, 8, 'IMG_9069.png', '2024-12-03 13:31:00', '2024-12-03 13:37:29'),
(43, 'Short Brown Envelope', 'Size: 9x12 inches\r\nGreat for mailing greeting cards and documents or just simply organizing your files', 5.00, 1000, 9, 'IMG_9258.jpeg', '2024-12-03 13:47:19', NULL),
(44, 'Long Clipboard with File Cover (Pink)', 'Color: Pink\r\nSize: 23x35cm\r\nType: Clip File Folder\r\nFunction: Collect documents, Folder writing pad\r\nFastener Material: Metal\r\nFastener Type: Low Profile Clip, Single/Double Clip', 70.00, 100, 9, 'IMG_9181.jpeg', '2024-12-03 13:48:51', NULL),
(45, 'Long Clipboard with File Cover (White)', 'Color: White\r\nSize: 23x35cm\r\nType: Clip File Folder\r\nFunction: Collect documents, Folder writing pad\r\nFastener Material: Metal\r\nFastener Type: Low Profile Clip, Single/Double Clip', 70.00, 100, 9, 'IMG_9182.jpeg', '2024-12-03 13:49:31', NULL),
(46, 'Long Clipboard with File Cover (Black)', 'Color: Black\r\nSize: 23x35cm\r\nType: Clip File Folder\r\nFunction: Collect documents, Folder writing pad\r\nFastener Material: Metal\r\nFastener Type: Low Profile Clip, Single/Double Clip', 70.00, 100, 9, 'IMG_9187.jpeg', '2024-12-03 13:50:04', NULL),
(47, 'Long Folder (Violet)', 'Color: Violet\r\nSize: 8.5 x 13 inches\r\nMaterial: Cardboard', 8.00, 100, 9, 'IMG_9270.jpeg', '2024-12-03 13:51:11', NULL),
(48, 'Long Folder (Green)', 'Color: Green\r\nSize: 8.5 x 13 inches\r\nMaterial: Cardboard', 8.00, 100, 9, 'IMG_9271.jpeg', '2024-12-03 13:51:44', NULL),
(49, 'Long Folder (Blue)', 'Color: Blue\r\nSize: 8.5 x 13 inches\r\nMaterial: Cardboard', 8.00, 100, 9, 'IMG_9274.jpeg', '2024-12-03 13:52:51', NULL),
(50, 'Long Folder (Brown)', 'Color: Brown\r\nSize: 8.5 x 13 inches\r\nMaterial: Cardboard', 8.00, 100, 9, 'IMG_9275.jpeg', '2024-12-03 13:54:59', NULL),
(51, 'Plastic Envelope (Green)', 'Color: Green\r\nThickness: 18 wire (0.18mm)\r\nSize: 32.7 x 23.5cm\r\nStyle: A4 Plastic File Bag', 5.00, 100, 9, 'IMG_9255.jpeg', '2024-12-03 14:18:26', NULL),
(52, 'Plastic Envelope (Blue)', 'Color: Blue\r\nThickness: 18 wire (0.18mm)\r\nSize: 32.7 x 23.5cm\r\nStyle: A4 Plastic File Bag', 5.00, 100, 9, 'IMG_9256.jpeg', '2024-12-03 14:18:50', NULL),
(53, 'Plastic Envelope (Red)', 'Color: Red\r\nThickness: 18 wire (0.18mm)\r\nSize: 32.7 x 23.5cm\r\nStyle: A4 Plastic File Bag', 5.00, 100, 9, 'IMG_9257.jpeg', '2024-12-03 14:20:42', NULL),
(54, 'Plastic Envelope (Transparent)', 'Product: Transparent Envelope\r\nThickness: 18 wire (0.18mm)\r\nSize: 32.7 x 23.5cm\r\nStyle: A4 Plastic File Bag', 5.00, 100, 9, 'IMG_9260.jpeg', '2024-12-03 14:21:15', NULL),
(55, 'Binder Notebook (Black)', 'Color: Black\r\nSize: 315*280*55mm\r\nPaper Size: A9 (1.5 x 2 inches\r\nCan hold up 325 sheets', 78.00, 100, 9, 'IMG_9183.jpeg', '2024-12-03 14:22:24', NULL),
(56, 'Binder Notebook (White)', 'Color: White\r\nSize: 315*280*55mm\r\nPaper Size: A9 (1.5 x 2 inches)\r\nCan hold up 325 sheets', 78.00, 100, 9, 'IMG_9184.jpeg', '2024-12-03 14:22:56', NULL),
(57, 'Binder Notebook (Pink)', 'Color: Pink\r\nSize: 315*280*55mm\r\nPaper Size: A9 (1.5 x 2 inches)\r\nCan hold up 325 sheets', 78.00, 100, 9, 'IMG_9185.jpeg', '2024-12-03 14:23:35', NULL),
(58, 'Binder Notebook (Violet)', 'Color: Violet\r\nSize: 315*280*55mm\r\nPaper Size: A9 (1.5 x 2 inches)\r\nCan hold up 325 sheets', 78.00, 100, 9, 'IMG_9186.jpeg', '2024-12-03 14:24:11', NULL),
(59, 'Bond Paper A4 Sheets 500pcs', 'Size: A4 (8.27 x 11.69 inches)\r\nSheets: 500 sheets\r\nPurpose: For school, office, and art use', 170.00, 100, 10, 'IMG_9254.jpeg', '2024-12-04 00:16:38', NULL),
(60, 'Colored Paper A4 Sheets 500pcs', 'Color: Multiple Color\r\nSize: A4 (8.27 x 11.69 inches)\r\nSheets: 500 sheets\r\nPurpose: For school, office, and art use', 215.00, 100, 10, 'IMG_9249.jpeg', '2024-12-04 00:17:09', NULL),
(61, 'White Index Card 3x5 100 sheets', 'Color: White\r\nSize: 3 x 5 inches\r\nItem sold per pack: 100 sheets per pack', 28.00, 100, 10, 'IMG_9207.jpeg', '2024-12-04 00:19:09', NULL),
(62, 'Assorted Colored Index Card 3x5 100 sheets', 'Color: Pink, Blue, Yellow, Green\r\nSize: 3 x 5 inches\r\nItem sold per pack: 50 sheets per color', 100.00, 100, 10, 'IMG_9295.jpeg', '2024-12-04 00:21:18', NULL),
(63, 'Intermediate Pad Paper 80 leaves', 'Color: White\r\nSize: 216 x 279mm \r\nLine: 28 lines\r\nSheets: 80', 25.00, 100, 10, 'IMG_9209.jpeg', '2024-12-04 00:23:08', NULL),
(64, 'Yellow Pad Paper 80 leaves', 'Color: Yellow\r\nSize: 215 x 330mm\r\nLine: 33 lines\r\nSheets: 80', 30.00, 100, 10, 'IMG_9208.jpeg', '2024-12-04 00:23:47', NULL),
(65, 'Colorful Post It Note Super Sticky Stationary', 'Color: Multiple Color\r\nSize: 76 x 76mm\r\nSheets: 100', 49.00, 100, 10, 'IMG_9093.jpeg', '2024-12-04 00:25:04', NULL),
(66, 'Faber-Castell Correction Tape (Black)', 'Color: Black\r\nSize: 4.6*9.5*1.5cm\r\nLength: 72m \r\nMaterial: Paper-based tape', 7.00, 100, 11, 'IMG_9191.jpeg', '2024-12-04 00:27:46', NULL),
(67, 'Faber-Castell Correction Tape (Pink)', 'Color: Pink\r\nSize: 4.6*9.5*1.5cm\r\nLength: 72m \r\nMaterial: Paper-based tape', 7.00, 100, 11, 'IMG_9192.jpeg', '2024-12-04 00:28:26', NULL),
(68, 'Replaceable Eraser Refill (Violet)', 'Color: Violet\r\nSize: 55 x 18mm\r\nMaterial: TPR', 6.00, 100, 11, 'IMG_9154.jpeg', '2024-12-04 00:30:17', NULL),
(69, '6pcs Deli Highlighter Pen Multiple Color', 'Color: Multiple Color\r\nSize: 115 x 25mm\r\nPigmented Ink, Clean and quick dry\r\nPackage: 6pcs/6 colors\r\nPen Size: 4mm', 49.00, 100, 11, 'IMG_9139.jpeg', '2024-12-04 00:40:06', NULL),
(70, 'HB Pencil (Black) 1 pc.', 'Color: Black\r\nSize:19 x 0.63cm\r\nQuality: Graphite Pencil\r\nMulti Purpose', 6.00, 100, 11, 'IMG_9201.jpeg', '2024-12-04 00:41:49', NULL),
(71, 'HB Pencil (Yellow) 1pc.', 'Color: Yellow\r\nSize:19 x 0.63cm\r\nQuality: Graphite Pencil\r\nMulti Purpose', 6.00, 100, 11, 'IMG_9202.jpeg', '2024-12-04 00:42:46', NULL),
(72, 'Cute and Aesthetic Deli Gel Pens (Black)', 'Color: Black\r\nSize: 147 x 10mm\r\nQuick dry black ink (16g)', 14.00, 100, 11, 'IMG_9164.jpeg', '2024-12-04 00:52:24', NULL),
(73, 'Cute and Aesthetic Deli Gel Pens (Gray)', 'Color: Gray\r\nSize: 147 x 10mm\r\nQuick dry black ink (16g)', 14.00, 100, 11, 'IMG_9161.jpeg', '2024-12-04 00:53:15', NULL),
(74, 'Cute and Aesthetic Deli Gel Pens (Green)', 'Color: Green\r\nSize: 147 x 10mm\r\nQuick dry black ink (16g)', 14.00, 100, 11, 'IMG_9160.jpeg', '2024-12-04 00:53:52', NULL),
(75, 'Cute and Aesthetic Deli Gel Pens (Blue)', 'Color: Blue\r\nSize: 147 x 10mm\r\nQuick dry black ink (16g)', 14.00, 100, 11, 'IMG_9158.jpeg', '2024-12-04 00:54:53', NULL),
(76, 'Cute and Aesthetic Deli Gel Pens (Pink)', 'Color: Pink\r\nSize: 147 x 10mm\r\nQuick dry black ink (16g)', 14.00, 100, 11, 'IMG_9159.jpeg', '2024-12-04 00:55:32', NULL),
(77, 'Cute and Aesthetic Deli Gel Pens (Brown)', 'Color: Brown\r\nSize: 147 x 10mm\r\nQuick dry black ink (16g)', 14.00, 100, 11, 'IMG_9165.jpeg', '2024-12-04 00:56:04', NULL),
(78, 'Faber-Castell Pencil Sharpener (White)', 'Color: White\r\nSharpener Size: 4*2.8*6.3cm\r\nSharpening Angle: 22-24 degrees\r\nType: 1 Hole', 9.00, 100, 11, 'IMG_9195.jpeg', '2024-12-04 00:56:50', NULL),
(79, 'Faber-Castell Pencil Sharpener (Violet)', 'Color: Violet\r\nSharpener Size: 4*2.8*6.3cm\r\nSharpening Angle: 22-24 degrees\r\nType: 1 Hole', 9.00, 100, 11, 'IMG_9196.jpeg', '2024-12-04 00:57:18', NULL),
(80, 'Faber-Castell Pencil Sharpener (Pink)', 'Color: Pink\r\nSharpener Size: 4*2.8*6.3cm\r\nSharpening Angle: 22-24 degrees\r\nType: 1 Hole', 9.00, 100, 11, 'IMG_9197.jpeg', '2024-12-04 00:57:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `cart_id` int UNSIGNED NOT NULL,
  `customer_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `customer_id` int UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`customer_id`, `first_name`, `last_name`, `email`, `username`, `password`, `phone_number`, `address`, `created_at`, `updated_at`) VALUES
(1, 'James', 'Fanio', 'jefanio718@gmail.com', 'James718', '$2y$10$AB7575u/lVdn8b/PmwMNhOI6PCERv7nnlCs5hAwM/tI20vKbkzvka', '09999999999', '1712 Bulacan St. Sta. Cruz, Manila', '2024-12-04 17:15:39', '2024-12-04 19:09:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_token` (`order_token`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `cart_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `customer_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_order` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `fk_customer_cart` FOREIGN KEY (`customer_id`) REFERENCES `users` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_cart` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
