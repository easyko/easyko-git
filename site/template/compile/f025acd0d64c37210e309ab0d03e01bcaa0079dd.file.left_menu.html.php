<?php /* Smarty version Smarty-3.1.12, created on 2019-05-04 12:39:44
         compiled from "/usr/local/web/test/pm/site/template/view/left_menu.html" */ ?>
<?php /*%%SmartyHeaderCode:19108238675ccd1790c9f948-41104656%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f025acd0d64c37210e309ab0d03e01bcaa0079dd' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/left_menu.html',
      1 => 1466236001,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19108238675ccd1790c9f948-41104656',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'menuName' => 0,
    'roleId' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5ccd1790ce4c98_62824107',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccd1790ce4c98_62824107')) {function content_5ccd1790ce4c98_62824107($_smarty_tpl) {?><li class="left-nav-item">
	<dl<?php if ($_smarty_tpl->tpl_vars['menuName']->value=='project'){?> class="current"<?php }?>>
		<dt><a href="project.php">项目查询</a></dt>
	</dl>
</li>
<?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='2')){?>
<li class="left-nav-item">
	<dl<?php if ($_smarty_tpl->tpl_vars['menuName']->value=='project_create'){?> class="current"<?php }?>>
		<dt><a href="project_create.php">创建项目</a></dt>
	</dl>
</li>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='0'){?>
<li class="left-nav-item">
	<dl<?php if ($_smarty_tpl->tpl_vars['menuName']->value=='member'){?> class="current"<?php }?>>
		<dt><a href="member.php">人员管理</a></dt>
	</dl>
</li>
<?php }?>
<?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='1')){?>
<li class="left-nav-item">
	<dl<?php if ($_smarty_tpl->tpl_vars['menuName']->value=='performance'){?> class="current"<?php }?>>
		<dt><a href="performance.php">绩效考核</a></dt>
	</dl>
</li>
<?php }?>
<?php if (($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='1'||$_smarty_tpl->tpl_vars['roleId']->value=='2')){?>
<li class="left-nav-item">
	<dl<?php if ($_smarty_tpl->tpl_vars['menuName']->value=='weekly_report'){?> class="current"<?php }?>>
		<dt><a href="weekly_report.php">工作周报</a></dt>
	</dl>
</li>
<li class="left-nav-item">
	<dl<?php if ($_smarty_tpl->tpl_vars['menuName']->value=='project_report'){?> class="current"<?php }?>>
		<dt><a href="project_report.php">项目总结</a></dt>
	</dl>
</li>
<?php }?><?php }} ?>