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
//定义标题
define("TITLE_NAME", "注册");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','register');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
//定义本页要跳转的页面的一个常量myInformationController.php为ajax取数据时放的一些函数
define('CONTROLLER_PATH', "myInformationController.php");

//登录状态
//_login_state();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		require ROOT_PATH.'includes/title.inc.php';
	?>	
</head>	
<body>
	<?php 
		include ROOT_PATH.'class/MyInformationService.class.php';
		$myInformationService=new MyInformationService();
// 	 		require  ROOT_PATH.'class/MyInformationService.class.php';
// 	 		$myInformationService=new MyInformationService();
	?>

	<div class="register">
		<div id="header"><p>Welcome to xiaoyuanromance!</p></div>
		<?php $_SESSION['uniqid'] = $_uniqid = _sha1_uniqid();?>
	    <!--*********************选取大学的那个div-->
		<div class="collegeDiv">
			<table  >
				<tr><td id='collegeCountry'><?php echo $myInformationService->ShowCountry();?></td></tr>
				<tr ><td id='collegeProvince'><?php echo $myInformationService->ShowProvinces('1');?></td></tr>
				<tr ><td><div id='colleges'><?php echo $myInformationService->ShowColleges('1','1');?></div></td></tr>
			</table>
		</div>
		<!-- 隐藏的日历 -->
		<div id="oContainer"></div>
		<!-- *******************个人信息******************** -->
    	<div class="BasicPersonalInformation">
	    	 <!-- *****************要提交的信息****************** -->
	    	<form action="registerController.php?action=register" method="post" enctype="multipart/form-data" >
			   
			    <table>
			    	<tr>
			    		<td class="InformationTd1">基本信息</td>
			    		<td align="center" class="InformationTd2" onclick="show(this)" id="BasicInfohead"><span >︽</span></td>
			    	</tr>
			    </table>
				<div id="BasicInfo">				
					<table >
						
						<tr><td>注册:<input type="text" name="email" class="email" placeholder="请填写注册邮箱/手机"/><span id="emailSpan">(*必填，激活账户)</span></td>
						<td>头像上传:<input type="file" name="upLoadFile" accept="image/png,image/jpeg,image/gif,image/x-ms-bmp,image/bmp"  />(*必填)</td></tr>
					    <tr><td>姓名:<input type="text" name="username"/>(*请填写真实姓名，以后不能更改)</td><td >
					   	昵　　称:<input type="text" name="nickname"/>(1～10位)</td></tr>
					   	<tr><td>密码:<input type="password" name="password"  />(*必填，至少六位)</td>
					   	<td>确认密码:<input type="password" name="notpassword"  />(*必填，至少六位)</td></tr>
					    <tr><td>性&nbsp;别:　<input type="radio" name="sex" checked="checked" value="男"/>男&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="sex" value="女"/>女&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(*必填)</td>		    
					    <td>身份:<select id="education" name="education"><option value="-1">--请选择--</option><option value="博士">博士</option><option value="硕士">硕士</option><option value="本科">本科</option></select></td></tr>
					    <tr><td>现&nbsp;居&nbsp;地&nbsp;:<select id='residenceCountry' name="residentCountry" ><option value="-1">--国家--</option><?php $myInformationService->ShowCountryInSelect(); ?></select><select id='residenceProvince' name="residentProvince" ><option value="-1">--省份--</option></select><select id='residenceCity' name="residentCity" ><option value="-1">--城市--</option></select>(*必填)</td>
					    <td>故乡:<select id='homeTownCountry' name="homeTownCountry" ><option value="-1">--国家--</option><?php $myInformationService->ShowCountryInSelect(); ?></select><select id='homeTownProvince' name="homeTownProvince" ><option value="-1">--省份--</option></select><select id='homeTownCity' name="homeTownCity" ><option value="-1">--城市--</option></select></td></tr>	
					    <tr><td>入学年份:<select id="entryUniversityTime" name="entryUniversityTime"><option value="-1">--请选择--</option><?php $myInformationService->getEntryUniversityTime();?></select>年</td><td>现读大学:<input type="text" name="college" id="college"/>(*必填)</td></tr>
					    <tr><td>星　　座:<select id="constellation" name="constellation"><option value="-1">--请选择--</option><?php  $myInformationService->getConstellation();?></select></td><td>公历生日:<input type="text" name="calendar" id="calendar" />(*必填)</td></tr>
				    </table>
				</div> 
				  <br></br>
	 <!-- *******************私人信息 ******************* -->
			<table>
			    <tr>
				    <td class="InformationTd1">私人信息</td>
				    <td align="center" class="InformationTd2" onclick="show(this)" id="PrivateInfohead"><span >︽</span></td>
			    </tr>
		    </table>
			<div id="PrivateInfo">
				<table >
				 <tr><td>身&nbsp;&nbsp;&nbsp;&nbsp;高:<select id="height" name="height"><option value="-1">--请选择--</option><?php  $myInformationService->getHeight();?></select>cm</td>
				 <td >体重:<select id="weight" name="weight"><option value="-1">--请选择--</option><?php  $myInformationService->getWeight();?></select>kg</td></tr>
			     <tr><td>自我描述:(*必填)<br /><textarea cols="40" rows="8" placeholder="我是一个什么样的人呢在Ta面前展现自我吧" name="selfDescription" ></textarea></td>
			     <td>兴趣爱好:<br /><textarea cols="40" rows="8" placeholder="我喜欢什么样的电影暮光之城nice，音乐呢七里香恩不错！我是运动达人，我爱好火影忍者，lol ye~" name="interest"></textarea></td>
			     </tr>
			     <tr><td>畅想未来:<br /><textarea cols="40" rows="8" placeholder="我以后想在北京发展，我要..." name="futurethinking"></textarea></td></tr>
			     <tr><td colspan="2">联系方式:</td></tr>
			     <tr><td>微信:<input type="text" name="weixin"/></td><td>手机:<input type="text" name="phonenumber" /></td></tr> 
				<tr><td>QQ&nbsp;&nbsp;:<input type="text" name="qq" /></td><td>主页:<input type="text" name="url" class="text" value="" /></td></tr>
				<tr><td>验证码:<input type="text" name="code" class="code" placeholder="请输入验证码" /> <img src="code.php" id="code" onclick="javascript:this.src='code.php?tm='+Math.random();" />  <span id="codeflag" ></span><br></br></td>
				<td><input type="submit"  value="提交" class="button"/><br></br></td>
				</tr>
			    </table>
			     
			</div>
			<input type="hidden" name="uniqid" value="<?php echo $_uniqid ?>" />
		  </form>
		  <br></br><br></br><br></br>
		</div>
<!-- 		<a href="login.php"><button class="button">返回登录界面(需要设计表现形式)</button></a> -->
   	</div>
   	 
   	 <script type="text/javascript">//*******************动态验证邮箱是否被注册过了   动态验证验证码是否输入正确了
   	 	//为解决由于浏览器自动填表的缘故，没有触发ajax进行邮箱是否已经注册验证，我让页面加载后一定focus到邮箱，怎样可定会触发ajax验证邮箱的合法性
   	 	$(document).ready(function(){
   	   	 	$('.email').focus();
   	   	});
		$('.email').blur(function(){
			//alert($(this).val());
			//data为1 显示红色的x并且提示已经被注册了  data为2 显示绿色的勾 显示恭喜可以注册
			var data=_check_email("POST", "registerController.php",$(this).val(),"json");
			if(data==1){
				//$("#emailSpan").html("该邮箱已经注册，若是您的邮箱，请你移步登录界面");
				alert("该邮箱已经注册，若是您的邮箱，请你移步登录界面");
			}else if(data==2){
				$("#emailSpan").html("恭喜你，该邮箱可以注册");
			}else{
				//什么都不做
			}
			
		});

		$('.code').keyup(function(){
			var data=_check_code("POST", "registerController.php",$(this).val(),"json");
			if(data==1){
				//$("#emailSpan").html("该邮箱已经注册，若是您的邮箱，请你移步登录界面");
				//alert("你输入的验证码不正确");
				$('#codeflag').css('background-color','red');
				$('#codeflag').html("验证码不正确");
				$('.code').focus();
				//window.location.href="register.php";
				//return false;
			
			}else if(data==2){
				//$("#emailSpan").html("恭喜你，该邮箱可以注册");
				$('#codeflag').css('background-color','green');
				$('#codeflag').html("验证码正确");
				//$('input[type=submit]').click();
			}else{
				//什么都不做   默认的data应该是0  我添加了else的目地是为了以后可能还有其他的分类的用
			}
		});

   	 </script>
   	 
   	 
   	 <script type="text/javascript"> //*******************************故乡国家省市联动Start
    //***显示  故乡   国家省联动    的回调函数
	$("#homeTownCountry").change(function(){
		var provinceHtml='';
		var cityHtml='';
		//***获取省份		
			//alert($(this).val());
			var data=getProvince("POST", "<?php echo CONTROLLER_PATH;?>",$(this).val(),"json");
			provinceHtml=provinceHtmlFunc(data);
		//***获取省份下的第一个城市
			$('#homeTownProvince').html(provinceHtml);
			//var data=getCity("POST","../PersonalInformation/myInformationProcess.php","1","json");
			var data=getCity("POST",  "<?php echo CONTROLLER_PATH;?>",$('#homeTownProvince').val(),"json");
			cityHtml=cityHtmlFunc(data);
			$('#homeTownCity').html(cityHtml);
			
	});
	//**********显示故乡  省市联动  
		$("#homeTownProvince").change(function(){
			var cityHtml='';
			var data=getCity("POST",  "<?php echo CONTROLLER_PATH;?>",$(this).val(),"json");
			cityHtml=cityHtmlFunc(data);
			$('#homeTownCity').html(cityHtml);
		});
	//*******************************故乡国家省市联动End
	//*******************************现居地国家省市联动Start
    //***显示  现居地   国家省联动   的回调函数
		$("#residenceCountry").change(function(){
			var provinceHtml='';
			var cityHtml='';
			//***获取省份		
			var data=getProvince("POST",  "<?php echo CONTROLLER_PATH;?>",$(this).val(),"json");
			provinceHtml=provinceHtmlFunc(data);
			$('#residenceProvince').html(provinceHtml);
			//获取省份下的第一个城市  这个时候的省份Id可以从<option value="provinceId"></option>中取Nice!!!			
			////alert($('#residenceProvince').val());
			var data=getCity("POST",  "<?php echo CONTROLLER_PATH;?>",$('#residenceProvince').val(),"json");
			cityHtml=cityHtmlFunc(data);
			$('#residenceCity').html(cityHtml);

			
		});
	//***显示现居地   省市联动   的回调函数
		$("#residenceProvince").change(function(){
			var cityHtml='';
			//alert($(this).val());
			var data=getCity("POST",  "<?php echo CONTROLLER_PATH;?>",$(this).val(),"json");
			cityHtml=cityHtmlFunc(data);
			$('#residenceCity').html(cityHtml);
		});	
	//*******************************现居地国家省市联动End
	</script>	
   
    
    <script type="text/javascript">	//********************************获取学校信息Start
	
	//***点击学校input控件弹出学校div
		$('#college').click(function(){
			//打开div
			$('.collegeDiv').css('display','block');
		});	
	//***********情况一:点击国家，然后点击省份或者不点击省份，点击学校，获取学校***********************
		var countryId='1';//初始化国家的Id 放在外面是因为后面直接点击省份要用到，那个时候的国家默认是中国 countryId='1'
		 $('#collegeCountry a').click(function(){
			var provinceHtml='';
			var collegesHtml='';
			countryId=$(this).attr('id');//获取国家的Id
			//***获取省份		
			//alert($(this).attr('id'));
			var data=getProvince("POST",  "<?php echo CONTROLLER_PATH;?>",$(this).attr('id'),"json");
			provinceHtml=provinceHtmlForColleges(data);
			$('#collegeProvince').html(provinceHtml);
	//获取省份下的第一个学校
			//var data=getCity("POST","../PersonalInformation/myInformationProcess.php","1","json");
			var data=getDefaultColleges("POST",  "<?php echo CONTROLLER_PATH;?>",$(this).attr('id'),"json") ;
			collegesHtml=collegesHtmlFunc(data);
			$('#colleges').html(collegesHtml);
			//点击学校控件获取该学校的名称  ???????????函数外面为什么不行呢	难道是和这个点击事件
			$('#colleges a').click(function(){
				$('#college').val($(this).attr('id'));
				//关闭div
				$('.collegeDiv').css('display','none');
			});		
		
	///////////////////////////////////////////针对有点击的情况
			 $('#collegeProvince a').click(function(){
				 collegesHtml='';//不要忘记了!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				 var data=getColleges("POST",  "<?php echo CONTROLLER_PATH;?>",countryId,$(this).attr('id'),"json") ;
				 collegesHtml=collegesHtmlFunc(data);
					$('#colleges').html(collegesHtml);

			//点击学校控件获取该学校的名称  ???????????函数外面为什么不行呢	难道是和这个点击事件
					$('#colleges a').click(function(){
						$('#college').val($(this).attr('id'));
						//关闭div
						$('.collegeDiv').css('display','none');
					});		
			 });							
	///////////////////////////////////////////////////
		 });

	//***********情况二:不点击国家，点击省份，点击学校，获取学校***********************

		 $('#collegeProvince a').click(function(){
			 collegesHtml='';//不要忘记了!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			 var data=getColleges("POST",  "<?php echo CONTROLLER_PATH;?>",countryId,$(this).attr('id'),"json") ;
			 collegesHtml=collegesHtmlFunc(data);
				$('#colleges').html(collegesHtml);

		//点击学校控件获取该学校的名称  ???????????函数外面为什么不行呢	难道是和这个点击事件
				$('#colleges a').click(function(){
					$('#college').val($(this).attr('id'));
					//关闭div
					$('.collegeDiv').css('display','none');
				});		
		 });		
	 
	 
	//***********情况三:不点击国家，不点击省份，点击学校，获取学校***********************
		$('#colleges a').click(function(){
			$('#college').val($(this).attr('id'));
			//关闭div
			$('.collegeDiv').css('display','none');
		});

//***********************************************获取学校信息End	
 
    </script>
   
    <script type="text/javascript"> //*******************************
    //***日历
	 $('#calendar').click(function(){
		$("#calendar").css('width',"203px");
		//获取$("#calendar")的位置，然后让$("#oContainer")绝对定位到那
		showCalendar();
		//以后这里可能需要判定一下  暂时这么判定吧
      $("#oContainer").css("top",$(this).offset().top + $(this).outerHeight());
      //alert($(window).height());//屏幕的高度
      //alert($(document).height());//整个文档的高度
      $("#oContainer").css("left",$(this).offset().left);
     // $("#oContainer").css("top",$(this).offset().top-$("#oContainer").outerHeight() );
		$("#oContainer").css('display',"block");

		 });
    </script>
   	 
   	 

	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
	</body>
</html>