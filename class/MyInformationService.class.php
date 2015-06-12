<?php	
	class MyInformationService{
	//***以链表显示国家
		public function ShowCountry(){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select *from country";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			for($i=0;$i<count($arr);$i++){
				$res.="<li><a href='#' id={$arr[$i]['id']}>{$arr[$i][name]}|</a></li>";
			}
			$mysqli->execute_close();
			return $res;
		}
	//***以链表显示省份
		public function ShowProvinces($id){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select*from province where countryId=$id";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			//echo"中国";
			for($i=0;$i<count($arr);$i++){
				$res.="<li><a href='#' id={$arr[$i]['id']}>{$arr[$i][name]}|</a></li>";
			}
			//echo"中国人";
			$mysqli->execute_close();
			return $res;
		}
	//以链表显示大学
		public  function ShowColleges($countryId,$proId){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select*from university where proId=$proId and countryId=$countryId";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			for($i=0;$i<count($arr);$i++){
				$res.="<li><a href='#' id={$arr[$i]['name']}>{$arr[$i][name]}|</a></li>";
			}
			$mysqli->execute_close();
			return $res;
		}
	//以下拉框显示国家
		public function ShowCountryInSelect(){//$sql="select *from province where countryId=$id";
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select *from country";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			for($i=0;$i<count($arr);$i++){
				echo"<option value={$arr[$i][id]}>{$arr[$i][name]}</option>";
			}
			$mysqli->execute_close();
		
		}

	//获取国家符合的第一个proId
		public function getProId($countryId){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select*from university where countryId=$countryId ";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res=$arr[0]['proId'];
			$mysqli->execute_close();
			return $res;
		}

	//***获取countryId=$id的省份
		public function getProvince($id){
			//global $mysqli;
			//file_put_contents("1.txt", "ssss"."\r\n",FILE_APPEND);
			$mysqli=new MysqliTool();
			$sql="select *from province where countryId=$id";//
			//file_put_contents('E:/MyEnv/Apache24/Apache24/htdocs/myblog/myblog.txt', $sql.'  '.date("y-m-d H:i:s",time())."\r\n",FILE_APPEND);
			//file_put_contents("1.txt", $sql."\r\n",FILE_APPEND);
			//exit();
			$array=array();
			$res="[";
	   		$array=$mysqli->execute_dql_arr($sql);
	   		//echo"<option>--省份--</option>";	
	   		for($i=0;$i<count($array);$i++){
				if($i==count($array)-1){
					$res.='{"province":"'.$array[$i]["name"].'","provinceId":"'.$array[$i]["id"].'"}';
				}else{
					$res.='{"province":"'.$array[$i]["name"].'","provinceId":"'.$array[$i]["id"].'"},';
				}
				
			}
			$res.="]";
			//file_put_contents('../1.txt', $res."\r\n",FILE_APPEND);
			//exit();
			$mysqli->execute_close();
			return $res;
			
		
		}
	//***获取proId=$id的城市
		public function getCity($id){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select *from city where proId=$id";
			$array=array();
			$res="[";
	   		$array=$mysqli->execute_dql_arr($sql);
			//echo"<option>--城市--</option>";
			for($i=0;$i<count($array);$i++){
				if($i==count($array)-1){
					$res.='{"city":"'.$array[$i]["name"].'","cityId":"'.$array[$i]["id"].'"}';
				}else{
					$res.='{"city":"'.$array[$i]["name"].'","cityId":"'.$array[$i]["id"].'"},';
				}				
			}
			$res.="]";
			$mysqli->execute_close();
			return $res;
			
		}
		//根据proId=$proId and countryId=$countryId获取大学的数据
		public function getColleges($countryId,$proId){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select*from university where proId=$proId and countryId=$countryId";
			$array=array();
			$res="[";
			$array=$mysqli->execute_dql_arr($sql);
			for($i=0;$i<count($array);$i++){
				if($i==count($array)-1){
					$res.='{"college":"'.$array[$i]["name"].'","collegeId":"'.$array[$i]["id"].'"}';
				}else{
					$res.='{"college":"'.$array[$i]["name"].'","collegeId":"'.$array[$i]["id"].'"},';
				}
			}
			$res.="]";
			$mysqli->execute_close();
			return $res;
		}
	//***获取星座	
		public function getConstellation(){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select *from constellation";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			for($i=0;$i<count($arr);$i++){
				echo"<option value={$arr[$i][name]}>{$arr[$i][name]}</option>";
			}
			$mysqli->execute_close();
		}
		//***获取身高
		public function getHeight(){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select *from height";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			for($i=0;$i<count($arr);$i++){
				$res.="<option value={$arr[$i][height]}>{$arr[$i][height]}</option>";
			}
			//file_put_contents('1.txt', $res."\r\n",FILE_APPEND);
			//exit();
			echo $res;
			$mysqli->execute_close();
		}
		//***获取体重
		public function getWeight(){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select *from weight";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			for($i=0;$i<count($arr);$i++){
				$res.="<option value={$arr[$i][weight]}>{$arr[$i][weight]}</option>";
			}
			//file_put_contents('1.txt', $res."\r\n",FILE_APPEND);
			//exit();
			echo $res;
			$mysqli->execute_close();
		}
		//***获取年龄
		public function getAge(){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select age from age";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			for($i=0;$i<count($arr);$i++){
				$res.="<option value={$arr[$i][age]}>{$arr[$i][age]}</option>";
			}
			//file_put_contents('1.txt', $res."\r\n",FILE_APPEND);
			//exit();
			echo $res;
			$mysqli->execute_close();
		}
		//***获取入学年份
		public function getEntryUniversityTime(){
			//global $mysqli;
			$mysqli=new MysqliTool();
			$sql="select year from entryUniversityTime";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			for($i=0;$i<count($arr);$i++){
				$res.="<option value={$arr[$i][year]}>{$arr[$i][year]}</option>";
			}
			//file_put_contents('1.txt', $res."\r\n",FILE_APPEND);
			//exit();
			echo $res;
			$mysqli->execute_close();
		}
		/**
		 * getCountryById($id) 通过$id获取属于该$id的国家的名字
		 * @param int $id 属于该国家的id
		 * @return string $res 返回输出的是<option value="id" >国家</option>
		 * ps:为什么value="id" 是因为我点击<select>的时候传输的都是value=id而不是name 稍微注意下
		 */
		public function getCountryById($id){
			$mysqli=new MysqliTool();
			$sql="select name from country where id=$id limit 1";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			$res.="<option value=$id>{$arr[0]['name']}</option>";
			echo $res;
			$mysqli->execute_close();
		}
		/**
		 * getProvinceById($id) 通过$id获取属于该$id的省份的名字
		 * @param int $id 属于该省份的id
		 * @return string $res 返回输出的是<option value="id" >省份</option>
		 * ps:为什么value="id" 是因为我点击<select>的时候传输的都是value=id而不是name 稍微注意下
		 */
		public function getProvinceById($id){
			$mysqli=new MysqliTool();
			$sql="select name from province where id=$id limit 1";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			$res.="<option value=$id>{$arr[0]['name']}</option>";
			echo $res;
			$mysqli->execute_close();
		}
		/**
		 * getCityById($id) 通过$id获取属于该$id的城市的名字
		 * @param int $id 属于该省份的id
		 * @return string $res 返回输出的是<option value="id" >城市</option>
		 * ps:为什么value="id" 是因为我点击<select>的时候传输的都是value=id而不是name 稍微注意下
		 */
		public function getCityById($id){
			$mysqli=new MysqliTool();
			$sql="select name from city where id=$id limit 1";
			$arr=array();
			$arr=$mysqli->execute_dql_arr($sql);
			$res='';
			$res.="<option value=$id>{$arr[0]['name']}</option>";
			echo $res;
			$mysqli->execute_close();
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>