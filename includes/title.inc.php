<?php
/**
* XiaoYuanRomance
* ================================================
* Copy 2014-2015
* Web: http://xiaoyuanromance.com
* ================================================
* Author:ycz
* Date: 2014-12-25
*/
global $_system;
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Forbidden!');
}
//防止非HTML页面调用
if (!defined('SCRIPT')) {
	exit('Script Error!');
}

?>
<title><?php echo $_system['webname']."—".TITLE_NAME;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<link rel="shortcut icon" href="images/tempLogo.jpg" />
<link rel="stylesheet" type="text/css" href="styles/basic.css" />
<link rel="stylesheet" type="text/css" href="styles/<?php echo SCRIPT?>.css" />		
<script src="js/jquery-1.11.1.min.js"></script>	
<script src="js/<?php echo SCRIPT?>.js"></script>	
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>

<script type="text/javascript">//获取屏幕的分辨率
	var width=window.screen.availWidth;
//	var width=document.body.clientWidth;
	//alert(width);
	$('html').css('width',width);
</script>























