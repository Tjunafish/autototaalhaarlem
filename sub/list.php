<?	
if(core::$cur_page['id'] == 10){ // Favorieten

	$favorites = json_decode($_COOKIE['favorites']);
	
	if(is_object($favorites))
		$favorites = get_object_vars($favorites);
	
	$cars      = array();
	
	foreach($favorites as $nr => $time){
		
		list($tmp) = sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($nr)."'");
		
		if(!$cars[$time])
			$cars[$time]     = $tmp;
		else
			$cars[$time.'b'] = $tmp;
			
	}
		
	krsort($cars);
	
	$banner = sql::fetch("object","banners","ORDER BY `order` ASC LIMIT 1");
	$img    = $banner->img;

}elseif(core::$cur_page['id'] == 9){ // Landingspagina

	$domain = str_replace('http://','',$_SERVER[HTTP_REFERER]);
	$domain = str_replace('www.',	'',$domain);
	$domain = str_replace('/',		'',$domain);
		
	list($landing) = sql::fetch("array","landing","WHERE `domain` = '".sql::escape($domain)."'");
		
	$tmp = array();
	
	if($landing[brand])
		$tmp[] = "`merk` = '".$landing[brand]."'";
	
	if($landing[model])
		$tmp[] = "`model` = '".$landing[model]."'";
	
	if($landing[fuel])
		$tmp[] = "`brandstof` = '".$landing[fuel]."'";
	
	if(count($tmp) > 0)
		$filtersql = "WHERE ".implode(" && ",$tmp);
	
	$cars  = sql::fetch("array","cars",$filtersql." ORDER BY `merk`,`model`,`type` ASC");
	
	$img   = $landing[img];
	$title = $landing[title];
	
}
?>
<ul id="slider">
	
	<?
	echo '<li><img src="'.$img.'" alt="'.$title.'" /><p>'.$title.'</p></li>';
	?>

</ul>

<div class="label <?= core::$cur_page['id'] == 9 && $landing[fuel] == 'H' ? 'green' : 'orange' ?>">
	<div class="wrapper-container">
		<p class="left arrow_down"><?= core::$cur_page['id'] == 10 ? 'Uw favorieten': "Onze auto's" ?></p>
		<p class="right">	<a href="<?= core::page_url('file','sub/voorraad.php') ?>" class="arrow_right" target="_top">Bekijk totale voorraad</a></p>
	</div>
	<div class="shadow"></div>
	
</div>

<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">
			<div class="content">
			
				<? core::draw_cars(1,$cars) ?>
			
			</div>
		</div>
	</div>
</div>