	
	function centerWindow(height,width) {
		var obj=new Object;
		//alert(screen.width+' '+screen.height);
	    obj.left = ($(window).width() - width) / 2;
	    obj.top = ($(window).height() - height) / 2;
//		window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
		return obj;
	}
