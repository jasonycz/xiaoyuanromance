//邮件进行ajax()处理写一个函数 发送函数 
//到数据库中进行验证是不是已经被注册过了
function _check_email(varType,varUrl,varData,varDataType){
	var res;
	//alert("good");
	//邮箱验证
	if (!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(varData)) {
		alert('邮件格式不正确,请重新填写');
		return false;
	}else{
		$.ajax({
			type:varType,
			url:varUrl,
			data:{email:varData},
			async:false,//***********注意这里是同步 用异步的话return html是有问题的
			dataType:varDataType,
			beforeSend:function(){},
			success:function(data){	
					 res=data['exist'];	
			},			
		 	error:function(data,state){
				alert(state);			 
			}
		});
		return res;
	}
	
}

//验证码进行ajax()处理写一个函数 发送函数 
//因为$_SESSION['code'];在register.php页面没有刷新的缘故
function _check_code(varType,varUrl,varData,varDataType){
	var res;
	//alert("good");
	//邮箱验证
	if (varData.length>4) {
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

		/*if (!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)) {
			alert('邮件格式不正确');
			fm.email.value = ''; //清空
			fm.email.focus(); //将焦点以至表单字段
			return false;
		}*/
		
		//用户名验证
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
		
		//密码验证
		if (fm.password.value.length < 6) {
			alert('密码不得小于6位');
			fm.password.value = ''; //清空
			fm.password.focus(); //将焦点以至表单字段
			return false;
		}
	
		//验证码验证  长度和是否正确都进行验证，这样的话可以提高效率 
		//考虑到js中不可以用到php的办法，我可以做一个判定的标志，看span中的codeflag值是不是我设定的html
		//就可以解决这个问题了  nice!!!!
		if (fm.code.value.length != 4) {
			alert('验证码必须是4位');
			//fm.code.value = ''; //清空
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

		
		return true;
	};
};


