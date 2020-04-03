<?php /* Smarty version Smarty-3.1.12, created on 2012-12-07 13:08:53
         compiled from "/home/1001g/1001g_com/wwwroot/site/plugin/editor/template/view/images_list.html" */ ?>
<?php /*%%SmartyHeaderCode:55834931550c075d5390ce8-63002187%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c8c6d71e25cae09521cd98300730057c2912c57' => 
    array (
      0 => '/home/1001g/1001g_com/wwwroot/site/plugin/editor/template/view/images_list.html',
      1 => 1354856911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '55834931550c075d5390ce8-63002187',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_50c075d5541f38_85504018',
  'variables' => 
  array (
    'updateOpener' => 0,
    'itemList' => 0,
    'path' => 0,
    'imgrow' => 0,
    'forward' => 0,
    'pageList' => 0,
    'baseurl' => 0,
    'page' => 0,
    'cid' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50c075d5541f38_85504018')) {function content_50c075d5541f38_85504018($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
</title>
<link href="css/layout.css" type="text/css" rel="stylesheet">
<style>
/**/
td div{float:left}
/**/
</style>
<script>
//
function preloader() {

	 var imgadd = new Array();
     imgadd[0] = new Image(); 
     imgadd[0].src = "images/img_add_over.gif";
	 imgadd[1] = new Image(); 
     imgadd[1].src = "images/img_remove_over.gif";

}

function ProtectPath(path)
{
	path = path.replace( /\\/g, '\\\\') ;
	path = path.replace( /'/g, '\\\'') ;
	return path ;
}

function OpenFile( fileUrl,describe )
{
	var html = '<img src="' + fileUrl + '" alt="'+describe+'" border="0" />';
	var dialog = window.parent.CKEDITOR.dialog.getCurrent();
	var editor = dialog.getParentEditor();
	editor.insertHtml(html);
	dialog.hide();
	parent.focus();
}
//
<?php echo $_smarty_tpl->tpl_vars['updateOpener']->value;?>

</script>
</head>
<body onload="preloader()">

<center>
<div class="main">
	<div class="inter">
		<div class="inter1">
			<div class="title1 center"><B>图片列表</B></div>
			<table id="mytable" cellspacing="0" width="100%">
			  <td>
			  <?php  $_smarty_tpl->tpl_vars['imgrow'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['imgrow']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['imgrow']->key => $_smarty_tpl->tpl_vars['imgrow']->value){
$_smarty_tpl->tpl_vars['imgrow']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['imgrow']->key;
?>
				<div>
					<A href='javascript:void()' onclick='OpenFile("<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
<?php echo $_smarty_tpl->tpl_vars['imgrow']->value['path'];?>
","<?php echo $_smarty_tpl->tpl_vars['imgrow']->value['describe'];?>
");return false;'><IMG SRC="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
<?php echo $_smarty_tpl->tpl_vars['imgrow']->value['path'];?>
" WIDTH="45" HEIGHT="45" BORDER="0" ALT=""></A> 
					<A HREF="javascript:if(confirm('确实要删除吗？')){window.location='?task=delete&iid=<?php echo $_smarty_tpl->tpl_vars['imgrow']->value['editor_image_list_id'];?>
&forward=<?php echo $_smarty_tpl->tpl_vars['forward']->value;?>
?cid=<?php echo $_smarty_tpl->tpl_vars['imgrow']->value['image_category_id'];?>
';}"><IMG SRC="images/icon_delete.gif" WIDTH="19" HEIGHT="18" BORDER="0" ALT=""></A> 
				</div>
			  <?php } ?>
			  </td>
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

<form name="form1" action="image_list.php" method="post" enctype="multipart/form-data">
<center>
<div class="main">
	<div class="inter">
		<div class="inter1">
			<div class="title1 center"><B>添加图片</B></div>
			<div id="img_add" valign="middle" align="left"><img src="images/img_add.gif" align="absmiddle" border="0" onclick="add()" onmouseover="this.src='images/img_add_over.gif'" onmouseout="this.src='images/img_add.gif'" ALT="添加图片"> <img src="images/img_remove.gif" align="absmiddle" border="0" onclick="del()" onmouseover="this.src='images/img_remove_over.gif'" onmouseout="this.src='images/img_remove.gif'" ALT="删除图片"></div>
			<div id="addimg" align="left">
				<div id="imgdiv0">
					<INPUT TYPE="file" NAME="pictures[]" size="40"><br />
					图片描述：<br />
					<INPUT TYPE="text" NAME="describe" size="40">
				</div>
			</div>
			<div class="submit" align="left">
				<INPUT TYPE="hidden" name="cid" value="<?php echo $_smarty_tpl->tpl_vars['cid']->value;?>
" />
				<INPUT TYPE="hidden" name="task" value="insert" />
				<INPUT TYPE="hidden" name="forward" value="image_list.php?cid=<?php echo $_smarty_tpl->tpl_vars['cid']->value;?>
" />
				<INPUT TYPE="button" value="上传" onclick="javasscript:addinput();" />
				<input  type="button" value="返回" onclick="javascript:window.location='image_list.php?cid=<?php echo $_smarty_tpl->tpl_vars['cid']->value;?>
';" style="margin-left: 15px;">
			</div>
		</div>
	</div>
</div>
</center>
</form>
</body>
<script>
//
	 var parts = location.search.replace(/^\?/, "").split("&");
	 for( var i = 0; i < parts.length; i++) {
		var info = parts[i].split("=");
		window[info[0]] = info[1];
	 }
	 
	 //add image
	 var dhtml = document.getElementById("imgdiv0").innerHTML;
	 var doc = document.getElementById("addimg");
	 var nu = 0;
	
	 function add(){
		nu++;
		var newNode = document.createElement("div");
		newNode.setAttribute("id","imgdiv"+nu);
		doc.appendChild(newNode);
		//document.getElementById("imgdiv"+nu).innerHTML = dhtml.replace(/(0)/gi,nu);
		document.getElementById("imgdiv"+nu).innerHTML = dhtml;
	 }

	 function addinput(){
		 document.form1.submit();	 
	 }

	 function del(){
		if(nu>0){
			doc.removeChild(document.getElementById("imgdiv"+nu));
		}
		nu--;
		if(nu<1){nu=0;}
	 }
//
</script>
</html><?php }} ?>