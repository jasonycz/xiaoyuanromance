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


//进行数据的接收
if($_GET['action']=='search'){
	
	global $globalService;
	//***接收从search.php中传输过来的数据
	//返回的数据有：头像，姓名，兴趣爱好，自我描述
	$_sql='select id,face,username,selfdescription from user where ';
	//***用于记录填写了什么条件
	$_arr=array();
	//**************年龄范围
	if($_POST['ageStart']!=-1 || $_POST['ageEnd']!=9999){
		$_sql.="birthday>='{$_POST['ageStart']}' and birthday<='{$_POST['ageEnd']}' and ";
		$_arr[]['key']="年龄范围";
		if($_POST['ageStart']==-1){$_POST['ageStart']='不限';}
		if($_POST['ageEnd']==9999){$_POST['ageEnd']='不限';}
		$_arr[]['value']=$_POST['ageStart'].'--'.$_POST['ageEnd'];

	}
	//**********性别
	if(!empty($_POST['sex'])){
		//如果是不限的话，就根本不用设置sql语句了
		$_sex=$_POST['sex'];
		if($_sex!='不限'){
			$_sql.="sex='$_sex' and ";
			$_arr[]['key']="性别";
			$_arr[]['value']="$_sex";
		}

	}
	//***********现居地的查询
	if($_POST['residentCountry']!=-1){
		$_residentCountryId=$_POST['residentCountry'];
		$_sql.="residentCountry='$_residentCountryId' and ";
		$_sqlTemp="select name from country where id=$_residentCountryId";
		$_res=$globalService->_query($_sqlTemp);
		$_arr[]['key']="现居国家";
		$_arr[]['value']=$_res[0]['name'];

	}
	if($_POST['residentProvince']!=-1){
		$_residentProvinceId=$_POST['residentProvince'];
		$_sql.="residentProvince='$_residentProvinceId' and ";
		$_sqlTemp="select name from province where id=$_residentProvinceId";
		$_res=$globalService->_query($_sqlTemp);
		$_arr[]['key']="现居省份";
		$_arr[]['value']=$_res[0]['name'];
	}
	if($_POST['residentCity']!=-1){
		$_residentCityId=$_POST['residentCity'];
		$_sql.="residentCity='$_residentCityId' and ";
		$_sqlTemp="select name from city where id=$_residentCityId";
		$_res=$globalService->_query($_sqlTemp);
		$_arr[]['key']="现居城市";
		$_arr[]['value']=$_res[0]['name'];

	}
	//*************故乡的查询
	if($_POST['homeTownCountry']!=-1){
		$_homeTownCountryId=$_POST['homeTownCountry'];
		$_sql.="homeTownCountry='$_homeTownCountryId' and ";
		$_sqlTemp="select name from country where id=$_homeTownCountryId";
		$_res=$globalService->_query($_sqlTemp);
		$_arr[]['key']="故乡国家";
		$_arr[]['value']=$_res[0]['name'];
	
	}
	if($_POST['homeTownProvince']!=-1){
		$_homeTownProvinceId=$_POST['homeTownProvince'];
		$_sql.="homeTownProvince='$_homeTownProvinceId' and ";
		$_sqlTemp="select name from province where id=$_homeTownProvinceId";
		$_res=$globalService->_query($_sqlTemp);
		$_arr[]['key']="故乡省份";
		$_arr[]['value']=$_res[0]['name'];
	}
	if($_POST['homeTownCity']!=-1){
		$_homeTownCityId=$_POST['homeTownCity'];
		$_sql.="homeTownCity='$_homeTownCityId' and ";
		$_sqlTemp="select name from city where id=$_homeTownCityId";
		$_res=$globalService->_query($_sqlTemp);
		$_arr[]['key']="故乡城市";
		$_arr[]['value']=$_res[0]['name'];
	
	}
	
	//**************身高范围
	if($_POST['heightStart']!=-1 || $_POST['heightEnd']!=9999){
		$_sql.="height>='{$_POST['heightStart']}' and height<='{$_POST['heightEnd']}' and ";
		$_arr[]['key']="身高范围";
		if($_POST['heightStart']==-1){$_POST['heightStart']='不限';}
		if($_POST['heightEnd']==9999){$_POST['heightEnd']='不限';}
		$_arr[]['value']=$_POST['heightStart'].'--'.$_POST['heightEnd'];
	
	}
	//**************体重范围
	if($_POST['weightStart']!=-1 || $_POST['weightEnd']!=9999){
		$_sql.="weight>='{$_POST['weightStart']}' and weight<='{$_POST['weightEnd']}' and ";
		$_arr[]['key']="体重范围";
		if($_POST['weightStart']==-1){$_POST['weightStart']='不限';}
		if($_POST['weightEnd']==9999){$_POST['weightEnd']='不限';}
		$_arr[]['value']=$_POST['weightStart'].'--'.$_POST['weightEnd'];
	
	}
	//***************星座
	if($_POST['constellation']!=-1 ){
		$_sql.="constellation='{$_POST['constellation']}' and ";
		$_arr[]['key']="星座";
		$_arr[]['value']=$_POST['constellation'];
	
	}
	//***************身份
	if($_POST['education']!=-1 ){
		$_sql.="education='{$_POST['education']}' and ";
		$_arr[]['key']="身份";
		$_arr[]['value']=$_POST['education'];
	
	}
	//***************昵称
	if($_POST['nickname']!='' ){
		$_sql.="nickname='{$_POST['nickname']}' and ";
		$_arr[]['key']="昵称";
		$_arr[]['value']=$_POST['nickname'];
	
	}
	//***************用户名
	if($_POST['username']!='' ){
		$_sql.="username='{$_POST['username']}' and ";
		$_arr[]['key']="用户名";
		$_arr[]['value']=$_POST['username'];
	
	}
	//***************入学年份
	if($_POST['entryUniversityTime']!=-1 ){
		$_sql.="entryUniversityTime='{$_POST['entryUniversityTime']}' and ";
		$_arr[]['key']="入学年份";
		$_arr[]['value']=$_POST['entryUniversityTime'];
	
	}
	//***************现读学校
	if($_POST['college']!='' ){
		$_sql.="college='{$_POST['college']}' and ";
		$_arr[]['key']="现读学校";
		$_arr[]['value']=$_POST['college'];
	
	}
	
	
	$_sql.="id>0";//为了凑句子

	$_SESSION['searchKeyWord']=$_arr;
	//跳转到搜索结果界面
 	header("Location:searchResult.php?search=$_sql");
// 	file_put_contents('1.txt', $_sql."\r\n",FILE_APPEND);
// 	print_r($_arr);
// 	exit();
	
	
	
}else{
	_alert_back("非法登录");
}






















?>