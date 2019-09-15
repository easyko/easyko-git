<?php /* Smarty version Smarty-3.1.12, created on 2019-05-04 12:41:54
         compiled from "/usr/local/web/test/pm/site/template/view/project_detail.html" */ ?>
<?php /*%%SmartyHeaderCode:16196860145ccd17ece39666-11610266%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b117afa1e0f40e6dec7eb785fca123d08a7fa752' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/project_detail.html',
      1 => 1556944921,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16196860145ccd17ece39666-11610266',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5ccd17ed15bf28_03147997',
  'variables' => 
  array (
    'title' => 0,
    'typeList' => 0,
    'detail' => 0,
    'lenLimitList' => 0,
    'roleId' => 0,
    'username' => 0,
    'projectStat' => 0,
    'i' => 0,
    'row' => 0,
    'child' => 0,
    'c' => 0,
    'typeList2' => 0,
    't' => 0,
    'type' => 0,
    'managerList' => 0,
    'userList' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccd17ed15bf28_03147997')) {function content_5ccd17ed15bf28_03147997($_smarty_tpl) {?><!DOCTYPE html>
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
    <style>
        .cke_chrome{
            display: inline-block!important;
        }
    </style>
    <script type="text/javascript">
        var typeList = <?php echo $_smarty_tpl->tpl_vars['typeList']->value;?>
;
    </script>
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
                                <form action="123.html">
                                <div class="select-main">

                                    <div class="modeStyle3">
                                        <div class="qx-cs">
                                            <div class="b-main">
                                                <span class="q-title w-120"><span class="dd">*</span> 项目编号：</span>
                                                <span class="job_no"><?php echo $_smarty_tpl->tpl_vars['detail']->value['project_no'];?>
</span>
                                            </div>
                                            <br/>

                                            <div class="b-main">
                                                <span class="q-title w-120"><span class="dd">*</span> 项目名称：</span>
                                                <input type="text" class="form-unit w-265" name="project_name" id="project_name" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['project_name'];?>
">
                                            </div>
                                            <br/>

                                            <div class="b-main">
                                                <span class="q-title w-120"><span class="dd">*</span> 客户名称：</span>
                                                <input type="text" class="form-unit w-265" name="customer_name" id="customer_name" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['customer_name'];?>
" placeholder="限<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['customerNameLen'];?>
个字符" maxlength="<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['customerNameLen'];?>
">
                                            </div>
                                            <br/>

                                            <div class="b-main">
                                                <span class="q-title w-120"><span class="dd">*</span> 开始日期：</span>
                                                <?php echo $_smarty_tpl->tpl_vars['detail']->value['start_date'];?>

                                            </div>
                                            <br/>

                                            <div class="b-main">
                                                <span class="q-title w-120"><span class="dd">*</span> 项目经理：</span>
                                                <?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>
												<?php echo $_smarty_tpl->tpl_vars['username']->value;?>

                                                <?php }else{ ?>
                                                <input type="text" class="form-unit w-265 manager" name="project_manager" id="project_manager" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['manager_name'];?>
">
                                                <?php }?>
                                                 <input type="hidden" name="project_manager_id" id="project_manager_id" value="<?php if ($_smarty_tpl->tpl_vars['detail']->value['project_manager_id']){?><?php echo $_smarty_tpl->tpl_vars['detail']->value['project_manager_id'];?>
<?php }else{ ?>0<?php }?>">
                                            </div>
                                            <br/>

                                            <div class="b-main">
                                                <span class="q-title w-120"><span class="dd">*</span> 项目状态：</span>
                                                <select class="form-unit interval-select" style="width:290px;" name="status" id="status">
                                                    <option value="">请选择</option>
                                                    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['projectStat']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['i']->value==$_smarty_tpl->tpl_vars['detail']->value['status']){?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <br/>
                                            <div class="b-main">
                                                <span class="q-title w-120">备注说明：</span>
                                                <textarea class="form-unit" style="width:612px; height:50px;" placeholder="备注说明（限<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['commentLen'];?>
个字符）" maxlength="<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['commentLen'];?>
" name="comment" id="comment"><?php echo $_smarty_tpl->tpl_vars['detail']->value['comment'];?>
</textarea>
                                            </div>
                                        </div>
                                        <div class="qx-cs write">
                                            <div class="b-con" style="width: 1100px;">
                                                <span class="q-title w-120 name" style="vertical-align: top;"><span class="dd">*</span> 分配执行人员：</span>
                                                <span class="fp-table" style="width: 950px;">
                                                    <table style="">
                                                        <thead>
                                                        <tr>
                                                            <td width="120">提交文件</td>
                                                            <td>工单号</td>
                                                            <td>执行人员</td>
                                                            <td>任务类型</td>
                                                            <td width="80">计划开始时间</td>
                                                            <td width="80">计划结束时间</td>
                                                            <td width="90">实际完成时间</td>
                                                            <td width="60">工作单元</td>
                                                            <td width="60">计划分值</td>
                                                            <td width="70">实际分值</td>
                                                            <td width="45">操作</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['detail']->value['usersList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
                                                        <tr>
                                                            <td>
                                                                <?php if (!empty($_smarty_tpl->tpl_vars['child']->value['attachment'])){?>
                                                                <?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['child']->value['attachment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['c']->key;
?>
                                                                <!--<?php if (($_smarty_tpl->tpl_vars['i']->value>0&&$_smarty_tpl->tpl_vars['i']->value%6==0)){?>
                                                                <br />
                                                                <?php }?>-->
                                                                <a href="<?php echo $_smarty_tpl->tpl_vars['c']->value['url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
" target="_blank" class="file"></a>
                                                                <?php } ?>
                                                                <?php }else{ ?>--<?php }?>
                                                            </td>
                                                            <td>
                                                                <input class="form-unit" type="text" name="my_no" style="border: 0px;" readonly="readonly" data-myId="<?php echo $_smarty_tpl->tpl_vars['child']->value['execuser_id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['job_no'];?>
">
                                                            </td>
                                                            <td>
                                                                <input class="form-unit" type="text" style="border: 0px;" name="people" readonly="readonly" data-allId=" <?php echo $_smarty_tpl->tpl_vars['child']->value['user_id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['username'];?>
" style="display: inline-block;">
                                                            </td>
                                                            <td>
                                                                <input class="form-unit see" type="text" name="task" readonly="readonly" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['type'];?>
" style="display: inline-block;">
                                                                <select class="form-unit edit" name="task-edit" style="width: 90px; display: none;">
																	<option value="">任务类型</option>
																<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['typeList2']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['t']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
                                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['t']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['type']->value==$_smarty_tpl->tpl_vars['child']->value['type']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</option>
                                                                <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['start_time'];?>
" class="form-unit edit" name="startTime-edit">
                                                                <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['start_time'];?>
" class="form-unit see"  readonly="readonly" name="startTime">
                                                            </td>
                                                            <td>
                                                                <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['end_time'];?>
" class="form-unit edit" name="endTime-edit">
                                                                <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['end_time'];?>
" class="form-unit see"  readonly="readonly" name="endTime">
                                                            </td>
                                                            <td><?php echo $_smarty_tpl->tpl_vars['child']->value['finished_time'];?>
</td>
                                                            <td>
                                                                <input class="form-unit edit" type="text" style="width: 40px; display: none;" name="dy-edit" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['work_unit'];?>
">
                                                                <input class="form-unit see" type="text" style="width: 40px; display: inline-block;" name="dy" readonly="readonly" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['work_unit'];?>
">
                                                            </td>
                                                            <td>
                                                                <input class="form-unit edit" type="text" style="width: 40px; display: none;" name="jhfz-edit" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['plan_score'];?>
">
                                                                <input class="form-unit see" type="text" style="width: 40px; display: inline-block;" name="jhfz" readonly="readonly" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['plan_score'];?>
">
                                                            </td>
                                                            <td>
																<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>
																	<?php if ($_smarty_tpl->tpl_vars['child']->value['real_score']==''){?>
																	--
																	<?php }else{ ?>
																	<?php echo $_smarty_tpl->tpl_vars['child']->value['real_score'];?>

																	<?php }?>
																<?php }else{ ?>
                                                                <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['real_score'];?>
" class="form-unit edit" name="sjfz-edit" style="width: 40px;"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['child']->value['real_score'];?>
" class="form-unit see"  readonly="readonly" name="sjfz" style="width: 40px;">
																<?php }?>
															</td>
                                                            <td>
                                                                <span class="see" style="display: inline;">
                                                                <span class="edit1">编辑</span>
                                                                <?php if ($_smarty_tpl->tpl_vars['roleId']->value=='0'||$_smarty_tpl->tpl_vars['roleId']->value=='1'){?>
                                                                <span class="deluser" job_no="<?php echo $_smarty_tpl->tpl_vars['child']->value['job_no'];?>
" user_id="<?php echo $_smarty_tpl->tpl_vars['child']->value['user_id'];?>
">删除</span>
                                                                <?php }?>
                                                                </span>
                                                                <span class="edit" style="display: none;">
                                                                <span class="change">保存</span>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td>--</td>
                                                            <td><input type="text" placeholder="自动生成" class="form-unit" readonly="readonly"  name="No" id="No"></td>
                                                            <td><input type="text" placeholder="选择姓名" class="form-unit staff" name="people" id="people"></td>
                                                            <td>
                                                                <select class="form-unit interval-select" id="task" value=""  name="task" id="task" style="width: 90px">
                                                                    <option value="">任务类型</option>
                                                                    <?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['typeList2']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['t']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
                                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['t']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" placeholder="选择时间" class="form-unit" ued="{ 'datepicker':'true,false,year'}" name="startTime" id="startTime"></td>
                                                            <td><input type="text" placeholder="选择时间" class="form-unit" ued="{ 'datepicker':'true,false,year'}" name="endTime" id="endTime"></td>
                                                            <td>--</td>
                                                            <td><input type="text"  placeholder="请输入" class="form-unit" style="width: 40px;" name="dy" id="dy" maxlength="4"></td>
                                                            <td><input type="text"  placeholder="请输入" class="form-unit" style="width: 40px;" name="jhfz" id="jhfz" maxlength="4"></td>
                                                            <td>
																<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>
																--
																<?php }else{ ?>
																<input type="text"  placeholder="请输入" class="form-unit" style="width: 40px;" name="sjfz" id="sjfz" maxlength="4">
																<?php }?>
															</td>
                                                            <td><span class="create">创建</span></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </span>
                                            </div>
                                        </div>


                                        <div class="qx-cs">
                                            <div class="b-main">
                                                <span class="q-title w-120" style="vertical-align: top">项目描述：</span>
                                                <textarea class="form-unit" style="width:612px; height:150px;" placeholder="请输入项目背景或要求" name="project_desc" id="project_desc"><?php echo $_smarty_tpl->tpl_vars['detail']->value['project_desc'];?>
</textarea>
                                            </div>
                                            <br/>

                                            <div class="update">
                                                <div class="b-main rel">
                                                    <span class="q-title w-120" style="vertical-align:-10px;"><span class="dd">*</span> 项目资料：</span>
                                                    <div style="display: inline-block; vertical-align: top; width: 700px">
                                                        <div class="btnUpload"></div>
                                                    </div>
                                                </div>
                                                <br/>
                                            </div>

                                            <div class="b-con">
                                                <span class="q-title w-120 name" style="vertical-align: top; padding-top: 6px"> 项目资料：</span>
                                                <span class="con">
                                                    <span class="text">
                                                        <span class="p-con">
                                                        <?php if (!empty($_smarty_tpl->tpl_vars['detail']->value['attachment'])){?>
												        <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['detail']->value['attachment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
                                                        <a target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['child']->value['name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"></a>
                                                        <?php } ?>
                                                        <?php }else{ ?>
                                                        --
                                                        <?php }?>
                                                        </span>
                                                    </span>
                                                </span>
                                                </span>
                                            </div>
											<?php if ($_smarty_tpl->tpl_vars['roleId']->value==0||$_smarty_tpl->tpl_vars['roleId']->value==2){?>
                                            <div class="update-ht">
                                                <div class="b-main rel">
                                                    <span class="q-title w-120" style="vertical-align:-10px;"> 合同：</span>
                                                    <div style="display: inline-block; vertical-align: top; width: 700px">
                                                        <div class="btnUpload1"></div>
                                                    </div>
                                                </div>
                                                <br/>
                                            </div>

                                            <div class="b-con">
                                                <span class="q-title w-120 name" style="vertical-align: top; padding-top: 6px"> 合同：</span>
                                                <span class="con">
                                                    <span class="text">
                                                        <span class="p-con">
                                                            <?php if (!empty($_smarty_tpl->tpl_vars['detail']->value['contract_attachment'])){?>
                                                            <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['detail']->value['contract_attachment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
                                                            <a target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['child']->value['name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"></a>
                                                            <?php } ?>
                                                            <?php }else{ ?>
															--
                                                            <?php }?>
                                                        </span>
                                                    </span>
                                                </span>
                                                </span>
                                            </div>
											<?php }?>
											<div class="update-ht">
                                                <div class="b-main rel">
                                                    <span class="q-title w-120" style="vertical-align:-10px;"> 项目提案：</span>
                                                    <div style="display: inline-block; vertical-align: top; width: 700px">
                                                        <div class="btnUpload2"></div>
                                                    </div>
                                                </div>
                                                <br/>
                                            </div>
											<div class="b-con">
                                                <span class="q-title w-120 name" style="vertical-align: top; padding-top: 6px"> 项目提案：</span>
                                                <span class="con">
                                                    <span class="text">
                                                        <span class="p-con">
                                                        <?php if (!empty($_smarty_tpl->tpl_vars['detail']->value['proposal_attachment'])){?>
												        <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['detail']->value['proposal_attachment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
                                                        <a target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['child']->value['name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"></a>
                                                        <?php } ?>
                                                        <?php }else{ ?>
                                                        --
                                                        <?php }?>
                                                        </span>
                                                    </span>
                                                </span>
                                                </span>
                                            </div>

											<div class="update-ht">
                                                <div class="b-main rel">
                                                    <span class="q-title w-120" style="vertical-align:-10px;"> 会议纪要：</span>
                                                    <div style="display: inline-block; vertical-align: top; width: 700px">
                                                        <div class="btnUpload3"></div>
                                                    </div>
                                                </div>
                                                <br/>
                                            </div>
											<div class="b-con">
                                                <span class="q-title w-120 name" style="vertical-align: top; padding-top: 6px"> 会议纪要：</span>
                                                <span class="con">
                                                    <span class="text">
                                                        <span class="p-con">
                                                        <?php if (!empty($_smarty_tpl->tpl_vars['detail']->value['meetingnote_attachment'])){?>
												        <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['detail']->value['meetingnote_attachment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
                                                        <a target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['child']->value['name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"></a>
                                                        <?php } ?>
                                                        <?php }else{ ?>
                                                        --
                                                        <?php }?>
                                                        </span>
                                                    </span>
                                                </span>
                                                </span>
                                            </div>
											<?php if ($_smarty_tpl->tpl_vars['roleId']->value==0||$_smarty_tpl->tpl_vars['roleId']->value==2){?>
                                            <div class="b-main">
                                                <span class="q-title w-120">合同号：</span>
                                                <input type="text" class="form-unit w-265" name="contract_no" id="contract_no" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['contract_no'];?>
">
                                            </div>
                                            <br/>
                                            <div class="b-main">
                                                <span class="q-title w-120">合同金额：</span>
                                                <input type="text" class="form-unit w-265" name="contract_amount" id="contract_amount" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['contract_amount'];?>
">
                                            </div>
                                            <br/>
                                            <div class="b-main">
                                                <span class="q-title w-120">实际收款：</span>
                                                <input type="text" class="form-unit w-265" name="real_amount" id="real_amount" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['real_amount'];?>
">
                                            </div>
                                            <br/>
                                            <div class="b-main">
                                                <span class="q-title w-120">第三方费用：</span>
                                                <input type="text" class="form-unit w-265" name="other_amount" id="other_amount" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['other_amount'];?>
">
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <div class="qx-btn">
                                    <a href="javascript:void (0)" class="btn btn-primary btn-submit btn-c"><span>修改项目</span></a>
                                </div>
                                </form>
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
    <span class="pp-title">提示</span>
    <a href="javascript:BOX_remove('tcdiv')" class="close"></a>
    <table align="center">
        <tr>
            <td width="80"></td>
            <td><span class="success">您创建的项目已成功！</span></td>
            <td width="80"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <a class="btn-add" href="javascript:BOX_remove('tcdiv')">确 定</a>
            </td>
            <td align="left"></td>
        </tr>
    </table>
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


<div id="managerdiv" class="pop" style="width: 700px">
    <span class="pp-title">请选择项目经理</span>
    <a href="javascript:BOX_remove('managerdiv');" class="close"></a>
    <table align="center" style="margin: 10px">
        <tr>
            <td colspan="3">
                <div class="manager-main">
                   <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['managerList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                    <span class="m-pp" data-name="<?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</span>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td align="right"></td>
            <td align="center">
                <a class="btn-on" href="javascript:void(0)" style="width: 300px">确 定</a>
            </td>
            <td align="left"></td>
        </tr>
    </table>
</div>

<div id="staffdiv" class="pop" style="width: 700px">
    <span class="pp-title">请选择执行人员</span>
    <a href="javascript:BOX_remove('staffdiv');" class="close"></a>
    <table align="center" style="margin: 10px">
        <tr>
            <td colspan="3">
                <div class="manager-main">
					<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['userList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                    <span class="m-pp" data-name="<?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['row']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</span>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td align="right"></td>
            <td align="center">
                <a class="btn-on" href="javascript:void(0)" style="width: 300px">确 定</a>
            </td>
            <td align="left"></td>
        </tr>
    </table>
</div>



<!--区间初始拷贝-->
<div class="b-con dis demo">
    <span class="q-title w-120 name"></span>
	<span class="fp-list">
		<input type="text" class="form-unit staff" name="people" style="width: 200px" placeholder="请选择人员>>">
		<select class="form-unit  w-141 interval-select"  name="task">
			<option value="">任务类型</option>
		</select>
		<input type="text" class="form-unit w-95" name="NO" readonly />
		<span class="q-title dd create_user-no">生成工单号</span>
	</span>
	<span class="control">
		<span class="clear">&nbsp;</span>
	</span>
</div>

<!--区间初始结束-->

<script src="/js/vendor/jquery-1.8.2.min.js"></script>
<script src="/js/plugins.min.js"></script>
<script src="/js/matrix.min.js"></script>
<script src="/js/all.js"></script>
<script src="/js/main.js"></script>
<script src="/js/interval.js"></script>
<script src="js/vendor/jquery.fineuploader-3.7.1.min.js"></script>
<script src="/site/plugin/editor/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/site/plugin/editor/jquery.ckeditor.js" type="text/javascript"></script>
<script type="text/javascript">
    var staffNow = "";
    var _no = $(".job_no").text();
    var _pNo = true;
    $(function() {
        $(".fp-table").find("tbody tr").find("input[name='startTime-edit']").attr("ued","{ 'datepicker':'true,false,year'}");
        $(".fp-table").find("tbody tr").find("input[name='endTime-edit']").attr("ued","{ 'datepicker':'true,false,year'}");
        var _oldData = $(".fp-table tbody tr").size();
 		CKEDITOR.replace('project_desc', {
			width:622,
			height:220,
			skin:'kama',
			toolbar:[
				['Bold','Italic','Underline','Strike'],
				['TextColor','BGColor'],
				['Source','-','Preview']
			]
		});

		/*CKEDITOR.replace('feedback', {
			width:622,
			height:220,
			skin:'kama',
			toolbar:[
				['Bold','Italic','Underline','Strike'],
				['TextColor','BGColor'],
				['Source','-','Preview']
			]
		});*/

        $('.btnUpload').fineUploader({
            request: {
                endpoint: '?task=upload&name=projectFile'
            },
            //validation: {
            //    allowedExtensions: ['jpeg', 'jpg', 'png']
            //},
            multiple: true,
            text: {
                uploadButton: '<div>上传项目资料</div>'
            },
            deleteFile: {
                enabled: true,
                endpoint: '?task=upload&name=projectFile&uuid='
            },
            validation: {
                sizeLimit: 1000 * (1024 * 1024)
            },
        }).on('complete', function (event, id, fileName, responseJson) {
            if (responseJson.success) {
                $(this).find(".qq-upload-delete").data("delUrl",fileName);
            }else{
                showInfo("error",responseJson.msg);
            }
        });

        $('.btnUpload1').fineUploader({
            request: {
                endpoint: '?task=upload&name=projectContractFile'
            },
            //validation: {
            //    allowedExtensions: ['jpeg', 'jpg', 'png']
            //},
            multiple: true,
            text: {
                uploadButton: '<div>上传合同</div>'
            },
            deleteFile: {
                enabled: true,
                endpoint: '?task=upload&name=projectContractFile&uuid='
            },
            validation: {
                sizeLimit: 1000 * (1024 * 1024)
            },
        }).on('complete', function (event, id, fileName, responseJson) {
            if (responseJson.success) {
                $(this).find(".qq-upload-delete").data("delUrl",fileName);
            }else{
                showInfo("error",responseJson.msg);
            }
        });

		$('.btnUpload2').fineUploader({
            request: {
                endpoint: '?task=upload&name=projectProposalFile'
            },
            //validation: {
            //    allowedExtensions: ['jpeg', 'jpg', 'png']
            //},
            multiple: true,
            text: {
                uploadButton: '<div>上传项目提案</div>'
            },
            deleteFile: {
                enabled: true,
                endpoint: '?task=upload&name=projectProposalFile&uuid='
            },
            validation: {
                sizeLimit: 1000 * (1024 * 1024)
            },
        }).on('complete', function (event, id, fileName, responseJson) {
            if (responseJson.success) {
                $(this).find(".qq-upload-delete").data("delUrl",fileName);
            }else{
                showInfo("error",responseJson.msg);
            }
        });

		$('.btnUpload3').fineUploader({
            request: {
                endpoint: '?task=upload&name=projectMeetingNoteFile'
            },
            //validation: {
            //    allowedExtensions: ['jpeg', 'jpg', 'png']
            //},
            multiple: true,
            text: {
                uploadButton: '<div>上传会议纪要</div>'
            },
            deleteFile: {
                enabled: true,
                endpoint: '?task=upload&name=projectMeetingNoteFile&uuid='
            },
            validation: {
                sizeLimit: 1000 * (1024 * 1024)
            },
        }).on('complete', function (event, id, fileName, responseJson) {
            if (responseJson.success) {
                $(this).find(".qq-upload-delete").data("delUrl",fileName);
            }else{
                showInfo("error",responseJson.msg);
            }
        });
        $(".manager").click(function(){
            $("#managerdiv .on").removeClass("on")
            BOX_show("managerdiv");
        });
        $(".staff").live("click",function(){
            $("#staffdiv .on").removeClass("on");
            staffNow = $(this);
            BOX_show("staffdiv");
        });

        $(".qq-upload-delete").live("hover",function(){
            _file = $(this).siblings(".qq-upload-file").text();
        });

        $("#managerdiv span, #staffdiv span").click(function(){
            $(this).siblings("span").removeClass("on");
            $(this).addClass("on");
        });
        //项目经理input
        $("#managerdiv .btn-on").click(function(){
            var _myself = "";
            _myself = $(this).parents("table").find(".manager-main .on:eq(0)");
            $("#project_manager_id").val(_myself.data("id"));
            $(".manager").val(_myself.data("name"));
            BOX_remove('managerdiv')
        })

        $("#staffdiv .btn-on").click(function(){
            var _myself = "";
            _myself = $(this).parents("table").find(".manager-main .on:eq(0)");

            staffNow.attr("data-allId",_myself.data("id"));
            staffNow.val(_myself.data("name"));
            BOX_remove('staffdiv')
        });


        $("input[name='No']").click(function(){
            var _this = $(this);
            if(!_pNo){
                return;
            }
            _pNo = false;
            $.post('?task=get_userno&t=' + Math.random(), {
                formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
            }, function(data){
                if (data.status == 'SUCCESS') {
                    _this.val(data.user_no);
                    _this.data("myId",data.user_no_id);
                    _pNo = false;
                } else if(data.user_no == 0) {
                    _pNo = true;
                    showInfo("error", data.msg);
                }
            }, 'json');

        });

        $(".create").click(function(){
            var _this = $(this).parents("tr");

            if (_this.find('#No').val() == '') {
                showInfo("error","请点击生成项目工单号！");
                return;
            }
            if (_this.find('.staff').val() == '') {
                showInfo("error","请选择执行人员！");
                return;
            }
            if (_this.find('#task').val() == '') {
                showInfo("error","请选择任务类型！");
                return;
            }
            if (_this.find('#startTime').val() == '') {
                showInfo("error","请选择计划开始时间！");
                return;
            }
            if (_this.find('#endTime').val() == '') {
                showInfo("error","请选择计划结束时间！");
                return;
            }
            if (_this.find('#dy').val() == '') {
                showInfo("error","请填写工作单元！");
                return;
            }else if(isNaN(_this.find('#dy').val())){
                showInfo("error","工作单元请填写数字！");
                return;
            }
            if (_this.find('#jhfz').val() == '') {
                showInfo("error","请填写计划分值！");
                return;
            }else if(isNaN(_this.find('#jhfz').val())){
                showInfo("error","计划分值请填写数字！");
                return;
            }
            if (_this.find('#sjfz').val() == '' || _this.find('#sjfz').val() == undefined) {
                _this.find('#sjfz').val("");
            }else if(isNaN(_this.find('#sjfz').val())){
                showInfo("error","实际分值请填写数字！");
                return;
            }

            var _html = '<tr><td>--</td>';
            _html += '<td><input type="text" value='+_this.find('#No').val()+' class="form-unit" readonly="readonly" style="border: 0px;"  name="my_no"></td>';
            _html += '<td><input type="text" value='+_this.find('.staff').val()+' data-allId='+_this.find('.staff').attr("data-allId")+' class="form-unit edit staff" name="people-edit"><input type="text" value='+_this.find('.staff').val()+' class="form-unit see" data-allId='+_this.find('.staff').attr("data-allId")+' readonly="readonly" name="people"></td>';
            var _div = _this.find('#task').clone().html();
            _html += '<td><select style="width: 90px; " name="task-edit" value="" class="form-unit interval-select edit">'+_div+'</select><input type="text" value='+_this.find('#task').find("option:selected").text()+' class="form-unit see"  readonly="readonly" name="task"></td>';
            _html += '<td><input type="text" value='+_this.find('#startTime').val()+' class="form-unit edit" name="startTime-edit"><input type="text" value='+_this.find('#startTime').val()+' class="form-unit see"  readonly="readonly" name="startTime"></td>';
            _html += '<td><input type="text" value='+_this.find('#endTime').val()+' class="form-unit edit" name="endTime-edit"><input type="text" value='+_this.find('#endTime').val()+' class="form-unit see"  readonly="readonly" name="endTime"></td>';
            _html += '<td>--</td>';
            _html += '<td><input type="text" value='+_this.find('#dy').val()+' class="form-unit edit" name="dy-edit"  maxlength="4" style="width: 40px;"><input type="text" value='+_this.find('#dy').val()+' class="form-unit see"  readonly="readonly" name="dy" style="width: 40px;"></td>';
            _html += '<td><input type="text" value='+_this.find('#jhfz').val()+' class="form-unit edit" name="jhfz-edit"  maxlength="4" style="width: 40px;"><input type="text" value='+_this.find('#jhfz').val()+' class="form-unit see"  readonly="readonly" name="jhfz" style="width: 40px;"></td>';
            if(_this.find('#sjfz').val() == undefined){
                _html += '<td>--</td>';
            }else{
                var _a = _this.find('#sjfz').val();
                if(_a == ""){
                    _html += '<td><input type="text" class="form-unit edit" name="sjfz-edit" maxlength="4" style="width: 40px;"><input type="text" class="form-unit see"  readonly="readonly" name="sjfz" style="width: 40px;"></td>';
                }else{
                    _html += '<td><input type="text" value='+_a+' class="form-unit edit" name="sjfz-edit" maxlength="4" style="width: 40px;"><input type="text" value='+_a+' class="form-unit see"  readonly="readonly" name="sjfz" style="width: 40px;"></td>';
                }

            }
            _html += '<td><span class="see"><span class="edit1">编辑</span><span class="del">删除</span></span><span class="edit"><span class="change">保存</span></span></td>';
            _html += '</tr>';
            $(".fp-table").find("tbody").append(_html);
            $(".fp-table").find("tbody tr:last").find("select[name='task-edit']").find("option[value="+_this.find('#task').val()+"]").attr("selected",true);
            $(".fp-table").find("tbody tr:last").find("input[name='startTime-edit']").attr("ued","{ 'datepicker':'true,false,year'}");
            $(".fp-table").find("tbody tr:last").find("input[name='endTime-edit']").attr("ued","{ 'datepicker':'true,false,year'}");
            $(".fp-table").find("tbody tr:last").find("input[name='my_no']").attr("data-myId",_this.find("input[name='No']").data("myId"));
            $(".fp-table").find("tbody tr:last").find("input[name='people']").attr("data-allId",_this.find("input[name='people']").data("allId"));

            _pNo = true;
            $(".fp-table tfoot input").val("");
            $(".fp-table tfoot select option:selected").attr("selected",false);
        })

        $(".fp-table tbody .edit1").live("click",function(){
            $(this).parents("tr").find(".see").hide();
            $(this).parents("tr").find(".edit").show();
        })

        $(".fp-table tbody .change").live("click",function(){
            $(this).parents("tr").find("td").each(function(){
                if($(this).find(".edit").length>0){
                    if($(this).find(".edit")[0].tagName=="SELECT"){
                        $(this).find(".see").val($(this).find(".edit").find("option:selected").text());
                        $(this).find(".see").data("sel",$(this).find(".edit").val());
                    }else{
                        $(this).find(".see").val($(this).find(".edit").val());
                        if($(this).find(".staff").length>0){
                            $(this).find(".see").attr("data-allid",$(this).find(".edit").attr("data-allid"));
                        }
                    }
                }
            })
            $(this).parents("tr").find(".see").show();
            $(this).parents("tr").find(".edit").hide();
        })

        $(".fp-table tbody .del").live("click",function(){
            $(this).parents("tr").remove();
        })


        $(".qx-btn").click(function(){
            var project_name	   = $('#project_name').val().replace(/^\s+|\s+$/g,"");
            var customer_name 	   = $('#customer_name').val().replace(/^\s+|\s+$/g,"");
            var project_manager_id = $('#project_manager_id').val().replace(/^\s+|\s+$/g,"");
            var project_status 	   = $('#status').val();
            var comment 		   = $('#comment').val().replace(/^\s+|\s+$/g,"");
            var project_desc 	   = CKEDITOR.instances.project_desc.getData();
            <?php if ($_smarty_tpl->tpl_vars['roleId']->value==0||$_smarty_tpl->tpl_vars['roleId']->value==2){?>
            var contract_no 	   = $('#contract_no').val().replace(/^\s+|\s+$/g,"");
            var contract_amount    = $('#contract_amount').val().replace(/^\s+|\s+$/g,"");
            var real_amount 	   = $('#real_amount').val().replace(/^\s+|\s+$/g,"");
            var other_amount 	   = $('#other_amount').val().replace(/^\s+|\s+$/g,"");
            //var feedback 	   	   = CKEDITOR.instances.feedback.getData();
			<?php }?>
			if (project_name == '') {
                showInfo("error","请填写项目名称！");
				$('#project_name').focus();
				return;
			}

			if (customer_name == '') {
                showInfo("error","请填写客户名称！");
				$('#customer_name').focus();
				return;
			} else if (customer_name.length > 6) {
				showInfo("error","客户名称最多6个字符！");
				$('#customer_name').focus();
				return;
			}

			if (project_manager_id == '' || project_manager_id == '0') {
                showInfo("error","请选择项目经理！");
				$('#project_manager').focus();
				return;
			}

			if (project_status == '') {
                showInfo("error","请选择项目状态！");
				$('#status').focus();
				return;
			}

			if (comment != '' && comment.length > 120) {
                showInfo("error","备注说明最多120个字符！");
				$('#comment').focus();
				return;
			}
			/*if (project_desc != '' && project_desc.length > 1000) {
                showInfo("error","项目描述最多1000个字符！");
				$('#project_desc').focus();
				return;
			}
            if (contract_no == '') {
				alert('请填写合同号');
				$('#contract_no').focus();
				return;
			}
			if (contract_amount == '') {
				alert('请填写合同金额');
				$('#contract_amount').focus();
				return;
			}
			if (real_amount == '') {
				alert('请填写实际收款');
				$('#real_amount').focus();
				return;
			}
			if (other_amount == '') {
				alert('请填写第三方费用');
				$('#other_amount').focus();
				return;
			}*/
            var personnel_list = [];
            $(".fp-table tbody tr").each(function(e){
                    var _yy = {};
                    _yy.user_no = $(this).find("td:eq(1)").find("input").val();
                    _yy.user_no_id = $(this).find("input[name='my_no']").attr("data-myId");
                    _yy.user_id = $(this).find("input[name='people']").attr("data-allId");
                    for(var i in typeList){
                        if(typeList[i]==$(this).find("td:eq(3)").find("input[name='task']").val()){
                            _yy.user_type = i;
                        }
                    }

                    _yy.start_time = $(this).find("td:eq(4)").find("input[name='startTime']").val();
                    _yy.end_time = $(this).find("td:eq(5)").find("input[name='endTime']").val();
                    _yy.work_unit = $(this).find("td:eq(7)").find("input[name='dy']").val();
                    _yy.plan_score = $(this).find("td:eq(8)").find("input[name='jhfz']").val();
                    if($(this).find("td:eq(9)").find("input[name='sjfz']").val()=="--" || $(this).find("td:eq(9)").find("input[name='sjfz']").val()== undefined){
                        _yy.real_score = "";
                    }else{
                        _yy.real_score = $(this).find("td:eq(9)").find("input[name='sjfz']").val();
                    }
                    personnel_list.push(_yy);
            })

			/*if (feedback != '' && feedback.length > <?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['feedbackLen'];?>
) {
                showInfo("error","修改意见最多<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['feedbackLen'];?>
个字符！");
				$('#feedback').focus();
				return;
			}*/

            if($(".write .error").length<=0){
                $.post('?task=update&t=' + Math.random(), {
                    project_no:'<?php echo $_smarty_tpl->tpl_vars['detail']->value['project_no'];?>
',
                    project_name:project_name,
                    customer_name:customer_name,
                    manager_id:project_manager_id,
                    status:project_status,
                    comment:comment,
                    project_desc:project_desc,
                    <?php if ($_smarty_tpl->tpl_vars['roleId']->value==0||$_smarty_tpl->tpl_vars['roleId']->value==2){?>
                    contract_no:contract_no,
                    contract_amount:contract_amount,
                    real_amount:real_amount,
                    other_amount:other_amount,
                    <?php }?>
                    personnel_list:personnel_list,
                    formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
',
                    project_id:'<?php echo $_smarty_tpl->tpl_vars['detail']->value['project_id'];?>
'
                    //feedback:feedback
                }, function(data){
                    if (data.status == 'SUCCESS') {
                        showInfo("success", "您修改的项目已成功！", function(){
                            window.location.reload();
                        });
                    }else{
                        showInfo("error", data.msg);
                    }
                }, 'json');
            }
        });

		$('.create_project-no').live("click", function(){
			$.post('?task=get_projectno&t=' + Math.random(), {
					location:$('#location').val(),
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){
				if (data.status == 'SUCCESS') {
                    $(".create_project-no").css("color","#989898");
                    $(".create_project-no").removeClass("create_project-no");
					$('#project_no').val(data.project_no);
				}
			}, 'json');
		});


		$('.create_user-no').live('click', function(){
            var _this = $(this);
			$.post('?task=get_userno&t=' + Math.random(), {
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){
				if (data.status == 'SUCCESS') {
                    _this.css("color","#989898");
                    _this.removeClass("create_user-no");
					_this.siblings("input[name^='NO']").val(data.user_no);
					_this.siblings("input[name^='NO']").data("myId", data.user_no_id)
                    //$('#project_no').val(data.user_no);
				} else if(data.user_no == 0) {
                    showInfo("error", data.msg);
                }
			}, 'json');
		});

		<?php if ($_smarty_tpl->tpl_vars['roleId']->value=='0'){?>
		$('.deluser').live('click', function(){
			if (!confirm('删除不可恢复，确定要删除么？')) {
				return;
			}

			var _this = $(this);
			$.post('?task=del_user&t=' + Math.random(), {
					product_id:'<?php echo $_smarty_tpl->tpl_vars['detail']->value['project_id'];?>
',
					product_no:'<?php echo $_smarty_tpl->tpl_vars['detail']->value['project_no'];?>
',
					job_no:_this.attr('job_no'),
					user_id:_this.attr('user_id'),
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){
				if (data.status == 'SUCCESS') {
                    _this.parents("tr").remove();
				} else {
                    showInfo("error", data.msg);
                }
			}, 'json');
		});

		/*$('.delfeedback').live('click', function(){
			if (!confirm('删除不可恢复，确定要删除么？')) {
				return;
			}

			var _this = $(this);
			$.post('?task=del_feedback&t=' + Math.random(), {
					project_id:_this.attr('project_id'),
					feedback_id:_this.attr('feedback_id'),
					formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
				}, function(data){
				if (data.status == 'SUCCESS') {
                    _this.parent().remove();
				} else {
                    showInfo("error", data.msg);
                }
			}, 'json');
		});*/
		<?php }?>
    });
</script>
</body>
</html>
<?php }} ?>