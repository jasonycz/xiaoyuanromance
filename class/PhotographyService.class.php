<?php
  	class PhotographyService{
  		/**
  		 * getPhotographyLi 用途：在点击上传相片的时候，上拉div显示已存在的所有相册的名字 ，相册通过li显示
  		 * @param int $_userId 用户的Id 具有唯一性
  		 */
  		function getPhotographyLi($_userId){
  			$mysqli=new MysqliTool();
  			$array=array();
  			$sql="select name from photography where userId=$_userId";
  			$array=$mysqli->execute_dql_arr($sql);
//   			需要嵌套使用foreach如果是二维数组的话，这样的话，我还是直接使用for吧我对for很熟悉
//   			foreach ($array[0] as $value){
//   				$html.= "<li>".$value."</li>";
//   			}
//   			file_put_contents('1.txt',$sql."\r\n",FILE_APPEND );
// 			file_put_contents('1.txt',count($array)."\r\n",FILE_APPEND );
// 			exit();
  			for($i=0;$i<count($array);$i++){
  				$html.= "<li>".$array[$i]['name']."</li>";
  			}
  			echo $html;
  			//file_put_contents('1.txt',$html."\r\n",FILE_APPEND );
  			$mysqli->execute_close();
  		}
  		
  		
  		
  		//***用于判定相册是否存在，若存在，不在photography表中进行添加
  		//参数有两个一个是用户名  一个是相册的名字
  		//返回的值是相册在photography中的id号码 这样 做的目地是为了后面存入相片到photo时候有相册的Id号码
  		//ps:$userId,$upLoadedUser以后这两个只要一个就可以了
  	public function saveToPhotography($userId,$NewFileName){
  			$mysqli=new MysqliTool();
  			$array=array();
  			$allNowRecord=0;//用于存放现在一共有多少条记录这样做的目的是为了后面能够知道表的末尾添加的id号是多少
  			$sql="select AUTO_INCREMENT from INFORMATION_SCHEMA.TABLES where TABLE_NAME='photography'";
  			//用于判定该数据库中一共有多少条记录了并得到记录的条数
//   			$sql="select id from photography order by id desc limit 1";
  			$array=$mysqli->execute_dql_arr($sql);
//   			echo $array[0]['AUTO_INCREMENT'];
//   			$allNowRecord=count($array);  //这样是错误的
// 			if(!empty($array)){
			  	$allNowRecord=$array[0]['AUTO_INCREMENT'];
// 			  	file_put_contents("../1.txt", "1".$allNowRecord."\r\n",FILE_APPEND);
// 			}
//   			$allNowRecord= $mysqli->execute_lastInsertId();
// 			file_put_contents("../1.txt", "11".$allNowRecord."\r\n",FILE_APPEND);
// 			exit();
  			//file_put_contents("1.txt", $allNowRecord."\r\n");
	  		//判定相册是否存在
	  		$sql="select*from photography where userId=$userId ";	  		
	  		$array=$mysqli->execute_dql_arr($sql);
	  		//第一次进行建立相册的时候即count($array)=0的时候
	  		if(count($array)==0){//这个地方还需要思考下逻辑
	  			$sql="insert into photography(name,createTime,updateTime,userId)";
	  			$sql.="values('$NewFileName',now(),now(),'$userId')";
	  			$mysqli->execute_dml($sql);
// 	  			$photographyId=$allNowRecord+1;//没有就在现有的所有的id后面加1
	  			$photographyId=$allNowRecord;//没有就在现有的所有的id后面加1
// 	  			file_put_contents("../1.txt", "2".$photographyId."\r\n",FILE_APPEND);
	  		}else{
		  		for($i=0;$i<count($array);$i++){
		  			if($array[$i]['name']==$NewFileName){//有点问题没有考虑到
		  				//如果有一个是相同的，更新日期时间，然后就跳出来  必须加上where name='$NewFileName'和用户 不然的话 不同的用户
		  				//建立相同的名字的相册名字就完了
		  				//$sql="update photography set updateTime=now() where name='$NewFileName'";
		  				$sql="update photography set updateTime=now() where userId='$userId' and name='$NewFileName'";
		  				$mysqli->execute_dml($sql);
		  				$photographyId=$array[$i]['id'];//获得现有的相册的id号码
		  				break;
		  			}else if(($i==(count($array)-1))&&($array[$i]['name']!=$NewFileName)){
		  				//如果遍历完了还是没有那就建立这么一条记录 并且建立创建日期和时间
		  				$sql="insert into photography(name,createTime,updateTime,userId)";
		  				$sql.="values('$NewFileName',now(),now(),'$userId')";
		  				$mysqli->execute_dml($sql);
		  				$photographyId=$allNowRecord;//在现有的所有的id后面加1
// 		  				file_put_contents("../1.txt", "3".$photographyId."\r\n");
		  			}
		  		}
	  		}
	  		$mysqli->execute_close();
	  		return $photographyId;
  		}
  		//***功能：用于存入照片的名字 
  		//参数：相片的名字  相册的Id 描述 
  		public  function saveToPhoto($iconvPhotoName,$photographyId,$photoDescript){
  			$mysqli=new MysqliTool();
  			$sql="insert into photo(photo,photographyId,descript,addTime)";
  			$sql.="values('$iconvPhotoName',$photographyId,'$photoDescript',now())";
  			$mysqli->execute_dml($sql);
  			$mysqli->execute_close();
  			
  			
  		}
  		
  		/**
  		 * showPhotography 在myPhotography.php中显示所有的相册
  		 * @param int $userId 用户的Id 具有唯一性 
  		 * @return string 返回给myPhotogrphy.php 的html代码
  		 */
  		public function showPhotography($userId){
  			$mysqli=new MysqliTool();
  			$photographyArray=array();
  			$photoArray=array();//为了得到第一个图片作为背景图片
  			$html='';//用于返回的html代码
  			$sql="select id,name from photography where userId='$userId'";
  			$photographyArray=$mysqli->execute_dql_arr($sql);
  			//显示相册
  			for($i=0;$i<count($photographyArray);$i++){
  				//考虑到url不能够有非法的字符对$photographyArray[$i]['name']进行替换操作
  				$photographyUrlName=preg_replace("/\ /", "\\ ", $photographyArray[$i]['name']);
  				
  				//取出最新的一个相片，为了作为相册的背景图片
  				$sql="select photo from photo where photographyId={$photographyArray[$i]['id']} order by addTime desc ";
  				$photoArray=$mysqli->execute_dql_arr($sql);

				//开始显示相册	
  				$html.="<div id='photographyOuterMostDiv'>";
  				//如果是本人的话，就能显示关闭按钮,否则是外人的话不显示
  				if(!empty($userId)){  			
					$html.="<a class='deletePhotography' href='myPhotoOrPhotographyDeleteController.php?action=deletePhotography&photographyId={$photographyArray[$i]['id']}'></a>";
  				}
				$html.="<div id='photographys' >";			
  				$html.="<a href='myPhoto.php?photographyId={$photographyArray[$i]['id']}' id='photographyHref'>";
  				$html.="<div id='divForImg'";//这个div为了更好地定位且在这里面显示背景图片
  				$html.="style='background-image:url(../UserResourse/Photography/$userId/$photographyUrlName/{$photoArray[0]['photo']})'>";
  				$html.="</div>";
  				$html.="<p>".$photographyArray[$i]['name'];
  				$html.="(<font color='red'>".count($photoArray)."</font>张)</p>";
  				$html.="</a>";
  				$html.="<div id='bottomDiv1'></div>";
  				$html.="<div id='bottomDiv2'></div>";
  				$html.="</div>";
  				$html.="</div>";//最外面那个div为了包含那个close button
  				
  			}
  			$mysqli->execute_close();
  			return $html;
  		}
  		
  			
  	}
?>