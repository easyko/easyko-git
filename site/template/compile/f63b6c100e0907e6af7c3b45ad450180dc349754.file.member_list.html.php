<?php /* Smarty version Smarty-3.1.12, created on 2019-05-06 21:33:26
         compiled from "/usr/local/web/test/pm/site/template/view/member_list.html" */ ?>
<?php /*%%SmartyHeaderCode:16157220005cd037a6e5c886-44015376%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f63b6c100e0907e6af7c3b45ad450180dc349754' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/member_list.html',
      1 => 1464875407,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16157220005cd037a6e5c886-44015376',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'itemList0' => 0,
    'row' => 0,
    'itemList1' => 0,
    'itemList2' => 0,
    'itemList3' => 0,
    'roleList' => 0,
    'i' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5cd037a7008074_48547986',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd037a7008074_48547986')) {function content_5cd037a7008074_48547986($_smarty_tpl) {?><!DOCTYPE html>
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
                        <div class="main-business-bottom">

                            <div class="main-business-right">
                                <div class="people-main">
									<h1>最高权限</h1>
                                    <div class="pp-btn">
                                        <a href="javascript:void(0);" class="pp-add">新增人员</a>
                                        <a href="javascript:void(0);" class="pp-del">删除人员</a>
                                    </div>
                                    <div class="pp-con role_0">
										<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList0']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                        <span class="list role_0_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
 item_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
">
                                            <input type="checkbox" name="user_id" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
"/> <span class="pp-name"><a href="javascript:void(0);" data="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
" title="可点击人员后修改信息"><span class="name"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</span><br /><span class="login_name"><?php echo $_smarty_tpl->tpl_vars['row']->value['login_name'];?>
</span></a></span>
                                        </span>
                                        <?php } ?>
                                    </div>

                                    <h1>总监</h1>
                                    <div class="pp-con role_1">
										<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                        <span class="list role_1_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
 item_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
">
                                            <input type="checkbox" name="user_id" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
"/> <span class="pp-name"><a href="javascript:void(0);" data="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
" title="可点击人员后修改信息"><span class="name"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</span><br /><span class="login_name"><?php echo $_smarty_tpl->tpl_vars['row']->value['login_name'];?>
</span></a></span>
                                        </span>
                                        <?php } ?>
                                    </div>

                                    <h1>项目经理</h1>
                                    <div class="pp-con role_2">
										<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList2']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                        <span class="list role_2_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
 item_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
">
                                            <input type="checkbox" name="user_id" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
"/> <span class="pp-name"><a href="javascript:void(0);" data="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
" title="可点击人员后修改信息"><span class="name"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</span><br /><span class="login_name"><?php echo $_smarty_tpl->tpl_vars['row']->value['login_name'];?>
</span></a></span>
                                        </span>
                                        <?php } ?>
                                    </div>

                                    <h1>执行人员</h1>
                                    <div class="pp-con role_3">
                                        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList3']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                        <span class="list role_3_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
 item_<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
">
                                            <input type="checkbox" name="user_id" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
"/> <span class="pp-name"><a href="javascript:void(0);" data="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
" title="可点击人员后修改信息"><span class="name"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</span><br /><span class="login_name"><?php echo $_smarty_tpl->tpl_vars['row']->value['login_name'];?>
</span></a></span>
                                        </span>
                                        <?php } ?>
                                    </div>
                                </div>
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
<div id="popdiv" class="pop">
    <span class="pp-title">提示</span>
    <a href="javascript:BOX_remove('popdiv')" class="close"></a>
    <table align="center">
        <tr>
            <td width="80"></td>
            <td class="poptext"><span class=""></span></td>
            <td width="80"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <a class="btn-add" href="javascript:BOX_remove('popdiv')">确 定</a>
            </td>
            <td align="left"></td>
        </tr>
    </table>
</div>

<div id="tcdiv" class="pop">
    <span class="pp-title">添加人员</span>
    <a href="javascript:BOX_remove('tcdiv')" class="close"></a>
    <table align="center">
        <tr>
            <td align="right" width="30%"> <span class="dd">*</span> 员工姓名：</td>
            <td width="40%"> <input type="text" id="add_name" name="add_name" placeholder="请输入员工姓名" /> </td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td align="right"><span class="dd">*</span> 登录名：</td>
            <td><input type="text" id="add_login" name="add_login" placeholder="请输入登录系统名" /></td>
            <td></td>
        </tr>
        <tr>
            <td align="right"><span class="dd">*</span> 密码：</td>
            <td><input type="text" id="add_passwd" name="add_passwd" placeholder="请输入六位数字密码" maxlength="6"/></td>
            <td align="left"> <span class="pw newpass"> 随机生成</span></td>
        </tr>
        <tr>
            <td align="right"><span class="dd">*</span> 职位：</td>
            <td>
                <select class="form-unit interval-select" style="width:163px; padding:5px;" name="add_role_id" id="add_role_id">
					<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['roleList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
</option>
                    <?php } ?>
                </select>
            </td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="right"> 邮箱：</td>
            <td><input type="text" id="add_email" name="add_email" placeholder="格式：example@xxx.com" /></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td><input type="checkbox" name="add_sendmail" id="add_sendmail"/> <span class="info">将用户名及密码发送至该邮箱</span></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td><a class="btn-add addInfo" href="javascript:void(0);">添 加</a>
                <span class="err">密码格式有误</span>
            </td>
            <td align="left"></td>
        </tr>
    </table>
</div>


<div id="chdiv" class="pop">
    <span class="pp-title">修改人员信息</span>
    <a href="javascript:BOX_remove('chdiv')" class="close"></a>
    <table align="center">
        <tr>
            <td align="right" width="30%"><span class="dd">*</span> 员工姓名：</td>
            <td width="40%"> <input type="text" id="edit_name" name="edit_name" value="" placeholder="请输入员工姓名" /> </td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td align="right"><span class="dd">*</span> 登录名：</td>
            <td><input type="text" id="edit_login" name="edit_login" value="" placeholder="请输入登录系统名"/></td>
            <td></td>
        </tr>
        <tr>
            <td align="right"><span class="dd">*</span> 密码：</td>
            <td><input type="text" id="edit_passwd" name="edit_passwd" value="" maxlength="6"/></td>
            <td align="left"> <span class="pw"> 随机生成</span></td>
        </tr>
        <tr>
            <td align="right"><span class="dd">*</span> 职位：</td>
            <td>
                <select class="form-unit interval-select" style="width:163px; padding:5px;" name="edit_role_id" id="edit_role_id">

                </select>
                <input type="hidden" name="edit_old_role_id" id="edit_old_role_id" value="" />
            </td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="right"> 邮箱：</td>
            <td><input type="text" id="edit_email" name="edit_email" value="" /></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td><input type="checkbox" id="sendmail" name="sendmail" /> <span class="info">将用户名及密码发送至该邮箱</span></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td><a class="btn-add editInfo" href="javascript:void(0);">确认修改</a>
                <span class="err">密码格式有误</span>
            </td>
            <td align="left"></td>
        </tr>
    </table>
</div>

<script src="/js/vendor/jquery-1.8.2.min.js"></script>
<script src="/js/plugins.min.js"></script>
<script src="/js/matrix.min.js"></script>
<script src="/js/all.js"></script>
<script src="/js/main.js"></script>
<script>
    $(function () {
		$('input[name="user_id"]').attr('checked', false);

        $('.pp-add').click(function(){
            BOX_show('tcdiv');
        });
        $('.list a').live('click', function(){
            BOX_show('chdiv');
            var user_id = $(this).attr('data');

			$.post('?task=getinfo', {
					user_id:user_id,
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){

				if (data.msg) {
					alert(data.msg);
					window.location.href = data.redirect;
					return;
				}

				$('#edit_name').val(data.userInfo.username);
				$('#edit_login').val(data.userInfo.login_name);
				$('#edit_passwd').val(data.userInfo.password);
				$('#edit_email').val(data.userInfo.email);

				var managerHtml = '';
				for (i in data.roleList) {
					if (i == data.userInfo.role_id) {
						managerHtml +='<option value="' + i + '" selected>' + data.roleList[i] + '</option>';
					} else {
						managerHtml +='<option value="' + i + '">' + data.roleList[i] + '</option>';
					}
				}
				$('#edit_role_id').html(managerHtml);
				$('.editInfo').attr('user_id', user_id);
				$('#edit_old_role_id').val(data.userInfo.role_id);
			}, 'json');
        });

        $('.pw').click(function(){
			$('#edit_passwd').val(Math.random()*900000|100000);
		});

		$('.newpass').click(function(){
			$('#add_passwd').val(Math.random()*900000|100000);
		});

		$('.editInfo').click(function(){
			if ($(this).html() == '修改中...') {
				return;
			}
			if ($('#edit_name').val() == '') {
				alert('请填写员工姓名');
				$('#edit_name').focus();
				return;
			}

			if ($('#edit_login').val() == '') {
				alert('请填写登录名');
				$('#edit_login').focus();
				return;
			}

			/*if ($('#edit_email').val() == '') {
				alert('请填写邮箱');
				$('#edit_email').focus();
				return;
			}*/

			var isSendMail = $('#sendmail').is(':checked') ? 1 : 0;

			$(this).html('修改中...');

			$.post('?task=editinfo', {
					type:'edit',
					user_id:$('.editInfo').attr('user_id'),
					edit_name:$('#edit_name').val(),
					edit_login:$('#edit_login').val(),
					edit_passwd:$('#edit_passwd').val(),
					edit_role_id:$('#edit_role_id').val(),
					edit_email:$('#edit_email').val(),
					send_mail:isSendMail,
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){

				$('.editInfo').html('确认修改');
				alert(data.msg);
				if (data.key) $('#' + data.key).focus();
				if (data.redirect) window.location.href = data.redirect;

				if (data.status == 'SUCCESS') {
					BOX_remove('chdiv');
					if ($('#edit_old_role_id').val() == $('#edit_role_id').val()) {
						$('.role_' + $('#edit_role_id').val() + '_' + $('.editInfo').attr('user_id')).find('.name').html($('#edit_name').val());
						$('.role_' + $('#edit_role_id').val() + '_' + $('.editInfo').attr('user_id')).find('.login_name').html($('#edit_login').val());
					} else {
						$('.role_' + $('#edit_old_role_id').val() + '_' + $('.editInfo').attr('user_id')).remove();

						$('.role_' + $('#edit_role_id').val()).append('<span class="list role_' + $('#edit_role_id').val() + '_' + $('.editInfo').attr('user_id') + ' item_' + $('.editInfo').attr('user_id') + '"><input type="checkbox" name="user_id" value="' + $('.editInfo').attr('user_id') + '"> <span class="pp-name"><a title="可点击人员后修改信息" data="' + $('.editInfo').attr('user_id') + '" href="javascript:void(0);"><span class="name">' + $('#edit_name').val() + '</span><br><span class="login_name">' + $('#edit_login').val() + '</span></a></span></span>');
					}
					emptyInput('edit');
				}
			}, 'json');
		});

		$('.addInfo').click(function(){
			if ($(this).html() == '添加中...') {
				return;
			}
			if ($('#add_name').val() == '') {
				alert('请填写员工姓名');
				$('#add_name').focus();
				return;
			}

			if ($('#add_login').val() == '') {
				alert('请填写登录名');
				$('#add_login').focus();
				return;
			}

			/*if ($('#add_email').val() == '') {
				alert('请填写邮箱');
				$('#add_email').focus();
				return;
			}*/

			var isSendMail = $('#add_sendmail').is(':checked') ? 1 : 0;

			$(this).html('添加中...');

			$.post('?task=editinfo', {
					type:'add',
					edit_name:$('#add_name').val(),
					edit_login:$('#add_login').val(),
					edit_passwd:$('#add_passwd').val(),
					edit_role_id:$('#add_role_id').val(),
					edit_email:$('#add_email').val(),
					send_mail:isSendMail,
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){

				$('.addInfo').html('添 加');
				alert(data.msg);
				if (data.key) $('#' + data.key).focus();
				if (data.redirect) window.location.href = data.redirect;

				if (data.status == 'SUCCESS') {
					BOX_remove('tcdiv');
					$('.role_' + $('#add_role_id').val()).append('<span class="list role_' + $('#add_role_id').val() + '_' + data.user_id + ' item_' + data.user_id + '"><input type="checkbox" name="user_id" value="' + data.user_id + '"> <span class="pp-name"><a title="可点击人员后修改信息" data="' + data.user_id + '" href="javascript:void(0);"><span class="name">' + $('#add_name').val() + '</span><br><span class="login_name">' + $('#add_login').val() + '</span></a></span></span>');
					emptyInput('add');
				}
			}, 'json');
		});

		$('.pp-del').click(function(){
			var user_ids = getDelUserId();
			if (user_ids == '') {
				showInfo("error","请勾选删除人员！");
				return;
			}

            showInfo("error","您确定要删除所选人员吗？",function(){
                $.post('?task=deluser', {
                    user_ids:user_ids,
                    formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
                }, function(data){
                    BOX_remove("popdiv");
                    alert(data.msg);
                    if (data.status == 'SUCCESS') {
                        for(var i = 0; i < user_ids.length; i++) {
                            $('.item_' + user_ids[i]).remove();
                        }
                    }
                }, 'json');
            });
		});
    });

    function emptyInput(type)
    {
		$('#' + type + '_name').val('');
		$('#' + type + '_login').val('');
		$('#' + type + '_passwd').val('');
		$('#' + type + '_email').val('');
	}

	function getDelUserId()
	{
		var user_ids = [];
		$("input[name='user_id']:checkbox").each(function(){
			if ($(this).is(':checked')) {
				user_ids.push($(this).attr('value'));
			}
		});

		return user_ids;
	}

</script>
</body>
</html><?php }} ?>