<?php /* Smarty version Smarty-3.1.12, created on 2019-05-19 00:31:56
         compiled from "/usr/local/web/test/pm/easyku/site/template/view/user.html" */ ?>
<?php /*%%SmartyHeaderCode:20326591815ce0337cb50221-41132232%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9d1279502c37e54306a6bd530bf0c8e53b7bdf97' => 
    array (
      0 => '/usr/local/web/test/pm/easyku/site/template/view/user.html',
      1 => 1467814971,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20326591815ce0337cb50221-41132232',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'username' => 0,
    'itemList' => 0,
    'row' => 0,
    'child' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5ce0337cbfb5e0_46369953',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ce0337cbfb5e0_46369953')) {function content_5ce0337cbfb5e0_46369953($_smarty_tpl) {?><!DOCTYPE html>
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
                        <span class="task">
                            <span class="no_complete on"><a href="/user.php">当前任务</a></span><span class="my_complete"><a href="/user.php?task=completed">执行情况</a></span>
                        </span>

                        <dl class="header-infos">
                            Hi，<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 <a href="javascript:void(0)" class="refresh">refresh</a> <a href="/?task=logout" class="out">退出</a>
                        </dl>
                    </div>
                </div>

                <div class="main">
                    <div class="makepeople-main">
                        <span class="mp-title">
                            工单号<br />Serial number
                        </span>
                        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                        <span class="task" is_read="<?php echo $_smarty_tpl->tpl_vars['row']->value['is_read'];?>
"><span class="job_id"><?php echo $_smarty_tpl->tpl_vars['row']->value['job_no'];?>
</span><span class="state"><?php if ($_smarty_tpl->tpl_vars['row']->value['is_read']=='1'){?>已读<?php }else{ ?>未读<?php }?></span></span>
						<?php } ?>
                    </div>
                    <div class="content">
                        <div class="main-business-bottom">
                            <div class="main-business-right">
							<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['itemList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
                               <div class="make-con-main">
                                    <span class="arrow"></span>
                                   <table>
                                       <tr>
                                           <td width="180">项目编号<br />project number</td>
                                           <td><?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
</td>
                                       </tr>
                                       <tr>
                                           <td>任务类型<br />task type</td>
                                           <td><?php echo $_smarty_tpl->tpl_vars['row']->value['type'];?>
</td>
                                       </tr>
                                       <tr>
                                           <td>项目名称<br />project name</td>
                                           <td class="red"><?php echo $_smarty_tpl->tpl_vars['row']->value['project_name'];?>
</td>
                                       </tr>
                                       <tr>
                                           <td>客户名称<br />customer name</td>
                                           <td><?php echo $_smarty_tpl->tpl_vars['row']->value['customer_name'];?>
</td>
                                       </tr>
                                       <tr>
                                           <td>项目描述<br />project description</td>
                                           <td><?php echo $_smarty_tpl->tpl_vars['row']->value['project_desc'];?>
</td>
                                       </tr>
                                       <tr>
                                           <td>开始日期<br />start date</td>
                                           <td><?php echo $_smarty_tpl->tpl_vars['row']->value['start_date'];?>
</td>
                                       </tr>
                                       <tr>
                                           <td>项目资料<br />project info</td>
                                           <td>
											  <?php if (!empty($_smarty_tpl->tpl_vars['row']->value['attachment'])){?>
											  <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['row']->value['attachment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['child']->key;
?>
											   <a href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['child']->value['name'];?>
</a>
											  <?php } ?>
											  <?php }?>
										   </td>
                                       </tr>

										<tr>
                                           <td>友情提示<br />notice</td>
                                           <td>
											  如上传单个文件超过800M，则建议登录服务器共享文件夹进行手动上传，手动上传路径地址：<font color="red">D:\projects\<?php echo $_smarty_tpl->tpl_vars['row']->value['project_no'];?>
\<?php echo $_smarty_tpl->tpl_vars['row']->value['job_no'];?>
\</font>
										   </td>
                                       </tr>

                                   </table>
                                   <div class="make-undate ajax-updata-number">
										<div class="b-main rel">
											<div class="btnUpload"></div>
										</div>
									</div>
                                   <div class="make-undate over">
                                       <div class="b-main">
                                           <a class="ajax-complete complete" style="margin-left:0px; width:105px; height:36px;" href="javascript:void (0)"><span>提 交<br /> Submit</span></a>
                                       </div>
                                   </div>
                               </div>
							<?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="push"></div>
</div>

<div class="b-main rel dis gd-demo">
	<input type="file" id="aj-file" name="aj-file">
	<span class="q-title w-120">上传任务 <br /> Upload task</span>
	<input type="text" class="form-unit w-350">
    <span class="control">
        <span class="file-clear">&nbsp;</span>
    </span>
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

<script src="/js/vendor/jquery-1.8.2.min.js"></script>
<script src="/js/plugins.min.js"></script>
<script src="/js/matrix.min.js"></script>
<script src="/js/all.js"></script>
<script src="/js/main.js"></script>
<script src="js/interval.js"></script>
<script src="js/vendor/jquery.fineuploader-3.7.1.min.js"></script>
<script>
    var _no = "";
    var _data = [];
    $(function () {
		$('.refresh').click(function(){
			window.location.href = '?t=' + Math.random();
		});

		$('.btnUpload').fineUploader({
			request: {
				endpoint: '?task=upload'
			},
			//validation: {
			//    allowedExtensions: ['jpeg', 'jpg', 'png']
			//},
			multiple: true,
			text: {
				uploadButton: '<div>上传任务<br>Upload task</div>'
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
                _data[id]=fileName;
			}
            console.log(_data)
		});

		$(".qq-upload-delete").live("hover",function(){
            //console.log($(this).parents(".qq-upload-success").index())
			_file = $(this).siblings(".qq-upload-file").text();
		});

        $(".pp-add").click(function(){
            BOX_show('tcdiv');
        })
        $(".list a").click(function(){
            BOX_show('chdiv');
        })

        $(".make-undate input[type='file']").live("change",function(){
            $(this).siblings("input").val($(this).val());
            $(this).siblings(".ajax-update").addClass("on");
        })

        $(".makepeople-main .task").each(function(e){
            $(this).data("id",e);
            $(".make-con-main:eq("+e+")").find(".arrow").css("top",130*(e+1)+"px");
            $(".make-con-main:eq("+e+")").find(".btnUpload").data("jobNo",$(this).find(".job_id").text())
        })
        $(".makepeople-main .task").click(function () {
            $(this).find(".state").text("已读");
            $(".makepeople-main span").removeClass("on");
            _no = $(this).find(".job_id").text();
            $(this).addClass("on");
            $(".make-con-main").hide();
            $(".make-con-main:eq("+$(this).data("id")+")").fadeIn();

            if($(this).data("read")==undefined){
                var _this = $(this);
                $.post('?task=read', {
                    job_no:_no,
                    formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
                }, function(data){
                    if (data.status == 'SUCCESS') {
                        _this.data("read","1");
                    }
                }, 'json');
            }
        })
        if($(".makepeople-main .task")[0]){
            $(".makepeople-main .task")[0].click();
        }
        $(".ajax-updata-number .ajax-update").live("click",function(){
            var _id = $(this).parents(".b-main").find("input[type='file']").attr("id");
            ajaxFileUpload(_id,"?task=upload");
        })
        $(".complete").click(function(){
            showInfo("success","请仔细确认您上传的文件是否正确！点击确认后，该工单号任务将默认完成并自动消失",function(){
                $.post('?task=finished', {
                    job_no:_no,
                    formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
                }, function(data){
                    if (data.status == 'SUCCESS') {
                        window.location.reload();
                        // 刷新页面
                    }else{
                        alert(data.msg);
                        BOX_remove('popdiv')
                    }
                }, 'json');
            })

        })
    });
</script>
</body>
</html><?php }} ?>