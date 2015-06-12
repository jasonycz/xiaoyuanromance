<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-23
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Forbidden!');
}

//设置字符集编码
header('Content-Type: text/html; charset=utf-8');

//转换硬路径常量
define('ROOT_PATH',substr(dirname(__FILE__),0,-8));

//拒绝PHP低版本
if (PHP_VERSION < '4.1.0') {
	exit('Version is to Low!');
}

//引入函数库
require ROOT_PATH.'class/Mysqli.class.php';
require ROOT_PATH.'class/GlobalService.class.php';//dql dml 的封装  其实它完全可以替代下面的global.func.php
require ROOT_PATH.'includes/global.func.php';

//$mysqli=new MysqliTool();//$mysqli不能像$globalService那么用是因为$mysqli用完后被关闭了 所以每次都要重新声明
//也即每次重新建立连接
//做成全局的，这样以后再用的时候就不用再声明了
$globalService=new GloablService();

//分页经常会用到的全局变量
$_page=0;//当前页是第几页
$_pagesize=0;//每页的大小
$_pagenum=0;//每页开始的第一条记录对应的条数
$_pageabsolute=0;//最大的页面个数   ceil($_num / $_pagesize);
$_num=0;//全部记录的条数

//执行耗时
define('START_TIME',_runtime());
//$GLOBALS['start_time'] = _runtime();

//网站系统设置初始化
$_sql="select*from systemConfigue where id=1 limit 1";
$_array=array();
$_system=array();
$_array=$globalService->_query($_sql);

if(!empty($_array)){
	$_system['webname'] = $_array[0]['webname'];
	$_system['sensitiveString']=$_array[0]['sensitiveString'];
	$_system = _html($_system);
} else {
	exit('系统表异常，请管理员检查！');
}





?>