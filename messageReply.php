<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-19
*/
session_start();
//定义标题
define("TITLE_NAME", "消息回复");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','messageReply');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	//_alert_close('请先登录！');
// 	_location('请先登录！',"login.php");
// }
//×××××××××××××××××××××××收信人的Id  发信的内容
//***********接收由messageDetail.php传过来的数据
if(!empty($_GET['getterId'])&&$_GET['action']='reply'){
	$_getterId=$_GET['getterId'];
	$_senderId=$_COOKIE['userId'];
	$_getterName=$_GET['getterName'];

}else{
	_alert_back("非法操作！");
}





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php 
			require ROOT_PATH.'includes/title.inc.php';
		?>
	</head>	
	<body>
		<?php require ROOT_PATH.'includes/header.inc.php';?>
		
		<div class="messageReply">
				<h2><strong>消息回复</strong></h2>
				<form action="messageController.php?action=reply" method="post" enctype="application/x-www-form-urlencoded">
					<dl>
						<dd>收 信 人：<?php echo $_getterName; ?></dd>
						<dd>内　  容：<br />
							<textarea name="content" class="content" ></textarea>
						
						</dd>
						
						<dd><input type="hidden" class="getterId" name="getterId" value="<?php echo $_getterId;?>"/><input type="submit" value="发送" class="button"/></dd>
					</dl>
			    </form>
		</div>
		


		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
	<!-- ------------------------------------jQery代码-------------------------------------------- -->
	</body>
</html>