//处理提交的时候相册的命名 过滤问题。
window.onload = function () {
	//表单验证[0]表示的是第一个表单
	var fm = document.getElementsByTagName('form')[0];
	if (fm == undefined) return;
	fm.onsubmit = function () {
		//能用客户端验证的，尽量用客户端
		//对上传的文件的大小，类型进行判定
		//不能超过2M  类型只能够是'image/jpeg','image/pjpeg','image/png','image/x-png','image/gif','image/bmp'	
//		alert(fm.upLoadFile.files[0].size);		
		if(fm.upLoadFile.files[0].size >2*1024*1024){
				alert('上传的图片不能超过2M');
				window.location.href="myPhotography.php";
				return false;
			}
		//$.inArray();返回的值从0开始。
		var array=['image/jpeg','image/pjpeg','image/png','image/x-png','image/gif','image/bmp'	];
		if($.inArray(fm.upLoadFile.files[0].type,array)==-1){
			alert('图片的类型不正确');
			window.location.href="myPhotography.php";
			return false;
		}

		//相片描述不能够超过50个字数
		if(fm.photoDescript.value !=''){
//			alert(fm.photoDescript.value.length);
			if (fm.photoDescript.value.length > 50) {
				alert('相片的描述请不要超过50个字');
				fm.photoDescript.value = ''; //清空
				fm.photoDescript.focus(); //将焦点以至表单字段
				return false;
			}
		}
		//相册的名字不能包含不合法的字符
		if (/[~,!@#$%&^*_?<>\'\"]/.test(fm.upLoadPhotographyName.value)) {
			alert("相册的名字不合理不能包含~,!,@,#,$,%,&,^,*,_,?,<,>,逗号，双引号");
			fm.upLoadPhotographyName.value = ''; //清空
			fm.upLoadPhotographyName.focus(); //将焦点以至表单字段
			return false;
		}
		if (fm.upLoadPhotographyName.value==0) {
			alert("相册的名字不能为空");
			fm.upLoadPhotographyName.value = ''; //清空
			fm.upLoadPhotographyName.focus(); //将焦点以至表单字段
			return false;
		}
// 		alert('js 通过');
 		return true;
	};
};


/**
 * getDate() 获取当前日期的函数
 */
function getDate(){
	var myDate = new Date();
	//myDate.getYear(); //获取当前年份(2位)
	var Y=myDate.getFullYear(); //获取完整的年份(4位,1970-????)
	var M=myDate.getMonth()+1; //获取当前月份(0-11,0代表1月)
	var D=myDate.getDate(); //获取当前日(1-31)
	//myDate.getMilliseconds(); //获取当前毫秒数(0-999)
	//myDate.toLocaleDateString(); //获取当前日期
	//为了和now()函数显示的日期一样 针对当前的日是1-9 now()的显示是01-09 做一下处理
	if(M<10){
		M="0"+M;
	}
	if(D<10){
		D="0"+D;
	}

	var date=Y+"-"+M+"-"+D;
	//alert(dateTime);
	return date;
	
}
/**
 * getDateTime() 获取当前日期时间的函数
 */
function getDateTime(){
	var myDate = new Date();
	//myDate.getYear(); //获取当前年份(2位)
	var Y=myDate.getFullYear(); //获取完整的年份(4位,1970-????)
	var M=myDate.getMonth()+1; //获取当前月份(0-11,0代表1月)
	var D=myDate.getDate(); //获取当前日(1-31)
	//myDate.getDay(); //获取当前星期X(0-6,0代表星期天)
	//myDate.getTime(); //获取当前时间(从1970.1.1开始的毫秒数)
	var H=myDate.getHours(); //获取当前小时数(0-23)
	var i=myDate.getMinutes(); //获取当前分钟数(0-59)
	var s=myDate.getSeconds(); //获取当前秒数(0-59)
	//myDate.getMilliseconds(); //获取当前毫秒数(0-999)
	//myDate.toLocaleDateString(); //获取当前日期
	//为了和now()函数显示的日期一样 针对当前的日是1-9 now()的显示是01-09 做一下处理
	if(M<10){
		M="0"+M;
	}
	if(D<10){
		D="0"+D;
	}
	if(H<10){
		H="0"+H;
	}
	if(i<10){
		i="0"+i;
	}
	if(s<10){
		s="0"+s;
	}
	var dateTime=Y+"-"+M+"-"+D+" "+H+":"+i+":"+s;
	//alert(dateTime);
	return dateTime;
	
}

/****************************************加载图片并且预览*********************************/ 
	function previewImage(file){
	  var MAXWIDTH  = 450;
	  var MAXHEIGHT = 400;
	  var div = document.getElementById('preview');
	  if (file.files && file.files[0]){
	    div.innerHTML = '<img id=imghead>';
	    //div.innerHTML += '<input type="text" value='+file.files[0].name+'/>';
	    //div.innerHTML += '<input type="file" value='+file.files[0].name+'/>';
//	    alert(file.files[0].type+' '+file.files[0].size+' '+file.files[0].name);
	    var img = document.getElementById('imghead');
	    img.onload = function(){
	      var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
	      img.width = rect.width;
	      img.height = rect.height;
	      img.style.marginLeft = rect.left+'px';
	      img.style.marginTop = rect.top+'px';
	    }
	    var reader = new FileReader();
	    reader.onload = function(evt){img.src = evt.target.result;}
	    reader.readAsDataURL(file.files[0]);
	  }else{
		alert('使用的是下面这个');
	    var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
	    file.select();
	    var src = document.selection.createRange().text;
	    //div.innerHTML ='<input type="file" name="upLoadFile" id="upload"  />';
	    div.innerHTML+= '<img id=imghead>';
	    var img = document.getElementById('imghead');
	    img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
	    var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
	    status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
	    div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;margin-left:"+rect.left+"px;"+sFilter+src+"\"'></div>";
	  }
}
function clacImgZoomParam( maxWidth, maxHeight, width, height ){
    var param = {top:0, left:0, width:width, height:height};//这种设置变量的做法可以借鉴
    if( width>maxWidth || height>maxHeight ){
        rateWidth = width / maxWidth;
        rateHeight = height / maxHeight;
        if( rateWidth > rateHeight ){
            param.width =  maxWidth;
            param.height = Math.round(height / rateWidth);//四舍五入法
        }else{
            param.width = Math.round(width / rateHeight);
            param.height = maxHeight;
        }
	}
    param.left = Math.round((maxWidth - param.width) / 2);
    param.top = Math.round((maxHeight - param.height) / 2);
    return param;
} 
/**********************************加载和预览js结束********************************************/