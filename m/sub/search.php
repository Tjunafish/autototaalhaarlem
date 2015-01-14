<?
require __DIR__.'/../php/cars_settings.php';
?>
<div id="cardetail"></div>
<div id="searchcontainer">
	<form id="controls" autocomplete="off">

		<input type="hidden" name="curr" value="page" />
		<input type="hidden" name="prev" />
		<input type="hidden" name="section" value="<?= $_GET[section] ?>" />
		<input type="hidden" name="start_limit" value="0" />
		
		<?
		$fields = array('view'		=> $search[view],
						'sort'		=> $search[sort][type],
						'sort_dir'	=> $search[sort][dir],
						'page'		=> $search[page][current]);
						
		foreach($fields as $field => $value)
			echo '<input type="hidden" name="'.$field.'" value="'.$value.'" />';
		?>

	</form>

	<div class="center">
		
	    <?
		require __DIR__.'/../includes/search_form.php';
	    ?>	

	</div>
	<div id="loading" class="ajax_container" style="display: none;">
		<div class="temp">
			<img src="ajax/load.gif" />
		</div>
	</div>
	<div id="cars_holder">

		<?
		require __DIR__.'/../ajax/cars.php';
		?>
		
	</div>
</div>