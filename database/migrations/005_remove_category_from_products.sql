-- Remove category association from products
-- Products will no longer be categorized, focusing on simple inventory management

ALTER TABLE `products` DROP FOREIGN KEY `products_ibfk_1`;
ALTER TABLE `products` DROP COLUMN `category_id`;
