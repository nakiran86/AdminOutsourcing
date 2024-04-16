<script type="text/javascript">
$(document).ready(function(){
    <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) { ?>
    <?php if (Link::getGet('action') == 'edit' && Link::getGet('list_id')) { ?>
    const idArr = '<?php echo Link::getGet('list_id'); ?>'.split(',');
    let firstId = true;
    for (const index of idArr) {
        // console.log(index);
        $('#records_'+index+'_edit_mode').val(0);
        $('div#code'+index).html('<input readonly type="text" name="records['+index+'][code]" id="records_'+index+'_code" value="'+$('div#code'+index).text()+'" \/>');
        $('div#name'+index).html('<input readonly type="text" name="records['+index+'][name]" id="records_'+index+'_name" value="'+$('div#name'+index).text()+'" \/>');
        $('div#size'+index).html('<input type="text" name="records['+index+'][size]" id="records_'+index+'_size" value="'+$('div#size'+index).text()+'" \/>');
        $('div#weight'+index).html('<input type="text" name="records['+index+'][weight]" id="records_'+index+'_weight" value="'+$('div#weight'+index).text()+'" \/>');
        $('div#packaging'+index).html('<input type="text" name="records['+index+'][packaging]" id="records_'+index+'_packaging" value="'+$('div#packaging'+index).text()+'" \/>');
        $('div#origin'+index).html('<input type="text" name="records['+index+'][origin]" id="records_'+index+'_origin" value="'+$('div#origin'+index).text()+'" \/>');
        $('div#other_information'+index).html('<textarea type="text" name="records['+index+'][other_information]" id="records_'+index+'_other_information">'+$('div#other_information'+index).text()+'<\/textarea>');
        if(firstId == true) {
            $('#records_' + index + '_other_information').focus();
            firstId = false;
        }
    }
    <?php } } ?>
    $('a#showAdvanceSearch').click(function() {
        $("#advance-search").dialog({
            height: 350,
            width: 610,
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
    <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) { ?>
        $('a#add_row').click(function() { addNewRow(); });
    <?php } ?>
});
<?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) { ?>
    function addNewRow() {
        var id = $("#rid").val();
        $("tbody#list").prepend('<tr id="row' + id + '"><td>&nbsp;<\/td><td align="center"><a href="#" class="removethis" onclick="removerow(\'tr#row' + id + '\'); return false;"><img src="public\/button\/cancel-32.png" width="24" height="24" alt="remove input" \/><\/a><\/td>\
        <td><input type="text" name="records_new[' + id + '][code]" id="records_new_' + id + '_code" \/><\/td>\
        <td><input type="text" name="records_new[' + id + '][name]" id="records_new_' + id + '_name" \/><\/td>\
        <td id="specification_' + id + '"><\/td>\
        <td align="center" id="unit_' + id + '"><\/td>\
        <td id="production_norms_' + id + '"><\/td>\
        <td><input type="text" name="records_new[' + id + '][overtime_cost]" id="records_new_' + id + '_overtime_cost" placeholder="{{.overtime_labor_cost.}}" autocomplete="off" onfocus="numberGroup(this.value, \'records_new_' + id + '_overtime_cost\')" onkeyup="numberGroup(this.value, \'records_new_' + id + '_overtime_cost\')" onblur="originalNumber(this.value, \'records_new_' + id + '_overtime_cost\')" value="0" \/><\/td>\
        <td><input type="text" name="records_new[' + id + '][regular_cost]" id="records_new_' + id + '_regular_cost_" placeholder="{{.regular_labor_cost.}}" autocomplete="off" onfocus="numberGroup(this.value, \'records_new_' + id + '_regular_cost_\')" onkeyup="numberGroup(this.value, \'records_new_' + id + '_regular_cost_\')" onblur="originalNumber(this.value, \'records_new_' + id + '_regular_cost_\')" value="0" \/><\/td><td>&nbsp;<\/td><\/tr>');
        $('#records_new_' + id + '_code').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'code')); ?>',
            minChars: 2, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $('#records_new_' + (id - 1) + '_name').val(ui.item.name);
                $('#specification_' + (id - 1)).html(ui.item.desc);
                $('#unit_' + (id - 1)).html(ui.item.unit);
                $('#specification_' + (id - 1)).html(ui.item.desc);
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>").append("<a>" + item.value + "<br>" + item.name + "<\/a>").appendTo(ul);
        };
        $('#records_new_' + id + '_name').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'proInfo', 'field' => 'name')); ?>',
            minChars: 2, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $('#records_new_' + (id - 1) + '_code').val(ui.item.code);
                $('#specification_' + (id - 1)).html(ui.item.desc);
                $('#unit_' + (id - 1)).html(ui.item.unit);
                $('#specification_' + (id - 1)).html(ui.item.desc);
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
    function edit_row(index) {
        var edit_mode = $('#records_' + index + '_edit_mode').val();
        if (edit_mode == 1) {
            $('#regular_cost_' + index).html('<input type="text" name="records[' + index + '][regular_cost]" id="records_' + index + '_regular_cost_" placeholder="{{.regular_labor_cost.}}" autocomplete="off" onfocus="numberGroup(this.value, \'records_' + index + '_regular_cost_\')" onkeyup="numberGroup(this.value, \'records_' + index + '_regular_cost_\')" onblur="originalNumber(this.value, \'records_' + index + '_regular_cost_\')" value="' + $('#regular_cost_' + index).text().replace(/\./g, '') + '" \/>');
            $('#overtime_cost_' + index).html('<input type="text" name="records[' + index + '][overtime_cost]" id="records_' + index + '_overtime_cost" placeholder="{{.overtime_labor_cost.}}" autocomplete="off" onfocus="numberGroup(this.value, \'records_' + index + '_overtime_cost\')" onkeyup="numberGroup(this.value, \'records_' + index + '_overtime_cost\')" onblur="originalNumber(this.value, \'records_' + index + '_overtime_cost\')" value="' + $('#overtime_cost_' + index).text().replace(/\./g, '') + '" \/>');
            $('#records_' + index + '_edit_mode').val(0);
        }
    }
    function del_row(index) {

    }
<?php } if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'edit')) { ?>
    function addNewNorms() {
        var id = $("#rid").val();
        var strHTMLNewRow = '<tr id="normsrow'+id+'"><td align="center"><a href="#" class="removethis" onclick="removerow(\'tr#normsrow'+id+'\'); return false;"><img src="public\/button\/cancel-32.png" width="24" height="24" alt="remove input" \/><\/a><input type="hidden" name="new_norms['+id+'][pro_id]" id="new_norms_'+id+'_pro_id" \/><\/td>';
        strHTMLNewRow += '<td align="center"><input type="text" name="new_norms['+id+'][pro_code]" id="new_norms_'+id+'_pro_code" onblur="return fillout($(\'input#new_norms_'+id+'_pro_code\').val(), '+id+');" \/><\/td>';
        strHTMLNewRow += '<td align="center"><input type="text" name="new_norms['+id+'][pro_name]" id="new_norms_'+id+'_pro_name" onblur="return fillout($(\'input#new_norms_'+id+'_pro_code\').val(), '+id+');" \/><\/td>';
        strHTMLNewRow += '<td align="center"><input type="text" name="new_norms['+id+'][pro_specs]" id="new_norms_'+id+'_pro_specs" readonly="readonly" \/><\/td>';
        strHTMLNewRow += '<td align="center"><input type="text" name="new_norms['+id+'][pro_unit]" id="new_norms_'+id+'_pro_unit" readonly="readonly" \/><\/td>';
        strHTMLNewRow += '<td align="center"><input type="text" name="new_norms['+id+'][pro_norms]" id="new_norms_'+id+'_pro_norms" \/><\/td>';
        strHTMLNewRow += '<\/tr>';
        $("table#tbl-pronorms").append(strHTMLNewRow);
        $('#new_norms_'+id+'_pro_code').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'productInfoCache', 'field' => 'code')); ?>', minChars: 2, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $('#new_norms_'+(id-1)+'_pro_id').val(ui.item.id);
                $('#new_norms_'+(id-1)+'_pro_name').val(ui.item.name);
                $('#new_norms_'+(id-1)+'_pro_specs').val(ui.item.desc);
                $('#new_norms_'+(id-1)+'_pro_unit').val(ui.item.unit);
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>").append("<a>" + item.value + "<br>" + item.name + "<\/a>").appendTo(ul);
        };
        $('#new_norms_'+id+'_pro_name').autocomplete({
            source: '<?php echo Link::createAdmin_current(array('cmd' => 'productInfoCache', 'field' => 'name')); ?>',
            minChars: 2, max: 15, width: 200, selectFirst: false,
            select: function(event, ui) {
                $('#new_norms_'+(id-1)+'_pro_id').val(ui.item.id);
                $('#new_norms_'+(id-1)+'_pro_code').val(ui.item.code);
                $('#new_norms_'+(id-1)+'_pro_specs').val(ui.item.desc);
                $('#new_norms_'+(id-1)+'_pro_unit').val(ui.item.unit);
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>").append("<a>" + item.value + "<br>" + item.desc + "<\/a>").appendTo(ul);
        };
        var options = {};
        $('#normsrow' + id).effect('highlight', options, 500, function(){
            setTimeout(function() {
                $('#normsrow' + id).removeAttr( "style" ).hide().fadeIn();
            }, 2000 );
        } );
        id = (id - 1) + 2;
        $("#rid").val(id);
    }

    function fillout(itemname, rowid) {
        $.ajax({
            url: '<?php echo Link::createAdmin_current(); ?>',
            data: {
                cmd: 'fillinfo',
                nameid: itemname
            },
            success: function(data){
                if (data) {
                    datashow = JSON.parse(data);
                    $('#new_norms_'+rowid+'_pro_id').val(datashow.id);
                    $('#new_norms_'+rowid+'_pro_code').val(datashow.code);
                    $('#new_norms_'+rowid+'_pro_name').val(datashow.name);
                    $('#new_norms_'+rowid+'_pro_specs').val(datashow.specification);
                    $('#new_norms_'+rowid+'_pro_unit').val(datashow.unit);
                }
            }
        });
    }

    function selectpronorm(proid) {
        $("#pronorms-box").load('<?php echo Link::createAdmin_current(array('cmd' => 'showpronorms')); ?>&id=' + proid);
        $("#pronorms-box").dialog({
            width: 800,
            modal: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            buttons: [{
                text: "{{.add_new.}}",
                click: function() { addNewNorms(); }
            },{
                text: '{{.save.}}',
                click: function() {
                    $.ajax({
                        url: '<?php echo Link::createAdmin_current(); ?>',
                        type: "POST",
                        data: $("#frmChangeNorms").serialize(),
                        success: function(data){
                            $("#pronorms-box").html('');
                            $("div#production-norms" + proid).html(data);
                        }
                    });
                    $(this).dialog( "close" );
                }
            }],
            close: function() {
                $("#pronorms-box").html('');
            }
        });
    }
<?php } ?>
</script>
<div class="content-wrapper">
    <div class="path">
        <ul>
            <li class="SecondLast"><a href="<?php echo Link::createAdmin('admin'); ?>">{{.home.}}</a></li>
            <li class="Last"><span>{{.list_product_outsourcing.}}</span></li>
        </ul>
    </div>
    <div class="subMenuBox">
        <ul class="submenu">
            <li><a href="<?php echo Link::createAdmin_current(array('cmd' => 'inventorystatus')); ?>"<?php echo (Link::get('cmd') == 'inventorystatus' ? ' class="active"' : '') ?>>{{.inventory_value_statistics.}}</a></li>
            <?php foreach ($this->subMenuList as $subMenu) { ?>
            <li><a href="<?php echo $subMenu['url']; ?>"<?php echo (Link::getUrl() == urlencode($subMenu['url']) ? ' class="active"' : '') ?>><?php echo $subMenu['name'];?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/generic-48.png'; ?>" />
            <span>{{.list_product_outsourcing.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" id="add_row" class="toolbar" title="{{.add_new.}}"><span class="icon-button Icon-32-Add" title="{{.add_new.}}"></span>{{.add_new.}}</a>
                            </td>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.save.}}" onclick="action('frmAdminItemsList', 'saveProduct');"><span class="icon-button Icon-32-Save" title="{{.save.}}"></span>{{.save.}}</a>
                            </td>
                        <?php } ?>
                        <td align="center">
                            <a href="javascript:void(0);" onclick="goBack();" class="toolbar" title="{{.back.}}"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="content-box">
        <div id="pronorms-box" title="{{.production_norms.}}"></div>
        <table cellspacing="1" cellpadding="4" class="admintable search">
            <tbody>
                <tr>
                    <td>{{.total.}}: <font color="Brown"><span><?php echo $this->totalRecord; ?></span></font> {{.record.}} / <?php echo $this->totalPage; ?> {{.page.}}</td>
                    <td style="vertical-align: middle; padding-top: 0;" align="right">
                        <form name="frmSearch" id="frmSearch" action="" method="get">
                            <input name="page" type="hidden" id="page" value="<?php echo Link::get('page');?>" />
                            <input name="cmd" type="hidden" id="cmd" value="list_product" />
                            {{.keyword.}}
                            <input type="text" class="TextInput" id="txtFindData" name="txtFindData" style="width: 200px;" />
                            <input type="submit" class="Button" id="btnFind" value="{{.search.}}" name="btnFind" />
                            <input type="button" class="Button" id="btnRemoveFilter" value="{{.remove_filter.}}" name="btnRemoveFilter" onclick="javascript:window.location.href='<?php echo Link::createAdmin_current(); ?>';" />
                            <a href="javascript:void(0);" id="showAdvanceSearch">{{.advance_search.}}</a>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="advance-search" title="{{.advance_search.}}">
            <form name="frmAdvanceSearch" id="frmAdvanceSearch" method="get" action="">
                <input name="page" type="hidden" id="page" value="<?php echo Link::get('page');?>" />
                <input name="cmd" type="hidden" id="cmd" value="list_product" />
                <table cellspacing="1" cellpadding="4" class="admintable search" align="center">
                    <tbody>
                        <tr>
                            <td class="key">{{.col_code.}}</td>
                            <td><input type="text" name="code" id="code" /></td>
                        </tr>
                        <tr>
                            <td class="key" style="white-space: normal;">{{.list_code.}}<div class="sub-title">{{.each_code_between.}}</div></td>
                            <td><input type="text" name="list_code" id="list_code" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.col_name.}}</td>
                            <td><input type="text" name="name" id="name" /></td>
                        </tr>
                        <tr>
                            <td class="key">{{.col_specification.}}</td>
                            <td><input type="text" name="specs" id="specs" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="btnSearch" id="btnSearch" value="{{.search.}}" />
                            <input type="reset" name="btnReset" id="btnReset" value="{{.reset.}}" /></td>
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
                <div class="tablewrapper" id="tablewrapper">
                <table cellspacing="0" border="1" class="adminlist">
                    <thead>
                        <tr>
                            <th class="col_20"><a href="<?php echo (Link::get('orid') == 'desc' ? Link::createAdmin_current(array('orid' => 'asc')) : Link::createAdmin_current(array('orid' => 'desc')));?>">#</a></th>
                            <th class="col_25"><input type="checkbox" onclick="javascript:SelectAllCheckboxes(this);" name="chkAll" id="chkAll"></th>
                            <th class="col_60">{{.col_code.}}</th>
                            <th class="col_250">{{.col_name.}}</th>
                            <th class="col_200">{{.col_specification.}}</th>
                            <th class="col_50">{{.product_unit.}}</th>
                            <th class="col_100">{{.production_norms.}}</th>
                            <th class="col_100">{{.approved_norms.}}</th>
                            <th class="col_100">{{.overtime_labor_cost.}}</th>
                            <th class="col_100">{{.regular_labor_cost.}}</th>
                            <th class="col_80">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="list">
                        <?php foreach ($this->productList as $key => $value) { ?>
                        <tr id="row<?php echo $value['id']; ?>" class="<?php echo ($value['index'] % 2 == 1 ? 'row0 ' : 'row1 ') . strtolower($value['status']); ?>">
                            <td align="right"><?php echo $value['index']; ?></td>
                            <td align="center"><input type="checkbox" name="chkid[]" value="<?php echo $value['id']; ?>" /></td>
                            <td class="col_center"><?php echo $value['code']; ?></td>
                            <td><?php echo $value['name']; ?></td>
                            <td><?php echo $value['specification']; ?></td>
                            <td class="col_center"><?php echo $value['unit']; ?></td>
                            <td class="col_left">
                                <div id="production-norms<?php echo $value['id']; ?>"><?php if ($value['production_norms_list']) { ?>
                                <table cellpadding="0" cellspacing="0" class="sub-table">
                                    <?php foreach ($value['production_norms_list'] as $pronorms) { ?>
                                    <tr><td class="col_left"><a href="javascript:void(0);" onclick="selectpronorm('<?php echo $value['id']; ?>');"><?php echo $pronorms['pro_code']; ?></a></td><td class="col_right"><?php echo $pronorms['pro_norms']; ?></td></tr>
                                    <?php } ?>
                                </table>
                                <?php } else { ?><a href="javascript:void(0);" onclick="selectpronorm('<?php echo $value['id']; ?>');">{{.not_yet_set.}}</a><?php } ?></div>
                            </td>
                            <td class="col_center"><?php echo $value['approve_norms_label'];?></td>
                            <td class="col_right"><div id="overtime_cost_<?php echo $value['id']; ?>"><?php echo $value['overtime_cost'];?></div></td>
                            <td class="col_right"><div id="regular_cost_<?php echo $value['id']; ?>"><?php echo $value['regular_cost'];?></div></td>
                            <td align="center">
                                <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) { ?>
                                <input name="records_<?php echo $value['id']; ?>_edit_mode" type="hidden" id="records_<?php echo $value['id']; ?>_edit_mode" value="1" />
                                <a href="javascript:void(0);" class="list-button" onclick="edit_row('<?php echo $value['id'];?>'); return false;">{{.edit.}}</a>
                                <a href="<?php echo Link::createAdmin_current(array('cmd' => 'deleteProduct', 'id' => $value['id'])); ?>" onclick="javascript:return confirm('{{.are_you_sure_want_to_delete_this_news.}}');" class="list-button">{{.delete.}}</a>
                                <?php }
                                if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'approved') && $this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'admin')) {
                                    if ($value['approve_norms'] == 'APPROVED') { ?>
                                        <a href="<?php echo Link::createAdmin_current(array('cmd' => 'approvedNorms', 'id' => $value['id'], 'approve_norms' => 'APPROVED_NO')); ?>" class="list-button text-norwap">{{.no_approve_norms.}}</a>
                                    <?php } else { ?>
                                        <a href="<?php echo Link::createAdmin_current(array('cmd' => 'approvedNorms', 'id' => $value['id'], 'approve_norms' => 'APPROVED')); ?>" class="list-button text-norwap">{{.approved_norms.}}</a>
                                <?php }
                                }?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
                <input type="hidden" name="rid" id="rid" value="1" />
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
    <div class="toolboxButton">
        <div class="header">
            <img src="<?php echo WEB_ROOT . 'public/icon/generic-48.png'; ?>" />
            <span>{{.list_product_outsourcing.}}</span>
        </div>
        <div class="toolbar-table">
            <table class="toolbar">
                <tbody>
                    <tr>
                        <?php if ($this->grant->check_privilege('MOD_ADMINOUTSOURCING', 'unlock')) { ?>
                            <td align="center">
                                <a href="javascript:void(0);" id="add_row" class="toolbar" title="{{.add_new.}}"><span class="icon-button Icon-32-Add" title="{{.add_new.}}"></span>{{.add_new.}}</a>
                            </td>
                            <td align="center">
                                <a href="javascript:void(0);" class="toolbar" title="{{.save.}}" onclick="action('frmAdminItemsList', 'saveProduct');"><span class="icon-button Icon-32-Save" title="{{.save.}}"></span>{{.save.}}</a>
                            </td>
                        <?php } ?>
                        <td align="center">
                            <a href="javascript:void(0);" onclick="goBack();" class="toolbar" title="{{.back.}}"><span class="icon-button Icon-32-Back" title="{{.back.}}"></span>{{.back.}}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>