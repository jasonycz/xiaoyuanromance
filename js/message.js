window.onload = function () {
	var all = document.getElementById('all');
	//第一个表单
	var fm = document.getElementsByTagName('form')[0];
	all.onclick = function () {
		//form.elements获取表单内的所有表单，目前一共16个
		//checked表示已选
		for (var i=0;i<fm.elements.length;i++) {
			if (fm.elements[i].name!='chkall') {
				fm.elements[i].checked = fm.chkall.checked;
			}
		}
	};
	fm.onsubmit = function () {
		if (confirm('确定要删除吗？')) {
			return true;
		} 
		return false;
	};
};