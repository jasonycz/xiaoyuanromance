<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-21
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
if(($_GET['action']=='sendMessage') && !empty($_POST['senderId']) && !empty($_POST['getterId']) && !empty($_POST['content'])){
// 	file_put_contents('1.txt', "2222"."\r\n",FILE_APPEND);
	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		include ROOT_PATH.'includes/check.func.php';
		$_clean = array();
		$_clean['senderId'] = $_POST['senderId'];
		$_clean['getterId'] = $_POST['getterId'];
		$_clean['content'] = _check_content($_POST['content']);
		$_clean = _mysql_string($_clean);
		
		//将发送的数据存入到chatLiveData表中
		$_sql="insert into chatlivedata(senderId,getterId,datetime,content)";
		$_sql.="values('{$_clean['senderId']}','{$_clean['getterId']}',now(),'{$_clean['content']}')";
		$_res=$globalService->_manipulate($_sql);
		
		//判断是否添加成功
		if ($_res == 1){
			$_result='{"good":"写入数据库成功"}';
			echo $_result;
		} else {
			//_session_destroy();
			//_alert_back('好友添加失败');
			$_result='{"bad":"存入到数据库时写入失败"}';
			echo $_result;
		}
		
		
	}
//-----------------------获取发过来的数据	
}else if(($_GET['action']=='getMessage') && !empty($_POST['senderId']) && !empty($_POST['getterId']) ){

	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		$_clean = array();
		$_clean['senderId'] = $_POST['senderId'];
		$_clean['getterId'] = $_POST['getterId'];
		$_clean = _mysql_string($_clean);

		//从chatLiveData表中取出senderId与getterId并且isRead=0的content和dateTime
		$_sql="select content,dateTime from chatlivedata where senderId='{$_clean['senderId']}' and getterId='{$_clean['getterId']}' ";
		$_sql.="and isRead=0";
		
// 		file_put_contents('1.txt', $_sql."\r\n",FILE_APPEND);
// 		exit();
		$_res=$globalService->_query($_sql);

		//判断是否有数据
		if ($_res){
			//file_put_contents('1.txt', count($_res)."\r\n",FILE_APPEND);
			$_result="[";
			for($i=0;$i<count($_res);$i++){
				if($i==count($_res)-1){
					$_result.='{"content":"'.$_res[$i]["content"].'","dateTime":"'.$_res[$i]["dateTime"].'"}';
				}else{
					$_result.='{"content":"'.$_res[$i]["content"].'","dateTime":"'.$_res[$i]["dateTime"].'"},';
				}	
			}
			$_result.="]";

			
			//将查看后的数据的标志isRead置1
			$_sql="update chatlivedata set isRead=1 where senderId='{$_clean['senderId']}' and getterId='{$_clean['getterId']}'";
			$_res=$globalService->_manipulate($_sql);
			//判断是否添加成功
			if ($_res !=1){
				$_result='{"bad":"将isRead置1写入失败"}';
			}
				echo $_result;
			
		}else{//没有数据的情况
			$_result='{}';
			//$_result='{"data":"没有数据"}';
			echo $_result;
		}
		//file_put_contents('1.txt', $_result."\r\n",FILE_APPEND);
		// 		exit();

	}

}
else{
	
	_alert_back("非法登录!");
}

















?>