<?php

/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
class Uploader
{	
	private $upLoadedUserId;
	private $format;//解决重名的时候要用到的一个变量
	
    private $fileField; //文件域名
    private $file; //文件上传对象
    private $base64; //文件上传对象
    private $config; //配置信息
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,
    private $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确"
    );

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct($fileField, $config, $type = "upload",$upLoadedUserId)
    {	
    	//添加日期：2014-12-15 22:33 start
    	$this->upLoadedUserId=$upLoadedUserId;
    	//file_put_contents('1.txt', $this->upLoadedUser."\r\n",FILE_APPEND);
    	//file_put_contents('1.txt', ($this->upLoadedUser)."\r\n",FILE_APPEND);才对
    	//end
        $this->fileField = $fileField;
        $this->config = $config;
        $this->type = $type;
        if ($type == "remote") {
            $this->saveRemote();
        } else if($type == "base64") {
            $this->upBase64();
        } else {
            $this->upFile();
        }

        $this->stateMap['ERROR_TYPE_NOT_ALLOWED'] = iconv('unicode', 'utf-8', $this->stateMap['ERROR_TYPE_NOT_ALLOWED']);
    }

    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upFile()
    {
        $file = $this->file = $_FILES[$this->fileField];
        if (!$file) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMPFILE");
            return;
        }
		//这个是图片的名字 要进行转码就在这里进行就好了
        $this->oriName = $file['name'];
/******************************************************************************/
        
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        //关于重名问题，我觉得在方法里面处理更加的好
        //目地什么？没有什么目地  存入到文件夹中的是在哪里 这个很重要
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        //下面应该是创建文件并把图片转移到那里面去了或者是新建目录把文件转移到里面去了，所以在这之前我要把重名的图片修改了
        $dirname = dirname($this->filePath);
       // file_put_contents('1.txt', $dirname."\r\n",FILE_APPEND);
       // file_put_contents('1.txt', $this->filePath."\r\n",FILE_APPEND);
       // file_put_contents('1.txt', $file["tmp_name"]."\r\n",FILE_APPEND);

        //E:/MyEnv/Apache24/Apache24/htdocsE:/MyEnv/Apache24/Apache24/htdocs/XiaoYuanRomance/UserResourse/Photography/ycz/yczSuiGan
        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }
		
        
        //移动文件
        if (!(move_uploaded_file($file["tmp_name"], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 处理base64编码的图片上传
     * @return mixed
     */
    private function upBase64()
    {
        $base64Data = $_POST[$this->fileField];
        $img = base64_decode($base64Data);

        $this->oriName = $this->config['oriName'];
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }

    }

    /**
     * 拉取远程图片
     * @return mixed
     */
    private function saveRemote()
    {
        $imgUrl = htmlspecialchars($this->fileField);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);

        //http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_LINK");
            return;
        }
        //获取请求头并检测死链
        $heads = get_headers($imgUrl);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = $this->getStateInfo("ERROR_DEAD_LINK");
            return;
        }
        //格式验证(扩展名验证和Content-Type验证)
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $this->config['allowFiles']) || stristr($heads['Content-Type'], "image")) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_CONTENTTYPE");
            return;
        }

        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $this->oriName = $m ? $m[1]:"";
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        
        
        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }

    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo($errCode)
    {
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower(strrchr($this->oriName, '.'));
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName()
    {	
    	///ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}
    	///XiaoYuanRomance/UserResourse/Photography/userName/photoName
    	//我要做的就是把userName 用用户名 session技术替换了  photoName用原来的文件名替换了   	
    	$format = $this->config["pathFormat"];    	
    	//下面的意思是把$format中的userName替换成$this->upLoadedUser并且返回到$format
    	$format = str_replace("userId",$this->upLoadedUserId, $format);
    	//下面的意思是把$format中的userName替换成$this->upLoadedUser并且返回到$format
    	//$format = str_replace("suiGan",$this->upLoadedUserId."SuiGan", $format);
    	$format = str_replace("suiGan","我的随感", $format);
    	
    	//file_put_contents('1.txt', $format ."\r\n",FILE_APPEND);   	
    	//过滤文件名的非法自负,并替换文件名
    	///strrpos(a,b);返回b在a中最后出现的位置的数值
    	///下面的语句应该就是获取图片的名字 不要图片的类型了
    	$oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
    	///正则表达式：原子表   +表示匹配前一个类容的一次或多次  | ? " < > / * \\ 都用空来替换
    	$oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
    	$format = str_replace("photoName", $oriName, $format);   	  	
   		$this->format=$format;    	
    	$ext = $this->getFileExt();  	
    	return $format . $ext;   	
    }

    /**
     * 获取文件名
     * @return string
     */
    private function getFileName () {
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 获取文件完整路径(这个完整路径指的是可以到图片的名字)
     * @return string
     */
    private function getFilePath()
    {
        $fullname = $this->fullName;
        $rootPath = $_SERVER['DOCUMENT_ROOT'];        
        //file_put_contents('1.txt', $fullname."\r\n",FILE_APPEND);
        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }
        //添加日期：2014-12-16 17:39 start
        if(file_exists($rootPath . $fullname)){
        	$fullname=$this->format.date('Ymd',time()).rand(0,9999).strrchr($rootPath . $fullname, '.');
        }
        //转码  让文件转移的时候能够正常的转移   中文不会乱码
        $fullname=iconv("utf-8", "gb2312", $fullname); 
        //end
        return $rootPath . $fullname;
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        return in_array($this->getFileExt(), $this->config["allowFiles"]);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= ($this->config["maxSize"]);
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    //添加日期：2014-12-15 23:08 start
    //返回：相册的名字  图片的名字（title title我会做一下处理 若是存在 那么我将改变图片的名字）
    //end
    
    public function getFileInfo()
    {	
    	//解决图片的名字是中文的问题  因为在fileName是从filePath来的 之前filePath已经被我进行了一次
    	//utf-8到gb2312的转码  现在应该要转回来 这样的话才对  （否则会提示错误，可能是Ueditor要求的吧）
    	//添加日期：2014-12-16 17:38 start
    	$this->fileName=iconv("gb2312", "utf-8", $this->fileName);
    	//end
        return array(
            "state" => $this->stateInfo,
            "url" => $this->fullName,//src="url"
            "title" => $this->fileName,//titel="title"//这个也是改动后的名字  服务器中存储的名字
            "original" => $this->oriName."111111",//original="alt"
            "type" => $this->fileType,
            "size" => $this->fileSize
        );
    }

}