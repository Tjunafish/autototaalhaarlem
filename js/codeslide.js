// -------------------------------------
// codeslide.js
// -------------------------------------
// Lightweight and simple content slider
//
// Created by Kasper Mol
// For CodeCreators ©2011
// -------------------------------------
/*
// TODO

- Add support for automatic pager detection
- "Instructions" part has a lot of redundant stuff that could be done with code

// Instructions:
// there needs to be a main div containing the slider ul with at least these styles:

div#slidewrapper {
	
	width:  	[sliderwidth];
	height: 	[sliderheight];
	overflow:	hidden;	
}

// slider needs to be an ul with at least these styles:

ul#slider {
	
	display: 	block; 
	list-style: none; 
	width: 		9999px; 
	padding:	0; 	
	
}

// sliderli needs to be a li within the slider list with at least these styles:

ul#slider li {
	
	float: 		left; (or right) 
	width:  	[sliderwidth]; 
	height: 	[sliderheight]; 
	position: 	relative;	

}
*/
(function($){  
 
	$.fn.codeslide = function(options) {  
 
		var defaults = { 
 
			speed:			500,
			leftarrow:		false,
			rightarrow:		false, 
			type:			'fade',
			autoplay:		true,
			startpage:		1,
			buttonholder:	false,
			buttonclass: 	'',
			buttonactive: 	''
 
		};
 
		var current,
			page,
			timerspeed,
			interval,
			slider   	= $(this),
			sliderli 	= $('li',slider),
			options  	= $.extend(defaults, options),
			busy	 	= false,
			sliderlen 	= $(sliderli).size(),
			sliderwidth = $(sliderli).width();
 
		this.each(function(){	
		
			if($(sliderli).length < 2)
				return false;
 
			if(interval != false)
				clearInterval(interval);	
 
			$(sliderli).show();
 
		    if(options.type == 'slide'){ // Code for sliding
 
		    	if(options.leftarrow)
		    		$(options.leftarrow).click(function(){ 	
 
						slideTo(page-1); 
						reset_autoplay();
 
					});
 
		    	if(options.rightarrow)
		    		$(options.rightarrow).click(function(){	
 
						slideTo(page+1); 
						reset_autoplay();
 
					});
 
		    	if(options.startpage > 0 && options.startpage <= sliderlen){
 
		    		if(options.startpage == 'last')
		    			page = sliderlen;
	    			else
	    				page = options.startpage
 
		    	}else
		    		page = 1;
 
		        slideTo(page);
 
		    }else if(options.type == 'fade'){ // Code for fading
 
		        $(sliderli).css({'position':	'absolute',
								 'left':		0});
 
				//Hiding all other slides
				$(sliderli).hide();
				$(sliderli).filter(':eq('+(current)+')').show();
 
		        $(options.leftarrow).click(function(){ 	
 
					fadeTo(current-1); 
					reset_autoplay();
 
				});
 
		        $(options.rightarrow).click(function(){ 
 
					fadeTo(current+1);  
					reset_autoplay();
 
				})
 
		        if(options.startpage > 0 && options.startpage <= sliderlen){
 
		            if(page == 'last')
		                page = sliderlen;  
					else
						page = options.startpage;      
 
		        }else
		            page = 1;
 
		        fadeTo(page);
 
		    }
 
		    // Pagination 
		    /////////////
 
		    if(options.buttonholder != false)	    	  		
		  		if(sliderlen == 1)	  		
		  			$(options.buttonholder).remove();
		  		else{
 
		  			var pagehtml = '';
 
			  		for(var i = 1;i <= sliderlen;i++)	  		
			  			pagehtml += '<a href="#" class="'+options.buttonclass+'" data-page="'+i+'">';
 
		  			$(options.buttonholder).html(pagehtml); 
		    		$(options.buttonholder).find('.'+options.buttonclass+'[data-page="'+current+'"]').addClass(options.buttonactive);	 
					$(options.buttonholder).find('.'+options.buttonclass).click(function(e){
 
						e.preventDefault();
 
						if(current != $(this).data('page')){	
 
							if(options.type == 'fade')
								fadeTo($(this).data('page'));
							else
								slideTo($(this).data('page'));
 
							reset_autoplay();
 
						}
 
					});   	
 
				}		
 
		    // Autoplay
		    ///////////
 
		    if(options.autoplay != false && typeof options.autoplay != undefined){
 
		    	if(options.autoplay == parseInt(options.autoplay))
		   			timerspeed = parseInt(options.autoplay);
				else
					timerspeed = 5000; // Default 5 seconds
 
				reset_autoplay();			
 
		    }
 
		    function reset_autoplay(){
 
				if(options.autoplay){
 
					if(interval != false)
						clearInterval(interval);
 
					interval = setInterval(function() {
 
						if(options.type == 'slide')
							slideTo(page+1);
						else if(options.type == 'fade')
							fadeTo(current+1);
 
					}, timerspeed);
 
				}
 
			}
 
			current = page;
 
			// Code for fading
			//////////////////
 
			function fadeTo(page){
 
			    $(sliderli).stop(true,true);
 
			    if(page < 1)
			        page = sliderlen;
 
			    if(page > sliderlen)
			        page = 1;
 
			    $(sliderli).filter(':eq('+(page-1)+')').show().css('z-index',2);
			    $(sliderli).filter(':eq('+(current-1)+')').css('z-index',3).fadeOut(options.speed,function(){
 
			        $(sliderli).filter(':eq('+(page-1)+')').css('z-index',3);
 
			    });
 
			    current = page;
 
			    if(options.buttonholder != false){
 
					$(options.buttonholder).find('.'+options.buttonclass).removeClass(options.buttonactive);
			    	$(options.buttonholder).find('.'+options.buttonclass+'[data-page="'+current+'"]').addClass(options.buttonactive);
 
			    }
 
			}
 
			// Code for sliding
			///////////////////
 
			function slideTo(page){
 
				$(function(){
 
					if(busy == false){
 
						busy = true;
 
						if(page == 'last')
							page = sliderlen;
 
						if(page != current)	{	
 
							if(page < current) // Slide to the right				
								if(!$(sliderli).css('left'))						  
									$(sliderli).animate({left:'-'+(sliderwidth * sliderlen)+'px'},options.speed,function(){ busy = false; });					
								else{
 
									var liPos = parseInt($(sliderli).css('left'));
 
									if(liPos < 0)					
										$(sliderli).animate({left:(liPos + sliderwidth) + 'px'},options.speed,function(){ busy = false; });					 
									else					
										$(sliderli).animate({left:'-'+(sliderwidth * (sliderlen-1))+'px'},options.speed,function(){ busy = false; });
 
								}				
							else // Slide to the left			
								if(!$(sliderli).css('left'))						  
									$(sliderli).animate({left:'-'+sliderwidth+'px'},options.speed,function(){ busy = false; });					
								else{
 
									var liPos = parseInt($(sliderli).css('left'));
 
									if(liPos > -(sliderwidth*(sliderlen-1)))					
										$(sliderli).animate({left:(liPos - sliderwidth) + 'px'},options.speed,function(){ busy = false; });					 
									else					
										$(sliderli).animate({left:0},options.speed,function(){ busy = false; });
 
								}
 
						}else
							busy = false;
 
					}
 
					if(options.buttonholder != false){
 
						$(options.buttonholder).find('.'+options.buttonclass).removeClass(options.buttonactive);
				    	$(options.buttonholder).find('.'+options.buttonclass+'[data-page="'+page+'"]').addClass(options.buttonactive);
 
				    }
 
				});
 
			}
 
 		})
 
 	};
 
 ;
 
})(jQuery);