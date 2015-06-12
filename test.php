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
// session_start();
// //定义个常量，用来授权调用includes里面的文件
// define('IN_TG',true);
// //引入公共文件
// require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

// $string="i 'love' you!";
// echo $string;
// echo "<hr/>";
// //echo addslashes($string)."<br>";
// echo  preg_replace('/\ /','\\ ', $string);
 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>定义input type="file" 的样式</title>
<style type="text/css">
body{ font-size:14px;}
input{ vertical-align:middle; margin:0; padding:0}
.file-box{ position:relative;width:340px}
.txt{ height:22px; border:1px solid #cdcdcd; width:180px;}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:24px; width:70px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:260px }
</style>
</head>
<body>
<div class="file-box">
  <form action="" method="post" enctype="multipart/form-data">
 <input type='text' name='textfield' id='textfield' class='txt' />  
 <input type='button' class='btn' value='浏览...' />
    <input type="file" name="fileField" class="file" id="fileField" size="28" onchange="document.getElementById('textfield').value=this.value" />
 <input type="submit" name="submit" class="btn" value="上传" />
  </form>
</div>
</body>
</html>




