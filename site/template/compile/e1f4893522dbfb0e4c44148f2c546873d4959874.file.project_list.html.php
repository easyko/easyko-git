<?php /* Smarty version Smarty-3.1.12, created on 2019-05-04 12:48:41
         compiled from "/usr/local/web/test/pm/site/template/view/project_list.html" */ ?>
<?php /*%%SmartyHeaderCode:18954909505ccd19a9723332-23429512%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e1f4893522dbfb0e4c44148f2c546873d4959874' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/project_list.html',
      1 => 1466954030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18954909505ccd19a9723332-23429512',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'type' => 0,
    'name' => 0,
    'projectName' => 0,
    'managerList' => 0,
    'member' => 0,
    'managerId' => 0,
    'userList' => 0,
    'user' => 0,
    'userId' => 0,
    'projectStat' => 0,
    's' => 0,
    'statusId' => 0,
    'stat' => 0,
    'roleId' => 0,
    'itemList' => 0,
    'row' => 0,
    'pageList' => 0,
    'url' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5ccd19a98bc502_86782691',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccd19a98bc502_86782691')) {function content_5ccd19a98bc502_86782691($_smarty_tpl) {?><!DOCTYPE html>
<html class="has-sidebar">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css"/>
    <link rel="stylesheet" href="/css/datepicker.css"/>
    <link rel="stylesheet" href="/css/all.css"/>
</head>
<body>
<div class="wrapper">
    <div class="min-width-out">
        <div class="min-width-in">
            <div class="min-width">
                <div class="header">
                    <div class="content">
                        <img src="/img/logo.png" alt=""/>
                        <a href="/?task=logout" class="out">退出</a>
                        <dl class="header-infos">
                            <span class="search-main vague" <?php if ($_smarty_tpl->tpl_vars['type']->value=='2'){?>style="display:none;"<?php }?>>
								<input type="text" name="name" id="name" value="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
" placeholder="请输入需要查找的关键词"/>
							</span>
                            <span class="detailed" <?php if ($_smarty_tpl->tpl_vars['type']->value=='1'){?>style="display:none;"<?php }?>>
                                项目名称：
                                <input class="form-unit w-95" type="text" style="padding:9px;" placeholder="请输入项目名称" name="project_name" id="project_name" value="<?php echo $_smarty_tpl->tpl_vars['projectName']->value;?>
">&nbsp;&nbsp;
                                项目经理：
                                <select class="form-unit w-95 interval-select" name="manager_id" id="manager_id">
									<option value="">请选择</option>
									<?php  $_smarty_tpl->tpl_vars['member'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['member']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['managerList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['member']->key => $_smarty_tpl->tpl_vars['member']->value){
$_smarty_tpl->tpl_vars['member']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['member']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['member']->value['user_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['member']->value['user_id']==$_smarty_tpl->tpl_vars['managerId']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['member']->value['username'];?>
</option>
                                    <?php } ?>
                                </select>&nbsp;&nbsp;
                                执行人员：
                                <select class="form-unit w-95 interval-select" name="user_id" id="user_id">
                                    <option value="">请选择</option>
                                    <?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['userList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['user']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['user']->value['user_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['user']->value['user_id']==$_smarty_tpl->tpl_vars['userId']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</option>
                                    <?php } ?>
                                </select>&nbsp;&nbsp;
                                项目状态：
                                <select class="form-unit w-95 interval-select" name="status_id" id="status_id">
                                    <option value="">请选择</option>
                                    <?php  $_smarty_tpl->tpl_vars['stat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['stat']->_loop = false;
 $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['projectStat']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['stat']->key => $_smarty_tpl->tpl_vars['stat']->value){
$_smarty_tpl->tpl_vars['stat']->_loop = true;
 $_smarty_tpl->tpl_vars['s']->value = $_smarty_tpl->tpl_vars['stat']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['s']->value==$_smarty_tpl->tpl_vars['statusId']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['stat']->value['statusName'];?>
</option>
                                    <?php } ?>
                                </select>
                            </span>
                            <span>
                                <select class="form-unit w-95 interval-select" name="search_type" id="search_type">
                                    <option value="1" <?php if ($_smarty_tpl->tpl_vars['type']->value=='1'){?>selected<?php }?>>模糊查询</option>
                                    <option value="2" <?php if ($_smarty_tpl->tpl_vars['type']->value=='2'){?>selected<?php }?>>精确查询</option>
                                </select>
                            </span>
                            <input type="hidden" name="type" id="type" value="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
">
                            <a href="javascript:void(0);" class="search-btn">查询</a>
                        </dl>
                    </div>
                </div>

                <div class="main">
                    <div class="sidebar-menu">
                        <div class="left-menu">
                            <ul class="left-nav">
                                <?php echo $_smarty_tpl->getSubTemplate ("left_menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                            </ul>
                        </div>
                    </div>
                    <div class="content">
                        <div class="main-content-bottom">
                            <div class="table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>项目编号</th>
                                        <th>项目名称</th>
                                        <th>客户名称</th>
                                        <th>开始日期</th>
                                        <?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='1')){?><th>项目经理</th><?php }?>
                                        <th>执行人员</th>
                                        <th>项目状态</th>
										<?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='2')){?>
                                        <th>合同号</th>
                                        <th>合同金额</th>
                                        <th>实际收款</th>
                                        <th>第三方费用</th>
                                        <?php }?>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php if (!empty($_smarty_tpl->tpl_vars['itemList']->value)){?>
									<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                    <tr>
                                        <td style="width:120px;"><a href="?task=detail&product_no=<?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
" style="color: #60a6ee" target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
</a></td>
                                        <td style="white-space:pre-line; <?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='2')){?>width:200px;<?php }else{ ?>width:400px;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['row']->value['project_name'];?>
</td>
                                        <td style="white-space:pre-line; <?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='2')){?>width:84px;<?php }else{ ?>width:120px;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['row']->value['customer_name'];?>
</td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['start_date'];?>
</td>
                                        <?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='1')){?><td><?php echo $_smarty_tpl->tpl_vars['row']->value['manager_name'];?>
</td><?php }?>
                                        <td style="white-space:pre-line; min-width:60px; max-width:80px;"><?php echo $_smarty_tpl->tpl_vars['row']->value['exce_users'];?>
</td>
                                        <td class="<?php echo $_smarty_tpl->tpl_vars['projectStat']->value[$_smarty_tpl->tpl_vars['row']->value['status']]['colorClass'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['stat'];?>
</td>
                                        <?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='2')){?>
                                        <td style="white-space:pre-line;"><?php echo $_smarty_tpl->tpl_vars['row']->value['contract_no'];?>
</td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['contract_amount'];?>
</td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['real_amount'];?>
</td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['other_amount'];?>
</td>
                                        <?php }?>
                                    </tr>
                                    <?php } ?>
                                    <?php }else{ ?>
                                    <tr>
										<td colspan="<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='0'){?>11<?php }elseif($_smarty_tpl->tpl_vars['roleId']->value=='1'){?>7<?php }elseif($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>10<?php }?>">暂无记录</td>
									</tr>
                                    <?php }?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='0'){?>11<?php }elseif($_smarty_tpl->tpl_vars['roleId']->value=='1'){?>7<?php }elseif($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>10<?php }?>">
                                            <table>
                                                <tfoot>
                                                <tr>
                                                    <td width="130">
                                                        <a href="javascript:;" class="btn-primary btn export" style="border: 1px solid #8c8b8b"> <span> 下载Excel格式</span> </a>

                                                    </td>
                                                    <td>
                                                        <?php if ($_smarty_tpl->tpl_vars['pageList']->value!=null){?>
                                                        <p class="page">
                                                            <?php if ($_smarty_tpl->tpl_vars['pageList']->value->current>1){?><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&p=<?php echo $_smarty_tpl->tpl_vars['pageList']->value->previous;?>
">上一页</a><?php }?>

                                                            <?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pageList']->value->pagesInRange; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
$_smarty_tpl->tpl_vars['page']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['page']->key;
?>
                                                            <?php if ($_smarty_tpl->tpl_vars['pageList']->value->current==$_smarty_tpl->tpl_vars['page']->value){?>
                                                            <a href="javascript:void(0);" class="current"><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</a>
                                                            <?php }else{ ?>
                                                            <a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&p=<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</a>
                                                            <?php }?>
                                                            <?php } ?>
                                                            <?php if ($_smarty_tpl->tpl_vars['pageList']->value->current<$_smarty_tpl->tpl_vars['pageList']->value->last){?><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&p=<?php echo $_smarty_tpl->tpl_vars['pageList']->value->next;?>
">下一页</a><?php }?>
                                                        </p>
                                                        <?php }?>

                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="push"></div>
</div>
<!--悬浮层-->
<div id="tcdiv" class="pop">
    <div class="main pd">
        <p class="txt warning">确认新增商户吗？</p>
    </div>
    <div class="btns">
        <a href="javascript:BOX_remove('tcdiv')" class="btn1 confirm">确定</a>
        <a href="javascript:BOX_remove('tcdiv')" class="btn1 confirm">取消</a>
    </div>
    <a href="javascript:BOX_remove('tcdiv');" class="close"></a>
</div>

<script src="/js/vendor/jquery-1.8.2.min.js"></script>
<script src="/js/plugins.min.js"></script>
<script src="/js/matrix.min.js"></script>
<script src="/js/all.js"></script>
<script src="/js/main.js"></script>
<script>
    $(function() {
		$("#name, #project_name").keyup(function(event){
			e = event ? event : (window.event ? window.event : null);
			if (e.keyCode == 13) {
				$('.search-btn').click();
			}
		});

		var url = 'project.php';

		$('.search-btn, .export').click(function(){
			if ($('#type').val() == '1') {
				var search_url = url;
				search_url += '?type=1';
				if ($('#name').val() != '') {
					search_url += '&name=' + $('#name').val();
				}
			} else {
				var search_url = url;
				search_url += '?type=2';
				if ($('#project_name').val() != '') {
					search_url += '&project_name=' + $('#project_name').val();
				}
				if ($('#manager_id').val() != '') {
					search_url += '&manager_id=' + $('#manager_id').val();
				}
				if ($('#user_id').val() != '') {
					search_url += '&user_id=' + $('#user_id').val();
				}
				if ($('#status_id').val() != '') {
					search_url += '&status_id=' + $('#status_id').val();
				}
			}

			if ($(this).hasClass('export')) {
				search_url += '&export=1';
			}

			window.location.href = search_url;
		});

        $("select[id='search_type']").change(function(){
            if($(this).val()=="1"){
				$('#type').val('1');
                $(".vague").fadeIn(500);
                $(".detailed").hide();
            }else{
				$('#type').val('2');
                $(".detailed").fadeIn(500);
                $(".vague").hide();
            }
        })
    });
</script>
</body>
</html><?php }} ?>