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


//-----------------------添加好友
if($_GET['action']=='makeFriend'){
	
	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		include ROOT_PATH.'includes/check.func.php';
		$_clean = array();
		$_clean['respendentId'] = $_POST['respendentId'];
		$_clean['applicantId'] = $_COOKIE['userId'];
		$_clean['content'] = _check_content($_POST['content']);
		$_clean = _mysql_string($_clean);

		//后台再次验证不能添加自己
		if ($_clean['respendentId'] == $_clean['applicantId']) {
			_alert_back('请不要添加自己！');
		}
		//数据库验证好友是否已经添加
		$_sql="select id from friend where respendentId='{$_clean['respendentId']}' and applicantId='{$_clean['applicantId']}'";
		$_sql.="or respendentId='{$_clean['applicantId']}' and applicantId='{$_clean['respendentId']}' limit 1 ";
		if(!!$_res=$globalService->_query($_sql)){
			if($_res[0]['state']==0){
				_alert_back("你已经提交好友申请，请等待验证!或对方已添加你为好友，等待你的确认！");
			}else{
				_alert_back('你们已经是好友了，无需再添加了。');
			}
		}else{

			//将发送的数据存入到friend表中
			$_sql="insert into friend(applicantId,respendentId,datetime,content)";
			$_sql.="values('{$_clean['applicantId']}','{$_clean['respendentId']}',now(),'{$_clean['content']}')";
			$_res=$globalService->_manipulate($_sql);
		
			//判断是否添加成功
			if ($_res == 1){
				//_session_destroy();
				//_location('好友添加成功，等待对方验证','message.php');
				_alert_back('好友添加成功，等待对方验证');
			} else {
				//_session_destroy();
				_alert_back('好友添加失败');
			}
		}
	}
}//-----------------------同意添加好友
else if($_GET['action'] == 'check' && isset($_GET['id'])){
	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";
	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		//修改表里state，从而通过验证
		$_sql="update friend set state=1 where id='{$_GET['id']}'";
		$_res=$globalService->_manipulate($_sql);
		if ($_res == 1) {
			//_location('好友验证成功','friendMakeCenter.php');
			_alert_back("好友验证成功");
		} else {
			_alert_back('好友验证失败');
		}
	}

}
//-----------------------------批删除好友验证信息
//isset($_POST['ids'])应该是$_POST过来的。就是POST过来的汗。。。
else if ($_GET['action'] == 'delete' && isset($_POST['ids'])) {

	$_clean = array();
	$_clean['ids'] = _mysql_string(implode(',',$_POST['ids']));
	//危险操作，为了防止cookies伪造，还要比对一下唯一标识符uniqid()
	global $globalService;
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1";

	$_arr=$globalService->_query($_sql);
	if(!empty($_arr[0]['uniqid'])){
		//判断唯一标识符是否异常
		_uniqid($_arr[0]['uniqid'],$_COOKIE['uniqid']);
		//删除该条短信
		$_sql="delete from friend where id in ({$_clean['ids']})";
		$res=$globalService->_manipulate($_sql);

		if ($res == 1) {
			_location('信息删除成功','friendMakeCenter.php');
		} else {
			_alert_back('信息删除失败');
		}
	}else{
		_alert_back('此信息不存在！');
	}
}	
//------------------------------解除好友关系
else if($_GET['action'] == 'deleteFriend' && isset($_GET['friendId']) ){
	//属于危险操作 验证用户的是否合法
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1 ";
	global $globalService;
	$_arr=$globalService->_query($_sql);
	if($_arr){
		_uniqid($_arr[0]['uniqid'], $_COOKIE['uniqid']);
		$_clean = array();
		$_clean['respendentId'] = $_GET['friendId'];;
		$_clean['applicantId'] = $_COOKIE['userId'];
		$_clean = _mysql_string($_clean);
		
		//去friend中解除好友关系
		$_sql="delete from friend where  (respendentId='{$_clean['respendentId']}' and applicantId='{$_clean['applicantId']}')";
		$_sql.=" or (applicantId='{$_clean['respendentId']}' and respendentId='{$_clean['applicantId']}') limit 1";
		$_arr=array();
		$_arr=$globalService->_manipulate($_sql);
		if($_arr==1){
				_alert_back('好友关系解除成功！');
			}else {
				_alert_back('好友关系解除失败！');
			}
	}
}
			

else{
	_alert_back("非法登录！");
}	
	
	
	
	
	

?>