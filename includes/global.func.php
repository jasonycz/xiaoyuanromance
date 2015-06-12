<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-23
*/

/**
 * _remove_Dir() 用来删除文件夹(无论当中是不是有文件或者文件夹)
 * @param string $dirName
 * @return boolean
 */
function _remove_Dir($dirName)
{
	if(! is_dir($dirName))
	{
		return false;
	}
	//打开一个目录句柄，可用于之后的 closedir() ， readdir() 和 rewinddir() 调用中
	$handle = @opendir($dirName);
	while(($file = @readdir($handle)) !== false)
	{
		if($file != '.' && $file != '..')
		{
			$dir = $dirName . '/' . $file;
			is_dir($dir) ? _remove_Dir($dir) : @unlink($dir);
		}
	}
	closedir($handle);
	return rmdir($dirName) ;
}


/**
 *_runtime()是用来获取执行耗时
 * @access public  表示函数对外公开
 * @return float 表示返回出来的是一个浮点型数字
 */
function _runtime() {
	$_mtime = explode(' ',microtime());
	return $_mtime[1] + $_mtime[0];
}

/**
 * _alert_back()表是JS弹窗
 * @access public
 * @param $_info
 * @return void 弹窗
 */
//_alert_back_two_step($_info)是为了解决2014-12-30 17:04  1.关于注册界面的遗留问题
function _alert_back_two_step($_info) {
	echo "<script type='text/javascript'>alert('$_info');history.go(-1);</script>";
	exit();
}
function _alert_back($_info) {
	echo "<script type='text/javascript'>alert('$_info');history.back();</script>";
	exit();
}

function _alert_close($_info) {
	echo "<script type='text/javascript'>alert('$_info');window.close();</script>";
	exit();
}

function _location($_info,$_url) {
	if (!empty($_info)) {
		echo "<script type='text/javascript'>alert('$_info');location.href='$_url';</script>";
		exit();
	} else {
		header('Location:'.$_url);
	}
}
/**
 * _login_state登录状态的判断
 */

function _login_state() {
	if (isset($_COOKIE['username'])) {
		_alert_back('登录状态无法进行本操作！');
	}
}

/**
 * 判断唯一标识符是否异常
 * @param $_mysql_uniqid
 * @param $_cookie_uniqid
 */

function _uniqid($_mysql_uniqid,$_cookie_uniqid) {
	if ($_mysql_uniqid != $_cookie_uniqid) {
		_alert_back('唯一标识符异常！');
	}
}
/**
 * htmlspecialchars() 函数把一些预定义的字符转换为 HTML 实体。
 * @param string $_string
 * @return string
 */
function _html($_string) {
	if (is_array($_string)) {
		foreach ($_string as $_key => $_value) {
			$_string[$_key] = _html($_value);   //这里采用了递归，如果不理解，那么还是用htmlspecialchars
		}
	} else {
		$_string = htmlspecialchars($_string);
	}
	return $_string;
}


/**
 * _title()标题截取函数
 * @param $_string
 */

function _title($_string,$_strlen) {
	if (mb_strlen($_string,'utf-8') > $_strlen) {
		$_string = mb_substr($_string,0,$_strlen,'utf-8').'...';
	}
	return $_string;
}

/**
 * _mysql_string
 * @param string $_string
 * @return string $_string
 */

function _mysql_string($_string) {
	//get_magic_quotes_gpc()如果开启状态，那么就不需要转义
	if (!GPC) {
		if (is_array($_string)) {
			foreach ($_string as $_key => $_value) {
				$_string[$_key] = _mysql_string($_value);   //这里采用了递归，如果不理解，那么还是用htmlspecialchars
			}
		} else {
			$_string = mysql_real_escape_string($_string);
		}
	}
	return $_string;
}


/**
 *
 * @param $_sql
 * @param $_size
 */

function _page($_sql,$_size) {
	//将里面的所有变量取出来，外部可以访问
	global $_page,$_pagesize,$_pagenum,$_pageabsolute,$_num;
	global $globalService;
	if (isset($_GET['page'])) {
		$_page = $_GET['page'];
		//还需要修改
		//$_COOKIE['test']=1;
		if (empty($_page) || $_page <= 0 || !is_numeric($_page)) {
			$_page = 1;
		} else {
			$_page = intval($_page);
		}
	} else {
		$_page = 1;
	}
	$_pagesize = $_size;
	$array=$globalService->_query($_sql);
	$_num =count($array);	
	if ($_num == 0) {
		$_pageabsolute = 1;
	} else {
		$_pageabsolute = ceil($_num / $_pagesize);
		//我觉得这样才合理  改动日期：2014-12-31 16:58
		if ($_page > $_pageabsolute) {
			$_page = $_pageabsolute;
		}
	}
//	觉得不合理
// 	if ($_page > $_pageabsolute) {
// 		$_page = $_pageabsolute;
// 	}
	
	$_pagenum = ($_page - 1) * $_pagesize;
}


/**
 * _paging分页函数
 * @param $_type
 * @return 返回分页
 */

function _paging($_type) {
	global $_page,$_pageabsolute,$_num,$_id;
	if ($_type == 1) {
		echo '<div id="page_num">';
		echo '<ul>';
		for ($i=0;$i<$_pageabsolute;$i++) {
			if ($_page == ($i+1)) {
				echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
			} else {
				echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a></li>';
			}
		}
		echo '</ul>';
		echo '</div>';
	} elseif ($_type == 2) {
		echo '<div id="page_text">';
		echo '<ul>';
		echo '<li>'.$_page.'/'.$_pageabsolute.'页 | </li>';
		echo '<li>共有<strong>'.$_num.'</strong>条数据 | </li>';
		if ($_page == 1) {
			echo '<li>首页 | </li>';
			echo '<li>上一页 | </li>';
		} else {
// 			echo '<li><a href="'.SCRIPT.'.php">首页</a> | </li>'; 
//			修改日期 2015-01-12 17:44  我这么修改更加的能够适应
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page=1"'.'>首页</a> | </li>';
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a> | </li>';
		}
		if ($_page == $_pageabsolute) {
			echo '<li>下一页 | </li>';
			echo '<li>尾页</li>';
		} else {
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a> | </li>';
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.$_pageabsolute.'">尾页</a></li>';
		}
		echo '</ul>';
		echo '</div>';
	} else {
		_paging(2);
	}
}

/**
 * _session_destroy删除session
 */

function _session_destroy() {
	if (session_start()) {
		session_destroy();
	}
}

/**
 * 删除cookies   _unsetcookies()
 */

function _unsetcookies() {
	setcookie('userId','',time()-1);
	setcookie('email','',time()-1);
	setcookie('username','',time()-1);
	setcookie('uniqid','',time()-1);
	_session_destroy();
	_location(null,'login.php');
// 	_location('感谢使用','login.php');
}


/**
 *
 */

function _sha1_uniqid() {
	return _mysql_string(sha1(uniqid(rand(),true)));
}



/**
 * _check_code
 * @param string $_first_code
 * @param string $_end_code
 * @return void 验证码比对
 */

function _check_code($_first_code,$_end_code) {
	if ($_first_code != $_end_code) {
		_alert_back('验证码不正确!');
		//_alert_back_two_step('验证码不正确!');
	
	}
}

/**
 * _code()是验证码函数
 * @access public
 * @param int $_width 表示验证码的长度
 * @param int $_height 表示验证码的高度
 * @param int $_rnd_code 表示验证码的位数
 * @param bool $_flag 表示验证码是否需要边框
 * @return void 这个函数执行后产生一个验证码
 */
function _code($_width = 75,$_height = 25,$_rnd_code = 4,$_flag = false) {
	//创建随机码
	for ($i=0;$i<$_rnd_code;$i++) {
		$_nmsg .= dechex(mt_rand(0,15));
	}
	//保存在session
	$_SESSION['code'] = $_nmsg;
	
	//创建一张图像
	$_img = imagecreatetruecolor($_width,$_height);
	//白色
	$_white = imagecolorallocate($_img,255,255,255);
	//填充
	imagefill($_img,0,0,$_white);

	if ($_flag) {
		//黑色,边框
		$_black = imagecolorallocate($_img,0,0,0);
		imagerectangle($_img,0,0,$_width-1,$_height-1,$_black);
	}
	//随即画出6个线条
	/*for ($i=0;$i<6;$i++) {
		$_rnd_color = imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		imageline($_img,mt_rand(0,$_width),mt_rand(0,$_height),mt_rand(0,$_width),mt_rand(0,$_height),$_rnd_color);
	}*/
	//随即雪花
	for ($i=0;$i<100;$i++) {
		$_rnd_color = imagecolorallocate($_img,mt_rand(150,255),mt_rand(150,255),mt_rand(150,255));
		imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),'*',$_rnd_color);
	}
	//输出验证码
	for ($i=0;$i<strlen($_SESSION['code']);$i++) {
		$_rnd_color = imagecolorallocate($_img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));
		$font='fonts/timesbd.ttf';
		imagettftext($_img,24,0,$i*$_width/$_rnd_code+mt_rand(1,10),mt_rand($_height/2,$_height-1),$_rnd_color,$font,$_SESSION['code'][$i]);
		//imagestring($_img,5,$i*$_width/$_rnd_code+mt_rand(1,10),mt_rand(1,$_height/2),$_SESSION['code'][$i],$_rnd_color);
	}
	//输出图像
	header('Content-Type: image/png');
	imagepng($_img);
	//销毁
	imagedestroy($_img);
}

























?>