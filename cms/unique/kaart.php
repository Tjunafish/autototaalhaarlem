<?
require_once __DIR__.'/../config.php';
?>
<div id="content_right" class="full">

	<div class="top_row">Autokaart genereren</div>
    <div class="form_block">
    
        <div class="row">
        
            <div class="left">
            
                Kies een auto:
            
            </div>
            
            <div class="right">
            
            	<select id="select_car">
                               
					<?
					foreach(sql::fetch("array","cars","ORDER BY `merk`,`model`,`type`,`kenteken` ASC") as $car)
						echo '<option value="'.$car['voertuignr'].'">'.$car['merk'].' '.$car['model'].' '.$car['type'].' '.$car['kenteken'].'</option>';                   
                    ?>
            
            	</select>
                
                <a href="#" class="form_button" style="margin-top:-1px;">maak</a>
            
            </div>
        
        </div>
        
    </div>
    
</div>

<script type="text/javascript">
$(function(){
	
	$('.form_button').click(function(event){
		
		event.preventDefault();
		
		window.open('/kaart/'+$('#select_car').val());
		
	});
});
</script>