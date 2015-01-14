$(function(){
	
	$('#select_template').unbind().change(function(){
		
		$('#newsletter_details').html('');	
		$.post('newsletter/ajax.php',{action: 'select_template', template: $(this).val()},function(data){
			
			$('#select_newsletter').html(data);
			
		});
		
	});
	
	$('#select_newsletter').unbind().change(function(){
		
		if($(this).hasClass('stats'))
			var url = 'newsletter/stat_second.php';
		else
			var url = 'newsletter/details.php';
		
		if($(this).val() != '')		
			$.post(url, {template: $('#select_template').val(), id: $('#select_newsletter').val()}, function(data){
				
				$('#newsletter_details').html(data)
										.show();
			
			});			
		else
			$('#newsletter_details').html('');
		
	});
	
	$('#show_preview').unbind().click(function(event){

		event.preventDefault();
		
		if($('#newsletter_preview').html() == "")
			$.post('newsletter/ajax.php',{action: 'load_preview', template: $('#select_template').val(), id: $('#select_newsletter').val()},function(data){
				
				$('#newsletter_preview').html(data)
										.slideToggle();
				
			});
		else
			$('#newsletter_preview').slideToggle();
			
		if($(this).find('.openclose').html() == "[openen]")		
			$(this).find('.openclose').html("[sluiten]");
		else
			$(this).find('.openclose').html("[openen]");
		
	});
	
	$('#show_stats').unbind().click(function(event){

		event.preventDefault();
		
		if($('#newsletter_stats').html() == "")
			$.post('newsletter/ajax.php',{action: 'load_stats', template: $('#select_template').val(), id: $('#select_newsletter').val()},function(data){
				
				$('#newsletter_stats').html(data)
									   .slideToggle();
				
			});
		else
			$('#newsletter_stats').slideToggle();
			
		if($(this).find('.openclose').html() == "[openen]")		
			$(this).find('.openclose').html("[sluiten]");
		else
			$(this).find('.openclose').html("[openen]");
		
	});
	
	$('#show_log').unbind().click(function(event){
		
		event.preventDefault();
		
		if($('#newsletter_log').html() == "")
			$.post('newsletter/ajax.php',{action: 'load_log', template: $('#select_template').val(), id: $('#select_newsletter').val()},function(data){
				
				$('#newsletter_log').html(data)
									.slideToggle();
				
			});
		else
			$('#newsletter_log').slideToggle();
			
		if($(this).find('.openclose').html() == "[openen]")		
			$(this).find('.openclose').html("[sluiten]");
		else
			$(this).find('.openclose').html("[openen]");
		
	});
	
	$('#send_single').unbind().click(function(event){
		
		event.preventDefault();
		
		processing();				
		
		$.post('newsletter/ajax.php',{	action: 	'send_single', 
										email: 		$('#single_email').val(), 
										template: 	$('#select_template').val(), 
										id: 		$('#select_newsletter').val()}, 
			function(data){
			
				finished_processing();			
				cmsalert(data);
				
			}
		);
		
	});
	
	$('#send_mult').unbind().click(function(event){
		
		event.preventDefault();
		var group = '';
		
		processing();
						
		if($('#group_all').is(':checked'))
			group = "all";
		else	
			$(".group_check:checked:not('#group_all')").each(function(){
				
				if($(this).val() != "undefined" && typeof $(this).val() != "undefined")
					group += $(this).val()+",";
				
			});
		
		$.post('newsletter/ajax.php',{	action: 	'send_mult', 
										group: 		group, 
										template: 	$('#select_template').val(), 
										id: 		$('#select_newsletter').val()}, 										
			function(data){
			
				tmp = data.split("|");
				$('#resend_form').html('');
				
				if(tmp[0] != ""){

					emails = tmp[0].split('#');
					
					for(var i in emails){
									
						var email = emails[i];
								
						if(email == "")
							continue;
						
						$('#resend_form').append('<span class="right"><input type="checkbox" id="check_'+email+'" name="'+email+'" value="'+email+'" /> <label for="check_'+email+'" style="position:relative;bottom:2px;">'+email+'</label></span> ');
						$('#resend').slideDown();
						
					}
				
				}
				
				if(tmp[1] != "")
					cmsalert(tmp[1]);
					
				finished_processing();										
			
			}			
		);
		
	});
	
	$('#send_re').unbind().click(function(event){
		
		event.preventDefault();
		var emails = '';
		
		$("#resend_form input:checkbox:checked").each(function(){
			
			if($(this).val() != "undefined" && typeof $(this).val() != "undefined")
				emails += $(this).val()+",";
			
		});
	
		$.post('newsletter/ajax.php',{	action: 	'send_re', 
										emails: 	emails, 
										template: 	$('#select_template').val(), 
										id: 		$('#select_newsletter').val()}, 										
			function(data){
			
				cmsalert(data);
				$('#resend').slideUp();
			
			}			
		);
		
	});
	
	$('.group_check').unbind().change(function(event){
		
		if($(this).is(':checked')){

			if($(this).attr('id') == "group_all")				
				$('.group_check').attr('checked','checked');	
			else if($('.group_check:not(:checked):not(#group_all)').length == 0)				
				$('#group_all').attr('checked','checked');
						
		}else{
			
			$('#group_all').removeAttr('checked');
			
			if($(this).attr('id') == "group_all")				
				$('.group_check').removeAttr('checked');
		
		}
		
	});
		
});