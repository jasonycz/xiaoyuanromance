<?php
class MysqliTool {
	private $mysqli;
	private static $host = "localhost";
	private static $user = "root";
	//private static $user = "woycz";
	private static $password = "9A102YCZ";
	private static $db = "xiaoyuanromance";
	//private static $db = "a0313130320";
	public function __construct() {
		// 完成初始化任务
		$this->mysqli = new Mysqli ( self::$host, self::$user, self::$password, self::$db );
		if ($this->mysqli->connect_error) {
			die ( "连接失败" . $this->connect_error );
		}
		// 设置访问数据库的字符集保证PHP是以utf8的方式来操作我们的mysql数据库的
		$this->mysqli->query ( "set names utf8" );
	}
	public function _get_connect(){
		return $this->mysqli;
	}
	//在函数中释放结果集，并且返回一个数组
	//数组的每个元素都是一行数据，$arr是一个多维数组
	public function execute_dql_arr($sql){
		$res=$this->mysqli->query($sql) or die("操作dql失败".$this->mysqli->error);
		$arr=array();
		$i=0;
		while(!!$row = $res->fetch_assoc()){//加上!!警告就消失了   因为后面的那个语句是赋值  至于!!作用我还不知道
			//这里我是人为的把一个一维的数组转换为了二维的了
			//$row是一行的数据
			//$row=$res->fetch_assoc();
			$arr[$i++]=$row;//或者$arr[]=$row		
		}
		//file_put_contents("1.txt", count($arr)."\r\n",FILE_APPEND);
		$res->free();		
		//exit();
		return $arr;
	}
	public function execute_dql($sql) {
		$res = $this->mysqli->query ( $sql ) or die ( "操作dql失败" . $this->mysqli->error );
		$res=$res->fetch_assoc();
		//$res由结果集变为了一个数组 该数组实际上是一个一行数据，且是一个一维的数组
		return $res;
	}

	public function execute_dml($sql) {
		$res = $this->mysqli->query ( $sql ) or die ( "操作dml失败" . $this->mysqli->error );
		if (! $res) {
			//echo "Failure<br>";
			return 0; // 表示失败
		} else {
			if ($this->mysqli->affected_rows > 0) {
				//echo "Success<br>";
				return 1; // 表示成功
			} else {
				//echo "没有行数影响<br>";
				return 2; // 表示没有行影响
			}
		}
	}
	// 自己编写的 认识：利用这个类我们创建一个实体，这个实体拥有mysqli属性还有上面定义的那些函数mysqli是它的一个属性。
	public function execute_close() {
		$this->mysqli->close ();
	}
}
?>
