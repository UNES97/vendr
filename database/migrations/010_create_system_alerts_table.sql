-- Create System Alerts Table
CREATE TABLE IF NOT EXISTS `system_alerts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `type` ENUM('info', 'warning', 'error', 'success') DEFAULT 'info',
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `icon` VARCHAR(50),
  `dismissible` BOOLEAN DEFAULT 1,
  `display_to_role` ENUM('admin', 'manager', 'cashier', 'chef', 'waitress', 'staff', 'all') DEFAULT 'all',
  `is_active` BOOLEAN DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL,
  `created_by` INT,
  INDEX idx_active (is_active),
  INDEX idx_type (type),
  FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Insert sample alerts
INSERT INTO `system_alerts` (`type`, `title`, `message`, `icon`, `display_to_role`, `is_active`) VALUES
('warning', 'Low stock alert', 'Tomatoes stock is below minimum level', 'fa-exclamation-triangle', 'all', 1),
('info', 'New order received', 'Order #12345 from Table 5', 'fa-bell', 'all', 1);
