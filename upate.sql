ALTER TABLE `tbl_outsourcing`
ADD COLUMN `handler_id_list` VARCHAR(255) NOT NULL DEFAULT '' AFTER `user_print`,
ADD COLUMN `num_hours` FLOAT NOT NULL DEFAULT 0 AFTER `handler_id_list`,
ADD COLUMN `warehouse_note` TEXT NULL AFTER `num_hours`,
ADD COLUMN `time_warehouse_note` INT NOT NULL DEFAULT 0 AFTER `warehouse_note`;;
