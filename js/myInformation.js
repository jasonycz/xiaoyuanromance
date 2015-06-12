//******************************myInformation.php中用到的函数Start
/*function $(id){//为什么这个函数在这里不起作用了 难道说是因为是.js文件？
  	return document.getElementById(id);
  }*/
  function show(obj){	
  	if(obj.id=="BasicInfohead"){
	  	if(obj.innerText=="》"){	
	  		document.getElementById("BasicInfo").style.display="block";
	  		document.getElementById("BasicInfohead").innerText="︽";
	  	}else if(obj.innerText="︽"){
	  		document.getElementById("BasicInfo").style.display="none";
	  		document.getElementById("BasicInfohead").innerText="》";
	  	}
	 }else if(obj.id=="PrivateInfohead"){	 	
	 		if(obj.innerText=="》"){	
	 			document.getElementById("PrivateInfo").style.display="block";
	 			document.getElementById("PrivateInfohead").innerText="︽";
		  	}else if(obj.innerText="︽"){
		  		document.getElementById("PrivateInfo").style.display="none";
		  		document.getElementById("PrivateInfohead").innerText="》";
		  	}
	 }	  
  }
function cancel(){
	if(confirm("确认取消")){
		return false;
	}else{
		return true;
	}
}
//******************************myInformation.php中用到的函数End

//*******************************自定义函数Start
/***************************************国家省市联动*******************************/
  //***得到省份的函数
  /*
   * @varType:访问下一链接的方式
   * @varUrl:下一链接的地址
   * @varData:给下一链接的数据
   * @varDataType:下一链接返回的数据格式
   * @outPut:返回得到的数据
   */
 function getProvince(varType,varUrl,varData,varDataType){
	// var html='';
	 var res;
	  $.ajax({
			type:varType,
			//考虑到要经常用到，所以url的处理直接放到myInformationProcess.php那里去处理
			 url:varUrl,
			 //传入的参数是国家的Id
			data:{CountryId:varData},
			async:false,//***********注意这里是同步 用异步的话return html是有问题的
		dataType:varDataType,
	  beforeSend:function(){},
	 	 success:function(data){	
	 		 res=data;	 		
		 },			
		 error:function(data,state){
			 alert(state);			 
       }
	});
	  return res;
  }
  //***得到城市的函数
  /*
   * @varType:访问下一链接的方式
   * @varUrl:下一链接的地址
   * @varData:给下一链接的数据
   * @varDataType:下一链接返回的数据格式
   * @outPut:返回得到的数据
   */
 function getCity(varType,varUrl,varData,varDataType){
	 var res;
	  $.ajax({
			type:varType,
			//考虑到要经常用到，所以url的处理直接放到myInformationProcess.php那里去处理
			 url:varUrl,
			 //传入的参数是国家的Id
			data:{ProvinceId:varData},
			async:false,//***********注意这里是同步 用异步的话return html是有问题的
		dataType:varDataType,
	  beforeSend:function(){},
	 	 success:function(data){	
	 		//解决了若点击国家后选择省 再点击"国家"而不是某个具体的国家值，由于国家并没有Id所以城市返回的是空，会出现城市
	 		//那个<option></option>中间的内容为空不好看。
	 		res=data;
		 },			
		 error:function(data,state){
			 alert(state);			 
       }
	});
	return res;
  }

//********************************自定义函数End
//********************************日历函数Start
var oDate = new Object();

function DateClass(_container) {
	var dateVal;//用于作为返回的日期的值
    this.author = '51JS.COM-ZMM';
    this.version = 'Web Calendar 1.0';
    this.container = _container;
    this.weekArr = ['日', '一', '二', '三', '四', '五', '六'];
    this.dateArr = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    this.showTable = function(_year, _month) {
         this.container.innerHTML = this.getDateTable(_year, _month);
    	//alert('okk');
        
    }

    this.getDateTable = function(_year, _month) {
         if (this.checkArgs(_year, _month)) {
             _year = parseInt(this.checkDate(_year, _month).split('-')[0]);
             _month = parseInt(this.checkDate(_year, _month).split('-')[1]);
             this.Thead = '<table cellpadding="5" cellspacing="0" class="DateTable">\n';
             this.Thead += '<tr><td align="center"  class="MonthTd" onclick="oDate.showTable(' + _year + ', ' + eval(_month-1) + ');">3</td>';
             this.Thead += '<td align="center" bgcolor="red"  colspan="5" class="SelectTd"><select onchange="oDate.showTable(options[selectedIndex].value, ' + _month + ');">';
             for (var i=1900; i<2101; i++) this.Thead += '<option value="' + i + '" ' + ((_year==i) ? 'selected' : '') + '>' + i + '年</option>';
             this.Thead += '</select><select onchange="oDate.showTable(' + _year + ', options[selectedIndex].value);">';
             for (var i=1; i<13; i++) this.Thead += '<option value="' + i + '" ' + ((_month==i) ? 'selected' : '') + '>' + i + '月</option>';
             this.Thead += '</select></td>';
             this.Thead += '<td align="center" class="MonthTd" onclick="oDate.showTable(' + _year + ', ' + eval(_month+1) + ');">4</td></tr>\n';
             this.Thead += '<tr>';
             for (var i=0; i<this.weekArr.length; i++) this.Thead += '<td align="center" class="WeekTd">' + this.weekArr[i] + '</td>';
             this.Thead += '</tr>\n';
             this.Tbody = '<tr>';
             this.dateArr[1] = (!this.checkYear(_year)) ? 28 : 29 ;
             for (var i=0; i<this.firstPos(_year, _month); i++) this.Tbody += '<td class="BlankTd"></td>';
             for (var i=1; i<=this.dateArr[_month-1]; i++) {
                  if (this.firstPos(_year, _month) == 0) {
                      if (i!=1 && i%7==1) this.Tbody += '</tr>\n<tr>';
                  } else {
                      if ((i+this.firstPos(_year, _month))%7==1) this.Tbody += '</tr>\n<tr>';
                  }
                  if (!this.today(_year, _month, i)) {
                      this.Tbody += '<td align="center" class="out" onmouseover="className=\'over\';" onmouseout="className=\'out\';" onmousedown="className=\'down\';" onclick="oDate.showDateStr(' + _year + ', ' + _month + ', ' + i + ', \'' + this.weekArr[new Date(_year, _month-1, i).getDay()] + '\');">' + i + '</td>';
                  } else {
                     this.Tbody += '<td align="center" class="Today" onclick="oDate.showDateStr(' + _year + ', ' + _month + ', ' + i + ', \'' + this.weekArr[new Date(_year, _month-1, i).getDay()] + '\');">' + i + '</td>'; 
                  } 
             }
             for (var i=0; i<6-this.lastPos(_year, _month); i++) this.Tbody += '<td class="BlankTd"></td>';
             this.Tbody += '</tr>\n'; 
             this.TFoot = '</table>\n';
             this.Table = this.Thead + this.Tbody + this.TFoot;
             return this.Table;
         } else {
             return 'Arguments Error!';
             }    
 
         }

         this.firstPos = function(_year, _month) {
              return new Date(_year, _month-1, 1).getDay();
         }

         this.lastPos = function(_year, _month) {
              return new Date(_year, _month-1, this.dateArr[_month-1]).getDay();
         }

         this.checkYear = function(_year) {
              return ((_year % 4 == 0) && (_year % 100 != 0)) || (_year % 400 == 0);
         }

         this.today = function(_year, _month, _date) {
              return (new Date().getFullYear() == _year) && (new Date().getMonth() == _month-1) && (new Date().getDate() == _date);
         }
 
         this.checkArgs = function(_year, _month) {
              if (_year<1900 || _year>2100) return false;
              if (_month<0 || _month>13) return false;
              return (!isNaN(_year) && !isNaN(_month));           
         }

         this.checkDate = function(_year, _month) {
              if (_month<1) { 
                  _year --;
                  _month = 12;
              }
              if (_month>12) { 
                  _year ++;
                  _month = 1;
              }
              return _year + '-' + _month;
     }        

     this.showDateStr = function(_year, _month, _date, _week) {
        // window.alert(_year + '年' + _month + '月' + _date + '日 星期' + _week); 
    	// dateVal=_year + '年' + _month + '月' + _date + '日 星期' + _week;
    	 dateVal=_year + '-' + _month + '-' + _date  ;
    	 document.getElementById("calendar").value=dateVal;
    	 document.getElementById("calendar").style.width='150px';
    	 //隐藏那个div
    	 document.getElementById("oContainer").style.display="none";
     }
     
}
function showCalendar(){
     oDate = new DateClass(self.oContainer);
     oDate.showTable(new Date().getFullYear(), new Date().getMonth()+1); 
	
}
    
//********************************日历函数End
//********************************学校获取Start  

/* @Function:获取学校信息 虽然这个函数和getProvince的输入参数是一样的但是最终要的目地不一样，所以注意下 ，传输给下一条的data的名字也不一样
 * collegeCountryIdForCollege这么命名是因为区别于getProvince()的collegeCountryId。
 * @varType:访问下一链接的方式
 * @varUrl:下一链接的地址
 * @varData:给下一链接的数据
 * @varDataType:下一链接返回的数据格式
 * @outPut:返回得到的数据
 */
function getDefaultColleges(varType,varUrl,varData,varDataType){
	var	res;	 
	///*
	$.ajax({
			type:varType,
			 url:varUrl,
			data:{collegeCountryIdForDefaultCollege:varData},
			async:false,
		dataType:varDataType,
	  beforeSend:function(){},
	 	 success:function(data){	
	 		if(data.length==0){
				//html+='<option value="-1">--省份--</option>';
	 			alert("数据长度为0");
		 	}else{
		 		res=data;
			}
		 },			
		 error:function(data,state){
			 alert(state);			 
     }
	});
	return res;
}
/* @Function:获取学校信息 虽然这个函数和getProvince的输入参数是一样的但是最终要的目地不一样，所以注意下 ，传输给下一条的data的名字也不一样
 * collegeCountryIdForCollege这么命名是因为区别于getProvince()的collegeCountryId。
 * @varType:访问下一链接的方式
 * @varUrl:下一链接的地址
 * @varData:给下一链接的数据
 * @varDataType:下一链接返回的数据格式
 * @outPut:返回得到的数据
 */
function getColleges(varType,varUrl,varData1,varData2,varDataType){
	var	res;	 
	///*
	$.ajax({
			type:varType,
			 url:varUrl,
			data:{collegeCountryIdForCollege:varData1,proId:varData2},
			async:false,
		dataType:varDataType,
	  beforeSend:function(){},
	 	 success:function(data){	
	 		if(data.length==0){
				//html+='<option value="-1">--省份--</option>';
	 			alert("数据长度为0");
		 	}else{
		 		res=data;
			}
		 },			
		 error:function(data,state){
			 alert(state);			 
     }
	});
	return res;
}
	//***************************************为了简化代码而做的四个函数
		function provinceHtmlFunc(data){
			var provinceHtml='';
			if(data.length==0){
				provinceHtml+='<option value="-1">--省份--</option>';
		 	}else{
		 		//把得到的数据进行写到界面里面去
		 		for(var i=0;i<data.length;i++){
		 			provinceHtml+='<option value='+data[i]['provinceId']+'>'+data[i]['province']+'</option>';
				}
			}
			return provinceHtml;
		}
		function cityHtmlFunc(data){
			var cityHtml='';
			if(data.length==0){
				cityHtml+='<option value="-1">--城市--</option>';
		 	}else{
		 		//把得到的数据进行写到界面里面去
		 		for(var i=0;i<data.length;i++){
		 			cityHtml+='<option value='+data[i]['cityId']+'>'+data[i]['city']+'</option>';
				}
			}
			return cityHtml;
		}
		function provinceHtmlForColleges(data){
			var provinceHtml='';
			if(data.length==0){
				//provinceHtml+='<option value="-1">--省份--</option>';
				alert("数据长度为0");
		 	}else{
		 		for(var i=0;i<data.length;i++){
		 			provinceHtml+='<li><a href="#" id='+data[i]['provinceId']+'>'+data[i]['province']+'|</a></li>';
				}
			}
			return provinceHtml;
		}
		function collegesHtmlFunc(data){
			var collegesHtml='';
			if(data.length==0){
				alert("数据长度为0");
		 	}else{
		 		//把得到的数据进行写到界面里面去    Notice!!!id='+data[i]['college']  为了容易获取学校的值这里我放的不是data[i]['id']
		 		for(var i=0;i<data.length;i++){
		 			collegesHtml+='<li><a href="#" id='+data[i]['college']+'>'+data[i]['college']+'|</a></li>';
				}
			}
			return collegesHtml;
		}

//********************************学校选取End

//********************************js验证输入的东西是否符合规则


//验证码进行ajax()处理写一个函数 发送函数 
//因为$_SESSION['code'];在register.php页面没有刷新的缘故
function _check_code(varType,varUrl,varData,varDataType){
	var res;
	//alert("good");
	//邮箱验证      || varData.length==0 为什么不要也可以 不会ajax提交去处理的么
	if (varData.length>4 ) {
		alert('输入的验证码的长度不正确,请重新输入');
		
		return false;
	}else{
		$.ajax({
			type:varType,
			url:varUrl,
			data:{code:varData},
			async:false,//***********注意这里是同步 用异步的话return html是有问题的
			dataType:varDataType,
			beforeSend:function(){},
			success:function(data){	
					 res=data['match'];	
			},			
		 	error:function(data,state){
				alert(state);			 
			}
		});
		return res;
	}
	
}


//等在网页加载完毕再执行
window.onload = function () {

	//code();//这个是什么意思? 在code.js中有定义，但是对于我来讲，好像没有什么意义的。
	var sp=document.getElementById('codeflag');
	//表单验证[0]表示的是第一个表单
	var fm = document.getElementsByTagName('form')[0];
	if (fm == undefined) return;
	fm.onsubmit = function () {
		//能用客户端验证的，尽量用客户端
		//邮箱验证我放到上面和$.ajax()一起，并且放在它的上面先进行验证
		//邮箱验证(之前没有写 没有考虑到要是别人不填直接就点注册了呢)

		//对上传的头像文件的大小，类型进行判定
		// 如果头像有值那么就进行判断
		if (fm.upLoadFile.value!='') {
			//不能超过2M  类型只能够是'image/jpeg','image/pjpeg','image/png','image/x-png','image/gif','image/bmp'		
			if(fm.upLoadFile.files[0].size >2*1024*1024){
					alert('上传的图片不能超过2M');
					fm.upLoadFile.value = ''; //清空
					fm.upLoadFile.focus(); //将焦点以至表单字段
					return false;
				}
			var array=['image/jpeg','image/pjpeg','image/png','image/x-png','image/gif','image/bmp'	];
			if($.inArray(fm.upLoadFile.files[0].type,array)==-1){
				alert('上传图片必须是jpg,jpeg,png,gif,jpeg,x-png中的一种！JS验证');
				fm.upLoadFile.value = ''; //清空
				fm.upLoadFile.focus(); //将焦点以至表单字段
				return false;
			}
		}
//		
		//用户名验证
//		if (fm.username.value.length < 2 || fm.username.value.length > 20) {
//			alert('用户名不得小于2位或者大于20位');
//			fm.username.value = ''; //清空
//			fm.username.focus(); //将焦点以至表单字段
//			return false;
//		}
//		if (/[<>\'\"\ ]/.test(fm.username.value)) {
//			alert('用户名不得包含非法字符');
//			fm.username.value = ''; //清空
//			fm.username.focus(); //将焦点以至表单字段
//			return false;
//		}
		
		//昵称验证
		if(fm.nickname.value !=''){
			if (fm.nickname.value.length < 1 || fm.nickname.value.length > 10) {
				alert('昵称不得小于1位或者大于10位');
				fm.nickname.value = ''; //清空
				fm.nickname.focus(); //将焦点以至表单字段
				return false;
			}
			if (/[<>\'\"\ ]/.test(fm.nickname.value)) {
				alert('用户名不得包含非法字符');
				fm.nickname.value = ''; //清空
				fm.nickname.focus(); //将焦点以至表单字段
				return false;
			}
		}
		
		
		//密码验证
		if(fm.password.value.length!=0){
			if (fm.password.value.length < 6) {
				alert('密码不得小于6位');
				fm.password.value = ''; //清空
				fm.password.focus(); //将焦点以至表单字段
				return false;
			}
			if (fm.password.value != fm.notpassword.value) {
				alert('密码和密码确认必须一致');
				fm.notpassword.value = ''; //清空
				fm.notpassword.focus(); //将焦点以至表单字段
				return false;
			}
		}
	
		//公历生日 不能为空
		if(fm.calendar.value==''){
			alert("请选择公历生日");
			fm.calendar.value = ''; //清空
			fm.calendar.focus(); //将焦点以至表单字段
			return false;
		}
		//学校验证	不能为-1  默认的设置是为-1	 不是的默认是空	
		if(fm.college.value==''){
			alert("请选择学校信息");
			fm.college.value = ''; //清空
			fm.college.focus(); //将焦点以至表单字段
			return false;
		}
		
		
		//QQ号码验证
		if (fm.qq.value != '') {
			if (!/^[1-9]{1}[\d]{4,9}$/.test(fm.qq.value)) {
				alert('QQ号码不正确');
				fm.qq.value = ''; //清空
				fm.qq.focus(); //将焦点以至表单字段
				return false;
			}
		}
		
		//网址验证
		if (fm.url.value != '') {
			if (!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)) {
				alert('网址不合法');
				fm.url.value = ''; //清空
				fm.url.focus(); //将焦点以至表单字段
				return false;
			}
		}
		//验证码验证  长度和是否正确都进行验证，这样的话可以提高效率 
		//考虑到js中不可以用到php的办法，我可以做一个判定的标志，看span中的codeflag值是不是我设定的html
		//就可以解决这个问题了  nice!!!!
		if (fm.code.value.length != 4) {
			alert('验证码必须是4位');
			fm.code.value = ''; //清空
			fm.code.focus(); //将焦点以至表单字段
			return false;
		}
		
		if (sp.innerText == "验证码不正确") {
			alert('请输入正确的验证码');
			//fm.code.value = ''; //清空
			fm.code.focus(); //将焦点以至表单字段
			return false;
		}
		//有一种基本不可能的情况，但是为了保证严谨性，添加如下的代码，情况是验证码不正确 但是这个时候的span中没有东西的情况 
		if(fm.code.value.length == 4&&sp.innerText ==''){
			alert('请输入正确的验证码');
			fm.code.focus(); //将焦点以至表单字段
			return false;
		}
		
		//自我描述验证
		if(fm.selfDescription.value ==''){
			alert('请填写自我描述，这样更容易找到TA ^_^');
			fm.selfDescription.value = ''; //清空
			fm.selfDescription.focus(); //将焦点以至表单字段
			return false;
		}
		
		return true;
	};
};

















































