window.onload = function () {
	var ret = document.getElementById('reply');
	var del = document.getElementById('delete');
	ret.onclick = function () {
		location.href='messageReply.php?action=reply&getterId='+this.name+'&getterName='+this.title;
	};
	del.onclick = function () {
		if (confirm('确定要删除此条短信？')) {
			location.href='messageController.php?action=delete&id='+this.name;
			
		}
	};
};