<?
if(!$filter)
	require dirname(__FILE__).'/../php/cars_settings.php';

?>
<form id="search_adv" method="POST" action="<?= mobile_core::page_url('file','sub/search.php') ?>" autocomplete="off">
    
    <div class="search">
    
        <div id="search">
    	
        	<input type="text"  name="string" value="<?= $_POST[string] ? $_POST[string] : 'trefwoord...' ?>" class="clickclear" />
        	<input type="image" src="images/icon_search-grey.png" width="14" alt="submit" />
    
        </div>
        
    	<div class="corner_left"> </div>
    	<div class="corner_right"></div>        
    
    </div>
    
    <div class="selector">
    	
    	<div>	
    		
    		<select id="select_brand" name="brand">
    		
    			<option value="">Selecteer merk</option>
    			<?
    			sql::$select = 'SELECT DISTINCT(`merk`)';
    			
    			foreach(sql::fetch("array","cars","ORDER BY `sortname` ASC") as $car)
    				echo '<option value="'.$car[merk].'" '.
    					 ($filter[brand] == $car[merk] ? 'selected="selected"' : '').
    					 '>'.$car[merk].'</option>';
    			?>
    		
    		</select>
    			
    	</div>
    			
    	<div>	
    	
    		<select id="select_model" class="model" name="model">
    		
    			<?
    			if(!empty($filter[merk])){
    			
    				echo '<option value="">Selecteer model</option>';
    				sql::$select = 'SELECT DISTINCT(`model`)';
    				
    				foreach(sql::fetch("array","cars","ORDER BY `model` ASC") as $car)
    					echo '<option value="'.$car[model].'" '.
    						 ($filter[model] == $car[model] ? 'selected="selected"' : '').
    						 '>'.$car[model].'</option>';
    						 
    			}else
    				echo '<option value="">Selecteer eerst een merk</option>';
    			?>
    		
    		</select>
    		
    	</div>
    
    	<div class="label">
    	
    		<a class="control" id="search_adv_submit" href="#">zoeken</a>						
    		<div class="corner_right"></div>
    		
    	</div>
    
    </div>

</form>