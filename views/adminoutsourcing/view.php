<script type="text/javascript">
    $(document).ready(function() {
        $('table#button-direct').show();
        $("#tabs").tabs();
        $('a#tab-direct').click(function() {
            $('table#button-direct').show();
            $('table#button-order').hide();
        });
        $('a#tab-order').click(function() {
            $('table#button-direct').hide();
            $('table#button-order').show();
        });
    });

    function showHideMaterial(rowid, rowstatus) {
        if (rowstatus == 'show') {
            $('tr#rows_material' + rowid).show();
            $('div#no' + rowid).html('<a href="javascript:void(0);" onclick="showHideMaterial(\'' + rowid + '\', \'hide\');" title="{{.hide_material.}}"><span class="icon-button-16 Icon-16-Collapse" title="{{.hide_material.}}"><\/span><\/a>');
        }
        if (rowstatus == 'hide') {
            $('tr#rows_material' + rowid).hide();
            $('div#no' + rowid).html('<a href="javascript:void(0);" onclick="showHideMaterial(\'' + rowid + '\', \'show\');" title="{{.show_material.}}"><span class="icon-button-16 Icon-16-Expand" title="{{.show_material.}}"><\/span><\/a>');
        }
    }

    function editField(id, value, field) {
        if (field == 'handler_id_list') {
            // $('#' + field + '_input_' + id).html('<input type = "text" name = "e_' + field + '_' + id + '" id = "e_' + field + '_' + id + '" value = "' + value + '" style = "width:60px" \/>');
            $('#' + field + '_input_' + id).html('<input type="checkbox" onclick="chkAllCheckboxes(\'chkAll\', \'clsUser\');" id="chkAllclsUser" \/><label for="chkAllclsUser">{{.all.}}<\/label> <?php foreach ($this->allStoreUserList as $user) { ?> <span class="text-nowrap" style="display: inline-block; padding: 3px;"> <input type="checkbox" name="handler_id_list[]" id="e_' + field + '_' + id + '_<?php echo $user['id']; ?>" class="clsUser" value="<?php echo $user['id']; ?>" \/> <label for="e_' + field + '_' + id + '_<?php echo $user['id']; ?>"><?php echo $user['fullname']; ?> <\/label> <\/span> <?php } ?>');
        } else if (field == 'num_hours') {
            $('#' + field + '_input_' + id).html('<input type = "number" name = "e_' + field + '_' + id + '" id = "e_' + field + '_' + id + '" value = "' + value + '" min=0  style = "width:60px" \/>');
        } else if (field == 'warehouse_note') {
            $('#' + field + '_input_' + id).html('<textarea name = "e_' + field + '_' + id + '" id = "e_' + field + '_' + id + '" style="width: 30%; height: 60px;">' + value + '<\/textarea>');
        }
        $('#button_' + field + id).html('<a href="javascript:void(0);" id="button_save_' + field + id + '" onclick="saveField(\'' + id + '\', \'' + field + '\');" title="{{.save.}}" class="btn btn-primary"><i class="fa fa-save"><\/i> {{.save.}}<\/a><a href="javascript:void(0);" onclick="cancelChange(\'' + id + '\', \'' + value + '\', \'' + field + '\')" id="button_cancel_' + field + id + '" title="{{.cancel.}}" class="btn btn-red"><i class="fa fa-remove"></i>&nbsp;{{.cancel.}}<\/a><script>$("#e_' + field + '_' + id + '").focus();<\/script>');
    }

    function cancelChange(id, value, field) {
        $('#' + field + '_input_' + id).html(value);
        $("#button_" + field + id).html('<a href="javascript:void(0);" onclick="editField(' + id + ', \`' + value + '\`, \'' + field + '\');" title="{{.edit.}}" class="list-button">{{.edit.}}<\/a');
    }
    function saveField(id, field) {
        if (field == 'handler_id_list') {
            var handler = [];
            $('#' + field + '_input_' + id + ' .clsUser').each(function() {
                if (this.checked) {
                    handler.push(this.value);
                }
            })
            var valueField = JSON.stringify(handler);
        } else {
            var valueField = $('#e_' + field + '_' + id).val();
        }
        $.ajax({
            url: '<?php echo Link::createAdmin_current(); ?>',
            data: {
                cmd: 'ajaxSaveField',
                id: id,
                field: field,
                value: valueField
            },
            beforeSend: function() {
                $("#button_save_" + field + id + ' i').addClass('fa-spinner fa-spin');
            },
            success: function(dataResult) {
                $('#' + field + '_input_' + id).html(dataResult);
                $('#button_' + field + id).html('<a href="javascript:void(0);" onclick="editField(' + id + ', \`' + dataResult + '\`, \'' + field + '\');" title="{{.edit.}}" class="list-button">{{.edit.}}<\/a>');
            }
        });
    }
</script>
<div class="content-wrapper">
    <div class="path">
        <ul>
            <li><a href="<?php echo Link::create('admin'); ?>">{{.home.}}</a></li>
            <li class="SecondLast"><a href="<?php echo Link::createAdmin('outsourcing'); ?>">{{.outsourcing_order_list.}}</a></li>
            <li class="Last"><span>{{.outsourcing_order_detail.}}</span></li>
        </ul>
    </div>
    <div class="subMenuBox">
        <ul class="submenu">
            <?php foreach ($this->subMenuList as $subMenu) { ?>
                <li><a href="<?php echo $subMenu['url']; ?>" <?php echo (Link::getUrl() == urlencode($subMenu['url']) ? ' class="active"' : '') ?>><?php echo $subMenu['name']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/stock-out-48.png'; ?>" />
            <span>{{.outsourcing_order_detail.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <?php if ($this->item['store_dept_edit']) { ?>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.save.}}" onclick="action('frmAdminItemEdit', 'storeDeptSave');"><span class="icon-button Icon-32-Save" title="{{.save.}}"></span>{{.save.}}</a>
                        </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'print')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.print.}}" onclick="javascript:window.open('<?php echo Link::createAdmin_current(array('cmd' => 'printer', 'id' => Link::get('id'))); ?>');"><span class="icon-button Icon-32-Print" title="{{.print.}}"></span>{{.print.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'delete') && in_array($this->item['status'], array('PENDING', 'CANCELING'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.delete.}}" onclick="confirmAction('frmAdminItemEdit', 'deleteOrder', '{{.are_you_sure_want_to_delete_this_item.}}');"><span class="icon-button Icon-32-Delete" title="{{.delete.}}"></span>{{.delete.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') && in_array('MANAGER', Session::get('group')) && $this->item['status'] == 'CANCELING') { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.cancel_delete.}}" onclick="confirmAction('frmAdminItemEdit', 'cancelDelete', '{{.are_you_sure_want_to_restore_this_order.}}');"><span class="icon-button Icon-32-Restore" title="{{.cancel_delete.}}"></span>{{.cancel_delete.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved') && $this->item['status'] == 'PENDING') { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.approved.}} {{.delete.}}" onclick="confirmAction('frmAdminItemEdit', 'approvedelete', '{{.are_you_sure_want_to_approve_this_order.}}');"><span class="icon-button Icon-32-Approve" title="{{.approved.}} {{.delete.}}"></span>{{.approved.}} {{.delete.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit') && $this->item['status'] == 'PENDING') { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.finish_order.}}" onclick="javascript:if(confirm('{{.are_you_sure_want_to_finish_this_order.}}')){window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'finishOrder', 'id' => Link::get('id'))); ?>';}"><span class="icon-button Icon-32-Finish" title="{{.finish_order.}}"></span>{{.finish.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit') && in_array($this->item['status'], array('PENDING', 'APPROVED'))) { ?>
                            <td align="center">
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'edit', 'id' => Link::get('id'))); ?>" class="toolbar" title="{{.edit.}}"><span class="icon-button Icon-32-Edit" title="{{.edit.}}"></span>{{.edit.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved') && in_array($this->item['status'], array('PENDING', 'FINISHED'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.approved.}} {{.edit.}}" onclick="javascript:if(confirm('{{.are_you_sure_want_to_approve_this_order.}}')){window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'approveedit', 'id' => Link::get('id'))); ?>';}"><span class="icon-button Icon-32-Approve" title="{{.approved.}} {{.edit.}}"></span>{{.approved.}} {{.edit.}}</a>
                            </td>
                            <?php if ($this->item['status'] == 'FINISHED') { ?>
                                <td align="center">
                                    <a href="javascript:void(0);" class="toolbar" title="{{.restore_order.}}" onclick="confirmAction('frmAdminItemEdit', 'restoreorder', '{{.are_you_sure_want_to_restore_this_order.}}');"><span class="icon-button Icon-32-Restore" title="{{.restore_order.}}"></span>{{.restore_order.}}</a>
                                </td>
                            <?php }
                        } ?>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.back.}}" onclick="goBack();"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="content-box">
        <div class="content-list">
            <div id="tabs">
                <ul>
                    <li><a href="#outsourcing-detail">{{.outsourcing_order_detail.}}</a></li>
                    <li><a href="#stockout-list">{{.stockout_local_list.}}</a></li>
                    <li><a href="#return-list">{{.order_returned_list.}}</a></li>
                    <li><a href="#general-details">{{.general_details.}}</a></li>
                </ul>
                <div id="outsourcing-detail">
                    <form name="frmAdminItemEdit" id="frmAdminItemEdit" action="" method="post">
                        <input name="cmd" type="hidden" id="cmd" />
                        <table cellspacing="1" cellpadding="4" class="admintable">
                            <tr>
                                <td class="key">{{.col_date_require.}}</td>
                                <td><?php echo Systems::displayVnDate($this->item['date_out']); ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.expired_date.}}</td>
                                <td><?php echo Systems::displayVnDate($this->item['expired_date']); ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.time_finish.}}</td>
                                <td><?php echo Systems::displayVnDate($this->item['time_finished']); ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.outsource_number.}}</td>
                                <td><?php echo $this->item['id']; ?><?php if ($this->item['po_id']) { ?> - {{.generated_via_po_number.}} <a href="<?php echo Link::createAdmin('adminpo', array('cmd' => 'view', 'id' => $this->item['po_id'])); ?>" title="{{.view_detail_this_po.}}" target="_blank"><?php echo $this->item['po_id']; ?></a><?php } ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.customer_code.}}</td>
                                <td><?php echo $this->item['customer_code']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.customer_name.}}</td>
                                <td><?php echo $this->item['customer_name']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.customer_address.}}</td>
                                <td><?php echo $this->item['customer_address']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.note.}}</td>
                                <td><?php echo $this->item['note']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.order_status.}}</td>
                                <td><?php echo $this->item['delivery_status']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.sales_name.}}</td>
                                <td><?php echo $this->item['sales_name']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.accountant_name.}}</td>
                                <td><?php echo $this->item['accountant_name']; ?></td>
                            </tr>
                            <tr>
                                <td class="key">{{.handler.}}</td>
                                <td>
                                    <?php if ($this->item['store_dept_edit']) { ?>
                                        <input type="hidden" name="records[id]" id="records_id" value="<?php echo $this->item['id']; ?>" />
                                        <input type="checkbox" onclick="chkAllCheckboxes('chkAll', 'clsUser');" id="chkAllclsUser" /><label for="chkAllclsUser">{{.all.}}</label>
                                        <?php foreach ($this->allStoreUserList as $user) { ?>
                                            <span class="text-nowrap" style="display: inline-block; padding: 3px;"> <input type="checkbox" name="records[handler_id_list][]" id="records_handler_id_list_<?php echo $user['id']; ?>" class="clsUser" value="<?php echo $user['id']; ?>" <?php echo in_array($user['id'], $this->item['handler_list_arr']) ? 'checked="checked"' : ''; ?> /> <label for="records_handler_id_list_<?php echo $user['id']; ?>"><?php echo $user['fullname']; ?> </label> </span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php echo $this->item['handler_list_name']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.number_hours_performed.}}</td>
                                <td>

                                    <?php if ($this->item['store_dept_edit']) { ?>
                                        <input type="number" name="records[num_hours]" id="records_num_hours" value="<?php echo $this->item['num_hours']; ?>" min=0  style = "width:60px" />
                                    <?php } else { ?>
                                        <?php echo $this->item['num_hours']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.warehouse_note.}}</td>
                                <td>
                                    <?php if ($this->item['store_dept_edit']) { ?>
                                        <textarea name="records[warehouse_note]" id="records_warehouse_note" style="width: 30%; height: 60px;"><?php echo $this->item['warehouse_note']; ?></textarea>
                                    <?php } else { ?>
                                        <?php echo $this->item['warehouse_note']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php if ($this->item['time_warehouse_note']) { ?>
                                <tr>
                                    <td class="key">{{.time.}} {{.warehouse_note.}}</td>
                                    <td>
                                        <?php echo Systems::convertToDate($this->item['time_warehouse_note'], 'H:i d/m/Y'); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2" style="padding:0">
                                    <table cellspacing="0" border="1" class="adminlist stockout">
                                        <thead>
                                            <tr>
                                                <th colspan="2">#</th>
                                                <th class="col_60">{{.col_code.}}</th>
                                                <th class="col_250">{{.col_name.}}</th>
                                                <th class="col_200">{{.col_specification.}}</th>
                                                <th class="col_50">{{.col_unit.}}</th>
                                                <th class="col_60">{{.col_quantity.}}</th>
                                                <th class="col_60">{{.quantity_imported.}}</th>
                                                <th class="col_60">{{.unit_hire_price.}}</th>
                                                <th class="col_60">{{.finished_price.}}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="list">
                                            <?php $i = 1;
                                            foreach ($this->proList as $outSourcePro) { ?>
                                                <tr id="rows<?php echo $i; ?>" class="<?php echo ($i % 2 == 1 ? 'row0' : 'row1') . ' ' . ($outSourcePro['quantity'] == $outSourcePro['quantity_import'] ? ' finished' : ''); ?>">
                                                    <td width="15" align="center">
                                                        <div id="no<?php echo $i; ?>"><a href="javascript:void(0);" onclick="showHideMaterial('<?php echo $i; ?>', 'show');" title="{{.show_material.}}"><span class="icon-button-16 Icon-16-Expand" title="{{.show_material.}}"></span></a></div>
                                                    </td>
                                                    <td width="20" align="center"><?php echo $i; ?></td>
                                                    <td align="center"><?php echo $outSourcePro['product_code']; ?></td>
                                                    <td><?php echo $outSourcePro['product_name']; ?></td>
                                                    <td><?php echo $outSourcePro['product_specs']; ?></td>
                                                    <td align="center"><?php echo $outSourcePro['product_unit']; ?></td>
                                                    <td align="right"><?php echo Systems::displayNumber($outSourcePro['quantity']); ?></td>
                                                    <td align="right"><?php echo Systems::displayNumber($outSourcePro['quantity_import']); ?></td>
                                                    <td align="right"><?php echo Systems::displayNumber($outSourcePro['hire_price']); ?></td>
                                                    <td align="right"><?php echo Systems::displayNumber($outSourcePro['price']); ?></td>
                                                </tr>
                                                <?php if ($this->proMaterialList) { ?>
                                                    <tr id="rows_material<?php echo $i; ?>" class="<?php echo ($i % 2 == 1 ? 'row0' : 'row1') . ' ' . ($outSourcePro['quantity'] == $outSourcePro['quantity_import'] ? ' finished' : ''); ?>" style="display: none;">
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td colspan="8">
                                                            <table cellspacing="0" border="1" class="adminlist">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col_20"><?php echo $i; ?></th>
                                                                        <th class="col_60">{{.col_code.}}</th>
                                                                        <th class="col_250">{{.col_name.}}</th>
                                                                        <th class="col_200">{{.col_specification.}}</th>
                                                                        <th class="col_50">{{.col_unit.}}</th>
                                                                        <th class="col_60">{{.product_norms.}}</th>
                                                                        <th class="col_60">{{.unit_price.}}</th>
                                                                        <th class="col_60">{{.amount.}}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="list_material_<?php echo $outSourcePro['id']; ?>">
                                                                    <?php $j = 1;
                                                                    foreach ($this->proMaterialList as $keM => $veM) { ?>
                                                                        <?php if ($veM['outsourcing_product_id'] == $outSourcePro['id']) { ?>
                                                                            <tr id="rows_material_<?php echo $keM; ?>">
                                                                                <td align="center"><?php echo $i . '.' . $j; ?></td>
                                                                                <td align="center"><?php echo $veM['product_code']; ?></td>
                                                                                <td><?php echo $veM['product_name']; ?></td>
                                                                                <td><?php echo $veM['product_specs']; ?></td>
                                                                                <td align="right"><?php echo $veM['product_unit']; ?></td>
                                                                                <td align="right"><?php echo $veM['product_norm']; ?></td>
                                                                                <td align="right"><?php echo Systems::displayNumber($veM['product_price']); ?></td>
                                                                                <td align="right"><?php echo Systems::displayNumber($veM['product_price'] * $veM['product_norm'], 0); ?></td>
                                                                            </tr>
                                                                            <?php $j++;
                                                                        } ?>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php $i++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="stockout-list">
                    <?php if ($this->itemList) { ?>
                        <?php foreach ($this->itemList as $kItem => $items) { ?>
                            <div class="stockout-content">
                                <table cellspacing="1" cellpadding="4" class="admintable">
                                    <tr>
                                        <td class="key">{{.date_out.}}</td>
                                        <td><?php echo Systems::displayVnDate($items['date_out']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.local_stockout_number.}}</td>
                                        <td><a href="<?php echo Link::createAdmin('stockoutlocal', array('cmd' => 'view', 'id' => $kItem)); ?>" target="_blank"><?php echo $items['id']; ?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.buyer_full_name.}}</td>
                                        <td><?php echo $items['buyer_fullname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.customer_code.}}</td>
                                        <td><?php echo $items['buyer_code']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.buyer_company_name.}}</td>
                                        <td><?php echo $items['buyer_company_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.buyer_company_address.}}</td>
                                        <td><?php echo $items['buyer_address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:0">
                                            <table cellspacing="0" border="1" class="adminlist stockout">
                                                <thead>
                                                    <tr>
                                                        <th class="col_20">#</th>
                                                        <th class="col_60">{{.col_code.}}</th>
                                                        <th class="col_250">{{.col_name.}}</th>
                                                        <th class="col_200">{{.col_specification.}}</th>
                                                        <th class="col_50">{{.col_unit.}}</th>
                                                        <th class="col_60">{{.col_quantity.}}</th>
                                                        <th class="col_60">{{.unit_price.}}</th>
                                                        <th class="col_60">{{.amount.}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="list">
                                                    <?php foreach ($items['proList'] as $index => $stockOutPro) { ?>
                                                        <tr id="rows<?php echo $index; ?>" class="<?php echo ($index % 2 == 1 ? 'row0' : 'row1'); ?>">
                                                            <td align="right"><?php echo $index; ?></td>
                                                            <td align="center"><?php echo $stockOutPro['item_code']; ?></td>
                                                            <td><?php echo $stockOutPro['item_name']; ?></td>
                                                            <td><?php echo $stockOutPro['item_specs']; ?></td>
                                                            <td align="center"><?php echo $stockOutPro['item_unit']; ?></td>
                                                            <td align="right"><?php echo Systems::displayNumber($stockOutPro['item_quantity']); ?></td>
                                                            <td align="right"><?php echo Systems::displayNumber($stockOutPro['item_price']); ?></td>
                                                            <td align="right" class="amount_real<?php echo $kItem; ?>"><?php echo Systems::displayNumber($stockOutPro['item_quantity'] * $stockOutPro['item_price']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="no-data">{{.no_data.}}</div>
                    <?php } ?>
                </div>
                <div id="return-list">
                    <?php if ($this->itemReturnedList) { ?>
                        <?php foreach ($this->itemReturnedList as $kRItem => $rItems) { ?>
                            <div class="stockout-content">
                                <table cellspacing="1" cellpadding="4" class="admintable">
                                    <tr>
                                        <td class="key">{{.date_return.}}</td>
                                        <td><?php echo Systems::displayVnDate($rItems['date_return']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.return_number.}}</td>
                                        <td><a href="<?php echo Link::createAdmin('returned_goods_local', array('cmd' => 'view', 'id' => $kRItem)); ?>" target="_blank"><?php echo $rItems['id']; ?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.local_stockout_number.}}</td>
                                        <td><a href="<?php echo Link::createAdmin('stockoutlocal', array('cmd' => 'view', 'id' => $rItems['stock_out_local_id'])); ?>" target="_blank"><?php echo $rItems['stock_out_local_id']; ?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.buyer_full_name.}}</td>
                                        <td><?php echo $rItems['buyer_fullname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.customer_code.}}</td>
                                        <td><?php echo $rItems['buyer_code']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.customer_name.}}</td>
                                        <td><?php echo $rItems['buyer_company_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="key">{{.customer_address.}}</td>
                                        <td><?php echo $rItems['buyer_address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:0">
                                            <table cellspacing="0" border="1" class="adminlist stockout">
                                                <thead>
                                                    <tr>
                                                        <th class="col_20">#</th>
                                                        <th class="col_60">{{.col_code.}}</th>
                                                        <th class="col_250">{{.col_name.}}</th>
                                                        <th class="col_200">{{.col_specification.}}</th>
                                                        <th class="col_50">{{.col_unit.}}</th>
                                                        <th class="col_60">{{.col_quantity.}}</th>
                                                        <th class="col_60">{{.unit_price.}}</th>
                                                        <th class="col_60">{{.amount.}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="list">
                                                    <?php foreach ($rItems['proList'] as $rindex => $returnedPro) { ?>
                                                        <tr id="rows<?php echo $rindex; ?>" class="<?php echo ($rindex % 2 == 1 ? 'row0' : 'row1'); ?>">
                                                            <td align="right"><?php echo $rindex; ?></td>
                                                            <td align="center"><?php echo $returnedPro['item_code']; ?></td>
                                                            <td><?php echo $returnedPro['item_name']; ?></td>
                                                            <td><?php echo $returnedPro['item_specs']; ?></td>
                                                            <td align="center"><?php echo $returnedPro['item_unit']; ?></td>
                                                            <td align="right"><?php echo Systems::displayNumber($returnedPro['item_quantity']); ?></td>
                                                            <td align="right"><?php echo Systems::displayNumber($returnedPro['item_price']); ?></td>
                                                            <td align="right" class="amount_real<?php echo $kRItem; ?>"><?php echo Systems::displayNumber($returnedPro['item_quantity'] * $returnedPro['item_price']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="no-data">{{.no_data.}}</div>
                    <?php } ?>
                </div>
                <div id="general-details">
                    <?php if ($this->itemsGeneral) { ?>
                        <table cellspacing="0" border="1" class="adminlist">
                            <thead>
                                <tr>
                                    <th class="col_20">#</th>
                                    <th class="col_60">{{.col_code.}}</th>
                                    <th class="col_250">{{.col_name.}}</th>
                                    <th class="col_200">{{.col_specification.}}</th>
                                    <th class="col_50">{{.col_unit.}}</th>
                                    <th class="col_60">{{.quantity_production.}}</th>
                                    <th class="col_60">{{.quantity_issued.}}</th>
                                    <th class="col_60">{{.excessive_quantity.}}</th>
                                    <th class="col_60">{{.quantity_returned.}}</th>
                                    <th class="col_60">{{.quantity_different.}}</th>
                                </tr>
                            </thead>
                            <tbody id="list">
                                <?php $k = 1;
                                foreach ($this->itemsGeneral as $index => $itemsGeneral) { ?>
                                    <tr id="rows<?php echo $index; ?>" class="<?php echo ($k % 2 == 1 ? 'row0' : 'row1') . ($itemsGeneral['quantity_returned'] - ($itemsGeneral['quantity_issued'] - $itemsGeneral['quantity_production']) >= 0 ? '' : ' miss'); ?>">
                                        <td align="right"><?php echo $k; ?></td>
                                        <td align="center"><?php echo $itemsGeneral['item_code']; ?></td>
                                        <td><?php echo $itemsGeneral['item_name']; ?></td>
                                        <td><?php echo $itemsGeneral['item_specs']; ?></td>
                                        <td align="center"><?php echo $itemsGeneral['item_unit']; ?></td>
                                        <td align="right"><?php echo Systems::displayNumber($itemsGeneral['quantity_production']); ?></td>
                                        <td align="right"><?php echo Systems::displayNumber($itemsGeneral['quantity_issued']); ?></td>
                                        <td align="right"><?php echo Systems::displayNumber($itemsGeneral['quantity_issued'] - $itemsGeneral['quantity_production']); ?></td>
                                        <td align="right"><?php echo Systems::displayNumber($itemsGeneral['quantity_returned']); ?></td>
                                        <td align="right"><?php echo Systems::displayNumber($itemsGeneral['quantity_returned'] - ($itemsGeneral['quantity_issued'] - $itemsGeneral['quantity_production'])); ?></td>
                                    </tr>
                                    <?php $k++;
                                } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="no-data">{{.no_data.}}</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/stock-out-48.png'; ?>" />
            <span>{{.outsourcing_order_detail.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <?php if ($this->item['store_dept_edit']) { ?>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.save.}}" onclick="action('frmAdminItemEdit', 'storeDeptSave');"><span class="icon-button Icon-32-Save" title="{{.save.}}"></span>{{.save.}}</a>
                        </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'print')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.print.}}" onclick="javascript:window.open('<?php echo Link::createAdmin_current(array('cmd' => 'printer', 'id' => Link::get('id'))); ?>');"><span class="icon-button Icon-32-Print" title="{{.print.}}"></span>{{.print.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'delete') && in_array($this->item['status'], array('PENDING', 'CANCELING'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.delete.}}" onclick="confirmAction('frmAdminItemEdit', 'deleteOrder', '{{.are_you_sure_want_to_delete_this_item.}}');"><span class="icon-button Icon-32-Delete" title="{{.delete.}}"></span>{{.delete.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin') && in_array('MANAGER', Session::get('group')) && $this->item['status'] == 'CANCELING') { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.cancel_delete.}}" onclick="confirmAction('frmAdminItemEdit', 'cancelDelete', '{{.are_you_sure_want_to_restore_this_order.}}');"><span class="icon-button Icon-32-Restore" title="{{.cancel_delete.}}"></span>{{.cancel_delete.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved') && $this->item['status'] == 'PENDING') { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.approved.}} {{.delete.}}" onclick="confirmAction('frmAdminItemEdit', 'approvedelete', '{{.are_you_sure_want_to_approve_this_order.}}');"><span class="icon-button Icon-32-Approve" title="{{.approved.}} {{.delete.}}"></span>{{.approved.}} {{.delete.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit') && $this->item['status'] == 'PENDING') { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.finish_order.}}" onclick="javascript:if(confirm('{{.are_you_sure_want_to_finish_this_order.}}')){window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'finishOrder', 'id' => Link::get('id'))); ?>';}"><span class="icon-button Icon-32-Finish" title="{{.finish_order.}}"></span>{{.finish.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit') && in_array($this->item['status'], array('PENDING', 'APPROVED'))) { ?>
                            <td align="center">
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'edit', 'id' => Link::get('id'))); ?>" class="toolbar" title="{{.edit.}}"><span class="icon-button Icon-32-Edit" title="{{.edit.}}"></span>{{.edit.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved') && in_array($this->item['status'], array('PENDING', 'FINISHED'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.approved.}} {{.edit.}}" onclick="javascript:if(confirm('{{.are_you_sure_want_to_approve_this_order.}}')){window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'approveedit', 'id' => Link::get('id'))); ?>';}"><span class="icon-button Icon-32-Approve" title="{{.approved.}} {{.edit.}}"></span>{{.approved.}} {{.edit.}}</a>
                            </td>
                            <?php if ($this->item['status'] == 'FINISHED') { ?>
                                <td align="center">
                                    <a href="javascript:void(0);" class="toolbar" title="{{.restore_order.}}" onclick="confirmAction('frmAdminItemEdit', 'restoreorder', '{{.are_you_sure_want_to_restore_this_order.}}');"><span class="icon-button Icon-32-Restore" title="{{.restore_order.}}"></span>{{.restore_order.}}</a>
                                </td>
                            <?php }
                        } ?>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.back.}}" onclick="goBack();"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>