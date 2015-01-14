<div id="content">
       
    <div id="papertrail">
    
    	<img src="images/link.png" alt="Papertrail" />
   		<?
        if(core::$PAGE->TAB) 
			echo core::$PAGE->TAB.' <p>&gt;</p> ';

		echo core::$PAGE->TITLE;
		
		if(core::$PAGE->SELECTOR == "show_all") 
			echo ' <p>&gt;</p> Show all'; 
		elseif(core::$PAGE->SELECTOR) 
			echo ' <p>&gt;</p> '.core::$PAGE->linkValue(core::$PAGE->SELECTOR_FIELD,core::$PAGE->SELECTOR); 
		?>
        
    </div>
    
    <?	
	if(core::$PAGE->FILE && file_exists(core::$PAGE->FILE))	
		include core::$PAGE->FILE;	
	else{
		
		if(method_exists(core::$PAGE,'printSearch'))
			core::$PAGE->printSearch();
		
		if(count(core::$PAGE->MODULES["left"]) > 0){ ?>
		<div id="content_left">
		
			<?		
			core::$PAGE->printModules("left");
			?>
		
		</div>
		
		<div id="content_right"<?= (core::$PAGE->CUSTOM_ORDER && !isset($_GET['search']))? ' class="custom_order"' : ''; ?>>
		
		<?
		}else{	
		?>
		
		<div id="content_right" class="full<?= (core::$PAGE->CUSTOM_ORDER && !isset($_GET['search']))? ' custom_order' : ''; ?>">
		
		<?        
        }	
				
		include 'form_values.php';
		
		echo '</div>';
		
	}
	?>
    
    <div class="clear"></div>	

</div>