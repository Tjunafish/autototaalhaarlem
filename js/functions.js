$(function(){

	// Function for delaying something
	//////////////////////////////////

	var delay = (function(){
		
     	var timer = 0;
  		return function(callback, ms){
    
			clearTimeout (timer);
		    timer = setTimeout(callback, ms);
	  
	  	};
	  	
	})();
	
	// Clear text on focus
	//////////////////////

	$('.clickclear').live('blur', function(){ 
 
		if(this.value == ''){
		
			$(this).removeClass('mark');
			this.value = this.defaultValue;
			
		}
	 
	});		
	 
	$('.clickclear').live('focus',function(){
	 
		if(this.value == this.defaultValue){
		
			$(this).addClass('mark');
			this.value = '';
			
		}
	 
	});
	
	$('#search_adv select').change(function(){
	
		if($(this).val() == '')
			$(this).prev('.selectbox').removeClass('mark');
		else
			$(this).prev('.selectbox').addClass('mark');
	
	});
	
	// Print function
	/////////////////
	
	$('.print').click(function(e){
	
		e.preventDefault();
		
		window.print();
	
	});
	
	// Remove error on typing
	/////////////////////////
	
	$('input.error, textarea.error').live('keypress',function(){
	
		$(this).removeClass('error');
	
	});
	
	// Search form / control functionality
	//////////////////////////////////////
	
	$('.control').live('click',function(e){
	
		e.preventDefault();
		
		$(this).attr('href',$(this).attr('href').replace('http://www.autoservicehaarlem.nl/',''));
		
		if($(this).attr('rel') == 'view')
			if($(this).attr('href') == 'grid'){
			
				$('#search_adv_submit > p span').html('zoeken');					
				$('#container .left').addClass('grid').removeClass('list');			
			
			}else{
				
				$('#search_adv_submit > p span').html('uitgebreid zoeken');			
				$('#container .left').addClass('list').removeClass('grid');			
			
			}
			
		if($('#wrapper').hasClass('green'))
				var color = '_green';
			else
				var color = '';
				
		if($(this).find('img[rel="sort"]').length){
				
			$('.control img[rel="sort"]').hide();
		
			var $img = $(this).find('img[rel="sort"]');
			var sort = $img.attr('alt');
			
			$img.show();
			
			if(sort == 'desc')
				var rev = 'asc';
			else
				var rev = 'desc';
				
			if($(this).hasClass('active')){
						
				$img.attr('alt',rev).attr('src','img/sort_'+rev+color+'.png');
				$('#controls input[name="sort_dir"]').val(rev);
			
			}else
				$('#controls input[name="sort_dir"]').val(sort);			
		
		}
			
		$(this).siblings('.control').each(function(){
		
			$(this).removeClass('active')
				   .find('img:not([rel="sort"])').attr('src','img/'+$(this).attr('href')+'.png');
					   
  		});
			   
		$(this).addClass('active')
			   .find('img:not([rel="sort"])').attr('src','img/'+$(this).attr('href')+'_active'+color+'.png');
  
  		var prev = $('#controls input[name="prev"]').val();
		var curr = $(this).attr('rel')+$(this).attr('href');
				
		$('#controls input[name="'+$(this).attr('rel')+'"]').val($(this).attr('href'));
		$('#controls input[name="curr"]').val(curr);
		
		refresh_cars();
		
		$('#controls input[name="prev"]').val(curr);
		
		if($(this).attr('rel') == 'page')
			refresh_pages($('#controls input[name="page"]').val());
		else
			refresh_pages(1);
	
	});
		
	$('#search_adv input, #search_ajax').keypress(function(e){
	
/*
		delay(function(){
	      filter_cars();
	    }, 500);
*/
	
	});
	
	$('#search_adv select').change(function(e){
	
/* 		filter_cars(); */
	
	});
	
	$('#search_adv_submit').live('click',function(e){
	
		e.preventDefault();
		filter_cars();			
			
	});			
	
	// Refresh cars after filter
	////////////////////////////	
	
	function filter_cars(){
	
	
		$('#controls input[name="curr"]').val('filter');

		refresh_pages(1);
		fit_text();
		

	
	}
	
	// Refresh cars after control
	/////////////////////////////
			
	function refresh_cars(){
		var params = $('#controls, #search_adv, #search').serialize();
		if(console && console.log) console.log('refresh cars:', params);
	
		$.post('ajax/cars.php?section='+$('input[name="section"]').val(),params,function(data){
		
			$('#cars_holder').html(data);
			fit_text();
			
		
		});
	
	}
	
	// Refresh pagination
	/////////////////////
	
	function refresh_pages(page){
	

	
		$('#controls input[name="page"]').val(page);
		var params = $('#controls, #search_adv, #search').serialize();
		if(console && console.log) console.log('refresh pages:', params);
	
		$.post('ajax/pages.php?section='+$('input[name="section"]').val(),params,function(data){
		
/* 			alert(data); */
			
			data = $.parseJSON(data);

			$('.page_holder').html(data.html);
			
		});
		
		
		params = $('#controls, #search_adv, #search').serialize();
		if(console && console.log) console.log('refresh pages pt2:', params);
		$.post('ajax/cars.php?section='+$('input[name="section"]').val(),params,function(data){
		

		
 			//alert(data); 
			
			//data = $.parseJSON(data); 

			$('#cars_holder').html(data);
			
		});	
		
		fit_text();
		
/*
<div id="cars_holder">
<?
require 'ajax/cars.php';
?>
</div>
*/



	
	}
	
	// Fit text within a certain amount of width
	////////////////////////////////////////////
	
	function fit_text(){
	
		$('.textfit').each(function(){
					
			while($(this).width() > $(this).attr('rel')){
			
				var dFontsize = parseFloat($(this).css("font-size"), 10);
				$(this).css("font-size", dFontsize - 1);
				
			}
			
			$(this).removeClass('textfit');
			
		});	
	
	}
	
	fit_text();
	
	// Ajax call for refreshing model list on selecting brand
	/////////////////////////////////////////////////////////
	
	$(window).ready(function(){

		$('#select_brand').change(function(){
		
			
		
			$.post('ajax/models.php',{brand: $(this).val()},function(data){
				
				if(data != 'error')
					$('#select_model').html(data);	
					
				$("#select_model").sb("refresh");
			
			});
		
		});
	});
	
	// Scroll to top links
	//////////////////////
	
	$('.totop').live('click',function(e){
	
		e.preventDefault();
		
		var target = $('#container .options').eq(0);
		var offset = target.offset().top;
		
		if($('html').scrollTop() > offset)
			$('html').animate({scrollTop: offset}, 300);
	
	});
	
	// Ajax forms
	/////////////
	
	$('form.ajax').live('submit',function(e){
	
		e.preventDefault();
		
		$(this).children('.error').removeClass('error');
		
		var $this = $(this);
		
		$.post($(this).attr('action'),$(this).serialize(),function(data){
		
			data = $.parseJSON(data);
			
			if(data.status == 'success'){
			
				if(data.returnText != '' && data.special != 'tax')
					$this.html(data.returnText);
					
				if(data.trigger && data.triggerType)
					$(data.trigger).trigger(data.triggerType);
					
				if(data.special == 'tax'){
				
					$this.parents('.bigblock').find('p').html(data.returnText);
					$this.parents('.bigblock').find('.label').remove();
/* 				    alert(data.returnText); */
                
				}
				
			}else			
				for(var key in data)
		  			if(data.hasOwnProperty(key) && key != 'status')			  
	  					$this.children('[name="'+key+'"]').addClass('error');
	  					
		});
		
	
	});
	
	// Frontpage car rotation
	/////////////////////////
	
	if($('#cars_rotate').length > 0){
        return false;
	
		var running  = false;
	
		var func_rotate = function rotate_cars(){
		
			running = true;
		
			$.post('ajax/cars.php',{type: 'rotator', start: $('#cars_rotate').data('start')},function(data){
			
				data = $.parseJSON(data);
				
				$('#cars_rotate').data('start',data.start);
				$('#cars_rotate .clear').before(data.html);
				
				$('#cars_rotate').animate({left: '-214px'},'fast',function(){
				
					$('#cars_rotate').css('left','26px');
					$('#cars_rotate .car').eq(0).remove();
				
				});
				
				fit_text();
			
			});
		
		}
		
		$(function(){
				
			if(document.hasFocus)
				var interval = setInterval(func_rotate,4000);
			else
				var interval;
		
			$(window).focus(function(){
			
				clearInterval(interval);
				
				if(!running)
					interval = setInterval(func_rotate,4000);
					
			}).blur(function(){
			
				clearInterval(interval);
				running = false;
			
			});
		
		});
	
	}
	
	// Tell a friend / Taxation forms
	/////////////////////////////////
	
	$('span.tell_friend a').click(function(e){
	
		e.preventDefault();
	
		$(this).toggleClass('active');
		$('form.tell_friend').toggle(function(){;
			
			if($('form.tell_friend').is(':visible'))
				$('form.tell_friend').one("clickoutside",function(){
			
					$(this).toggleClass('active');
					$(this).hide();
				
				});
			else
				$('form.tell_friend').unbind('clickoutside');
		
		});
	
	});
	
	$('.tax_toggle').click(function(e){
	
		e.preventDefault();
		$('form.taxatie').toggle(function(){;
			
			if($('form.taxatie').is(':visible'))
				$('form.taxatie').one("clickoutside",function(){
			
					$(this).hide();
				
				});
			else
				$('form.taxatie').unbind('clickoutside');
		
		});
		
	});
	
	// Fancybox
	///////////
	
	if($('a.fancy').length > 0)
		$("a.fancy").fancybox();
		
	// Favorites interaction
	////////////////////////
	
	function favorites(action,car){
	
		if(typeof car == 'undefined')
			var car = false;
			
		$.post('ajax/favorite.php',{action: action, car: car});
	
	}
	
	$('.toggle_fav').live('click',function(e){
	
		e.preventDefault();
		
		favorites('toggle',$(this).data('car'));
		
		var favimg = $(this).find('img[rel="fav"]');
		
		if(favimg.attr('src') == 'img/icon_fav.jpg')
			favimg.attr('src','img/icon_fav_min.jpg');
		else{
			
			if($(this).attr('rel') == 'fav_page'){
			
				$(this).parents('.car').remove();
				$('#tiptip_holder').hide();
			
			}else
				favimg.attr('src','img/icon_fav.jpg');
		
		}
		
	});
	
	// Selectbox styling
	////////////////////
	
	$('#search_adv select').sb();
	
});

