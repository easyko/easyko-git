<?php /* Smarty version Smarty-3.1.12, created on 2019-05-06 21:33:26
         compiled from "/usr/local/web/test/pm/site/template/view/project_create.html" */ ?>
<?php /*%%SmartyHeaderCode:5404478825cd037a6635463-80822898%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e147815e431a8f7531832e3b2c5afe43ca79f9f8' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/project_create.html',
      1 => 1556944773,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5404478825cd037a6635463-80822898',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'locationList' => 0,
    'i' => 0,
    'row' => 0,
    'lenLimitList' => 0,
    'roleId' => 0,
    'username' => 0,
    'userId' => 0,
    'projectStat' => 0,
    'managerList' => 0,
    'userList' => 0,
    'typeList' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5cd037a6769a92_52499221',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd037a6769a92_52499221')) {function content_5cd037a6769a92_52499221($_smarty_tpl) {?><!DOCTYPE html>
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
                                                    <input type="text" class="form-unit w-265" name="project_no" id="project_no" readonly />
                                                    <select class="form-unit w-141 interval-select" name="location" id="location">
                                                        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['locationList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                                                        <option value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['cn'];?>
（<?php echo $_smarty_tpl->tpl_vars['row']->value['en'];?>
）</option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="dd create_project-no">点击生成</span>
                                                </div>
                                                <br/>

                                                <div class="b-main">
                                                    <span class="q-title w-120"><span class="dd">*</span> 项目名称：</span>
                                                    <input type="text" class="form-unit w-265" name="project_name" id="project_name">
                                                </div>
                                                <br/>

                                                <div class="b-main">
                                                    <span class="q-title w-120"><span class="dd">*</span> 客户名称：</span>
                                                    <input type="text" class="form-unit w-265" name="customer_name" id="customer_name" placeholder="限<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['customerNameLen'];?>
个字符" maxlength="<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['customerNameLen'];?>
">
                                                </div>
                                                <br/>

                                                <div class="b-main">
                                                    <span class="q-title w-120"><span class="dd">*</span> 开始日期：</span>
                                                    <input type="text" class="form-unit w-265" ued="{ 'datepicker':'true,false,year'}" name="start_date" id="start_date">
                                                </div>
                                                <br/>

                                                <div class="b-main">
                                                    <span class="q-title w-120"><span class="dd">*</span> 项目经理：</span>
                                                    <?php if ($_smarty_tpl->tpl_vars['roleId']->value=='2'){?>
                                                    <?php echo $_smarty_tpl->tpl_vars['username']->value;?>

                                                    <?php }else{ ?>
                                                    <input type="text" class="form-unit w-265 manager" name="project_manager" id="project_manager">
                                                    <?php }?>
                                                    <input type="hidden" name="project_manager_id" id="project_manager_id" value="<?php if ($_smarty_tpl->tpl_vars['userId']->value){?><?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
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
"><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="b-main">
                                                    <span class="q-title w-120">备注说明：</span>
                                                    <textarea class="form-unit" style="width:612px; height:50px;" placeholder="备注说明" name="comment" id="comment"></textarea>
                                                </div>
                                            </div>

                                            <div class="qx-cs write">
                                                <div class="b-con">
                                                    <span class="q-title w-120 name"><span class="dd">*</span> 分配执行人员：</span>
                                                <span class="fp-table">
                                                    <table style="">
                                                        <thead>
                                                        <tr>
                                                            <td>提交文件</td>
                                                            <td>工单号</td>
                                                            <td>执行人员</td>
                                                            <td>任务类型</td>
                                                            <td width="80">计划开始时间</td>
                                                            <td width="80">计划结束时间</td>
                                                            <td width="80">实际完成时间</td>
                                                            <td>工作单元</td>
                                                            <td>计划分值</td>
                                                            <td>实际分值</td>
                                                            <td>操作</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td>--</td>
                                                            <td><input type="text" placeholder="自动生成" class="form-unit" readonly="readonly"  name="No" id="No"></td>
                                                            <td><input type="text" placeholder="选择姓名" class="form-unit staff" name="people" id="people"></td>
                                                            <td>
                                                                <select class="form-unit interval-select" id="task" value=""  name="task" id="task" style="width: 90px">
                                                                    <option value="">任务类型</option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" placeholder="选择时间" class="form-unit" ued="{ 'datepicker':'true,false,year'}" name="startTime" id="startTime"></td>
                                                            <td><input type="text" placeholder="选择时间" class="form-unit" ued="{ 'datepicker':'true,false,year'}" name="endTime" id="endTime"></td>
                                                            <td>--</td>
                                                            <td><input type="text"  placeholder="请输入" class="form-unit" style="width: 40px;" name="dy" id="dy"  maxlength="4"></td>
                                                            <td><input type="text"  placeholder="请输入" class="form-unit" style="width: 40px;" name="jhfz" id="jhfz"  maxlength="4"></td>
                                                            <td>--</td>
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
                                                    <textarea class="form-unit" style="width:612px; height:150px;" placeholder="请输入项目背景或要求" name="project_desc" id="project_desc"></textarea>
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
                                                <div class="update-ht">
                                                    <div class="b-main rel">
                                                        <span class="q-title w-120" style="vertical-align:-10px;"> 合同：</span>
                                                        <div style="display: inline-block; vertical-align: top; width: 700px">
                                                            <div class="btnUpload1"></div>
                                                        </div>
                                                    </div>
                                                    <br/>
                                                </div>
                                                <div class="update-ht">
                                                    <div class="b-main rel">
                                                        <span class="q-title w-120" style="vertical-align:-10px;"> 项目提案：</span>
                                                        <div style="display: inline-block; vertical-align: top; width: 700px">
                                                            <div class="btnUpload2"></div>
                                                        </div>
                                                    </div>
                                                    <br/>
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
                                                <div class="b-main">
                                                    <span class="q-title w-120">合同号：</span>
                                                    <input type="text" class="form-unit w-265" name="contract_no" id="contract_no">
                                                </div>
                                                <br/>
                                                <div class="b-main">
                                                    <span class="q-title w-120">合同金额：</span>
                                                    <input type="text" class="form-unit w-265" name="contract_amount" id="contract_amount">
                                                </div>
                                                <br/>
                                                <div class="b-main">
                                                    <span class="q-title w-120">实际收款：</span>
                                                    <input type="text" class="form-unit w-265" name="real_amount" id="real_amount">
                                                </div>
                                                <br/>
                                                <div class="b-main">
                                                    <span class="q-title w-120">第三方费用：</span>
                                                    <input type="text" class="form-unit w-265" name="other_amount" id="other_amount">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="qx-btn">
                                        <a href="javascript:void (0)" class="btn btn-primary btn-submit btn-c"><span>创 建</span></a>
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
<script src="/js/vendor/jquery.fineuploader-3.7.1.min.js"></script>
<script src="/site/plugin/editor/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/site/plugin/editor/jquery.ckeditor.js" type="text/javascript"></script>
<script>
    var staffNow = "";
    var _no = "";
    var _pNo = true;
    $(function() {
        CKEDITOR.replace('project_desc', {
            width:622,
            height:150,
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

        $("#project_desc").html("<B><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>月<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>日&nbsp;&nbsp;工单号：<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></B><br /><B>一.项目目标：</B>&nbsp;<br /><B>二.项目背景：</B>&nbsp;<br /><B>三.项目内容：</B>&nbsp;<br /><B>四.提案要求：</B>&nbsp;<br /><B>五.注意事项：</B>&nbsp;");

        var typeList = eval(<?php echo $_smarty_tpl->tpl_vars['typeList']->value;?>
);
        var _txt = "";
        for(var i in typeList){
            _txt += "<option value="+i+">"+typeList[i]+"</option>"
        }

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

        $(".qq-upload-delete").live("hover",function(){
            _file = $(this).siblings(".qq-upload-file").text();
        });

        $(".b-con select").append(_txt);
        $(".manager").click(function(){
            $("#managerdiv .on").removeClass("on")
            BOX_show("managerdiv");
        });
        $(".staff").live("click",function(){
            $("#staffdiv .on").removeClass("on");
            staffNow = $(this);
            BOX_show("staffdiv");
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

            staffNow.data("allId",_myself.data("id"));
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

            var _html = '<tr><td>--</td>';
            _html += '<td><input type="text" value='+_this.find('#No').val()+' class="form-unit" readonly="readonly" style="border: 0px;"  name="my_no"></td>';
            _html += '<td><input type="text" value='+_this.find('.staff').val()+' class="form-unit edit staff" name="people-edit"><input type="text" value='+_this.find('.staff').val()+' class="form-unit see"  readonly="readonly" name="people"></td>';
            var _div = _this.find('#task').clone().html();
            _html += '<td><select style="width: 90px; " name="task-edit" value="" class="form-unit interval-select edit">'+_div+'</select><input type="text" value='+_this.find('#task').find("option:selected").text()+' class="form-unit see"  readonly="readonly" name="task"></td>';
            _html += '<td><input type="text" value='+_this.find('#startTime').val()+' class="form-unit edit" name="startTime-edit"><input type="text" value='+_this.find('#startTime').val()+' class="form-unit see"  readonly="readonly" name="startTime"></td>';
            _html += '<td><input type="text" value='+_this.find('#endTime').val()+' class="form-unit edit" name="endTime-edit"><input type="text" value='+_this.find('#endTime').val()+' class="form-unit see"  readonly="readonly" name="endTime"></td>';
            _html += '<td>--</td>';
            _html += '<td><input type="text" value='+_this.find('#dy').val()+' class="form-unit edit" name="dy-edit"  maxlength="4" style="width: 40px;"><input type="text" value='+_this.find('#dy').val()+' class="form-unit see"  readonly="readonly" name="dy" style="width: 40px;"></td>';
            _html += '<td><input type="text" value='+_this.find('#jhfz').val()+' class="form-unit edit" name="jhfz-edit"   maxlength="4" style="width: 40px;"><input type="text" value='+_this.find('#jhfz').val()+' class="form-unit see"  readonly="readonly" name="jhfz" style="width: 40px;"></td>';
            _html += '<td>--</td>';
            _html += '<td><span class="see"><span class="edit1">编辑</span><span class="del">删除</span></span><span class="edit"><span class="change">保存</span></span></td>';
            _html += '</tr>';
            $(".fp-table").find("tbody").append(_html);
            $(".fp-table").find("tbody tr:last").find("select[name='task-edit']").find("option[value="+_this.find('#task').val()+"]").attr("selected",true);
            $(".fp-table").find("tbody tr:last").find("input[name='startTime-edit']").attr("ued","{ 'datepicker':'true,false,year'}");
            $(".fp-table").find("tbody tr:last").find("input[name='endTime-edit']").attr("ued","{ 'datepicker':'true,false,year'}");
            $(".fp-table").find("tbody tr:last").find("input[name='my_no']").data("myId",_this.find("input[name='No']").data("myId"))
            $(".fp-table").find("tbody tr:last").find("input[name='people']").data("allId",_this.find("input[name='people']").data("allId"))


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
                    }else{
                        $(this).find(".see").val($(this).find(".edit").val());
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
            var project_no 		   = $('#project_no').val().replace(/^\s+|\s+$/g,"");
            var location 		   = $('#location').val();
            var project_name	   = $('#project_name').val().replace(/^\s+|\s+$/g,"");
            var customer_name 	   = $('#customer_name').val().replace(/^\s+|\s+$/g,"");
            var start_date 		   = $('#start_date').val();
            var project_manager_id = $('#project_manager_id').val().replace(/^\s+|\s+$/g,"");
            var project_status 	   = $('#status').val();
            var comment 		   = $('#comment').val().replace(/^\s+|\s+$/g,"");
            var project_desc 	   = CKEDITOR.instances.project_desc.getData();
            var contract_no 	   = $('#contract_no').val().replace(/^\s+|\s+$/g,"");
            var contract_amount    = $('#contract_amount').val().replace(/^\s+|\s+$/g,"");
            var real_amount 	   = $('#real_amount').val().replace(/^\s+|\s+$/g,"");
            var other_amount 	   = $('#other_amount').val().replace(/^\s+|\s+$/g,"");
            //var feedback 	   	   = CKEDITOR.instances.feedback.getData();

            if (project_no == '') {
                showInfo("error","请点击生成项目编号！");
                $('#project_no').focus();
                return;
            }

            if (project_name == '') {
                showInfo("error","请填写项目名称！");
                $('#project_name').focus();
                return;
            }

            if (customer_name == '') {
                showInfo("error","请填写客户名称！");
                $('#customer_name').focus();
                return;
            } else if (customer_name.length > <?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['customerNameLen'];?>
) {
                showInfo("error","客户名称最多<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['customerNameLen'];?>
个字符！");
                $('#customer_name').focus();
                return;
            }

            if (start_date == '') {
                showInfo("error","请选择开始日期！");
                $('#start_date').focus();
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

            /*if (comment != '' && comment.length > <?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['commentLen'];?>
) {
                showInfo("error","备注说明最多<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['commentLen'];?>
个字符！");
                $('#project_no').focus();
                return;
            }
            if (project_desc != '' && project_desc.length > <?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['projectDescLen'];?>
) {
             showInfo("error","项目描述最多<?php echo $_smarty_tpl->tpl_vars['lenLimitList']->value['projectDescLen'];?>
个字符！");
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
                _yy.user_no_id = $(this).find("input[name='my_no']").data("myId");
                _yy.user_id = $(this).find("input[name='people']").data("allId");
                for(var i in typeList){
                    if(typeList[i]==$(this).find("td:eq(3)").find("input[name='task']").val()){
                        _yy.user_type = i;
                    }
                }
                 //_yy.user_type = $(this).find("td:eq(3)").find("input[name='task']").val();
                 _yy.start_time = $(this).find("td:eq(4)").find("input[name='startTime']").val();
                 _yy.end_time = $(this).find("td:eq(5)").find("input[name='endTime']").val();
                 _yy.work_unit = $(this).find("td:eq(7)").find("input[name='dy']").val();
                 _yy.plan_score = $(this).find("td:eq(8)").find("input[name='jhfz']").val();
                 _yy.real_score = "";


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
                $.post('?task=insert&t=' + Math.random(), {
                    project_no:project_no,
                    location:location,
                    project_name:project_name,
                    customer_name:customer_name,
                    start_date:start_date,
                    manager_id:project_manager_id,
                    status:project_status,
                    comment:comment,
                    project_desc:project_desc,
                    contract_no:contract_no,
                    contract_amount:contract_amount,
                    real_amount:real_amount,
                    other_amount:other_amount,
                    personnel_list:personnel_list,
                    formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
                    //feedback:feedback
                }, function(data){
                    if (data.status == 'SUCCESS') {
                        $('form')[0].reset();
                        showInfo("success", "您创建的项目已成功！", function(){
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
                    _no = data.project_no;
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
    });
</script>
</body>
</html>
<?php }} ?>