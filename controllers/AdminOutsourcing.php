<?php

/**
 * @author Mr Ninh <ninhnv@yahoo.com>
 * @copyright (c) 2013, Ninhnv
 * @version 1.0
 */
class AdminOutsourcing extends Controller {

    private $_allProduct;

    function __construct() {
        parent::__construct();
        if (file_exists('caches/backend/cHJvZHVjdHMuY2FjaGU')) {
            require_once 'caches/backend/cHJvZHVjdHMuY2FjaGU';
            $this->_allProduct = eval('return ' . base64_decode($pL) . ';');
        }
        $this->view->subMenuList = array();
        array_push($this->view->subMenuList, array('name' => '{{.outsourcing_order.}}', 'url' => Link::createAdmin_current()));
        array_push($this->view->subMenuList, array('name' => $this->view->renderLabel('statistic_outsourcing'), 'url' => Link::createAdmin_current(array('cmd' => 'statisticOutsourcing'))));
        array_push($this->view->subMenuList, array('name' => $this->view->renderLabel('list_product_outsourcing'), 'url' => Link::createAdmin_current(array('cmd' => 'list_product'))));
    }

    /**
     *
     */
    private function _getNewItemProperties() {
        $this->form->post('customer_id')
            ->post('date_out')
            ->val('minlength', 'invalid_date_out', 10)
            ->post('expired_date')
            ->val('date_time', 'invalid_date')
            ->post('buyer_code')
            ->val('minlength', 'invalid_customer_code', 2)
            ->post('buyer_company')
            ->val('minlength', 'invalid_buyer_company', 3)
            ->post('buyer_address')
            ->val('minlength', 'invalid_address', 3)
            ->post('note')
            ->post('construction')
            ->post('records_new_withdrawal')
            ->val('mixed', 'invalid_product_withdrawal_list', NULL, FALSE)
            ->post('records_withdrawal')
            ->val('mixed', 'invalid_product_withdrawal_list', NULL, FALSE)
            ->post('new_list_material')
            ->val('mixed', 'invalid_product_withdrawal_list', NULL, FALSE)
            ->post('edit_list_material')
            ->val('mixed', 'invalid_product_material_list', NULL, FALSE);
        $data_post = $this->form->fetch();
        if (!$this->form->submit()) {
            throw new Exception($this->form->displayError());
        }
        $data = array();

        $data['customer_id'] = $data_post['customer_id'];
        $data['date_out'] = $data_post['date_out'];
        $data['expired_date'] = $data_post['expired_date'];
        $data['customer_code'] = $data_post['buyer_code'];
        $data['customer_name'] = $data_post['buyer_company'];
        $data['customer_address'] = $data_post['buyer_address'];
        $data['construction'] = $data_post['construction'];
        $data['note'] = $data_post['note'];
        $data['product_withdrawal_list'] = $data_post['records_new_withdrawal'];
        $data['product_withdrawal_edit_list'] = $data_post['records_withdrawal'];
        $data['material_new_list'] = $data_post['new_list_material'];
        $data['material_edit_list'] = $data_post['edit_list_material'];
        return $data;
    }

    /**
     * Get condition list
     */
    private function _getConditionList() {
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            $cond = '';
        } else {
            $userIdList = array();
            if (file_exists('caches/backend/dXNlck1hbmFnZXJMaXN0')) {
                $userManagerList = '';
                require_once 'caches/backend/dXNlck1hbmFnZXJMaXN0';
                $userIdList = eval('return ' . base64_decode($userManagerList) . ';');
            }
            $userListAccountant = $this->model->getUserListByUser('tbl_customer.user_id_list = "' . Session::get('user_id') . '"');
            if ($userListAccountant) {
                $cond = ' AND `tbl_outsourcing`.`user_accountant` = "' . Session::get('user_id') . '"';
            } else {
                if ($this->grant->check_privilege('MOD_PUBLICCUSTOMER', 'view')) {
                    $cond = ' AND (tbl_outsourcing.user_id IN ("", ' . $userIdList[Session::get('user_id')] . ') OR tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '")';
                } else {
                    $cond = ' AND (tbl_outsourcing.user_id IN (' . $userIdList[Session::get('user_id')] . ') OR tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '")';
                }
            }
            //            $customerList = array();
//            if (file_exists('caches/backend/Y3VzdG9tZXJNYW5hZ2VyTGlzdA')) {
//                $customerManagerList = '';
//                require_once 'caches/backend/Y3VzdG9tZXJNYW5hZ2VyTGlzdA';
//                $customerList = eval('return ' . base64_decode($customerManagerList) . ';');
//            }
//            $arrUserList = eval('return array(' . $userIdList[Session::get('user_id')] . ');');
//            if (Link::get('cbUser') && in_array(Link::get('cbUser'), $arrUserList)) {
//                $cond = ' AND tbl_outsourcing.user_id = "' . Link::get('cbUser') . '"';
//            } else {
//                if (Link::get('cbUser')) {
//                    if (Link::get('cbUser') == 'ALL' && $this->grant->check_privilege('MOD_PUBLICCUSTOMER', 'view')) {
//                        $cond = ' AND tbl_outsourcing.user_id = ""';
//                    } else {
//                        $cond = ' AND tbl_outsourcing.user_id = "none"';
//                    }
//                } else {
//                    if ($this->grant->check_privilege('MOD_PUBLICCUSTOMER', 'view')) {
//                        $cond = ' AND (tbl_outsourcing.user_id IN ("", ' . $userIdList[Session::get('user_id')] . ') OR tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '"';
//                    } else {
//                        $cond = ' AND (tbl_outsourcing.user_id IN (' . $userIdList[Session::get('user_id')] . ') OR tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '"';
//                    }
//                    $cond .= ' OR `tbl_outsourcing`.`customer_id` IN (' . $customerList[Session::get('user_id')] . '))';
//                }
//            }
        }
        if (Link::get('cbUser')) {
            if (Link::get('cbUser') == 'ALL') {
                $cond .= ' AND tbl_outsourcing.user_id = ""';
            } else {
                $cond .= ' AND tbl_outsourcing.user_id = "' . Link::get('cbUser') . '"';
            }
        }
        if (Link::get('outsource_number')) {
            $cond .= ' AND `tbl_outsourcing`.`id` IN ("' . str_replace(' ', '","', Link::get('outsource_number')) . '")';
        }
        if (Link::get('customer_code')) {
            $cond .= ' AND `tbl_outsourcing`.`customer_code` = "' . Link::get('customer_code') . '"';
        }
        if (Link::get('customer_name')) {
            $cond .= ' AND `tbl_outsourcing`.`customer_name` LIKE "%' . Link::get('customer_name') . '%"';
        }
        if (Link::get('product_code')) {
            $cond .= ' AND `tbl_outsourcing_product`.`product_code` = "' . Link::get('product_code') . '"';
        }
        if (Link::get('product_name')) {
            $cond .= ' AND `tbl_outsourcing_product`.`product_name` LIKE "%' . Link::get('product_name') . '%"';
        }
        if (Link::get('from_date')) {
            $cond .= ' AND DATE(`tbl_outsourcing`.`date_out`) >= "' . Systems::displaySqlDate(Link::get('from_date')) . '"';
        }
        if (Link::get('to_date')) {
            $cond .= ' AND DATE(`tbl_outsourcing`.`date_out`) <= "' . Systems::displaySqlDate(Link::get('to_date')) . '"';
        }
        if (Link::get('expire')) {
            if (Link::get('expire') == 'yes') {
                $cond .= ' AND `tbl_outsourcing`.`expired_date` >= CURDATE() AND `tbl_outsourcing_product`.`quantity` > `tbl_outsourcing_product`.`quantity_import`';
            }
            if (Link::get('expire') == 'no') {
                $cond .= ' AND `tbl_outsourcing`.`expired_date` < CURDATE() AND `tbl_outsourcing_product`.`quantity` > `tbl_outsourcing_product`.`quantity_import`';
            }
        }
        if (Link::get('status') && in_array(Link::get('status'), array('PENDING', 'FINISHED', 'NOTENOUGH'))) {
            if (in_array(Link::get('status'), array('PENDING', 'FINISHED'))) {
                $cond .= ' AND `tbl_outsourcing`.`status` = "' . Link::get('status') . '"';
            } else {
                $cond .= ' AND `tbl_outsourcing_product`.`quantity` > `tbl_outsourcing_product`.`quantity_import`';
            }
        }
        if (Link::get('po_number')) {
            $cond .= ' AND `tbl_outsourcing`.`po_id` = "' . Link::get('po_number') . '"';
        }
        if (Link::get('type')) {
            $cond .= ' AND `tbl_outsourcing`.`datatype` = "' . Link::get('type') . '"';
        }
        if (Link::get('accountant')) {
            $cond .= ' AND `tbl_outsourcing`.`user_create_id` = "' . Link::get('accountant') . '"';
        }
        if (Link::get('txtFindData')) {
            $cond .= ' AND (`tbl_outsourcing`.`customer_code` = "' . Link::get('txtFindData') . '" OR `tbl_outsourcing`.`customer_name` LIKE "%' . mb_strtolower(Link::get('txtFindData'), 'utf-8') . '%" OR `tbl_outsourcing`.`customer_name` LIKE "%' . mb_strtoupper(Link::get('txtFindData'), 'utf-8') . '%" OR `tbl_outsourcing`.`customer_name` LIKE "%' . Link::get('txtFindData') . '%")';
        }
        return $cond;
    }

    /**
     * Confirm to edit stockout
     */
    private function _confirmEdit($id) {
        if (is_numeric($id)) {
            $currentItem = $this->model->itemSingleList($id);
            if (in_array($currentItem['status'], array('PENDING', 'FINISHED'))) {
                $itemConfirm = $currentItem['log'];
                $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                $itemConfirm .= " - Status: " . $currentItem['status'] . " -> APPROVED\n";
                $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                $this->model->editSave('tbl_outsourcing', array('id' => $id, 'status' => 'APPROVED', 'modify_time' => time(), 'log' => $itemConfirm));
                unset($itemConfirm);
            }
        }
    }

    /**
     * Confirm delivery order status
     */
    private function _confirmDelivered($id, $extra_cond) {
        if (is_numeric($id)) {
            $currentItem = $this->model->itemSingleList($id, $extra_cond);
            if ($currentItem) {
                if ($currentItem['status'] == 'PENDING') {
                    $itemConfirm = $currentItem['log'];
                    $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                    $itemConfirm .= " - Status: PENDING -> FINISHED\n";
                    $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                    $this->model->editSave('tbl_outsourcing', array('id' => $id, 'time_finished' => date('Y-m-d H:i:s'), 'status' => 'FINISHED', 'log' => $itemConfirm));
                    unset($itemConfirm);
                }
            }
        }
    }

    /**
     * Confirm print outsource status
     */
    private function _confirmPrint($id) {
        if (is_numeric($id)) {
            $currentItem = $this->model->itemSingleList($id);
            $itemConfirm = $currentItem['log'];
            $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
            $arrConfirm['id'] = $id;
            if ($currentItem['user_print'] == '') {
                $arrConfirm['user_print'] = Session::get('user_id');
                $itemConfirm .= " - Action: PRINT\n";
            } else {
                $itemConfirm .= " - Action: REPRINT\n";
            }
            $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
            $arrConfirm['log'] = $itemConfirm;
            $this->model->editSave('tbl_outsourcing', $arrConfirm);
            unset($itemConfirm);
        }
    }

    /**
     * Set billing content
     */
    private function _setBillingContent($id) {
        $stringContent = '';
        $userList = $this->model->getUserList();
        $item = $this->model->itemSingleList($id);
        $item['accountant_name'] = $userList[$item['user_create_id']]['fullname'];
        if ($item && $item['status'] == 'PENDING') {
            $arr_date = explode('-', $item['date_out']);
            $item['year'] = $arr_date[0];
            $item['month'] = $arr_date[1];
            $item['day'] = $arr_date[2];
            $proList = $this->model->productOutsourceList($id);
            $materialProList = $this->model->productMaterialList($id);
            $stringContent .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $stringContent .= '<style>table, table tr td {font-family: Arial, "Times New Roman", serif; font-size: 10pt; vertical-align: middle; padding: 2px 5px;}</style>';
            $stringContent .= '<table cellspacing="0" cellpadding="0"><tr>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td>'
                . '<td width="19" height="1"></td></tr>'
                . '<tr><td colspan="8" rowspan="8" align="left" valign="top"><img width="150" height="150" src="' . $this->image->show_image_url($GLOBALS['webconfig']['company_logo'], 150, 150) . '" /></td>'
                . '<td colspan="27" align="center">&nbsp;</td></tr>'
                . '<tr><td colspan="18" align="center"><span style="font-size: 19pt; text-transform: uppercase;">' . mb_strtoupper($this->view->renderLabel('outsourcing_order')) . '</span></td><td colspan="9">&nbsp;</td></tr>'
                . '<tr><td colspan="18" align="right">&nbsp;</td><td colspan="9" align="center"><span style="font-size: 9pt;">' . $this->view->renderLabel('template') . ': 01 GTKT-3LL</span></td></tr>'
                . '<tr><td colspan="18" align="center"><span style="font-size: 9pt;">' . $this->view->renderLabel('copy_2') . ': ' . $this->view->renderLabel('delivery_to_warehouse') . '</span></td><td colspan="9">&nbsp;</td></tr>'
                . '<tr><td colspan="18">&nbsp;</td>'
                . '<td colspan="9" align="center"><span style="font-size: 9pt;">' . $item['id'] . '/GC' . $item['month'] . '</span></td></tr>'
                . '<tr><td colspan="18" align="center"><span style="font-size: 9pt; font-style: italic">Ngày&nbsp;&nbsp; ' . $item['day'] . '&nbsp;&nbsp; tháng&nbsp;&nbsp;&nbsp; ' . $item['month'] . '&nbsp;    năm&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $item['year'] . '</span></td><td colspan="9">&nbsp;</td></tr>'
                . '<tr><td colspan="27">&nbsp;</td></tr>'
                . '<tr><td colspan="27">&nbsp;</td></tr>'
                . '<tr><td colspan="35" height="24">' . $this->view->renderLabel('customer') . ': ' . $item['customer_code'] . ' - ' . $item['customer_name'] . '</td></tr>';
            $stringContent .= '<tr><td colspan="35" height="24">' . $this->view->renderLabel('note') . ': ' . $item['note'] . '</td></tr>'
                . '<tr><td colspan="35" style="border-bottom:#000 solid 0.3pt;">&nbsp;</td></tr>'
                . '<tr><td colspan="2" height="30" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt; font-weight: bold">STT</td>'
                . '<td colspan="4" height="30" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold">' . $this->view->renderLabel('product_code') . '</td>'
                . '<td colspan="22" height="30" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold">' . $this->view->renderLabel('product_information') . '</td>'
                . '<td colspan="3" height="30" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold">' . $this->view->renderLabel('col_unit_excel') . '</td>'
                . '<td colspan="4" height="30" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold">' . $this->view->renderLabel('col_quantity_excel') . '</td>'
                . '</tr>';
            $stringContent .= '<tr>'
                . '<td colspan="2" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0.3pt;">&nbsp;</td>'
                . '<td colspan="2" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">STT</td>'
                . '<td colspan="4" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">' . $this->view->renderLabel('col_code_excel') . '</td>'
                . '<td colspan="17" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">' . $this->view->renderLabel('col_name_excel') . '</td>'
                . '<td colspan="3" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">' . $this->view->renderLabel('col_unit') . '</td>'
                . '<td colspan="3" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">' . $this->view->renderLabel('product_norms') . '</td>'
                . '<td colspan="4" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">' . $this->view->renderLabel('col_quantity') . '</td>'
                . '</tr>';
            $index = 1;
            $checkRequireMaterial = 0;
            //            $subTotal = 0;
            foreach ($proList as $product) {
                $stringContent .= '<tr><td colspan="2" align="right" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt;">' . $index . '</td>';
                $stringContent .= '<td colspan="4" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0;">' . $product['product_code'] . '</td>';
                $stringContent .= '<td colspan="22" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0;">' . $product['product_name'] . ' - ' . $product['product_specs'] . '</td>';
                $stringContent .= '<td colspan="3" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0;">' . $product['product_unit'] . '</td>';
                $stringContent .= '<td colspan="4" align="right" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0;">' . Systems::displayNumber($product['quantity']) . '</td>';
                $stringContent .= '</tr>';
                $subIndex = 1;
                foreach ($materialProList as $materialValue) {
                    if ($materialValue['outsourcing_product_id'] == $product['id']) {
                        $stringContent .= '<tr>'
                            . '<td colspan="2" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0.3pt; font-style: italic;">&nbsp;</td>'
                            . '<td colspan="2" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-style: italic;">' . $index . '.' . $subIndex . '</td>'
                            . '<td colspan="4" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-style: italic;">' . $materialValue['product_code'] . '</td>'
                            . '<td colspan="17" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-style: italic;">' . $materialValue['product_name'] . ' - ' . $materialValue['product_specs'] . '</td>'
                            . '<td colspan="3" align="center" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-style: italic;">' . $materialValue['product_unit'] . '</td>'
                            . '<td colspan="3" align="right" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-style: italic;">' . $materialValue['product_norm'] . '</td>'
                            . '<td colspan="4" align="right" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0; font-style: italic;">' . ($product['quantity'] * $materialValue['product_norm']) . '</td>'
                            . '</tr>';
                        if ($subIndex == 1) {
                            $checkRequireMaterial++;
                        }
                        $subIndex++;
                    }
                }
                $stringContent .= '<tr><td colspan="35" style="border:#000 solid 0.3pt; border-width: 0 0.3pt 0.3pt 0.3pt;">&nbsp;</td></tr>';
                $index++;
            }
            //            $stringContent .= '<tr><td colspan="30" align="right" style="border:#000 solid; border-width: 0 0.3pt 0.3pt 0.3pt; font-weight: bold;">' . $this->view->renderLabel('subtotal') . ':</td>'
//                    . '<td colspan="5" align="right" style="border:#000 solid; border-width: 0 0.3pt 0.3pt 0; font-weight: bold;">' . Systems::displayNumber($subTotal) . '</td></tr>'
            $stringContent .= '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="8" align="center"><span style="font-size: 9pt;">' . $this->view->renderLabel('processing_staff_excel') . '</span></td>'
                . '<td colspan="9" align="center"><span style="font-size: 9pt;">&nbsp;</span></td>'
                . '<td colspan="10" align="center"><span style="font-size: 9pt;">' . $this->view->renderLabel('storekeepers') . '</span></td>'
                . '<td colspan="8" align="center"><span style="font-size: 9pt;">' . $this->view->renderLabel('editor_excel') . '</span></td></tr>'
                . '<tr><td colspan="8" align="center"><span style="font-size: 9pt;">(' . $this->view->renderLabel('sign_and_fullname') . ')</span></td>'
                . '<td colspan="9" align="center"><span style="font-size: 9pt;">&nbsp;</span></td>'
                . '<td colspan="10" align="center"><span style="font-size: 9pt;">(' . $this->view->renderLabel('sign_and_fullname') . ')</span></td>'
                . '<td colspan="8" align="center"><span style="font-size: 9pt;">(' . $this->view->renderLabel('sign_and_fullname') . ')</span></td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                . '<tr><td colspan="8" align="center"><span style="font-size: 9pt; font-weight: bold; font-style: italic;">&nbsp;</span></td>'
                . '<td colspan="9" align="center"><span style="font-size: 9pt; font-weight: bold; font-style: italic;">&nbsp;</span></td>'
                . '<td colspan="10" align="center"><span style="font-size: 9pt; font-weight: bold; font-style: italic;">&nbsp;</span></td>'
                . '<td colspan="8" align="center"><span style="font-size: 9pt; font-weight: bold; font-style: italic;">' . $item['accountant_name'] . '</span></td></tr>'
                . '<tr><td colspan="35">&nbsp;</td></tr>'
                //                    . '<tr><td colspan="35" align="center"><span style="font-size: 9pt;">(' . $this->view->renderLabel('need_to_check_compare_before_accepting_the_invoice') . ')</span></td></tr>'
//                    . '<tr><td colspan="35"><br />&nbsp;</td></tr>'
                . '</table>';
            if ($checkRequireMaterial == $index - 1) {
                $this->_confirmPrint($id);
            } else {
                $stringContent = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $stringContent .= '<h2 style="text-align:center; font-size: 20pt;">' . $this->view->renderLabel('not_enough_material_norms') . '</h2>';
            }
        } else {
            $stringContent = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $stringContent .= '<h2 style="text-align:center; font-size: 20pt;">' . $this->view->renderLabel('the_order_has_been_completed') . '</h2>';
        }
        return $stringContent;
    }

    /**
     * Restore stock out local
     */
    public function restoreorder($id = '') {
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') && in_array('MANAGER', Session::get('group'))) {
            $currentItem = $this->model->itemSingleList($id);
            if ($currentItem['status'] == 'FINISHED') {
                $itemConfirm = $currentItem['log'];
                $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                $itemConfirm .= " - Restore status: FINISHED -> PENDING\n";
                $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                $this->model->editSave('tbl_outsourcing', array('id' => $id, 'status' => 'PENDING', 'log' => $itemConfirm));
                unset($itemConfirm);
                Link::redirectAdminCurrent(array('cmd' => 'view', 'id' => $id));
            } else {
                Link::redirectAdminCurrent();
            }
        } else {
            Link::redirectAdminCurrent();
        }
    }

    /**
     * Show Customer information
     */
    public function showCustomer() {
        $keyword = Link::get('term');
        $dataShow = Link::get('list');
        $arrdata = array();
        if ($dataShow == 'multi') {
            $cond = '(tbl_customer.name LIKE "%' . $keyword . '%" OR tbl_customer.short_name LIKE "%' . $keyword . '%")';
            $allCustomer = $this->model->selectTable('tbl_customer', $cond);
            foreach ($allCustomer as $key => $customer) {
                $arrdata[$key]['value'] = htmlspecialchars_decode($customer['name']);
                $arrdata[$key]['desc'] = htmlspecialchars_decode($customer['address']);
                $arrdata[$key]['id'] = $customer['id'];
                $arrdata[$key]['code'] = $customer['code'];
                $arrdata[$key]['contact'] = $customer['contact_staff_list'];
            }
        } else if ($dataShow == 'code') {
            $cond = 'tbl_customer.code LIKE "%' . $keyword . '%"';
            $allCustomer = $this->model->selectTable('tbl_customer', $cond);
            foreach ($allCustomer as $key => $customer) {
                $arrdata[$key]['value'] = htmlspecialchars_decode($customer['code']);
                $arrdata[$key]['desc'] = htmlspecialchars_decode($customer['name']);
                $arrdata[$key]['address'] = htmlspecialchars_decode($customer['address']);
                $arrdata[$key]['id'] = $customer['id'];
                $arrdata[$key]['contact'] = $customer['contact_staff_list'];
            }
        } else if ($dataShow == 'single') {
            $cond = 'tbl_customer.name = "' . $keyword . '"';
            $allCustomer = $this->model->selectTable('tbl_customer', $cond);
            foreach ($allCustomer as $key => $customer) {
                $arrdata['company_code'] = htmlspecialchars_decode($customer['code']);
                $arrdata['company_name'] = htmlspecialchars_decode($customer['name']);
                $arrdata['company_address'] = htmlspecialchars_decode($customer['address']);
                $arrdata['id'] = $customer['id'];
                $arrdata['contact'] = $customer['contact_staff_list'];
            }
        } else if ($dataShow == 'scode') {
            $cond = 'tbl_customer.code = "' . $keyword . '"';
            $allCustomer = $this->model->selectTable('tbl_customer', $cond);
            foreach ($allCustomer as $key => $customer) {
                $arrdata['company_code'] = htmlspecialchars_decode($customer['code']);
                $arrdata['company_name'] = htmlspecialchars_decode($customer['name']);
                $arrdata['company_address'] = htmlspecialchars_decode($customer['address']);
                $arrdata['id'] = $customer['id'];
                $arrdata['contact'] = $customer['contact_staff_list'];
            }
        }
        echo json_encode($arrdata);
        exit;
    }

    /**
     * Show the product information
     */
    public function proInfo() {
        $keyword = Link::get('term');
        $fieldName = Link::get('field');
        $arrdata = array();
        $allProduct = array();
        $productList = array();
        if (!empty(Link::get('proid'))) {
            $proId = Link::get('proid');
            $itemProduct = $this->model->productSingleProduct($proId);
            if (!empty($itemProduct['production_norms'])) {
                $stringProductionNormsList = explode("\n", $itemProduct['production_norms']);
                foreach ($stringProductionNormsList as $productionNormsList) {
                    $stringNormsList = explode('|', $productionNormsList);
                    $productList[$stringNormsList[0]]['pro_norms'] = $stringNormsList[1];
                }
                if ($productList) {
                    $allProduct = $this->model->getListProduct(' AND tbl_product.id IN (' . implode(',', array_keys($productList)) . ') AND tbl_product.' . $fieldName . ' LIKE "%' . trim($keyword) . '%"');
                }
            }
        } else {
            foreach ($this->_allProduct as $key => $value) {
                if (stripos($value[$fieldName], $keyword) !== FALSE) {
                    $allProduct[$key] = $value;
                }
            }
        }
        if ($allProduct) {
            foreach ($allProduct as $key => $product) {
                if ($fieldName == 'name') {
                    $arrdata[$key]['id'] = $product['id'];
                    $arrdata[$key]['value'] = htmlspecialchars_decode($product['name']);
                    $arrdata[$key]['desc'] = htmlspecialchars_decode($product['specification']);
                    $arrdata[$key]['code'] = $product['code'];
                    $arrdata[$key]['unit'] = $product['unit'];
                    $arrdata[$key]['quantity'] = $product['quantity'];
                    $arrdata[$key]['price'] = $product['price'];
                    $arrdata[$key]['norm'] = '';
                    if (!empty($productList)) {
                        $arrdata[$key]['norm'] = $productList[$key]['pro_norms'];
                    }
                } else {
                    //                $arrdata[] = $product[$fieldName];
                    $arrdata[$key]['id'] = $product['id'];
                    $arrdata[$key]['name'] = htmlspecialchars_decode($product['name']);
                    $arrdata[$key]['desc'] = htmlspecialchars_decode($product['specification']);
                    $arrdata[$key]['value'] = $product['code'];
                    $arrdata[$key]['unit'] = $product['unit'];
                    $arrdata[$key]['quantity'] = $product['quantity'];
                    $arrdata[$key]['price'] = $product['price'];
                    $arrdata[$key]['norm'] = '';
                    if (!empty($productList)) {
                        $arrdata[$key]['norm'] = $productList[$key]['pro_norms'];
                    }
                }
            }
        } else {
            $arrdata[1]['id'] = '0';
            $arrdata[1]['value'] = $keyword;
            $arrdata[1]['name'] = $this->view->renderLabel('invalid_product');
            $arrdata[1]['desc'] = $this->view->renderLabel('invalid_product');
            $arrdata[1]['code'] = '';
            $arrdata[1]['unit'] = '';
            $arrdata[1]['quantity'] = 0;
            $arrdata[1]['price'] = 0;
            $arrdata[1]['norm'] = '';
        }
        echo json_encode($arrdata);
        exit;
    }

    /**
     * Fill missing information into related field of the product
     */
    public function fillinfo() {
        $keyword = Link::get('nameid');
        $arrdata = array();
        if ($keyword) {
            foreach ($this->_allProduct as $product) {
                if ($product['code'] == strtoupper($keyword)) {
                    $arrdata['id'] = $product['id'];
                    $arrdata['code'] = $product['code'];
                    $arrdata['name'] = htmlspecialchars_decode($product['name']);
                    $arrdata['specification'] = htmlspecialchars_decode($product['specification']);
                    $arrdata['unit'] = $product['unit'];
                    $arrdata['price'] = $product['price'];
                }
            }
        }
        echo json_encode($arrdata);
        exit;
    }

    /**
     * Show factory information
     */
    public function showfactory() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            Link::accessDenied();
        }
        $keyword = Link::get('term');
        $arrdata = array();
        $cond = '(tbl_customer.code = "' . $keyword . '" OR tbl_customer.name LIKE "%' . $keyword . '%" OR tbl_customer.short_name LIKE "%' . $keyword . '%")';

        $allCustomer = $this->model->selectTable('tbl_customer', $cond);
        foreach ($allCustomer as $key => $customer) {
            $arrdata[$key]['value'] = $customer['code'] . ' - ' . htmlspecialchars_decode($customer['name']);
            $arrdata[$key]['id'] = $customer['id'];
            $arrdata[$key]['code'] = $customer['code'];
        }
        echo json_encode($arrdata);
        exit;
    }

    /**
     * Check factory information
     */
    public function checkfactory() {
        $keyword = Link::get('nameid');
        $arrdata = array();
        $cond = 'CONCAT(tbl_customer.code," - ", tbl_customer.name) = "' . $keyword . '"';
        $allFactory = $this->model->selectTable('tbl_customer', $cond);
        foreach ($allFactory as $factory) {
            $arrdata['value'] = $factory['code'] . ' - ' . htmlspecialchars_decode($factory['name']);
            $arrdata['id'] = $factory['id'];
        }
        echo json_encode($arrdata);
        exit;
    }

    /**
     *
     */
    function index() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'view')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('outsourcing_order_list');
        $cond = $this->_getConditionList();
        $userList = $this->model->getUserList();
        $this->view->salesUserList = array();
        $this->view->accountantUserList = array();
        foreach ($userList as $keyUser => $user) {
            if ($user['status'] != 'DELETED') {
                if (strpos($user['group'], 'SALES') !== FALSE) {
                    $this->view->salesUserList[$keyUser] = $user;
                }
                //            if (strpos($user['group'], 'TRANSACTION') !== FALSE) {
                if ($user['department'] == $this->view->renderLabel('customer_care_department') || $user['department'] == $this->view->renderLabel('commerce_department')) {
                    $this->view->accountantUserList[$keyUser] = $user;
                }
            }
        }
        $pagesize = (Link::get('pagesize') > 0) ? Link::get('pagesize') : 50;
        $start = ($this->view->page_no() - 1) * $pagesize;
        $total_record = $this->model->getTotalRecord($cond);
        $this->view->totalRecord = $total_record['total_record'];
        $this->view->totalPage = $this->view->totalPage($this->view->totalRecord, $pagesize);
        $this->view->pagingList = $this->view->paging($this->view->totalRecord, $pagesize, FALSE);
        // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
        $this->view->itemList = $this->model->getList($cond, $start, $pagesize);
        foreach ($this->view->itemList as $key => $item) {
            $this->view->itemList[$key]['create_time'] = date("H:i:s d/m/Y", $item['create_time']);
            $this->view->itemList[$key]['order_status_label'] = '';
            if ($item['order_status']) {
                $this->view->itemList[$key]['order_status_label'] = $this->view->renderLabel(strtolower($item['order_status']));
            }
            $this->view->itemList[$key]['time_complete_date'] = '';
            if ($item['time_complete']) {
                $this->view->itemList[$key]['time_complete_date'] = date("H:i:s d/m/Y", $item['time_complete']);
            }
            $this->view->itemList[$key]['accountant_name'] = $userList[$item['user_create_id']]['fullname'];
            $this->view->itemList[$key]['outsource_number'] = $item['id'];
            $this->view->itemList[$key]['handler_list_name'] = $this->view->renderLabel('none_user');
            if ($item['handler_id_list']) {
                $handlerIdList[$key] = explode('|', $item['handler_id_list']);
                $stringUserId[$key] = '';
                foreach ($handlerIdList[$key] as $user) {
                    if (isset($userList[$user])) {
                        $stringUserId[$key] .= $userList[$user]['fullname'] . ', ';
                    }
                }
                $this->view->itemList[$key]['handler_list_name'] = $stringUserId[$key];
            }
        }
        $content = $this->view->render('adminoutsourcing/index', array(
            'salesUserList' => $this->view->salesUserList,
            'accountantUserList' => $this->view->accountantUserList,
            'totalRecord' => $this->view->totalRecord,
            'totalPage' => $this->view->totalPage,
            'pagingList' => $this->view->pagingList,
            'itemList' => $this->view->itemList,
            'subMenuList' => $this->view->subMenuList
        ), 'plugins/AdminOutsourcing/');
        return $content;
    }

    /**
     * View detail stock out
     */
    public function view($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'view')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('outsourcing_order_detail');
        //        $extra_cond = $this->_getExtraCond();
        $extra_cond = '';
        if (is_numeric($id)) {
            $this->view->item = $this->model->itemSingleList($id, $extra_cond);
            if ($this->view->item) {
                $userList = $this->model->getUserList();
                $this->view->item['delivery_status'] = $this->view->renderLabel('order_' . strtolower($this->view->item['status']));
                $this->view->item['outsource_number'] = $id . '/NBM' . $this->view->item['month_out'];
                $this->view->item['construction_label'] = $this->view->renderLabel(strtolower($this->view->item['construction']));
                if ($this->view->item['user_id']) {
                    $this->view->item['sales_name'] = $userList[$this->view->item['user_id']]['fullname'];
                } else {
                    $this->view->item['sales_name'] = $this->view->renderLabel('public_customer');
                }
                $this->view->item['handler_list_name'] = $this->view->renderLabel('none_user');
                $this->view->item['handler_list_arr'] = array();
                if ($this->view->item['handler_id_list']) {
                    $handlerIdList = explode('|', $this->view->item['handler_id_list']);
                    $stringUserId = '';
                    foreach ($handlerIdList as $user) {
                        if (isset($userList[$user])) {
                            $stringUserId .= $userList[$user]['fullname'] . ', ';
                        }
                    }
                    $this->view->item['handler_list_name'] = $stringUserId;
                    $this->view->item['handler_list_arr'] = $handlerIdList;
                }
                $this->view->item['order_status_label'] = '';
                if ($this->view->item['order_status']) {
                    $this->view->item['order_status_label'] = $this->view->renderLabel(strtolower($this->view->item['order_status']));
                }
                $this->view->item['store_dept_edit'] = false;
                if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'lock') && in_array('STORE', Session::get('group')) && $this->view->item['status'] == 'PENDING') {
                    $this->view->item['store_dept_edit'] = true;
                }
                $this->view->item['accountant_name'] = $userList[$this->view->item['user_create_id']]['fullname'];
                // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
                $this->view->proList = $this->model->productOutsourceList($id);
                foreach ($this->view->proList as $keyP => $valueP) {
                    $this->view->proList[$keyP]['price_accounting_label'] = ($valueP['price_accounting'] == 'CAPITAL_PRICE') ? $this->view->renderLabel('price_accounting_capital') : $this->view->renderLabel('price_accounting_local');
                }
                $this->view->itemList = $this->model->getStockOutByOutsource($id);
                $this->view->itemsGeneral = array();
                $this->view->proMaterialList = $this->model->productMaterialList($id);
                foreach ($this->view->proMaterialList as $keyM => $valueM) {
                    $this->view->itemsGeneral[$valueM['product_id']]['item_code'] = $valueM['product_code'];
                    $this->view->itemsGeneral[$valueM['product_id']]['item_name'] = $valueM['product_name'];
                    $this->view->itemsGeneral[$valueM['product_id']]['item_specs'] = $valueM['product_specs'];
                    $this->view->itemsGeneral[$valueM['product_id']]['item_unit'] = $valueM['product_unit'];
                    if (isset($this->view->itemsGeneral[$valueM['product_id']]['quantity_production'])) {
                        $this->view->itemsGeneral[$valueM['product_id']]['quantity_production'] += $valueM['quantity_needed'];
                    } else {
                        $this->view->itemsGeneral[$valueM['product_id']]['quantity_production'] = $valueM['quantity_needed'];
                    }
                    $this->view->itemsGeneral[$valueM['product_id']]['quantity_issued'] = 0;
                    $this->view->itemsGeneral[$valueM['product_id']]['quantity_returned'] = 0;
                }
                $stockOutIdList = '';
                $this->view->itemReturnedList = array();
                if ($this->view->itemList) {
                    $stockOutIdList = implode('","', array_keys($this->view->itemList));
                    $productStockOutList = $this->model->proSOList($stockOutIdList);
                    foreach ($productStockOutList as $keyProOut => $stockOutInfo) {
                        $this->view->itemList[$stockOutInfo['stock_out_id']]['proList'][$keyProOut] = $stockOutInfo;
                        $this->view->itemsGeneral[$stockOutInfo['item_id']]['id'] = $stockOutInfo['item_id'];
                        $this->view->itemsGeneral[$stockOutInfo['item_id']]['item_code'] = $stockOutInfo['item_code'];
                        $this->view->itemsGeneral[$stockOutInfo['item_id']]['item_name'] = $stockOutInfo['item_name'];
                        $this->view->itemsGeneral[$stockOutInfo['item_id']]['item_specs'] = $stockOutInfo['item_specs'];
                        $this->view->itemsGeneral[$stockOutInfo['item_id']]['item_unit'] = $stockOutInfo['item_unit'];
                        if (isset($this->view->itemsGeneral[$stockOutInfo['item_id']]['quantity_issued'])) {
                            $this->view->itemsGeneral[$stockOutInfo['item_id']]['quantity_issued'] += $stockOutInfo['item_quantity'];
                        } else {
                            $this->view->itemsGeneral[$stockOutInfo['item_id']]['quantity_issued'] = $stockOutInfo['item_quantity'];
                        }
                        $this->view->itemsGeneral[$stockOutInfo['item_id']]['quantity_returned'] = 0;
                    }
                    $this->view->itemReturnedList = $this->model->getReturnedByOutsource($stockOutIdList);
                    $productReturnedList = $this->model->proReturnList($stockOutIdList);
                    foreach ($productReturnedList as $keyProRe => $returnedInfo) {
                        $this->view->itemReturnedList[$returnedInfo['returned_id']]['proList'][$keyProRe] = $returnedInfo;
                        if (isset($this->view->itemsGeneral[$returnedInfo['item_id']]['quantity_returned'])) {
                            $this->view->itemsGeneral[$returnedInfo['item_id']]['quantity_returned'] += $returnedInfo['item_quantity'];
                        } else {
                            $this->view->itemsGeneral[$returnedInfo['item_id']]['quantity_returned'] = $returnedInfo['item_quantity'];
                        }
                    }
                }

                $allStoreUserList = $this->model->getUserList(' AND `tbl_user`.`department` = "' . $this->view->renderLabel('warehouse_department') . '" AND `tbl_user`.`status` <> "DELETED" AND `tbl_user`.`block` = "0"');

                $content = $this->view->render('adminoutsourcing/view', array(
                    'subMenuList' => $this->view->subMenuList,
                    'item' => $this->view->item,
                    'proList' => $this->view->proList,
                    'proMaterialList' => $this->view->proMaterialList,
                    'itemList' => $this->view->itemList,
                    'itemReturnedList' => $this->view->itemReturnedList,
                    'itemsGeneral' => $this->view->itemsGeneral,
                    'allStoreUserList' => $allStoreUserList
                ), 'plugins/AdminOutsourcing/');
                return $content;
            } else {
                Link::redirectAdminCurrent();
            }
        } else {
            Link::redirectAdminCurrent();
        }
    }

    /**
     * Show Detail Stockout
     */
    public function showdetailstockout() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            Link::accessDenied();
        }
        $product_id = Link::get('proid');
        $from_date = Link::get('fromdate');
        $to_date = Link::get('todate');
        $customer_id = Link::get('customer');
        $factory_id = Link::get('factory');
        $row_number = Link::get('rowno');
        $cond = '';
        $cond_local_stockout = '';
        if ($from_date) {
            $cond .= ' AND `tbl_stockout_local`.`date_out` >= "' . Systems::displaySqlDate($from_date) . '"';
            $cond_local_stockout .= ' AND `tbl_stockout_local`.`date_out` >= "' . Systems::displaySqlDate($from_date) . '"';
        }
        if ($to_date) {
            $cond .= ' AND `tbl_stockout_local`.`date_out` <= "' . Systems::displaySqlDate($to_date) . '"';
            $cond_local_stockout .= ' AND `tbl_stockout_local`.`date_out` <= "' . Systems::displaySqlDate($to_date) . '"';
        }
        if ($customer_id && is_numeric($customer_id)) {
            $cond .= ' AND `tbl_outsourcing`.`customer_id` = "' . $customer_id . '"';
        }
        if ($factory_id && is_numeric($factory_id)) {
            $cond .= ' AND `tbl_stockout_local`.`customer_id` = "' . $factory_id . '"';
            $cond_local_stockout .= ' AND `tbl_stockout_local`.`customer_id` = "' . $factory_id . '"';
        }
        if ($product_id) {
            $cond .= ' AND `tbl_stockout_local_product`.`item_id` = "' . $product_id . '"';
            $cond_local_stockout .= ' AND `tbl_stockout_local_product`.`item_id` = "' . $product_id . '"';
        }
        $outsourceList = $this->model->getListDetailOutsourceList($cond);
        $productionList = $this->model->getListDetailProductionList(' AND tbl_outsourcing.id IN ("' . implode('","', array_keys($outsourceList)) . '") AND tbl_processed_materials.product_id = "' . $product_id . '"');
        $localStockOutList = $this->model->getListDetailStockoutList($cond_local_stockout . ' AND tbl_stockout_local.outsourcing_id IN ("' . implode('","', array_keys($outsourceList)) . '")');
        $localReturnedList = $this->model->getListDetailReturnedList(' AND tbl_stockout_local.outsourcing_id IN ("' . implode('","', array_keys($outsourceList)) . '") AND tbl_local_returned_product.item_id = "' . $product_id . '"');
        $string_return = '';
        $index = 1;
        foreach ($outsourceList as $value) {
            if (!isset($productionList[$value['id']])) {
                $productionList[$value['id']]['quantity_production'] = 0;
            } else {
                $productionList[$value['id']]['quantity_production'] = Systems::displayFloatNumber($productionList[$value['id']]['quantity_production']);
            }
            if (!isset($localStockOutList[$value['id']])) {
                $localStockOutList[$value['id']]['quantity_issued'] = 0;
            } else {
                $localStockOutList[$value['id']]['quantity_issued'] = Systems::displayFloatNumber($localStockOutList[$value['id']]['quantity_issued']);
            }
            if (!isset($localReturnedList[$value['id']])) {
                $localReturnedList[$value['id']]['quantity_returned'] = 0;
            } else {
                $localReturnedList[$value['id']]['quantity_returned'] = Systems::displayFloatNumber($localReturnedList[$value['id']]['quantity_returned']);
            }
            $string_return .= '<tr class="detail' . $product_id . '">'
                . '<td align="right">&nbsp;</td><td align="right">' . $row_number . '.' . $index . '</td>'
                . '<td colspan="4"><a href="' . Link::createAdmin_current(array('cmd' => 'view', 'id' => $value['id'])) . '" title="' . $this->view->renderLabel('outsourcing_order_detail') . '" target="_blank">' . Systems::displayVnDate($value['date_out']) . ' - ' . $value['id'] . ' - ' . $value['customer_code'] . ' - ' . $value['customer_name'] . '</a></td>';
            $string_return .= '<td align="right">' . Systems::displayNumber($productionList[$value['id']]['quantity_production']) . '</td>';
            $string_return .= '<td align="right">' . Systems::displayNumber($localStockOutList[$value['id']]['quantity_issued']) . '</td>';
            $string_return .= '<td align="right">' . Systems::displayNumber($localStockOutList[$value['id']]['quantity_issued'] - $productionList[$value['id']]['quantity_production']) . '</td>';
            $string_return .= '<td align="right">' . Systems::displayNumber($localReturnedList[$value['id']]['quantity_returned']) . '</td>';
            $string_return .= '<td align="right">' . Systems::displayNumber($localReturnedList[$value['id']]['quantity_returned'] - ($localStockOutList[$value['id']]['quantity_issued'] - $productionList[$value['id']]['quantity_production'])) . '</td>'
                . '</tr>';
            $index++;
        }
        echo $string_return;
        exit;
    }

    /**
     * Outsourcing order statistics
     */
    public function statistic() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('outsourcing_order_statistics');
        // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
        if (Link::get('from_date') || Link::get('to_date')) {
            $pagesize = (Link::get('pagesize') > 0) ? Link::get('pagesize') : 50;
            $start = ($this->view->page_no() - 1) * $pagesize;
            $cond = '';
            if (Link::get('from_date')) {
                $cond .= ' AND `tbl_stockout_local`.`date_out` >= "' . Systems::displaySqlDate(Link::get('from_date')) . '"';
            }
            if (Link::get('to_date')) {
                $cond .= ' AND `tbl_stockout_local`.`date_out` <= "' . Systems::displaySqlDate(Link::get('to_date')) . '"';
            }
            if (Link::get('customer_name') && is_numeric(Link::get('customer_id'))) {
                $cond .= ' AND `tbl_outsourcing`.`customer_id` = "' . Link::get('customer_id') . '"';
            }
            if (Link::get('factory_name') && is_numeric(Link::get('factory_id'))) {
                $cond .= ' AND `tbl_stockout_local`.`customer_id` = "' . Link::get('factory_id') . '"';
            }
            if (Link::get('product')) {
                $cond .= ' AND (`tbl_stockout_local_product`.`item_code` = "' . Link::get('product') . '" OR `tbl_stockout_local_product`.`item_name` LIKE "%' . Link::get('product') . '%")';
            }
            $total_record = $this->model->getTotalStatisticRecord($cond);
            $this->view->totalRecord = $total_record['total_record'];
            $this->view->totalPage = $this->view->totalPage($this->view->totalRecord, $pagesize);
            $this->view->pagingList = $this->view->paging($this->view->totalRecord, $pagesize, FALSE);
            $this->view->itemsList = $this->model->getListStatistic($cond, $start, $pagesize);
            $outsourceIdList = $this->model->getListOutsourceByProductId($cond . ' AND tbl_stockout_local_product.item_id IN ("' . implode('","', array_keys($this->view->itemsList)) . '")');
            $itemsProductionList = $this->model->getListQuantityProduction(' AND tbl_outsourcing_product.outsourcing_id IN ("' . implode('","', array_keys($outsourceIdList)) . '")');
            $stockoutIdList = $this->model->getListLocalStockOutId(' AND tbl_stockout_local.outsourcing_id IN ("' . implode('","', array_keys($outsourceIdList)) . '")');
            $itemsReturnedList = $this->model->getListStatisticReturnList(' AND tbl_local_returned.stock_out_local_id IN ("' . implode('","', array_keys($stockoutIdList)) . '")');

            foreach ($this->view->itemsList as $value) {
                if (isset($itemsProductionList[$value['id']])) {
                    $this->view->itemsList[$value['id']]['quantity_production'] = Systems::displayFloatNumber($itemsProductionList[$value['id']]['quantity_production']);
                } else {
                    $this->view->itemsList[$value['id']]['quantity_production'] = 0;
                }
                if (isset($itemsReturnedList[$value['id']])) {
                    $this->view->itemsList[$value['id']]['quantity_returned'] = Systems::displayFloatNumber($itemsReturnedList[$value['id']]['quantity_returned']);
                } else {
                    $this->view->itemsList[$value['id']]['quantity_returned'] = 0;
                }
            }
        } else {
            $this->view->totalRecord = 0;
            $this->view->totalPage = 0;
            $this->view->pagingList = '';
            $this->view->itemsList = array();
        }
        $content = $this->view->render('adminoutsourcing/statistic', array(
            'subMenuList' => $this->view->subMenuList,
            'totalRecord' => $this->view->totalRecord,
            'totalPage' => $this->view->totalPage,
            'pagingList' => $this->view->pagingList,
            'itemsList' => $this->view->itemsList
        ), 'plugins/AdminOutsourcing/');
        return $content;
    }

    /**
     * Approve to edit outsourcing order
     */
    public function approveedit($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved')) {
            Link::accessDenied();
        }
        if (is_numeric($id)) {
            $this->_confirmEdit($id);
            Link::redirectAdminCurrent();
        } else {
            Link::redirectAdminCurrent();
        }
    }

    /**
     * Approve to edit outsourcing order
     */
    public function approvelistedit($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved')) {
            Link::accessDenied();
        }
        $id_list = Link::get('chkid');
        if (is_array($id_list)) {
            foreach ($id_list as $id) {
                if (is_numeric($id)) {
                    $this->_confirmEdit($id);
                }
            }
        }
        Link::redirectAdminCurrent();
    }

    /**
     *
     */
    public function add() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'add')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('add_new') . ' ' . $this->view->renderLabel('outsourcing_order');
        $this->addHeaderCss('public/css/jquery-ui/jquery.datetimepicker.min.css');
        $this->addHeaderJs('public/js/jquery.datetimepicker.full.min.js');
        // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
        $content = $this->view->render('adminoutsourcing/edit', array('error' => $this->view->error, 'subMenuList' => $this->view->subMenuList), 'plugins/AdminOutsourcing/');
        return $content;
    }

    /**
     *
     */
    public function edit($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('edit') . ' ' . $this->view->renderLabel('outsourcing_order');
        $this->addHeaderCss('public/css/jquery-ui/jquery.datetimepicker.min.css');
        $this->addHeaderJs('public/js/jquery.datetimepicker.full.min.js');
        if (is_numeric($id)) {
            $this->view->item = $this->model->itemSingleList($id);
            //            $itemStockoutTransferList = $this->model->getStockOutByOutsource($id);
//            if (empty($itemStockoutTransferList) || in_array('MANAGER', Session::get('group')) || $this->view->item['status'] == 'APPROVED') {
            if ($this->view->item['status'] != 'FINISHED' && $this->view->item['status'] != 'DELETED') {
                if ($this->view->item) {
                    // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
                    $this->view->proList = $this->model->productOutsourceList($id);
                    foreach ($this->view->item as $key => $value) {
                        if (is_string($value) and !Link::getPost($key)) {
                            if ($key == 'date_out') {
                                // $_POST[$key] = Systems::displayVnDate($value);
                                $_POST[$key] = date('H:i d/m/Y', strtotime($value));
                            } else if ($key == 'expired_date') {
                                $_POST['expired_date'] = Systems::displayVnDate($value);
                            } else if ($key == 'customer_code') {
                                $_POST['buyer_code'] = $value;
                            } else if ($key == 'customer_name') {
                                $_POST['buyer_company'] = $value;
                            } else if ($key == 'customer_address') {
                                $_POST['buyer_address'] = $value;
                            } else if ($key == 'datatype') {
                                $_POST['type'] = $value;
                            } else {
                                $_POST[$key] = $value;
                            }
                        }
                    }
                    $this->view->proMaterialList = $this->model->productMaterialList($id);
                    $content = $this->view->render('adminoutsourcing/edit', array(
                        'error' => $this->view->error,
                        'subMenuList' => $this->view->subMenuList,
                        'item' => $this->view->item,
                        'proList' => $this->view->proList,
                        'proMaterialList' => $this->view->proMaterialList
                    ), 'plugins/AdminOutsourcing/');
                    return $content;
                } else {
                    Link::redirectAdminCurrent();
                }
            } else {
                Link::redirectAdminCurrent();
            }
        } else {
            Link::redirectAdminCurrent();
        }
    }

    /**
     *
     */
    public function create() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'add')) {
            Link::accessDenied();
        }
        try {
            $data = $this->_getNewItemProperties();
            if ($data) {
                // $dataCusInfo['date_out'] = Systems::displaySqlDate($data['date_out']);
                $dateOut = DateTime::createFromFormat('H:i d/m/Y', $data['date_out']);
                $dataCusInfo['order_status'] = $this->_setOrderStatus($dateOut);
                $dataCusInfo['date_out'] = $dateOut->format('Y-m-d H:i:s');
                $dataCusInfo['expired_date'] = Systems::displaySqlDate($data['expired_date']);
                $dataCusInfo['customer_code'] = $data['customer_code'];
                $dataCusInfo['customer_name'] = $data['customer_name'];
                $dataCusInfo['customer_address'] = $data['customer_address'];
                $dataCusInfo['construction'] = $data['construction'];
                $dataCusInfo['note'] = $data['note'];
                $dataCusInfo['user_create_id'] = Session::get('user_id');
                $dataCusInfo['status'] = 'PENDING';
                $dataCusInfo['create_time'] = time();

                $customerInfo = $this->model->selectTable('tbl_customer', 'tbl_customer.id = "' . $data['customer_id'] . '"');
                if (is_numeric($data['customer_id']) && $customerInfo[$data['customer_id']]['name'] == $data['customer_name'] && $customerInfo[$data['customer_id']]['code'] == $data['customer_code']) {
                    $dataCusInfo['customer_id'] = $data['customer_id'];
                    $dataCusInfo['user_id'] = $customerInfo[$data['customer_id']]['user_id'];
                    $dataCusInfo['user_accountant'] = $customerInfo[$data['customer_id']]['user_id_list'];
                    $dataCusInfo['seller_id'] = $customerInfo[$data['customer_id']]['seller_id'];
                    $proList = $data['product_withdrawal_list'];
                    if ($proList) {
                        $flagCheck = TRUE;
                        $errors = '';
                        if (strtotime($dataCusInfo['expired_date'] . ' +1 day') > strtotime('+2 month') || strtotime($dataCusInfo['expired_date'] . ' +1 day') < strtotime('now') || $dataCusInfo['expired_date'] < $dateOut->format('Y-m-d')) {
                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#expired_date\').focus();$(\'#expired_date\').addClass(\'error\');">1. ' . $this->view->renderLabel('invalid_po_expired_date') . '</a></li>';
                            $flagCheck = FALSE;
                        }
                        $arrProductIdList = array();
                        foreach ($proList as $key => $product) {
                            $arrProductIdList[$product['real_id']] = $product['real_id'];
                            if ($product['real_quantity'] > 0) {
                                $existProductReal[$key] = $this->_allProduct[$product['real_id']];
                                if ($existProductReal[$key] == array()) {
                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_new_s' . $key . '_real_code\').focus();$(\'tr#rows' . $key . ' input\').addClass(\'error\');$(\'tr#rows' . $key . ' textarea\').addClass(\'error\');">2. ' . $product['real_code'] . ' - ' . $product['real_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                    $flagCheck = FALSE;
                                } else if ($product['real_name'] != $existProductReal[$key]['name'] || $product['real_specs'] != $existProductReal[$key]['specification'] || $product['real_unit'] != $existProductReal[$key]['unit']) {
                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_new_s' . $key . '_real_code\').focus();$(\'tr#rows' . $key . ' input\').addClass(\'error\');$(\'tr#rows' . $key . ' textarea\').addClass(\'error\');">3. ' . $product['real_code'] . ' - ' . $product['real_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                    $flagCheck = FALSE;
                                } else if (!is_numeric($product['real_quantity'])) {
                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_new_s' . $key . '_real_quantity\').focus();$(\'#records_new_s' . $key . '_real_quantity\').addClass(\'error\');">4. ' . $product['real_code'] . ' - ' . $product['real_name'] . ': ' . $this->view->renderLabel('invalid_quantity') . '</a></li>';
                                    $flagCheck = FALSE;
                                }
                            } else {
                                unset($proList[$key]);
                            }
                        }
                        if ($errors) {
                            $errors = '<ul id="error">' . $errors . '</ul>';
                        }
                        if ($flagCheck) {
                            if ($proList) {
                                $productNormsInfoList = $this->model->getProductNormsInfo(' AND tbl_product.id IN ("' . implode('","', $arrProductIdList) . '")');
                                $stock_id = $this->model->create('tbl_outsourcing', $dataCusInfo);
                                $outProId = array();
                                $proInsData = array();
                                $proInsMaterialData = array();
                                $stringProductionNormsList = array();
                                $stringNormsList = array();
                                $endProductPrice = array();
                                foreach ($proList as $key => $product) {
                                    //                                            Data for Insert new record
                                    $proInsData[$key]['outsourcing_id'] = $stock_id;
                                    $proInsData[$key]['product_id'] = $product['real_id'];
                                    $proInsData[$key]['product_code'] = $product['real_code'];
                                    $proInsData[$key]['product_name'] = $product['real_name'];
                                    $proInsData[$key]['product_specs'] = $product['real_specs'];
                                    $proInsData[$key]['product_unit'] = $product['real_unit'];
                                    $proInsData[$key]['quantity'] = $product['real_quantity'];
                                    // $proInsData[$key]['hire_price'] = $product['real_hire_price'];
                                    if ($dataCusInfo['construction'] == 'OUTSIDE_HOURS') {
                                        $proInsData[$key]['hire_price'] = $productNormsInfoList[$product['real_id']]['overtime_cost'];
                                    }
                                    if ($dataCusInfo['construction'] == 'OFFICE_HOURS') {
                                        $proInsData[$key]['hire_price'] = $productNormsInfoList[$product['real_id']]['regular_cost'];
                                    }
                                    $proInsData[$key]['price'] = $product['real_hire_price'];
                                    $proInsData[$key]['price_accounting'] = $product['price_accounting'];
                                    $proInsData[$key]['datatype'] = 'PRODUCT';
                                    $proInsData[$key]['status'] = 'PENDING';
                                    $proInsData[$key]['create_time'] = time();
                                    $outProId[$key] = $this->model->create('tbl_outsourcing_product', $proInsData[$key]);
                                    if ($productNormsInfoList[$product['real_id']]['production_norms']) {
                                        $endProductPrice[$key] = $product['real_hire_price'];
                                        $stringProductionNormsList[$key] = explode("\n", $productNormsInfoList[$product['real_id']]['production_norms']);
                                        foreach ($stringProductionNormsList[$key] as $kn => $productionNormsList) {
                                            $stringNormsList[$kn] = explode('|', $productionNormsList);
                                            if (isset($this->_allProduct[$stringNormsList[$kn][0]])) {
                                                $proInsMaterialData[$kn]['outsourcing_product_id'] = $outProId[$key];
                                                $proInsMaterialData[$kn]['product_id'] = $stringNormsList[$kn][0];
                                                $proInsMaterialData[$kn]['product_norm'] = $stringNormsList[$kn][1];
                                                $proInsMaterialData[$kn]['quantity_needed'] = round(($stringNormsList[$kn][1] * $product['real_quantity']), 2);
                                                $proInsMaterialData[$kn]['product_price'] = $this->_allProduct[$stringNormsList[$kn][0]]['price'];
                                                $proInsMaterialData[$kn]['status'] = 'NORMAL';
                                                $proInsMaterialData[$kn]['create_time'] = time();
                                                $this->model->create('tbl_processed_materials', $proInsMaterialData[$kn]);
                                                $endProductPrice[$key] += Systems::displayFloatNumber(($proInsMaterialData[$kn]['product_norm'] * $proInsMaterialData[$kn]['product_price']), 0);
                                            }
                                        }
                                        if ($endProductPrice[$key] != $product['real_hire_price']) {
                                            $this->model->editSave('tbl_outsourcing_product', array('id' => $outProId[$key], 'price' => $endProductPrice[$key]));
                                        }
                                    }
                                }
                            } else {
                                throw new Exception($this->view->renderLabel('invalid_product_withdrawal_list'));
                            }
                        } else {
                            throw new Exception($errors);
                        }
                        Link::redirectAdminCurrent();
                    } else {
                        throw new Exception($this->view->renderLabel('invalid_product_withdrawal_list'));
                    }
                } else {
                    throw new Exception($this->view->renderLabel('invalid_buyer_company'));
                }
            }
        } catch (Exception $exc) {
            $this->view->error = $exc->getMessage();
            return $this->add();
        }
    }

    private function _getQuantityAfterPo($poId) {
        $poProList = array();
        $productPOList = $this->model->getPOProductsList('tbl_po_product.`po_id` = ' . $poId);
        $productIdPOList = array_unique(array_column($productPOList, 'item_id'));
        $allProductOrdered = $this->model->getListCheckAutoOrder('tbl_product_order.`status` IN ("APPROVED","PROCESSING","CANCELING") AND tbl_product_order.product_id IN ("' . implode('","', $productIdPOList) . '")');
        $allProductOutSourceOrder = $this->model->getListOutSourceOrder(' AND tbl_outsourcing_product.product_id IN ("' . implode('","', $productIdPOList) . '")');
        $productPOPendingList = $this->model->getPOProPendingList(' AND tbl_po_product.item_id IN ("' . implode('","', $productIdPOList) . '")');
        foreach ($productPOList as $kPro => $vPro) {
            if (!isset($allProductOrdered[$vPro['item_id']])) {
                $allProductOrdered[$vPro['item_id']]['total_quantity'] = 0;
            }
            if (!isset($allProductOutSourceOrder[$vPro['item_id']])) {
                $allProductOutSourceOrder[$vPro['item_id']]['total_quantity'] = 0;
            }
            if (!isset($productPOPendingList[$vPro['item_id']])) {
                $productPOPendingList[$vPro['item_id']]['delivery_quantity_pending'] = 0;
            }
            $field_qty_check = $this->getDefaultProductQuantityField($vPro['stock']);
            $poProList[$vPro['item_id']]['quantity_after_po'] = Systems::displayFloatNumber($this->_allProduct[$vPro['item_id']][$field_qty_check] + $allProductOrdered[$vPro['item_id']]['total_quantity'] + $allProductOutSourceOrder[$vPro['item_id']]['total_quantity'] - $productPOPendingList[$vPro['item_id']]['delivery_quantity_pending']);
        }
        return $poProList;
    }

    private function _setOrderStatus($dateOut) {
        $bCal = new BusinessTimeCalc();
        $curDate = new DateTime();
        $workingTime = $bCal->calcBusinessMinute($dateOut, $curDate) / 60;
        if ($workingTime <= 4) {
            return 'SUPPER_URGENT';
        } else if ($workingTime > 4 && $workingTime <= 16) {
            return 'URGENT';
        } else {
            return 'NORMAL';
        }
    }

    /**
     *
     */
    public function editSave($id) {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) {
            Link::accessDenied();
        }
        try {
            $data = $this->_getNewItemProperties();
            if ($data) {
                $curItem = $this->model->itemSingleList($id);
                //                $itemStockoutTransferList = $this->model->getStockOutByOutsource($id);
//                if (empty($itemStockoutTransferList) || in_array('MANAGER', Session::get('group')) || $curItem['status'] == 'APPROVED') {
                if ($curItem['status'] != 'FINISHED' && $curItem['status'] != 'DELETED') {
                    $originCurentProList = $this->model->productOutsourceList($id);
                    $originCurentProMaterialList = $this->model->productMaterialList($id);
                    $dataCusInfo['id'] = $id;
                    // $dataCusInfo['date_out'] = Systems::displaySqlDate($data['date_out']);
                    // $data['date_out'] = '14:30 11/01/2024';
                    $dateOut = DateTime::createFromFormat('H:i d/m/Y', $data['date_out']);
                    $dataCusInfo['date_out'] = $dateOut->format('Y-m-d H:i:s');
                    $dataCusInfo['order_status'] = $this->_setOrderStatus($dateOut);
                    $dataCusInfo['customer_code'] = $data['customer_code'];
                    $dataCusInfo['customer_name'] = $data['customer_name'];
                    $dataCusInfo['customer_address'] = $data['customer_address'];
                    $dataCusInfo['construction'] = $data['construction'];
                    $dataCusInfo['note'] = $data['note'];
                    $proList = $data['product_withdrawal_edit_list'];
                    $proNewList = $data['product_withdrawal_list'];
                    $proMaterialEditList = $data['material_edit_list'];
                    $proMaterialNewList = $data['material_new_list'];
                    $customerInfo = $this->model->selectTable('tbl_customer', 'tbl_customer.id = "' . $data['customer_id'] . '"');

                    $poProList = $this->_getQuantityAfterPo($curItem['po_id']);

                    if (is_numeric($data['customer_id']) && $customerInfo[$data['customer_id']]['name'] == $data['customer_name'] && $customerInfo[$data['customer_id']]['code'] == $data['customer_code']) {
                        if ($proList || $proNewList) {
                            $errors = '';
                            $flagCheck = TRUE;
                            if (($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') && in_array('MANAGER', Session::get('group'))) || $curItem['status'] == 'APPROVED') {
                                $dataCusInfo['expired_date'] = Systems::displaySqlDate($data['expired_date']);
                                if (($curItem['expired_date'] != $dataCusInfo['expired_date'] && strtotime($dataCusInfo['expired_date'] . ' +1 day') < strtotime('now')) || $dataCusInfo['expired_date'] < $dateOut->format('Y-m-d')) {
                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#expired_date\').focus();$(\'#expired_date\').addClass(\'error\');">5. ' . $this->view->renderLabel('invalid_po_expired_date') . '</a></li>';
                                    $flagCheck = FALSE;
                                }
                            } else {
                                if ($curItem['expired_date'] == '1970-01-01') {
                                    $dataCusInfo['expired_date'] = Systems::displaySqlDate($data['expired_date']);
                                    if (strtotime($dataCusInfo['expired_date'] . ' +1 day') < strtotime('now') || $dataCusInfo['expired_date'] < $dateOut->format('Y-m-d')) {
                                        $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#expired_date\').focus();$(\'#expired_date\').addClass(\'error\');">6. ' . $this->view->renderLabel('invalid_po_expired_date') . '</a></li>';
                                        $flagCheck = FALSE;
                                    }
                                }
                            }
                            $arrProductIdList = array();
                            if ($proList) {
                                $arrMaterialPriceList = array();
                                foreach ($proList as $key => $product) {
                                    if ($poProList[$product['real_id']]['quantity_after_po'] < 0) {
                                        $errors .= '<li><span>26. ' . $this->view->renderLabel('quantity_after_po') . ' < 0</span></li>';
                                        $flagCheck = FALSE;
                                    }
                                    $arrProductIdList[$product['real_id']] = $product['real_id'];
                                    $arrMaterialPriceList[$key] = 0;
                                    if ($product['real_quantity'] > 0) {
                                        $existProductReal[$key] = $this->_allProduct[$product['real_id']];
                                        if ($product['real_quantity'] < $originCurentProList[$key]['quantity']) {
                                            if ($originCurentProList[$key]['quantity_import'] > $product['real_quantity']) {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_withdrawal_' . $key . '_real_quantity\').focus();$(\'#records_withdrawal_' . $key . '_real_quantity\').addClass(\'error\');">7. ' . $originCurentProList[$key]['product_code'] . ' - ' . $originCurentProList[$key]['product_name'] . ' ' . $this->view->renderLabel('deficient') . ' ' . ($originCurentProList[$key]['quantity_import'] - $product['real_quantity']) . ' ' . $originCurentProList[$key]['product_unit'] . '</a></li>';
                                                $flagCheck = FALSE;
                                            }
                                        }
                                        if ($originCurentProList[$key]['quantity_import'] > 0 && $curItem['status'] != 'APPROVED') {
                                            if ($originCurentProList[$key]['hire_price'] != $product['real_hire_price']) {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_withdrawal_' . $key . '_real_hire_price\').focus();$(\'#records_withdrawal_' . $key . '_real_hire_price\').addClass(\'error\');">8. ' . $originCurentProList[$key]['product_code'] . ' - ' . $originCurentProList[$key]['product_name'] . ': ' . $this->view->renderLabel('can_not_change_hire_price') . '</a></li>';
                                                $flagCheck = FALSE;
                                            }
                                            if ($originCurentProList[$key]['price'] != $product['real_price']) {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_withdrawal_' . $key . '_real_price\').focus();$(\'#records_withdrawal_' . $key . '_real_price\').addClass(\'error\');">9. ' . $originCurentProList[$key]['product_code'] . ' - ' . $originCurentProList[$key]['product_name'] . ': ' . $this->view->renderLabel('can_not_change_price') . '</a></li>';
                                                $flagCheck = FALSE;
                                            }
                                        }
                                        if ($existProductReal[$key] == array()) {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_withdrawal_' . $key . '_real_code\').focus();$(\'tr#rows' . $key . ' input\').addClass(\'error\');$(\'tr#rows' . $key . ' textarea\').addClass(\'error\');">10. ' . $originCurentProList[$key]['product_code'] . ' - ' . $originCurentProList[$key]['product_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                            $flagCheck = FALSE;
                                        } else if (!is_numeric($product['real_quantity'])) {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_withdrawal_' . $key . '_real_quantity\').focus();$(\'#records_withdrawal_' . $key . '_real_quantity\').addClass(\'error\');">11. ' . $originCurentProList[$key]['product_code'] . ' - ' . $originCurentProList[$key]['product_name'] . ': ' . $this->view->renderLabel('invalid_quantity') . '</a></li>';
                                            $flagCheck = FALSE;
                                        }
                                    } else {
                                        unset($proList[$key]);
                                    }
                                }
                            } else {
                                $proList = array();
                            }
                            $proMaterialEditedList = array();
                            if ($proMaterialEditList) {
                                foreach ($proMaterialEditList as $proOSKeyEdit => $editMaterial) {
                                    foreach ($editMaterial as $kme => $prome) {
                                        if ($prome['real_id'] == $originCurentProMaterialList[$kme]['product_id']) {
                                            if (is_numeric($prome['real_norm']) && $prome['real_norm'] > 0) {
                                                $existProductReal[$kme] = $this->_allProduct[$prome['real_id']];
                                                if ($existProductReal[$kme] == array()) {
                                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'tr#rows_material_' . $kme . ' input\').addClass(\'error\')">' . $originCurentProMaterialList[$kme]['product_code'] . ' - ' . $originCurentProMaterialList[$kme]['product_name'] . ': ' . $this->view->renderLabel('the_product_has_been_deleted') . '</a></li>';
                                                    $flagCheck = FALSE;
                                                } else {
                                                    if (!is_numeric($prome['real_norm'])) {
                                                        $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#edit_list_material_' . $kme . '_real_norm\').focus();$(\'#edit_list_material_' . $kme . '_real_norm\').addClass(\'error\');">12. ' . $originCurentProMaterialList[$kme]['product_code'] . ' - ' . $originCurentProMaterialList[$kme]['product_name'] . ': ' . $this->view->renderLabel('invalid_quantity') . '</a></li>';
                                                        $flagCheck = FALSE;
                                                    }
                                                }
                                                if ($curItem['status'] != 'APPROVED') {
                                                    if ($prome['real_norm'] != $originCurentProMaterialList[$kme]['product_norm']) {
                                                        $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#edit_list_material_' . $kme . '_real_norm\').focus();$(\'#edit_list_material_' . $kme . '_real_norm\').addClass(\'error\');">13. ' . $originCurentProMaterialList[$kme]['product_code'] . ' - ' . $originCurentProMaterialList[$kme]['product_name'] . ': ' . $this->view->renderLabel('cannot_change_product_norm') . '</a></li>';
                                                        $flagCheck = FALSE;
                                                    }
                                                    if ($originCurentProList[$proOSKeyEdit]['quantity_import'] > 0) {
                                                        if ($prome['real_price'] != $originCurentProMaterialList[$kme]['product_price']) {
                                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#edit_list_material_' . $kme . '_real_price\').focus();$(\'#edit_list_material_' . $kme . '_real_price\').addClass(\'error\');">14. ' . $originCurentProMaterialList[$kme]['product_code'] . ' - ' . $originCurentProMaterialList[$kme]['product_name'] . ': ' . $this->view->renderLabel('cannot_change_price') . '</a></li>';
                                                            $flagCheck = FALSE;
                                                        }
                                                    }
                                                }
                                                $proMaterialEditedList[$kme] = $prome;
                                            }
                                        } else {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'tr#rows_material_' . $kme . ' input\').addClass(\'error\')">' . $originCurentProMaterialList[$kme]['product_code'] . ' - ' . $originCurentProMaterialList[$kme]['product_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                            $flagCheck = FALSE;
                                        }
                                        $arrMaterialPriceList[$proOSKeyEdit] += Systems::displayFloatNumber(($prome['real_norm'] * $prome['real_price']), 0);
                                    }
                                }
                            }
                            $deleteOutsourcingProList = array_diff_key($originCurentProList, $proList);
                            $proOutsourcingDel = array();
                            foreach ($deleteOutsourcingProList as $kWDel => $vWDel) {
                                if ($vWDel['quantity_import'] > 0) {
                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_withdrawal_' . $kWDel . '_real_quantity\').focus();$(\'tr#rows' . $kWDel . ' input\').addClass(\'error\');">15. ' . $vWDel['product_code'] . ' - ' . $vWDel['product_name'] . ': ' . $this->view->renderLabel('can_not_delete') . '</a></li>';
                                    $flagCheck = FALSE;
                                } else {
                                    $proOutsourcingDel[$kWDel]['id'] = $vWDel['id'];
                                    $proOutsourcingDel[$kWDel]['status'] = 'DELETED';
                                    $proOutsourcingDel[$kWDel]['log'] = $vWDel['log'];
                                    $proOutsourcingDel[$kWDel]['log'] .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                    $proOutsourcingDel[$kWDel]['log'] .= " - Action: DELETE\n";
                                    $proOutsourcingDel[$kWDel]['log'] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                }
                            }
                            $deleteProMaterialList = array_diff_key($originCurentProMaterialList, $proMaterialEditedList);
                            $proMaterialDel = array();
                            if ($deleteProMaterialList) {
                                $extraExistsCond = ' AND tbl_processed_materials.id NOT IN ("' . implode('","', array_keys($deleteProMaterialList)) . '")';
                                $checkExistsMaterial = $this->model->checkMaterialList($id, $extraExistsCond);
                                $checkExistsStockoutMaterial = $this->model->checkStockoutMaterialList($id);
                                $itemStockoutTransferList = $this->model->getArrLocalStockOutIdList($id);
                                $checkExistsReturnMaterial = $this->model->checkReturnMaterialList(implode('","', array_keys($itemStockoutTransferList)));
                                foreach ($deleteProMaterialList as $kMDel => $vMDel) {
                                    if ($originCurentProList[$kMDel]['quantity_import'] > 0) {
                                        $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'tr#rows_material_' . $kMDel . ' input\').addClass(\'error\');">16. ' . $vMDel['product_code'] . ' - ' . $vMDel['product_name'] . ': ' . $this->view->renderLabel('warehoused') . ', ' . $this->view->renderLabel('can_not_delete_material') . '</a></li>';
                                        $flagCheck = FALSE;
                                    } else {
                                        if (in_array($vMDel['product_id'], array_keys($checkExistsMaterial))) {
                                            $proMaterialDel[$kMDel]['id'] = $vMDel['id'];
                                            $proMaterialDel[$kMDel]['status'] = 'DELETED';
                                            $proMaterialDel[$kMDel]['log'] = $vMDel['log'];
                                            $proMaterialDel[$kMDel]['log'] .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                            $proMaterialDel[$kMDel]['log'] .= " - Action: DELETE\n";
                                            $proMaterialDel[$kMDel]['log'] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                        } else {
                                            if (in_array($vMDel['product_id'], array_keys($checkExistsStockoutMaterial))) {
                                                if (!isset($checkExistsReturnMaterial[$vMDel['product_id']])) {
                                                    $checkExistsReturnMaterial[$vMDel['product_id']]['total_quantity'] = 0;
                                                }
                                                if (round(($checkExistsStockoutMaterial[$vMDel['product_id']]['total_quantity'] - $checkExistsReturnMaterial[$vMDel['product_id']]['total_quantity']), 2) > 0) {
                                                    $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'tr#rows_material_' . $kMDel . ' input\').addClass(\'error\');">17. ' . $vMDel['product_code'] . ' - ' . $vMDel['product_name'] . ': ' . $this->view->renderLabel('has_been_issued') . '</a></li>';
                                                    $flagCheck = FALSE;
                                                } else {
                                                    $proMaterialDel[$kMDel]['id'] = $vMDel['id'];
                                                    $proMaterialDel[$kMDel]['status'] = 'DELETED';
                                                    $proMaterialDel[$kMDel]['log'] = $vMDel['log'];
                                                    $proMaterialDel[$kMDel]['log'] .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                                    $proMaterialDel[$kMDel]['log'] .= " - Action: DELETE\n";
                                                    $proMaterialDel[$kMDel]['log'] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                                }
                                            } else {
                                                $proMaterialDel[$kMDel]['id'] = $vMDel['id'];
                                                $proMaterialDel[$kMDel]['status'] = 'DELETED';
                                                $proMaterialDel[$kMDel]['log'] = $vMDel['log'];
                                                $proMaterialDel[$kMDel]['log'] .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                                $proMaterialDel[$kMDel]['log'] .= " - Action: DELETE\n";
                                                $proMaterialDel[$kMDel]['log'] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                            }
                                        }
                                    }
                                }
                            }
                            if ($proNewList) {
                                foreach ($proNewList as $keyNew => $proNew) {
                                    $arrProductIdList[$proNew['real_id']] = $proNew['real_id'];
                                    if ($proNew['real_quantity'] > 0) {
                                        $existProductReal[$keyNew] = $this->_allProduct[$proNew['real_id']];
                                        if ($existProductReal[$keyNew] == array()) {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_new_s' . $keyNew . '_real_code\').focus();$(\'tr#rows' . $keyNew . ' input\').addClass(\'error\');$(\'tr#rows' . $keyNew . ' textarea\').addClass(\'error\');">18. ' . $proNew['real_code'] . ' - ' . $proNew['real_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                            $flagCheck = FALSE;
                                        } else if ($proNew['real_name'] != $existProductReal[$keyNew]['name'] || $proNew['real_specs'] != $existProductReal[$keyNew]['specification'] || $proNew['real_unit'] != $existProductReal[$keyNew]['unit']) {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_new_s' . $keyNew . '_real_code\').focus();$(\'tr#rows' . $keyNew . ' input\').addClass(\'error\');$(\'tr#rows' . $keyNew . ' textarea\').addClass(\'error\');">19. ' . $proNew['real_code'] . ' - ' . $proNew['real_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                            $flagCheck = FALSE;
                                        } else if (!is_numeric($proNew['real_quantity'])) {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#records_new_s' . $keyNew . '_real_quantity\').focus();$(\'#records_new_s' . $keyNew . '_real_quantity\').addClass(\'error\');">20. ' . $proNew['real_code'] . ' - ' . $proNew['real_name'] . ': ' . $this->view->renderLabel('invalid_quantity') . '</a></li>';
                                            $flagCheck = FALSE;
                                        }
                                    } else {
                                        unset($proNewList[$keyNew]);
                                    }
                                }
                            }
                            $productNormsInfoList = $this->model->getProductNormsInfo(' AND tbl_product.id IN ("' . implode('","', $arrProductIdList) . '")');
                            if ($proMaterialNewList) {
                                foreach ($proMaterialNewList as $proOSKeyNew => $newMaterial) {
                                    foreach ($newMaterial as $kmn => $promn) {
                                        if (is_numeric($promn['real_norm']) && $promn['real_norm'] > 0) {
                                            $existProductReal[$kmn] = $this->_allProduct[$promn['real_id']];
                                            if ($existProductReal[$kmn] == array()) {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#new_list_material_n' . $kmn . '_real_code\').focus();$(\'tr#row_material_n' . $kmn . ' input\').addClass(\'error\');$(\'tr#rows' . $kmn . ' textarea\').addClass(\'error\');">21. ' . $promn['real_code'] . ' - ' . $promn['real_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                                $flagCheck = FALSE;
                                            } else if ($promn['real_name'] != htmlspecialchars_decode($existProductReal[$kmn]['name']) || $promn['real_specs'] != htmlspecialchars_decode($existProductReal[$kmn]['specification']) || $promn['real_unit'] != $existProductReal[$kmn]['unit']) {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#new_list_material_n' . $kmn . '_real_code\').focus();$(\'tr#row_material_n' . $kmn . ' input\').addClass(\'error\');$(\'tr#rows' . $kmn . ' textarea\').addClass(\'error\');">22. ' . $promn['real_code'] . ' - ' . $promn['real_name'] . ': ' . $this->view->renderLabel('product_information_incorrect') . '</a></li>';
                                                $flagCheck = FALSE;
                                            }
                                            //                                            if ($originCurentProList[$proOSKeyNew]['quantity_import'] > 0 && (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') || !in_array('MANAGER', Session::get('group')))) {
                                            if ($originCurentProList[$proOSKeyNew]['quantity_import'] > 0 && $curItem['status'] != 'APPROVED') {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#new_list_material_n' . $kmn . '_real_code\').focus();$(\'tr#row_material_n' . $kmn . ' input\').addClass(\'error\');$(\'tr#rows' . $kmn . ' textarea\').addClass(\'error\');">23. ' . $promn['real_code'] . ' - ' . $promn['real_name'] . ': ' . $this->view->renderLabel('cannot_add') . '</a></li>';
                                                $flagCheck = FALSE;
                                            }
                                            if (strpos($productNormsInfoList[$originCurentProList[$proOSKeyNew]['product_id']]['production_norms'], '|') !== FALSE && $curItem['status'] != 'APPROVED') {
                                                $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#new_list_material_n' . $kmn . '_real_code\').focus();$(\'tr#row_material_n' . $kmn . ' input\').addClass(\'error\');$(\'tr#rows' . $kmn . ' textarea\').addClass(\'error\');">24. ' . $promn['real_code'] . ' - ' . $promn['real_name'] . ': ' . $this->view->renderLabel('cannot_add') . '</a></li>';
                                                $flagCheck = FALSE;
                                            }
                                        } else {
                                            $errors .= '<li><a href="javascript:void(0);" onclick="javascript:$(\'#new_list_material_n' . $kmn . '_real_norm\').focus();$(\'#new_list_material_n' . $kmn . '_real_norm\').addClass(\'error\');">25. ' . $promn['real_code'] . ' - ' . $promn['real_name'] . ': ' . $this->view->renderLabel('invalid_norm') . '</a></li>';
                                            $flagCheck = FALSE;
                                        }
                                        $arrMaterialPriceList[$proOSKeyNew] += Systems::displayFloatNumber(($promn['real_norm'] * $promn['real_price']), 0);
                                    }
                                }
                            }
                            if ($errors) {
                                $errors = '<ul id="error">' . $errors . '</ul>';
                            }
                            if ($flagCheck) {
                                if ($proList || $proNewList) {
                                    $dataCusInfo['customer_id'] = $data['customer_id'];
                                    $dataCusInfo['user_accountant'] = $customerInfo[$data['customer_id']]['user_id_list'];
                                    $dataCusInfo['user_id'] = $customerInfo[$data['customer_id']]['user_id'];
                                    $dataCusInfo['seller_id'] = $customerInfo[$data['customer_id']]['seller_id'];
                                    //                Update tbl_outsourcing log
                                    $modify = $curItem['log'];
                                    $strMod = '';
                                    foreach ($curItem as $field => $curValue) {
                                        if (isset($dataCusInfo[$field]) && $dataCusInfo[$field] != $curValue) {
                                            $strMod .= ' - ' . 'Old ' . $field . ': ' . $curValue . "\n";
                                            $strMod .= '   ' . 'New ' . $field . ': ' . $dataCusInfo[$field] . "\n";
                                        }
                                    }
                                    if ($curItem['status'] == 'APPROVED') {
                                        $strMod .= " - Status: APPROVED -> PENDING\n";
                                        $dataCusInfo['status'] = 'PENDING';
                                        $dataCusInfo['modify_time'] = '0';
                                    }
                                    if ($curItem['status'] == 'CANCELING') {
                                        $strMod .= " - Status: CANCELING -> PENDING\n";
                                        $dataCusInfo['status'] = 'PENDING';
                                        $dataCusInfo['modify_time'] = '0';
                                    }
                                    if ($strMod) {
                                        $modify .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n" . $strMod;
                                        $modify .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                    }
                                    //                                    $dataCusInfo['modify_time'] = time();
                                    $dataCusInfo['log'] = $modify;
                                    unset($modify);
                                    unset($strMod);
                                    $this->model->editSave('tbl_outsourcing', $dataCusInfo);
                                    if (empty($proList)) {
                                        $proList = array();
                                    }
                                    if ($proOutsourcingDel) {
                                        foreach ($proOutsourcingDel as $outSourcingDel) {
                                            $this->model->editSave('tbl_outsourcing_product', $outSourcingDel);
                                        }
                                    }
                                    if ($proMaterialDel && $curItem['status'] == 'APPROVED') {
                                        foreach ($proMaterialDel as $materialDel) {
                                            $this->model->editSave('tbl_processed_materials', $materialDel);
                                        }
                                    }
                                    $dataUpdatePCPrice = array();
                                    if ($proList) {
                                        $proOutsourcingEdit = array();
                                        $endImportedList = $this->model->getEndImportedList(' AND tbl_end_product.outsourcing_product_id NOT IN ("' . implode('","', array_keys($proList)) . '")');
                                        foreach ($proList as $kW => $vW) {
                                            $strModProAdd[$kW] = '';
                                            if ($vW['real_quantity'] != $originCurentProList[$kW]['quantity']) {
                                                $strModProAdd[$kW] .= ' - ' . 'Old quantity: ' . $originCurentProList[$kW]['quantity'] . "\n";
                                                $strModProAdd[$kW] .= '   ' . 'New quantity: ' . $vW['real_quantity'] . "\n";
                                                $proOutsourcingEdit[$kW]['quantity'] = $vW['real_quantity'];
                                            }
                                            $strModEndImported = '';
                                            $dataEndImportedUpdate = array();
                                            //                                            if ($originCurentProList[$kW]['quantity_import'] == 0 || ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') && in_array('MANAGER', Session::get('group')))) {
                                            if ($vW['real_hire_price'] != $originCurentProList[$kW]['hire_price']) {
                                                $strModProAdd[$kW] .= ' - ' . 'Old hire_price: ' . $originCurentProList[$kW]['hire_price'] . "\n";
                                                $strModProAdd[$kW] .= '   ' . 'New hire_price: ' . $vW['real_hire_price'] . "\n";
                                                if ($dataCusInfo['construction'] == 'OUTSIDE_HOURS') {
                                                    $vW['real_hire_price'] = $productNormsInfoList[$vW['real_id']]['overtime_cost'];
                                                }
                                                if ($dataCusInfo['construction'] == 'OFFICE_HOURS') {
                                                    $vW['real_hire_price'] = $productNormsInfoList[$vW['real_id']]['regular_cost'];
                                                }
                                                $proOutsourcingEdit[$kW]['hire_price'] = $vW['real_hire_price'];
                                                if ($originCurentProList[$kW]['quantity_import'] > 0) {
                                                    //                                                    Cap nhat lai tien cong <br />;
                                                    $strModEndImported .= ' - ' . 'Old hire_price: ' . $originCurentProList[$kW]['hire_price'] . "\n";
                                                    $strModEndImported .= '   ' . 'New hire_price: ' . $vW['real_hire_price'] . "\n";
                                                    $dataEndImportedUpdate['hire_price_update'] = $vW['real_hire_price'];
                                                } else {
                                                    //                                                    Khong cap nhat lai tien cong <br />;
                                                    $dataEndImportedUpdate['hire_price_update'] = $originCurentProList[$kW]['hire_price'];
                                                }
                                            } else {
                                                //                                                Khong cap nhat lai tien cong 2<br />;
                                                $dataEndImportedUpdate['hire_price_update'] = $originCurentProList[$kW]['hire_price'];
                                            }
                                            if ($arrMaterialPriceList[$kW] + $vW['real_hire_price'] != $originCurentProList[$kW]['price']) {
                                                $strModProAdd[$kW] .= ' - ' . 'Old price: ' . $originCurentProList[$kW]['price'] . "\n";
                                                $strModProAdd[$kW] .= '   ' . 'New price: ' . ($arrMaterialPriceList[$kW] + $vW['real_hire_price']) . "\n";
                                                $proOutsourcingEdit[$kW]['price'] = $arrMaterialPriceList[$kW] + $vW['real_hire_price'];
                                                if ($originCurentProList[$kW]['quantity_import'] > 0) {
                                                    //                                                    Cap nhat lai gia thanh pham
                                                    $strModEndImported .= ' - ' . 'Auto update price: ' . $originCurentProList[$kW]['price'] . ' -> ' . ($arrMaterialPriceList[$kW] + $vW['real_hire_price']) . "\n";
                                                    $dataEndImportedUpdate['price_update'] = ($arrMaterialPriceList[$kW] + $vW['real_hire_price']);
                                                } else {
                                                    //                                                    Khong cap nhat lai gia thanh pham <br />;
                                                    $dataEndImportedUpdate['price_update'] = $originCurentProList[$kW]['price'];
                                                }
                                            } else {
                                                //                                                Khong cap nhat lai gia thanh pham 2<br />;
                                                $dataEndImportedUpdate['price_update'] = $originCurentProList[$kW]['price'];
                                            }
                                            if ($strModEndImported) {
                                                $dataEndImportedUpdate['outsourcing_product_id'] = $kW;
                                                $dataEndImportedUpdate['log_add'] = ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                                $dataEndImportedUpdate['log_add'] .= ' - Edited outsourcing_id: ' . $id . "\n";
                                                $dataEndImportedUpdate['log_add'] .= $strModEndImported;
                                                $dataEndImportedUpdate['log_add'] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                                $this->model->updateEndImportedPrice($dataEndImportedUpdate);
                                                $dataUpdatePCPrice[$originCurentProList[$kW]['product_id']]['quantity'] = '0';
                                                $dataUpdatePCPrice[$originCurentProList[$kW]['product_id']]['create_time'] = $endImportedList[$originCurentProList[$kW]['product_id']]['create_time'];
                                                $dataUpdatePCPrice[$originCurentProList[$kW]['product_id']]['stockin'] = 'MAIN';
                                            }
                                            //                                            }
                                            if ($strModProAdd[$kW]) {
                                                $strModPro[$kW] = ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                                $strModPro[$kW] .= $strModProAdd[$kW];
                                                $strModPro[$kW] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                                $proOutsourcingEdit[$kW]['id'] = $originCurentProList[$kW]['id'];

                                                if ($vW['real_quantity'] == $originCurentProList[$kW]['quantity_import']) {
                                                    $proOutsourcingEdit[$kW]['status'] = 'FINISHED';
                                                } else {
                                                    if ($originCurentProList[$kW]['status'] == 'FINISHED') {
                                                        $proOutsourcingEdit[$kW]['status'] = 'PENDING';
                                                    }
                                                }
                                                $proOutsourcingEdit[$kW]['log'] = $originCurentProList[$kW]['log'] . $strModPro[$kW];
                                                $this->model->editSave('tbl_outsourcing_product', $proOutsourcingEdit[$kW]);
                                            }
                                        }
                                    }
                                    if ($proMaterialEditedList) {
                                        $proMaterialEdit = array();
                                        foreach ($proMaterialEditedList as $keyME => $valueME) {
                                            $strModMaterialAdd[$keyME] = '';
                                            if ($valueME['real_norm'] != $originCurentProMaterialList[$keyME]['product_norm']) {
                                                $strModMaterialAdd[$keyME] .= ' - ' . 'Old product_norm: ' . $originCurentProMaterialList[$keyME]['product_norm'] . "\n";
                                                $strModMaterialAdd[$keyME] .= '   ' . 'New product_norm: ' . $valueME['real_norm'] . "\n";
                                                $proMaterialEdit[$keyME]['product_norm'] = $valueME['real_norm'];
                                            }
                                            if ($valueME['real_price'] != $originCurentProMaterialList[$keyME]['product_price']) {
                                                $strModMaterialAdd[$keyME] .= ' - ' . 'Old product_price: ' . $originCurentProMaterialList[$keyME]['product_price'] . "\n";
                                                $strModMaterialAdd[$keyME] .= '   ' . 'New product_price: ' . $valueME['real_price'] . "\n";
                                                $proMaterialEdit[$keyME]['product_price'] = $valueME['real_price'];
                                            }
                                            $proMaterialEdit[$keyME]['id'] = $keyME;
                                            $proMaterialEdit[$keyME]['quantity_needed'] = round(($valueME['real_norm'] * $proList[$originCurentProMaterialList[$keyME]['outsourcing_product_id']]['real_quantity']), 2);
                                            if ($strModMaterialAdd[$keyME]) {
                                                $strModMaterial[$keyME] = ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                                                $strModMaterial[$keyME] .= $strModMaterialAdd[$keyME];
                                                $strModMaterial[$keyME] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                                                $proMaterialEdit[$keyME]['log'] = $originCurentProMaterialList[$keyME]['log'] . $strModMaterial[$keyME];
                                            }
                                            $this->model->editSave('tbl_processed_materials', $proMaterialEdit[$keyME]);
                                        }
                                    }
                                    if ($proNewList) {
                                        $outProId = array();
                                        $autoMaterialData = array();
                                        $stringProductionNormsList = array();
                                        $stringNormsList = array();
                                        $proInsOutsourcingData = array();
                                        $endProductPrice = array();
                                        foreach ($proNewList as $kWNew => $vWNew) {
                                            $proInsOutsourcingData[$kWNew]['outsourcing_id'] = $id;
                                            $proInsOutsourcingData[$kWNew]['product_id'] = $vWNew['real_id'];
                                            $proInsOutsourcingData[$kWNew]['product_code'] = $vWNew['real_code'];
                                            $proInsOutsourcingData[$kWNew]['product_name'] = $vWNew['real_name'];
                                            $proInsOutsourcingData[$kWNew]['product_specs'] = $vWNew['real_specs'];
                                            $proInsOutsourcingData[$kWNew]['product_unit'] = $vWNew['real_unit'];
                                            $proInsOutsourcingData[$kWNew]['quantity'] = $vWNew['real_quantity'];
                                            if ($dataCusInfo['construction'] == 'OUTSIDE_HOURS') {
                                                $vWNew['real_hire_price'] = $productNormsInfoList[$vWNew['real_id']]['overtime_cost'];
                                            }
                                            if ($dataCusInfo['construction'] == 'OFFICE_HOURS') {
                                                $vWNew['real_hire_price'] = $productNormsInfoList[$vWNew['real_id']]['regular_cost'];
                                            }
                                            $proInsOutsourcingData[$kWNew]['hire_price'] = $vWNew['real_hire_price'];
                                            $proInsOutsourcingData[$kWNew]['price'] = $vWNew['real_hire_price'];
                                            $proInsOutsourcingData[$kWNew]['datatype'] = 'PRODUCT';
                                            $proInsOutsourcingData[$kWNew]['status'] = 'PENDING';
                                            $proInsOutsourcingData[$kWNew]['create_time'] = time();
                                            $outProId[$kWNew] = $this->model->create('tbl_outsourcing_product', $proInsOutsourcingData[$kWNew]);
                                            if ($productNormsInfoList[$vWNew['real_id']]['production_norms']) {
                                                $endProductPrice[$kWNew] = $vWNew['real_hire_price'];
                                                $stringProductionNormsList[$kWNew] = explode("\n", $productNormsInfoList[$vWNew['real_id']]['production_norms']);
                                                foreach ($stringProductionNormsList[$kWNew] as $kn => $productionNormsList) {
                                                    $stringNormsList[$kn] = explode('|', $productionNormsList);
                                                    if (isset($this->_allProduct[$stringNormsList[$kn][0]])) {
                                                        $autoMaterialData[$kn]['outsourcing_product_id'] = $outProId[$kWNew];
                                                        $autoMaterialData[$kn]['product_id'] = $stringNormsList[$kn][0];
                                                        $autoMaterialData[$kn]['product_norm'] = $stringNormsList[$kn][1];
                                                        $autoMaterialData[$kn]['quantity_needed'] = round(($stringNormsList[$kn][1] * $vWNew['real_quantity']), 2);
                                                        $autoMaterialData[$kn]['product_price'] = $this->_allProduct[$stringNormsList[$kn][0]]['price'];
                                                        $autoMaterialData[$kn]['status'] = 'NORMAL';
                                                        $autoMaterialData[$kn]['create_time'] = time();
                                                        $this->model->create('tbl_processed_materials', $autoMaterialData[$kn]);
                                                        $endProductPrice[$kWNew] += Systems::displayFloatNumber(($autoMaterialData[$kn]['product_norm'] * $autoMaterialData[$kn]['product_price']), 0);
                                                    }
                                                }
                                                if ($endProductPrice[$kWNew] != $vWNew['real_hire_price']) {
                                                    $this->model->editSave('tbl_outsourcing_product', array('id' => $outProId[$kWNew], 'price' => $endProductPrice[$kWNew]));
                                                }
                                            }
                                        }
                                        unset($proInsOutsourcingData);
                                    }
                                    $proInsMaterialData = array();
                                    if ($proMaterialNewList) {
                                        foreach ($proMaterialNewList as $keyNewMaterial => $newMaterial) {
                                            foreach ($newMaterial as $kmn => $promn) {
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['outsourcing_product_id'] = $keyNewMaterial;
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['product_id'] = $promn['real_id'];
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['product_norm'] = $promn['real_norm'];
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['quantity_needed'] = round(($promn['real_norm'] * $proList[$keyNewMaterial]['real_quantity']), 2);
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['product_price'] = $promn['real_price'];
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['status'] = 'NORMAL';
                                                $proInsMaterialData[$keyNewMaterial . $kmn]['create_time'] = time();
                                            }
                                        }
                                        if ($proInsMaterialData) {
                                            $this->model->insertMulti('tbl_processed_materials', $proInsMaterialData);
                                        }
                                        unset($proInsMaterialData);
                                    }
                                    if ($dataUpdatePCPrice) {
                                        $this->updateProductPCPrice($dataUpdatePCPrice);
                                    }
                                } else {
                                    throw new Exception($this->view->renderLabel('invalid_product_withdrawal_list'));
                                }
                            } else {
                                throw new Exception($errors);
                            }
                            Link::redirectAdminCurrent();
                        } else {
                            throw new Exception($this->view->renderLabel('invalid_product_withdrawal_list'));
                        }
                    } else {
                        throw new Exception($this->view->renderLabel('invalid_buyer_company'));
                    }
                } else {
                    Link::redirectAdminCurrent();
                }
            } else {
                Link::redirectAdminCurrent();
            }
        } catch (Exception $exc) {
            $this->view->error = $exc->getMessage();
            return $this->edit($id);
        }
    }

    /**
     * Delete outsourcing order
     */
    public function deleteOrder($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'delete')) {
            Link::accessDenied();
        }
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            $extra_cond = '';
        } else {
            $extra_cond = ' AND tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '"';
        }
        $currentItem = $this->model->itemSingleList($id, $extra_cond);
        if ($currentItem['status'] == 'PENDING' || $currentItem['status'] == 'CANCELING') {
            $canDelete = TRUE;
            $proList = $this->model->productOutsourceList($id);
            if ($currentItem['status'] == 'PENDING') {
                $itemStockoutTransferList = $this->model->getStockOutByOutsource($id);
                if ($itemStockoutTransferList) {
                    $canDelete = FALSE;
                }
            }
            foreach ($proList as $pValue) {
                if ($pValue['quantity_import'] > 0) {
                    $canDelete = FALSE;
                    break;
                }
            }
            $strLogAdd = ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
            $strLogAdd .= " - Action: DELETE\n";
            $strLogAdd .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
            if ($canDelete) {
                $poProList = $this->_getQuantityAfterPo($currentItem['po_id']);
                foreach ($proList as $pValue) {
                    if ($poProList[$pValue['product_id']]['quantity_after_po'] >= 0) {
                        $this->model->editSave('tbl_outsourcing_product', array('id' => $pValue['id'], 'status' => 'DELETED', 'log' => $pValue['log'] . $strLogAdd));
                    }
                }
                $itemConfirm = $currentItem['log'] . $strLogAdd;
                $this->model->editSave('tbl_outsourcing', array('id' => $id, 'status' => 'DELETED', 'log' => $itemConfirm));
                Link::redirectAdminCurrent();
            } else {
                Link::redirectAdminCurrent(array('cmd' => 'view', 'id' => $id));
            }
        } else {
            Link::redirectAdminCurrent();
        }
    }

    /**
     * Print stock out
     */
    public function printer($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'print')) {
            Link::accessDenied();
        }
        if (is_numeric($id)) {
            $stringContent = $this->_setBillingContent($id);
            $this->view->printContent($stringContent);
        } else {
            Link::redirectAdminCurrent();
        }
    }

    /**
     * Confirm to delete outsourcing order
     */
    public function approvedelete($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved')) {
            Link::accessDenied();
        }
        if (is_numeric($id)) {
            $itemStockoutTransferList = $this->model->getStockOutByOutsource($id);
            if ($itemStockoutTransferList) {
                if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
                    $extra_cond = '';
                } else {
                    $extra_cond = ' AND tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '"';
                }
                $currentItem = $this->model->itemSingleList($id, $extra_cond);
                if ($currentItem['status'] == 'PENDING') {
                    $itemConfirm = $currentItem['log'];
                    $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                    $itemConfirm .= " - Status: PENDING -> CANCELING\n";
                    $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                    $this->model->editSave('tbl_outsourcing', array('id' => $id, 'status' => 'CANCELING', 'log' => $itemConfirm));
                    unset($itemConfirm);
                }
            }
        }
        Link::redirectAdminCurrent(array('cmd' => 'view', 'id' => $id));
    }

    /**
     * Cancel delete outsourcing order
     */
    public function cancelDelete($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') || !in_array('MANAGER', Session::get('group'))) {
            Link::accessDenied();
        }
        if (is_numeric($id)) {
            $currentItem = $this->model->itemSingleList($id);
            if ($currentItem['status'] == 'CANCELING') {
                $itemConfirm = $currentItem['log'];
                $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                $itemConfirm .= " - Status: CANCELING -> PENDING\n";
                $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                $this->model->editSave('tbl_outsourcing', array('id' => $id, 'status' => 'PENDING', 'log' => $itemConfirm));
                unset($itemConfirm);
            }
        }
        Link::redirectAdminCurrent(array('cmd' => 'view', 'id' => $id));
    }

    /**
     * Confirm delivery order
     */
    public function finishOrder($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) {
            Link::accessDenied();
        }
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            $extra_cond = '';
        } else {
            $extra_cond = ' AND tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '"';
        }
        $this->_confirmDelivered($id, $extra_cond);
        Link::redirectAdminCurrent();
    }

    /**
     * Confirm delivery list order
     */
    public function finishListOrder() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) {
            Link::accessDenied();
        }
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
            $extra_cond = '';
        } else {
            $extra_cond = ' AND tbl_outsourcing.user_create_id = "' . Session::get('user_id') . '"';
        }
        $id_list = Link::get('chkid');
        if (is_array($id_list)) {
            foreach ($id_list as $id) {
                $this->_confirmDelivered($id, $extra_cond);
            }
        }
        Link::redirectAdminCurrent();
    }

    public function storeDeptSave() {
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'lock') && in_array('STORE', Session::get('group'))) {
            $cond = ' AND tbl_outsourcing.`status` = "PENDING"';
            $records = Link::getPost('records');
            $currentItem = $this->model->itemSingleList($records['id'], $cond);
            if ($currentItem) {
                $modify = $currentItem['log'];
                $data['id'] = $records['id'];
                $strMod = '';
                $data['handler_id_list'] = '|' . implode('|', $records['handler_id_list']) . '|';
                $data['num_hours'] = $records['num_hours'];
                if ($currentItem['warehouse_note'] != $records['warehouse_note']) {
                    $data['warehouse_note'] = $records['warehouse_note'];
                    $data['time_warehouse_note'] = time();
                }
                foreach ($records as $field => $record) {
                    if ($field == 'handler_id_list') {
                        $record = '|' . implode('|', $record) . '|';
                    }
                    if ($currentItem[$field] != $record) {
                        $strMod .= " - ACTION: Edit $field\n";
                        $strMod .= ' - Old ' . $field . ': ' . $currentItem[$field] . "\n";
                        $strMod .= '   New ' . $field . ': ' . $record . "\n";
                    }
                }
                if ($strMod) {
                    $modify .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n" . $strMod;
                    $modify .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                    $data['log'] = $modify;
                    $this->model->editSave('tbl_outsourcing', $data);
                }
            }
        }
        Link::redirectAdminCurrent();
    }


    /**
     * Summary of confirmComplete
     * @param mixed $id
     * @return never
     */
    public function confirmComplete($id = '') {
        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'lock') && in_array('STORE', Session::get('group'))) {
            if (is_numeric($id)) {
                $currentItem = $this->model->itemSingleList($id, ' AND tbl_outsourcing.`status` = "PENDING" AND tbl_outsourcing.time_complete = "0"');
                if ($currentItem) {
                    $itemConfirm = $currentItem['log'];
                    $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                    $itemConfirm .= " - ACTION: COMFIRM COMPLETE\n";
                    $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                    $this->model->editSave('tbl_outsourcing', array('id' => $id, 'time_complete' => time(), 'log' => $itemConfirm));
                    unset($itemConfirm);
                }
            }
        }
        Link::redirectAdminCurrent();
    }

    /**
     * Summary of statisticOutsourcing
     * @return mixed
     */
    public function statisticOutsourcing() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'view')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('outsourcing_order_statistics');
        // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
        $cond = '';
        if (Link::get('cbMonth') && Link::get('cbYear')) {
            $yearMonth = Link::get('cbYear') . (Link::get('cbMonth') > 9 ? Link::get('cbMonth') : '0' . Link::get('cbMonth'));
        } else {
            $yearMonth = date('Ym');
        }
        $cond .= ' AND DATE_FORMAT(tbl_outsourcing.date_out,"%Y%m") = "' . $yearMonth . '"';
        $this->view->itemsList = array();
        $getStatisticOutsourcing = $this->model->getStatisticOutsourcing($cond);
        $countDayBz = new BusinessTimeCalc();
        $total = $orderOnTime = $orderDelayed = $dayDelay = 0;
        $outsourceIdList = $ontimeIdList = $delayIdList = '';
        foreach ($getStatisticOutsourcing as $key => $value) {
            $total++;
            if (empty($outsourceIdList)) {
                $outsourceIdList = $key;
            } else {
                $outsourceIdList .= ', ' . $key;
            }
            if ($value['status'] == 'FINISHED') {
                if ($value['time_finished'] <= $value['expired_date']) {
                    $orderOnTime++;
                    if (empty($ontimeIdList)) {
                        $ontimeIdList = $key;
                    } else {
                        $ontimeIdList .= ', ' . $key;
                    }
                } else {
                    $orderDelayed++;
                    if (empty($delayIdList)) {
                        $delayIdList = $key;
                    } else {
                        $delayIdList .= ', ' . $key;
                    }
                    $dayDelay += $countDayBz->countDayWorking(Systems::displayVnDate($value['expired_date']), date('d/m/Y'), true);
                }
            } else {
                if ($value['expired_date'] < date('Y-m-d')) {
                    $orderDelayed++;
                    if (empty($delayIdList)) {
                        $delayIdList = $key;
                    } else {
                        $delayIdList .= ', ' . $key;
                    }
                    $dayDelay += $countDayBz->countDayWorking(Systems::displayVnDate($value['expired_date']), date('d/m/Y'), true);
                }
            }
        }
        $this->view->itemsList['total'] = $total;
        $this->view->itemsList['outsource_id_list'] = $outsourceIdList;
        $this->view->itemsList['order_on_time'] = $orderOnTime;
        $this->view->itemsList['ontime_id_list'] = $ontimeIdList;
        $this->view->itemsList['order_delayed'] = $orderDelayed;
        $this->view->itemsList['day_delay'] = $dayDelay - 1;
        $this->view->itemsList['delay_id_list'] = $delayIdList;
        $content = $this->view->render('adminoutsourcing/statistic_outsourcing', array(
            'subMenuList' => $this->view->subMenuList,
            'itemsList' => $this->view->itemsList
        ), 'plugins/AdminOutsourcing/');
        return $content;
    }

    private function _getCondProList() {
        $cond = ' AND `tbl_product`.`data_type` = "OUTSOURCE"';
        if (Link::get('txtFindData')) {
            $cond .= ' AND (`tbl_product`.`code` = "' . Link::get('txtFindData') . '" OR `tbl_product`.`name` LIKE "%' . mb_strtolower(Link::get('txtFindData'), 'utf-8') . '%" OR `tbl_product`.`name` LIKE "%' . mb_strtoupper(Link::get('txtFindData'), 'utf-8') . '%" OR `tbl_product`.`name` LIKE "%' . Link::get('txtFindData') . '%" OR `tbl_product`.`specification` LIKE "%' . Link::get('txtFindData') . '%" OR `tbl_product`.`other_information` LIKE "%' . Link::get('txtFindData') . '%")';
        }
        if (Link::get('code')) {
            $cond .= ' AND `tbl_product`.`code` = "' . Link::get('code') . '"';
        } else {
            if (Link::get('list_code')) {
                $cond .= ' AND `tbl_product`.`code` IN ("' . str_replace(' ', '","', Link::get('list_code')) . '")';
            }
        }
        if (Link::get('name')) {
            $cond .= ' AND CONVERT(CAST(CONVERT(`tbl_product`.`name` USING latin1) AS BINARY) USING utf8) LIKE _utf8 "%' . Link::get('name') . '%" COLLATE utf8_general_ci';
        }
        if (Link::get('specs')) {
            $cond .= ' AND `tbl_product`.`specification` LIKE "%' . Link::get('specs') . '%"';
        }
        return $cond;
    }

    /**
     * Summary of index
     * @return mixed
     */
    function list_product() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'view')) {
            Link::accessDenied();
        }
        Page::$title = $this->view->renderLabel('list_product_outsourcing');
        $cond = $this->_getCondProList();
        $pagesize = (Link::get('pagesize') > 0) ? Link::get('pagesize') : 50;
        $start = ($this->view->page_no() - 1) * $pagesize;
        // $this->view->subMenuList = $this->category->getCategoryList('tbl_admin_menu.parent_id = "11"');
        $this->view->productList = $this->model->getListProduct($cond, $start, $pagesize);
        $index = 1;
        foreach ($this->view->productList as $key => $value) {
            $this->view->productList[$key]['index'] = $start + $index;
            if ($value['production_norms']) {
                $this->view->productList[$key]['production_norms_list'] = array();
                $stringProductionNormsList = explode("\n", $value['production_norms']);
                foreach ($stringProductionNormsList as $kn => $productionNormsList) {
                    $stringNormsList = explode('|', $productionNormsList);
                    $this->view->productList[$key]['production_norms_list'][$kn]['pro_id'] = $stringNormsList[0];
                    if (isset($this->_allProduct[$stringNormsList[0]])) {
                        $this->view->productList[$key]['production_norms_list'][$kn]['pro_code'] = $this->_allProduct[$stringNormsList[0]]['code'];
                    } else {
                        $this->view->productList[$key]['production_norms_list'][$kn]['pro_code'] = $stringNormsList[0];
                    }
                    $this->view->productList[$key]['production_norms_list'][$kn]['pro_norms'] = $stringNormsList[1];
                }
            } else {
                $this->view->productList[$key]['production_norms_list'] = array();
            }
            $index++;
        }
        $total_record = $this->model->getTotalProducts($cond);
        $this->view->totalRecord = $total_record['total_record'];
        $this->view->totalPage = $this->view->totalPage($this->view->totalRecord, $pagesize);
        $this->view->pagingList = $this->view->paging($this->view->totalRecord, $pagesize, FALSE);
        $content = $this->view->render('adminoutsourcing/list_product', array(
            'error' => $this->view->error,
            'totalRecord' => $this->view->totalRecord,
            'totalPage' => $this->view->totalPage,
            'pagingList' => $this->view->pagingList,
            'subMenuList' => $this->view->subMenuList,
            'productList' => $this->view->productList
        ), 'plugins/AdminOutsourcing/');
        return $content;
    }

    public function saveProduct() {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) {
            Link::accessDenied();
        }
        try {
            $newRecords = Link::getPost('records_new');
            if ($newRecords && is_array($newRecords)) {
                $arrProCode = array_unique(array_column($newRecords, 'code'));
                $currentItemList = $this->model->getListProduct(' AND tbl_product.`code` IN ("' . implode('","', $arrProCode) . '")');
                if (!empty($currentItemList)) {
                    foreach ($newRecords as $value) {
                        $dataAdd = array();
                        foreach ($currentItemList as $prokey => $proValue) {
                            if ($proValue['code'] == $value['code'] && $proValue['data_type'] != 'OUTSOURCE') {
                                $dataAdd[$prokey]['id'] = $prokey;
                                $dataAdd[$prokey]['data_type'] = 'OUTSOURCE';
                                if (is_numeric($value['regular_cost'])) {
                                    $dataAdd[$prokey]['regular_cost'] = $value['regular_cost'];
                                }
                                if (is_numeric($value['overtime_cost'])) {
                                    $dataAdd[$prokey]['overtime_cost'] = $value['overtime_cost'];
                                }
                                $this->model->editSave('tbl_product', $dataAdd[$prokey]);
                            }
                        }
                    }
                }
            }
            $editRecords = Link::getPost('records');
            if ($editRecords && is_array($editRecords)) {
                $currentItem = $this->model->getListProduct(' AND tbl_product.id IN ("' . implode('","', array_keys($editRecords)) . '")');
                $dataEdit = array();
                foreach ($editRecords as $key => $data) {
                    if (!empty($currentItem[$key])) {
                        if (is_numeric($data['regular_cost'])) {
                            $dataEdit[$key]['regular_cost'] = $data['regular_cost'];
                        }
                        if (is_numeric($data['overtime_cost'])) {
                            $dataEdit[$key]['overtime_cost'] = $data['overtime_cost'];
                        }
                        $modify[$key] = $currentItem[$key]['log'];
                        $strMod[$key] = '';
                        foreach ($currentItem[$key] as $field => $curValue) {
                            if (isset($dataEdit[$key][$field]) && $dataEdit[$key][$field] != $curValue) {
                                $strMod[$key] .= ' - ' . 'Old ' . $field . ': ' . $curValue . "\n";
                                $strMod[$key] .= '   ' . 'New ' . $field . ': ' . $dataEdit[$key][$field] . "\n";
                            } else {
                                unset($dataEdit[$key][$field]);
                            }
                        }
                        $dataEdit[$key]['id'] = $key;
                        if ($strMod[$key]) {
                            $modify[$key] .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n" . $strMod[$key];
                            $modify[$key] .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                            $dataEdit[$key]['log'] = $modify[$key];
                            $this->model->editSave('tbl_product', $dataEdit[$key]);
                        }
                    }
                }
            }
            Link::redirectUrl(Link::createAdminAll());
        } catch (Exception $exc) {
            $this->view->error = $exc->getMessage();
            return $this->index();
        }
    }

    public function deleteProduct($id = '') {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) {
            Link::accessDenied();
        }
        $currentItem = $this->model->productSingleProduct($id);
        if ($currentItem) {
            $modify = $currentItem['log'];
            $strMod = " - Action: OUTSOURCING DELETE\n";
            $modify .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n" . $strMod;
            $modify .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
            $data['id'] = $id;
            $data['data_type'] = 'GOODS';
            $data['production_norms'] = '';
            $data['log'] = $modify;
            $this->model->editSave('tbl_product', $data);
        }

        Link::redirectAdminCurrent(array('cmd' => 'list_product'));
    }

    /**
      *
      */
    public function showpronorms($id) {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) {
            Link::accessDenied();
        }
        $stringContent = '';
        if (is_numeric($id)) {
            $currentItem = $this->model->productSingleProduct($id);
            $stringContent .= '<form name="frmChangeNorms" id="frmChangeNorms" action="" method="post"><input name="cmd" type="hidden" id="cmd" value="changenorms" /><input name="id" type="hidden" id="id" value="' . $id . '" />';
            $stringContent .= '<table cellspacing="1" cellpadding="4" class="adminlist">';
            $stringContent .= '<tr><th>' . $this->view->renderLabel('product_code') . '</th><th>' . $this->view->renderLabel('product_name') . '</th><th>' . $this->view->renderLabel('product_specification') . '</th><th>' . $this->view->renderLabel('col_unit') . '</th></tr>';
            $stringContent .= '<tr><td class="col_center">' . $currentItem['code'] . '</td><td>' . $currentItem['name'] . '</td><td>' . $currentItem['specification'] . '</td><td class="col_center">' . $currentItem['unit'] . '</td></tr></table>';
            $stringContent .= '<table cellspacing="0" border="1" class="adminlist" id="tbl-pronorms">';
            $stringContent .= '<tr><th align="center">#</th><th>' . $this->view->renderLabel('col_code') . '</th><th>' . $this->view->renderLabel('col_name') . '</th><th>' . $this->view->renderLabel('product_specification') . '</th><th>' . $this->view->renderLabel('product_unit') . '</th><th>' . $this->view->renderLabel('set_norms') . '</th></tr>';
            if ($currentItem['production_norms']) {
                $stringProductionNormsList = explode("\n", $currentItem['production_norms']);
                foreach ($stringProductionNormsList as $kn => $productionNormsList) {
                    $stringNormsList = explode('|', $productionNormsList);
                    if ($this->_allProduct[$stringNormsList[0]]) {
                        $stringContent .= '<tr id="row' . $stringNormsList[0] . '"><td align="center"><a href="#" class="removethis" onclick="removerow(\'tr#row' . $stringNormsList[0] . '\'); return false;"><img src="public/button/cancel-32.png" width="24" height="24" alt="remove input" /></a><input type="hidden" name="edit_norms[' . $stringNormsList[0] . '][pro_id]" id="edit_norms_' . $stringNormsList[0] . '_pro_id" value="' . $stringNormsList[0] . '" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_code]" id="edit_norms_' . $stringNormsList[0] . '_pro_code" value="' . $this->_allProduct[$stringNormsList[0]]['code'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_name]" id="edit_norms_' . $stringNormsList[0] . '_pro_name" value="' . $this->_allProduct[$stringNormsList[0]]['name'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_specs]" id="edit_norms_' . $stringNormsList[0] . '_pro_specs" value="' . $this->_allProduct[$stringNormsList[0]]['specification'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_unit]" id="edit_norms_' . $stringNormsList[0] . '_pro_unit" value="' . $this->_allProduct[$stringNormsList[0]]['unit'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_norms]" id="edit_norms_' . $stringNormsList[0] . '_pro_norms" value="' . $stringNormsList[1] . '" /></td></tr>';
                    } else {
                        $productNormInfo = $this->model->productSingleProduct($stringNormsList[0]);
                        if ($productNormInfo) {
                            $stringContent .= '<tr id="row' . $stringNormsList[0] . '"><td align="center"><a href="#" class="removethis" onclick="removerow(\'tr#row' . $stringNormsList[0] . '\'); return false;"><img src="public/button/cancel-32.png" width="24" height="24" alt="remove input" /></a><input type="hidden" name="edit_norms[' . $stringNormsList[0] . '][pro_id]" id="edit_norms_' . $stringNormsList[0] . '_pro_id" value="' . $stringNormsList[0] . '" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_code]" id="edit_norms_' . $stringNormsList[0] . '_pro_code" value="' . $productNormInfo['code'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_name]" id="edit_norms_' . $stringNormsList[0] . '_pro_name" value="' . $productNormInfo['name'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_specs]" id="edit_norms_' . $stringNormsList[0] . '_pro_specs" value="' . $productNormInfo['specification'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_unit]" id="edit_norms_' . $stringNormsList[0] . '_pro_unit" value="' . $productNormInfo['unit'] . '" readonly="readonly" /></td><td><input type="text" name="edit_norms[' . $stringNormsList[0] . '][pro_norms]" id="edit_norms_' . $stringNormsList[0] . '_pro_norms" value="' . $stringNormsList[1] . '" /></td></tr>';
                        }
                    }
                }
            }
            $stringContent .= '</table>';
            $stringContent .= '</form>';
        }
        echo $stringContent;
        exit;
    }

    /**
     *
     */
    public function changenorms($id) {
        if (!$this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) {
            Link::accessDenied();
        }
        $editNormsList = Link::get('edit_norms');
        $newNormsList = Link::get('new_norms');
        $stringContent = '';
        if (is_numeric($id)) {
            $currentItem = $this->model->productSingleProduct($id);
            $arrNormsListUpdate = array();
            if ($editNormsList || $newNormsList) {
                $stringContent .= '<table cellpadding="0" cellspacing="0" class="sub-table">';
                if ($editNormsList) {
                    foreach ($editNormsList as $editKey => $editNorms) {
                        $arrNormsListUpdate[$editNorms['pro_id']] = $editNorms['pro_id'] . '|' . $editNorms['pro_norms'];
                        $stringContent .= '<tr><td class="col_left"><a href="javascript:void(0);" onclick="selectpronorm(\'' . $id . '\');">' . $editNorms['pro_code'] . '</a></td><td class="col_right">' . $editNorms['pro_norms'] . '</td></tr>';
                    }
                }
                if ($newNormsList) {
                    foreach ($newNormsList as $newKey => $newNorms) {
                        if ($this->_allProduct[$newNorms['pro_id']]) {
                            $arrNormsListUpdate[$newNorms['pro_id']] = $newNorms['pro_id'] . '|' . $newNorms['pro_norms'];
                            $stringContent .= '<tr><td class="col_left"><a href="javascript:void(0);" onclick="selectpronorm(\'' . $id . '\');">' . $newNorms['pro_code'] . '</a></td><td class="col_right">' . $newNorms['pro_norms'] . '</td></tr>';
                        }
                    }
                }
                $stringContent .= '</table>';
            }
            $newStringNorms = implode("\n", $arrNormsListUpdate);
            if (empty($newStringNorms)) {
                $stringContent .= '<a href="javascript:void(0);" onclick="selectpronorm(\'' . $id . '\');">' . $this->view->renderLabel('not_yet_set') . '</a>';
            }
            if ($currentItem['production_norms'] != $newStringNorms) {
                $itemConfirm = $currentItem['log'];
                $itemConfirm .= ' * Date: ' . date('d/m/Y H:i:s') . ":\n";
                $itemConfirm .= " - Old production_norms: " . $currentItem['production_norms'] . "\n";
                $itemConfirm .= "   New production_norms: " . $newStringNorms . "\n";
                $itemConfirm .= ' - User: ' . Session::get('user_fullname') . ' - ' . Session::get('user_id') . "\n";
                $this->model->eSave('tbl_product', array('id' => $id, 'production_norms' => $newStringNorms, 'log' => $itemConfirm));
            }
        }
        echo $stringContent;
        exit;
    }

    /**
     * Show the product information
     */
    public function productInfoCache() {
        $keyword = Link::get('term');
        $fieldName = Link::get('field');
        $arrproIDSupList = array();
        if (is_numeric(Link::get('sid'))) {
            $proSupList = $this->model->selectTable('tbl_supplier_product', 'supplier_id = "' . Link::get('sid') . '"');
            $arrproIDSupList = array_unique(array_column($proSupList, 'product_id'));
        }
        $allProduct = array();
        foreach ($this->_allProduct as $key => $value) {
            if (is_numeric(Link::get('sid'))) {
                if (in_array($value['id'], $arrproIDSupList)) {
                    if (stripos($value[$fieldName], $keyword) !== FALSE) {
                        $allProduct[$key] = $value;
                    }
                }
            } else {
                if (stripos($value[$fieldName], $keyword) !== FALSE) {
                    $allProduct[$key] = $value;
                }
            }
        }
        $arrdata = array();
        foreach ($allProduct as $key => $product) {
            if ($fieldName == 'name') {
                $arrdata[$key]['value'] = $product['name'];
                $arrdata[$key]['code'] = $product['code'];
                $arrdata[$key]['desc'] = $product['specification'];
                $arrdata[$key]['id'] = $product['id'];
                $arrdata[$key]['unit'] = $product['unit'];
                $arrdata[$key]['price'] = $product['price'];
                $arrdata[$key]['available_quantity'] = $product['quantity'];
            } else {
                $arrdata[$key]['value'] = $product['code'];
                $arrdata[$key]['name'] = $product['name'];
                $arrdata[$key]['desc'] = $product['specification'];
                $arrdata[$key]['id'] = $product['id'];
                $arrdata[$key]['unit'] = $product['unit'];
                $arrdata[$key]['price'] = $product['price'];
                $arrdata[$key]['available_quantity'] = $product['quantity'];
            }
        }
        echo json_encode($arrdata);
        exit;
    }

}