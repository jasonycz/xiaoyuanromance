<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-19
*/
session_start();
//定义标题
define("TITLE_NAME", "{$_COOKIE['username']}的好友");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','friend');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	//_alert_close('请先登录！');
// 	_location('请先登录！',"login.php");
// }
//-------------------------获取好友信息
	global $globalService;
	//分页模块  1.获取相应的参数
	global $_pagesize,$_pagenum,$_id;
	$_sql="select applicantId,respendentId from friend where respendentId='{$_COOKIE['userId']}' or applicantId='{$_COOKIE['userId']}' and state=1 ";
	//global  $_id;我是没有想到  真他妈的精髓
	//$_id = 'photographyId='.$_photography_id.'&';
	//获取相应的全局参数
	$_size=10;
	$_type=1;
	_page($_sql, $_size);
	//2.取出每个页面需要的记录
	$_sql="select applicantId,respendentId from friend where respendentId='{$_COOKIE['userId']}' or applicantId='{$_COOKIE['userId']}' and state=1  limit $_pagenum,$_pagesize ";
	$_friendList=array();
	$_friendList=$globalService->_query($_sql);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php 
			require ROOT_PATH.'includes/title.inc.php';
		?>
	</head>	
	<body>
		<?php require ROOT_PATH.'includes/header.inc.php';?>
		<!-- -----用于发送站内信的隐藏的div-------- -->
		<div class="divForSendMessage">
			<form method="post" action="messageController.php?action=send" enctype="application/x-www-form-urlencoded">
				<dl>
					<dd><span class="closeDiv" ></span></dd>
					<dd class="getter" ></dd>
					<dd><textarea  name="content" class="contentForSend">这是我发给你的私信。</textarea> </dd>
					<dd><input type="hidden" class="getterId" name="getterId" value=""/><input type="submit" value="发送" class="button"/></dd>	
				</dl>			
			</form>
		</div>
		<!-- -----------------------聊天窗口------------------------ -->
		<div class="chatWindow">
			<dl>
				<dd><font color="red"><span class="sender"></span></font>正在和<font color="red"><span class="getter"></span></font>在聊天<span class="closeDiv" ></span></dd>
				<dd><div class="chatDiv"></div></dd>
				<dd><textarea  class="sendContent" placeholder="输入你的信息"></textarea></dd>
				<dd><input type="hidden" class="getterId" value=""/> <input type="button" class="sendMessage button" value="发送" /></dd>
			
			</dl>
		</div>
		
		<!-- -----------------------好友列表------------------------ -->
		
		<div class="friendDiv">
			<h1>好友列表</h1>
			<hr/>
			<div class="friendList">
				<?php
					$_html=array();
					for($i=0;$i<count($_friendList);$i++){
						/********Start--通过申请人和被申请人的Id获取发送人的名字************/
						$_sql="select username,face from user where id={$_friendList[$i]['respendentId']}  limit 1";
						$_respendent=$globalService->_query($_sql);
						$_sql="select username,face from user where id={$_friendList[$i]['applicantId']}  limit 1";
						$_applicant=$globalService->_query($_sql);
						//判断被申请的人是不是自己(重要)
						if($_respendent[0]['username']==$_COOKIE['username']){
							$_html['ta']=$_applicant[0]['username'];
							$_html['taId']=$_friendList[$i]['applicantId'];
							$_html['taFace']=$_applicant[0]['face'];
						}else{
							$_html['ta']=$_respendent[0]['username'];
							$_html['taId']=$_friendList[$i]['respendentId'];
							$_html['taFace']=$_respendent[0]['face'];
						}
				?>
				<div class="friendOuterMostDiv">
					<dl><!-- 写一个js让程序去获取好友最外面的那个div的宽度 从而去计算出高度 -->
						<dd><font color="red" style="border: 1px solid red;"><?php echo $_html['ta'];?></font><a class="deleteFriend" href='friendController.php?action=deleteFriend&friendId=<?php echo $_html['taId']; ?>'></a></dd>
						<dd><div class="friendPhoto" style='background-image:url(../UserResourse/Photography/<?php echo $_html['taId']; ?>/我的头像/<?php echo $_html['taFace'];?>);'></div></dd>
						<dd class="communicate">
							<button class="button flower" title="送花给Ta"></button>
							<button class="button chatLive" title="实时聊天" name="<?php echo $_html['ta']; ?>" id="<?php echo $_html['taId'];?>" ></button>
							<button class="button messageSend" title="发站内信" name="<?php echo $_html['ta']; ?>" id="<?php echo $_html['taId'];?>" ></button>
							
						</dd>
					</dl>
				</div>
			
				<?php }?>
			</div>
			<?php
				//4.分页模式的设置
				 _paging($_type);
			?>
		
		</div>
		


		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
	<!-- ------------------------------------jQuery代码-------------------------------------------- -->
	
	<script type="text/javascript">//******************好友关系解除
		$('.deleteFriend').click(function(){
			if(confirm("确认解除好友关系吗?")){
				return true;
			}else{
				return false;
			}
		});


	</script>
	
	
	<script type="text/javascript">//******************实时聊天窗口

		function getChatLiveData(){	//********************从数据库中异步获取被点击的好友发过来的数据
				$.ajax({
							type:"POST",
							 url:"chatLiveController.php?action=getMessage",
							data:{senderId:$('.getterId').val(),getterId:"<?php echo $_COOKIE['userId'];?>" },
						dataType:"json",
						beforeSend:function(){
							},
					 	success:function(data){	
					 		//alert($('.chatDiv')[0].scrollHeight);

						 	for(var i=0;i<data.length;i++){
						 		$(".chatDiv").html($(".chatDiv").html()+"<br/>"+'<font color="red" size="5px">'+$('.getter').text()+'</font>  '+data[i].dateTime +':<br/>'+data[i].content);
						 		
							}
						 	$('.chatDiv').scrollTop($('.chatDiv')[0].scrollHeight);
						 	
						 
						 },			
						 error:function(data,state){
							 //有问题???
							 alert(state);
							 alert('接收数据出错');
			             }
				});
		}

		var intervalId=0;
		$('.chatLive').click(function(){//************************点击添加实时聊天
				//关闭发站内信
				$('.divForSendMessage').css({'display':'none'});
				//由聊天窗口的宽获取聊天窗口的高
				var chatDivWidth=$('.chatWindow').outerWidth();
				$('.chatWindow').css({'height':chatDivWidth});
// 				alert(chatDivWidth);
// 				alert($('.chatDiv').css('height'));
				//清空之前的chatDiv的内容。		
				$('.chatDiv').text("");
			
				var top=centerWindow($('.chatWindow').outerHeight(),$('.chatWindow').outerWidth()).top;
				var left=centerWindow($('.chatWindow').outerHeight(),$('.chatWindow').outerWidth()).left;
				//赋值
				$('.sender').text("<?php echo $_COOKIE['username'];?>");
				$('.getter').text($(this).attr('name'));
				$('.getterId').val($(this).attr('id'));
				
				$('.chatWindow').css({'display':'block','left':left,'top':top});
				//获取实时的数据
				intervalId=setInterval("getChatLiveData()",1000);
				//getChatLiveData();

				
			});
	
		$(".sendMessage").click(function(){//***点击发送按钮执行把数据发送到数据库，同时在自己的页面中显示自己讲的话


			//进行空格和回车的转换
				$(".sendContent").val($(".sendContent").val().replace(/(\n)/g,"<br/>"));
				$(".sendContent").val($(".sendContent").val().replace(/( )/g,"　"));//"　"是全角下的空格
			
				
			$.ajax({	//***获取textarea中的类容，并显示在chatDiv中
						type:"POST",
						 url:"chatLiveController.php?action=sendMessage",
						data:{senderId:"<?php echo $_COOKIE['userId'];?>",getterId: $('.getterId').val(),content:$(".sendContent").val()},
					dataType:"json",//预期服务器返回的数据类型。并不是说是发送数据的格式是json 这之前我理解错误了
									//之前以为发送的数据也是会要求是json数据格式
					beforeSend:function(){
						var dateTime=getDateTime();
						if($(".sendContent").val()==''){
								alert("你输入的内容为空，请重新输入");
								return false;
						}else{	
								$(".chatDiv").html($(".chatDiv").html()+"<br/>"+'<font color="red" size="5px"><?php echo $_COOKIE['username'];?></font>  '+dateTime +':<br/>'+$(".sendContent").val());
								//清空发送textarea中的数据
								$(".sendContent").val("");
								$(".sendContent").focus();
								
						}
					},
				 	success:function(data){
// 						 	alert(data.good);
					 },			
					 error:function(data,state){
						 alert(state);
						 alert(data.bad);
						 alert('发送数据出错');
						
		             }
			});
		});



	</script>

	
	<script type="text/javascript">//******************给好友的站内信
			$('.messageSend').click(function(){//***点击站内信
			//关闭添加好友DIV
			$('.chatWindow').css({'display':'none'});		
			var top=centerWindow($('.divForSendMessage').outerHeight(),$('.divForSendMessage').outerWidth()).top;
			var left=centerWindow($('.divForSendMessage').outerHeight(),$('.divForSendMessage').outerWidth()).left;
			//alert(top+''+left);			
			$('.getter').text("收信人："+$(this).attr("name"));
			$('.getterId').val($(this).attr("id"));
			$('.contentForSend').val("这是我发给你的私信。");			
			$('.divForSendMessage').css({'display':'block','left':left,'top':top});

			
		});

		$('.closeDiv').click(function(){//点击关闭发信的按钮
			//清空发送的数据  停止不断去数据库获取信息
			//alert(intervalId);
			clearInterval(intervalId);
			$('.getter').text("");
			$('.getterId').val("");
			$('.contentForSend').val("这是我发给你的私信。");
			$('.chatDiv').text("");
			$('.sendContent').val("");
			$('.chatWindow').css({'display':'none'});
			$('.divForSendMessage').css({'display':'none'});
			
			
		});
		
	    $(document).keydown(function (e) {//***响应回车键进行发送数据
	    	if((e.ctrlKey)&&(e.keyCode===13)){	    
	        	$(".sendMessage").click();
	        }
	    	if(e.keyCode===27){	    
	    		$('.closeDiv').click();
	        }
	    });
	</script>
	
	
	
	
	
	
	
	</body>
</html>



























