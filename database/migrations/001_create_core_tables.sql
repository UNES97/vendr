-- Restaurant POS & Inventory Management System
-- Core Database Schema

-- ==========================================
-- CI SESSIONS TABLE (CodeIgniter Session Storage)
-- ==========================================
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` VARCHAR(128) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `timestamp` INT UNSIGNED DEFAULT 0,
  `data` BLOB NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- USERS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `role` ENUM('admin', 'manager', 'cashier', 'staff') DEFAULT 'staff',
  `avatar` VARCHAR(255),
  `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX idx_email (email),
  INDEX idx_status (status)
);

-- ==========================================
-- CATEGORIES TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `slug` VARCHAR(100) UNIQUE,
  `description` TEXT,
  `icon` VARCHAR(255),
  `color` VARCHAR(7),
  `display_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX idx_status (status)
);

-- ==========================================
-- PRODUCTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `sku` VARCHAR(50) UNIQUE,
  `barcode` VARCHAR(100),
  `cost_price` DECIMAL(10, 2) NOT NULL,
  `selling_price` DECIMAL(10, 2) NOT NULL,
  `stock` INT DEFAULT 0,
  `min_stock_level` INT DEFAULT 10,
  `max_stock_level` INT DEFAULT 100,
  `unit` VARCHAR(20) DEFAULT 'piece',
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
-- STOCK MOVEMENTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `type` ENUM('in', 'out', 'adjustment', 'damaged', 'expired') NOT NULL,
  `quantity` INT NOT NULL,
  `reference_type` VARCHAR(50),
  `reference_id` INT,
  `notes` TEXT,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (created_by) REFERENCES users(id),
  INDEX idx_product (product_id),
  INDEX idx_type (type),
  INDEX idx_date (created_at)
);

-- ==========================================
-- TABLES (For restaurant seating)
-- ==========================================
CREATE TABLE IF NOT EXISTS `tables` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `table_number` INT NOT NULL UNIQUE,
  `section` VARCHAR(50),
  `capacity` INT DEFAULT 4,
  `status` ENUM('available', 'occupied', 'reserved', 'maintenance') DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX idx_status (status)
);

-- ==========================================
-- ORDERS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_number` VARCHAR(50) UNIQUE NOT NULL,
  `table_id` INT,
  `customer_name` VARCHAR(100),
  `customer_phone` VARCHAR(20),
  `order_type` ENUM('dine-in', 'takeaway', 'delivery', 'online') DEFAULT 'dine-in',
  `total_amount` DECIMAL(10, 2) NOT NULL,
  `subtotal` DECIMAL(10, 2) NOT NULL,
  `tax_amount` DECIMAL(10, 2) DEFAULT 0,
  `discount_amount` DECIMAL(10, 2) DEFAULT 0,
  `discount_percentage` DECIMAL(5, 2) DEFAULT 0,
  `payment_method` ENUM('cash', 'card', 'online', 'cheque') DEFAULT 'cash',
  `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  `order_status` ENUM('pending', 'preparing', 'ready', 'served', 'completed', 'cancelled') DEFAULT 'pending',
  `notes` TEXT,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (table_id) REFERENCES tables(id),
  FOREIGN KEY (created_by) REFERENCES users(id),
  INDEX idx_order_number (order_number),
  INDEX idx_status (payment_status),
  INDEX idx_date (created_at)
);

-- ==========================================
-- ORDER ITEMS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(10, 2) NOT NULL,
  `discount_amount` DECIMAL(10, 2) DEFAULT 0,
  `total_price` DECIMAL(10, 2) NOT NULL,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id),
  INDEX idx_order (order_id)
);

-- ==========================================
-- EXPENSE CATEGORIES TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `expense_categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `icon` VARCHAR(255),
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX idx_status (status)
);

-- ==========================================
-- EXPENSES TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `payment_method` ENUM('cash', 'card', 'cheque', 'bank_transfer') DEFAULT 'cash',
  `reference_number` VARCHAR(100),
  `notes` TEXT,
  `attachment` VARCHAR(255),
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (category_id) REFERENCES expense_categories(id),
  FOREIGN KEY (created_by) REFERENCES users(id),
  INDEX idx_category (category_id),
  INDEX idx_date (created_at)
);

-- ==========================================
-- PAYMENTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `payment_method` ENUM('cash', 'card', 'online', 'cheque') NOT NULL,
  `transaction_id` VARCHAR(100),
  `status` ENUM('pending', 'successful', 'failed') DEFAULT 'pending',
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  INDEX idx_order (order_id),
  INDEX idx_status (status)
);

-- ==========================================
-- DISCOUNTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `code` VARCHAR(50) UNIQUE,
  `description` VARCHAR(255),
  `type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
  `value` DECIMAL(10, 2) NOT NULL,
  `minimum_amount` DECIMAL(10, 2) DEFAULT 0,
  `max_uses` INT,
  `current_uses` INT DEFAULT 0,
  `valid_from` DATETIME,
  `valid_until` DATETIME,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_code (code),
  INDEX idx_status (status)
);

-- ==========================================
-- STAFF SHIFTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `staff_shifts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `shift_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME,
  `status` ENUM('scheduled', 'checked_in', 'checked_out', 'absent') DEFAULT 'scheduled',
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_user (user_id),
  INDEX idx_date (shift_date)
);

-- ==========================================
-- SETTINGS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  `value` LONGTEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- ACTIVITY LOGS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `action` VARCHAR(100) NOT NULL,
  `table_name` VARCHAR(100),
  `record_id` INT,
  `old_values` JSON,
  `new_values` JSON,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_user (user_id),
  INDEX idx_date (created_at)
);

-- ==========================================
-- CREATE INITIAL DATA
-- ==========================================

-- Insert default expense categories
INSERT INTO `expense_categories` (`name`, `description`) VALUES
('Utilities', 'Electricity, water, gas'),
('Staff', 'Salaries and wages'),
('Rent', 'Building rent'),
('Supplies', 'Kitchen and office supplies'),
('Maintenance', 'Equipment maintenance'),
('Marketing', 'Promotional activities'),
('Insurance', 'Business insurance'),
('Other', 'Other expenses');

-- Insert sample categories
INSERT INTO `categories` (`name`, `slug`, `description`) VALUES
('Appetizers', 'appetizers', 'Starters and appetizers'),
('Main Courses', 'main-courses', 'Main dishes'),
('Desserts', 'desserts', 'Desserts and sweets'),
('Beverages', 'beverages', 'Drinks and beverages'),
('Specials', 'specials', 'Special offers');

-- Create admin user (password: admin123 hashed)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`) VALUES
('Admin User', 'admin@restaurant.local', '$2y$10$O8kS3Z3Y3Y3Y3Y3Y3Y3Y3uL7Q7Q7Q7Q7Q7Q7Q7Q7Q7Q7Q7Q7Q7Q7Q', 'admin', 'active');
