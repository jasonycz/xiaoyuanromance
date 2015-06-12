
/**
 * 监听有没有点击超链接
 */
$(function() {	  	
				  $('a[href*=#]:not([href=#])').click(function() {
//				  	alert(location.pathname);
//				  	alert(location.pathname.replace(/^\//,''));
//				  	alert(location.hostname );
//				  	alert(this.hash);
					//要清空http://localhost/XiaoYuanRomance/xiaoyuan/index.php?action=recentActivity#price1
					//要清除掉action=recentActivity
				    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {			
				      var target = $(this.hash);
					//不知道下面这几句有什么意义
				    // target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				    // alert('[name=' + this.hash.slice(0) +']');
				    //target=$('[name=#]');
				    //  alert('[name=' + this.hash.slice(1) +']');
				    //   alert(target[0]);
//				    alert(target.length);
				      if (target.length) {
				        $('html,body').animate({
				          scrollTop: target.offset().top-60  //90px是header的大约高度
				        }, 600);
				        return false;
				      }
				    }
				  });
});
				
				
/*还不知道有什么作用,所以先屏蔽 要是最后也没有什么影响就不要了吧
 * $(window).load(function(){
				$('div.description').each(function(){
					$(this).css('display', 'block');
				});
				
				$('div.content-top-grid').hover(function(){
					$(this).children('.description').stop().fadeTo(500, 1);
					
				},function(){
					$(this).children('.description').stop().fadeTo(500, 0);
				});
				
			});
*/	
	
/**
 * _href(_hash) 函数的功能是为了实现其它页面跳转到index.php页面后再次进行相当于相应的(如个人，推荐...)本页面跳转
 * _hash string 从其他的页面跳转是带过的查询参数
 */	
function _href(_hash){	  	
	  $_target = $("#"+_hash);
      if ($_target.length) {
        $('html,body').animate({
          scrollTop: $_target.offset().top-60  //90px是header的大约高度
        }, 600);
        return false;
     }   
}
		
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			