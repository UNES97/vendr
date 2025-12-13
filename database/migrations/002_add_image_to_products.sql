-- Add image column to products table if it doesn't exist
ALTER TABLE `products` ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL AFTER `description`;

-- Create uploads directory if needed (this is for documentation)
-- The actual directory creation should be done via PHP
