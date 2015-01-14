<?
require 'newsletter/config.php';
?>
<script type="text/javascript" src="newsletter/newsletter.js"></script>
<div id="content_right" class="full" style="margin-bottom:0;">

    <div class="top_row">Kies een nieuwsbrief</div>

    <div id="input_form" class="form_block" style="display:block;margin-bottom:10px;">
    
    	<div class="row">
        
        	<div class="left">Template:</div>
            <div class="right">
            
            	<select id="select_template">
                
					<?					
					$count = 0;
					
					foreach($templates as $name => $temp){
						
						$count++;							
						echo '<option value="'.$name.'|'.$temp['table'].'|'.$temp['file'].'|'.$temp['titlefield'].'|'.$temp['titletype'].'|'.$temp['title'].'"'.
							 (($count == count($templates))? 'selected="selected"' : '')
							 .'>'.
							 $temp['title'].'
							 </option>';	
						
					}
                    ?>
                    
                </select>
            
            </div>
        
        </div>
    	
        <div class="form_divider"></div>
        
        <div class="row">
        
        	<div class="left">Nieuwsbrief:</div>
            <div class="right">
            
            	<select id="select_newsletter">
                
                	<option>Kies eerst een template</option>
                    
                </select>
            
            </div>
        
        </div>
    
    </div>
    
    <div id="newsletter_details"></div>

</div>

<script type="text/javascript">
$(function(){
	
	$('#select_template').trigger('change');
	
});
</script>