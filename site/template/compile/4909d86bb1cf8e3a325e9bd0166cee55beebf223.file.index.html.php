<?php /* Smarty version Smarty-3.1.12, created on 2019-08-15 16:47:03
         compiled from "F:\www\easyku_src\site\template\view\index.html" */ ?>
<?php /*%%SmartyHeaderCode:79195d53b6b26894f9-69747840%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4909d86bb1cf8e3a325e9bd0166cee55beebf223' => 
    array (
      0 => 'F:\\www\\easyku_src\\site\\template\\view\\index.html',
      1 => 1565858509,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '79195d53b6b26894f9-69747840',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5d53b6b26d2ce0_01124813',
  'variables' => 
  array (
    'formhash' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d53b6b26d2ce0_01124813')) {function content_5d53b6b26d2ce0_01124813($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>首页</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/login.css"/>
    <link rel="stylesheet" href="/css/datepicker.css"/>
    <link rel="stylesheet" href="/css/all.css"/>
    <style>
		.copy {
			position: absolute; top: 400px; text-align: center; margin: 0 auto; font-size:12px;width: 100%;
		}
	</style>
</head>
<body>
    <div class="login-main">
        <div class="form-m">
            <form action="kj.html">
            <img src="/img/logo.png" alt=""/>
            <table>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <a href="/login" class="login">登 录</a>
                        <a href="/register" class="login">注 册</a>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </div>
    <script src="/js/vendor/jquery-1.8.2.min.js"></script>
    <script>
        $(function(){
			document.onkeydown = function(event){
				e = event ? event : (window.event ? window.event : null);
				if (e.keyCode == 13) {
					$(".login").click();
				}
			}

            $(".form-m").hide();
            $(".form-m").fadeIn(200);
            setCenter();
            $(window).resize(function(){
                setCenter();
            });
            $(".login").click(function(){
                $(".err").hide();
                $("input[name='name']").removeClass();
                $("input[name='password']").removeClass();

                if($("input[name='name']").val()==""){
                    $("input[name='name']").addClass("err-type err-border");
                    setTimeout(function(){
                        $("input[name='name']").removeClass("err-type");
                    },400);
                    $(".err").html("您的姓名或密码有误，请重新输入<br />Login name or password is incorrect");
                    $(".err").fadeIn();
                    return;
                }
                if($("input[name='password']").val()==""){
                    $("input[name='password']").addClass("err-type err-border");
                    setTimeout(function(){
                        $("input[name='password']").removeClass("err-type");
                    },400)
                    $(".err").html("您的姓名或密码有误，请重新输入<br />Login name or password is incorrect");
                    $(".err").fadeIn();
                    return;
                }

				$.post('?task=login', {
						name:$("input[name='name']").val(),
						passwd:$("input[name='password']").val(),
						formhash:'<?php echo $_smarty_tpl->tpl_vars['formhash']->value;?>
'
					}, function(data){
					if (data.status == 'LOGIN_SUCCESS') {
						window.location.href = data.redirect;
					} else {
						$("input[name='" + data.key + "']").addClass("err-type err-border");
						setTimeout(function(){
							$("input[name='" + data.key + "']").removeClass("err-type");
						},400)
						$(".err").text(data.msg);
						$(".err").fadeIn();
					}
				}, 'json');
            })
        });

		function setCenter(){
			var _allh = $(document).height();
			var _bh = $(".form-m").innerHeight();
			$(".form-m").animate({ top:(_allh-_bh)/2+"px"},300);
		}
	</script>
</body>
</html><?php }} ?>