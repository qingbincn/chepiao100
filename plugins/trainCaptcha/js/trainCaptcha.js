/*
 * 实现12306验证码
 * 文件名：trainCaptcha.js
 *
 * @author phpbin
 *
 * -----------------------------------------------------------------------------
 *
 * 引用
 * <script type="text/javascript" src="jquery-1.11.3.min.js"></script>
 * <script type="text/javascript" src="jquery.trainCaptcha.js"></script>
 * <link type="text/css" href="css/trainCaptcha.css" rel="stylesheet"/>
 *
 * -----------------------------------------------------------------------------
 *
 * 使用说明：
 * 1. $('#TrainCaptcha').TrainCaptcha();
 * 2. $('#TrainCaptcha').TrainCaptcha({input:"randCode",imgUrl:"captcha.php"});
 * 
 * 验证成功：
 * $('#randCode').data('flag', 'success').click();
 * 验证失败
 * $('#randCode').data('flag', 'failure').click();
 * 
 * -----------------------------------------------------------------------------
 *
 */
(function($) { 
    $.fn.TrainCaptcha = function(options) {
       
	   //  默认扩展
	   var defaults = {
		         input:  'randCode',
		        imgUrl:  'captcha.php',
			   damaUrl:  'dama.php'
	   }; 
	   var opts = $.extend(defaults, options);
		
	   // 实例调用
	   var __this    = $(this);
	   var __input   = $('#'+opts.input);
	   var __leftpos = 0;

	   
	   // 加载图片
	   var loadImg = function() {
		   __this.find('.touclick').show();
		   __this.find('.container').removeClass('failure');
		   __captcha = opts.imgUrl+'&'+Math.random();
	       __this.find('img').hide().attr('src', __captcha).load(function(){
		       $(this).show();
		   });
		   __this.find('.toucblock').remove();
		   collection();
	   }
		
	   // 打码操作
	   var damaImg = function() {
		   if ( opts.damaUrl == '') return;
		   __captcha = opts.damaUrl+'&'+Math.random();
	       $.get(__captcha, function(code){
			   
			   if (code!="") {
			   __codes = code.split('|');
			   $.each(__codes, function(i, v){
			       __posi = v.split(',');
				   __left = parseInt(__posi[0]) - 15;
			       __top  = parseInt(__posi[1]) - 15;
				   __html = '<div class="toucblock" style="top:'+__top+'px;left:'+__left+'px"></div>';
				   $(__html).click(function(e){
				   $(this).remove();
					   collection();
					   e.stopPropagation();
				   }).appendTo(__this.find('.container'));
				   collection()
			   });
			   }
		   });
	   }	
		
	   // 统计数据
	   var collection = function() {
	       input_val = [];
		   __this.find('.toucblock').each(function(){
			   _left = parseInt($(this).css('left')) + 13;
			   _top  = parseInt($(this).css('top')) - 16;
		       input_val.push(_left +','+ _top);
		   });
		   input_val = input_val.join(',');
		   __input.val(input_val);
	   }
		
	   // 初始化
	   var html = '';
	   html += '<div class="container"><img src="'+opts.imgUrl+'"/>';
	   html += '<div class="touclick"></div>';
	   html += '</div>';
	   $(html).appendTo($(this));
	   __this.find('.container').find('img').load(function(){
	       damaImg(); 
	   });
	  
       __this.find('.touclick').click(function(e){
	       loadImg();
		   e.stopPropagation();
	   }).parent().click(function(e){
		    // --------------------------------------------------------------
		    __top  = e.pageY - $(this).offset().top - 14;
			if ( __top < 30) __top = 30;
			if ( __top > 164) __top = 164;
			__left = e.pageX - $(this).offset().left - 14;
			if ( __left < 0) __left = 0;
			if ( __left > 267) __left = 267;
			__html = '<div class="toucblock" style="top:'+__top+'px;left:'+__left+'px"></div>';
			// --------------------------------------------------------------
	       $(__html).click(function(e){
		       $(this).remove();
			   collection();
			   e.stopPropagation();
		   }).appendTo($(this));
		   collection();
	   });
	   
	   // 事件绑定
	   __input.click(function(){
		   __this.find('.toucblock').remove();
		   __this.find('.touclick').hide();
		   __flag = $(this).data('flag');
		   if (__flag == 'success') {
		       __this.find('.container').addClass('success').find('img').hide();
		   } else if (__flag == 'failure') {
		      __this.find('.container').addClass('failure').find('img').hide();
			  collection();
			  setTimeout(loadImg, 2000);
		   } else if (__flag == 'initial') {
			   __this.find('.container').removeClass('success').find('img').hide();
			   loadImg();
		   } else {
		      collection();
		   }
	   });
   }
   
})(jQuery);
