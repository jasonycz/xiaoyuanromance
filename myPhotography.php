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
//定义标题
define("TITLE_NAME", "{$_COOKIE['username']}的相册");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','myPhotography');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	//_alert_close('请先登录！');
// 	_location('请先登录！',"login.php");
// }
//根据$_COOKIE['email']取出userId;
if(isset($_COOKIE['email'])){
$_userId=$_COOKIE['userId'];
		
}
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
			//取出相册的并且按照<option>方式显示出来
			include ROOT_PATH.'class/PhotographyService.class.php';
			$photographyService=new PhotographyService();
		
		?>
		<div id="lightbox" > 
			<!-- 显示上拉相册名字的那个div -->
			<div id="allPhotography"  >
				<?php $photographyService->getPhotographyLi($_userId); ?>
			</div>
			<div id="addPhotoDiv" >
			 <form action="myPhotoUploadController.php?action=upload " method="post" enctype="multipart/form-data">
				
				<table >
					<tr><td></td><td><span id="closePhotoDiv"></span></td></tr>
					<tr><td>
					<div id="preview">
						<input type="button" value="点击文件上传" id="modulateFileUpload" />
						<img id="imghead" src='' />
					</div></td><td></td></tr>
		 			<tr><td>
					    	<table>
							   <tr ><td rowspan="2">相片的描述:<br /><textarea rows="4px" cols="30px" name="photoDescript" placeholder="描述请不要超过50个字"></textarea></td>
							   <td>上传相册名:<input  type="text" name="upLoadPhotographyName" id="upLoadPhotographyName"  value=" " />
							   <span id="popupMenu">︽</span></td></tr>          
							   <tr><td>
							   <input type="file" name="upLoadFile" id="upload" accept="image/png,image/jpeg,image/gif,image/x-ms-bmp,image/bmp" onchange="previewImage(this)"  />
							   <input type="submit" value="点击上传" class="button" /></td></tr> 
						     </table>
				    </td><td></td></tr>
			    </table>
			   </form>
			</div>
		</div>
<!-- /************************相册显示部分*****************/	 -->
	<?php require ROOT_PATH.'includes/header.inc.php';?>
	<div class="photography">
	<!-- //************************添加相片的按钮-->
		<div class="addButton"><button id="openPhotoDiv">添加照片 </button></div>		
	<!-- //************************显示相册 -->
		<div class="myPotography">
			<?php 
				echo "我的相册:<br>";
				//以后userId可以通过session获取 即是获取登录后的用户
				$html=$photographyService->showPhotography($_userId);
				echo $html;
			?>
		</div>

	</div>
		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
		
	<!-- ------------------------------------jQery代码-------------------------------------------- -->		
	<script type="text/javascript">//***************点击上传触发实际的文件上传按钮nice这是自己的一个思考
		$('#modulateFileUpload').click(function(){
			//alert('ok');
			$('#upload').click();
		});
	</script>
	<script type="text/javascript">//****************点击删除按钮的时候出发事件
	$('.deletePhotography').click(function(){
		if(confirm("确认删除该相册")){
			return true;
		}
		return false;
	});

	</script>
	<script type="text/javascript">//*****************点击打开添加照片的DIV
		$("#openPhotoDiv").click(function(){
			$('#addPhotoDiv').slideDown("fast");
			$('#lightbox').css({'display':'block','background':'#ccc'});
			});
		$("#closePhotoDiv").click(function(){
			$('#addPhotoDiv').slideUp("fast");
			$('#lightbox').css({'display':'none'});
			});
	</script>
	<script type="text/javascript">//***************点击显示上拉相册的div
	 $('#popupMenu').click(function(){
		 $("#allPhotography").toggle();
		 //alert($(this).offset().top+' '+$(this).offset().left);
		 //alert($("#allPhotography").outerHeight()+' '+$("#allPhotography").outerWidth());
   		 $("#allPhotography").css("top",$(this).offset().top-$("#allPhotography").outerHeight() );
   		 $("#allPhotography").css("left",$(this).offset().left-$("#allPhotography").outerWidth());
		
		 });
	
	</script>
	<script type="text/javascript">//*************一开始就给相册赋值即给$('#upLoadPhotographyName')赋值
	$('#upLoadPhotographyName').val(getDate());
	</script>
	<script type="text/javascript">//***************点击上拉相册的div中的li，1.把li中的值放到upLoadPhotographyName中 
								   //2.关闭上拉所有相册的div
		$('#allPhotography li').click(function(){
			//alert($(this).html());
			$('#upLoadPhotographyName').val($(this).html());
			//关闭#allPhotography
			$("#allPhotography").hide();
		});
	
	</script>

	

	</body>
</html>