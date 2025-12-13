-- Add location field to tables
ALTER TABLE `tables` ADD COLUMN `location` VARCHAR(100) NULL AFTER `section`;
