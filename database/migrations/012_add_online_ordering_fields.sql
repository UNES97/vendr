-- Migration 012: Add Online Ordering Fields
-- Description: Add fields to support online ordering and QR code menu functionality
-- Date: 2025-12-13

-- Add QR code fields to tables
ALTER TABLE `tables`
  ADD COLUMN `qr_code` VARCHAR(255) NULL AFTER `capacity`,
  ADD COLUMN `qr_code_generated_at` TIMESTAMP NULL AFTER `qr_code`;

-- Add customer email and delivery fields to orders
ALTER TABLE `orders`
  ADD COLUMN `customer_email` VARCHAR(100) NULL AFTER `customer_phone`,
  ADD COLUMN `delivery_address` TEXT NULL AFTER `customer_email`,
  ADD COLUMN `delivery_fee` DECIMAL(10, 2) DEFAULT 0 AFTER `discount_percentage`,
  ADD COLUMN `special_instructions` TEXT NULL AFTER `notes`;

-- Add settings for online ordering
INSERT INTO `settings` (`key`, `value`) VALUES
  ('online_ordering_enabled', '1'),
  ('delivery_fee', '100'),
  ('minimum_delivery_order', '500'),
  ('estimated_preparation_time', '30');
