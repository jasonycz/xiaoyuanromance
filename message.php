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
define("TITLE_NAME", "消息中心");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	_alert_back('请先登录！');
// }


	//分页模块
	global $_pagesize,$_pagenum,$globalService;
	//获取相应的全局参数
	$_sql="select id from message where getterId='{$_COOKIE['userId']}'";
	$_size=10;
	$_type=1;
	_page($_sql, $_size);
	$_sql="select id,isRead,senderId,content,datetime from message where getterId='{$_COOKIE['userId']}' order by datetime desc limit $_pagenum,$_pagesize ";
	$_clean_information=array();
	$_clean_information=$globalService->_query($_sql);




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		require ROOT_PATH.'includes/title.inc.php';
	?>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="messageCenter">
	
			<h2>消息管理中心</h2>
			<form method="post" action="messageController.php?action=delete">
			<table >
			<tr><th>发信人</th><th>消息内容</th><th>时间</th><th>状态</th><th>操作</th></tr>
			<?php

			$_html = array();
			for($i=0;$i<count($_clean_information);$i++) {
				$_html['id'] = $_clean_information[$i]['id'];
				/********Start--通过发送人的Id获取发送人的名字************/
				$_sql="select username from user where id={$_clean_information[$i]['senderId']} limit 1";
				$_sendName=$globalService->_query($_sql);
				$_html['sender'] = $_sendName[0]['username'];
				/********End--通过发送人Id获取发送人的名字************/
				$_html['content'] =$_clean_information[$i]['content'];
				$_html['datetime'] = $_clean_information[$i]['datetime'];
				$_html = _html($_html);
				if (empty($_clean_information[$i]['isRead'])) {
					$_html['state'] = '<img src="images/read.gif" alt="未读" title="未读" />';
					$_html['content_html'] = '<strong><font color="red">'._title($_html['content'],14).'</font></strong>';
				} else {
					$_html['state'] = '<img src="images/noread.gif" alt="已读 title="已读" />';
					$_html['content_html'] = _title($_html['content'],14);
				}			
				?>
					<tr>
						<td><?php echo $_html['sender'];?></td>
						<td><a href="messageDetail.php?id=<?php echo $_html['id']?>" title="<?php echo $_html['content']?>"><?php echo $_html['content_html']?></a></td>
						<td><?php echo $_html['datetime']?></td>
						<td><?php echo $_html['state']?></td>
						<td><input name="ids[]" value="<?php echo $_html['id']?>" type="checkbox" /></td>
					</tr>
		  <?php }?>
				   <tr><td colspan="5"><label for="all">全选 <input type="checkbox" name="chkall" id="all" /></label> <input type="submit" value="批删除"  class="button"/></td></tr>
				</table>
			</form>	
		
	
		<?php _paging($_type);?>
	</div>

<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>