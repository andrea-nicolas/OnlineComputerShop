-- ---------------------------------------------------------------
-- Task 2 (23-50934-1) - product status
--
-- The shared dump has no status column on `products`, and the project
-- rules say the shared tables must not be dropped or altered. So the
-- active/inactive flag lives in its own small table instead. Nothing
-- in the shared schema is touched, and the other three tasks are not
-- affected even if they re-import onlinecomputershop.sql.
--
-- A product with no row here counts as ACTIVE, so existing products
-- keep working without needing any data added for them.
--
-- Import this into the `onlinecomputershop` database once, after the
-- shared dump.
-- ---------------------------------------------------------------

CREATE TABLE `product_status` (
  `product_id` int(10) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `product_status`
--
ALTER TABLE `product_status`
  ADD PRIMARY KEY (`product_id`);

--
-- Constraints for table `product_status`
--
-- Deleting a product removes its status row automatically.
--
ALTER TABLE `product_status`
  ADD CONSTRAINT `product_status_ibfk_1` FOREIGN KEY (`product_id`)
  REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
