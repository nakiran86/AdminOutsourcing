<script type="text/javascript">
$(document).ready(function() {
    $('input#from_date').datepicker({dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
        onClose: function( selectedDate ) {
            $("#to_date").datepicker( "option", "minDate", selectedDate);
        }
    });
    $('input#to_date').datepicker({dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
        onClose: function( selectedDate ) {
            $("#from_date").datepicker("option", "maxDate", selectedDate);
        }
    });
    $('input#customer_name').autocomplete({
        source: '<?php echo Link::createAdmin_current(array('cmd' => 'showfactory'));?>',
        minChars: 1, max: 15, width: 200, selectFirst: false,
        select: function(event, ui){
            $("#customer_id").val(ui.item.id);
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>").append("<a>" + item.code + "<br>" + item.value + "<\/a>").appendTo(ul);
    };
    $('input#factory_name').autocomplete({
        source: '<?php echo Link::createAdmin_current(array('cmd' => 'showfactory'));?>',
        minChars: 1, max: 15, width: 200, selectFirst: false,
        select: function(event, ui){
            $("#factory_id").val(ui.item.id);
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>").append("<a>" + item.code + "<br>" + item.value + "<\/a>").appendTo(ul);
    };
});

function showHideDetail(rowid, rowstatus, rowno) {
    if (rowstatus == 'show') {
        $('div#no' + rowid).html('<a href="javascript:void(0);" onclick="showHideDetail(\'' + rowid + '\', \'hide\', \'' + rowno + '\');" title="{{.hide_material.}}"><span class="icon-button-16 Icon-16-Collapse" title="{{.hide_material.}}"><\/span><\/a>');
        if ($('tr.detail' + rowid).length > 0) {
            $('tr.detail' + rowid).show();
        } else {
            $.ajax({
                url: '<?php echo Link::createAdmin_current(array('cmd' => 'showdetailstockout')); ?>',
                data: {
                    proid: rowid,
                    fromdate: "<?php echo Link::get('from_date'); ?>",
                    todate: "<?php echo Link::get('to_date'); ?>",
                    customer: "<?php echo Link::get('customer_id'); ?>",
                    factory: "<?php echo Link::get('factory_id'); ?>",
                    rowno: rowno
                },
                success: function(data){
                    $('tr#item' + rowid).after(data);
                }
            });
        }
    }
    if (rowstatus == 'hide') {
        $('div#no' + rowid).html('<a href="javascript:void(0);" onclick="showHideDetail(\'' + rowid + '\', \'show\', \'' + rowno + '\');" title="{{.show_material.}}"><span class="icon-button-16 Icon-16-Expand" title="{{.show_material.}}"><\/span><\/a>');
        $('tr.detail' + rowid).hide();
    }
}

function checkFactory(fname) {
    $.ajax({
        url: '<?php echo Link::createAdmin_current();?>',
        data: {
            cmd: 'checkfactory',
            nameid: fname
        },
        success: function(data){
            if (data) {
                datashow = JSON.parse(data);
                $('#factory_name').val(datashow.value);
                $('#factory_id').val(datashow.id);
            }
        }
    });
}

function checkCustomer(cname) {
    $.ajax({
        url: '<?php echo Link::createAdmin_current();?>',
        data: {
            cmd: 'checkfactory',
            nameid: cname
        },
        success: function(data){
            if (data) {
                datashow = JSON.parse(data);
                $('#customer_name').val(datashow.value);
                $('#customer_id').val(datashow.id);
            }
        }
    });
}
</script>
<div class="content-wrapper">
    <div class="path">
        <ul>
            <li><a href="<?php echo Link::create('admin'); ?>">{{.home.}}</a></li>
            <li class="SecondLast"><a href="<?php echo Link::createAdmin('outsourcing'); ?>">{{.outsourcing_order_list.}}</a></li>
            <li class="Last"><span>{{.outsourcing_order_statistics.}}</span></li>
        </ul>
    </div>
    <div class="subMenuBox">
        <ul class="submenu">
            <?php foreach ($this->subMenuList as $subMenu) { ?>
            <li><a href="<?php echo $subMenu['url']; ?>"<?php echo (Link::getUrl() == urlencode($subMenu['url']) ? ' class="active"' : '') ?>><?php echo $subMenu['name'];?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/report-48.png'; ?>" />
            <span>{{.outsourcing_order_statistics.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
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
            <div class="advance-search">
                <form name="frmAdvanceSearch" id="frmAdvanceSearch" method="get" action="">
                    <input name="page" type="hidden" id="page" value="<?php echo Link::get('page');?>" />
                    <input name="cmd" type="hidden" id="cmd" value="<?php echo Link::get('cmd');?>" />
                    <table cellspacing="1" cellpadding="4" class="admintable search" align="center">
                        <tbody>
                            <tr>
                                <td class="key">{{.col_date_import.}}</td>
                                <td>
                                    {{.from.}} <input type="text" name="from_date" id="from_date" maxlength="10" class="dateTime" value="<?php echo Link::get('from_date'); ?>" autocomplete="off" required="required" />
                                    {{.to.}} <input type="text" name="to_date" id="to_date" maxlength="10" class="dateTime" value="<?php echo Link::get('to_date'); ?>" autocomplete="off" required="required" />
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.customer_name.}}</td>
                                <td>
                                    <input type="text" name="customer_name" id="customer_name" class="TextInput" value="<?php echo Link::get('customer_name'); ?>" onblur="checkCustomer(this.value);" />  
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo Link::get('customer_id'); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.factory_name.}}</td>
                                <td>
                                    <input type="text" name="factory_name" id="factory_name" class="TextInput" value="<?php echo Link::get('factory_name'); ?>" onblur="checkFactory(this.value);" />  
                                    <input type="hidden" name="factory_id" id="factory_id" value="<?php echo Link::get('factory_id'); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.product.}}</td>
                                <td><input type="text" name="product" id="product" class="TextInput" value="<?php echo Link::get('product'); ?>" /></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center"><input type="submit" name="btnSearch" id="btnSearch" value="{{.search.}}" /><input type="button" class="Button" id="btnRemoveFilter" value="{{.remove_filter.}}" name="btnRemoveFilter" onclick="javascript:window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'statistic')); ?>';" /></td>
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
                            {{.total.}}: <font color="Brown"><span><?php echo $this->totalRecord; ?></span></font> {{.record.}} / <?php echo $this->totalPage; ?> {{.page.}} - {{.show.}}
                            <select id="cbItemPerPage" onchange="javascript:window.location.href='<?php echo Link::createAll(array('pagesize'),array(),false);?>&pagesize='+this.value;" name="cbItemPerPage">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50" selected="selected">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="500">500</option>
                            </select>
                            {{.record.}} / {{.page.}}
                            <?php if (Link::get('pagesize')) { ?>
                            <script type="text/javascript">$('select#cbItemPerPage').val('<?php echo Link::get('pagesize');?>');</script>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="content-list">
                <form name="frmAdminItemsList" id="frmAdminItemsList" action="" method="post">
                    <input name="cmd" type="hidden" id="cmd" />
                    <table cellspacing="0" border="1" class="adminlist">
                        <thead>
                            <tr>
                                <th colspan="2" width="35">#</th>
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
                            <?php if ($this->itemsList) { ?>
                            <?php $k = 1; foreach ($this->itemsList as $value) { ?>
                            <tr id="item<?php echo $value['id']; ?>" class="<?php echo ($k % 2 == 1 ? 'row0' : 'row1') . ($value['quantity_returned'] - ($value['quantity_issued'] - $value['quantity_production']) >= 0 ? '' : ' miss'); ?>">
                                <td width="15" align="center"><div id="no<?php echo $value['id']; ?>"><a href="javascript:void(0);" onclick="showHideDetail('<?php echo $value['id']; ?>', 'show', '<?php echo $k; ?>');" title="{{.show_detail_outsourcing_list.}}"><span class="icon-button-16 Icon-16-Expand" title="{{.show_detail_outsourcing_list.}}"></span></a></div></td>
                                <td width="20" align="right"><?php echo $k; ?></td>
                                <td align="center"><?php echo $value['item_code']; ?></td>
                                <td><?php echo $value['item_name']; ?></td>
                                <td><?php echo $value['item_specs']; ?></td>
                                <td align="center"><?php echo $value['item_unit']; ?></td>
                                <td align="right"><?php echo Systems::displayNumber($value['quantity_production']); ?></td>
                                <td align="right"><?php echo Systems::displayNumber($value['quantity_issued']); ?></td>
                                <td align="right"><?php echo Systems::displayNumber($value['quantity_issued'] - $value['quantity_production']); ?></td>
                                <td align="right"><?php echo Systems::displayNumber($value['quantity_returned']); ?></td>
                                <td align="right"><?php echo Systems::displayNumber($value['quantity_returned'] - ($value['quantity_issued'] - $value['quantity_production'])); ?></td>
                            </tr>
                            <?php $k++; } ?>
                            <?php } else { ?>
                            <tr><td colspan="11" align="center">{{.no_data.}}</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <table cellspacing="1" cellpadding="4" class="admintable">
                <tbody>
                    <tr>
                        <td><?php echo ($this->pagingList ? $this->pagingList : ''); ?></td>
                        <td align="right">{{.total.}}: <font color="Brown"><span><?php echo $this->totalRecord; ?></span></font> {{.record.}} / <?php echo $this->totalPage; ?> {{.page.}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/report-48.png'; ?>" />
            <span>{{.outsourcing_order_statistics.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.back.}}" onclick="goBack();"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>