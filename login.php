<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-23
*/
session_start();
//定义标题
define("TITLE_NAME", "登录");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','login');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php 
		require ROOT_PATH.'includes/title.inc.php';
	?>
	</head>	
	<body>
		<div id="login">	
			<div id="header">
				<p>Welcome to XiaoYuanRomance</p>
			</div>	
			<div id="loginRegister">
				<form action="loginController.php?action=login" method="post" >
					<dl>
					<dd>帐　号：<input type="text" name="email" class="email" placeholder="请输入注册时的邮箱"/><span id="emailflag"></span></dd>
					<dd>密　码：<input type="password" name="password" class="password" placeholder="请输入密码"/></dd>					
					<dd>验证码：<input type="text" name="code" class="code" placeholder="请输入验证码" /><span id="codeflag"></span><img src="code.php" id="code"  onclick="javascript:this.src='code.php?tm='+Math.random();" /></dd>	
					<dd>
						<input type="hidden" name="keeptime" value="0" />
						<input type="checkbox" name="keeptime" value="2" class="keeptime" /><span class="keeptimespan">保留一周</span>
						<a class="forgetpassword" href="forgetPassword.php">忘记密码？</a>
					
					</dd>
					<dd><input type="submit" value="登录" class="button" id="submit" /> <input type="button" value="注册" id="register" class="button" /></dd>		
					</dl>			
				</form>
			</div>
		</div>
<!---------------------------------------jQery代码----------------------------------------------->
			<script type="text/javascript">//获取屏幕的宽度和高度
			$(document).ready(function(){
					var height=$(window).height();
					var width=$(window).width();
// 					alert(width+' '+height);
					$('#login').css({'height':height});
					

				});

			</script>
			<script type="text/javascript" >//点击注册按钮，连接到注册界面				
				$("#register").click(function(){
					window.location.href="register.php?action=register";
				});
			</script>
			<script type="text/javascript" >//动态验证输入的邮箱，和验证码问题
				$('.email').blur(function(){
					//alert($(this).val());
					//data为1 显示红色的x并且提示已经被注册了  data为2 显示绿色的勾 显示恭喜可以注册
					var data=_check_email("POST", "loginController.php",$(this).val(),"json");
					if(data==1){
						
// 						$('#usernameflag').css('background-color','green');
// 						$("#usernameflag").html("欢迎登录");
						$('#emailflag').css('background-image','url(images/right.png)');
						$('.password').focus();
					}else if(data==2){
// 						$('#usernameflag').css('background-color','red');
// 						$("#usernameflag").html("请先注册");
						alert('该邮箱没有注册,请确邮箱保填写正确');
						$('#emailflag').css('background-image','url(images/error.jpg)');

					}else{
						//什么都不做
					
					}
					
				});
	
				$('.code').keyup(function(){
					var data=_check_code("POST", "loginController.php",$(this).val(),"json");
					if(data==1){
						//$("#emailSpan").html("该邮箱已经注册，若是您的邮箱，请你移步登录界面");
						//alert("你输入的验证码不正确");
// 						$('#codeflag').css('background-color','red');
// 						$('#codeflag').html("验证码不正确");
						$('#codeflag').css('background-image','url(images/error.jpg)');
						$('.code').focus();
						
					
					}else if(data==2){
						//$("#emailSpan").html("恭喜你，该邮箱可以注册");
// 						$('#codeflag').css('background-color','green');
// 						$('#codeflag').html("验证码正确");
						$('#codeflag').css('background-image','url(images/right.png)');
						$('input[type=submit]').click();
						
					}else{
						//什么都不做   默认的data应该是0  我添加了else的目地是为了以后可能还有其他的分类的用
					}
				});
			

			</script>
		
		
		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>

	</body>
</html>