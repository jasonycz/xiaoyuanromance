
/**
 * per2num() 百分数变为小数
 * per percent 要转变的百分数
 * return float  返回小数
 */
function per2num(per) {
    return per.replace(/([0-9.]+)%/, function (a, b) { return +b / 100;})
}
  
  
function centerWindow(height,width) {
	var obj=new Object;
	//alert(screen.width+' '+screen.height);
    obj.left = ($(window).width() - width) / 2;
    obj.top = ($(window).height() - height) / 2;
//		window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
	return obj;
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
  
 
 

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  