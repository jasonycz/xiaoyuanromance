<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-15
*/
session_start();
//定义标题
define("TITLE_NAME", "管理页面");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','manageData');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	//_alert_close('请先登录！');
// 	_location('请先登录！',"login.php");
// }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php 
			require ROOT_PATH.'includes/title.inc.php';
		?>
	</head>	
	<body style="border: 1px solid orange;">
		<?php require ROOT_PATH.'includes/header.inc.php';?>
		<div class="manageData">
			<table>
			<?php 
				//获取登录的人和登录的人数
				global $globalService;
				$_sql="select id,username,registerdate,logindate,lastvisitip,loginCount from user where id>=41 or id=17";
				$_arr=$globalService->_query($_sql);
				
				$_register_num=count($_arr);
				
				echo "注册总人数:".$_register_num;
				echo "<hr />";
				echo "<tr><th>id</th><th>用户名</th><th>注册时间</th><th>登录时间</th><th>登录ip</th><th>登录总次数</th></tr>";
				for($i=0;$i<$_register_num;$i++){
	
					
			?>
				
				<tr>
					<td><?php echo $_arr[$i]['id'];?></td>
					<td><?php echo $_arr[$i]['username'];?></td>
					<td><?php echo $_arr[$i]['registerdate'];?></td>
					<td><?php echo $_arr[$i]['logindate'];?></td>
					<td><?php echo $_arr[$i]['lastvisitip'];?></td>
					<td><?php echo $_arr[$i]['loginCount'];?></td>
				
				</tr>
			
			
			<?php  } ?>
			</table>
		
		</div>
		


		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
	<!-- ------------------------------------jQery代码-------------------------------------------- -->
	</body>
</html>