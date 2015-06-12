<?php
/**
 * 我添加的代码，这些只是暂时为了用上这些功能临时这么做，以后可定要规范起来的
 */
$upLoadedUserId=$_COOKIE['userId'];
// session_start();
// $upLoadedUser=$_SESSION['userName'];
//定义个常量，用来授权调用includes里面的文件
//define('IN_TG',true);
//引入公共文件 为什么不能包含 包含就会出现错误  应该是我之前已经包含了 错误 应该是dirname(__FILE__)的缘故
//明天弄明白dirname(__FILE__)的意义吧2015-01-04 晚上 11点左右  已经弄明白了 2015-01-05 10:01
//__FILE__  用在被包含文件中，则返回被包含的文件名
// require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
// include  ROOT_PATH.'class/PhotographyService.class.php';

include  '../../class/Mysqli.class.php';
include  '../../class/PhotographyService.class.php';
//file_put_contents('2.txt', ROOT_PATH."\r\n",FILE_APPEND);
//exit();
/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";

/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" =>$CONFIG['imagePathFormat'],  
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => $CONFIG['scrawlPathFormat'],
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => $CONFIG['videoPathFormat'],
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => $CONFIG['filePathFormat'],
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64,$upLoadedUserId);

/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

//保存照片需要的参数有：相册  照片的名字['title']  应该在这里呢 还是在Uploader.class.php中处理呢 目前感觉在后面
//那个处理更加的好  
//现在暂时固定一些值
// $photoName=$up->getFileInfo()['title'];细想想这样写确实不合理
$arr=array();
$arr=$up->getFileInfo();
$photoName=$arr['title'];//这样更加的合理  日期：2015-03-20 14:45
// $NewFileName=$upLoadedUserId."SuiGan";
$NewFileName="我的随感";
$photoDescript="我的随感相册";
//肯定存在那个相册的 所以直接存入就好了
//file_put_contents('1.txt', $photoName."\r\n",FILE_APPEND);
//***要做两件事情
// 第一 判定相册是不是已经存在  存在不再存入到数据库 否则存入到相册表
// 第二把相片的名字存入到相片表中

$photographyService=new photographyService();
//将相册的名字存入到相册表
$photographyId=$photographyService->saveToPhotography($upLoadedUserId, $NewFileName);
//将相片的名字存入到相片表
$photographyService->saveToPhoto($photoName,$photographyId,$photoDescript);


/* 返回数据 */
return json_encode($up->getFileInfo());
