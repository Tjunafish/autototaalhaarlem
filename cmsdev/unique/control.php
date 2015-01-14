<?
if(file_exists('../config.php'))
	require_once '../config.php';
else
	require_once 'config.php';
?>
<div id="content_right" class="full">

	<div class="top_row">Professionals accepteren</div>
    <div class="form_block">
    
        <div class="row">
        
            <div class="left">
            
                Professional naam
            
            </div>
            
            <div class="right">
            
            	<select id="select_pro">
                
                	<option value="">Kies een professional</option>
               
					<?
					foreach(sql::fetch("array","professionals","WHERE `password` = '' ORDER BY `name` ASC") as $pro)
						echo '<option value="'.$pro['id'].'">'.$pro['name'].' ('.$pro['email'].')</option>';                   
                    ?>
            
            	</select>
                
                <a href="#" id="accept_pro" class="form_button" style="margin-top:-1px;">accepteren</a>
            
            </div>
        
        </div>
        
    </div>
    
    <br/>
    
    <div class="top_row">Opnieuw versturen</div>
    <div class="form_block">
    
        <div class="row">
        
            <div class="left">
            
                Ticket
            
            </div>
            
            <div class="right">
            
            	<select id="select_ticket">
                
                	<option value="">Kies een ticketnummer</option>
               
					<?
					foreach(sql::fetch("array","event_signups","WHERE `status` = 'Completed' ORDER BY `date` ASC") as $ticket)
						echo '<option value="'.$ticket['id'].'">'.$ticket['order_id'].'</option>';                   
                    ?>
            
            	</select>
                
                <a href="#" id="send_ticket" class="form_button" style="margin-top:-1px;">versturen</a>
            
            </div>
        
        </div>
        
        <div class="form_divider"></div>
        
        <div class="row">
        
            <div class="left">
            
                Factuur
            
            </div>
            
            <div class="right">
            
            	<select id="select_invoice">
                
                	<option value="">Kies een factuurnummer</option>
               
					<?
					foreach(sql::fetch("array","invoice","ORDER BY `date` ASC") as $invoice)
						echo '<option value="'.$invoice['id'].'">'.$invoice['number'].'</option>';                   
                    ?>
            
            	</select>
                
                <a href="#" id="send_invoice" class="form_button" style="margin-top:-1px;">versturen</a>
            
            </div>
        
        </div>
        
    </div>
    
</div>

<script type="text/javascript">
$(function(){
	
	$('.form_button').click(function(event){
		
		event.preventDefault();
		
		switch($(this).attr('id')){
		
			case "accept_pro":
			if($('#select_pro').val() == '')
				break;
				
			if(confirm('Weet u zeker dat u '+$('#select_pro option[value='+$('#select_pro').val()+']').text()+' wilt accepteren en daarmee in kan laten loggen in het systeem?'))			
				$.post('unique/control_ajax.php',{action: 'accept', id: $('#select_pro').val(), hash: '<?=sha1(md5($_SERVER['REMOTE_ADDR']."h4esecure"))?>'},function(){
			
					alert('Professional geaccepteerd en e-mail verstuurd');
					window.location = 'index.php?page=professionals_accepted&id='+$('#select_pro').val()+'&action=edit';
					
				});
			break;
			
			case "send_ticket":
			if($('#select_ticket').val() == '')
				break;
				
			if(confirm('Weet u zeker dat u de download link voor ticket "'+$('#select_ticket option[value='+$('#select_ticket').val()+']').text()+'" opnieuw wilt versturen?'))
				$.post('unique/control_ajax.php',{action: 'ticket', id: $('#select_ticket').val(), hash: '<?=sha1(md5($_SERVER['REMOTE_ADDR']."h4esecure"))?>'},function(data){
			
					alert('De download link voor de ticket is opnieuw verstuurd naar de klant zowel als naar '+data);
					
				});
			break;
			
			case "send_invoice":
			if($('#select_invoice').val() == '')
				break;
				
			if(confirm('Weet u zeker dat u de download link voor factuur "'+$('#select_invoice option[value='+$('#select_invoice').val()+']').text()+'" opnieuw wilt versturen?'))
				$.post('unique/control_ajax.php',{action: 'invoice', id: $('#select_invoice').val(), hash: '<?=sha1(md5($_SERVER['REMOTE_ADDR']."h4esecure"))?>'},function(data){
			
					alert('De download link voor de factuur is opnieuw verstuurd naar de professional zowel als naar '+data);
					
				});	
			break;
			
		}
		
		
		
	});
});
</script>