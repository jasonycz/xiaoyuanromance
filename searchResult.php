<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-16
*/
session_start();
//定义标题
define("TITLE_NAME", "搜索结果");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','searchResult');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
// //判断是否登录了
// if (!isset($_COOKIE['username'])) {
// 	//_alert_close('请先登录！');
// 	_location('请先登录！',"login.php");
// }
global $globalService;

//把$searchKeyWord做成一个二维数组怎么样
$_searchKeyWord=$_SESSION['searchKeyWord'];
//获取从前一页面传输过来的搜索条件
if(!empty($_GET['search'])){
		$_searchSql=$_GET['search'];
			
		//分页模块  1.获取相应的参数
		global $_pagesize,$_pagenum;//$_id是用于进入别人主页用的
		//global  $_id;我是没有想到  真他妈的精髓
		$_id = "search=".$_searchSql.'&';
		//获取相应的全局参数
		$_size=5;
		$_type=1;

		_page($_searchSql, $_size);
		//2.取出每个页面需要的记录
		$_sql=$_searchSql." limit $_pagenum,$_pagesize ";

//		file_put_contents('1.txt', $_sql."\r\n",FILE_APPEND);
		$_searchResult=array();
		$_searchResult=$globalService->_query($_sql);
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
		<?php require ROOT_PATH.'includes/header.inc.php';?>
		<!-- -----用于发送好友申请的隐藏的div-------- -->
		<div class="divForMakeFriend">
			<form method="post" action="friendController.php?action=makeFriend" enctype="application/x-www-form-urlencoded">
				<dl>
					<dd><span class="closeDiv" ></span></dd>
					<dd class="respendent" ></dd>
					<dd><textarea rows="12" cols="55" name="content" class="contentForApply">你好，我非常想和你交朋友！</textarea> </dd>
					<dd><input type="hidden" class="respendentId" name="respendentId" value=""/><input type="submit" value="发送" class="button"/></dd>	
				</dl>			
			</form>
		</div>
		<!-- -----用于发送站内信的隐藏的div-------- -->
		<div class="divForSendMessage">
			<form method="post" action="messageController.php?action=send" enctype="application/x-www-form-urlencoded">
				<dl>
					<dd><span class="closeDiv" ></span></dd>
					<dd class="getter" ></dd>
					<dd><textarea rows="12" cols="55" name="content" class="contentForSend">这是我发给你的私信。</textarea> </dd>
					<dd><input type="hidden" class="getterId" name="getterId" value=""/><input type="submit" value="发送" class="button"/></dd>	
				</dl>			
			</form>
		</div>

		
		<div class="searchResultDiv">

			<div id="searchKeyWord">
				关键字:
				<?php 
					if(empty($_searchKeyWord)){
						echo "<font color='red'>全部用户</font>";
					}else{
						for($i=0;$i<count($_searchKeyWord);$i++){
							echo "<font color='red'>".$_searchKeyWord[$i++]['key']."</font>";
							echo ":";
							echo "<font color='blue'>".$_searchKeyWord[$i]['value']."</font>";
							echo " ";						
						}
					}					
				?>
			</div>
			<hr />			
			<div class="searchResults">
				搜索结果:<br />
				<?php 
					if(count($_searchResult)==0){
						echo "<font color='red'>Not Found</font>";
					}else{		
						for($i=0;$i<count($_searchResult);$i++){
				?>
				<div class="searchResult">
					<table >
						<tr>
							<td  rowspan="3" >
							<div style="background-image: url(../UserResourse/Photography/<?php echo $_searchResult[$i]['id']; ?>/我的头像/<?php echo $_searchResult[$i]['face']; ?>)" ></div></td>                                                  
							<td>姓名:</td><td><font color="red"><?php echo $_searchResult[$i]['username'];?></font></td>
						</tr>
						<tr><td>个人描述:</td><td><font color="red"><?php echo $_searchResult[$i]['selfdescription'];?></font></td></tr>
						<tr>
							<td><input type="button" class="friensdMake" id="<?php echo $_searchResult[$i]['id'];?>" title="<?php echo $_searchResult[$i]['username'];?>" value="加好友" /></td>
							<td><input type="button" class="messageSend" id="<?php echo $_searchResult[$i]['id'];?>" title="<?php echo $_searchResult[$i]['username'];?>" value="发消息" /></td>
						</tr>
					</table>
				</div>		
				<hr />
				
				<?php }?>
			</div>
		
			<?php	
				//4.分页模式的设置				
				 _paging($_type);}
			?>

		
		</div>

		<?php 
			require ROOT_PATH.'includes/footer.inc.php';
		?>
	<!-- ------------------------------------jQery代码-------------------------------------------- -->
	
	<script type="text/javascript">//******************发送好友申请和站内信
		$('.friensdMake').click(function(){//***点击添加好友
			//关闭发送站内信的DIV
			$('.divForSendMessage').css({'display':'none'});
			//在客户端进行判定 该用户是不是自己
			if($(this).attr("id")==<?php echo $_COOKIE['userId'];?>){
				alert("请不要添加自己为好友");
				return false;
			}		
			var top=centerWindow($('.divForMakeFriend').outerHeight(),$('.divForMakeFriend').outerWidth()).top;
			var left=centerWindow($('.divForMakeFriend').outerHeight(),$('.divForMakeFriend').outerWidth()).left;
			//alert(top+''+left);
			$('.respendent').text($(this).attr("title")+'你好:');
			$('.respendentId').val($(this).attr("id"));
			$('.contentForApply').val("我非常想和你交朋友！");
			$('.divForMakeFriend').css({'display':'block','left':left,'top':top});
		});
		$('.messageSend').click(function(){//***点击站内信
			//关闭添加好友DIV
			$('.divForMakeFriend').css({'display':'none'});
			//在客户端进行判定 该用户是不是自己
			if($(this).attr("id")==<?php echo $_COOKIE['userId'];?>){
				alert("请不要对自己发送站内信");
				return false;
			}		
			//alert($('.divForSendMessage').outerHeight()+' '+$('.divForSendMessage').outerWidth());
			var top=centerWindow($('.divForSendMessage').outerHeight(),$('.divForSendMessage').outerWidth()).top;
			var left=centerWindow($('.divForSendMessage').outerHeight(),$('.divForSendMessage').outerWidth()).left;
			//alert(top+''+left);			
			$('.getter').text("收信人："+$(this).attr("title"));
			$('.getterId').val($(this).attr("id"));
			$('.contentForSend').val("这是我发给你的私信。");			
			$('.divForSendMessage').css({'display':'block','left':left,'top':top});

			
		});

		$('.closeDiv').click(function(){//点击关闭发信的按钮
			//清空发送的数据
			$('.getter').text("");
			$('.getterId').val("");
			$('.contentForApply').val("我非常想和你交朋友！");
			$('.contentForSend').val("这是我发给你的私信。");
			$('.divForMakeFriend').css({'display':'none'});
			$('.divForSendMessage').css({'display':'none'});
		});
	</script>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	</body>
</html>