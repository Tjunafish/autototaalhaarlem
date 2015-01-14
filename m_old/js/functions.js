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
	
	// Remove error on typing
	/////////////////////////
	
	$('input.error, textarea.error').live('keypress',function(){
	
		$(this).removeClass('error');
	
	});
	
	// Search form / control functionality
	//////////////////////////////////////
	
	$('.control').live('click',function(e){
	
		e.preventDefault();
		
		//$('#controls input[name="start_limit"]').val(0);	
		
		
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
		
	});
		
	$('#search_adv input, #search_ajax').keypress(function(e){
	
		delay(function(){
	      filter_cars();
	    }, 500);
	
	});
	
	$('#search_adv select').change(function(e){
	
		filter_cars();
	
	});
	
	$('#search_adv_submit').live('click',function(e){
	
		e.preventDefault();
		filter_cars();			
			
	});			
	
	// Refresh cars after filter
	////////////////////////////	
	
	function filter_cars(){
			
		$('#controls input[name="curr"]').val('filter');
				
		refresh_cars();	
	
	}
	
	// Refresh cars after control
	/////////////////////////////
			
	function refresh_cars(){
	
		$('#controls input[name="start_limit"]').val(0);
	
		$.post('ajax/cars.php?section='+$('input[name="section"]').val(),$('#controls, #search_adv, #search').serialize(),function(data){
		
			$('#cars_holder').html(data);
			fit_text();
		
		});
	
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
	
	$('#select_brand').change(function(){
	
		$.post('ajax/models.php',{brand: $(this).val()},function(data){
			
			if(data != 'error')
				$('#select_model').html(data);	
				
			//$("#select_model").sb("refresh");
		
		});
	
	});
	
	// Ajax forms
	/////////////
	
	$('form.ajax').live('submit',function(e){
	
		e.preventDefault();
		
		console.log('form.ajax is aangeroepen');
		
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
				    alert(data.returnText);
                
				}
				
			}else			
				for(var key in data)
		  			if(data.hasOwnProperty(key) && key != 'status')			  
	  					$this.children('[name="'+key+'"]').addClass('error');
	  					
		});
	
	});
	
	// detail_toggle
	////////////////
	
	$('.detail_button').live('click', function(e){
		
		e.preventDefault();
		var rel=$(this).attr('rel');
		$(this).children('img').toggle();
		$('.specification[rel='+rel+']').slideToggle();
	
	});
	
	// Ajax load more
	/////////////////
	var ajax_call_busy = false;
	
	$(window).bind('scroll', function(){
		
		if (ajax_call_busy == false) {
		
			var height = $(document).height();
			var scrollTop = $(window).scrollTop()+$(window).height();
			
			if (height * .8 < scrollTop) {
	
				ajax_call_busy = true;
				
				var current_limit = parseFloat($('#controls input[name="start_limit"]').val());
				var new_limit = current_limit + 10;
				$('#controls input[name="start_limit"]').val(new_limit);
				
				$('.ajax_container[rel='+new_limit+']').html('<div class="ajax_loader"></div>');
				
				
				$.post('ajax/cars.php?section='+$('input[name="section"]').val(),$('#controls, #search_adv, #search').serialize(),function(data){
					
					$('.ajax_container[rel='+new_limit+']').html(data);
					fit_text();
					ajax_call_busy = false;
			
				});
				
			
			}
		
		}

	});
	
	// Selectbox styling
	////////////////////
	
	
	// Image Scroller
	/////////////////
	$('a[rel=img_scr]').live('click', function(e){
	
		e.preventDefault();
		
		var img_tot 	= $('input[name=img_tot]').val();
		var img_pos 	= $('input[name=img_pos]').val();
		var img_pos_m 	= (img_pos*1)-1;
		var img_pos_p 	= (img_pos*1)+1;
		var img_pos_r 	= (img_pos*1)+3;
		var img_pos_l 	= (img_pos*1)-1;
		var img_pos_lh 	= (img_pos*1)+2;
	
		if (img_tot > 3) {
		
			if ( (img_tot-img_pos) > 3 && $(this).hasClass('slid_right') ) {
			
				$('a[rel='+img_pos+']').hide();
				$('a[rel='+img_pos_r+']').show();
				$('input[name=img_pos]').val(img_pos_p);
			
			} else if (img_pos > 1 && $(this).hasClass('slid_left') ) {
			
				$('a[rel='+img_pos_lh+']').hide();
				$('a[rel='+img_pos_l+']').show();
				$('input[name=img_pos]').val(img_pos_m);
			
			}
			
		}
		
		img_tot 	= $('input[name=img_tot]').val();
		img_pos 	= $('input[name=img_pos]').val();
			
		if ((img_tot-img_pos) <= 3) {
			$('.slid_right').children('img[rel=i]').show();
			$('.slid_right').children('img[rel=a]').hide();
		} else {
			$('.slid_right').children('img[rel=i]').hide();
			$('.slid_right').children('img[rel=a]').show();
		}
		if (img_pos == 1) {
			$('.slid_left').children('img[rel=i]').show();
			$('.slid_left').children('img[rel=a]').hide();
		} else {
			$('.slid_left').children('img[rel=i]').hide();
			$('.slid_left').children('img[rel=a]').show();
		}
		
	});
    
    $('.img_small').click(function(e){
       
       e.preventDefault();
           
       $('#full_link').attr('href',$(this).attr('href'));
       $('#full_img').attr('src',$(this).attr('href'));
        
    });
	
	
	//$('#search_adv select').sb();
	
});