<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-2
*/
session_start();
//定义标题
define("TITLE_NAME", "消息详情");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','messageDetail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	_alert_back('请先登录！');
// }else{}
global $globalService;
//-------------------------获取该条信息的详情  并且把是否查看的标志位进行置1
if (isset($_GET['id'])) {
	$id=$_GET['id'];
	$_sql="select*from message where id=$id limit 1";//因为只有一条
	$_rows=array();
	//$_rows = $mysqli->execute_dql_arr($sql);
	$_rows =$globalService->_query($_sql);
	//print_r($_rows);
	//exit();		
	if ($_rows[0]['id']) {
		//将它state状态设置为1即可
		//echo $_rows[0]['id'];
		if (empty($_rows[0]['isRead'])) {
			$_sql="update message set isRead=1 where id=$id limit 1";
			$globalService->_manipulate($_sql);

		}
		$_html= array();
		$_html='';
		$_html['id']= $_rows[0]['id'];
		/********Start--通过发送人的Id获取发送人的名字************/
		$_sql="select username from user where id={$_rows[0]['senderId']} limit 1";
		$_sendName=$globalService->_query($_sql);
		$_html['sender'] = $_sendName[0]['username'];
		/********End--通过发送人Id获取发送人的名字************/
		$_html['content'] = $_rows[0]['content'];
		$_html['datetime'] = $_rows[0]['datetime'];
		$_message_html = _html($_html);
		//print_r($_message_html);
		
	}
}else{
		_alert_back('此短信不存在！');
	}
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php 
		require ROOT_PATH.'includes/title.inc.php';
	?>
	</head>
	<body>	
		<?php 
			require ROOT_PATH.'includes/header.inc.php';
		?>
		
		<div id="messageDetail" >
			
				<h2><strong>消息详情</strong></h2>
				<dl>
					<dd>发 信 人：<?php echo $_message_html['sender']?></dd>
					<dd>内　　容：<strong><?php echo $_message_html['content']?></strong></dd>
					<dd>发信时间：<?php echo $_message_html['datetime']?></dd>
					<dd  class="button">
						<input type="button"  value="回复" id="reply"  class="button" name="<?php echo $_rows[0]['senderId'];?>" title="<?php echo $_message_html['sender'];?>" />
					 	<input type="button" id="delete" name="<?php echo $_message_html['id']?>" value="删除短信"  class="button" />
					</dd>
				</dl>
			
		</div>	
		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>		
	</body>

</html>
