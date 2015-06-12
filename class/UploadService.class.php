<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2015-1-13
*/	
	//包含是相对调用的那个文件的路径来说的 所以include '../includes/check.func.php';不对
	//其实这两个包含文件放到调用的那张页面更合理？这样的话，即使我不在根目录下访问也可以，只要相应的在
	//调用这个类的那个页面包含这两个包含文件，路径就可以自己变化  但是目前的情况用不着因为我都在根目录下
	include_once 'includes/check.func.php';
	include_once 'class/PhotographyService.class.php';
	class UploadService{
		
		/**
		 * _upload_photo()  上传相片到磁盘并且在数据库中进行记录
		 * @param string $_name	 默认的是upLoadFile   input控件的name的名字 以前我全部用upLoadFile 现在为了增加灵活性 设置了该参量
		 * @param string $_photography_name	相册的名字
		 * @param string $_photo_descript	相片的描述	
		 * @return string $iconvPhotoName	utf-8类型的最终的上传相片的名字
		 * 
		 */
		
		public function _upload_photo($_photography_name,$_photo_descript,$_name="upLoadFile"){
				global $_userId;
				//验证相册的名字和描述的长度
				$_photography_name=_check_photography_name($_photography_name);
				$_photo_descript= _check_photo_descript($_photo_descript,50);
				
				//设置上传图片的类型
				$_files = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif','image/bmp');
				
// 				file_put_contents('1.txt', "文件的类型".$_userId.":".$_FILES['upLoadFile']['type']."\r\n",FILE_APPEND);
// 				exit();
				
				if (is_array($_files)) {
					if (!in_array($_FILES['upLoadFile']['type'],$_files)) {
						_alert_back('上传图片必须是jpg,jpeg,png,gif,jpeg,x-png中的一种！');
					}
				}
				//设定上传文件的大小 2M
				if ($_FILES['upLoadFile']['size'] > 2*1024*1024) {
					_alert_back('上传的文件不得超过2M');
				}
				//判断文件错误类型
				if ($_FILES['upLoadFile']['error'] > 0) {
					switch ($_FILES['upLoadFile']['error']) {
						case 1: _alert_back('上传文件超过配置文件中的大小');
						break;
						case 2: _alert_back('上传文件超过表单中设置的大小');
						break;
						case 3: _alert_back('部分文件被上传');
						break;
						case 4: _alert_back('没有任何文件被上传！');
						break;
					}
					exit;
				}
				//判断文件是否上传了
				if(is_uploaded_file($_FILES['upLoadFile']['tmp_name'])){
					/*******把文件进行转移开始****************/
					//取上传的地址
					$upLoadedFile=$_FILES['upLoadFile']['tmp_name'];
					$userPath="../UserResourse/Photography/".$_userId."/".$_photography_name;//用户相册的地址
					//进行地址转码考虑到中文路径问题
					$userPath=iconv("utf-8", "gb2312", $userPath);
					if(!file_exists($userPath)){
						mkdir($userPath,0777, true);
					}
					//发现一个问题，对于已经用iconv处理过的字符串，如果再进行iconv函数处理，那么输出的字符串将是空的。
					$iconvPhotoName=iconv("utf-8", "gb2312",$_FILES['upLoadFile']['name']);
					$destination=$userPath."/".$iconvPhotoName;
					//如果文件名存在那么修改文件名为时间加后缀名 strrchr($moveToFile, '.')取得文件名的后缀
					if(file_exists($destination)){
						$iconvPhotoName=date('Y-m-d',time())."-".rand(0,9999).strrchr($destination, '.');
						$destination=$userPath."/".$iconvPhotoName;
						//如果有重复的就用时间来代替原来的名字
					}
				
					// 		//移动文件
					// 		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
					if	(!@move_uploaded_file($upLoadedFile,$destination)) {
						_alert_back('移动失败');
					} else {
						
						$userPath="../UserResourse/Photography/".$_userId."/".$_photography_name;
						$destination=$userPath."/".iconv("gb2312", "utf-8", $iconvPhotoName);
						
						$photographyService=new PhotographyService();
						//把相片的名字再转回来为utf-8的
						$iconvPhotoName=iconv("gb2312", "utf-8",$iconvPhotoName);
						//将相册的名字存入到相册表
						$photographyId=$photographyService->saveToPhotography($_userId, $_photography_name);
						//将相片的名字存入到相片表
						$photographyService->saveToPhoto($iconvPhotoName,$photographyId,$_photo_descript);
				
				  	}
				  	return $iconvPhotoName;
				 } else {
					_alert_back('上传的临时文件不存在！');
				 }
				 
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
		
		
		
			
		
?>