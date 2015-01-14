<?
if(!$filter)
	require dirname(__FILE__).'/../php/cars_settings.php';

?>
<form id="search_adv" method="POST" action="<?= core::page_url('file','sub/voorraad.php') ?>" autocomplete="off" <?= core::$cur_page[file] == 'sub/home.php' ? 'style="margin-top:0;"' : '' ?>>

	<label for="select_brand">Merk</label>
	
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
	
	<div class="clear"></div>
	
	<label for="select_model">Model</label>
	
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

</form>