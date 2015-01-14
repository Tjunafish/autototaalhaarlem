<?
require 'config.php';
?>
<script type="text/javascript" src="newsletter/newsletter.js"></script>
<script type="text/javascript" src="//www.google.com/jsapi">  			</script>

<div class="top_row pointer" 	id="show_stats">Statistieken <div class="openclose">[openen]</div></div>
<div class="form_block hidden" 	id="newsletter_stats"></div> 	

<script type="text/javascript">
$(function(){
	
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
</script>