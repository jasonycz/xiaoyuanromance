<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-29
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}

if (!function_exists('_alert_back')) {
	exit('_alert_back()函数不存在，请检查!');
}

if (!function_exists('_mysql_string')) {
	exit('_mysql_string()函数不存在，请检查!');
}

/**
 * _setcookies生成登录cookies
 * @param int $_userId 用户的Id 因为后面很多地方都用到，所以添加了
 * @param string $_email 登录的邮箱
 * @param string $_username 用户的名字
 * @param string $_uniqid	唯一标识
 * @param int $_keep_time	COOKIE保存的时间
 */


function _setcookies($_userId,$_email,$_username,$_uniqid,$_keep_time) {
	switch ($_keep_time) {
		case '0':  //浏览器进程
			setcookie('userId',$_userId);
			setcookie('email',$_email);
			setcookie('username',$_username);
			setcookie('uniqid',$_uniqid);			
			break;
		case '1':  //一天
			setcookie('userId',$_userId,time()+86400);
			setcookie('email',$_email,time()+86400);
			setcookie('username',$_username,time()+86400);
			setcookie('uniqid',$_uniqid,time()+86400);
			break;
		case '2':  //一周
			setcookie('userId',$_userId,time()+604800);
			setcookie('email',$_email,time()+604800);
			setcookie('username',$_username,time()+604800);
			setcookie('uniqid',$_uniqid,time()+604800);
			break;
		case '3':  //一月
			setcookie('userId',$_userId,time()+2592000);
			setcookie('email',$_email,time()+2592000);
			setcookie('username',$_username,time()+2592000);
			setcookie('uniqid',$_uniqid,time()+2592000);
			break;
	}
}
/**
 * _check_email() 检查邮箱是否合法
 * @access public
 * @param string $_string 提交的邮箱地址
 * @return string $_string 验证后的邮箱
 */

function _check_email($_string,$_min_num,$_max_num) {
	//参考bnbbs@163.com
	//[a-zA-Z0-9_] => \w
	//[\w\-\.] 16.3.
	//\.[\w+] .com.com.com.net.cn
	//正则挺起来比较麻烦，但是你理解了，就很简单了。
	//如果听起来比较麻烦，就直接套用

	if (!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/',$_string)) {
		_alert_back('邮件格式不正确！');
	}
	if (strlen($_string) < $_min_num || strlen($_string) > $_max_num) {
		_alert_back('邮件长度不合法！');
	}

	return _mysql_string($_string);
}


/**
 * _check_username表示检测并过滤用户名
 * @access public
 * @param string $_string 受污染的用户名
 * @param int $_min_num  最小位数
 * @param int $_max_num 最大位数
 * @return string  过滤后的用户名
 */
function _check_username($_string,$_min_num,$_max_num) {
	//去掉两边的空格
	$_string = trim($_string);

	//长度小于两位或者大于20位
	if (mb_strlen($_string,'utf-8') < $_min_num || mb_strlen($_string,'utf-8') > $_max_num) {
		_alert_back('用户名长度不得小于'.$_min_num.'位或者大于'.$_max_num.'位');
	}

	//限制敏感字符
	$_char_pattern = '/[<>\'\"\ \　]/';
	if (preg_match($_char_pattern,$_string)) {
		_alert_back('用户名不得包含敏感字符');
	}

	//将用户名转义输入
	return _mysql_string($_string);
}


/**
 * _check_password验证密码
 * @access public
 * @param string $_first_pass
 * @param int $_min_num
 * @return string $_first_pass 返回一个加密后的密码
 */

function _check_password($_string,$_min_num) {
	//判断密码
	if (strlen($_string) < $_min_num) {
		_alert_back('密码不得小于'.$_min_num.'位！');
	}

	//将密码返回
	return sha1($_string);
}


function _check_keep_time($_string) {
	$_time = array('0','1','2','3');
	if (!in_array($_string,$_time)) {
		_alert_back('保留方式出错！');
	}
	return _mysql_string($_string);
}


?>