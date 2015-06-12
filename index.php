<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-23 
*
*/
session_start();
//定义标题
define("TITLE_NAME", "{$_COOKIE['username']}的主页");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','index');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

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
	<!-- 加入发表文章的js Start -->
	<script type="text/javascript" src="ueditor/ueditor.config.js"></script>
   	<script type="text/javascript" src="ueditor/ueditor.all.min.js"></script>
   	<!-- 加入发表文章的js Etart -->
</head>	
<body>	
	<?php 
		require ROOT_PATH.'includes/indexHeader.inc.php';
		require ROOT_PATH.'class/ArticleService.class.php';
		$articleService=new ArticleService();
		//为了模拟登录用户 暂时用session  暂时这么做  以后登录必须用用户名登录了否则后面上传照片有问题
// 		$_SESSION['userName']=17;
		
	?>

   <script type="text/javascript">//*****设置UEDITOR的参数  
	  	window.UEDITOR_HOME_URL="ueditor/"; 		
	  	//window.onload=function(){//用window.onload表现出来的效果不好，所以我去掉了
			//window.UEDITOR_CONFIG.initialFrameWidth=500;
			//window.UEDITOR_CONFIG.initialFrameHeight=20;
			//现在的设置是：header zIndex=997; #messageDiv zIndex=999
			//配置ueditor相应的参数设置(应该放在实例化之前)
	  		window.UEDITOR_CONFIG.zIndex=996;
	  		//window.UEDITOR_CONFIG.initialContent="i see...";
	  		//实例化   那个编辑框在$('#content')的里面;
			UE.getEditor('content');
			
	  	//}
  </script>
 
<!-- 接收内容并且存入到数据库中去Start -->  
   	<?php 
 		if(!empty($_POST['editorValue'])){
			//print_r($_POST['editorValue']);
			$content=$_POST['editorValue'];
			
			//存入到数据库中去	
			$articleService->saveArticleContent($content);
		}
	?>

	
<!-- 接收内容并且存入到数据库中去End -->    
  
  
<!-- /*******************************界面显示部分*****************/	 -->
	   <!----start-wrap---->
		<div class="myFeeling" id="myFeeling">
			  <p>状态的发表</p>
			  
			  <form action="index.php?action=recentArticles" method="post" style="border:0px solid green;">
				 	<div id="content" ></div>
				 	<input type="submit" value="发表随感" id="deliverMyFeeling"/>
	 		  </form>
 		  
		</div>

		
		<div class="recentActivity" id="recentActivity">
		  <p>近期的活动</p>
		</div>
		<!----start-content 六块--->

			<!--- start-top-services-grids 网格---->
			<div class="myInformation" id="services" >
			<!-- ***下面是那六个板块的设置***-->
				<p>个人信息</p>
				<div class="wrap" >
					<div class="grid " >
						<label> </label>
						<a class="icon colors" href="myInformation.php"></a>
						<a href="myInformation.php" >个人信息</a>
						<p>我是一个什么样子的人呢.</p>
					</div>
					<div class="grid" >
						<label> </label>
						<a class="icon target" href="myPhotography.php"> </a>
						<a href="myPhotography.php" >相册</a>
						<p>有什么想要给大家看的嘛.</p>
					</div>
					<div class="grid" >
						<label> </label>
						<a class="icon monitor" href="#"> </a>
						<a href="#">我的状态</a>
						<p>有什么想要和大家说的嘛.</p>
					</div>
					<div class="grid" >
						<label> </label>
						<a class="icon photo" href="#"> </a>
						<a href="#">日志</a>
						<p>有什么想要跟大家分享嘛.</p>
					</div>
					<div class="grid " >
						<label> </label>
						<a class="icon man" href="friend.php"> </a>
						<a href="friend.php">我的好友</a>
						<p>来看看我的朋友们吧.</p>
					</div>
					<div class="grid " >
						<label> </label>
						<a class="icon spare" href="search.php"> </a>
						<a href="search.php">搜索</a>
						<p>我想要一个什么样子的小伙伴呢.</p>
					</div>
					<div class="clear"> </div>
				</div>
			</div>
			<!--- //End-top-services-grids---->
			
			<!-- //Start近期访问的人 -->
			<!---start-people-images--->
			<div class="people-info" >
				<p>近期访客</p>
				<div class="wrap" >
					<div class="people-pics">
						<ul>
							<li><a class="people1" href="#"><span> </span></a></li>
							<li><a class="people2" href="#"><span> </span></a></li>
							<li><a class="people3" href="#"><span> </span></a></li>
							<li><a class="people4" href="#"><span> </span></a></li>
							<li><a class="people5" href="#"><span> </span></a></li>
							<li><a class="people6" href="#"><span> </span></a></li>
							<li><a class="people7" href="#"><span> </span></a></li>
							<li><a class="people8" href="#"><span> </span></a></li>
							<li><a class="people9" href="#"><span> </span></a></li>
							<li><a class="people10" href="#"><span> </span></a></li>
							
						</ul>
					</div>					
					
				</div>
				<div class="clear"> </div>
			</div>
			<!---//End-people-images--->
			<!-- //End近期访问的人 -->
			
			<!---start-priceing-tabels 推荐---->
			<div class="pricing-plans" id="price1" >
				
				<div class="wrap" >				
					<p>系统推荐人物!!!</p>				
					<div class="pricing-grids">
						<div class="pricing-grid">
							<h3><i><label>♀</label>李华梅</i></h3>
							<ul>
								<li><a href="#">清华大学</a></li>
								<li><a href="#">23岁</a></li>
								<li><a href="#"><img src="./images/3.jpg"/></a></li>
							</ul>
							
						</div>
						<div class="pricing-grid">
							<h3><i><label>♀</label>王子欣</i></h3>
							<ul>
								<li><a href="#">北京大学</a></li>
								<li><a href="#">19岁</a></li>
								<li><a href="#"><img src="./images/2.jpg"/></a></li>
							</ul>
							
						</div>
						<div class="pricing-grid">
							<h3><i><label>♀</label>刘云凌</i></h3>
							<ul>
								<li><a href="#">复旦大学</a></li>
								<li><a href="#">22岁</a></li>
								<li><a href="#"><img src="./images/1.jpg"/></a></li>
							</ul>
							
						</div>
						<div class="clear"> </div>
					</div>
				</div>
			</div>
			<div class="clear"> </div>
			<!---//End-priceing-tabels---->
			
			<!-------------Start-article 好友动态------------->
			<div class="recentArticles" id="recentArticles">
				<p>随感--关注过的人发布时间先后显示</p>
				<div class="wrap">
					<!-- 发表过的文章显示Start -->
					<?php //获取相应的数据
						$_clean=array();
						$_arrayTemp=array();
						$_array=$articleService->getArticlePara();
						for($_i=0;$_i<count($_array);$_i++){
							$_clean['senderId']=$_array[$_i]['senderId'];
							$_clean['content']=$_array[$_i]['content'];
							$_clean['themeId']=$_array[$_i]['themeId'];
							$_clean['dateTime']=$_array[$_i]['dateTime'];
							$_clean['likeNum']=$_array[$_i]['likeNum'];
							$_arrayTemp=$articleService->getUserNameAndFace($_clean['senderId']);
							$_clean['username']=$_arrayTemp[0]['username'];
							$_clean['face']=$_arrayTemp[0]['face'];
							
							
					?>
					<div class="article">
						<div class="articleSender" style="background-image: url(../UserResourse/Photography/<?php echo $_clean['senderId']; ?>/我的头像/<?php echo $_clean['face'];?>);">
					
						</div>
						<div class="articleDetail">
							<dl>
								<dd>
									　　<font class="fontStyleForName"><?php echo$_clean['username'] ?></font>　发表于　<?php echo $_clean['dateTime'];?>
								<br /><br />
								</dd>
								<dd class="articleDetailContent">
									<?php echo $_clean['content'];?>
								
								</dd>
								<dd >
									<br /><br />
									<hr />
									<span class="commentAndLike">评论(<?php echo $articleService->getCommentNum($_clean['themeId']);?>)</span>
									<span class="commentAndLike">Like(<?php echo $_clean['likeNum'];?>)</span>
								
								</dd>
							</dl>
						</div>
					
					</div>
					
					
					<?php  }?>
					<!-- 发表过的文章显示End -->
				<div class="clear"> </div>
					
				</div>
			</div>
			<!---//End-article---->
			
				
			<!---start-contact---->
			<div class="contact" id="contact">
				<div class="contact-head">
					<h3>分享至其他平台</h3>
				</div>
				<div class="wrap">
					<div class="contact-info">
						<div class="getin-touch">
							<h4>点击并分享</h4>
							<ul>
								<li><a class="facebook" href="#"><span> </span></a></li>
								<li><a class="twitter" href="#"><span> </span></a></li>
								<li><a class="googlepluse" href="#"><span> </span></a></li>								
							</ul>
							<div class="clear"> </div>
							
						</div>
							
							<!----start-scrooling-script---->
								<script type="text/javascript">
									$(document).ready(function() {
										var defaults = {
								  			containerID: 'toTop', // fading element id
											containerHoverID: 'toTopHover', // fading element hover id
											scrollSpeed: 1200,
											easingType: 'linear' 
								 		};
										$().UItoTop({ easingType: 'easeOutQuart' });
									});
								</script>
						    <a href="#" id="toTop"><span id="toTopHover" style="opacity: 1;"> </span></a>
							<!----//End-scrooling-script---->
						</div>
					</div>
				</div>

			<!---//End-contact---->
		
		<!----//End-content--->
		<!----//End-wrap---->
<!-- ---------------Start--接收来自其他页面的两部跳转----------------------- -->
	<?php 	
					if(!empty($_GET['action'])){
					$_hash=$_GET['action'];
					
					
		 	    ?>
			    <script type="text/javascript">
			    window.onload=function(){
			    	_href("<?php echo $_hash;?>");
			    }
			    	
				</script>
				
	<?php  }?>
<!-- ---------------End--接收来自其他页面的两部跳转----------------------- -->
		
		
		

		
		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
	</body>
</html>

	
