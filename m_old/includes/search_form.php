<?
if(!$filter)
	require dirname(__FILE__).'/../php/cars_settings.php';

?>
<div class="selector">

	<form id="search_adv" method="POST" action="<?= mobile_core::page_url('file','sub/search.php') ?>" autocomplete="off">
			
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

	</form>

	<div class="label">
	
		<a class="control" id="search_adv_submit" href="#">zoeken</a>						
		<div class="corner_right"></div>
		
	</div>

</div>

