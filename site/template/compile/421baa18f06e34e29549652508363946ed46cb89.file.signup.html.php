<?php /* Smarty version Smarty-3.1.12, created on 2019-09-03 11:15:41
         compiled from "/usr/local/web/test/pm/easyku/site/template/login/signup.html" */ ?>
<?php /*%%SmartyHeaderCode:9185494685d651de9d97098-77192578%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '421baa18f06e34e29549652508363946ed46cb89' => 
    array (
      0 => '/usr/local/web/test/pm/easyku/site/template/login/signup.html',
      1 => 1567476463,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9185494685d651de9d97098-77192578',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5d651de9dd33e5_56790990',
  'variables' => 
  array (
    'title' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d651de9dd33e5_56790990')) {function content_5d651de9dd33e5_56790990($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="#">
    <link rel="stylesheet" href="/css/login_.css">
    <script src="/vendor/jquery-1.12.3.min.js"></script>
    <style>
        .thy-form-vertical .tip {
            display: none;
        }
    </style>
    
    <script>
        $(function(){
            //获取短信验证码
            $(".getCode").click(function(){
                if(!$(this).attr("disabled")){
                    var vm=this;
                    var count=59;
                    $(this).attr("disabled",true).html("剩余60秒");
                    var timer=setInterval(function(){
                        $(vm).html("剩余"+count+"秒");
                        count--;
                        if(count ==-1){
                            clearInterval(timer);
                            $(vm).attr("disabled",false).html("获取短信验证码");
                        }
                    },1000)
                }
                var mobile = $(".mobile>input").val();
                var formhash = $(".formhash").val();
                $.post('?script=register&task=sendsms',{phone: mobile, smstype: 1, formhash: formhash},
                    function(data) {
                        data = JSON.parse(data);
                        if(data.status == '1') {
                            return;
                        } else {
                            //ajax返回错误
                            $(".tip").show();
                            $(".tip span.active").html(data.msg);
                            return false;
                        }

                    }
                );
            });
            //下一步
            $(".next").click(function(){
                var mobile = $(".mobile>input").val();
                var code = $(".code input").val();
                var reg = /^1\d{10}$/g;
                if (!mobile) {
                    $(".mobile").addClass("is-invalid");
                    $(".mobile").parent().find(".invalid-feedback").html("请输入手机号码");
                    return false;
                }
                if (!reg.test(mobile)) {
                    $(".mobile").addClass("is-invalid");
                    $(".mobile").parent().find(".invalid-feedback").html("手机号格式不正确");
                    return false;
                }
                if (!code) {
                    $(".code>div:eq(0)").addClass("is-invalid");
                    $(".code>.invalid-feedback").html("请填写短信验证码");
                    return false;
                }

                var formhash = $(".formhash").val();
                $.post('?script=register&task=index',{phone: mobile, verCode: code, formhash: formhash},
                    function(data) {
                        data = JSON.parse(data);
                        if(data.status == 'LOGIN_SUCCESS') {
                            //登陆成功跳转管理内容页面
                            window.location.href = "/register/index.php?task=register";
                        } else {
                            //ajax返回错误
                            $(".tip").show();
                            $(".tip span.active").html(data.msg);
                            return false;
                        }
                    }
                );

            });

            $(document).on("input",".mobile input",function(){
                var phone=$(this).val();
                var reg = /^1\d{10}$/g;
                if(reg.test(phone)){
                    $(".getCode").attr("disabled",false);
                }else{
                    $(".getCode").attr("disabled",true);
                }
                $(this).parent().removeClass("is-invalid");
                $(".tip").hide();
            });
            $(document).on("input",".code input",function(){
                $(this).parent().removeClass("is-invalid");
                $(".tip").hide();
            });

            $(document).on("focus",".mytip",function(){
                $(this).parent().find(".input-label").addClass("active");
                $(this).parent().addClass("form-control-active");
            });
            $(document).on("blur",".mytip",function(){
                $(this).parent().find(".input-label").removeClass("active");
                $(this).parent().removeClass("form-control-active");
            });
        });
    </script>
    
</head>

<body>
        <div class="single-wrap">
            <div class="single-main">
                <div class="single-header"><a class="logo-section" href="#"><img
                        class="logo-default" src="/images/logo-default.png"></a></div>
                <div class="single-body">
                    <div class="single-card">
                        <div class="single-card-title"> 30秒注册企业</div>
                        <form name="signupPhoneForm" class="thy-form thy-form-vertical ng-untouched ng-pristine ng-invalid">
                            <input type="hidden" name="formhash" class="formhash" value="<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
">
                            <div>
                                <div class="form-group">
                                    <div name="mobile" class="thy-input form-control mobile">
                                        <span class="input-label input-label-lg">手机号</span>
                                        <input class="form-control-lg  form-control mytip" maxlength="11" type="text" placeholder="请输入手机号">
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mobile-code-form-group clearfix form-group code">
                                    <div name="code" class="thy-input form-control">
                                        <span class="input-label input-label-lg">短信验证码</span>
                                        <input class="form-control-lg form-control mytip" type="text" maxlength="6" placeholder="请输入短信验证码">
                                        <div class="input-append">
                                            <div><a class="mobile-code-action" href="javascript:;">
                                                <button type="button" class="font-size-sm btn btn-primary btn-sm btn-square getCode" disabled>
                                                    获取短信验证码
                                                </button>
                                            </a></div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="mt-3 form-group tip">
                                <thy-alert thytype="danger">
                                    <div class="thy-alert thy-alert-danger"><span
                                            class="wtf mr-2 wtf-times-circle"></span>
                                        <span class="active"></span></div>
                                </thy-alert>
                            </div>
                            <div class="single-card-actions text-center form-group">
                                <div class="btn-pair">
                                    <button class="btn-block single-btn btn btn-primary btn-square next" type="button">
                                         下一步
                                    </button>
                                </div>
                            </div>
                            <div class="single-card-actions-desc form-group">
                                <div class="btn-pair"> 或 <a href="?script=login">登录企业</a>
                                </div>
                            </div>
                        </form></div></div>
            </div>
            <div class="single-footer">© 2019 easyku.com<br>
                沪ICP备15051032号-1
            </div>
        </div>
</body>
</html><?php }} ?>