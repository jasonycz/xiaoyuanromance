<?php
	//require_once 'web/MYSQLI.class.php';//到时候试试看可以吗
	//require_once 'Mysqli.class.php';//前面common.inc.php文件中已经调用了该类了
	
	class GloablService{
		/**
		 * 说明：为了让程序更加的灵活，我选择_query($_sql)这种方式而不是多写很多的函数来实现相应的查询  比如分页 比如一些其他
		 * 的查询  如果再写其他的方法，那么就会出现有一些代码重复了  所以我是牺牲代码的可读性来提高代码的简洁
		 * _query($_sql) 函数的功能：一个查询方法 
		 * @param string $_sql
		 * @return array $array
		 */
		function _query($_sql){
			//global $mysqli;//应该是不可以的
			$mysqli=new MysqliTool();
			$sql=$_sql;
			$array=array();
			$array=$mysqli->execute_dql_arr($sql);
			$mysqli->execute_close();
			return $array;
		}
		/**
		 * _manipulate($_sql) 操作数据库
		 * @param string $_sql
		 */
		public function _manipulate($_sql) {
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql=$_sql;
			$res=$mysqli->execute_dml($sql);
			$mysqli->execute_close();
			return $res;
			
		}
		
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>