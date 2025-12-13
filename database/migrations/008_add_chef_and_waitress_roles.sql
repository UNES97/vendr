-- Add Chef (Cuisine) and Waitress roles to users table
-- This migration updates the role ENUM to include 'chef' and 'waitress'

ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'manager', 'cashier', 'staff', 'chef', 'waitress') DEFAULT 'staff';
