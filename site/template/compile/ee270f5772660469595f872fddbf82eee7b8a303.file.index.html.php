<?php /* Smarty version Smarty-3.1.12, created on 2019-08-23 11:18:43
         compiled from "F:\www\demo\site\template\common\index.html" */ ?>
<?php /*%%SmartyHeaderCode:280345d5f5b130db936-59782825%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee270f5772660469595f872fddbf82eee7b8a303' => 
    array (
      0 => 'F:\\www\\demo\\site\\template\\common\\index.html',
      1 => 1566524794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '280345d5f5b130db936-59782825',
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
  'unifunc' => 'content_5d5f5b131184a0_32996773',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d5f5b131184a0_32996773')) {function content_5d5f5b131184a0_32996773($_smarty_tpl) {?><!DOCTYPE html>
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
            //点击登录
            $(".signin").click(function () {
                var formhash = $(".formhash").val();
                if($(".loginTab").hasClass("active")){  //通过账号登录
                    var userAccount = $(".userAccount").val();
                    var password = $(".password").val();
                    var reg = /^1\d{10}$/g;

                    $.post('/login/index.php?task=logout',{},
                        function(data) {
                            data = JSON.parse(data);
                            if(data.status == 'LOGOUT_SUCCESS') {
                                //登陆成功跳转管理内容页面
                                window.location.href = "/";
                            }
                            //ajax返回错误
                            $(".tip").show();
                            $(".tip span.active").html(data.msg);
                            return false;

                        }
                    );
                }
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
                    <div class="single-card-subtitle"><a href="javascript:;" class="loginTab active"></a></div>
                    <form name="signinForm" class="thy-form thy-form-vertical">
                        <input type="hidden" name="formhash" class="formhash" value="<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
">

                        <div class="single-card-actions form-group">
                            <div class="btn-pair">
                                <button type="button"
                                        class="btn-block single-btn btn btn-primary btn-square signin">
                                    退 出
                                </button>
                            </div>
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