<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-28
*/
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
header("Content-Type: text/html;charset=utf-8");
header("Cache-Control: no-cache");

//进行数据的接收
if ($_GET['action'] == 'register') {
	//为了防止恶意注册，跨站攻击  到底前面已经验证了现在这里还要不要呢????2014-12-30 16:16
	//我在前面添加了完整的js验证，那么这里还要不要呢 2014-12-31  11:55
	_check_code($_POST['code'],$_SESSION['code']);
	//引入验证文件
	include ROOT_PATH.'includes/check.func.php';
	//创建一个空数组，用来存放提交过来的合法数据
	$_clean = array();
	//可以通过唯一标识符来防止恶意注册，伪装表单跨站攻击等。
	//这个存放入数据库的唯一标识符还有第二个用处，就是登录cookies验证
	$_clean['uniqid'] = _check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
	//active也是一个唯一标识符，用来刚注册的用户进行激活处理，方可登录。
	$_clean['active'] = _sha1_uniqid();
	
	//注册的邮箱   ajax动态验证的时候已经到数据库中进行了验证，所以这个地方不需要再次验证了
	$_clean['email'] = _check_email($_POST['email'],6,40);
	//由于出现浏览器自动填表的情况 导致用户不会去点击input框而不会触发邮箱在数据库中是否存在所以我在这里再次去验证数据库中是否存在
	//到底要不要考虑这么麻烦呢
	global $globalService;
//	我可以让register.php加载的时候，自动focus邮箱输入input不就解决这个问题了么nice!日期 2015-01-13 22:05
// 	$_sql="select id from user where email='{$_clean['email'] }'";
// 	$_res=$globalService->_query($_sql);
// 	if($_res){
// 		_alert_back("由于浏览器自动填表的缘故，没有触发ajax进行邮箱是否已经注册验证，该邮箱已经注册，请登录，或者另用邮箱登陆");
// 	}

	//$_POST['upLoadFile']是看不到的 可能是我更改了form表单的编码格式了吧
	//**********************$_clean['face']实际上是空的
// 	$_clean['face'] = _check_face($_POST['upLoadFile']);
	$_clean['face'] = _check_face($_FILES['upLoadFile']['name']);
	
// 	echo $_clean['face'];
// 	exit();
	
	//姓名(必须)	
	$_clean['username'] = _check_username($_POST['username'],2,20);
	//昵称(不是必须)
	$_clean['nickname']=_check_nickname($_POST['nickname'],1,10);
	//密码(必须)
	$_clean['password'] = _check_password($_POST['password'],$_POST['notpassword'],6);
	//性别(必须)
	$_clean['sex'] = _check_type_one($_POST['sex']);
	//身份(不是必须)
	$_clean['education']=_check_type_two($_POST['education']);
	//现居地 (必填)
	$_clean['residentcountry']=_check_type_one($_POST['residentCountry']);
	$_clean['residentprovince']=_check_type_one($_POST['residentProvince']);
	$_clean['residentcity']=_check_type_one($_POST['residentCity']);
	//故乡 不是必填
	$_clean['hometowncountry']=_check_type_two($_POST['homeTownCountry']);
	$_clean['hometownprovince']=_check_type_two($_POST['homeTownProvince']);
	$_clean['hometowncity']=_check_type_two($_POST['homeTownCity']);
	//入学年份 不是必填
	$_clean['entryUniversityTime']=_check_type_two($_POST['entryUniversityTime']);
	//公历生日  必填
	$_clean['birthday']=_check_type_one($_POST['calendar']);
	//现读大学  必填
	$_clean['college']=_check_type_one($_POST['college']);
	//星座   不是必填
	$_clean['constellation']=_check_type_two($_POST['constellation']);
	//身高   不是必填
	$_clean['height']=_check_type_two($_POST['height']);
	//体重   不是必填
	$_clean['weight']=_check_type_two($_POST['weight']);
	//自我描述  必须填写
	$_clean['selfdescription']=_check_type_two($_POST['selfDescription']);
	//兴趣爱好   不是必填
	$_clean['interest']=_check_type_two($_POST['interest']);
	//畅想未来	 不是必填
	$_clean['futurethinking']=_check_type_two($_POST['futurethinking']);
	//微信	不是必填
	$_clean['weixin']=_check_type_two($_POST['weixin']);
	//手机	不是必填
	$_clean['phonenumber']=_check_type_two($_POST['phonenumber']);
	//QQ号码	不是必填
	$_clean['qq']=_check_qq($_POST['qq']);
	//主页	不是必填
	$_clean['url']=_check_url($_POST['url'],40);

	//print_r($_clean);
	//新增用户  //在双引号里，直接放变量是可以的，比如$_username,但如果是数组，就必须加上{} ，比如 {$_clean['username']}
	//$globalService=new GloablService();
	$_sql="insert into user(uniqid,active,email,face,username,nickname,password,
			sex,education,residentcountry,residentprovince,residentcity,hometowncountry,
			hometownprovince,hometowncity,birthday,college,constellation,height,weight,selfdescription,
			interest,futurethingking,weixin,phonenumber,qq,url,registerdate,logindate,lastvisitip)";
	$_sql.="values('{$_clean['uniqid']}','{$_clean['active']}','{$_clean['email']}','{$_clean['face']}',
			'{$_clean['username']}','{$_clean['nickname']}','{$_clean['password']}','{$_clean['sex']}','{$_clean['education']}',
			'{$_clean['residentcountry']}','{$_clean['residentprovince']}','{$_clean['residentcity']}','{$_clean['hometowncountry']}',
			'{$_clean['hometownprovince']}','{$_clean['hometowncity']}','{$_clean['birthday']}','{$_clean['college']}','{$_clean['constellation']}',
			'{$_clean['height']}','{$_clean['weight']}','{$_clean['selfdescription']}','{$_clean['interest']}','{$_clean['futurethingking']}','{$_clean['weixin']}',
			'{$_clean['phonenumber']}','{$_clean['qq']}','{$_clean['url']}',now(),now(),'{$_SERVER["REMOTE_ADDR"]}')";
	
	//file_put_contents("1.txt", $_sql."\r\n",FILE_APPEND);
	//exit();
	$_res=$globalService->_manipulate($_sql);
	if($_res==1){
		//获取用户的ID
		$_sql="select id from user where email='{$_clean['email']}' limit 1";
		$_arr=$globalService->_query($_sql);
		$_userId=$_arr[0]['id'];
		/*******Start--头像上传处理*******/
		//需要上传的照片的名字$uploadService 的返回值吧
		include 'class/UploadService.class.php';
		$uploadService=new UploadService();
		$_name="upLoadFile";
		$_photography_name="我的头像";
		$_photo_descript="头像的描述怎么设计好呢?";
		$_photo_name=$uploadService->_upload_photo($_photography_name, $_photo_descript,$_name);
// 		//不需要，因为第一次注册的时候，不可能会有重复名字，但是在修改信息的时候可能会出现这种情况，所以在修改信息的时候这些语句是需要的。
// 		//需要因为上面根本接受不到文件的名字
// 		//更新下face的名字，因为考虑到用户上传的文件的名字是一样的，我在上传的时候对重名的文件进行过处理，为了保证没有bug此处进行更新下
// 		$_sql="update user set face='$_photo_name' where id=$_userId";
// 		$globalService->_manipulate($_sql);
		
		/*******End--头像上传处理*******/
		
		//激活工作  以后再做
		_location('恭喜你，注册成功！','login.php');
	}else{
		_alert_back("注册失败，请重新尝试。写入数据库失败");
	}
	
	
	
}else{
	
	//动态返回用户注册用的邮箱是否被注册过了          注意  isset不能用!empty代替
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