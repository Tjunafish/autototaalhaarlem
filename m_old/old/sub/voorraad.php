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

<?
	require __DIR__.'/../includes/search_form.php';
?>	

<?/**
<div class="options">

	<div class="top">
	
		<p>Sorteer op:</p>
		
		<a class="control <?= $search[sort][type] == 'name'	 ? 'active' : '' ?>" rel="sort" data-dir="asc" 	href="name">naam 
		<img src="../img/sort_asc.png" alt="asc"   rel="sort" <?= $search[sort][type] != 'name'  ? 'style="display:none;"' : '' ?>/></a>   
		
		<span class="divider"></span>
		
		<a class="control <?= $search[sort][type] == 'date'  ? 'active' : '' ?>" rel="sort" data-dir="desc" href="date">datum
		<img src="../img/sort_desc.png" alt="desc" rel="sort" <?= $search[sort][type] != 'date'  ? 'style="display:none;"' : '' ?> /></a>
		
		<span class="divider"></span>
		
		<a class="control <?= $search[sort][type] == 'price' ? 'active' : '' ?>" rel="sort" data-dir="asc" 	href="price">prijs
		<img src="../img/sort_asc.png" alt="asc"   rel="sort" <?= $search[sort][type] != 'price' ? 'style="display:none;"' : '' ?> /></a>

	</div>
	
</div>
**/?>

<div class="clear"></div>

<div id="cars_holder">
	
<?
require 'ajax/cars.php';
?>

</div>
