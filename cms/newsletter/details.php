<?
require 'config.php';
?>
<script type="text/javascript" src="newsletter/newsletter.js"></script>
<div class="top_row pointer" id="show_preview">Voorbeeld <div class="openclose">[openen]</div></div>
<div class="form_block hidden" id="newsletter_preview"></div>  

<div class="spacer"></div>

<div class="top_row">Versturen / testen / nasturen</div>
<div id="input_form" class="form_block" style="display:block;margin-bottom:10px;">

    <div class="row">
    
        <div class="left">Enkel versturen:</div>
        <div class="right">
        
        	<input type="text" id="single_email" style="width:300px"/><a href="#" id="send_single" class="form_button" style="margin-top:-1px;">versturen</a>
        
        </div>
    
    </div>
    
    <div class="form_divider"></div>
    
    <div class="row">
    
        <div class="left">Groep versturen:</div>
        <div class="log_right">
        <?
		list($tmp) = explode("|",$_POST['template']);
		
		$all_sent = sql::num('',false,"SELECT DISTINCT(`datetime`) 
										 FROM `".LOG_TABLE."` 
										 WHERE `template` = '".sql::escape($tmp)."' 
											&& `letter` = '".sql::escape($_POST['id'])."' 
											&& `type` = 'Groep: Iedereen'");
		?>
        	
        	<input type="hidden" id="mult_email" />        
			<div class="right">
            
                <input type="checkbox" id="group_all" class="group_check" /> 
                <label for="group_all">
                Iedereen (<?=
                          sql::num(PEOPLE_TABLE);
                          ?>)
                <?=
                ($all_sent)? '<div class="times_sent">Al '.$all_sent.' keer verstuurd</div>' : '';
                ?></label>
                
            </div>
            
            <br/>
            
			<?
            if(GROUP_FIELD)
                foreach(sql::fetch("array",PEOPLE_TABLE,"GROUP BY `".GROUP_FIELD."`") as $group){
					
					$group_sent = sql::num('',false,"SELECT DISTINCT(`datetime`) 
														FROM `".LOG_TABLE."` 
														WHERE `template` = '".sql::escape($tmp)."' 
															&& `letter` = '".sql::escape($_POST['id'])."' 
															&& `type` = 'Groep: ".$group[GROUP_FIELD]."'");
								
                    echo '<div class="right"><input type="checkbox" id="group_'.$group[GROUP_FIELD].'" class="group_check" value="'.$group[GROUP_FIELD].'"> 
						  <label for="group_'.$group[GROUP_FIELD].'" style="position:relative;bottom:2px;">'.$group[GROUP_FIELD].' ('.
						  sql::num(PEOPLE_TABLE,"WHERE `".GROUP_FIELD."` = '".$group[GROUP_FIELD]."'").
						  ')</label> '.(($group_sent)? '<div class="times_sent">Al '.$group_sent.' keer verstuurd</div>' : '').'</div><br/>';
						  
				}
            ?>
            <div class="right">
            	<a href="#" id="send_mult" class="form_button" style="margin:0;float:left;">versturen</a>
            </div>
            
        </div>
        
        <div class="clear"></div>
        
	</div>
    
    <div id="resend" style="display:none;">
       
       	<div class="form_divider"></div>
            
        <div class="row">
            
            <div class="left">
            	Dubbel versturen:
            </div>
            
            <div class="log_right">
            
                <span class="right"><b>De volgende e-mail adressen hebben deze nieuwsbrief al ontvangen, selecteer welke je alsnog dubbel wilt versturen.</b></span><br/>
                
                <form id="resend_form" style="margin-bottom:3px;"></form>
                
                <span class="right">
                <a href="#" id="send_re" class="form_button" style="margin:0;float:left;">versturen</a>
                </span>
                        
            </div>
       
            <div class="clear"></div>
        
        </div>
        
    </div>

</div>

<div class="top_row pointer" 	id="show_log">Verstuurdata <div class="openclose">[openen]</div></div>
<div class="form_block hidden" 	id="newsletter_log"></div>