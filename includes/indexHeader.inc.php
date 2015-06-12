<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-4
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
//防止非HTML页面调用
if (!defined('SCRIPT')) {
	exit('Script Error!');
}

?>
<!----start-header--->
<div class="header">
		<?php 
			//	判断是不是我自己
			if($_COOKIE['username']=='woycz'){
		?>
			<div class="manageDiv"><a href="manageData.php">管理</a></div>
		<?php }?>	
	<div class="wrap">	
		<!---start-logo---->
		<div class="logo">
<!-- 			<a href="index.php"><img src="./images/logo.png" title="futura" /></a> -->
		</div>			
		<!---End-logo---->

		<!---start-top-nav---->
		<div class="top-nav" >
			<ul>	
				<li class="active"><a href="index.php">首页</a></li>
				<li><a href="#myFeeling">状态</a></li>
				<li><a href="#recentActivity">活动</a></li>
				<li><a href="#services">个人</a></li>
				<li><a href="#price1">推荐</a></li>
				<li><a href="#recentArticles">动态</a></li>
				<!-- <li><a href="#portfolio">应用</a></li> -->
				<li><a href="#contact">分享</a></li>
				<li class="friend">	
					<span class="addFriendInfoNum">						
							<?php
								//这里是显示所有未读的数据的前按时间顺序的前5条
								$_sql="select id,applicantId,respendentId,datetime,content from friend where (applicantId='{$_COOKIE['userId']}' or respendentId='{$_COOKIE['userId']}') and state=0 order by datetime desc ";
								global $globalService;
								//echo $_sql;
								//exit();
								$_friend=$globalService->_query($_sql);
								//print_r($_friend);
								//exit();
								if(count($_friend)>0){
									echo count($_friend);
							?>
							<script type="text/javascript">
								$('.addFriendInfoNum').addClass("active");
							</script>
							<?php 
								}
							?>									
						</span>						
						<!-- ------------------好友添加---------------------- -->
						<div id="friendDiv">
							<?php 
							
								//情况一：没有最新消息的情况 
							if(count($_friend)==0){
								$_html="<table ><tr><td>无最新消息</td></tr>";
								/******************************规划下*********************************/
								$_html.="<tr><td><a href='friendMakeCenter.php'>点击查看历史交友记录</a></td></tr></table>";
								echo $_html;
							}else{	
									//只显示其中的前$_select_num;现在定义为最多10个
									$_select_num=0;
									if(count($_friend)<=10){
										$_select_num=count($_friend);
									}else{
										$_select_num=10;
									}
									echo "<table><tr><th>Ta</th><th>消息内容</th><th>状态</th></tr>";
									$_html = array();
									for($i=0;$i<$_select_num;$i++) {
										$_html['id'] = $_friend[$i]['id'];
										/********Start--通过申请人和被申请人的Id获取发送人的名字************/
										$_sql="select username from user where id={$_friend[$i]['respendentId']} limit 1";
										$_respendent=$globalService->_query($_sql);
										$_sql="select username from user where id={$_friend[$i]['applicantId']} limit 1";
										$_applicant=$globalService->_query($_sql);
										//判断被申请的人是不是自己(重要)
										if($_respendent[0]['username']==$_COOKIE['username']){
											$_html['ta']=$_applicant[0]['username'];
										}else{
											$_html['ta']=$_respendent[0]['username'];
										}
										/********End--通过申请人和被申请人的Id获取发送人的名字************/
													
										$_html['content'] =$_friend[$i]['content'];
										$_html['datetime'] = $_friend[$i]['datetime'];
										$_html = _html($_html);
										//处理一下发信的内容
										$_html['content_html'] = _title($_html['content'],10);
										
										if ($_respendent[0]['username']==$_COOKIE['username']) {
											if (empty($_html['state'])) {
												//<a class="check">为什么要加一个class 是因为这样点击这个超链接的时候，friendDiv不会变小
												//这样看起来的效果更加的好。
												$_html['state_html'] = '<a class="check" href="friendController.php?action=check&id='.$_html['id'].'" style="color:red;">验证</a>';
											}
										} else if ($_applicant[0]['username'] == $_COOKIE['username']) {
											if (empty($_html['state'])) {
												$_html['state_html'] = '<span style="color:blue;">等待验证</span>';
											}
										}				
										echo "<tr>";
										echo "<td>{$_html['ta']}</td>";
										echo '<td><a href="friendMakeCenter.php" title="'.$_html['content'].'" style="color:red;">'.$_html['content_html'].'</a></td>';
// 										echo '<td>'.$_html['datetime'].'</td>';	
										echo '<td>'.$_html['state_html'].'</td>';
										echo '</tr>';
								   }
								   
								   echo '<tr><td colspan=4><a href="friendMakeCenter.php">点击获取更多消息</a></td></tr></table>';
								   
							}
				
							?>		
										
						</div>										
				</li>
				<li class="message">
				<span class="messageInfoNum">
					<?php
					
						//这里是显示所有未读的数据的前按时间顺序的前5条
						$_sql="select id,senderId,datetime,content from message where getterId='{$_COOKIE['userId']}' and isRead=0 order by datetime desc ";	
						global $globalService;	
						//echo $_sql;
						//exit();
						$_message=$globalService->_query($_sql);
						//print_r($_array);
						//exit();
						if(count($_message)>0){
							echo count($_message);
							?>	
						<script type="text/javascript">
							$('.messageInfoNum').addClass('active');
						</script>
						<?php 
							}
						?>
				</span>
				<!-- -------------------新消息---------------------- -->
				<div id="messageDiv" > 
						<?php 
							//情况一：没有最新消息的情况 
						if(count($_message)==0){
							$_html="<table id='messageTable'><tr><td>无最新消息</td></tr>";
							$_html.="<tr><td><a href='message.php'>点击查看历史留言</a></td></tr></table>";
							echo $_html;
						}else{	
								//只显示其中的前$_select_num;现在定义为最多10个
								$_select_num=0;
								if(count($_message)<=10){
									$_select_num=count($_message);
								}else{
									$_select_num=10;
								}
								echo "<table><tr><th>Ta</th><th>消息内容</th><th>时间</th></tr>";
								$_html = array();
								$_sendName=array();
								for($i=0;$i<$_select_num;$i++) {
									$_html['id'] = $_message[$i]['id'];
									/********Start--通过发送人的Id获取发送人的名字************/
									$_sql="select username from user where id={$_message[$i]['senderId']} limit 1";
									$_sendName=$globalService->_query($_sql);
									$_html['sender'] = $_sendName[0]['username'];
									/********End--通过发送人Id获取发送人的名字************/
									$_html['content'] =$_message[$i]['content'];
									$_html['datetime'] = $_message[$i]['datetime'];
									$_html = _html($_html);
									// 有bug   因为我选择的都是未读的  所以这里的语句需要修改下$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
									if (empty($_message[$i]['isRead'])) {
										$_html['content_html'] = '<strong><span style="color:red;">'._title($_html['content'],14).'</span></strong>';
									} else {
										$_html['content_html'] = _title($_html['content'],14);
									}			
									echo "<tr><td>{$_html['sender']}</td>";
									
									echo '<td><a href="messageDetail.php?id='.$_html['id'].'" title="'.$_html['content'].'">'.$_html['content_html'].'</a></td>';
									echo '<td>'.$_html['datetime'].'</td></tr>';	
							   }
							   
							   echo '<tr><td colspan=3><a href="message.php">点击获取更多消息</a></td></tr></table>';
							   
						}
			
						?>		
									
					</div>
				</li>	
			
			</ul>		
		</div>			
 			
	</div>
	<!-- Start UserInformation And face -->
	<?php //获取用户的信息。
		$_userId=$_COOKIE['userId'];
		$_sql="select face,username,logindate from user where id=$_userId limit 1";
		$_clean=array();
		$_html=$globalService->_query($_sql);
		$_clean['face']=$_html[0]['face'];
		$_clean['username']=$_html[0]['username'];
		$_clean['email']=$_COOKIE['email'];
		$_clean['logindate']=$_html[0]['logindate'];
		$_clean=_html($_clean);
	
	?>
	<!-- Start--UserInformation And face -->
		<div class="user">
				<span class="usernameAnddownMenu">
					<span class="username">
						<?php echo $_clean['username'];?>
					</span>
					<span class="downMenu"></span>
				</span>
				<span class="face" style="background-image: url(../UserResourse/Photography/<?php echo $_userId; ?>/我的头像/<?php echo $_clean['face'];?>)"></span>
				
				<!-- ---------------------用户信息---------------------------- -->
				<div id="userInformation">
					<table >
						<tr>
							<td colspan="2"><span class="face" style="background-image: url(../UserResourse/Photography/<?php echo $_userId; ?>/我的头像/<?php echo $_clean['face'];?>)"></span></td>							
						</tr>
						<tr><td><?php echo $_clean['username'];?></td><td ><a href="myInformation.php">信息修改</a></td></tr>
						<tr><td>LastVisitTime:<?php echo $_clean['logindate'];?></td><td><a href="loginController.php?action=loginOut">退出登录</a></td></tr>
					
					</table>
				
				</div>
		
		</div>
	<!-- End UserInformation And face -->
</div>
<!----End-header--->
	




	

<!-- ------------------------------jQuery代码----------------------------------------- -->
		<script type="text/javascript">//*****获取屏幕的宽度和高度 ---给header赋值  
									   //*****给下拉的message和friend宽度赋值
		   							   //*****给userInformation下拉菜单赋值
			$(document).ready(function(){
// 				var height=$(window).height();
				var width=$(window).width();
//  				alert(width);
//  				alert('dd');
				$('.header').css({'width':width});
				$('#friendDiv').css({'width':width*0.335});
				$(' #messageDiv').css({'width':width*0.37});
				$(' #userInformation').css({'width':width*0.28,'height':width*0.28,'left':-width*0.152});

			});

			//$('.user').hover(function(){alert('hover user');});
		</script>
	
	
	
	
	
	
	
	
	
	
	