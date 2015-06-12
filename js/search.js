//******************************myInformation.php中用到的函数Start
/*function $(id){//为什么这个函数在这里不起作用了 难道说是因为是.js文件？
  	return document.getElementById(id);
  }*/
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
		 		//添加日期  2015-01-15 11:46  这是针对搜索进行的添加
		 		provinceHtml+='<option value="-1">--不限--</option>';
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
		 		//添加日期  2015-01-15 11:46  这是针对搜索进行的添加
		 		cityHtml+='<option value="-1">--不限--</option>';
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

//等在网页加载完毕再执行
window.onload = function () {

	//表单验证[0]表示的是第一个表单
	var fm = document.getElementsByTagName('form')[0];
	if (fm == undefined) return;
	fm.onsubmit = function () {
		//能用客户端验证的，尽量用客户端

		//判定年龄是否由小到大 不是就处理为由小到大
		//这样其实是不合理的，因为考虑到后面还要进行处理，所以前面要是不是由小到大就提醒用户。
//		if(fm.ageStart.value>fm.ageEnd.value){
//			var temp=fm.ageStart.value;
//			fm.ageStart.value=fm.ageEnd.value;
//			fm.ageEnd.value=temp;
//			//alert("年龄范围应该由小到大");
//			//alert(fm.ageStart.value+' '+fm.ageEnd.value);
//			//处理一下 默认以前的是小的就好了
//			//return false;
//			
//		}
		//判定身高是否由小到大 不是就处理为由小到大
		if(fm.heightStart.value>fm.heightEnd.value){
			var temp=fm.heightStart.value;
			fm.heightStart.value=fm.heightEnd.value;
			fm.heightEnd.value=temp;
			//alert("身高范围应该由小到大");
	
			
		}
		//判定体重是否由小到大 不是就处理为由小到大
		if(fm.weightStart.value>fm.weightEnd.value){
			var temp=fm.weightStart.value;
			fm.weightStart.value=fm.weightEnd.value;
			fm.weightEnd.value=temp;
			//alert("体重范围应该由小到大");
		
		}
		
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
		//用户名验证
		if (fm.username.value!=''){
			if (fm.username.value.length < 2 || fm.username.value.length > 20) {
				alert('用户名不得小于2位或者大于20位');
				fm.username.value = ''; //清空
				fm.username.focus(); //将焦点以至表单字段
				return false;
			}
			if (/[<>\'\"\ ]/.test(fm.username.value)) {
				alert('用户名不得包含非法字符');
				fm.username.value = ''; //清空
				fm.username.focus(); //将焦点以至表单字段
				return false;
			}
		}
		
		return true;
	};
};




































