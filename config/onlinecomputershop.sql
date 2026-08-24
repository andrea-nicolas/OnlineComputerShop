-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 04:31 PM
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
-- Database: `onlinecomputershop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `category_id`, `created_at`) VALUES
(2, 'Intel', 9, '2026-08-22 19:04:56'),
(3, 'AMD', 9, '2026-08-22 19:04:56'),
(4, 'ASUS', 10, '2026-08-22 19:06:37'),
(5, 'Sapphire', 10, '2026-08-22 19:06:37'),
(6, 'Colorful', 10, '2026-08-22 19:06:37'),
(7, 'Powercolor', 10, '2026-08-22 19:06:37'),
(8, 'NVIDIA', 10, '2026-08-22 19:06:37'),
(9, 'ASUS', 11, '2026-08-22 19:08:09'),
(10, 'Colorful', 11, '2026-08-22 19:08:09'),
(11, 'GIGABYTE', 11, '2026-08-22 19:08:09'),
(12, 'MSI', 11, '2026-08-22 19:08:09'),
(13, 'Colorful', 12, '2026-08-22 19:09:36'),
(14, 'Corsair', 12, '2026-08-22 19:09:36'),
(15, 'Kingston', 12, '2026-08-22 19:09:36'),
(16, 'V-color', 12, '2026-08-22 19:09:36'),
(17, 'Toshiba', 13, '2026-08-22 19:10:48'),
(18, 'Seagate', 13, '2026-08-22 19:10:48'),
(19, 'Transcend', 14, '2026-08-22 19:10:48'),
(20, 'Corsair', 14, '2026-08-22 19:10:48'),
(21, 'GIGABYTE', 14, '2026-08-22 19:10:48'),
(22, 'Colorful', 14, '2026-08-22 19:10:48'),
(23, 'KINGSTON', 14, '2026-08-22 19:10:48'),
(24, 'Corsair', 15, '2026-08-22 19:12:40'),
(25, 'ASUS', 15, '2026-08-22 19:12:40'),
(26, 'GIGABYTE', 15, '2026-08-22 19:12:40'),
(27, 'MSI', 15, '2026-08-22 19:12:40'),
(28, 'Gamdias', 15, '2026-08-22 19:12:40'),
(29, 'Gamdias', 16, '2026-08-22 19:12:40'),
(30, 'Corsair', 16, '2026-08-22 19:12:40'),
(31, 'GIGABYTE', 15, '2026-08-22 19:12:40'),
(32, 'ASUS', 16, '2026-08-22 19:12:40'),
(33, 'MSI', 16, '2026-08-22 19:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `created_at`) VALUES
(9, 'Processor', NULL, '2026-08-22 18:57:19'),
(10, 'Graphics Card', NULL, '2026-08-22 18:57:19'),
(11, 'Motherboard', NULL, '2026-08-22 18:57:19'),
(12, 'RAM', NULL, '2026-08-22 18:57:19'),
(13, 'HDD', NULL, '2026-08-22 18:57:19'),
(14, 'SDD', NULL, '2026-08-22 18:57:19'),
(15, 'Casing', NULL, '2026-08-22 18:57:19'),
(16, 'PSU', NULL, '2026-08-22 18:57:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,0) NOT NULL,
  `payment_method` enum('cash','card','bkash') NOT NULL,
  `status` enum('pending','processing','cancelled','completed') NOT NULL,
  `order_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `manufacturer_review` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(10) NOT NULL,
  `brand_id` int(10) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `stock` int(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `manufacturer_review`, `price`, `category_id`, `brand_id`, `image_path`, `stock`, `created_at`) VALUES
(3, 'AMD Ryzen 9 9950X3D Gaming Processor', 'Model: Ryzen 9 9950X3D (Tray Box)\r\nClock Speed: 4.3GHz Up to 5.7GHz\r\nCores: 16; Threads: 32\r\nCache: L1 : 1280KB; L2 : 16MB; L3 : 128MB\r\nCPU Socket: AM5', 'The AMD Ryzen 9 9950X3D CPU is a cutting-edge AMD Gaming CPU that provides outstanding performance to gamers, creators, and professionals alike. Powered by AMD\'s innovative Zen 5 architecture and constructed on a breakthrough 4nm process, this AMD Ryzen 9 Processor has 16 cores and 32 threads, making it a powerhouse for multitasking and performing demanding workloads with ease.', 78500.00, 9, 3, '../assets/ryzen-9-9950x3d-01-500x500.webp', 5, '2026-08-22 19:51:09'),
(4, 'Intel Core Ultra 9 285K Arrow Lake Processor', 'Model: Core Ultra 9 285K (Tray Box)\r\nClock Speed: 3.2GHz up to 5.7GHz\r\nCache: 36 MB, Socket: LGA1851\r\nCPU Cores: 24, CPU Threads: 24\r\nNPU: Intel AI Boost', 'The Intel Core Ultra 9 285K Arrow Lake Processor is a top-tier CPU designed to handle the most demanding computing tasks with ease. Featuring 24 cores, including 8 Performance cores and 16 Efficient cores, this processor operates at a base clock speed of 3.7 GHz and can boost up to an impressive 5.7 GHz, ensuring exceptional performance across a wide range of applications.', 64500.00, 9, 2, '../assets/tuf-gaming-geforce-rtx-5090-oc-edition-01-500x500.webp', 10, '2026-08-22 19:51:09'),
(5, 'ASUS TUF Gaming GeForce RTX 5090 32GB GDDR7 OC Edition Graphics Card', 'MPN: TUF-RTX5090-O32G-GAMING\r\nModel: TUF Gaming GeForce RTX 5090 32GB GDDR7 OC Edition\r\nVideo Memory: 32GB GDDR7, CUDA Core: 21760\r\nMemory Speed: 28 Gbps, Memory Interface: 512-bit\r\nEngine Clock: 2550 MHz(Boost clock), OC Mode: 2580 MHz\r\nOutput Ports: 2x Native HDMI 2.1b, 3x Native DisplayPort 2.1a', 'The ASUS TUF Gaming RTX 5090 OC Edition Graphics Card represents the peak of high-performance graphics technology, combining NVIDIA\'s innovative Blackwell architecture with superb engineering to provide next-generation gaming and creative computing. ', 75000.00, 10, 4, '../assets/rtx-5090-32g-lightning-z-01-500x500.webp', 0, '2026-08-22 20:00:34'),
(6, 'Sapphire NITRO+ AMD Radeon RX 9070 XT 16GB Gaming OC GDDR6 Graphics Card', 'MPN: 11348-01-20G\r\nModel: NITRO+ AMD Radeon RX 9070 XT\r\nBoost Clock: Up to 3060 MHz, Game Clock: Up to 2520 MHz\r\nMemory: 16GB/ 256 bit GDDR6, 20 Gbps Effective\r\nStream Processors: 4096, AMD RDNA 4 Architecture\r\nOutput Ports: 2x HDMI, 2x DisplayPort 2.1a', 'The Sapphire NITRO+ AMD Radeon RX 9070 XT 16GB GDDR6 Graphics Card is a powerful graphics solution designed for gamers and content creators. Powered by the AMD RDNA 4 Architecture, this AMD RX 9070 graphics card has a boost clock of up to 3060 MHz and a game clock of up to 2520 MHz, resulting in remarkable performance and efficiency. With 4096 stream processors, 64 compute units, and 64 ray accelerators, the Sapphire NITRO+ AMD Radeon RX 9070 XT Graphics Card can handle demanding workloads while providing seamless, high-quality images.', 135900.00, 10, 5, '../assets/nitro-plus-amd-radeon-rx-9070-xt-01-500x500.webp', 3, '2026-08-22 20:00:34'),
(7, 'Colorful iGame GeForce RTX 5080 Neptune OC 16GB-V GDDR7 Graphics Card', 'Model: iGame GeForce RTX 5080 Neptune OC 16GB-V\r\nMemory Clock: 30 Gbps, Memory Bus: 256 bit\r\nCore Clock Base: 2295Mhz; Boost:2617Mhz\r\nOne-Key OC Base: 2295Mhz; Boost:2695Mhz\r\nOutput Ports: 1x HDMI 2.1b, 3x DisplayPort 2.1b', 'The colorful iGame GeForce RTX 5080 Neptune OC 16GB-V GDDR7 Graphics Card is a high-performance GPU designed for gamers and professionals. With 10752 CUDA cores and a base clock of 2295 MHz, which can be increased to 2617 MHz, this graphics card provides remarkable speed and efficiency.', 259000.00, 10, 6, '../assets/igame-geforce-rtx-5080-neptune-oc-16gb-v-01-500x500.webp', 8, '2026-08-22 20:00:34'),
(8, 'PowerColor Hellhound Spectral White AMD Radeon RX 9070 XT 16GB GDDR6 Graphics Card', 'MPN: RX9070XT 16G-L/OC/WHITE\r\nModel: Hellhound Spectral White AMD Radeon RX 9070 XT 16GB GDDR6\r\nEngine Clock: Up to 2460MHz(Game)/ 3010MHz(Boost)\r\nMemory Speed: 20.0 Gbps, Memory Interface: 256-bit\r\nStream Processors: 4096 Units, AMD RDNA 4 Architecture\r\nOutput Ports: 1 x HDMI 2.1b, 3 x DisplayPort 2.1b', 'The PowerColor Hellhound Spectral White AMD Radeon RX 9070 XT Graphics Card features 4096 stream processors that ensure smooth rendering and efficient parallel processing performance. ', 120000.00, 10, 7, '../assets/hellhound-spectral-white-amd-radeon-rx-9070-xt-500x500.webp', 1, '2026-08-22 20:00:34'),
(9, 'NVIDIA H200 NVL PCIE 141GB HBM3e Graphics Card', 'Model: H200 NVL\r\n141GB of HBM3e GPU memory\r\n4.8TB/s of memory bandwidth\r\n4 petaFLOPS of FP8 performance\r\n2X LLM inference performance', 'The NVIDIA H200 NVL PCIE 141GB HBM3e Graphics Card delivers exceptional computing power for AI, deep learning, and high performance computing environments.', 11000.00, 10, 8, '../assets/h200-nvl-500x500.webp', 0, '2026-08-22 20:00:34'),
(10, 'ASUS PRIME H510M-F R3.0 LGA1200 Micro-ATX Motherboard', 'Model: PRIME H510M-F R3.0\r\nSupported CPU: 11th and 10th Gen Intel Processors (LGA1200)\r\nSupported RAM: 2x DDR4, Max 64GB 3200(O.C.)\r\nGraphics Output: 1x HDMI port\r\nFeatures: 1 x Realtek 1Gb Ethernet', 'The ASUS PRIME H510M-F R3.0 is a powerful and versatile Intel H470 (LGA1200) micro-ATX motherboard, designed to fully unleash the potential of 11th and 10th-generation Intel processors.', 10300.00, 11, 9, '../assets/prime-h510m-f-r3-0-01-500x500.webp', 4, '2026-08-22 20:12:09'),
(11, 'Colorful BATTLE-AX H610M-E WIFI V20 mATX Motherboard', 'Model: BATTLE-AX H610M-E WIFI V20\r\nSupported CPU: 14th/13th/12th Gen Intel Processors (LGA1700)\r\nSupported RAM: 2x DDR4, Max 64GB 3200(OC)\r\nGraphics Output: 1×VGA, 1x HDMI\r\nFeatures: RTL8111H Gbit network card, WiFi 5', 'The colorful BATTLE-AX H610M-E WIFI V20 Motherboard is a high-performance motherboard built to satisfy current computing demands, with a bright and dynamic design and innovative functions.', 10200.00, 11, 10, '../assets/battle-ax-h610m-e-wifi-v20-01-500x500.webp', 23, '2026-08-22 20:12:09'),
(12, 'GIGABYTE GA-H81M-H 4th Gen Micro ATX Motherboard', 'Model: GA-H81M-H\r\nSupported CPU: 4th Generation Intel Core processors (LGA1150)\r\nSupported RAM: 2 x 1.5V DDR3 DIMM up to 16 GB 1600MHz\r\nGraphics Output: 1 x D-Sub, 1 x HDMI\r\nFeatures: LAN with high ESD Protection', 'The GIGABYTE GA-H81M-H Motherboard is a powerful and feature-rich platform designed to support 4th Generation Intel Core processors.', 8800.00, 11, 11, '../assets/ga-h81m-h-01-500x500.webp', 2, '2026-08-22 20:12:09'),
(13, 'MSI A520M-A Pro AM4 AMD Micro-ATX Motherboard', 'Model: MSI A520M-A Pro\r\nAMD Ryzen 5000/4000G Series & AMD Ryzen 3rd Generation Processors\r\nPCB with 2OZ Thickened Copper\r\nSupports up to 4600(OC) MHz RAM\r\nCore Boost, Turbo M.2 & Audio Boost', 'PRO series helps users work smarter by delivering an efficient and productive experience. Featuring stable functionality and high-quality assembly, PRO series motherboards provide not only optimized professional workflows but also less troubleshooting and longevity.', 6800.00, 11, 12, '../assets/a520m-a-pro-500x500.jpg', 1, '2026-08-22 20:12:09'),
(14, 'Colorful Battle-AX DDR4 8GB 3200MHz Desktop RAM', 'MPN: BA08G3200D4ZP16B\r\nModel: Battle-AX DDR4\r\nMemory Capacity: 8GB\r\nMemory Type: DDR4\r\nMemory Frequency: 3200 MHz\r\nWorking Voltage: 1.35V', 'The Colorful Battle-AX DDR4 8GB 3200MHz Desktop RAM is a dependable and high-performance memory module that will significantly increase the speed and responsiveness of your desktop PC.', 6700.00, 12, 13, '../assets/battle-ax-ddr4-black-500x500.webp', 23, '2026-08-22 20:16:47'),
(15, 'Corsair Vengeance LPX 8GB 3200MHz DDR4 Desktop RAM', 'MPN: CMK8GX4M1E3200C16\r\nModel: Vengeance LPX 8GB\r\nCapacity 8GB\r\nSpeed: 3200MHz\r\nTested Latency: 16-20-20-38\r\nVoltage: 1.35V', NULL, 8200.00, 12, 14, '../assets/VENG_LPX_BLK_01-500x500.png', 3, '2026-08-22 20:16:47'),
(16, 'Kingston FURY Beast 8GB 3200MHz DDR4 Desktop RAM', 'MPN: KF432C16BB/8\r\nModel: FURY Beast\r\nFrequency: 3200MHz; CAS Latency: 16-18-18\r\nVoltage: 1.35V; Form Factor: UDIMM\r\nOperating Temperature : 0°C to 85°C\r\nDimensions: 133.35mm x 34.1mm x 7.2mm', 'Kingston FURY Beast Desktop RAM comes with 8GB 3200MHz DDR4 capacity. This Kingston FURY Beast RAM provides a powerful performance boost for gaming, video editing and rendering speeds of up to 3200MHz.', 8600.00, 12, 15, '../assets/fury-beast-01-500x500.jpg', 12, '2026-08-22 20:16:47'),
(17, 'V-Color Manta XSky RGB 8GB DDR5 6000MHz CL36 Desktop RAM', 'MPN: TMXSAL8G60836KWK\r\nModel: Manta XSky RGB\r\nCapacity: 8GB, Type: DDR5\r\nFrequency: 6000MHz\r\nCAS Latency: CL36-36-36-96\r\nVoltage: 1.35V, Profile: AMD E.X.P.O', 'The V-Color Manta XSky RGB 8GB DDR5 6000MHz CL36 Desktop RAM compatible with AM5 motherboards. This RAM offers 8GB of capacity in a single module. Built on DDR5 U-DIMM 288 PIN architecture, this DDR5 6000MHz RAM ensures wide platform compatibility. Running at 6000MHz, this V-Color DDR5 RAM handles demanding tasks with ease.', 16800.00, 12, 16, '../assets/manta-xsky-rgb-01-500x500.webp', 32, '2026-08-22 20:16:47'),
(18, 'Toshiba S300 2TB 5400RPM 3.5\" Surveillance Hard Drive', 'MPN: HDWT720UZSVA\r\nModel: Toshiba S300\r\nForm factor: 3.5-inch\r\nSpeed: 5400 RPM\r\nInterface: 6.0 Gbit/s\r\nBuffer Size: 128MB', 'The Toshiba S300 2TB 5400RPM 3.5-inch Surveillance Hard Drive is engineered for continuous operation and reliable performance in security systems.', 14200.00, 13, 17, '../assets/s300-2tb-01-500x500.webp', 0, '2026-08-22 20:21:49'),
(19, 'Seagate SkyHawk 2TB 3.5\" Surveillance Hard Drive', 'Model: ST2000VX017/ST2000VX016\r\nType Internal\r\nCapacity 2TB\r\nInterface SATA 6Gb/s\r\nRPM Class: 5400', 'Seagate SkyHawk 2TB Surveillance Hard Drive - SATA 6Gb/s 64MB Cache 3.5-Inch Internal Drive Custom-built for surveillance applications with Image Perfect firmware for crisp, clear, 24Ã—7 video workloads. Rotational vibration (RV) sensors help maintain performance in RAID and multi-drive systems.', 17000.00, 13, 18, '../assets/2tb-500x500.jpg', 2, '2026-08-22 20:21:49'),
(20, 'Transcend 420S 120GB M.2 SSD', 'MPN: TS120GMTS420S\r\nModel: Transcend 420S\r\nCapacity 120GB\r\nFlash Type 3D NAND flash\r\nInterface SATA III 6Gb/s', 'Transcend\'s ultra-compact SATA III 6Gb/s M.2 SSD 420S addresses the high-performance needs and strict size limitations of small form factor devices, best suited for Ultrabooks and thin, light notebooks.', 4400.00, 14, 19, '../assets/120gb-1-500x500.png', 1, '2026-08-22 20:25:06'),
(21, 'Corsair MP600 PRO XT 1TB M.2 NVMe PCIe Gen. 4 x4 SSD', 'MPN: F1000GBMP600PXT\r\nModel: MP600 PRO XT\r\nInterface: PCIe Gen 4.0 x4\r\nForm Factor M.2 2280\r\nSequential Read: Up to 7,100MB/s\r\nSequential Write: Up to 5,800MB/s', 'The Corsair MP600 PRO XT 1TB M.2 NVMe PCIe Gen. 4 x4 SSD established a new benchmark for high-performance storage. Thanks to its PCIe Gen 4.0 x4 interface, this SSD, which has a stylish M.2 2280 form factor and a sturdy black aluminum heatsink, offers remarkable read and write speeds of up to 7,100MB/s and 5,800MB/s, respectively.', 25500.00, 14, 20, '../assets/mp600-pro-xt-01-500x500.webp', 1, '2026-08-22 20:25:06'),
(22, 'GIGABYTE AORUS Gen4 5000E 1TB M.2 2280 PCIe NVMe SSD', 'MPN: AG450E1TB-G\r\nModel: AORUS Gen4 5000E\r\nCapacity: 1TB, 3D Nand Flash Memory\r\nInterface: PCIe 4.0 x4, NVMe 1.4 interface\r\nR/W Speed: Up to 5,000MB/4,600MB/s\r\nTRIM & S.M.A.R.T supported, Energy-efficient', 'The GIGABYTE AORUS Gen4 5000E 1TB M.2 2280 PCIe NVMe SSD is a high-performance solid-state drive designed for demanding users who require fast and efficient storage. ', 10000.00, 14, 21, '../assets/aorus-gen4-5000e-01-500x500.webp', 2, '2026-08-22 20:27:21'),
(23, 'Colorful CN600 PRO 512GB M.2 NVMe SSD', 'Model: CN600 PRO\r\nCapacity: 512GB\r\nInterface: PCIe3.0\r\nNAND Flash: 3D NAND\r\nRead: 3300MB/s, Write: 2600MB/s', 'The Colorful CN600 PRO 512GB M.2 NVMe SSD is a dynamic storage solution that enhances your computing experience with speed, style, and performance. This SSD is designed to fulfill the needs of current customers who value efficiency as well as aesthetics. It delivers an excellent combination of cutting-edge technology and bright design.', 9500.00, 14, 22, '../assets/cn600-pro-02-500x500.webp', 2, '2026-08-22 20:27:21'),
(24, 'Kingston NV3 500GB M.2 PCIe Gen 4.0 NVMe SSD', 'Model: NV3\r\nInterface: PCIe 4.0 x4 NVMe\r\nStorage Capacity: 500GB\r\nForm factor: M.2 2280\r\nSequential read/write: 5,000/3,00MB/s', 'The Kingston NV3 500GB M.2 PCIe Gen 4.0 NVMe SSD is a high-performance storage solution designed to meet the needs of gamers, content creators, and professionals. ', 14200.00, 14, 23, '../assets/kingston-nv3-1tb-02-500x500.webp', 3, '2026-08-22 20:27:21'),
(30, 'Corsair 480T RGB Airflow Tempered Glass Mid-Tower ATX Casing', 'MPN: CC-9011272-WW\r\nModel: 480T RGB\r\nForm Factor: Mid-Tower\r\nCase Window: Tempered Glass\r\nCase Expansion Slots: 7 horizontal\r\nPre-installed: 3x AR120 RGB fans', 'The CORSAIR 480T RGB Airflow Tempered Glass Mid-Tower ATX Case is a stunning choice for PC enthusiasts and gamers, delivering exceptional cooling capabilities, customizable RGB lighting, and a sleek, spacious design.', 7000.00, 11, 24, '../assets/480t-rgb-01-500x500.webp', 32, '2026-08-22 20:32:27'),
(31, 'ASUS TUF Gaming GT301 Mid-tower ATX Casing', 'Model: TUF Gaming GT301\r\nMotherboard Support: ATX, Micro-ATX & Mini-ITX\r\n2 x 2.5/3.5\" Drive Bays, 4 x 2.5\" Drive Bays, 7 x Expansion Slots\r\nUSB 3.2 Gen 1 Type-A, Audio In/Out\r\n3 x 120mm & 1 x 120mm Fan Pre-installed', 'The ASUS TUF Gaming GT301 Mid-tower Case is designed in a stylish design. The tiny container has a perforated honeycomb front panel to help ventilation and a tempered-glass side panel to display your build\'s internals.', 8400.00, 15, 25, '../assets/tuf-gaming-gt301-01-500x500.jpg', 3, '2026-08-22 20:32:27'),
(32, 'GIGABYTE C102G GLASS Mid Tower Gaming Casing', 'Model: C102G GLASS\r\nMotherboard Support: Mini ITX / Micro ATX\r\nFull-Size Tempered Glass Side Panel\r\nConnectors: 2x USB3.0, Reset Button, HD Audio\r\nPre-installed Fan: 2x 120mm', 'The GIGABYTE C102G GLASS Mid Tower Gaming Casing seamlessly blends style and functionality to enhance your gaming setup. Its optimized airflow design, featuring vented front, top, and partial right-side panels, ensures generous cooling for your components while adding a touch of premium aesthetics.', 4000.00, 15, 26, '../assets/c102g-01-500x500.webp', 23, '2026-08-22 20:32:27'),
(33, 'MSI PRO FORGE M051R ARGB mATX Mid Tower Gaming Casing', 'Model: PRO FORGE M051R\r\nMotherboard Support: Micro-ATX, Mini-ITX\r\nTop and Bottom Dust Filter, Tempered Glass Window\r\nPre-Install Fans: 4 x 120mm ARGB Fan\r\nI/O Port: 2x USB, 1x USB 5Gbps, 1x Audio Out, 1x Mic In', 'The MSI PRO FORGE M051R ARGB mATX Mid Tower Gaming Casing is designed under MSI\'s PRO series philosophy. This Casing combines clean aesthetics with practical functionality for productivity-focused users.', 3800.00, 15, 27, '../assets/pro-forge-m051r-01-500x500.webp', 0, '2026-08-22 20:32:27'),
(34, 'Gamdias AURA GC101M ARGB mATX Micro Tower Gaming Casing', 'Model: AURA GC101M\r\nMotherboard Support: Micro-ATX, Mini-ITX\r\nMagnetic Dust Filter, Tempered Glass Side & Front Panel\r\nPre-installed Fans: 5 x 120mm ARGB Fans\r\nI/O Ports: Type-C x 1, USB x2, HD Audio x1, LED x1', 'The Gamdias AURA GC101M ARGB Micro Tower Gaming Casing is designed for Micro-ATX and Mini-ITX builds.', 3600.00, 15, 28, '../assets/aura-gc101m-01-500x500.webp', 3, '2026-08-22 20:32:27'),
(35, 'Gamdias AURA GP650 650W Power Supply', 'Model: AURA GP650\r\nTotal Output Power: 650 Watts\r\nCompatible with ATX12V v2.4\r\nClose to 80 PLUS White efficiency\r\nProtection: OVP / UVP / OPP / OCP / SCP', 'The Gamdias AURA GP650 650W Power Supply is a dependable option for modern systems that require steady power delivery with minimal complexity. With an ATX12V v2.4 design, the Gamdias AURA GP650 650W Power Supply supports a wide range of components, making it a perfect choice for gaming PCs, office installations, and residential use. ', 3900.00, 16, 29, '../assets/aura-gp650-01-500x500.png', 2, '2026-08-22 20:38:32'),
(36, 'Corsair CX750 750W 80 PLUS Bronze ATX Power Supply', 'MPN: CP-9020279-UK\r\nModel: CX750\r\nContinuous power: 750 Watts\r\nPSU Form Factor: ATX\r\n120mm low-noise cooling fan\r\n80 PLUS Bronze efficiency up to 88%', 'The Corsair CX750 750W 80 PLUS Bronze ATX Power Supply is a durable and efficient component that will provide consistent power to your computer system. ', 7000.00, 16, 30, '../assets/cx750-01-500x500.webp', 3, '2026-08-22 20:38:32'),
(37, 'Gigabyte P450B 450W 80 Plus Bronze Certified Power Supply', 'MPN: GP-P450B/PSU 450W/80+B\r\nModel: Gigabyte P450B\r\nOVP/OPP/SCP/UVP/OCP/OTP protection\r\n80 PLUS Bronze certified\r\nReliable flat cable\r\nSingle +12V rail', '80 Plus Bronze certified ensures to deliver 85% efficiency at 50% load. The better power efficiency leads to less power waste, less heat and less fan noise. P450B provides the best solution for the system builds.', 3900.00, 16, 31, '../assets/p450b-500x500.jpg', 3, '2026-08-22 20:38:32'),
(38, 'MSI MAG A300N-H 300W 80 Plus Non Modular Power Supply', 'Model: MAG A300N-H\r\nOutput Capacity: 300W\r\nModular: Non modular design\r\nEfficiency Rating: 80 PLUS\r\nActive PFC, Hydraulic Bearing', 'The MSI MAG A300N-H 300W 80 Plus Non-Modular Power Supply is a dependable and efficient power solution designed for entry-level and compact PC builds.', 2300.00, 16, 33, '../assets/mag-a300n-h-01-500x500.webp', 3, '2026-08-22 20:38:32'),
(39, 'ASUS Prime 750W 80 Plus Gold Full Modular Power Supply', 'MPN: AP-750G\r\nModel: Prime 750W\r\nTotal Output: 750W\r\nIntel Form Factor: ATX12V\r\nEfficiency: 80Plus Gold\r\nATX Standard: ATX 3.0', 'The ASUS Prime 750W 80 Plus Gold Full Modular Power Supply is a robust, high-performance PSU designed to meet the needs of demanding PC builds, including gaming systems and versatile workstations.', 14800.00, 16, 32, '../assets/prime-750w-01-500x500.webp', 0, '2026-08-22 20:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_ibfk_1` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
