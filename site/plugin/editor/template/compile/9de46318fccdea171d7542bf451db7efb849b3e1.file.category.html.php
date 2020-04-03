<?php /* Smarty version Smarty-3.1.12, created on 2012-12-07 13:08:51
         compiled from "/home/1001g/1001g_com/wwwroot/site/plugin/editor/template/view/category.html" */ ?>
<?php /*%%SmartyHeaderCode:154230682950c072d31c4294-02982775%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9de46318fccdea171d7542bf451db7efb849b3e1' => 
    array (
      0 => '/home/1001g/1001g_com/wwwroot/site/plugin/editor/template/view/category.html',
      1 => 1354856910,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '154230682950c072d31c4294-02982775',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_50c072d329c626_72312849',
  'variables' => 
  array (
    'itemList' => 0,
    'i' => 0,
    'catrow' => 0,
    'pageList' => 0,
    'baseurl' => 0,
    'page' => 0,
    'category' => 0,
    'forward' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50c072d329c626_72312849')) {function content_50c072d329c626_72312849($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
</title>
<link href="css/layout.css" type="text/css" rel="stylesheet">
</head>
<body>

<center>
	<div class="main">
		<div class="inter">
			<div class="inter1">
				<div class="title1 center"><B>类别管理</B></div>
				<table id="mytable" cellspacing="0" width="100%">
				  <tr>
					<th>文件夹名称</th>
					<th>管理</th> 
				  </tr>
				  <?php  $_smarty_tpl->tpl_vars['catrow'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['catrow']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['catrow']->key => $_smarty_tpl->tpl_vars['catrow']->value){
$_smarty_tpl->tpl_vars['catrow']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['catrow']->key;
?> 
				  <tr <?php if ($_smarty_tpl->tpl_vars['i']->value%2==0){?>class="ground2"<?php }?>> 
					<td><a href="image_list.php?cid=<?php echo $_smarty_tpl->tpl_vars['catrow']->value['editor_image_category_id'];?>
"><span id="item<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" style="color:#000"><?php echo $_smarty_tpl->tpl_vars['catrow']->value['name'];?>
</span></a></td> 
					<td>
						<A HREF="javascript:setmodify(<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['catrow']->value['editor_image_category_id'];?>
)"><IMG SRC="images/icon_edit.gif" WIDTH="19" HEIGHT="18" BORDER="0" ALT="编辑" align="absmiddle"></A> <A HREF="javascript:if(confirm('确实要删除吗？')){window.location='category.php?task=delete&cid=<?php echo $_smarty_tpl->tpl_vars['catrow']->value['editor_image_category_id'];?>
';}"><IMG SRC="images/icon_delete.gif" WIDTH="19" HEIGHT="18" BORDER="0" ALT="删除" align="absmiddle"></A></div>
					</td> 
				  </tr> 
				  <?php } ?>
				</table>
			</div>		
		</div>
	</div>
</center>
<center>
	<div class="title2">
	
	<?php if ($_smarty_tpl->tpl_vars['pageList']->value!=null){?>
		<?php if ($_smarty_tpl->tpl_vars['pageList']->value->firstPageInRange>1){?><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['pageList']->value->first;?>
" class="but">第一页</a> <?php }?>
		<?php if ($_smarty_tpl->tpl_vars['pageList']->value->firstPageInRange>1){?><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['pageList']->value->previous;?>
" class="but">上一页</a> <?php }?>
		<?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pageList']->value->pagesInRange; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
$_smarty_tpl->tpl_vars['page']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['page']->key;
?>
			<?php if ($_smarty_tpl->tpl_vars['pageList']->value->current==$_smarty_tpl->tpl_vars['page']->value){?><span><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</span><?php }else{ ?><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</a> <?php }?>
		<?php } ?>
		<?php if ($_smarty_tpl->tpl_vars['pageList']->value->current<$_smarty_tpl->tpl_vars['pageList']->value->last){?><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['pageList']->value->next;?>
" class="but">下一页</a> <?php }?>
		<?php if ($_smarty_tpl->tpl_vars['pageList']->value->current<$_smarty_tpl->tpl_vars['pageList']->value->last){?><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['pageList']->value->last;?>
" class="but">最后页</a><?php }?>
	<?php }?>
	
	</div>
</center>

<center>
	<div class="main">
		<div class="inter">
			<div class="inter1">
				<form action="category.php" name="form1" method="post" enctype="multipart/form-data">
					<div class="title11 center">
					<B id="editFolderTitle">添加文件夹</B>
					</div>
					<div class="title11 center" style="text-align:left;height:73px;">
						文件夹名称：<INPUT TYPE="text" NAME="category" VALUE="<?php echo $_smarty_tpl->tpl_vars['category']->value;?>
" size="30"> &nbsp;
						<INPUT id="post" TYPE="submit" value=" 添加 " />
					</div>
					<INPUT TYPE="hidden" NAME="task" VALUE="insert" />
					<INPUT TYPE="hidden" NAME="forward" VALUE="<?php echo $_smarty_tpl->tpl_vars['forward']->value;?>
" />
					<INPUT TYPE="hidden" NAME="cid" VALUE="" />
				</form>
			</div>
		</div>
	</div>
</center>
</body>
<script>
	
	//
	function setmodify(id,cid){

		document.form1.task.value = "update";
		document.form1.category.value = document.getElementById("item"+id).innerHTML;
		document.form1.cid.value = cid;
		document.getElementById("post").value = " 修改 ";
		document.getElementById("editFolderTitle").innerHTML = "修改文件夹";
	}
	
	function refreshpage(){
		window.location.reload();
	}
	//
</script>
</html><?php }} ?>