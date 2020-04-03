<?php /* Smarty version Smarty-3.1.12, created on 2019-05-05 06:12:15
         compiled from "/usr/local/web/test/pm/site/template/view/login.html" */ ?>
<?php /*%%SmartyHeaderCode:382792365cce0e3fbf04e5-77125119%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c12fd7b146dbd565e1b88fdb62a84c08ae9f5e9' => 
    array (
      0 => '/usr/local/web/test/pm/site/template/view/login.html',
      1 => 1553417265,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '382792365cce0e3fbf04e5-77125119',
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
  'unifunc' => 'content_5cce0e3fc3d619_36876514',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cce0e3fc3d619_36876514')) {function content_5cce0e3fc3d619_36876514($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
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
                    <td align="right" width="70"><span class="login-name"></span></td>
                    <td><input type="text" id="name" name="name" placeholder="登录名 Login name" /> </td>
                </tr>
                <tr>
                    <td align="right"><span class="login-password"></span></td>
                    <td><input type="password" id="password" name="password" placeholder="密码 password" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <a href="javascript:void(0)" class="login">登 录 Login</a>
                        <span class="err">您的姓名或密码有误，请重新输入</span>
                    </td>
                </tr>
            </table>
            </form>
        </div>
        <div class="copy"><a href="http://www.miitbeian.gov.cn" target="_blank" style="color:#282828">沪ICP备15051032号-2</a></div>
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