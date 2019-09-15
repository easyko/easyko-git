<?php /* Smarty version Smarty-3.1.12, created on 2019-05-06 21:33:27
         compiled from "/usr/local/web/test/pm/site/template/view/performance_list.html" */ ?>
<?php /*%%SmartyHeaderCode:9757323015cd037a7b9caa4-23428444%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9957f382f51eb39bdcfb33bb1c147561c2a7c8a2' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/performance_list.html',
      1 => 1550329701,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9757323015cd037a7b9caa4-23428444',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'userList' => 0,
    'row' => 0,
    'userId' => 0,
    'dateList' => 0,
    'i' => 0,
    'date' => 0,
    'itemList' => 0,
    'j' => 0,
    'child' => 0,
    'totalList' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5cd037a7cedfc8_39206809',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd037a7cedfc8_39206809')) {function content_5cd037a7cedfc8_39206809($_smarty_tpl) {?><!DOCTYPE html>
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
                        <div class="main-content-bottom">
                            <div class="achievements">
                                姓名：
                                <select id="user_id" name="user_id">
									<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['userList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['userId']->value==$_smarty_tpl->tpl_vars['row']->value['user_id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</option>
                                    <?php } ?>
                                </select>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：
                                <select name="date" id="date">
									<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['dateList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['date']->value==$_smarty_tpl->tpl_vars['i']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
</option>
                                    <?php } ?>
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <a class="search-btn" href="javascript:void(0);">开始查询</a>
                            </div>
                            <div>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>姓名</th>
                                        <th>项目编号</th>
                                        <th>项目名称</th>
                                        <th>工单号</th>
                                        <th>任务类型</th>
                                        <th>计划开始时间</th>
                                        <th>计划结束时间</th>
                                        <th>实际完成时间</th>
                                        <th>提交文件</th>
                                        <th>工作单元</th>
                                        <th>计划分值</th>
                                        <th>实际分值</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($_smarty_tpl->tpl_vars['itemList']->value){?>
									<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
									<?php if (count($_smarty_tpl->tpl_vars['row']->value['list'])==1){?>
										<tr>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</td>
											<td><a href="project.php?task=detail&product_no=<?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
" target="_blank" style="color: #60a6ee"><?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
</a></td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['project_name'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['job_no'];?>
 <input type="hidden" name="jobNo" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['job_no'];?>
"></td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['type'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['start_time'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['end_time'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['finished_time'];?>
</td>
											<td class="att"><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['attachment'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['work_unit'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['plan_score'];?>
</td>
											<td><span class="see"><?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['real_score'];?>
</span><span class="edit" style="display: none"><input
                                                    type="text" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['real_score'];?>
" class="form-unit" name="edit" style="width: 20px; padding: 5px 10px;"> </span></td>
											<td><span class="see link">编辑</span><span class="edit submit link" style="display: none">提交</span></td>
										</tr>
                                    <?php }else{ ?>
										<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['row']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
										<tr>
											<?php if ($_smarty_tpl->tpl_vars['j']->value==0){?><td rowspan="<?php echo count($_smarty_tpl->tpl_vars['row']->value['list']);?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</td>
											<td rowspan="<?php echo count($_smarty_tpl->tpl_vars['row']->value['list']);?>
"><a href="project.php?task=detail&product_no=<?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
" target="_blank" style="color: #60a6ee"><?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
</a></td>
											<td rowspan="<?php echo count($_smarty_tpl->tpl_vars['row']->value['list']);?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['project_name'];?>
</td>
											<?php }?>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['job_no'];?>
<input type="hidden" name="jobNo" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['job_no'];?>
"></td>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['type'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['start_time'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['end_time'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['finished_time'];?>
</td>
											<td class="att"><?php echo $_smarty_tpl->tpl_vars['child']->value['attachment'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['work_unit'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['child']->value['plan_score'];?>
</td>
                                            <td><span class="see"><?php echo $_smarty_tpl->tpl_vars['child']->value['real_score'];?>
</span><span class="edit" style="display: none"><input
                                                    type="text" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['list'][0]['real_score'];?>
" class="form-unit"  name="edit" style="width: 20px; padding: 5px 10px;"></span></td>
                                            <td><span class="see link">编辑</span><span class="edit submit link" style="display: none">提交</span></td>
										</tr>
										<?php } ?>
                                    <?php }?>
                                    <?php } ?>
                                    <?php }else{ ?>
                                    <tr>
										<td colspan="14" align="center" height="80">暂无记录</td>
									</tr>
                                    <?php }?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="background-color: #f3f3f3;"> 合计： </td>
                                            <td colspan="8" style="background-color: #f3f3f3;">   </td>
                                            <td style="color: red; font-weight: bold; background-color: #f3f3f3;"> <?php echo $_smarty_tpl->tpl_vars['totalList']->value['work_unit'];?>
 </td>
                                            <td bgcolor="" style="color: red; font-weight: bold; background-color: #f3f3f3;"> <?php echo $_smarty_tpl->tpl_vars['totalList']->value['plan_score'];?>
 </td>
                                            <td bgcolor="#f3f3f3" style="color: red; font-weight: bold; background-color: #f3f3f3;"> <?php echo $_smarty_tpl->tpl_vars['totalList']->value['real_score'];?>
 </td>
                                            <td style="color: red; font-weight: bold; background-color: #f3f3f3;">&nbsp;</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a class="btn-primary btn m-t-10" style="border: 1px solid #8c8b8b" href="javascript:void(0)">
                                    <span class="export">下载Excel格式</span>
                                </a>
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
    <a href="javascript:BOX_remove('tcdiv')" class="close"></a>
</div>

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


<script src="/js/vendor/jquery-1.8.2.min.js"></script>
<script src="/js/plugins.min.js"></script>
<script src="/js/matrix.min.js"></script>
<script src="/js/all.js"></script>
<script src="/js/main.js"></script>
<script>
$(function () {
    $(".table .edit input").keyup(function(event){
        e = event ? event : (window.event ? window.event : null);
        if (e.keyCode == 13) {
            $(this).parents("tr").find(".submit").click();
        }
    });

	var url = 'performance.php';

    $(".table .see").click(function(){
        $(this).parents("tr").find(".see").hide();
        $(this).parents("tr").find(".edit").show();
    })
    $(".table .submit").click(function(){
        var _this = $(this).parents("tr");
        var _jobNo = $(this).parents("tr").find("input[name='jobNo']").val();
        var _editfz = $(this).parents("tr").find("input[name='edit']").val();

        if (_editfz == '') {
            showInfo("error","请填写实际分值！");
            return;
        }else if(isNaN(_editfz)){
            showInfo("error","实际分值请填写数字！");
            return;
        }

        $.post('?task=update', {
            job_no:_jobNo,
            real_score:_editfz
        }, function(data){
            if (data.success == 1) {
                _this.find(".see:eq(0)").text(_editfz);
                _this.find(".see").show();
                _this.find(".edit").hide();
                var _no = 0;
                $(".table tbody tr").each(function(){
                    if($(this).find(".see:eq(0)").text() =="" || $(this).find(".see:eq(0)").text() == undefined || $(this).find(".see:eq(0)").text() == null){
                    }else{
                        _no += parseFloat($(this).find(".see:eq(0)").text());
                    }
                })
                _no = _no.toFixed(2);
                $(".table tfoot td:eq(4)").text(_no);
                showInfo("success",data.msg)
            }else{
                showInfo("error", data.msg);
            }
        }, 'json');
    })
	$('.search-btn').click(function(){
		var date = $('#date').val();
		var user_id = $('#user_id').val();

		if (date != '') {
			if (url.indexOf('?') != -1) {
				url += '&date=' + date;
			} else {
				url += '?date=' + date;
			}
		}

		if (user_id != '') {
			if (url.indexOf('?') != -1) {
				url += '&user_id=' + user_id;
			} else {
				url += '?user_id=' + user_id;
			}
		}

		window.location.href = url + '&t=' + Math.random();
	});

	$('.export').click(function(){
		var date = $('#date').val();
		var user_id = $('#user_id').val();

		if (date != '') {
			if (url.indexOf('?') != -1) {
				url += '&date=' + date;
			} else {
				url += '?date=' + date;
			}
		}

		if (user_id != '') {
			if (url.indexOf('?') != -1) {
				url += '&user_id=' + user_id;
			} else {
				url += '?user_id=' + user_id;
			}
		}

		window.location.href = url + '&export&t=' + Math.random();
	});
});
</script>
</body>
</html><?php }} ?>