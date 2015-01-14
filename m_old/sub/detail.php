<?
$car_nr = array_pop(explode("-",$_GET['item']));

if(sql::exists("cars",array("voertuignr"=>$car_nr,"active"=>1)))
	list($car) = sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($car_nr)."'");
else
	list($car) = sql::fetch("array","cars","ORDER BY `created_at` DESC LIMIT 1");

////////////////////
// GET NEIGHBOURS //
////////////////////

$filters 				= array('brand|str'			=> "`merk`  					= 	'--input--'",
								'model|str'			=> "`model` 					= 	'--input--'",
								'minprice|int'		=> "`verkoopprijs_particulier` 	>= 	--input--",
								'maxprice|int'		=> "`verkoopprijs_particulier` 	<= 	--input--",
								'minkm|int'			=> "`tellerstand` 				>= 	--input--",	
								'maxkm|int'			=> "`tellerstand` 				<= 	--input--",
								'minyear|int'		=> "`bouwjaar` 					>= 	--input--",
								'maxyear|int'		=> "`bouwjaar` 					<= 	--input--",
								'transmission|str'  => "`transmissie`		 		= 	'--input--'",
								'doors|int'			=> "`aantal_deuren` 			= 	--input--",	
								//'seats|int'		=> "`aantal_zitplaatsen` 		= 	--input--",
								'fuel|str'			=> "`brandstof` 				= 	'--input--'");

foreach($filters as $f => $sql){

	list($name,$type) = explode("|",$f);
	$val			  = $_SESSION[values][$name] ? $_SESSION[values][$name] : '';
	
	if(empty($val))
		continue;

	if($type == 'int' && !is_numeric($val))
		continue;
			
	$val 			= sql::escape($val);		
	$tmp[] 			= str_replace('--input--',$val,$sql);
	
}

$tmp[] = '`active` = 1';

if(core::$cur_page[id] == 3) // Aanbiedingen pagina
	$tmp[] = '`actieprijs` > 0';
elseif(core::$cur_page[id] == 4) // Milieubewust pagina
	$tmp[] = "`milieu_bewust` = '1'";
	
if($search[string])
	$tmp[] = "(`sortname` LIKE '%".$search[string]."%' || `merk` LIKE '%".$search[string]."%' || `model` LIKE '%".$search[string]."%' || `type` LIKE '%".$search[string]."%' || concat(`merk`,' ',`model`,' ',`type`) LIKE '%".$search[string]."%')";

if(count($tmp) > 0)
	$filtersql = "WHERE ".implode(" && ",$tmp);
    
if($_SESSION[sort_dir] != "asc" && $_SESSION[sort_dir] != "desc")
    unset($_SESSION[sort_dir]);

$search[sort][type]		= $_SESSION[sort]   ? $_SESSION[sort]   
										    : 'name';
	
$search[sorting][name][fields] 	= '`sortname`';
$search[sorting][date][fields] 	= '`created_at`';
$search[sorting][price][fields]	= '`verkoopprijs_particulier`';

$search[sortsql]		= $search[sorting][$search[sort][type]] ? $search[sorting][$search[sort][type]] 
                                                                : reset($search[sorting]);

list($prev,$next) = mobile_core::get_neighbours($car,'cars',$search[sortsql][fields],$filtersql);

////////////////

$color = $car[milieu_bewust] ? 'green'
							 : ' ';
							 
//print_r($car);

$images = explode(",",$car[afbeeldingen]);
$thumbs = core::car_thumbs($car);

?>

<div class="detail_center">

	<div class="head corner_left"></div>
	<div class="head corner_right"></div>

	<div class="nav">
	
		<a class="left" href="http://www.autoservicehaarlem.nl/m/<?=mobile_core::car_url($prev)?>">vorige</a>		
		<a href="zoeken/">overzicht</a>		
		<a class="right" href="http://www.autoservicehaarlem.nl/m/<?=mobile_core::car_url($next)?>">volgende</a>
	
	</div>
	
	<div class="title">
	
		<?= $car[merk]." ".$car[model]." ".$car[type] ?>
	
	</div>
	
	<div class="image">
    
        <a href="<?=$images[0]?>" target="_blank" id="full_link"></a>
		
		<img id="full_img" src="<?=$images[0]?>" />
		
        <?
        if($car[verkocht] == 'j')
            echo '<div class="sold '.$color.'"></div>';
        ?>
        
		<div class="label">
			
			<div class="text">&euro; <?= mobile_core::car_price($car) ?>,-</div>			
			<div class="corner_right"></div>
			
		</div>		
        
        <? 
        if($car[newprice] == 1 && CODECREATORS){ 
        ?>
		<div class="new_label">
			
			<div class="text">new price</div>
			<div class="corner_right"></div>
			
		</div>
		<? 
        } 
        ?>
		
	</div>
	
	<div class="detail_box">
	
		<div class="slider">

			<a rel="img_scr" class="slid_left" href="#"><img rel="i" src="images/arrow_left_i.png" /><img style="display:none" rel="a" src="images/arrow_left.png" /></a>
			
			<?
			$count = 0;
			foreach($images as $image) {
				$count++;
				if($count <= 3)
					echo '<a rel="'.$count.'" class="img_small" href="'.$image.'" target="_blank"><img src="'.$image.'" /></a>';
				else
					echo '<a rel="'.$count.'" class="img_small" href="'.$image.'" target="_blank" style="display:none;"><img src="'.$image.'" /></a>';
			
			}
			?>
			<input type="hidden" name="img_tot" value="<?=$count?>" />
			<input type="hidden" name="img_pos" value="1" />
			
			<a rel="img_scr" class="slid_right" href="#"><img style="display:none" rel="i" src="images/arrow_right_i.png" /><img rel="a" src="images/arrow_right.png" /></a>

		</div>
		
		<div class="label">
		
			<div class="buttons">
			
				<a target="_blank" href="mailto:info@autoservicehaarlem.nl?subject=<?=$car[merk]." ".$car[model]." ".$car[type]?>"><img src="images/share_message.png" /></a>
				<a href="http://twitter.com/home?source=webclient&status=<?=urlencode($car[merk].' '.$car[model].' '.$car[type]).'+-+http://www.autoservicehaarlem.nl/'.core::car_url($car);?>"><img src="images/share_tweet.png" /></a>
				<a href="http://www.facebook.com/sharer.php?u=http://www.autoservicehaarlem.nl/<?=rawurlencode(mobile_core::car_url($car)).'&t='.urlencode($car[merk].' '.$car[model].' '.$car[type]);?>"><img src="images/share_face.png" /></a>
				
				<div class="corner_right"></div>
			
			</div>
			
		
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="detail_row">
		
		<a rel="spec" class="detail_button" href="#"><img style="display: none" width="11px" src="images/opt_arrow_down.png" /><img width="11px" src="images/opt_arrow_right.png" />specificaties</a>		
		
	</div>
	
	<div rel="spec" class="specification">
	
		<strong>Model:</strong> 			<?= $car[model] 		?><br />
		<strong>Carosserievorm:</strong> 	<?= $car[carrosserie] 	?><br />
		<strong>Brandstof:</strong> 		<?= mobile_core::string("brandstof ".$car[brandstof]) ?><br />
		<br />
		<strong>Bouwjaar:</strong> 			<?= $car[bouwjaar] 		?><br />
		<strong>Transmissie:</strong> 		<?= mobile_core::string("transmissie ".$car[transmissie]) 	?><br />
		<strong>Aantal deuren:</strong> 	<?= $car[aantal_deuren] ?><br />
		<br />
		<strong>Kilometerstand:</strong> 	<?= $car[tellerstand].' '.mobile_core::string("eenheden ".$car[tellerstand_eenheid]) ?><br />
		<strong>Cilinderinhoud:</strong> 	<?= $car[cilinderinhoud] 							?> cc<br />
		<strong>Kleur:</strong>  			<?= $car[basiskleur].' '.$car[laksoort]	?>
	
	</div>
	
	<div class="detail_row">
	
		<a rel="opti" class="detail_button" href="#"><img style="display: none" width="11px" src="images/opt_arrow_down.png" /><img width="11px" src="images/opt_arrow_right.png" />opties</a>	
	
	</div>
	
	<div rel="opti" class="specification options">
	
		<?
		//$accessoires = explode(",",str_replace('*',' ',preg_replace('/(,([^\s]))+/'," <span class=\"bull\">&bull;</span> $2",$car[accessoires])));
		$accesoires_temp = str_replace('*',' ',preg_replace('/(,([^\s]))+/',",$2",$car[accessoires]));
		$accesoires = explode(",",$accesoires_temp);
		
		//print_r($accesoires);		
		for ($i = 0; $i <= 9; $i++) {
			echo '&bull; '.$accesoires[$i].'<br />';
		}
		
		if(count($accesoires) > 10) {
			echo '&bull; en meer ..';
		}
		?>
		

	
	</div>
	
</div>