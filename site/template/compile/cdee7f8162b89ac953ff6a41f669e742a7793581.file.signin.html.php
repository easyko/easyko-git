<?php /* Smarty version Smarty-3.1.12, created on 2019-08-14 15:23:47
         compiled from "F:\www\easyku_src\site\template\login\signin.html" */ ?>
<?php /*%%SmartyHeaderCode:293475d53b7038f7cb5-85890207%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cdee7f8162b89ac953ff6a41f669e742a7793581' => 
    array (
      0 => 'F:\\www\\easyku_src\\site\\template\\login\\signin.html',
      1 => 1565767262,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '293475d53b7038f7cb5-85890207',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5d53b703931e72_22948254',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d53b703931e72_22948254')) {function content_5d53b703931e72_22948254($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="icon" type="image/x-icon" href="#">-->
    <link rel="stylesheet" href="/css/login_.css">
    <script src="/vendor/jquery-1.12.3.min.js"></script>
    <style>
        .thy-form-vertical .tip {
            display: none;
        }

        .wtf-pwd-invisible.active:before {
            content: "\e73d";
        }
        .phoneLogin{
            display: none;
        }
        .d-flex .w-100{
            height: 44px;
        }
        .d-flex{
            position: relative;
        }
        .tu-feedback{
            position: absolute;
            top:42px;
        }
    </style>
    
    <script>
        $(function () {
            //账号登录和手机登录切换
            $(".loginTab").click(function(){
                if($(this).hasClass("active")){ //切换成手机登录
                    $(this).removeClass("active").html("账号密码");
                    $(".phoneLogin").show();
                    $(".accountLogin").hide();
                } else{  //切换成账号登录
                    $(this).addClass("active").html("手机验证码");
                    $(".phoneLogin").hide();
                    $(".accountLogin").show();
                }
            });
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
            });
            //点击登录
            $(".signin").click(function () {
                if($(".loginTab").hasClass("active")){  //通过账号登录
                    var userAccount = $(".userAccount").val();
                    var password = $(".password").val();
                    var reg = /^1\d{10}$/g;
                    if (!userAccount) {
                        $(".userNames").addClass("is-invalid");
                        $(".userNames").parent().find(".invalid-feedback").html("请输入手机号码");
                        return false;
                    }
                    if (!reg.test(userAccount)) {
                        $(".userNames").addClass("is-invalid");
                        $(".userNames").parent().find(".invalid-feedback").html("手机号格式不正确");
                        return false;
                    }
                    if (!password) {
                        $(".password").parent().addClass("is-invalid");
                        $(".pass-feedback").html("请输入密码");
                        return false;
                    }
                }else{ //通过手机号登录
                    var mobile = $(".mobile>input").val();
                    var captcha = $(".captcha>input").val();
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
                    if (!captcha) {
                        $(".captcha").addClass("is-invalid");
                        $(".captcha").parent().find(".invalid-feedback").html("请填写图形验证码");
                        return false;
                    }
                    if (!code) {
                        $(".code>div:eq(0)").addClass("is-invalid");
                        $(".code>.invalid-feedback").html("请填写短信验证码");
                        return false;
                    }

                }

                var formhash = $(".formhash").val();
                $.post('/login/index.php?script=login&task=login',{name: "13809043282", passwd: "xudongyang", formhash: formhash},
                    function(data) {
                        data = JSON.parse(data);
                        if(data.status == 'LOGIN_SUCCESS') {
                            //登陆成功跳转管理内容页面
                            window.location.href = "../main/index.html";
                        }
                        //ajax返回错误
                        $(".tip").show();
                        $(".tip span.active").html(data.msg);
                        return false;

                    }
                );

            });
            //取消提示框
            $(document).on("input", ".userAccount", function () {
                $(this).parent().removeClass("is-invalid");
                $(".tip").hide();
            });
            $(document).on("input", ".password", function () {
                $(this).parent().removeClass("is-invalid");
                $(".tip").hide();
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
            $(document).on("input",".captcha input",function(){
                $(this).parent().removeClass("is-invalid");
                $(".tip").hide();
            });
            $(document).on("input",".code input",function(){
                $(this).parent().removeClass("is-invalid");
                $(".tip").hide();
            });

            //点击密码显示
            $(".input-append").click(function () {
                if ($(".password").attr("type") == "password") {
                    $(".wtf-pwd-invisible").addClass("active");
                    $(".password").attr("type", "text");
                } else {
                    $(".wtf-pwd-invisible").removeClass("active");
                    $(".password").attr("type", "password");
                }
            });

            $(document).on("focus",".mytip",function(){
                $(this).parent().find(".input-label").addClass("active");
                $(this).parent().addClass("form-control-active");
            });
            $(document).on("blur",".mytip",function(){
                $(this).parent().find(".input-label").removeClass("active");
                $(this).parent().removeClass("form-control-active");
            });
        })
    </script>
    
</head>
<body>
        <div class="single-wrap">
            <div class="single-main">
                <div class="single-header">
                    <a class="logo-section" href="#">
                        <img class="logo-default" src="/images/logo-default.png">
                    </a>
                </div>
                <div class="single-body">
                    <div class="single-card">
                        <div class="single-card-title">登录</div>
                        <div>
                            <div class="single-card-subtitle"> 通过您的帐号密码登录企业, 或 <a href="javascript:;" class="loginTab active">手机验证码</a>
                            </div>
                            <form name="signinForm" class="thy-form thy-form-vertical">
                                <input type="hidden" name="formhash" class="formhash" value="<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
">
                                <div class="phoneLogin">
                                    <div class="form-group">
                                        <div name="mobile" class="thy-input form-control mobile">
                                            <span class="input-label input-label-lg">手机号</span>
                                            <input class="form-control-lg ng-pristine ng-valid form-control mytip" maxlength="11" type="text" placeholder="请输入手机号">
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex">
                                            <div class="w-100 captcha thy-input form-control">
                                                <span class="input-label input-label-lg">图形验证码</span>
                                                <input name="captcha" class="form-control-lg form-control mytip" maxlength="4" placeholder="请输入右侧验证码">
                                            </div>
                                            <div class="tu-feedback invalid-feedback">12</div>
                                            <div class="ml-2"><a class="captcha" href="javascript:;"><img
                                                    style="height: 46px;" src="/images/generate.png"></a>
                                                <div class="text-info tips-by-email cursor-pointer"
                                                     style="width: 132px;">
                                                    看不清？点击图片换一张
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mobile-code-form-group clearfix form-group code">
                                        <div name="code" class="thy-input form-control">
                                            <span class="input-label input-label-lg">短信验证码</span>
                                            <input class="form-control-lg form-control mytip" type="text" maxlength="6" placeholder="请输入短信验证码">
                                            <div class="input-append">
                                                <div>
                                                    <a class="mobile-code-action" href="javascript:;">
                                                        <button type="button" class="font-size-sm btn btn-primary btn-sm btn-square getCode" disabled>
                                                            获取短信验证码
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group accountLogin"><label class="form-label"></label>
                                    <div class="userNames thy-input form-control">
                                        <span class="input-label input-label-lg">手机号</span>
                                        <input maxlength="11" type="text" class="form-control-lg form-control userAccount mytip" placeholder="输入手机号">
                                    </div>
                                    <div class="invalid-feedback">1</div>
                                </div>
                                <div class="mb-2 form-group accountLogin">
                                    <label class="form-label"></label>
                                    <div>
                                        <div maxlength="50" class="thy-input form-control">
                                            <span class="input-label input-label-lg">登陆密码</span>
                                            <input class="form-control-lg form-control password mytip" type="password"
                                                          placeholder="请输入登陆密码">
                                            <div class="input-append"><a
                                                    class="link-secondary input-password-icon" href="javascript:;">
                                                <i class="wtf wtf-pwd-invisible" href="javascript:;"></i>
                                            </a></div>
                                        </div>
                                        <div class="invalid-feedback pass-feedback"></div>
                                    </div>
                                </div>
                                <div class="mt-3 form-group tip">
                                    <thy-alert thytype="danger">
                                        <div class="thy-alert thy-alert-danger"><span
                                                class="wtf mr-2 wtf-times-circle"></span>
                                            <span class="active"></span></div>
                                    </thy-alert>
                                </div>
                                <div class="single-card-actions form-group">
                                    <div class="btn-pair">
                                        <button type="button"
                                                class="btn-block single-btn btn btn-primary btn-square signin">
                                            登录
                                        </button>
                                    </div>
                                </div>
                                <div class="single-card-actions-desc form-group">
                                    <div class="btn-pair"> 或 <a href="signup.html">注册企业/团队</a></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-footer">© 2019 easyku.com<br>
                沪ICP备13017353号-3 京公网安备11010802012357号
            </div>
        </div>
</body>
</html><?php }} ?>