<?
require '../php/cars_settings.php';
?>

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

	<div class="search">
	
		<form id="search" method="post" action="zoeken" target="_top">
		
			<input type="text"  name="string" value="<?= $_POST[string] ? $_POST[string] : 'trefwoord...' ?>" class="clickclear" />
			<input type="image" src="images/icon_search-grey.png" width="14" alt="submit" />
	
		</form>
		
		<div class="corner_left"> </div>
		<div class="corner_right"></div>
	
	</div>
	
    <?
	require __DIR__.'/../includes/search_form.php';
    ?>	

</div>
<div id="cars_holder">

	<?
	require 'ajax/cars.php';
	?>
	
</div>