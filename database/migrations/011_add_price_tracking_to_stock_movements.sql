-- Add price tracking fields to stock_movements table
-- This allows tracking actual purchase prices per check-in transaction

ALTER TABLE `stock_movements`
ADD COLUMN `unit_cost` DECIMAL(10, 2) NULL COMMENT 'Actual purchase price per unit' AFTER `quantity`,
ADD COLUMN `total_cost` DECIMAL(10, 2) NULL COMMENT 'Total cost (quantity * unit_cost)' AFTER `unit_cost`,
ADD COLUMN `supplier` VARCHAR(255) NULL COMMENT 'Supplier name' AFTER `total_cost`;

-- Add index for cost reporting
ALTER TABLE `stock_movements` ADD INDEX `idx_cost_tracking` (`product_id`, `unit_cost`, `created_at`);
