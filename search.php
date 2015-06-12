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
//定义标题
define("TITLE_NAME", "搜索");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','search');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
//定义本页要跳转的页面的一个常量myInformationController.php为ajax取数据时放的一些函数
define('CONTROLLER_PATH', "myInformationController.php");
// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	//_alert_close('请先登录！');
// 	_location('请先登录！',"login.php");
// }

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
		
			require ROOT_PATH.'includes/header.inc.php';
			include ROOT_PATH.'class/MyInformationService.class.php';
			$myInformationService=new MyInformationService();
			global $globalService;
		
		?>
	
		<div class="search">
			
		    <!--*********************选取大学的那个div-->
			<div class="collegeDiv">
				<table  >
					<tr><td id='collegeCountry'><?php echo $myInformationService->ShowCountry();?></td></tr>
					<tr ><td id='collegeProvince'><?php echo $myInformationService->ShowProvinces('1');?></td></tr>
					<tr ><td><div id='colleges'><?php echo $myInformationService->ShowColleges('1','1');?></div></td></tr>
				</table>
			</div>
		<!-- *******************个人信息******************** -->
    	<div class="searchInformation">
	    	 <!-- *****************要提交的信息****************** -->
	    	<form action="searchController.php?action=search" method="post" enctype="multipart/form-data" >   
			    <table>
			    	<tr>
			    		<td class="InformationTd1" align="center">搜索信息</td>
			    	</tr>
			    </table>
				<div id="searchInfo">				
					<table >
					    <tr>
					    	<td>年龄范围:<select id="ageStart" name="ageStart"><option value="-1">--不限--</option><?php  $myInformationService->getAge();?></select>--<select id="ageEnd" name="ageEnd"><option value="9999">--不限--</option><?php  $myInformationService->getAge();?></select></td>
						    <td>性&nbsp;别:<input type="radio" name="sex" checked="checked" value="不限"/>不限<input type="radio" name="sex"  value="男"/>男<input type="radio" name="sex" value="女"/>女</td>		    
						    
					    </tr>
					    <tr>
						    <td>现&nbsp;居&nbsp;地&nbsp;&nbsp;:<select id='residenceCountry' name="residentCountry" ><option value="-1">--国家--</option><?php $myInformationService->ShowCountryInSelect(); ?></select><select id='residenceProvince' name="residentProvince" ><option value="-1">--省份--</option></select><select id='residenceCity' name="residentCity" ><option value="-1">--城市--</option></select></td>
						    <td>故乡&nbsp;:<select id='homeTownCountry' name="homeTownCountry" ><option value="-1">--国家--</option><?php $myInformationService->ShowCountryInSelect(); ?></select><select id='homeTownProvince' name="homeTownProvince" ><option value="-1">--省份--</option></select><select id='homeTownCity' name="homeTownCity" ><option value="-1">--城市--</option></select></td>
					    </tr>	
					    <tr>
						    <td>性格类型:(弹出DIV让用户选择，DIV中是一些标签)</td>
						    <td>兴趣爱好:(弹出DIV让用户选择，DIV中是一些标签)</td>
				    	</tr>
					    <tr>
							 <td>身高范围:<select id="height" name="heightStart"><option value="-1">--不限--</option><?php  $myInformationService->getHeight();?></select>--<select id="height" name="heightEnd"><option value="9999">--不限--</option><?php  $myInformationService->getHeight();?></select>cm</td>
							 <td>体重范围:<select id="weight" name="weightStart"><option value="-1">--不限--</option><?php  $myInformationService->getWeight();?></select>--<select id="weight" name="weightEnd"><option value="9999">--不限--</option><?php  $myInformationService->getWeight();?></select>kg</td>
					 	</tr>

					    <tr>
					    	<td>星　　座:<select id="constellation" name="constellation"><option value="-1">--不限--</option><?php  $myInformationService->getConstellation();?></select></td>
				    		<td>身份&nbsp;:<select id="education" name="education"><option value="-1">--不限--</option><option value="博士">博士</option><option value="硕士">硕士</option><option value="本科">本科</option></select></td>
				    	</tr>
					    <tr>
					    	<td>昵　&nbsp;&nbsp;&nbsp;称:<input type="text" name="nickname"/></td>
					    	<td>姓名:<input type="text" name="username"/></td>
						</tr>
					    
					    <tr>
					    	<td>入学年份:<select id="entryUniversityTime" name="entryUniversityTime"><option value="-1">--请选择--</option><?php $myInformationService->getEntryUniversityTime();?></select>年</td>
					    	<td>现读大学:<input type="text" name="college" id="college"/></td>
					    </tr>
					    
				    </table>
				</div> 
					<input type="submit"  value="搜索" class="button"/>
					<input type="reset" value="取消" class="button" onclick="cancel()"/>
					<br></br>
			</form>
			     
		</div>
			
	</div>	  
		
	
	
	



		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
	<!-- ------------------------------------jQery代码-------------------------------------------- -->
	
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
				//alert($(this).val());
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
		

	
	
	</body>
</html>