<?php	
	class ArticleService{
		//保存编辑器中提交的东西(html)代码
		function saveArticleContent($content){
			$mysqli=new MysqliTool();
			$sql="insert into article(content,datetime,senderId) values('$content',now(),{$_COOKIE['userId']})";
			$mysqli->execute_dml($sql);
			$rightNowLastId=mysqli_insert_id($mysqli->_get_connect());
			//file_put_contents('1.txt', 'aa'.$rightNowLastId,FILE_APPEND);
			$sql="update article set themeId=$rightNowLastId where id=$rightNowLastId";
			$mysqli->execute_dml($sql);
			$mysqli->execute_close();
		}
		function getArticlePara(){
			$mysqli=new MysqliTool();
			$sql="select themeId,likeNum,dateTime,senderId,content from article where reId=0 order by datetime desc";
			$array=array();
			$array=$mysqli->execute_dql_arr($sql);
			$mysqli->execute_close();
			//file_put_contents('1.txt', $array ."\r\n",FILE_APPEND);
			return $array;
		}
		/**
		 * getCommentNum($_themeId) 获取该文章的评论数
		 * @param int $_themeId :主题贴的id
		 * @return int 返回帖子中的评论数
		 *
		 */
		function getCommentNum($_themeId){
			$mysqli=new MysqliTool();
			$sql="select id from article where reId!=0 and themeId=$_themeId";
			$array=array();
			$array=$mysqli->execute_dql_arr($sql);
			$mysqli->execute_close();
			//file_put_contents('1.txt', $array ."\r\n",FILE_APPEND);
			return count($array);
		}
		/**
		 * getUserName($_senderId) 获取用户的名字
		 * @param int $_senderId 用户的Id
		 * @return array $array 返回用户的名字和头像
		 */
		function getUserNameAndFace($_senderId){
			$mysqli=new MysqliTool();
			$sql="select username,face from user where id=$_senderId limit 1";
			$array=array();
			$array=$mysqli->execute_dql_arr($sql);
			$mysqli->execute_close();
			return $array;
		}
		
	}

?>