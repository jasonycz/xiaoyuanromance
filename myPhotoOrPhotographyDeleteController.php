<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-7
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
//删除相册
if($_GET['action']=='deletePhotography'&&isset($_GET['photographyId'])){
	$_photography_id=$_GET['photographyId'];
	$_userId=$_COOKIE['userId'];
// 	echo $_userId;
// 	exit();
	//属于危险操作 验证用户的是否合法
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1 ";
	global $globalService;
	$_arr=$globalService->_query($_sql);
	if($_arr){
		_uniqid($_arr[0]['uniqid'], $_COOKIE['uniqid']);
		//删除磁盘中的文件 1.从数据库中找到相册的名字 
		$_sql="select name from photography where id=$_photography_id";
		$_arr=$globalService->_query($_sql);
		if($_arr){
			$_html=array();
			$_html['photographyName']=$_arr[0]['name'];
			$_html=_html($_html);
			//2.组装好要删除的文件夹的物理地址  用绝对路径好，还是相对路径好呢????2015-01-09 22:37
			$_dir='../UserResourse/Photography/'.$_userId.'/'.$_html['photographyName'];
			//将路径进行转码的问题。nice!!!是的。
			$_dir=iconv("utf-8", "gb2312", $_dir);
			//echo $_dir;
			//exit();
			//3.删除磁盘中的目录
			if (is_dir($_dir)) {
				if (_remove_Dir($_dir)) {
 						//1.删除目录里的数据库图片
 						//$_sql="delete from photo where photographyId=$_photography_id ";
 						//$globalService->_manipulate($_sql);
 						//2.删除这个目录的数据库
 						$_sql="delete from photography where id=$_photography_id ";
						$globalService->_manipulate($_sql);
 						_location('相册删除成功!','myPhotography.php');
				} else {
					_alert_back('相册删除失败!');
				}
			}else{
				_alert_back('磁盘中不存在该相册');
			}
		}else{
			_alert_back('数据库中该相册不存在！');
		}

	}
}else if($_GET['action'] == 'deletePhoto' && isset($_GET['photoId']) && isset($_GET['photography'])){//删除相片
	$_photo_id=$_GET['photoId'];
	$_userId=$_COOKIE['userId'];
	$_photography=$_GET['photography'];
	
	//属于危险操作 验证用户的是否合法
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1 ";
	global $globalService;
	$_arr=$globalService->_query($_sql);
	if($_arr){
		_uniqid($_arr[0]['uniqid'], $_COOKIE['uniqid']);
		//判断删除图片的身份是否合法吧
		//删除磁盘中的文件 1.从数据库中找到相片的名字和所属的相册 
		$_sql="select photo,photographyId from photo where id=$_photo_id";
		$_arr=array();
		$_arr=$globalService->_query($_sql);
		if($_arr){
			$_html=array();
			$_html['photo']=$_arr[0]['photo'];
			$_html['photographyId']=$_arr[0]['photographyId'];
			$_html=_html($_html);
			//2.组装好要删除的文件夹的物理地址  用绝对路径好，还是相对路径好呢????2015-01-09 22:37
			$_dir='../UserResourse/Photography/'.$_userId.'/'.$_photography.'/'.$_html['photo'];
			//将路径进行转码的问题。nice!!!是的。
			$_dir=iconv("utf-8", "gb2312", $_dir);
			//echo $_dir;
			//exit();
			//3.删除磁盘中的相片
			if (file_exists($_dir)) {
				unlink($_dir);
				//4.删除数据库中的记录
				$_sql="delete from photo where id=$_photo_id";
				$_res=$globalService->_manipulate($_sql);
				if($_res==1){
					_location('相片删除成功!',"myPhoto.php?photographyId={$_html['photographyId']}");
				}else{
					_alert_back('删除失败！');
				}
			}else {
				_alert_back('磁盘里已不存在此图！');
			}
		}
			
	}else{
		_alert_back('数据库中该相片不存在！');
	}
		
}else{
	_alert_back('非法登录！');
}





?>