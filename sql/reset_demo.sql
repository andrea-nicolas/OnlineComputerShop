-- =====================================================================
-- Online Computer Shop - RESET the demo back to its starting state
--
-- Import this in phpMyAdmin whenever you want the demo to look fresh again:
-- it empties the eight tables and puts the original products, accounts,
-- reviews, cart items and sample order back.
--
-- Safe to run as many times as you like. It only touches data, never the
-- table structure, so the shared schema is left exactly as it is.
--
-- Test passwords:  admin@shop.com -> admin123
--                  every customer -> password123
-- =====================================================================

USE `onlinecomputershop`;

-- Foreign keys are switched off for the emptying step only, so the tables can
-- be cleared in any order, then switched straight back on.
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `order_items`;
TRUNCATE TABLE `orders`;
TRUNCATE TABLE `carts`;
TRUNCATE TABLE `reviews`;
TRUNCATE TABLE `products`;
TRUNCATE TABLE `brands`;
TRUNCATE TABLE `categories`;
TRUNCATE TABLE `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Categories (parent_id NULL = top level)
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `parent_id`, `created_at`) VALUES
(1, 'Processor', NULL, NOW()),
(2, 'Storage',   NULL, NOW()),
(3, 'Monitor',   NULL, NOW()),
(4, 'RAM',       NULL, NOW()),
(5, 'SSD',       2,    NOW()),
(6, 'HDD',       2,    NOW());

-- --------------------------------------------------------
-- Brands (each brand belongs to a category)
-- --------------------------------------------------------
INSERT INTO `brands` (`id`, `name`, `category_id`, `created_at`) VALUES
(1, 'Intel',   1, NOW()),
(2, 'AMD',     1, NOW()),
(3, 'Samsung', 5, NOW()),
(4, 'Seagate', 6, NOW()),
(5, 'ASUS',    3, NOW()),
(6, 'LG',      3, NOW()),
(7, 'Corsair', 4, NOW()),
(8, 'G.Skill', 4, NOW());

-- --------------------------------------------------------
-- Products
-- --------------------------------------------------------
INSERT INTO `products`
(`id`, `name`, `description`, `manufacturer_review`, `price`, `category_id`, `brand_id`, `image_path`, `stock`, `created_at`) VALUES
(1, 'Intel Core i5-13400F',
    '10 cores (6P + 4E), 16 threads, up to 4.6 GHz turbo, LGA1700 socket, 65W base power. Ships with the Laminar RM1 cooler.',
    'A balanced mid-range chip that handles gaming and everyday productivity comfortably.',
    18500.00, 1, 1, 'images/intel-core-i5-13400f.avif', 12, NOW()),

(2, 'AMD Ryzen 5 7600',
    '6 cores, 12 threads, up to 5.1 GHz boost, AM5 socket, 65W TDP with the Wraith Stealth cooler included.',
    'Strong single-core performance on a platform with a long upgrade life.',
    22500.00, 1, 2, 'images/amd-ryzen-5-7600.jpg', 8, NOW()),

(3, 'Samsung 990 EVO 1TB NVMe SSD',
    'PCIe 4.0 x4 / 5.0 x2 M.2 2280 drive. Sequential read up to 5000 MB/s, write up to 4200 MB/s.',
    'Fast, cool running and backed by a five year warranty.',
    11200.00, 5, 3, 'images/samsung-990-evo-1tb.jpg', 20, NOW()),

(4, 'Seagate BarraCuda 2TB HDD',
    '3.5 inch SATA III desktop drive, 7200 RPM, 256MB cache. Good bulk storage for media libraries.',
    'Reliable capacity per taka when speed is not the priority.',
    5400.00, 6, 4, 'images/seagate-barracuda-2tb.jpg', 15, NOW()),

(5, 'ASUS VG249Q1A 24" 165Hz Monitor',
    '23.8 inch IPS panel, 1920x1080, 165Hz refresh, 1ms MPRT, FreeSync Premium, HDMI and DisplayPort.',
    'A sensible first gaming monitor with accurate colours out of the box.',
    21000.00, 3, 5, 'images/asus-vg249q1a-24.webp', 6, NOW()),

(6, 'LG 27GN800-B 27" QHD Monitor',
    '27 inch Nano IPS, 2560x1440, 144Hz, 1ms GtG, HDR10, G-SYNC compatible.',
    'Sharper text and more desktop space than a 1080p panel of the same size.',
    34500.00, 3, 6, 'images/lg-27gn800-27.png', 3, NOW()),

(7, 'Corsair Vengeance 16GB DDR5-5600',
    '2 x 8GB DDR5 kit, 5600 MT/s, CL36, on-die ECC, low profile heat spreader.',
    'Straightforward DDR5 that runs at its rated speed with one XMP click.',
    7800.00, 4, 7, 'images/corsair-vengeance-16gb.webp', 25, NOW()),

(8, 'G.Skill Trident Z5 32GB DDR5-6000',
    '2 x 16GB DDR5 kit, 6000 MT/s, CL30, aluminium heat spreader.',
    'Enthusiast grade timings for people who tune their memory.',
    16900.00, 4, 8, 'images/gskill-trident-z5-32gb.webp', 4, NOW());

-- --------------------------------------------------------
-- Users (1 admin + 3 customers)
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `profile_picture`, `created_at`) VALUES
(1, 'Mahmud Hasan', 'admin@shop.com',      '$2y$12$RzFBEHuHUJiOSH04Dgcs7uj5g2.ANj1aHgokUktDbZGtwGq8F0kw.', 'admin',    NULL, NOW()),
(2, 'Rafid Islam',  'rafid@example.com',   '$2y$12$z0IT/SQZhdPIilHgn0Dv7eAHgCZqh2XEQNGxgHDmu1ckbE8q.q6wG', 'customer', NULL, NOW()),
(3, 'Nusrat Jahan', 'nusrat@example.com',  '$2y$12$z0IT/SQZhdPIilHgn0Dv7eAHgCZqh2XEQNGxgHDmu1ckbE8q.q6wG', 'customer', NULL, NOW()),
(4, 'Tanvir Ahmed', 'tanvir@example.com',  '$2y$12$z0IT/SQZhdPIilHgn0Dv7eAHgCZqh2XEQNGxgHDmu1ckbE8q.q6wG', 'customer', NULL, NOW());

-- --------------------------------------------------------
-- A few existing reviews, so the review section is not empty
-- --------------------------------------------------------
INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 1, 2, 'Running this with a B760 board and it stays under 65 degrees on the stock cooler. Great value for the price.', NOW() - INTERVAL 6 DAY),
(2, 1, 3, 'Good chip, but remember the F version has no integrated graphics. I found that out after buying it.',          NOW() - INTERVAL 3 DAY),
(3, 3, 2, 'Cloned my old drive onto it in under ten minutes. Boot time dropped noticeably.',                              NOW() - INTERVAL 2 DAY),
(4, 5, 3, 'Colours needed a small tweak out of the box, but 165Hz on a budget is hard to argue with.',                    NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Cart rows, so the checkout page has something to place an order from
-- (carts.added_at is an INT in the schema, so it holds a UNIX timestamp)
-- --------------------------------------------------------
INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 2, 3, 1, UNIX_TIMESTAMP()),
(2, 2, 7, 2, UNIX_TIMESTAMP()),
(3, 3, 5, 1, UNIX_TIMESTAMP());

-- --------------------------------------------------------
-- One older order, so the admin dashboard is not empty on first run
-- --------------------------------------------------------
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_method`, `status`, `order_date`) VALUES
(1, 3, 47700, 'bkash', 'completed', NOW() - INTERVAL 9 DAY);

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 4, 1, 5400.00),
(2, 1, 6, 1, 34500.00),
(3, 1, 7, 1, 7800.00);
