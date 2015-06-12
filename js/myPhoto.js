jQuery(document).ready(function($){
	
	/************** Gallery Hover Effect *********************/
	//alert('kkk');
	$(".overlay").hide();
	
	$('.photoOuterMostDiv').hover(
	  function() {
	    $(this).find('.overlay').addClass('animated fadeIn').show();
	  },
	  function() {
	    $(this).find('.overlay').removeClass('animated fadeIn').hide();
	  }
	);

	/************** LightBox *********************/
	$(function(){
		$('[data-rel="lightbox"]').lightbox();//$('[data-rel="lightbox"]')是属性选择器 当中的某个属性data-rel="lightbox"
	});
});

/************** Pagination 留作分页处理*********************/