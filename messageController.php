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
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
header("Content-Type: text/html;charset=utf-8");
header("Cache-Control: no-cache");
// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	_alert_back('请先登录！');
// }

//-----------------------------回复并存储发送过来的站内信
if ($_GET['action'] == 'reply') {
	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		include ROOT_PATH.'includes/check.func.php';
		$_clean = array();
		$_clean['getterId'] = $_POST['getterId'];
		$_clean['senderId'] = $_COOKIE['userId'];
		$_clean['content'] = _check_content($_POST['content']);
		$_clean = _mysql_string($_clean);
		//		print_r($_clean);

		//将发送的数据存入到message表中
		$_sql="insert into message(senderId,getterId,datetime,content)";
		$_sql.="values('{$_clean['senderId']}','{$_clean['getterId']}',now(),'{$_clean['content']}')";
		$_res=$globalService->_manipulate($_sql);

		//判断是否添加成功
		if ($_res == 1)
			//_session_destroy();
			_location('消息发送成功','message.php');
			
	} else {
		//_session_destroy();
			_alert_back('消息发送失败');
	}
//-----------------------------接收并存储发送过来的站内信	
}else if ($_GET['action'] == 'send') {
	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		include ROOT_PATH.'includes/check.func.php';
		$_clean = array();
		$_clean['getterId'] = $_POST['getterId'];
		$_clean['senderId'] = $_COOKIE['userId'];
		$_clean['content'] = _check_content($_POST['content']);
		$_clean = _mysql_string($_clean);
//		print_r($_clean);
		
		//将发送的数据存入到message表中
		$_sql="insert into message(senderId,getterId,datetime,content)";
		$_sql.="values('{$_clean['senderId']}','{$_clean['getterId']}',now(),'{$_clean['content']}')";
		$_res=$globalService->_manipulate($_sql);
		
		//判断是否添加成功
		if ($_res == 1) 
			//_session_destroy();
			_alert_back('消息发送成功');
		} else {
			//_session_destroy();
			_alert_back('消息发送失败');
		}
//-----------------------------批删除短信
//isset($_POST['ids'])应该是$_POST过来的。就是POST过来的汗。。。
} else if ($_GET['action'] == 'delete' && isset($_POST['ids'])) {
	
 	$_clean = array();
 	$_clean['ids'] = _mysql_string(implode(',',$_POST['ids']));
 	//print_r($_clean);
 	//exit();
 	//危险操作，为了防止cookies伪造，还要比对一下唯一标识符uniqid()
	global $globalService;
 	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
 	//echo $_sql;
 	//exit();
 	$_arr=$globalService->_query($_sql);
 	if(!empty($_arr[0]['uniqid'])){
 		//判断唯一标识符是否异常
 		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
 		//删除该条短信
 		$_sql="delete from message where id in ({$_clean['ids']})";
 		$res=$globalService->_manipulate($_sql);
 	
 		if ($res == 1) {
 			_location('短信删除成功','message.php');
 		} else {
 			_alert_back('短信删除失败');
 		}
 	}else{
 		_alert_back('此短信不存在！');
	}
//-----------------------------单条短信删除模块
}else if ($_GET['action'] == 'delete' && isset($_GET['id'])){
	//验证短信是否合法 即 id是不是存在
	$_id=$_GET['id'];
	global $globalService;
	$_sql="select id from message where id=$_id";
	$_clean=array();
	$_clean=$globalService->_query($_sql);
	if(isset($_clean[0]['id'])){
		//危险操作，为了防止cookies伪造，还要比对一下唯一标识符uniqid()
		$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
		//echo $_sql;
		//exit();
		$_arr=$globalService->_query($_sql);
		if(!empty($_arr[0]['uniqid'])){
			//判断唯一标识符是否异常
			_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
			//删除该条短信
			$_sql="delete from message where id={$_clean[0]['id']}";
			$res=$globalService->_manipulate($_sql);

			if ($res == 1) {
				_location('短信删除成功','message.php');
				//_alert_back_two_step('短信删除成功');
			} else {
				_alert_back('短信删除失败');
			}
		}
	}else{
			_alert_back('此短信不存在！');
		}

}else{
	//提示错误
	_alert_back('非法登录');
}



?>