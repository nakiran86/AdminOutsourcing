ALTER TABLE `tbl_outsourcing`
ADD COLUMN `handler_id_list` VARCHAR(255) NOT NULL DEFAULT '' AFTER `user_print`,
ADD COLUMN `num_hours` FLOAT NOT NULL DEFAULT 0 AFTER `handler_id_list`,
ADD COLUMN `warehouse_note` TEXT NULL AFTER `num_hours`,
ADD COLUMN `time_warehouse_note` INT NOT NULL DEFAULT 0 AFTER `warehouse_note`,
ADD COLUMN `time_complete` INT NOT NULL DEFAULT 0 COMMENT 'Thời gian kho xác nhận hoàn thành' AFTER `warehouse_note`;

UPDATE `tbl_product` SET `data_type`='OUTSOURCE' WHERE  `production_norms` <> "";
ALTER TABLE `tbl_outsourcing` CHANGE COLUMN `date_out` `date_out` DATETIME NOT NULL ;
ALTER TABLE `tbl_product`
ADD COLUMN `regular_cost` INT NOT NULL DEFAULT 0 COMMENT 'Chi phí nhân công trong giờ' AFTER `product_vat_id`,
ADD COLUMN `overtime_cost` INT NOT NULL DEFAULT 0 COMMENT 'Chi phí nhân công ngoài giờ' AFTER `regular_cost`;
ALTER TABLE `tbl_outsourcing`
ADD COLUMN `construction` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'Thi công: Trong giờ | Ngoài giờ' AFTER `time_warehouse_note`,
ADD COLUMN `order_status` VARCHAR(20) NULL DEFAULT '' COMMENT 'Tình trạng đơn hàng' AFTER `construction`;
ALTER TABLE `tbl_outsourcing_product`
ADD COLUMN `price_accounting` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'Hạch toán' AFTER `product_type`;
ALTER TABLE `tbl_product`
ADD COLUMN `approve_norms` CHAR(10) NOT NULL DEFAULT '' COMMENT 'Duyệt định mức' AFTER `production_norms`;

ALTER TABLE `tbl_product`
CHANGE COLUMN `approve_norms` `approve_norms` CHAR(15) NOT NULL DEFAULT '' COMMENT 'Duyệt định mức';