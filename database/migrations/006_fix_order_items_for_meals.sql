-- Fix order_items table to support meals instead of products
-- Drop the old foreign key constraint
ALTER TABLE `order_items` DROP FOREIGN KEY `order_items_ibfk_2`;

-- Rename product_id to meal_id
ALTER TABLE `order_items` CHANGE COLUMN `product_id` `meal_id` INT NOT NULL;

-- Add new foreign key for meals
ALTER TABLE `order_items` ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`);
