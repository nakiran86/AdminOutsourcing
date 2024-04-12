<script language="javascript">
    $(document).ready(function() {
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
        $('input#from_date_expired').datepicker({
            dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
            onClose: function(selectedDate) {
                $("#to_date_expired").datepicker("option", "minDate", selectedDate);
            }
        });
        $('input#to_date_expired').datepicker({
            dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
            onClose: function(selectedDate) {
                $("#from_date_expired").datepicker("option", "maxDate", selectedDate);
            }
        });
        $('input#from_input_date').datepicker({
            dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
            onClose: function(selectedDate) {
                $("#from_input_date").datepicker("option", "minDate", selectedDate);
            }
        });
        $('input#to_input_date').datepicker({
            dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true,
            onClose: function(selectedDate) {
                $("#to_input_date").datepicker("option", "maxDate", selectedDate);
            }
        });
        <?php if (Link::get('cbChooseDay') == 'day') { ?>
            $("#cbMonth").attr('disabled', 'disabled');
            $("#cbMonth").hide();
            $("#cbYear").attr('disabled', 'disabled');
            $("#cbYear").hide();
        <?php } else if (Link::get('cbChooseDay') == 'month') { ?>
                $("#from_date").attr('disabled', 'disabled');
                $("#from_date").hide();
                $("#to_date").attr('disabled', 'disabled');
                $("#to_date").hide();
                $("span.datelabel").hide();
        <?php } else { ?>
                // $("#cbMonth").attr('disabled', 'disabled');
                // $("#cbMonth").hide();
                // $("#cbYear").attr('disabled', 'disabled');
                // $("#cbYear").hide();
                $("#from_date").attr('disabled', 'disabled');
                $("#from_date").hide();
                $("#to_date").attr('disabled', 'disabled');
                $("#to_date").hide();
                $("span.datelabel").hide();
        <?php } ?>
        $('select#cbChooseDay').change(function() {
            if ($(this).val() == 'day') {
                $("#cbMonth").attr('disabled', 'disabled');
                $("#cbMonth").hide();
                $("#cbYear").attr('disabled', 'disabled');
                $("#cbYear").hide();
                $("#from_date").removeAttr('disabled');
                $("#from_date").show();
                $("#to_date").removeAttr('disabled');
                $("#to_date").show();
                $("span.datelabel").show();
            } else if ($(this).val() == 'month') {
                $("#from_date").attr('disabled', 'disabled');
                $("#from_date").hide();
                $("#to_date").attr('disabled', 'disabled');
                $("#to_date").hide();
                $("span.datelabel").hide();
                $("#cbMonth").removeAttr('disabled');
                $("#cbMonth").show();
                $("#cbYear").removeAttr('disabled');
                $("#cbYear").show();
            } else {
                $("#cbMonth").attr('disabled', 'disabled');
                $("#cbMonth").hide();
                $("#cbYear").attr('disabled', 'disabled');
                $("#cbYear").hide();
                $("#from_date").attr('disabled', 'disabled');
                $("#from_date").hide();
                $("#to_date").attr('disabled', 'disabled');
                $("#to_date").hide();
                $("span.datelabel").hide();
            }
        });
        <?php if (Link::get('cbDateExpired') == 'day') { ?>
            $("#cbMonthExpired").attr('disabled', 'disabled');
            $("#cbMonthExpired").hide();
            $("#cbYearExpired").attr('disabled', 'disabled');
            $("#cbYearExpired").hide();
        <?php } else if (Link::get('cbDateExpired') == 'month') { ?>
                $("#from_date_expired").attr('disabled', 'disabled');
                $("#from_date_expired").hide();
                $("#to_date_expired").attr('disabled', 'disabled');
                $("#to_date_expired").hide();
                $("span.datelabelexpired").hide();
        <?php } else { ?>
                $("#cbMonthExpired").attr('disabled', 'disabled');
                $("#cbMonthExpired").hide();
                $("#cbYearExpired").attr('disabled', 'disabled');
                $("#cbYearExpired").hide();
                $("#from_date_expired").attr('disabled', 'disabled');
                $("#from_date_expired").hide();
                $("#to_date_expired").attr('disabled', 'disabled');
                $("#to_date_expired").hide();
                $("span.datelabelexpired").hide();
        <?php } ?>
        $('select#cbDateExpired').change(function() {
            if ($(this).val() == 'day') {
                $("#cbMonthExpired").attr('disabled', 'disabled');
                $("#cbMonthExpired").hide();
                $("#cbYearExpired").attr('disabled', 'disabled');
                $("#cbYearExpired").hide();
                $("#from_date_expired").removeAttr('disabled');
                $("#from_date_expired").show();
                $("#to_date_expired").removeAttr('disabled');
                $("#to_date_expired").show();
                $("span.datelabelexpired").show();
            } else if ($(this).val() == 'month') {
                $("#from_date_expired").attr('disabled', 'disabled');
                $("#from_date_expired").hide();
                $("#to_date_expired").attr('disabled', 'disabled');
                $("#to_date_expired").hide();
                $("span.datelabelexpired").hide();
                $("#cbMonthExpired").removeAttr('disabled');
                $("#cbMonthExpired").show();
                $("#cbYearExpired").removeAttr('disabled');
                $("#cbYearExpired").show();
            } else {
                $("#cbMonthExpired").attr('disabled', 'disabled');
                $("#cbMonthExpired").hide();
                $("#cbYearExpired").attr('disabled', 'disabled');
                $("#cbYearExpired").hide();
                $("#from_date_expired").attr('disabled', 'disabled');
                $("#from_date_expired").hide();
                $("#to_date_expired").attr('disabled', 'disabled');
                $("#to_date_expired").hide();
                $("span.datelabelexpired").hide();
            }
        });
    });
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
                <li><a href="<?php echo $subMenu['url']; ?>" <?php echo (Link::getUrl() == urlencode($subMenu['url']) ? ' class="active"' : '') ?>><?php echo $subMenu['name']; ?></a></li>
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
                    <input name="page" type="hidden" id="page" value="<?php echo Link::get('page'); ?>" />
                    <input name="cmd" type="hidden" id="cmd" value="<?php echo Link::get('cmd'); ?>" />
                    <table cellspacing="1" cellpadding="4" class="admintable search" align="center">
                        <tbody>
                            <tr>
                                <td class="key">{{.col_date_require.}}</td>
                                <td>
                                    <select id="cbChooseDay" name="cbChooseDay">
                                        <option value="day">{{.by.}} {{.day.}}</option>
                                        <option value="month">{{.by.}} {{.month.}}</option>
                                    </select>
                                    <script type="text/javascript">$('select#cbChooseDay').val('<?php echo Link::get('cbChooseDay', 'month'); ?>');</script>
                                    <span class="datelabel">{{.from.}}</span> <input type="text" name="from_date" id="from_date" maxlength="10" class="dateTime" value="<?php echo Link::get('from_date'); ?>" autocomplete="off" />
                                    <span class="datelabel">{{.to.}}</span> <input type="text" name="to_date" id="to_date" maxlength="10" class="dateTime" value="<?php echo Link::get('to_date'); ?>" autocomplete="off" />
                                    <select id="cbMonth" name="cbMonth">
                                        <?php for ($month = 1; $month <= 12; $month++) { ?>
                                            <option value="<?php echo $month; ?>">{{.month.}} <?php echo $month; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">$('select#cbMonth').val('<?php echo (Link::get('cbMonth') ? Link::get('cbMonth') : date('n')); ?>');</script>
                                    <select id="cbYear" name="cbYear">
                                        <?php for ($year = 2020; $year <= date('Y'); $year++) { ?>
                                            <option value="<?php echo $year; ?>">{{.year.}} <?php echo $year; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">$('select#cbYear').val('<?php echo (Link::get('cbYear') ? Link::get('cbYear') : date('Y')); ?>');</script>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.expired_date.}}</td>
                                <td>
                                    <select id="cbDateExpired" name="cbDateExpired">
                                        <option value="">-</option>
                                        <option value="day">{{.by.}} {{.day.}}</option>
                                        <option value="month">{{.by.}} {{.month.}}</option>
                                    </select>
                                    <script type="text/javascript">$('select#cbDateExpired').val('<?php echo Link::get('cbDateExpired'); ?>');</script>
                                    <span class="datelabelexpired">{{.from.}}</span> <input type="text" name="from_date_expired" id="from_date_expired" maxlength="10" class="dateTime" value="<?php echo Link::get('from_date_expired'); ?>" autocomplete="off" />
                                    <span class="datelabelexpired">{{.to.}}</span> <input type="text" name="to_date_expired" id="to_date_expired" maxlength="10" class="dateTime" value="<?php echo Link::get('to_date_expired'); ?>" autocomplete="off" />
                                    <select id="cbMonthExpired" name="cbMonthExpired">
                                        <?php for ($month = 1; $month <= 12; $month++) { ?>
                                            <option value="<?php echo $month; ?>">{{.month.}} <?php echo $month; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">$('select#cbMonthExpired').val('<?php echo (Link::get('cbMonthExpired') ? Link::get('cbMonthExpired') : date('n')); ?>');</script>
                                    <select id="cbYearExpired" name="cbYearExpired">
                                        <?php for ($year = 2020; $year <= date('Y'); $year++) { ?>
                                            <option value="<?php echo $year; ?>">{{.year.}} <?php echo $year; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">$('select#cbYearExpired').val('<?php echo (Link::get('cbYearExpired') ? Link::get('cbYearExpired') : date('Y')); ?>');</script>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.order_status.}}</td>
                                <td>
                                    <select id="order_status" name="order_status">
                                        <option value="">-</option>
                                        <option value="SUPPER_URGENT">{{.supper_urgent.}}</option>
                                        <option value="URGENT">{{.urgent.}}</option>
                                        <option value="NORMAL">{{.normal.}}</option>
                                    </select>
                                    <script>$('select#order_status').val('<?php echo Link::get('order_status'); ?>');</script>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">{{.processing_user.}}</td>
                                <td>
                                    <select id="cbUser" name="cbUser">
                                        <option value="">-</option>
                                        <?php foreach ($this->userList as $key => $user) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $user['fullname']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script>$('select#cbUser').val('<?php echo Link::get('cbUser'); ?>');</script>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center"><input type="submit" name="btnSearch" id="btnSearch" value="{{.search.}}" /><input type="button" class="Button" id="btnRemoveFilter" value="{{.remove_filter.}}" name="btnRemoveFilter" onclick="javascript:window.location.href='<?php echo Link::createAdmin_current(array('cmd' => 'statisticOutsourcing')); ?>';" /></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="content-list">
                <form name="frmAdminItemsList" id="frmAdminItemsList" action="" method="post">
                    <input name="cmd" type="hidden" id="cmd" />
                    <table cellspacing="0" border="1" class="adminlist">
                        <thead>
                            <tr>
                                <th class="col_20">#</th>
                                <th class="col_80">{{.employee.}}</th>
                                <th class="col_60">{{.not_finished.}}</th>
                                <th class="col_60">{{.orders_on_time.}}</th>
                                <th class="col_60">{{.orders_delayed.}}</th>
                                <th class="col_60">{{.total.}}</th>
                                <th class="col_60">{{.total_days_delayed.}} ({{.day.}})</th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php if ($this->itemsList) {
                                $i = 1;
                                foreach ($this->itemsList as $key => $value) { ?>
                                <tr class="<?php echo ($i % 2 == 1 ? 'row0 ' : 'row1 '); ?>">
                                    <td align="right"><?php echo $i; ?></td>
                                    <td>
                                        <?php echo $value['fullname']; ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($value['no_finish']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $value['no_finish_id_list'])) ?>" target="_blank"><?php echo $value['no_finish']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($value['order_on_time']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $value['ontime_id_list'])) ?>" target="_blank"><?php echo $value['order_on_time']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($value['order_delayed']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $value['delay_id_list'])) ?>" target="_blank"><?php echo $value['order_delayed']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($value['total']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $value['outsource_id_list'])) ?>" target="_blank"><?php echo $value['total']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($value['order_delayed']) { ?><?php echo $value['day_delay']; ?><?php } ?>
                                    </td>
                                </tr>
                            <?php $i++;}
                            } else { ?>
                                <tr>
                                    <td colspan="11" align="center">{{.no_data.}}</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
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