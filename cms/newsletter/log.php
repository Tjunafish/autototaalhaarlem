<?
if(!$templates || !$name || !$_POST['id']) 								exit;
if(!$templates[$name]) 													exit;
if(!sql::exists($templates[$name]['table'],array("id"=>$_POST['id']))) 	exit;
	
$dates = sql::fetch("array","","","SELECT * FROM `".LOG_TABLE."` 
									WHERE `template` = '".sql::escape($name)."' 
										&& `letter` = '".sql::escape($_POST['id'])."' 
									GROUP BY `datetime`");
?>

<div id="input_form" style="border:0;margin:0;display:block;">

	<?
    $first = true;
    foreach($dates as $date){
        
		if(!$first)
			echo '<div class="form_divider"></div>';
		else
			$first = false;
		
	?>
	<div class="row">
	
		<?            
		$emails = sql::fetch("array",LOG_TABLE,"WHERE `datetime` = '".$date['datetime']."'");
		?>
		<div class="left">
		
			<b><?=date('H:i - d M Y',strtotime($date['datetime']))?></b><br/>
            <?=$date['type']?>
			
		</div>
	
		<div class="log_right">
	
			<?
			foreach($emails as $email)
				echo '<div class="right"'.(($email['readcount_online'] > 0 || $email['readcount_email'] > 0)? ' style="text-decoration:underline;"' : '').'> '.$email['email'].' </div> &nbsp;';
			?>
	
		</div>
        
        <div class="clear"></div>
	
	 </div>
         
    <?
    }
    ?>        

</div>