-- Restaurant POS & Inventory Management System
-- Add Meals and Recipes Tables

-- ==========================================
-- MEALS TABLE (Finished dishes/menu items)
-- ==========================================
CREATE TABLE IF NOT EXISTS `meals` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `sku` VARCHAR(50) UNIQUE,
  `cost_price` DECIMAL(10, 2) NOT NULL,
  `selling_price` DECIMAL(10, 2) NOT NULL,
  `image` VARCHAR(255),
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  INDEX idx_category (category_id),
  INDEX idx_status (status),
  INDEX idx_sku (sku)
);

-- ==========================================
-- MEAL RECIPES TABLE (Maps meals to ingredients)
-- ==========================================
CREATE TABLE IF NOT EXISTS `meal_recipes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `meal_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity_required` DECIMAL(10, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (meal_id) REFERENCES meals(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id),
  UNIQUE KEY unique_meal_product (meal_id, product_id),
  INDEX idx_meal (meal_id),
  INDEX idx_product (product_id)
);

-- ==========================================
-- ALTER CATEGORIES TABLE (Add type field)
-- ==========================================
ALTER TABLE `categories` ADD COLUMN `type` ENUM('ingredient', 'meal', 'both') DEFAULT 'both' AFTER `description`;
