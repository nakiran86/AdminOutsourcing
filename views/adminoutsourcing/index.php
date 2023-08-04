<script type="text/javascript">
    $(document).ready(function() {
        $('a#showAdvanceSearch').click(function() {
            $('input#from_date').datepicker({
                dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
                onClose: function(selectedDate) {
                    $("#to_date").datepicker("option", "minDate", selectedDate);
                }
            });
            $('input#to_date').datepicker({
                dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
                onClose: function(selectedDate) {
                    $("#from_date").datepicker("option", "maxDate", selectedDate);
                }
            });
            $("#advance-search").dialog({
                height: 410,
                width: 500,
                modal: true,
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                }
            });
        });
    });
</script>
<div class="content-wrapper">
    <div class="path">
        <ul>
            <li class="SecondLast"><a href="<?php echo Link::createAdmin('admin'); ?>">{{.home.}}</a></li>
            <li class="Last"><span>{{.outsourcing_order_list.}}</span></li>
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
            <span>{{.outsourcing_order_list.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'add')) { ?>
                            <td align="center">
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'add')); ?>" class="toolbar" title="{{.add_new.}}"><span class="icon-button Icon-32-Add" title="{{.add_new.}}"></span>{{.add_new.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.finish_order.}}" onclick="confirmCheckAction('frmAdminItemsList', 'finishListOrder', '{{.are_you_sure_want_to_finish_the_selected_items.}}');"><span class="icon-button Icon-32-Finish" title="{{.finish_order.}}"></span>{{.finish.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) { ?>
                            <td align="center">
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'statistic')); ?>" class="toolbar" title="{{.statistic.}}"><span class="icon-button Icon-32-Statistic" title="{{.statistic.}}"></span>{{.statistic.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.approved.}} {{.edit.}}" onclick="confirmCheckAction('frmAdminItemsList', 'approvelistedit', '{{.are_you_sure_want_to_approve_the_selected_items.}}');"><span class="icon-button Icon-32-Approve" title="{{.approved.}} {{.edit.}}"></span>{{.approved.}} {{.edit.}}</a>
                            </td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="content-box">
        <table cellspacing="1" cellpadding="4" class="admintable search">
            <tbody>
                <tr>
                    <td>{{.total.}}: <font color="Brown"><span><?php echo $this->totalRecord; ?></span></font> {{.record.}} / <?php echo $this->totalPage; ?> {{.page.}}</td>
                    <td style="vertical-align: middle; padding-top: 0;" align="right">
                        <form name="frmSearch" id="frmSearch" action="" method="get">
                            <input name="page" type="hidden" id="page" value="<?php echo Link::get('page'); ?>" />
                            {{.keyword.}}
                            <input type="text" class="TextInput" id="txtFindData" name="txtFindData" />
                            {{.sales_name.}}
                            <select id="cbUser" name="cbUser">
                                <option value="">-</option>
                                <?php if ($this->grant->check_privilege('MOD_PUBLICCUSTOMER', 'view')) { ?>
                                    <option value="ALL">{{.public_customer.}}</option>
                                <?php }
                                foreach ($this->salesUserList as $salesUser) { ?>
                                    <option value="<?php echo $salesUser['username']; ?>"><?php echo $salesUser['fullname']; ?></option>
                                <?php } ?>
                            </select>
                            <script type="text/javascript">$('select#cbUser').val('<?php echo Link::get('cbUser'); ?>');</script>
                            <input type="submit" class="Button" id="btnFind" value="{{.search.}}" name="btnFind" />
                            <input type="button" class="Button" id="btnRemoveFilter" value="{{.remove_filter.}}" name="btnRemoveFilter" onclick="javascript:window.location.href='<?php echo Link::createAdmin_current(); ?>';" />
                            <a href="javascript:void(0);" id="showAdvanceSearch">{{.advance_search.}}</a>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="advance-search">
            <div class="advance-search-title">{{.advance_search.}}</div>
            <form name="frmAdvanceSearch" id="frmAdvanceSearch" method="get" action="">
                <input name="page" type="hidden" id="page" value="<?php echo Link::get('page'); ?>" />
                <input name="view" type="hidden" id="view" value="<?php echo Link::get('view'); ?>" />
                <table cellspacing="1" cellpadding="4" class="admintable search" align="center">
                    <tbody>
                        <tr>
                            <td class="key">{{.outsource_number.}}</td>
                            <td><input type="text" name="outsource_number" id="outsource_number" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.po_number.}}</td>
                            <td><input type="text" name="po_number" id="po_number" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.customer_code.}}</td>
                            <td><input type="text" name="customer_code" id="customer_code" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.customer_name.}}</td>
                            <td><input type="text" name="customer_name" id="customer_name" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.product_code.}}</td>
                            <td><input type="text" name="product_code" id="product_code" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.product_name.}}</td>
                            <td><input type="text" name="product_name" id="product_name" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.col_date_import.}}</td>
                            <td>
                                {{.from.}} <input type="text" name="from_date" id="from_date" maxlength="10" class="dateTime" autocomplete="off" />
                                {{.to.}} <input type="text" name="to_date" id="to_date" maxlength="10" class="dateTime" autocomplete="off" />
                            </td>
                        </tr>
                        <tr>
                            <td class="key">{{.expired_date.}}</td>
                            <td>
                                <select id="expire" name="expire">
                                    <option value="">-</option>
                                    <option value="yes">{{.still_valid.}}</option>
                                    <option value="no">{{.expired.}}</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">{{.order_status.}}</td>
                            <td>
                                <select id="status" name="status">
                                    <option value="">-</option>
                                    <option value="PENDING">{{.not_completed.}}</option>
                                    <option value="FINISHED">{{.completed.}}</option>
                                    <option value="NOTENOUGH">{{.not_enough_stock.}}</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">{{.sales_name.}}</td>
                            <td>
                                <select id="cbUser" name="cbUser">
                                    <option value="">-</option>
                                    <?php if ($this->grant->check_privilege('MOD_PUBLICCUSTOMER', 'view')) { ?>
                                        <option value="ALL">{{.public_customer.}}</option>
                                    <?php }
                                    foreach ($this->salesUserList as $salesUser) { ?>
                                        <option value="<?php echo $salesUser['username']; ?>"><?php echo $salesUser['fullname']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">{{.accountant_name.}}</td>
                            <td>
                                <select id="accountant" name="accountant">
                                    <option value="">-</option>
                                    <?php foreach ($this->accountantUserList as $accountUser) { ?>
                                        <option value="<?php echo $accountUser['username']; ?>"><?php echo $accountUser['fullname']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="btnSearch" id="btnSearch" value="{{.search.}}" />
                                <input type="reset" name="btnReset" id="btnReset" value="{{.reset.}}" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <table cellspacing="1" cellpadding="4" class="admintable">
            <tbody>
                <tr>
                    <td><?php echo ($this->pagingList ? $this->pagingList : ''); ?></td>
                    <td style="vertical-align: middle; padding-top: 0;" align="right">
                        {{.show.}}
                        <select id="cbItemPerPage" onchange="javascript:window.location.href='<?php echo Link::createAll(array('pagesize'), array(), false); ?>&pagesize='+this.value;" name="cbItemPerPage">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50" selected="selected">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="500">500</option>
                        </select>
                        {{.record.}} / {{.page.}}
                        <?php if (Link::get('pagesize')) { ?>
                            <script type="text/javascript">$('select#cbItemPerPage').val('<?php echo Link::get('pagesize'); ?>');</script>
                        <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="content-list">
            <form name="frmAdminItemsList" id="frmAdminItemsList" action="" method="post">
                <input name="cmd" type="hidden" id="cmd" />
                <table cellspacing="0" border="1" class="adminlist stockout-index">
                    <thead>
                        <tr>
                            <th class="col_20">#</th>
                            <th class="col_25"><input type="checkbox" onclick="javascript:SelectAllCheckboxes(this);" name="chkAll" id="chkAll"></th>
                            <th class="col_70">{{.create_time.}}</th>
                            <th class="col_70">{{.col_date_require.}}</th>
                            <th class="col_70">{{.expired_date.}}</th>
                            <th class="col_70">{{.time_finish.}}</th>
                            <th class="col_60">{{.outsource_number.}}</th>
                            <th class="col_50">{{.customer_code.}}</th>
                            <th class="col_200">{{.customer_name.}}</th>
                            <th class="col_100">{{.note.}}</th>
                            <th class="col_100">{{.accountant_name.}}</th>
                            <th class="col_100">{{.handler.}}</th>
                            <th class="col_50">{{.number_hours_performed.}}</th>
                            <th class="col_50">{{.warehouse_note.}}</th>
                            <th class="col_50">{{.date_completed.}}</th>
                            <th class="col_80">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($this->itemList as $key => $value) { ?>
                            <tr class="<?php echo ($i % 2 == 1 ? 'row0' : 'row1') . ' ' . strtolower($value['status']); ?>">
                                <td align="right"><?php echo $i; ?></td>
                                <td align="center"><input type="checkbox" name="chkid[]" value="<?php echo $value['id']; ?>" /></td>
                                <td align="center"><?php echo $value['create_time']; ?></td>
                                <td align="center"><?php echo $value['date_out']; ?></td>
                                <td align="center"><?php echo Systems::displayVnDate($value['expired_date']); ?></td>
                                <td align="center"><?php echo Systems::displayVnDate($value['time_finished']); ?></td>
                                <td align="center"><?php echo $value['outsource_number']; ?></td>
                                <td align="center"><?php echo $value['customer_code']; ?></td>
                                <td>
                                    <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'view')) { ?>
                                        <a href="<?php echo Link::createAdmin_current(array('cmd' => 'view', 'id' => $value['id'])); ?>" title="{{.view_detail.}}"><?php echo $value['customer_name']; ?></a>
                                    <?php } else {
                                        echo $value['customer_name'];
                                    } ?>
                                </td>
                                <td><?php echo $value['note']; ?></td>
                                <td align="center"><?php echo $value['accountant_name']; ?></td>
                                <td align="center"><?php echo $value['handler_list_name']; ?></td>
                                <td align="center"><?php echo $value['num_hours']; ?></td>
                                <td align="center"><?php echo $value['warehouse_note']; ?></td>
                                <td align="center"><?php echo $value['time_complete_date']; ?></td>
                                <td align="center">
                                    <div id="button<?php echo $value['id']; ?>">
                                        <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit') && in_array($value['status'], array('PENDING', 'APPROVED'))) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('cmd' => 'edit', 'id' => $value['id'])); ?>" title="{{.edit.}}" class="list-button">{{.edit.}}</a>
                                        <?php }
                                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved') && in_array($value['status'], array('PENDING', 'FINISHED'))) { ?>
                                            <a href="javascript:void(0);" title="{{.approved.}} {{.edit.}}" onclick="javascript:if(confirm('{{.are_you_sure_want_to_approve_this_order.}}')){window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'approveedit', 'id' => $value['id'])); ?>';}" class="list-button">{{.approved.}} {{.edit.}}</a>
                                        <?php }
                                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit') && $value['status'] == 'PENDING') { ?>
                                            <a href="javascript:void(0);" title="{{.finish_order.}}" onclick="javascript:if(confirm('{{.are_you_sure_want_to_finish_this_order.}}')){window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'finishOrder', 'id' => $value['id'])); ?>';}" class="list-button">{{.finish.}}</a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++;
                        } ?>
                    </tbody>
                </table>
            </form>
        </div>
        <table cellspacing="1" cellpadding="4" class="admintable search">
            <tbody>
                <tr>
                    <td><?php echo ($this->pagingList ? $this->pagingList : ''); ?></td>
                    <td align="right">{{.total.}}: <font color="Brown"><span><?php echo $this->totalRecord; ?></span></font> {{.record.}} / <?php echo $this->totalPage; ?> {{.page.}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/stock-out-48.png'; ?>" />
            <span>{{.outsourcing_order_list.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'add')) { ?>
                            <td align="center">
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'add')); ?>" class="toolbar" title="{{.add_new.}}"><span class="icon-button Icon-32-Add" title="{{.add_new.}}"></span>{{.add_new.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.finish_order.}}" onclick="confirmCheckAction('frmAdminItemsList', 'finishListOrder', '{{.are_you_sure_want_to_finish_the_selected_items.}}');"><span class="icon-button Icon-32-Finish" title="{{.finish_order.}}"></span>{{.finish.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) { ?>
                            <td align="center">
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'statistic')); ?>" class="toolbar" title="{{.statistic.}}"><span class="icon-button Icon-32-Statistic" title="{{.statistic.}}"></span>{{.statistic.}}</a>
                            </td>
                        <?php }
                        if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.approved.}} {{.edit.}}" onclick="confirmCheckAction('frmAdminItemsList', 'approvelistedit', '{{.are_you_sure_want_to_approve_the_selected_items.}}');"><span class="icon-button Icon-32-Approve" title="{{.approved.}} {{.edit.}}"></span>{{.approved.}} {{.edit.}}</a>
                            </td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>