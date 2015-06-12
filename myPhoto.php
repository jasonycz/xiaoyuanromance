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
define("TITLE_NAME", "{$_COOKIE['username']}的相片");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','myPhoto');
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
//获取相册信息
if(!empty($_GET['photographyId'])){
	$_photography_id=$_GET['photographyId'];
	global $globalService;
	
	//获取相册的名字
	$_sql="select name from photography where id=$_photography_id";
	$_clean=$globalService->_query($_sql);
	$_clean=_html($_clean);
	$_photography_name=$_clean[0]['name'];
	
}else{
	_alert_back('非法操作！');
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php 
			require ROOT_PATH.'includes/title.inc.php';
		?>
		<link rel="stylesheet" type="text/css" href="styles/lightbox.css" />
		<link href="./styles/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<script src="./js/jquery.lightbox.js"></script>
	</head>	
	<body>
		<?php require ROOT_PATH.'includes/header.inc.php';?>

		<div class="myPhoto">
			<div class="myPhotoNav">
				<a href="myPhotography.php">我的相册</a>》<?php echo $_photography_name;?>
			</div>
			<div class="myPhotos">
				<?php 
					//分页模块  1.获取相应的参数
					global $_pagesize,$_pagenum,$_id;
					//global  $_id;我是没有想到  真他妈的精髓
					$_id = 'photographyId='.$_photography_id.'&';
					//获取相应的全局参数
					$_sql="select id from photo where photographyId=$_photography_id order by addTime desc";
					$_size=10;
					$_type=1;
					_page($_sql, $_size);
					//2.取出每个页面需要的记录
					$_sql="select id,photo,addTime,descript from photo where photographyId=$_photography_id order by addTime desc limit $_pagenum,$_pagesize ";
					$_clean=array();
					$_clean=$globalService->_query($_sql);
	
				?>

				<?php 
					//*****Start--对分页模块得到的数据进行处理
					//对$_photography_name进行转码
					//3.对2中取出的记录数进行处理
					$_photography_name_for_url=preg_replace("/\ /", "\\ ", $_photography_name);
					$_html='';
					for($i=0;$i<count($_clean);$i++){
						if(!$_clean[$i]['descript']){
							$_clean[$i]['descript']="还没有添加描述呃";
						}

						$_html.="<div class='photoOuterMostDiv' style='background-image:url(../UserResourse/Photography/$_userId/$_photography_name_for_url/{$_clean[$i]['photo']});'>";
						if(!empty($_userId)){
							$_html.="<a class='deletePhoto' href='myPhotoOrPhotographyDeleteController.php?action=deletePhoto&photography=$_photography_name&photoId={$_clean[$i]['id']}'></a>";
						}
						$_html.="<div class='overlay'>";
						$_html.="<a href='../UserResourse/Photography/$_userId/$_photography_name/{$_clean[$i]['photo']}' data-rel='lightbox' class='fa fa-expand' descript='photoDescript:{$_clean[$i]['descript']};  AddTime:{$_clean[$i]['addTime']}'></a>";
						$_html.="</div>";
						$_html.="</div>";

					}
					echo $_html;
					//*****End--对分页模块得到的数据进行处理
				?>
				
			</div>
		
			<?php
				//4.分页模式的设置
				 _paging($_type);
			?>
		</div>
		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
		
	<!-- ------------------------------------jQery代码-------------------------------------------- -->
		<script type="text/javascript">//****************点击删除按钮的时候出发事件
			$('.deletePhoto').click(function(){
				if(confirm("确认删除该相片")){
					return true;
				}
				return false;
			});
	
		</script>
	
	
	
	
	</body>
</html>








































