<?php /* Smarty version Smarty-3.1.12, created on 2019-09-03 08:51:32
         compiled from "F:\www\easyku_v0.0.1\site\template\login\setup.html" */ ?>
<?php /*%%SmartyHeaderCode:102705d5a5de693edb9-38352376%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f9b6908d0d960029c22a7d26d544bd4897277d48' => 
    array (
      0 => 'F:\\www\\easyku_v0.0.1\\site\\template\\login\\setup.html',
      1 => 1567471814,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '102705d5a5de693edb9-38352376',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5d5a5de699a2e8_76228384',
  'variables' => 
  array (
    'title' => 0,
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d5a5de699a2e8_76228384')) {function content_5d5a5de699a2e8_76228384($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/css/login_.css">
<script src="/vendor/jquery-1.12.3.min.js"></script>
<style>
    .thy-form-vertical .tip {
        display: none;
    }

    .wtf-pwd-invisible.active:before {
        content: "\e73d";
    }

</style>
<style>
    .mobile-code-form-group[_ngcontent-c4] {
    position: relative
}

.mobile-code-form-group[_ngcontent-c4]   .mobile-code-action[_ngcontent-c4]   .btn[_ngcontent-c4] {
    padding: 4.5px 15px;
    -webkit-transform: translateX(5px);
    transform: translateX(5px)
}

.mobile-code-form-group[_ngcontent-c4]   .form-text[_ngcontent-c4] {
    position: absolute;
    bottom: -10px;
    right: 0
}

.mobile-code-form-group[_ngcontent-c4]   .form-text[_ngcontent-c4]   .btn[_ngcontent-c4] {
    padding-left: 0;
    padding-right: 0
}

.mobile-code-form-group[_ngcontent-c4]   .tips-by-mobile[_ngcontent-c4] {
    float: right;
    margin-top: 10px;
    font-size: 12px
}

.mobile-zone[_ngcontent-c4] {
    display: flex;
    align-items: center;
    justify-content: space-between
}

.mobile-zone[_ngcontent-c4] span[_ngcontent-c4] {
    margin-right: 20px
}

.mobile-zone[_ngcontent-c4] i[_ngcontent-c4] {
    margin-right: 14px
}
</style>
<style>
    .agree-checkbox-label[_ngcontent-c6] {
    margin-top: 10px;
    margin-bottom: 15px
}

.agree-checkbox-label[_ngcontent-c6]   .checkbox-setup[_ngcontent-c6] {
    width: 16px !important;
    height: 16px !important;
    vertical-align: middle;
    display: inline-block
}

.agree-checkbox-label[_ngcontent-c6]   .agree-setup[_ngcontent-c6] {
    display: inline-block;
    vertical-align: -2px;
    margin-left: 5px;
    color: #afafaf
}
</style>
<script>
    $(function () {
        //完成注册
        $(".save").click(function () {
            var userName=$(".userName").val();
            var password=$(".password").val();
            var teamName=$(".teamName").val();

            var formhash = $(".formhash").val();
            $.post('?script=register&task=register',{ username: userName, password: password, company: teamName, formhash: formhash},
                function(data) {
                    data = JSON.parse(data);
                    if(data.status == 'REGISTER_SUCCESS') {
                        //登陆成功跳转管理内容页面
                        window.location.href = "/";
                    } else{
                        //ajax返回错误
                        $(".tip").show();
                        $(".tip span.active").html(data.msg);
                        return false;
                    }
                }
            );
        });
        $(document).on("input",".userName,.password,.teamName",function(){
            $(this).parent().removeClass("is-invalid");
        });
        $(document).on("focus",".userName,.password,.teamName",function(){
            $(this).parent().find(".input-label").addClass("active");
            $(this).parent().addClass("form-control-active");
        });
        $(document).on("blur",".userName,.password,.teamName",function(){
            $(this).parent().find(".input-label").removeClass("active");
            $(this).parent().removeClass("form-control-active");
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
        //点击同意
        $(".checkbox-setup").click(function(){
            if(!$(this).is(":checked")){
                $(".save").attr("disabled",true);
            }else{
                $(".save").attr("disabled",false);
            }
        });
    });
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
                <div class="single-card-title"> 填写注册信息</div>
                <div class="single-card-subtitle"> 填写注册信息，完成注册</div>
                <form name="signupSuccessForm" class="thy-form thy-form-vertical ng-untouched ng-pristine ng-invalid">
                    <input type="hidden" name="formhash" class="formhash" value="<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
">
                    <div class="form-group"><label
                            class="form-label label-required">姓名</label>
                        <div>
                            <div name="userName" class="thy-input form-control">
                                <span class="input-label input-label-lg">姓名</span>
                                <input class="form-control-lg form-control userName" type="text" placeholder="请输入您的姓名">
                            </div>
                            <div class="invalid-feedback">11</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label label-required">密码</label>
                        <div>
                            <div name="password" class="thy-input form-control">
                                <span class="input-label input-label-lg">密码</span>
                                <input class="form-control-lg form-control password" type="password"
                                       placeholder="设置登录密码，不少于6个字符，不大于20个字符">
                                <div class="input-append">
                                    <a class="link-secondary input-password-icon" href="javascript:;">
                                        <i class="wtf wtf-pwd-invisible" href="javascript:;"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="invalid-feedback">11</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label label-required">企业/团队名称</label>
                        <div>
                            <div name="teamName" class="thy-input form-control">
                                <span class="input-label input-label-lg">企业/团队名称</span>
                                <input class="form-control-lg form-control teamName" type="text"
                                       placeholder="请输入您要注册的企业/团队名称">
                            </div>
                            <div class="invalid-feedback">11</div>
                        </div>
                    </div>
                    <div _ngcontent-c6="" class="agree-checkbox-label">
                        <input _ngcontent-c6="" class="checkbox-setup" name="checkbox" type="checkbox" checked>
                        <span _ngcontent-c6="" class="agree-setup"> 我已阅读并同意 <a _ngcontent-c6="" href="#" target="_blank">《easyku服务条款》</a></span>
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
                            <button class="btn-block single-btn btn btn-primary btn-square save" type="button">
                                完成注册
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="single-footer">© 2019 easyku.com<br>
        沪ICP备15051032号-1
    </div>
</div>
</body>
</html><?php }} ?>