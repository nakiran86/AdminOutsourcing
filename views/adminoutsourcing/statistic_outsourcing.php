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
                                <td class="key">{{.col_date_import.}}</td>
                                <td>
                                    <select id="cbMonth" name="cbMonth">
                                        <?php for ($month = 1; $month <= 12; $month++) { ?>
                                            <option value="<?php echo $month; ?>">{{.month.}} <?php echo $month; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">$('select#cbMonth').val('<?php echo (Link::get('cbMonth') ? Link::get('cbMonth') : date('n')); ?>');</script>
                                    <select id="cbYear" name="cbYear">
                                        <?php for ($year = 2016; $year <= date('Y'); $year++) { ?>
                                            <option value="<?php echo $year; ?>">{{.year.}} <?php echo $year; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">$('select#cbYear').val('<?php echo (Link::get('cbYear') ? Link::get('cbYear') : date('Y')); ?>');</script>
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
                                <th class="col_60">{{.total.}}</th>
                                <th class="col_250">{{.orders_on_time.}}</th>
                                <th class="col_200">{{.orders_delayed.}}</th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php if ($this->itemsList) { ?>
                                <tr>
                                    <td align="right">
                                        <?php if ($this->itemsList['total']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $this->itemsList['outsource_id_list'] ))?>" target="_blank"><?php echo $this->itemsList['total']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($this->itemsList['order_on_time']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $this->itemsList['ontime_id_list'] ))?>" target="_blank"><?php echo $this->itemsList['order_on_time']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?>
                                    </td>
                                    <td align="right">
                                        <?php if ($this->itemsList['order_delayed']) { ?>
                                            <a href="<?php echo Link::createAdmin_current(array('outsource_number' => $this->itemsList['delay_id_list'] ))?>" target="_blank"><?php echo $this->itemsList['order_delayed']; ?></a>
                                        <?php } else {
                                            echo '0';
                                        } ?> <?php if ($this->itemsList['order_delayed']) { ?>(<?php echo $this->itemsList['day_delay']; ?> {{.day.}})<?php } ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
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