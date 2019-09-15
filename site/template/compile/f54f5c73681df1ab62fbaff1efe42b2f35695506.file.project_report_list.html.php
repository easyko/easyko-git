<?php /* Smarty version Smarty-3.1.12, created on 2019-05-06 21:33:28
         compiled from "/usr/local/web/test/pm/site/template/view/project_report_list.html" */ ?>
<?php /*%%SmartyHeaderCode:2016688095cd037a8cc9b84-86446733%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f54f5c73681df1ab62fbaff1efe42b2f35695506' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/project_report_list.html',
      1 => 1466349813,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2016688095cd037a8cc9b84-86446733',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'roleId' => 0,
    'managerList' => 0,
    'row' => 0,
    'params' => 0,
    'itemList' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5cd037a8d7df95_03790424',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd037a8d7df95_03790424')) {function content_5cd037a8d7df95_03790424($_smarty_tpl) {?><!DOCTYPE html>
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
    <link rel="stylesheet" href="/css/fineuploader-new.css"/>
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
                        <div class="main-content-bottom">
							<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>
                            <div style="display: inline-block; vertical-align: top; width: 700px">
                                <div class="btnUpload"></div>
                            </div>
                            <?php }else{ ?>
							<form method="get" action="" name="search_form" id="search_form">
                            <div class="achievements">
                                姓名：
                                <select name="user_id" id="user_id" >
                                    <option value="">请选择项目经理</option>
                                    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['managerList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['params']->value['user_id']==$_smarty_tpl->tpl_vars['row']->value['user_id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</option>
                                    <?php } ?>
                                </select>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：
                                <input type="text" class="form-unit" ued="{ 'datepicker':'true,false,year'}" name="start_date" id="start_date" value="<?php echo $_smarty_tpl->tpl_vars['params']->value['start_date'];?>
">
                                至
                                <input type="text" class="form-unit" ued="{ 'datepicker':'true,false,year'}" name="end_date" id="end_date" value="<?php echo $_smarty_tpl->tpl_vars['params']->value['end_date'];?>
">
                                &nbsp;&nbsp;&nbsp;
                                <a href="javascript:void(0);" class="search-btn">开始查询</a>
                            </div>
                            </form>
                            <?php }?>
                            <div class="table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>项目总结文档（点击下载查看）</th>
                                        <?php if ($_smarty_tpl->tpl_vars['roleId']->value!='2'){?><th>项目经理</th><?php }?>
                                        <th>上传时间</th>
                                        <th>操作</th>
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
                                        <td><a href="<?php echo $_smarty_tpl->tpl_vars['row']->value['name_url'];?>
" style="color: #60a6ee"><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</a></td>
                                        <?php if ($_smarty_tpl->tpl_vars['roleId']->value!='2'){?><td><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</td><?php }?>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['date'];?>
</td>
                                        <td><a href="javascript:;" onclick="del('<?php echo $_smarty_tpl->tpl_vars['row']->value['project_report_id'];?>
');" style="color:#db0d0d">删除</a></td>
                                    </tr>
									<?php } ?>
									<?php }else{ ?>
									<tr>
                                        <td colspan="<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>3<?php }else{ ?>4<?php }?>"width="50" height="50" align="center">暂无记录</td>
                                    </tr>
									<?php }?>
                                    </tbody>
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

<script src="/js/vendor/jquery-1.8.2.min.js"></script>
<script src="/js/plugins.min.js"></script>
<script src="/js/matrix.min.js"></script>
<script src="/js/all.js"></script>
<script src="/js/main.js"></script>
<script src="/js/vendor/jquery.fineuploader-3.7.1.1.min.js"></script>
<script>
var _no = "";
$(function() {
   	<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>
    $('.btnUpload').fineUploader({
        request: {
            endpoint: '?task=upload'
        },
        //validation: {
        //    allowedExtensions: ['jpeg', 'jpg', 'png']
        //},
        multiple: false,
        text: {
            uploadButton: '<div>上传项目总结</div>'
        },
        deleteFile: {
            enabled: true,
            endpoint: '?task=upload&uuid='
        },
        validation: {
            sizeLimit: 1000 * (1024 * 1024)
        },
    }).on('complete', function (event, id, fileName, responseJson) {
        if (responseJson.success) {
            window.location.reload();//刷新当前页面
        }else{
            showInfo("error",responseJson.msg);
        }
    });
    <?php }else{ ?>
    $(".hasDatepicker").attr("readonly", false);
    $('.search-btn').click(function(){
		$('#search_form').submit();
	});
	<?php }?>
});

function del(report_id) {
	if (!confirm('删除不可恢复，确定要删除么？')) {
		return;
	}

	var url = 'project_report.php?task=del&report_id=' + report_id;

	<?php if ($_smarty_tpl->tpl_vars['roleId']->value!='2'){?>
	if ($('#user_id').val() != '') {
		url += '&user_id=' + $('#user_id').val();
	}
	if ($('#start_date').val() != '') {
		url += '&start_date=' + $('#start_date').val();
	}
	if ($('#end_date').val() != '') {
		url += '&end_date=' + $('#end_date').val();
	}
	<?php }?>

	window.location.href = url;
}

</script>
</body>
</html><?php }} ?>