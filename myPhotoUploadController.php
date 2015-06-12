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
////添加目录
// if (!$_COOKIE['username']) {
// 	_alert_back('非法登录！');
// }
$_userId=$_COOKIE['userId'];

if($_GET['action'] == 'upload'){
	//属于危险操作 验证用户的是否合法
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1 ";
	global $globalService;
	$_arr=$globalService->_query($_sql);
	if($_arr){
		_uniqid($_arr[0]['uniqid'], $_COOKIE['uniqid']);
		//接收前一页面的数据
		$_photography_name=$_POST['upLoadPhotographyName'];
		$_photo_descript=$_POST['photoDescript'];
		$_name="upLoadFile";
		//调用上传类
		include 'class/UploadService.class.php';
		$uploadService=new UploadService();
		$uploadService->_upload_photo($_photography_name, $_photo_descript,$_name);
		//------------------添加将路径存入到数据库结束----------------//	
		_location('图片上传成功！','myPhotography.php');
 	}
}else{
	_alert_back('非法登录！');
}






























?>