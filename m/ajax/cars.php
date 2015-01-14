<?
if(!is_object('mobile_core')){
	
	if(class_exists(sql)){
		sql::reset_select();
	}	
	
	require __DIR__.'/../php/config.php';
	
	if($_POST[type] != 'rotator')
		require __DIR__.'/../php/cars_settings.php';
		
}

$start_limit = $_POST[start_limit] ? $_POST[start_limit] : 0;

$sort        = implode(" ".$search[sort][dir].", ",explode(",",$search[sortsql]));
$cars        = sql::fetch("array","cars",$filtersql." ORDER BY ".$sort." LIMIT ".sql::escape($start_limit).",".$search[limit]);

if(count($cars) < count(sql::fetch("array","cars",$filtersql)))
    $add_container = true;
else
    $add_container = false;
?>
<div class="content">

	<?
	mobile_core::draw_cars(1,$cars, false, $start_limit, $add_container);
	?>
	
</div>