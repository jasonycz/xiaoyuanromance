<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-25
*/
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
header("Content-Type: text/html;charset=utf-8");
header("Cache-Control: no-cache");
//登录状态
//_login_state();
//开始处理登录状态
if ($_GET['action'] == 'login') {
	//为了防止恶意注册，跨站攻击
	//***以后要做成实时验证
	//我在前面添加了完整的js验证，那么这里还要不要呢 2014-12-31  11:55
	_check_code($_POST['code'],$_SESSION['code']);
	
	//引入验证文件
	include ROOT_PATH.'includes/login.func.php';
	//接受数据
	$_clean = array();
	////////////////////////////////////////////////////////////
	//注意：这里的username并不是真正的用户名，而是注册的邮箱，因为这个地方我还没想好用什么来表达，用户名不太合适。
	//这里暂时这么用吧 _check_username()这个函数验证邮箱是不合理的，所以我应该
	//$_clean['username'] = _check_username($_POST['username'],2,20);
	$_clean['email']=_check_email($_POST['email'], 6, 30);
	$_clean['password'] = _check_password($_POST['password'],6);
	$_clean['keeptime'] = _check_keep_time($_POST['keeptime']);
	
	//到数据库去验证
	global $globalService;
	$_sql="select id,username,uniqid from user where email='{$_clean['email']}' and password='{$_clean['password']}'";
	$_res=$globalService->_query($_sql);
	//print_r($_res);
	//exit();
	if(!empty($_res)){

		_setcookies($_res[0]['id'],$_clean['email'],$_res[0]['username'],$_res[0]['uniqid'],$_clean['keeptime']);
 		//print_r($_COOKIE);
	  	//exit();
	  	//更新登录的时间和登录的IP
	  	$_sql="update user set logindate=now(),lastvisitip='{$_SERVER["REMOTE_ADDR"]}',loginCount=loginCount+1 where id={$_res[0]['id']}";
	  	$globalService->_manipulate($_sql);
		
		_location(null,'index.php');
		
	} else {
		//_session_destroy();
		_location('用户名密码不正确或者该账户未被激活！','login.php');
	}
}else if($_GET['action'] == 'loginOut'){
	_unsetcookies();

}else{
	//动态返回用户注册用的邮箱是否存在不存在 就提示错误   注意  isset不能用!empty代替
	if(isset($_POST['email'])){
		$_email=$_POST['email'];
		$_sql="select email from user where email='$_email'";
		global $globalService;
		$arr=$globalService->_query($_sql);
		if($arr){
			$res='{"exist":"1"}';
		}else{
			$res='{"exist":"2"}';
		}
		//file_put_contents("1.txt", $res."\r\n",FILE_APPEND);
		//exit();
		echo $res;
	}
	if(isset($_POST['code'])){
		$_code=$_POST['code'];
		//file_put_contents("1.txt", $_SESSION['code']."\r\n",FILE_APPEND);
		if($_code!=$_SESSION['code']){
			$res='{"match":"1"}';
		}else{
			$res='{"match":"2"}';
		}
		echo $res;
	}
	
}



























?>
