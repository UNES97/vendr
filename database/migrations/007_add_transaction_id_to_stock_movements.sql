-- Add transaction_id to group stock movements from the same transaction
ALTER TABLE `stock_movements` ADD COLUMN `transaction_id` VARCHAR(50) NULL AFTER `reference_id`;
ALTER TABLE `stock_movements` ADD INDEX `idx_transaction` (`transaction_id`);
