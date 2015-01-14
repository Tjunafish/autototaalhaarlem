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

if($_SESSION[special] == 'actieprijs') // Aanbiedingen pagina
	$tmp[] = '`actieprijs` > 0';
elseif($_SESSION[special] == 'milieu_bewust') // Milieubewust pagina
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

list($prev,$next) = core::get_neighbours($car,'cars',$search[sortsql][fields],$filtersql);

////////////////

$color = $car[milieu_bewust] ? 'green'
							 : 'orange';
							 
$favorites = json_decode($_COOKIE['favorites']);

if(is_object($favorites))
	$favorites = get_object_vars($favorites);
?>

<script type="text/javascript" src ="js/fancybox/jquery.fancybox-1.3.4.pack.js">	</script>
<script type="text/javascript" src ="js/fancybox/jquery.easing-1.4.pack.js">		</script>
<link 	rel="stylesheet" 	   href="js/fancybox/jquery.fancybox-1.3.4.css"/>

<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">

			<div id="top_spacer"></div>
			
			<?
			core::draw_papertrail();
			?>
			
			<div class="options">
			
				<span class="left long">
				
					<span class="tell_friend">
					
						<a href="#">Tell a friend</a>
						
						<form class="tell_friend ajax" action="ajax/tell_friend.php">
						
							<div class="arrow"></div>
						
							<h2>Deel deze auto met een vriend!</h2>
							
							<input type="hidden" name="car" value="<?= $car[voertuignr] ?>" />
							
							<input type="text" name="name"  class="clickclear" value="Je naam" />
							<input type="text" name="email" class="clickclear" value="Je e-mailadres" /><br />
							<input type="text" name="fname" class="clickclear" value="Naam vriend" />
							<input type="text" name="fmail" class="clickclear" value="E-mailadres vriend" /><br />
							
							<textarea name="msg" class="clickclear">Persoonlijk bericht</textarea><br />
							
							<input type="submit" value="verstuur bericht" />
										
						</form>
						
					</span>
					
					<span class="divider"></span>
					
					<a href="#" class="print">Print deze pagina <img src="img/icon_print.png" alt="print deze pagina" /></a>
					
				</span>
				
				<span class="right">
				
					<a href="<?= core::car_url($prev) ?>">&lt; Vorige</a>
				
					<span class="divider"></span>
					
					<a href="<?
					if($_SESSION[special] == 'actieprijs')
						echo 'aanbiedingen';
					elseif($_SESSION[special] == 'milieu_bewust')
						echo 'milieubewust';
					else
						echo 'voorraad';
					?>/">overzicht</a>
					
					<span class="divider"></span>
					
					<a href="<?= core::car_url($next) ?>">Volgende &gt;</a>
				
				</span>
				
			</div>
			
			<div class="content">
			
				<div class="car detail">
				
					<?= $car[newprice] == 1 ? '<div class="newprice"></div>' : '' ?>
						
					<div class="left">
					
						<?
						$images = explode(",",$car[afbeeldingen]);
						$thumbs = core::car_thumbs($car);
						?>
					
						<a href="<?= $images[0] ?>" rel="car" class="fancy"><img height="120" width="163" src="<?= $thumbs[0] ?>" alt="<?= $car[merk]." ".$car[model]." ".$car[type] ?>" /></a>
						<a href="<?= $images[0] ?>" rel="car" class="viewfull fancy"></a>
						<?
						if($car[verkocht] == 'j')
							echo '<div class="sold '.$color.'"></div>';
						?>
			
						<div class="thumbs">
						
							<?
							array_shift($thumbs);
							array_shift($images);
							foreach($thumbs as $num => $thumb)
								echo '<a href="'.$images[$num].'" rel="car" class="fancy"><img width="79" src="'.$thumb.'" alt="'.$car[merk]." ".$car[model]." ".$car[type].'" /></a> ';
							?>
						
						</div>
					
					</div>
					
					<div class="right">
					
						<div class="label <?= $color ?>">
				
							<div class="right"><div class="corner"></div></div>
						
							<p>&euro; <?= core::car_price($car) ?> ,-</p>
						
							<div class="shadow"></div>
							
						</div>
					
						<div class="details">
						
							<p class="textfit" rel="514"><?= $car[merk]." ".$car[model]." ".$car[type] ?></p><br />
							
							<div class="col">
			
								<strong>Model:</strong> 			<?= $car[model] 		?><br />
								<strong>Carosserievorm:</strong> 	<?= $car[carrosserie] 	?><br />
								<strong>Brandstof:</strong> 		<?= core::string("brandstof ".$car[brandstof]) ?>
							
							</div>
							
							<div class="col">
							
								<strong>Bouwjaar:</strong> 			<?= $car[bouwjaar] 		?><br />
								<strong>Transmissie:</strong> 		<?= core::string("transmissie ".$car[transmissie]) 	?><br />
								<strong>Aantal deuren:</strong> 	<?= $car[aantal_deuren] ?>
							
							</div>
							
							<div class="col">
							
								<strong>Kilometerstand:</strong> 	<?= $car[tellerstand].' '.core::string("eenheden ".$car[tellerstand_eenheid]) ?><br />
								<strong>Cilinderinhoud:</strong> 	<?= $car[cilinderinhoud] 							?> cc<br />
								<strong>Kleur:</strong>  			<?= $car[basiskleur].' '.$car[laksoort]	?>
							
							</div>
							
							<div class="greentext2">
								
								<?php 
								if ($color == "green" && $car["newprice"]!=1) {
								print("Green Deal<br/>Mileubewuste keus");
								}
								?>
								
							</div>
							
			
							
							
						
						</div>
						
						<div class="share">
						
							<a href="#" class="toggle_fav" title="Favorieten" data-car="<?=$car[voertuignr]?>"><img src="img/icon_fav<?= $favorites[$car[voertuignr]] ? '_min' : '' ?>.jpg" rel="fav" alt="Favorieten" /></a>
							<a href="<?= social::share_url('twitter', $car[merk].' '.$car[model].' op Autoservicehaarlem.nl',ROOT.core::car_url($car)) ?>" target="_blank" title="Deel op Twitter">	<img src="img/icon_twit.jpg" alt="Twitter" 	 /></a>
							<a href="<?= social::share_url('facebook',$car[merk].' '.$car[model].' op Autoservicehaarlem.nl',ROOT.core::car_url($car)) ?>" target="_blank" title="Deel op Facebook"> <img src="img/icon_fb.jpg"	 alt="Facebook"	 /></a>
							
						</div>
						
						<div class="description">
						
							<h2>opties</h2>
							
							<p><?= str_replace('*',' ',preg_replace('/(,([^\s]))+/'," <span class=\"bull\">&bull;</span> $2",$car[accessoires])) ?></p>
							
							<h2>Opmerkingen</h2>
						
							<p><?= stripslashes(str_replace('\r\n',' ',$car[opmerkingen])) ?></p>
						
							<?
							if(sql::exists("nap",array("car"=>$car[voertuignr])))
								$nap = sql::fetch("object","nap","WHERE `car` = '".$car[voertuignr]."'")->file;
							else
								$nap = $car[nap_weblabel] == 'n' ? false : 'none';
							?>
							<div class="certificates">
								
								<?	
								if($nap == 'none')
									echo '<img src="img/other/nap_small.png" alt="Nationale auto pas" />';
								elseif($nap)
									echo '<a href="'.$nap.'" target="_blank">bekijk nap<img src="img/other/nap_small.png" alt="Nationale auto pas" /></a>';
								?>
			
								<a href="http://www.rdw.nl/" target="_blank"><img src="img/other/rdw_small.png" alt="rdw" /></a>
							
							</div>
						
						</div>
						
						<form class="contact ajax" action="ajax/contact.php">
						
							<h2>Neem contact op <img src="img/icon_contact.jpg" alt="neem contact op" /></h2>
							
							<input type="hidden" name="car" value="<?= $car[voertuignr] ?>" />
							
							<input type="text" 	class="clickclear" name="name" 		value="voornaam" /> 
							<input type="text" 	class="clickclear" name="lastname" 	value="achternaam" />
												
							<input type="text" 	class="clickclear" name="phone" 		value="telefoonnummer" />
							<input type="text" 	class="clickclear" name="email" 		value="e-mailadres" />
							
							<textarea name="msg" class="clickclear">Vraag of opmerking</textarea>	
							
							<input type="submit" value="Verstuur bericht" />	
			
							<!--
			<div class="alabel">
							
								<?php
								if($color == "green") {
								print("<img src='http://www.autoservicehaarlem.nl/img/mediablock/alabel.jpg'/>");
								}
								?>
							
							
							</div>	
			-->			
						
						</form>
					
					</div>
					
					<div class="clear"></div>
					
				</div>
			
				<div class="options bottom">
			
					<span class="left long">
					
						<h3>Alternatieve auto's</h3>
						
					</span>
					
					<span class="right">
					
						<a href="<?= core::car_url($prev) ?>">&lt; Vorige</a>
					
						<span class="divider"></span>
						
						<a href="<?= core::page_url('file','sub/voorraad.php') ?>">overzicht</a>
						
						<span class="divider"></span>
						
						<a href="<?= core::car_url($next) ?>">Volgende &gt;</a>
					
					</span>
					
				</div>
				
				<?
				$cars = core::get_similar($car,'cars','voertuignr','merk model type carrosserie milieu_bewust',4,'`merk`,`model` ASC');
				core::draw_cars(4,$cars);
				?>
			
				<div class="clear"></div>
			
			</div>
		</div>
	</div>
</div>