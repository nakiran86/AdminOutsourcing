<script type="text/javascript">
    function addNewRow(ele) {
        if (!($('#customer_id').val())) {
            alert('{{.action_not_allowed.}}');
        } else {
            var id = $("#rid").val();
            $("tbody#list_" + ele).append('<tr id="row' + id + '"><td><a href="#" class="removethis" onclick="removerow(\'tr#row' + id + '\'); return false;"><img src="public\/button\/cancel-32.png" width="24" height="24" alt="remove input" /><\/a><input type="hidden" name="records_new_' + ele + '[' + id + '][real_id]" id="records_new_' + ele + '_' + id + '_real_id" readonly="readonly" \/><\/td><td align="center"><input type="text" name="records_new_' + ele + '[' + id + '][real_code]" id="records_new_' + ele + '_' + id + '_real_code" onblur="return fillout(this.value, ' + id + ', \'real\', \'' + ele + '\');" \/><\/td><td><input type="text" name="records_new_' + ele + '[' + id + '][real_name]" id="records_new_' + ele + '_' + id + '_real_name" onblur="return fillout($(\'input#records_new_' + ele + '_' + id + '_real_code\').val(), ' + id + ', \'real\', \'' + ele + '\');" \/><\/td><td><input type="text" name="records_new_' + ele + '[' + id + '][real_specs]" id="records_new_' + ele + '_' + id + '_real_specs" \/><\/td><td><input type="text" name="records_new_' + ele + '[' + id + '][real_unit]" id="records_new_' + ele + '_' + id + '_real_unit" \/><\/td><td><input type="number" name="records_new_' + ele + '[' + id + '][real_quantity]" id="records_new_' + ele + '_' + id + '_real_quantity" class="TinyText" min="0" autocomplete="off" \/>\/<span class="qty-available qty-new-' + id + '">-<\/span><\/td><td><input type="number" name="records_new_' + ele + '[' + id + '][real_hire_price]" id="records_new_' + ele + '_' + id + '_real_hire_price" min="0" value="0" readOnly="true" \/><\/td><td><select name="records_new_' + ele + '[' + id + '][price_accounting]" id="records_new_' + ele + '_' + id + '_price_accounting"> <option value="CAPITAL_PRICE">{{.price_accounting_capital.}}<\/option> <option value="INTERNAL_COST">{{.price_accounting_local.}}<\/option> <\/select><\/td><td><input type="number" name="records_new_' + ele + '[' + id + '][real_price]" id="records_new_' + ele + '_' + id + '_real_price" min="0" value="0" readonly="readonly" \/><\/td><\/tr>');
            $('#records_new_' + ele + '_' + id + '_real_code').autocomplete({
                source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'code')); ?>',
                minChars: 2, max: 15, width: 200, selectFirst: false,
                select: function(event, ui) {
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_id').val(ui.item.id);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_name').val(ui.item.name);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_specs').val(ui.item.desc);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_unit').val(ui.item.unit);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_quantity').val(0);
                    $('.qty-new-' + (id - 1) + '').text(formatNumber(ui.item.quantity));
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append("<a>" + item.value + "<br>" + item.name + "<\/a>").appendTo(ul);
            };
            $('#records_new_' + ele + '_' + id + '_real_name').autocomplete({
                source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'name')); ?>',
                minChars: 2, max: 15, width: 200, selectFirst: false,
                select: function(event, ui) {
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_id').val(ui.item.id);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_code').val(ui.item.code);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_specs').val(ui.item.desc);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_unit').val(ui.item.unit);
                    $('#records_new_' + ele + '_' + (id - 1) + '_real_quantity').val(0);
                    $('.qty-new-' + (id - 1) + '').text(formatNumber(ui.item.quantity));
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append("<a>" + item.value + "<br>" + item.desc + "<\/a>").appendTo(ul);
            };
            var options = {};
            $('#row' + id).effect('highlight', options, 500, function() {
                setTimeout(function() {
                    $('#row' + id).removeAttr("style").hide().fadeIn();
                }, 2000);
            });
            id = (id - 1) + 2;
            $("#rid").val(id);
        }
    }

    function addNewMaterial(ele, rtype, index) {
        var id = $("#rid").val();
        $("tbody#" + ele + '_' + index).append('<tr id="row_material_' + id + '"><td align="center"><a href="#" class="removethis" onclick="removerow(\'tr#row_material_' + id + '\'); return false;"><span class="icon-button-16 Icon-16-Delete" title="{{.delete.}}"></span><\/a><input type="hidden" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_id]" id="' + rtype + '_' + ele + '_' + id + '_real_id" readonly="readonly" \/><\/td><td align="center"><input type="text" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_code]" id="' + rtype + '_' + ele + '_' + id + '_real_code" onblur="return fillout(this.value, ' + id + ', \'real\', \'' + ele + '\');" \/><\/td><td><input type="text" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_name]" id="' + rtype + '_' + ele + '_' + id + '_real_name" onblur="return fillout($(\'input#' + rtype + '_' + ele + '_' + id + '_real_code\').val(), ' + id + ', \'real\', \'' + ele + '\');" \/><\/td><td><input type="text" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_specs]" id="' + rtype + '_' + ele + '_' + id + '_real_specs" \/><\/td><td><input type="text" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_unit]" id="' + rtype + '_' + ele + '_' + id + '_real_unit" \/><\/td><td><input type="text" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_norm]" id="' + rtype + '_' + ele + '_' + id + '_real_norm" min="0" class="TinyText" onkeyup="return showPrice(\'new\',\'' + index + '\',\'' + id + '\');" \/><\/td><td><input type="text" name="' + rtype + '_' + ele + '[' + index + '][' + id + '][real_price]" id="' + rtype + '_' + ele + '_' + id + '_real_price" min="0" value="0" onkeyup="return showPrice(\'new\',\'' + index + '\',\'' + id + '\');" \/><\/td><td align="right" id="' + rtype + '_' + ele + '_' + id + '_real_amount" class="subtotal-' + index + '">&nbsp;<\/td><\/tr>');
        $('#' + rtype + '_' + ele + '_' + id + '_real_code').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'code')); ?>&proid=' + $('#records_withdrawal_' + index + '_real_id').val(),
            minChars: 2, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_id').val(ui.item.id);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_name').val(ui.item.name);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_specs').val(ui.item.desc);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_unit').val(ui.item.unit);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_price').val(ui.item.price);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_norm').val(ui.item.norm);
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>").append("<a>" + item.value + "<br>" + item.name + "<\/a>").appendTo(ul);
        };
        $('#' + rtype + '_' + ele + '_' + id + '_real_name').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'name')); ?>&proid=' + $('#records_withdrawal_' + index + '_real_id').val(),
            minChars: 2, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_id').val(ui.item.id);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_code').val(ui.item.code);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_specs').val(ui.item.desc);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_unit').val(ui.item.unit);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_price').val(ui.item.price);
                $('#' + rtype + '_' + ele + '_' + (id - 1) + '_real_norm').val(ui.item.norm);
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>").append("<a>" + item.value + "<br>" + item.desc + "<\/a>").appendTo(ul);
        };
        id = (id - 1) + 2;
        $("#rid").val(id);
    }

    function showPrice(rtype, prokey, matkey) {
        var matprice = Number($("input#" + rtype + "_list_material_" + matkey + "_real_norm").val()) * Number($("input#" + rtype + "_list_material_" + matkey + "_real_price").val());
        $("td#" + rtype + "_list_material_" + matkey + "_real_amount").text(formatNumber(matprice, 0));
        var proprice = 0;
        $('td.subtotal-' + prokey).each(function() {
            proprice += Number($(this).text().replace(/\./g, '').replace(/\,/g, '.'));
        });
        var price_accounting = $("#input#records_withdrawal_" + prokey + "_price_accounting").val();
        if (price_accounting == 'CAPITAL_PRICE') {
            proprice += Number($("input#records_withdrawal_" + prokey + "_real_hire_price").val());
        }
        $("input#records_withdrawal_" + prokey + "_real_price").val(proprice.toFixed(0));
    }

    $(document).ready(function() {
        // $('#date_out').datepicker({ dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true });
        $('#date_out').datetimepicker({
            minDate: '+0d',
            format: 'H:i d/m/Y',
            step: 15
        });
        $('#expired_date').datepicker({ dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true });
        $('a#add_row').click(function() { addNewRow('withdrawal'); });
    <?php if (Link::getPost('records_new')) { ?>
        <?php foreach (Link::getPost('records_new') as $index => $record) { ?>
            $('#records_new_s<?php echo $index; ?>_real_code').autocomplete({
                source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'code')); ?>',
                minChars: 2, max: 15, width: 200, selectFirst: false,
                select: function(event, ui) {
                    $('#records_new_s<?php echo $index; ?>_real_id').val(ui.item.id);
                    $('#records_new_s<?php echo $index; ?>_real_name').val(ui.item.name);
                    $('#records_new_s<?php echo $index; ?>_real_specs').val(ui.item.desc);
                    $('#records_new_s<?php echo $index; ?>_real_unit').val(ui.item.unit);
                    $('#records_new_s<?php echo $index; ?>_real_quantity').val(ui.item.quantity);
                }
            }).autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>").append("<a>" + item.value + "<br>" + item.name + "<\/a>").appendTo(ul);
            };
            $('#records_new_s<?php echo $index; ?>_real_name').autocomplete({
                source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'name')); ?>',
                minChars: 2, max: 15, width: 200, selectFirst: false,
                select: function(event, ui) {
                    $('#records_new_s<?php echo $index; ?>_real_id').val(ui.item.id);
                    $('#records_new_s<?php echo $index; ?>_real_code').val(ui.item.code);
                    $('#records_new_s<?php echo $index; ?>_real_specs').val(ui.item.desc);
                    $('#records_new_s<?php echo $index; ?>_real_unit').val(ui.item.unit);
                    $('#records_new_s<?php echo $index; ?>_real_quantity').val(ui.item.quantity);
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append("<a>" + item.value + "<br>" + item.desc + "<\/a>").appendTo(ul);
            };
    <?php }
    } ?>
        $('input#buyer_code').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'showCustomer', 'list' => 'code')); ?>',
            minChars: 1, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $("#buyer_company").val(ui.item.desc);
                $("#buyer_address").val(ui.item.address);
                $("#customer_id").val(ui.item.id);
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>").append("<a>" + item.value + "<br>" + item.desc + "<\/a>").appendTo(ul);
        };
    $('input#buyer_company').autocomplete({
        source: '<?php echo Link::createAdmin_current(array('cmd' => 'showCustomer', 'list' => 'multi')); ?>',
        minChars: 1, max: 15, width: 200, selectFirst: false,
        select: function(event, ui) {
            $("#buyer_code").val(ui.item.code);
            $("#buyer_address").val(ui.item.desc);
            $("#customer_id").val(ui.item.id);
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>").append("<a>" + item.value + "<br>" + item.desc + "<\/a>").appendTo(ul);
    };
});

    function checkExistCustomer(cusname, listtype) {
        $.ajax({
            url: '<?php echo Link::createAdmin_current(array('cmd' => 'showCustomer')); ?>',
            data: {
                term: cusname,
                list: listtype
            },
            success: function(data) {
                if (data) {
                    datashow = JSON.parse(data);
                    $('#buyer_code').val(datashow.company_code);
                    $('#buyer_company').val(datashow.company_name);
                    $('#buyer_address').val(datashow.company_address);
                    $('#customer_id').val(datashow.id);
                }
            }
        });
    }

    function removerow(id) {
        $(id).remove();
    }

    function fillout(itemname, rowid, type, ele) {
        $.ajax({
            url: '<?php echo Link::createAdmin_current(); ?>',
            data: {
                cmd: 'fillinfo',
                nameid: itemname,
                arange: ele
            },
            success: function(data) {
                if (data) {
                    datashow = JSON.parse(data);
                    $('#records_new_' + ele + '_' + rowid + '_' + type + '_id').val(datashow.id);
                    $('#records_new_' + ele + '_' + rowid + '_' + type + '_code').val(datashow.code);
                    $('#records_new_' + ele + '_' + rowid + '_' + type + '_name').val(datashow.name);
                    $('#records_new_' + ele + '_' + rowid + '_' + type + '_specs').val(datashow.specification);
                    $('#records_new_' + ele + '_' + rowid + '_' + type + '_unit').val(datashow.unit);
                }
            }
        });
    }
    function changePriceAccounting(value, prokey) {
        proprice = 0;
        $('td.subtotal-' + prokey).each(function() {
            proprice += Number($(this).text().replace(/\./g, '').replace(/\,/g, '.'));
        });
        if (value == 'CAPITAL_PRICE') {
            proprice += Number($("input#records_withdrawal_" + prokey + "_real_hire_price").val());
        }
        $("input#records_withdrawal_" + prokey + "_real_price").val(proprice.toFixed(0));
    }
</script>
<div class="content-wrapper">
    <div class="path">
        <ul>
            <li><a href="<?php echo Link::create('admin'); ?>">{{.home.}}</a></li>
            <li class="SecondLast"><a href="<?php echo Link::createAdmin_current(); ?>">{{.outsourcing_order.}}</a></li>
            <li class="Last"><span><?php echo (Link::get('cmd') == 'edit' || Link::get('cmd') == 'editSave') ? '{{.edit.}} ' : '{{.add_new.}} '; ?> {{.outsourcing_order.}}</span></li>
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
            <span><?php echo (Link::get('cmd') == 'edit' || Link::get('cmd') == 'editSave') ? '{{.edit.}} ' : '{{.add_new.}} '; ?> {{.outsourcing_order.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.add_new.}}" id="add_row"><span class="icon-button Icon-32-Add" title="{{.add_new.}}"></span>{{.add_new.}}</a>
                        </td>
                        <?php if (in_array(Link::get('cmd'), array('add', 'create'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.save.}}" onclick="action('frmAdminItemEdit', 'create');"><span class="icon-button Icon-32-Save" title="{{.save.}}"></span>{{.save.}}</a>
                            </td>
                        <?php }
                        if (in_array(Link::get('cmd'), array('edit', 'editSave'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.update.}}" onclick="action('frmAdminItemEdit', 'editSave');"><span class="icon-button Icon-32-Apply" title="{{.update.}}"></span>{{.update.}}</a>
                            </td>
                            <?php if ($this->grant->check_privilege('MOD_ADMINSTOCKOUTLOCAL', 'delete')) { ?>
                                <td align="center">
                                    <a href="javascript:void(0);" class="toolbar" title="{{.delete.}}" onclick="confirmAction('frmAdminItemEdit', 'delete', '{{.are_you_sure_want_to_delete_this_item.}}');"><span class="icon-button Icon-32-Delete" title="{{.delete.}}"></span>{{.delete.}}</a>
                                </td>
                            <?php }
                        } ?>
                        <td align="center">
                            <a href="<?php echo Link::createAdmin_current(); ?>" class="toolbar" title="{{.back.}}"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($this->error) { ?><div class="content-box error-box"><?php echo $this->error; ?></div><?php } ?>
    <div class="content-box">
        <div class="content-list">
            <form name="frmAdminItemEdit" id="frmAdminItemEdit" action="" method="post">
                <input name="cmd" type="hidden" id="cmd" />
                <input type="hidden" name="rid" id="rid" value="1" />
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo Link::getPost('customer_id'); ?>" />
                <table cellspacing="1" cellpadding="4" class="admintable">
                    <tr>
                        <?php echo Link::getPost('date_out'); ?>
                        <td class="key"><span class="Required">*</span> {{.col_date_require.}}</td>
                        <td><input name="date_out" type="text" id="date_out" placeholder="H:i dd/mm/yyyy" value="<?php echo (Link::getPost('date_out') ? Link::getPost('date_out') : date('H:i d/m/Y')); ?>" class="TextInput" maxlength="10" autocomplete="off" /></td>
                    </tr>
                    <tr>
                        <td class="key"><span class="Required">*</span> {{.expired_date.}}</td>
                        <td><input name="expired_date" type="text" id="expired_date" value="<?php echo Link::getPost('expired_date'); ?>" class="TextInput" maxlength="10" autocomplete="off" /></td>
                    </tr>
                    <tr>
                        <td class="key"><span class="Required">*</span> {{.customer_code.}}</td>
                        <td><input name="buyer_code" type="text" id="buyer_code" value="<?php echo Link::getPost('buyer_code'); ?>" class="TextInput" onblur="return checkExistCustomer(this.value, 'scode'); return false;" /></td>
                    </tr>
                    <tr>
                        <td class="key"><span class="Required">*</span> {{.customer_name.}}</td>
                        <td><input name="buyer_company" type="text" id="buyer_company" value="<?php echo Link::getPost('buyer_company'); ?>" class="TextInput" onblur="return checkExistCustomer(this.value, 'single'); return false;" /><span id="sales-man"></span></td>
                    </tr>
                    <tr>
                        <td class="key"><span class="Required">*</span> {{.customer_address.}}</td>
                        <td><input name="buyer_address" type="text" id="buyer_address" value="<?php echo Link::getPost('buyer_address'); ?>" class="TextInput" /></td>
                    </tr>
                    <tr>
                        <td class="key">{{.construction.}}</td>
                        <td>
                            <select name="construction" id="construction">
                                <option value="OUTSIDE_HOURS">{{.outside_hours.}}</option>
                                <option value="OFFICE_HOURS">{{.office_hours.}}</option>
                            </select>
                            <script>$("#construction").val('<?php echo Link::getPost('construction', 'OFFICE_HOURS'); ?>');</script>
                        </td>
                    </tr>
                    <tr>
                        <td class="key">{{.note.}}</td>
                        <td><textarea name="note" id="note"><?php echo Link::getPost('note'); ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:0"><span id="showhideproduct"></span></td>
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
                                        <th class="col_60">{{.unit_hire_price.}}</th>
                                        <th class="col_60">{{.price_accounting.}}</th>
                                        <th class="col_60">{{.finished_price.}}</th>
                                    </tr>
                                </thead>
                                <?php if (in_array(Link::get('cmd'), array('edit', 'editSave'))) {
                                    $iw = 1; ?>
                                    <tbody id="list_withdrawal_edit">
                                        <?php foreach ($this->proList as $keW => $veW) { ?>
                                            <tr id="rows<?php echo $keW; ?>" class="<?php echo ($iw % 2 == 1 ? 'row0' : 'row1'); ?>">
                                                <td><a href="#" class="removethis" onclick="removerow('tr#rows<?php echo $keW; ?>'); return false;"><span class="icon-button-16 Icon-16-Delete" title="{{.delete.}}"></span></a><input type="hidden" name="records_withdrawal[<?php echo $keW; ?>][real_id]" id="records_withdrawal_<?php echo $keW; ?>_real_id" value="<?php echo $veW['product_id']; ?>" readonly="readonly" /></td>
                                                <td align="center"><?php echo $veW['product_code']; ?></td>
                                                <td><?php echo $veW['product_name']; ?></td>
                                                <td><?php echo $veW['product_specs']; ?></td>
                                                <td><?php echo $veW['product_unit']; ?></td>
                                                <td><input type="number" name="records_withdrawal[<?php echo $keW; ?>][real_quantity]" id="records_withdrawal_<?php echo $keW; ?>_real_quantity" value="<?php echo $veW['quantity']; ?>" autocomplete="off" /></td>
                                                <td><input type="number" name="records_withdrawal[<?php echo $keW; ?>][real_hire_price]" id="records_withdrawal_<?php echo $keW; ?>_real_hire_price" value="<?php echo $veW['hire_price']; ?>" readOnly="true" /></td>
                                                <td align="center">
                                                    <select name="records_withdrawal[<?php echo $keW; ?>][price_accounting]" id="records_withdrawal_<?php echo $keW; ?>_price_accounting" onchange="return changePriceAccounting(this.value, '<?php echo $keW; ?>');">
                                                        <option value="CAPITAL_PRICE">{{.price_accounting_capital.}}</option>
                                                        <option value="INTERNAL_COST">{{.price_accounting_local.}}</option>
                                                    </select>
                                                    <script>$("#records_withdrawal_<?php echo $keW; ?>_price_accounting").val('<?php echo $veW['price_accounting']; ?>');</script>
                                                </td>
                                                <td><input type="text" name="records_withdrawal[<?php echo $keW; ?>][real_price]" id="records_withdrawal_<?php echo $keW; ?>_real_price" value="<?php echo $veW['price']; ?>" readonly="readonly" /></td>
                                            </tr>
                                            <tr id="rows<?php echo $keW; ?>">
                                                <td>&nbsp;</td>
                                                <td colspan="9">
                                                    <table cellspacing="0" border="1" class="adminlist">
                                                        <thead>
                                                            <tr>
                                                                <th class="col_20"><a href="javascript:void(0);" title="{{.add_new.}}" onclick="addNewMaterial('list_material','new','<?php echo $keW; ?>'); return false;"><span class="icon-button-16 Icon-16-Add" title="{{.add_new.}}"></span></a></th>
                                                                <th class="col_60">{{.col_code.}}</th>
                                                                <th class="col_250">{{.col_name.}}</th>
                                                                <th class="col_200">{{.col_specification.}}</th>
                                                                <th class="col_50">{{.col_unit.}}</th>
                                                                <th class="col_60">{{.product_norms.}}</th>
                                                                <th class="col_60">{{.unit_price.}}</th>
                                                                <th class="col_60">{{.amount.}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="list_material_<?php echo $keW; ?>">
                                                            <?php foreach ($this->proMaterialList as $keM => $veM) { ?>
                                                                <?php if ($veM['outsourcing_product_id'] == $keW) { ?>
                                                                    <tr id="rows_material_<?php echo $keM; ?>" class="m-row">
                                                                        <td align="center"><a href="javascript:void(0);" class="removethis" onclick="removerow('tr#rows_material_<?php echo $keM; ?>'); return false;"><span class="icon-button-16 Icon-16-Delete" title="{{.delete.}}"></span></a><input type="hidden" name="edit_list_material[<?php echo $keW; ?>][<?php echo $keM; ?>][real_id]" id="edit_list_material_<?php echo $keM; ?>_real_id" value="<?php echo $veM['product_id']; ?>" readonly="readonly" /></td>
                                                                        <td align="center"><?php echo $veM['product_code']; ?></td>
                                                                        <td><?php echo $veM['product_name']; ?></td>
                                                                        <td><?php echo $veM['product_specs']; ?></td>
                                                                        <td><?php echo $veM['product_unit']; ?></td>
                                                                        <td><input type="text" name="edit_list_material[<?php echo $keW; ?>][<?php echo $keM; ?>][real_norm]" id="edit_list_material_<?php echo $keM; ?>_real_norm" value="<?php echo $veM['product_norm']; ?>" onkeyup="return showPrice('edit','<?php echo $keW; ?>','<?php echo $keM; ?>');" /></td>
                                                                        <td><input type="text" name="edit_list_material[<?php echo $keW; ?>][<?php echo $keM; ?>][real_price]" id="edit_list_material_<?php echo $keM; ?>_real_price" value="<?php echo $veM['product_price']; ?>" onkeyup="return showPrice('edit','<?php echo $keW; ?>','<?php echo $keM; ?>');" /></td>
                                                                        <td align="right" id="edit_list_material_<?php echo $keM; ?>_real_amount" class="subtotal-<?php echo $keW; ?>"><?php echo Systems::displayNumber(($veM['product_price'] * $veM['product_norm']), 0); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php if (Link::getPost('new_list_material') && isset(Link::getPost('new_list_material')[$keW])) { ?>
                                                                <?php foreach (Link::getPost('new_list_material')[$keW] as $idm => $recm) { ?>
                                                                    <tr id="row_material_n<?php echo $idm; ?>">
                                                                        <td><a href="#" class="removethis" onclick="removerow('tr#row_material_n<?php echo $idm; ?>'); return false;"><span class="icon-button-16 Icon-16-Delete" title="{{.delete.}}"></span></a><input type="hidden" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_id]" id="new_list_material_n<?php echo $idm; ?>_real_id" readonly="readonly" value="<?php echo $recm['real_id']; ?>" /></td>
                                                                        <td align="center"><input type="text" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_code]" id="new_list_material_n<?php echo $idm; ?>_real_code" onblur="return fillout(this.value, 'n<?php echo $idm; ?>', 'real', 'list_material');" value="<?php echo $recm['real_code']; ?>" autocomplete="off" /></td>
                                                                        <td><input type="text" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_name]" id="new_list_material_n<?php echo $idm; ?>_real_name" onblur="return fillout($('input#new_list_material_n<?php echo $idm; ?>_real_code').val(), 'n<?php echo $idm; ?>', 'real', 'list_material');" value="<?php echo $recm['real_name']; ?>" autocomplete="off"></td>
                                                                        <td><input type="text" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_specs]" id="new_list_material_n<?php echo $idm; ?>_real_specs" value="<?php echo $recm['real_specs']; ?>" /></td>
                                                                        <td><input type="text" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_unit]" id="new_list_material_n<?php echo $idm; ?>_real_unit" value="<?php echo $recm['real_unit']; ?>" /></td>
                                                                        <td><input type="text" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_norm]" id="new_list_material_n<?php echo $idm; ?>_real_norm" min="0" value="<?php echo $recm['real_norm']; ?>" class="TinyText" onkeyup="return showPrice('new','<?php echo $keW; ?>','n<?php echo $idm; ?>');" /></td>
                                                                        <td><input type="text" name="new_list_material[<?php echo $keW; ?>][n<?php echo $idm; ?>][real_price]" id="new_list_material_n<?php echo $idm; ?>_real_price" min="0" value="<?php echo $recm['real_price']; ?>" onkeyup="return showPrice('new','<?php echo $keW; ?>','n<?php echo $idm; ?>');" /></td>
                                                                        <td align="right" id="new_list_material_n<?php echo $idm; ?>_real_amount" class="subtotal-<?php echo $keW; ?>"><?php echo Systems::displayNumber(($recm['real_norm'] * $recm['real_price']), 0); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <?php $iw++;
                                        } ?>
                                    </tbody>
                                <?php } ?>
                                <tbody id="list_withdrawal">
                                    <?php if (Link::getPost('records_new_withdrawal')) { ?>
                                        <?php foreach (Link::getPost('records_new_withdrawal') as $idw => $recw) { ?>
                                            <tr id="rows<?php echo $idw; ?>">
                                                <td><a href="#" class="removethis" onclick="removerow('tr#rows<?php echo $idw; ?>'); return false;"><img src="public/button/cancel-32.png" width="24" height="24" alt="remove input" /></a><input type="hidden" name="records_new_withdrawal[s<?php echo $idw; ?>][real_id]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_id" value="<?php echo $recw['real_id']; ?>" readonly="readonly" /></td>
                                                <td align="center"><input type="text" name="records_new_withdrawal[s<?php echo $idw; ?>][real_code]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_code" onblur="return fillout(this.value, 's<?php echo $idw; ?>', 'real', 'stockout');" value="<?php echo $recw['real_code']; ?>" /></td>
                                                <td><input type="text" name="records_new_withdrawal[s<?php echo $idw; ?>][real_name]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_name" onblur="return fillout($('input#records_new_withdrawal_s<?php echo $idw; ?>_real_code').val(), 's<?php echo $idw; ?>', 'real', 'stockout');" value="<?php echo $recw['real_name']; ?>" /></td>
                                                <td><input type="text" name="records_new_withdrawal[s<?php echo $idw; ?>][real_specs]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_specs" value="<?php echo $recw['real_specs']; ?>" /></td>
                                                <td><input type="text" name="records_new_withdrawal[s<?php echo $idw; ?>][real_unit]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_unit" value="<?php echo $recw['real_unit']; ?>" /></td>
                                                <td><input type="number" name="records_new_withdrawal[s<?php echo $idw; ?>][real_quantity]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_quantity" value="<?php echo $recw['real_quantity']; ?>" autocomplete="off" /></td>
                                                <td><input type="number" name="records_new_withdrawal[s<?php echo $idw; ?>][real_hire_price]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_hire_price" value="<?php echo $recw['real_quantity']; ?>" /></td>
                                                <td><input type="text" name="records_new_withdrawal[s<?php echo $idw; ?>][real_price]" id="records_new_withdrawal_s<?php echo $idw; ?>_real_price" value="<?php echo $recw['real_quantity']; ?>" readonly="readonly" /></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/stock-out-48.png'; ?>" />
            <span><?php echo (Link::get('cmd') == 'edit' || Link::get('cmd') == 'editSave') ? '{{.edit.}} ' : '{{.add_new.}} '; ?> {{.outsourcing_order.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <td align="center">
                            <a href="javascript:void(0);" class="toolbar" title="{{.add_new.}}" id="add_row"><span class="icon-button Icon-32-Add" title="{{.add_new.}}"></span>{{.add_new.}}</a>
                        </td>
                        <?php if (in_array(Link::get('cmd'), array('add', 'create'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.save.}}" onclick="action('frmAdminItemEdit', 'create');"><span class="icon-button Icon-32-Save" title="{{.save.}}"></span>{{.save.}}</a>
                            </td>
                        <?php }
                        if (in_array(Link::get('cmd'), array('edit', 'editSave'))) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.update.}}" onclick="action('frmAdminItemEdit', 'editSave');"><span class="icon-button Icon-32-Apply" title="{{.update.}}"></span>{{.update.}}</a>
                            </td>
                            <?php if ($this->grant->check_privilege('MOD_ADMINSTOCKOUTLOCAL', 'delete')) { ?>
                                <td align="center">
                                    <a href="javascript:void(0);" class="toolbar" title="{{.delete.}}" onclick="confirmAction('frmAdminItemEdit', 'delete', '{{.are_you_sure_want_to_delete_this_item.}}');"><span class="icon-button Icon-32-Delete" title="{{.delete.}}"></span>{{.delete.}}</a>
                                </td>
                            <?php }
                        } ?>
                        <td align="center">
                            <a href="<?php echo Link::createAdmin_current(); ?>" class="toolbar" title="{{.back.}}"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>