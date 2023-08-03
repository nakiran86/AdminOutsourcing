<?php

/**
 * @author Mr Ninh <ninhnv@yahoo.com>
 * @copyright (c) 2013, Ninhnv
 * @version 1.0
 */
class AdminOutsourcing_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    public function getList($cond, $start = 0, $pagesize = 50) {
        return $this->db->selectAll('
            SELECT
                DISTINCT(tbl_outsourcing.id),
                DATE_FORMAT(tbl_outsourcing.date_out, "%d/%m/%Y") AS date_out,
                DATE_FORMAT(tbl_outsourcing.date_out, "%m") AS month_out,
                tbl_outsourcing.expired_date,
                tbl_outsourcing.time_finished,
                tbl_outsourcing.customer_code,
                tbl_outsourcing.customer_name,
                tbl_outsourcing.`status`,
                tbl_outsourcing.user_create_id,
                tbl_outsourcing.note,
                tbl_outsourcing.handler_id_list,
                tbl_outsourcing.num_hours,
                tbl_outsourcing.warehouse_note,
                tbl_outsourcing.time_warehouse_note,
                tbl_outsourcing.create_time,
                tbl_outsourcing.modify_time,
                tbl_outsourcing.log
            FROM
                tbl_outsourcing INNER JOIN tbl_outsourcing_product ON tbl_outsourcing.id = tbl_outsourcing_product.outsourcing_id
            WHERE
                `tbl_outsourcing`.`status` != "DELETED" AND tbl_outsourcing_product.`status` != "DELETED"' . $cond . '
            ORDER BY
                tbl_outsourcing.modify_time DESC, tbl_outsourcing.`status` DESC, tbl_outsourcing.date_out DESC, tbl_outsourcing.id DESC
            LIMIT
                ' . $start . ', ' . $pagesize . '
            ');
    }

    /**
     *
     */
    public function getTotalRecord($cond) {
        return $this->db->selectOne('
            SELECT
                COUNT(DISTINCT(tbl_outsourcing.id)) AS total_record
            FROM
                tbl_outsourcing INNER JOIN tbl_outsourcing_product ON tbl_outsourcing.id = tbl_outsourcing_product.outsourcing_id
            WHERE
                `tbl_outsourcing`.`status` != "DELETED" AND tbl_outsourcing_product.`status` != "DELETED"' . $cond . '
            LIMIT 0,1
            ');
    }

    /**
     *
     */
    public function itemSingleList($id, $extra_cond = '') {
        return $this->db->selectOne('
            SELECT
                tbl_outsourcing.id,
                tbl_outsourcing.date_out,
                DATE_FORMAT(tbl_outsourcing.date_out, "%m") AS month_out,
                tbl_outsourcing.expired_date,
                tbl_outsourcing.time_finished,
                tbl_outsourcing.customer_id,
                tbl_outsourcing.customer_code,
                tbl_outsourcing.customer_name,
                tbl_outsourcing.customer_address,
                tbl_outsourcing.seller_id,
                tbl_outsourcing.user_id,
                tbl_outsourcing.user_create_id,
                tbl_outsourcing.user_print,
                tbl_outsourcing.handler_id_list,
                tbl_outsourcing.num_hours,
                tbl_outsourcing.warehouse_note,
                tbl_outsourcing.time_warehouse_note,
                tbl_outsourcing.note,
                tbl_outsourcing.po_id,
                tbl_outsourcing.`status`,
                tbl_outsourcing.log
            FROM
                tbl_outsourcing INNER JOIN tbl_outsourcing_product ON tbl_outsourcing.id = tbl_outsourcing_product.outsourcing_id
            WHERE
                tbl_outsourcing.id = "' . $id . '"' . $extra_cond . '
            LIMIT
                0, 1
        ');
    }

    /**
     *
     */
    public function productOutsourceList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_outsourcing_product.id,
                tbl_outsourcing_product.outsourcing_id,
                tbl_outsourcing_product.product_id,
                tbl_outsourcing_product.product_code,
                tbl_outsourcing_product.product_name,
                tbl_outsourcing_product.product_specs,
                tbl_outsourcing_product.product_unit,
                tbl_outsourcing_product.quantity,
                tbl_outsourcing_product.quantity_import,
                tbl_outsourcing_product.hire_price,
                tbl_outsourcing_product.price,
                tbl_outsourcing_product.datatype,
                tbl_outsourcing_product.`status`,
                tbl_outsourcing_product.log
            FROM
                tbl_outsourcing INNER JOIN tbl_outsourcing_product ON tbl_outsourcing.id = tbl_outsourcing_product.outsourcing_id
            WHERE
                tbl_outsourcing_product.`status` != "DELETED" AND tbl_outsourcing_product.outsourcing_id = "' . $id . '"' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function getUserList($cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_user.username AS id,
                tbl_user.username,
                tbl_user.fullname,
                tbl_user.department,
                tbl_user.`status`,
                tbl_user.`group`
            FROM
                tbl_user
            WHERE
                tbl_user.username !="admin"' . $cond . '
            ORDER BY
                tbl_user.fullname ASC
            ');
    }

    /**
     *
     */
    public function getStockOutByOutsource($outId, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_stockout_local.id,
                tbl_stockout_local.date_out,
                tbl_stockout_local.buyer_code,
                tbl_stockout_local.buyer_fullname,
                tbl_stockout_local.buyer_company_name,
                tbl_stockout_local.buyer_address,
                tbl_stockout_local.user_create_id
            FROM
                tbl_stockout_local
            WHERE
                tbl_stockout_local.`status` NOT IN ("DELETED","CANCELLED") AND tbl_stockout_local.outsourcing_id = "' . $outId . '" ' . $extra_cond . '
            ORDER BY
                tbl_stockout_local.date_out DESC, tbl_stockout_local.id DESC
            ');
    }

    /**
     *
     */
    public function proSOList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_stockout_local_product.id,
                tbl_stockout_local_product.stock_out_id,
                tbl_stockout_local_product.item_id,
                tbl_stockout_local_product.item_code,
                tbl_stockout_local_product.item_name,
                tbl_stockout_local_product.item_specs,
                tbl_stockout_local_product.item_unit,
                tbl_stockout_local_product.item_quantity,
                tbl_stockout_local_product.item_price
            FROM
                tbl_stockout_local INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local_product.stock_out_id IN ("' . $id . '")' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function getReturnedByOutsource($sOutId, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_local_returned.id,
                tbl_local_returned.stock_out_local_id,
                tbl_local_returned.date_return,
                tbl_local_returned.buyer_code,
                tbl_local_returned.buyer_fullname,
                tbl_local_returned.buyer_company_name,
                tbl_local_returned.buyer_address,
                tbl_local_returned.user_create_id
            FROM
                tbl_local_returned
            WHERE
                tbl_local_returned.`status` != "DELETED" AND tbl_local_returned.stock_out_local_id IN ("' . $sOutId . '") ' . $extra_cond . '
            ORDER BY
                tbl_local_returned.date_return DESC, tbl_local_returned.id DESC
            ');
    }

    /**
     *
     */
    public function proReturnList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_local_returned_product.id,
                tbl_local_returned_product.returned_id,
                tbl_local_returned_product.sopi,
                tbl_local_returned_product.item_id,
                tbl_local_returned_product.item_code,
                tbl_local_returned_product.item_name,
                tbl_local_returned_product.item_specs,
                tbl_local_returned_product.item_unit,
                tbl_local_returned_product.item_quantity,
                tbl_local_returned_product.item_price,
                tbl_local_returned.stock_out_local_id
            FROM
                tbl_local_returned INNER JOIN tbl_local_returned_product ON tbl_local_returned.id = tbl_local_returned_product.returned_id
            WHERE
                tbl_local_returned.stock_out_local_id IN ("' . $id . '")' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function selectTable($table, $cond) {
        return $this->db->selectTable($table, $table . '.status != "DELETED" AND ' . $cond);
    }

    /**
     *
     */
    public function create($table, $data) {
        return $this->db->insert($table, $data);
    }

    /**
     *
     */
    public function productMaterialList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_processed_materials.id,
                tbl_processed_materials.outsourcing_product_id,
                tbl_processed_materials.product_id,
                tbl_processed_materials.product_norm,
                tbl_processed_materials.quantity_needed,
                tbl_processed_materials.product_price,
                tbl_processed_materials.log,
                tbl_product.code AS product_code,
                tbl_product.name AS product_name,
                tbl_product.specification AS product_specs,
                tbl_product.unit AS product_unit
            FROM
                tbl_product INNER JOIN (tbl_outsourcing_product INNER JOIN tbl_processed_materials ON tbl_outsourcing_product.id = tbl_processed_materials.outsourcing_product_id) ON tbl_product.id = tbl_processed_materials.product_id
            WHERE
                tbl_processed_materials.`status` != "DELETED" AND tbl_outsourcing_product.`status` != "DELETED" AND tbl_outsourcing_product.outsourcing_id = "' . $id . '"' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function checkMaterialList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_processed_materials.outsourcing_product_id,
                tbl_processed_materials.product_id AS id
            FROM
                tbl_outsourcing_product INNER JOIN tbl_processed_materials ON tbl_outsourcing_product.id = tbl_processed_materials.outsourcing_product_id
            WHERE
                tbl_processed_materials.`status` != "DELETED" AND tbl_outsourcing_product.`status` != "DELETED" AND tbl_outsourcing_product.outsourcing_id = "' . $id . '" ' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function checkStockoutMaterialList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_stockout_local_product.item_id AS id,
                SUM(tbl_stockout_local_product.item_quantity) AS total_quantity
            FROM
                tbl_stockout_local INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.outsourcing_id = "' . $id . '" ' . $extra_cond . '
            GROUP BY
                tbl_stockout_local_product.item_id
        ');
    }

    /**
     *
     */
    public function checkReturnMaterialList($id, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_local_returned_product.item_id AS id,
                SUM(tbl_local_returned_product.item_quantity) AS total_quantity
            FROM
                tbl_local_returned INNER JOIN tbl_local_returned_product ON tbl_local_returned.id = tbl_local_returned_product.returned_id
            WHERE
                tbl_local_returned.stock_out_local_id IN ("' . $id . '") ' . $extra_cond . '
            GROUP BY
                tbl_local_returned_product.item_id
        ');
    }

    /**
     *
     */
    public function getArrLocalStockOutIdList($outId, $extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_stockout_local.id
            FROM
                tbl_stockout_local INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.`status` NOT IN ("DELETED","CANCELLED") AND tbl_stockout_local.outsourcing_id = "' . $outId . '" ' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function getEndImportedList($cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_end_product.product_id AS id,
                tbl_end_product.create_time,
                tbl_end_product.stock
            FROM
                tbl_end_product
            WHERE
                tbl_end_product.`status` NOT IN ("DELETED") ' . $cond . '
            ORDER BY
                tbl_end_product.create_time DESC
        ');
    }

    /**
     *
     */
    public function updateEndImportedPrice($data) {
        $strSQL = 'UPDATE `tbl_end_product` SET `hire_price` = ' . $data['hire_price_update'] . ', `price` = ' . $data['price_update'] . ', `log`=CONCAT(COALESCE(`log`,""),"' . $data['log_add'] . '") WHERE `outsourcing_product_id` = "' . $data['outsourcing_product_id'] . '" AND `status` != "DELETED";';
        $this->db->runSql($strSQL);
    }

    /**
     *
     */
    public function getListStatistic($cond, $start = 0, $pagesize = 50) {
        return $this->db->selectAll('
            SELECT
                tbl_stockout_local_product.item_id AS id,
                SUM(tbl_stockout_local_product.item_quantity) AS quantity_issued,
                tbl_stockout_local_product.item_code,
                tbl_stockout_local_product.item_name,
                tbl_stockout_local_product.item_specs,
                tbl_stockout_local_product.item_unit
            FROM
                (tbl_outsourcing INNER JOIN tbl_stockout_local ON tbl_outsourcing.id = tbl_stockout_local.outsourcing_id) INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.datatype = "TRANSFER" AND tbl_stockout_local.`status` IN ("DELIVERED","FINISHED") AND tbl_stockout_local.outsourcing_id != "0" ' . $cond . '
            GROUP BY
                tbl_stockout_local_product.item_id
            ORDER BY
                tbl_stockout_local_product.item_name ASC, tbl_stockout_local.date_out ASC
            LIMIT
                ' . $start . ', ' . $pagesize . '
        ');
    }

    /**
     *
     */
    public function getTotalStatisticRecord($cond) {
        return $this->db->selectOne('
            SELECT
                COUNT(DISTINCT(tbl_stockout_local_product.item_id)) AS total_record
            FROM
                (tbl_outsourcing INNER JOIN tbl_stockout_local ON tbl_outsourcing.id = tbl_stockout_local.outsourcing_id) INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.datatype = "TRANSFER" AND tbl_stockout_local.`status` IN ("DELIVERED","FINISHED") AND tbl_stockout_local.outsourcing_id != "0" ' . $cond . '
            LIMIT
                0, 1
        ');
    }

    /**
     *
     */
    public function getListOutsourceByProductId($cond) {
        return $this->db->selectAll('
            SELECT
                DISTINCT(tbl_stockout_local.outsourcing_id) AS id
            FROM
                (tbl_outsourcing INNER JOIN tbl_stockout_local ON tbl_outsourcing.id = tbl_stockout_local.outsourcing_id) INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.datatype = "TRANSFER" AND `tbl_outsourcing`.`status` != "DELETED" ' . $cond . '
            ORDER BY
                tbl_stockout_local_product.item_name ASC, tbl_stockout_local.date_out ASC
        ');
    }

    /**
     *
     */
    public function getListQuantityProduction($extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_processed_materials.product_id AS id,
                SUM(tbl_processed_materials.quantity_needed) AS quantity_production
            FROM
                tbl_outsourcing_product INNER JOIN tbl_processed_materials ON tbl_outsourcing_product.id = tbl_processed_materials.outsourcing_product_id
            WHERE
                tbl_processed_materials.`status` != "DELETED" AND tbl_outsourcing_product.`status` != "DELETED" ' . $extra_cond . '
            GROUP BY
                tbl_processed_materials.product_id
        ');
    }

    /**
     *
     */
    public function getListStatisticReturnList($extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_local_returned_product.item_id AS id,
                SUM(tbl_local_returned_product.item_quantity) AS quantity_returned
            FROM
                tbl_local_returned INNER JOIN tbl_local_returned_product ON tbl_local_returned.id = tbl_local_returned_product.returned_id
            WHERE
                tbl_local_returned.datatype = "TRANSFER" ' . $extra_cond . '
            GROUP BY
                tbl_local_returned_product.item_id
        ');
    }

    /**
     *
     */
    public function getListLocalStockOutId($extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                DISTINCT(tbl_stockout_local.id) AS id
            FROM
                tbl_stockout_local INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.datatype = "TRANSFER" AND tbl_stockout_local.`status` IN ("DELIVERED","FINISHED") ' . $extra_cond . '
        ');
    }

    /**
     *
     */
    public function getListDetailOutsourceList($cond) {
        return $this->db->selectAll('
            SELECT
                DISTINCT(tbl_outsourcing.id),
                tbl_outsourcing.date_out,
                tbl_outsourcing.customer_code,
                tbl_outsourcing.customer_name
            FROM
                (tbl_outsourcing INNER JOIN tbl_stockout_local ON tbl_outsourcing.id = tbl_stockout_local.outsourcing_id) INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.datatype = "TRANSFER" AND `tbl_outsourcing`.`status` != "DELETED" ' . $cond . '
            ORDER BY
                tbl_outsourcing.date_out ASC, tbl_outsourcing.id ASC
        ');
    }

    /**
     *
     */
    public function getListDetailProductionList($extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_outsourcing.id,
                SUM(tbl_processed_materials.quantity_needed) AS quantity_production
            FROM
                (tbl_outsourcing INNER JOIN tbl_outsourcing_product ON tbl_outsourcing.id = tbl_outsourcing_product.outsourcing_id) INNER JOIN tbl_processed_materials ON tbl_outsourcing_product.id = tbl_processed_materials.outsourcing_product_id
            WHERE
                tbl_processed_materials.`status` != "DELETED" AND tbl_outsourcing_product.`status` != "DELETED" ' . $extra_cond . '
            GROUP BY
                tbl_outsourcing.id
        ');
    }

    /**
     *
     */
    public function getListDetailStockoutList($cond) {
        return $this->db->selectAll('
            SELECT
                DISTINCT(tbl_stockout_local.outsourcing_id) AS id,
                SUM(tbl_stockout_local_product.item_quantity) AS quantity_issued
            FROM
                tbl_stockout_local INNER JOIN tbl_stockout_local_product ON tbl_stockout_local.id = tbl_stockout_local_product.stock_out_id
            WHERE
                tbl_stockout_local.datatype = "TRANSFER" AND tbl_stockout_local.`status` IN ("DELIVERED","FINISHED") AND tbl_stockout_local.outsourcing_id != "0" ' . $cond . '
            GROUP BY
                tbl_stockout_local.outsourcing_id
        ');
    }

    /**
     *
     */
    public function getListDetailReturnedList($extra_cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_stockout_local.outsourcing_id AS id,
                SUM(tbl_local_returned_product.item_quantity) AS quantity_returned
            FROM
                (tbl_stockout_local INNER JOIN tbl_local_returned ON tbl_stockout_local.id = tbl_local_returned.stock_out_local_id) INNER JOIN tbl_local_returned_product ON tbl_local_returned.id = tbl_local_returned_product.returned_id
            WHERE
                tbl_local_returned.datatype = "TRANSFER" ' . $extra_cond . '
            GROUP BY
                tbl_stockout_local.outsourcing_id
        ');
    }

    /**
     *
     */
    public function getProductNormsInfo($cond = '') {
        return $this->db->selectAll('
            SELECT
                tbl_product.id,
                tbl_product.production_norms,
                tbl_product.price
            FROM
                tbl_product
            WHERE
                tbl_product.`status` != "DELETED"  ' . $cond . '
        ');
    }

    /**
     * Summary of getPOProductsList
     * @param mixed $cond
     * @return mixed
     */
    public function getPOProductsList($cond) {
        return $this->db->selectAll('
            SELECT
                tbl_po_product.id,
                tbl_po_product.po_id,
                tbl_po_product.date_po_product,
                tbl_po_product.date_tax,
                tbl_po_product.product_id,
                tbl_po_product.product_code,
                tbl_po_product.product_name,
                tbl_po_product.product_specs,
                tbl_po_product.product_unit,
                tbl_po_product.product_quantity,
                tbl_po_product.product_delivery_quantity,
                tbl_po_product.product_merge_quantity,
                tbl_po_product.product_price,
                tbl_po_product.item_id,
                tbl_po_product.item_code,
                tbl_po_product.item_name,
                tbl_po_product.item_specs,
                tbl_po_product.item_unit,
                tbl_po_product.item_quantity,
                tbl_po_product.item_delivery_quantity,
                tbl_po_product.item_merge_quantity,
                tbl_po_product.item_price,
                tbl_po_product.vat_quantity,
                tbl_po_product.vat_price,
                tbl_po_product.product_vat_id,
                tbl_po_product.bill_sheets_quantity,
                tbl_po_product.real_sheets_quantity,
                tbl_po_product.stock,
                tbl_po_product.`status`,
                tbl_po_product.note AS product_note
            FROM
                tbl_po_product
            WHERE
                ' . $cond . '
            ORDER BY
                tbl_po_product.item_name ASC
            ');
    }

}