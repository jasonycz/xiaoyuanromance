/*!
 * jquery.lightbox.js
 * https://github.com/duncanmcdougall/Responsive-Lightbox
 * Copyright 2013 Duncan McDougall and other contributors; @license Creative Commons Attribution 2.5
 *
 * Options: 
 * margin - int - default 50. Minimum margin around the image
 * nav - bool - default true. enable navigation
 * blur - bool - default true. Blur other content when open using css filter
 * minSize - int - default 0. Min window width or height to open lightbox. Below threshold will open image in a new tab.
 *
 */
(function ($) {
    'use strict';
    $.fn.lightbox = function (options) {//lightbox函数里面有opt plugin 变量 ;plugin 变量里面又有一些函数
        var opts = {
            margin: 50,
            nav: true,
            blur: true,
            minSize: 0,
            direction:"toRight",//用来表示图片出来的方向
        };
        var plugin = {
            items: [],
            lightbox: null,
            image: null,
            current: null,
            locked: false,
            caption: null,
            descript: 'No descript',
            init: function (items) {
                plugin.items = items;//plugin.items是DOM对象，并且plugin.init(this)中的this就是$('[data-rel="lightbox"]')
                //
				plugin.selector = "lightbox-"+Math.random().toString().replace('.','');

                if (!plugin.lightbox) {
                    $('body').append(
                      '<div id="lightbox" style="display:none;">'+
                      '<a href="#" class="lightbox-close lightbox-button"></a>' +
                      '<div class="lightbox-nav">'+
                      '<a href="#" class="lightbox-previous lightbox-button"></a>' +
                      '<a href="#" class="lightbox-next lightbox-button"></a>' +
                      '</div>' +
                      '<div href="#" class="lightbox-caption"><p></p></div>' +
                      '</div>'
                    );

                    plugin.lightbox = $("#lightbox");
                    plugin.caption = $('.lightbox-caption', plugin.lightbox);
                }
                if (plugin.items.length > 1 && opts.nav) {
                    $('.lightbox-nav', plugin.lightbox).show("slow");
                } else {
                    $('.lightbox-nav', plugin.lightbox).hide();
                }

                plugin.bindEvents();

            },

            loadImage: function () {
                if(opts.blur) {
                    $("body").addClass("blurred");
                }
                $("img", plugin.lightbox).remove();
                //加载lightbox并且加载loadint图标
                plugin.lightbox.fadeIn('fast').append('<span class="lightbox-loading"></span>');

                var img = $('<img src="' + $(plugin.current).attr('href') + '" draggable="false">');
                //alert($(plugin.current).attr('href'));
                $(img).load(function () {
                    $('.lightbox-loading').remove();
                    plugin.lightbox.append(img);
                    plugin.image = $("img", plugin.lightbox).hide();
                    ///alert('kkk');
                    plugin.resizeImage();
                    plugin.setCaption();
                });
            },

            setCaption: function () {
                var caption = $(plugin.current).data(plugin.descript);
                if(!!caption && caption.length > 0) {
                    plugin.caption.fadeIn();
                    $('p', plugin.caption).text(caption);
                }else{
                    plugin.caption.hide();
                }
            },

            resizeImage: function () {
                var ratio, wHeight, wWidth, iHeight, iWidth;
                //alert($(window).outerWidth(true));
//                 wHeight = $(window).height() - opts.margin;
				//目前看来-60比较合适  2015-01-12 16:50  60是header的高度少一点地  少一点点看起来更好
                wHeight = $(window).height() - opts.margin-60;
                //wWidth =$(window).height() - opts.margin;//$(window).outerWidth(true) 为什么不可以
                wWidth =$(window).outerWidth(true) - opts.margin;
                plugin.image.width('').height('');
                iHeight = plugin.image.height();
                iWidth = plugin.image.width();

                if (iWidth > wWidth) {
                    ratio = wWidth / iWidth;
                    iWidth = wWidth;
                    iHeight = Math.round(iHeight * ratio);
                }
                if (iHeight > wHeight) {
                    ratio = wHeight / iHeight;
                    iHeight = wHeight;
                    iWidth = Math.round(iWidth * ratio);
                }
                
                //添加开始  2014-12-09 21:48
                plugin.image.width(iWidth).height(iHeight);//这个应该就是设置相片的宽度和高度
            	var InitTop=($(window).height()- plugin.image.outerHeight() )/2+'px';
            	var InitLeft=($(window).width()- plugin.image.outerWidth() )/2+'px';
               //添加结束   2014-12-09 21:48
                if(opts.direction=="toRight"){
                	 plugin.image.css({
 						'top':"0%",
 						'left':"-100%"
 					}).show().animate({//0,"easeInOutBack"
						top:InitTop,//
						left:InitLeft,//
					},300,"");//easeInOutBack
                }else if(opts.direction=="toLeft"){
                    plugin.image.css({
						'top': "0%",
						'left': "101%"//用"100%"的时候刚开始出来的时候有点小瑕疵
					}).show().animate({//animate()：应该针对的是display=“block”的。应该是这样的。
									   //如果没有show()出来，animate没有起作用
						top:InitTop,//
						left:InitLeft,//
						//top:'+50px',//是可以的  只不过这么做的话 最终会移动到 50*10000的地方  倒不如直接定义好 应该top是多少
						//left:'+50px',//
					},300,"");//show("slow","easeInOutBack");easeInOutBack
                }
    
                plugin.locked = false;
            },

            getCurrentIndex: function () {
                return $.inArray(plugin.current, plugin.items);
            },

            next: function () {
                if (plugin.locked) {
                    return false;
                }
                plugin.locked = true;
              ///**********************************添加照片消失的动作              
                plugin.image.animate({
                	width:"0",
                	height:"0",
                	left:"100%",
                	top:"50%"               	
                	
                },200,"easeInExpo",function(){
                	opts.direction="toRight";
                    if (plugin.getCurrentIndex() >= plugin.items.length - 1) {
                        $(plugin.items[0]).click();
                    } else {
                        $(plugin.items[plugin.getCurrentIndex() + 1]).click();
                    }
                });
 
                
            },

            previous: function () {
                if (plugin.locked) {
                    return false;
                }
                plugin.locked = true;
                ///**********************************添加照片消失的动作
                plugin.image.animate({
                	width:"0",
                	height:"0",
                	left:"0%",
                	top:"50%"      	               	
                },200,"easeInExpo",function(){
                	opts.direction="toLeft";
                    if (plugin.getCurrentIndex() <= 0) {
                        $(plugin.items[plugin.items.length - 1]).click();
                    } else {
                        $(plugin.items[plugin.getCurrentIndex() - 1]).click();
                    }
                });

            },

            bindEvents: function () {
                $(plugin.items).click(function (e) {
                    if(!$("#lightbox").is(":visible") && ($(window).width() < opts.minSize || $(window).height() < opts.minSize)) {
                        $(this).attr("target", "_blank");
                        return;
                    }
                   /// alert("click");
                    var self = $(this)[0];
                    e.preventDefault();
                    plugin.current = self;
                    var descript=$(plugin.current).attr('descript');
                    /*alert(descript);
                    if(descript==" "){
                    	descript="还没有添加描述哟";
                    }*/
                    $(plugin.current).data(plugin.descript,descript);
                    plugin.loadImage();

                    // Bind Keyboard Shortcuts
                    $(document).on('keydown', function (e) {
                        // Close lightbox with ESC
                        if (e.keyCode === 27) {
                            plugin.close();
                        }
                        // Go to next image pressing the right key
                        if (e.keyCode === 39) {
                            plugin.next();
                        }
                        // Go to previous image pressing the left key
                        if (e.keyCode === 37) {
                            plugin.previous();
                        }
//                        以后可以研究一下  进一步的优化lightbox
//                        if((e.ctrlKey)&&(e.keyCode!=17)){
//                        	alert("组合按键");
//                        }
                    });
                    //Bind Mousewheel
                    $(document).on('mousewheel',function(e){
                        e = e || window.event; 
                        //alert(e);
                        //alert(e.originalEvent.wheelDelta);
                        //alert(e.originalEvent.detail);
                        if (e.originalEvent.wheelDelta) {  //判断浏览器IE，谷歌滑轮事件               
                            if (e.originalEvent.wheelDelta > 0) { //当滑轮向上滚动时  
                                //alert("滑轮向上滚动");
                            	plugin.previous();  
                            }  
                            if (e.originalEvent.wheelDelta <=0) { //当滑轮向下滚动时  
                                //alert("滑轮向下滚动");  
                                plugin.next();
                                
                            }  
                        } else if (e.originalEvent.detail) {  //Firefox滑轮事件  
                            if (e.originalEvent.detail> 0) { //当滑轮向上滚动时  
                                //alert("滑轮向上滚动");  
                            	plugin.previous();
                            }  
                            if (e.originalEvent.detail<=0) { //当滑轮向下滚动时  
                                //alert("滑轮向下滚动");  
                                plugin.next();                              
                            }  
                        } 
                       
                    });
               

                // Add click state on overlay background only
                plugin.lightbox.on('click', function (e) {
                    if (this === e.target) {
                        plugin.close();
                    }
                });

                // Previous click
                $(plugin.lightbox).on('click', '.lightbox-previous', function () {
                	//alert("Previous click");
                    plugin.previous();
                    return false;
                });

                // Next click
                $(plugin.lightbox).on('click', '.lightbox-next', function () {
                	//alert("Next click");
                    plugin.next();
                    return false;
                });

                // Close click
                $(plugin.lightbox).on('click', '.lightbox-close', function () {
                    plugin.close();
                    return false;
                });
 			});
                $(window).resize(function () {
                    if (!plugin.image) {
                        return;
                    }
                    plugin.resizeImage();
                });
            },
		
            close: function () {
                $(document).off('keydown'); // Unbind all key events each time the lightbox is closed
                $(document).off('mousewheel');//添加日期 2015-01-07 11:05
                //这个地方进行修改 让动态效果更加的好
                $(plugin.lightbox).fadeOut('slow');
                $('body').removeClass('blurred');
            }
        };

        $.extend(opts, options);

        plugin.init(this);
    };

})(jQuery);