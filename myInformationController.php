<?php
	session_start();
	//定义个常量，用来授权调用includes里面的文件
	define('IN_TG',true);
	//引入公共文件
	require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
	include  ROOT_PATH.'class/MyInformationService.class.php';
	header("Content-Type: text/html;charset=utf-8");
	header("Cache-Control: no-cache");
	
	//file_put_contents("1.txt", "1111"."\r\n",FILE_APPEND);
	//global $myInformationService;
	$myInformationService=new MyInformationService();
	//file_put_contents("1.txt", "222"."\r\n",FILE_APPEND);
	
//*************************************修改来自修改页面的数据提交***********************/
//进行数据的接收
if($_GET['action']=='modify'){
	
	//属于危险操作 验证用户的是否合法
	$_sql="select uniqid from user where email='{$_COOKIE['email']}' limit 1 ";
	global $globalService;
	$_arr=$globalService->_query($_sql);
	if($_arr){
		_uniqid($_arr[0]['uniqid'], $_COOKIE['uniqid']);
		//为了防止恶意注册，跨站攻击  到底前面已经验证了现在这里还要不要呢????2014-12-30 16:16
		//我在前面添加了完整的js验证，那么这里还要不要呢 2014-12-31  11:55
		//_check_code($_POST['code'],$_SESSION['code']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		//创建一个空数组，用来存放提交过来的合法数据
		$_clean = array();
		//创建sql语句
		$_sql="update user set ";
		//可以通过唯一标识符来防止恶意注册，伪装表单跨站攻击等。
		//这个存放入数据库的唯一标识符还有第二个用处，就是登录cookies验证
		//$_clean['uniqid'] = _check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
		//active也是一个唯一标识符，用来刚注册的用户进行激活处理，方可登录。
		//$_clean['active'] = _sha1_uniqid();
		//注册的邮箱   ajax动态验证的时候已经到数据库中进行了验证，所以这个地方不需要再次验证了
		//$_clean['email'] = _check_email($_POST['email'],6,40);
		//头像	需要判断是不是规定的图片的格式  图片的大小    如果改变了就更新

		if(!empty(($_FILES['upLoadFile']['name']))){
			$_clean['face'] = _check_face($_FILES['upLoadFile']['name']);
			$_sql.="face='{$_clean['face']}',";
// 			echo $_clean['face'];
// 			exit();
		}
		
		//姓名(必须)	
		//$_clean['username'] = _check_username($_POST['username'],2,20);
		//昵称(不是必须)
		$_clean['nickname']=_check_nickname($_POST['nickname'],1,10);
		//密码(必须)
		if(!empty($_POST['password'])){
	 		//file_put_contents("1.txt", "1212"."\r\n",FILE_APPEND);
			$_clean['password'] = _check_password($_POST['password'],$_POST['notpassword'],6);
			$_sql.="password='{$_clean['password']}',";
// 			echo $_sql;
// 			exit();
		}
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
		global $globalService;
		//$globalService=new GloablService();
		$_sql.="nickname='{$_clean['nickname']}',sex='{$_clean['sex']}',education='{$_clean['education']}',residentcountry='{$_clean['residentcountry']}',
				residentprovince='{$_clean['residentprovince']}',residentcity='{$_clean['residentcity']}',hometowncountry='{$_clean['hometowncountry']}',
				hometownprovince='{$_clean['hometownprovince']}',hometowncity='{$_clean['hometowncity']}',entryUniversityTime='{$_clean['entryUniversityTime']}',birthday='{$_clean['birthday']}',college='{$_clean['college']}',
				constellation='{$_clean['constellation']}',height='{$_clean['height']}',weight='{$_clean['weight']}',selfdescription='{$_clean['selfdescription']}',
				interest='{$_clean['interest']}',futurethingking='{$_clean['futurethingking']}',weixin='{$_clean['weixin']}',phonenumber='{$_clean['phonenumber']}',qq='{$_clean['qq']}',
				url='{$_clean['url']}' ";
		//$_COOKIE['email'] 在loginController.php的时候设置过的。这个是我修改后设置的。2015-01-05 22:23
		$_sql.="where email='{$_COOKIE['email']}'";
		
		//file_put_contents("1.txt", $_sql."\r\n",FILE_APPEND);
		//exit();		
		$_res=$globalService->_manipulate($_sql);
		
		//可能前面的那些信息没有改动 所以$_res==2也是可能的
		if($_res==1 || $_res==2){
			
			//获取用户的ID
			$_userId=$_COOKIE['userId'];
			//判定有没有进行头像的磁盘存入和数据库中的再次修改(以防重名情况)
			if(!empty(($_FILES['upLoadFile']['name']))){

				/*******Start--头像上传处理*******/
				//需要上传的照片的名字$uploadService 的返回值吧
				include 'class/UploadService.class.php';
				$uploadService=new UploadService();
				$_name="upLoadFile";
				$_photography_name="我的头像";
				$_photo_descript="头像的描述怎么设计好呢?";
				$_photo_name=$uploadService->_upload_photo($_photography_name, $_photo_descript,$_name);
				//		不需要，因为第一次注册的时候，不可能会有重复名字，但是在修改信息的时候可能会出现这种情况，所以在修改信息的时候这些语句是需要的。
				// 		//更新下face的名字，因为考虑到用户上传的文件的名字是一样的，我在上传的时候对重名的文件进行过处理，为了保证没有bug此处进行更新下
				$_sql="update user set face='$_photo_name' where id=$_userId limit 1" ;
				$_res=$globalService->_manipulate($_sql);
			}

		
			/*******End--头像上传处理*******/		
			//激活工作  以后再做
			_location('恭喜你，修改成功！','myInformation.php');
		}
	}
	
}else{
	
	//***********************************处理验证码问题**********************************/
	if(isset($_POST['code'])){
		$_code=$_POST['code'];
		//file_put_contents("1.txt", $_SESSION['code']."\r\n",FILE_APPEND);
		if($_code!=$_SESSION['code']){
			$res='{"match":"1"}';
		}else{
			$res='{"match":"2"}';
		}
		echo $res;
		//file_put_contents("1.txt", $res."\r\n",FILE_APPEND);
		//exit();
	}
	
	
	
	
	//*******************************************SELECT相关************************************	
//下面注释的部分好像没有什么用途
// 	if(!empty($_POST['idname'])){
// 		$id=$_POST['idname'];
// 		$myInformationService->ShowProvinces($id);
// 		//echo'llll';
// 	}
	//***Sel下的省份
	if(!empty($_POST['CountryId'])){
		$id=$_POST['CountryId'];
		//file_put_contents("1.txt", $id."\r\n",FILE_APPEND);
		$res=$myInformationService->getProvince($id);
		//file_put_contents("1.txt", $res."\r\n",FILE_APPEND);
		//exit();
		echo $res;
	}
	//***Sel下的城市
	if(!empty($_POST['ProvinceId'])){//
		$id=$_POST['ProvinceId'];
		$res=$myInformationService->getCity($id);
		echo $res;
		
	
	}
	//*************************************DIV相关******************************************/
	//***College下的默认大学 
	if(!empty(($_POST['collegeCountryIdForDefaultCollege']))){
		$countryId=$_POST['collegeCountryIdForDefaultCollege'];
		$proId=$myInformationService->getProId($countryId);
		$res=$myInformationService->getColleges($countryId, $proId);
		//file_put_contents('../1.txt',$proId.$countryId,FILE_APPEND);
		//$colleges=$myInformationService->ShowColleges($countryId,$proId);
		//$res.='{"colleges":"'.$colleges.'"}';
		//file_put_contents('../1.txt',$res."\n",FILE_APPEND);
		//exit();
		echo $res;
	}
	//***College下的点击省份后的大学
	if(!empty(($_POST['collegeCountryIdForCollege'])&&($_POST['proId']))){
		$countryId=$_POST['collegeCountryIdForCollege'];
		$proId=$_POST['proId'];
		$res=$myInformationService->getColleges($countryId, $proId);
		//file_put_contents('../1.txt',$proId.$countryId,FILE_APPEND);
		//$colleges=$myInformationService->ShowColleges($countryId,$proId);
		//$res.='{"colleges":"'.$colleges.'"}';
		//file_put_contents('../1.txt',$res."\n",FILE_APPEND);
		//exit();
		echo $res;
	}
}
	?>
	
