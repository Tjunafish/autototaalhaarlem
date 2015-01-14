var nicEdits 			= new Array();
var combo_active 		= new Array();
var inner_duplicates	= new Array();
var duplicate_counter	= 0;
var combo_counter		= 0;
var alert_timer;
var stickyTopOffset;
var stickyBotOffset;
var isLoading			= false;

$(function(){
	
	combo_counter = $('.combo_field').length+1;
	
	$('#sortable').sortable({
			
		 axis:					"y",
		 containment:			"#content_right",
		 opacity:				0.7,
		 helper:				"clone",
		 placeholder:			"drop_placeholder",
		 forcePlaceholderSize:	true
		 
	});
	
	$('#sortable').disableSelection();
	
	$('#sortable').live("sortstop",function(event, ui){
		
		var count = 0;
		$('#sortable .row').each(function(){
			
			if(count % 2 == 0){
				
				if($(this).hasClass('odd'))
					$(this).removeClass('odd');	

			}else{
				
				if(!$(this).hasClass('odd'))
					$(this).addClass('odd');	

			}
			
			count++;
			$('#custom_order_notice').html('<div id="custom_order_icon"></div><p>Klik hier om de nieuwe volgorde op te slaan</p>');
			$('#custom_order_notice p').animate({color: "#269926"},'slow',function(){ $(this).animate({color: "#000"},'slow');});
			
		});
		
		var result = $('#sortable').sortable('toArray');
		$('#custom_order_notice p').click(function(){ save_order(result); });
		
	});	
	
	// Set focus on first text field
    $("input:text:first").focus();

	$('form').submit(function(){
        
    	processing();
    
	});
	
	$('.field_check').each(function(){
	
	    if(this.checked == true)	
	        $('#'+$(this).attr('name')).css('font-weight','bold');
	
	}).click(function(){
	
		check(this);
		blur();
	
	});
	
	$('input[type=checkbox][name=check_all]').click(function(){
	
		check_all(this);
		blur();
	
	});
	
	$('input[type=file]').each(function(){
	
	    this.disabled = false;
	
	});
	
	$('#check_form_type').val('mod');
        
    $('#content_right').css('min-height',$('#content_left').height());
	
	create_events();
	
});

// Function to load extra values through ajax on scrolling
//////////////////////////////////////////////////////////

function loadvalues(){
	
	if($('#ajax_loader').length < 1)
		return false;
	
	var windowBot = $(window).scrollTop()+$(window).height();
		
	if(windowBot+($(window).height() / 2) > $('#ajax_loader').offset().top){
	
		if(!isLoading){
			
			isLoading = true;
			$.post('ajax/load_values.php',{ page: 			$('input[name=page]').val(),
											start: 			$('#ajax_loader').data('start'),
											search: 		$('input[name=search]').val(),
											search_field:	$('#search_field').val(),
											selector:		$('input[name=selector]').val()
											}, function(data){
				
				$('#check_form').append(data);
				isLoading = false;
				$('#ajax_loader').data('start',$('#ajax_loader').data('start')+50);
				
				if(data == '')
					$('#ajax_loader').remove();
					
				sticky_relocate();
				create_events();
				
			});
		
		}
		
	}
	
}

// Sticky bars
//////////////

$(function(){

	// Create div's to show position
	$('#sticktop').after('<div class="pos_flag top"></div>');
	$('#stickbot').before('<div class="pos_flag bot"></div>');

	sticky_recalc();
  	$(window).scroll(sticky_relocate);
	$(window).resize(sticky_relocate);
	$(window).scroll(loadvalues);

});

// Recalculate sticky bars locations
////////////////////////////////////

function sticky_recalc(relocate){
	
	if(typeof relocate == 'undefined')
		relocate = true;
		
	if($('#sticktop').length > 0)
		stickyTopOffset	= $('.pos_flag.top').offset().top;
		
	if($('#stickbot').length > 0)
		stickyBotOffset	= $('.pos_flag.bot').offset().top+35;
		
	if(relocate)
		sticky_relocate(false);
	
}

// Relocate the sticky bars depending on scroll position
////////////////////////////////////////////////////////

function sticky_relocate(recalc){
	
    if(typeof recalc == 'undefined')
		recalc = true;
    
    if(recalc)
	   sticky_recalc(false);
       
	var windowTop = $(window).scrollTop();
	var windowBot = $(window).scrollTop()+$(window).height();
	
	if($('#sticktop').length > 0)				
		if(windowTop > stickyTopOffset){
		
			$('#sticktop_placeholder').show();
			$('#sticktop').addClass('sticky');
		
		}else{

			$('#sticktop_placeholder').hide();			
			$('#sticktop').removeClass('sticky');
		
		}
	
	if($('#stickbot').length > 0)				
		if(windowBot < stickyBotOffset && windowBot > (stickyTopOffset+73)){

			$('#stickbot_placeholder').show();
			$('#stickbot').addClass('sticky');
		
		}else{
			
			$('#stickbot_placeholder').hide();			
			$('#stickbot').removeClass('sticky');
			
		}

}

// Function to show a cms alertbox
//////////////////////////////////

function cmsalert(title,msg){
	
	clearTimeout(alert_timer);
	alert_timer = setTimeout('close_alert()',5000);

	if(typeof msg == "undefined")
		$('#alert #wrap div').html(title);
	else{
		
		$('#alert h1').html(title);
		$('#alert p') .html(msg);
		
	}
	
	if($('#alert').css('bottom') != 0){
	
		$('#alert').animate({'bottom' : 0, 'opacity' : 0.8},'fast');
		
		$('#alert #exit').unbind()
						 .one('click',function(){
			
			close_alert();
			
		});
	
	}
	
}

// Function to remove cms alertbox
//////////////////////////////////

function close_alert(){

	$('#alert').animate({'bottom' : -77, 'opacity' : 0},'fast');
	clearTimeout(alert_timer);
	
}

// Function for saving new row order
////////////////////////////////////

function save_order(order){
	
	var new_order = new Array();
	
	for(var curr_item in order){
	
		var tmp = order[curr_item].split('_');
		new_order.push(tmp[1]);
		
	}
	
	$.post('ajax/order_update.php',{order: new_order, page:$('#active_page').html()}, function(data){
		
	  $('#custom_order_notice').html('<div id="custom_order_icon"></div>De nieuwe volgorde is succesvol opgeslagen');
	  $('#custom_order_notice').animate({color: "#269926"},'slow',function(){	  
	  																	$(this).animate({color: "#000"},'slow');																		
																   });
	  
	});
	
}

// Function that (re)creates all events
///////////////////////////////////////

$('#check_form .bool_click').live('click',function(){

	bool_click(this,'ajax');

});

$('#form_block .bool_click_form').live('click',function(){

	bool_click(this,'form');

});

$('#form_block .combo_field').live('click',function(){
			
	var target = $(this).attr('target');
	
	var tmp = target.split("-");
	
	if(tmp.length == 3)
		tmp[1] = tmp[1]+"-"+tmp[2];
		
	var num = $(this).parents('.right').find('#combo_field_active-'+tmp[1]).attr('rel');
	
	if(combo_active[num] != target){
		
		$('#form_block #combo_tab_'+target).show();
		$(this).addClass("combo_active");
		
		$('#form_block #combo_tab_'+combo_active[num]).hide();
		$('#form_block #combo_top_'+combo_active[num]).removeClass("combo_active");
		
		var tmp = num.split("-");
		tmp.shift();
		$('#form_block #combo_field_active-'+tmp.join("-")).attr('value',target);
		
		combo_active[num] = target;
		
	}

});

$('.file_upload_button').live('click',function(){
		
	var cur_field = $(this).parents('.files_uploaded').attr('id').split("_");
	cur_field.shift();
	cur_field.shift();

	$.post('ajax/uploader.php?anticache='+new Date().getTime(),{	req: 			$(this).attr('rel'), 
																  	current_field: 	cur_field.join("_"), 
																  	type: 			$(this).attr('fieldtype')},
																	function(data){
		
		$('#full_overlay').fadeIn();					
		$('#notice').html(data).fadeIn();							
		
		$(function(){
			
			scrollToItem('#notice',-50);
		
		});
		
		$(window).bind('beforeunload', function(){
			
			if($('#notice #uploaded_files li').length)
				return 'Als je nu de pagina verlaat zullen de reeds upgeloade bestanden blijven zwerven op de server, klik eerst op annuleren om de bestanden weg te gooien.';
				
		});
		
	});

});

$('.duplicate_inner').live('click',function(){

	duplicate_add(this,this,'inner');

});

$('.duplicate_remove').live('click',function(e){

	e.preventDefault();
	duplicate_remove($(this).attr('info-id'));

});

$('#search_button').live('click',function(){
			
	$('#search_form').submit();
	
});

function create_events(){

	$(function(){
								
		combo_active = new Array();
		
		$('#form_block .combo_field_active').each(function(){

			var tmp 		  = $(this).attr('rel');
			combo_active[tmp] = $(this).attr('value');
			
		});
		
		for(var edit in nicEdits)				
			nicEdits[edit].removeInstance(edit);
		
		$('.rich_field').each(function(){
			
			if($(this).attr('id') != "")
				nicEdits[$(this).attr('id')] = new nicEditor({fullpanel:false,buttonlist:['bold','italic','underline']}).panelInstance($(this).attr('id'));
		
		});
		
		$( ".datepicker" ).datepicker({
			
			changeMonth: 		true,
			changeYear: 		true,
			showOtherMonths:  	true,
			selectOtherMonths:  true,
			dateFormat: 		'd MM yy'
			
		});
		
		$('#sortable').sortable('refresh');
		
		jscolor.bind();
		
	});
	
}

// Function for checking all rows
/////////////////////////////////

function check_all(source){
	
	$('.field_check').each(function(){
		
		if(source.checked == true){
			
			this.checked = true;
			check(this,true);	
			
		}else{
			
			this.checked = false;
			check(this,false);	
			
		}
		
	});
	
}

// Function for checking a row
//////////////////////////////

function check(source){
	
	if(source.checked == true)	
		$('#'+$(source).attr('name')).css('font-weight','bold');
	else
		$('#'+$(source).attr('name')).css('font-weight','normal');

}

// Form button event handlers
/////////////////////////////

$(function(){

	$('.form_button.extra_item').click(function(e){
	
		e.preventDefault();
		
		duplicate_add('#block_hidden','#form_block');
	
	});
	
	$('.form_button.confirm_multdel').click(function(e){
	
		e.preventDefault();
		
		confirmMultDel('#check_form');
	
	});
	
	$('.form_button.check_submit').click(function(e){
	
		e.preventDefault();
		
		$('#check_form').submit();
	
	});

});

// Input checkboxes behaviour
/////////////////////////////

$(function(){

	$('.input_checkbox').change(function(){
	
		var parent = $('input[type=hidden][data-group="'+$(this).data('group')+'"]');
	
		$(parent).val('');
		var count = 1;
	
		$('.input_checkbox[data-group="'+$(this).data('group')+'"]:checked').each(function(){
		
			$(parent).val($(parent).val()+$(this).next('p').html());
			
			if($('.input_checkbox[data-group="'+$(this).data('group')+'"]:checked').length != count)
				$(parent).val($(parent).val()+'||');
				
			count++;
		
		});
	
	});

});

// Function for duplicating a form
//////////////////////////////////

function duplicate_add(source,target,type){

	combo_counter++;
	
	var current_id, 
		real_id, 
		orig_source,
		divider_needed = true;

	if(typeof type == 'undefined')
		type = 'normal';
	
	if(type == 'inner'){
		
		target = source = '#'+$(source).parents('.row').find('.right').attr('id');
		
		if(!inner_duplicates[source])
			inner_duplicates[source] = $(source).html();
	
		$('#workspace').html(inner_duplicates[source]);
		
		orig_source = source;
		source 		= '#workspace';
		
		$('.form_error'			,source).remove();
		$('.combo_error'		,source).removeClass('combo_error');
		$('.error_input'		,source).removeClass('error_input');
		$('textarea'			,source).val('').text('').html('');
		$('.files_uploaded span',source).html('0 bestanden');
		
		if($('.inner_dup',source).length > 0)			
			$('#workspace').html($('.inner_dup:last').html());
		
		$('.duplicate_remove'	,source).parents('.floatright').remove();
		$('.inner_dup_divide'	,source).remove();
		
		inner_duplicates[source] = $('#workspace').html();
	
	}
	
	$('input[id], select[id]',source).each(function(){

		var tmp = $(this).attr('id').split("%%");
		$(this).attr('id',tmp[1]);
		
	});	
	
	$(".file_browse_link, .file_browser, .old_file_input,.form_block input:file,.form_block .row .right,.combo_field,.combo_tab,.combo_field_active,.rich_field,.datepicker,.duplicate_inner,.files_uploaded,.upload_data,.color",source).each(
		function(){
				
			var atts = new Array();
			
			atts.push("id");
			atts.push("target");
			atts.push("value");
			atts.push("rel");
			
			for(var att in atts)				
				if($(this).attr(atts[att])){
			
					current_id 	= $(this).attr(atts[att]);
					var tmp 	= current_id.split("-");
					
					if(tmp.length == 3 && type == "inner"){
										
						end = tmp.pop();
						
						if($(this).hasClass('combo_field') || $(this).hasClass('combo_tab') || $(this).hasClass('combo_field_active')){
							
							real_id = combo_counter;
							tmp.pop();
							
						}else
							real_id = parseInt(tmp.pop())+1;
						
						tmp.push(real_id);
						tmp.push(end);
						
					}else{
						
						if($(this).hasClass('combo_field') || $(this).hasClass('combo_tab') || $(this).hasClass('combo_field_active')){
							
							real_id = combo_counter;
							tmp.pop();
							
						}else
							real_id = parseInt(tmp.pop())+1;	
							
						if(real_id == 1)
							real_id = 2;
							
						tmp.push(real_id);
						
					}
					
					$(this).attr(atts[att],tmp.join("-"));			
				
				}
			
		}		
	);
	
	$(":input",source).each(
		function(){
			
			current_id = $(this).attr('name').split("-");
			
			if(type == 'inner'){
				
				var end = current_id.pop();
				real_id = parseInt(current_id.pop());

				$(this).attr('name',current_id+'-'+(real_id+1)+'-'+end);
				$(this).val('');
				
			}else{
				
				real_id = parseInt(current_id.pop());
				
				if(real_id == 0)
					real_id = 1;	
				
				$(this).attr('name',current_id.join('-')+"-"+(real_id+1));	
				
			}					
			
		}
	);
		
	$('textarea',source).each(function(){

		var tmp = $(this).attr('name').split("-");
		
		if(tmp[0] == 'textarea')
			$(this).attr('id',$(this).attr('name'));
		else
			$(this).attr('id','textarea-'+$(this).attr('name'));
		
	});
	
	$('.hasDatepicker',source).each(function(){
			
		$(this).removeClass('hasDatepicker');
		
	});
		
	if(type == 'normal'){
				
		var extra_html = '<div id="form_extra-'+(real_id+1)+'"><div class="top_row" style="border-top:0;">Nog een item toevoegen'+
						 '<div class="floatright"><a href="#" info-id="#form_extra-'+(real_id+1)+'" id="dup_remove-'+(real_id+1)+'" class="duplicate_remove"><img src="images/delete.png"></a></div></div>'+$(source).html()+'</div>';
		$(target).append(extra_html);	
		
	}else{
		
		duplicate_counter++;
		
		while($('#inner_duplicate-'+duplicate_counter).length > 0)
			duplicate_counter++;
			
		var tmp_html = '<div id="inner_duplicate-'+duplicate_counter+'" class="inner_dup">';
		var tmp 	  = $('.combo_field_active',orig_source).attr('rel').split("-");
		
		if(divider_needed)
			tmp_html += '<div class="floatright" style="margin-top:5px">'+
						'<a href="#" id="dup_remove-'+duplicate_counter+'" class="duplicate_remove" info-id="#inner_duplicate-'+duplicate_counter+'"><img src="images/delete.png"></a>'+
						'</div><div class="inner_dup_divide"></div>';
		
		$('#input_form '+target).append(tmp_html+$(source).html()+'</div>');
		
		$('#input_form '+target+' #inner_duplicate-'+duplicate_counter+' :input').val('').attr('style','');
		
		if(($('.inner_dup',orig_source).length+1) >= $('#dup_limit-'+tmp[0]).val() && $('#dup_limit-'+tmp[0]).val() != 0)
			$(orig_source).parents('.row').find('#dup_inner-'+tmp[0]).hide();
				 
		inner_duplicates[orig_source] = $('#workspace').html();
		
		$('#workspace').html('');
		
	}	
	
	$('.rich_field',source).each(function(){
		
		$(this).attr('id','');
		
	});
	
	if(type == 'normal')	
		$('input, select',source).each(function(){
			
			$(this).attr('id','FAKE%%'+$(this).attr('id'));
			
		});
		
	enter_submit('#input_form :input','#input_form','submit');
	
	create_events();
	
}

// Function for removing a duplicated form
//////////////////////////////////////////

function duplicate_remove(source){
		
	var orig_source	= $(source).parents('.row');
	if($('.combo_field_active',orig_source).length > 0){
		
		var tmp = $('.combo_field_active',orig_source).attr('rel').split("-");
		
		if(($('.inner_dup',orig_source).length) < $('#dup_limit-'+tmp[0]).val() || $('#dup_limit-'+tmp[0]).val() == 0)
			$('#dup_inner-'+tmp[0],orig_source).show();
		
	}
		
	$(source).remove();
	
	$(function(){
		create_events();
	});
	
}

// Function allowing forms to be submit by pressing the enter key
/////////////////////////////////////////////////////////////////

function enter_submit(fields,form,type){

	$(fields).keypress(function(e){
		
		if(e.which == 13){
			
			if(type == "submit")
				$(form).submit();
			else
				eval(type);	
			
			e.preventDefault();
			return false;
			
		}	
			
	});	

}

// Function for confirming the deletion of multiple rows
////////////////////////////////////////////////////////

function confirmMultDel(form){

	var answer = confirm("Weet u zeker dat u de geselecteerde rijen permanent wilt verwijderen?");
	
	if(answer){
		
		$('#check_form_type').val('del');
		$(form).submit();	
		
	}

}

// Function for confirming the deletion of a single row
///////////////////////////////////////////////////////

$(function(){

	$('.confirm_del').click(function(e){
	
		e.preventDefault();
		
		confirmDel($(this).attr('href'));
	
	});

});

function confirmDel(url){

	var answer = confirm("Weet u zeker dat u deze rij permanent wilt verwijderen?");
	if (answer)
		window.location = url;

}

// Function that handles clicking boolean values
////////////////////////////////////////////////

function bool_click(source,type){
	
	var tmp 	= $(source).attr("rel").split("|");
	var field 	= tmp[0];
	var id 		= tmp[1];
	
	var newvalue;
	
	
	if($(source).attr('src') == "images/tick.png"){
	
		$(source).attr('src','images/cross.png');
		newvalue = $(source).data('false');
	
	}else{
		
		$(source).attr('src','images/tick.png');
		newvalue = $(source).data('true');
		
	}
	
	if(type == "ajax")	
		$.post('ajax/bool_update.php',{	id: 	id, 
										page:	$('#active_page').html(),
										field: 	field, 
										val: 	newvalue });
	else	
		$(source).next('input').val(newvalue);
	
}

// Function for sliding the input forms
///////////////////////////////////////

var state = 0;

function slide_form(slide,force){
	
	if (force === undefined )
      force = 'nothing to see here';

	if(state == 0 || force == 0){	
	
		if(slide == 1)
			$('#input_form').slideDown('fast',sticky_recalc);
		else
			$('#input_form').show(sticky_recalc);

		$('#slide_img').html('-');
		state = 1;
	
	}else{
		
		if(slide == 1)
			$('#input_form').slideUp('fast',sticky_recalc);
		else
			$('#input_form').hide(sticky_recalc);
		
		$('.printvalues').css('margin-top','10px');
		$('#slide_img').html('+');
		state = 0;		
		
	}
	
}

$(function(){

	$('.slide_form').click(function(){
	
		slide_form(1);
	
	});

});

// Function to show the loading popup
/////////////////////////////////////

function processing(){

	$('#full_overlay').fadeIn();
	$('#processing').fadeIn();
	
}

function finished_processing(){

	$('#full_overlay').fadeOut();
	$('#processing').fadeOut();

}

// Function for centering an element
////////////////////////////////////

jQuery.fn.center = function () {
	
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
    return this;
	
}

// Function for scrolling to an element
///////////////////////////////////////

function scrollToItem(varitem,varoffset){
	
	var target = $(varitem);
	var offset = target.offset().top + varoffset;
	
	$('html, body').animate({scrollTop:offset}, 500);

}

// Allow form submitting by pressing the enter key
//////////////////////////////////////////////////

enter_submit('#login_form :input',				'#login_form','submit');
enter_submit('#input_form :input:not(textarea)','#input_form','submit');

$('#login_submit').click(function(){

	$('#login_form').submit();

});

$('#forget_pass').click(function(){

	$('#login_form').append($("<input>").attr("type", "hidden").attr("name", "forgot_pass").val("true"));
	$('#login_form').submit();

});

// jQuery selectbox
///////////////////

$(function(){

	$('#search_field').sb();

});