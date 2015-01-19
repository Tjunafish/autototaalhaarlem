<?
$log = fopen('../log.txt','a');

fwrite($log,"[".date('H:i:s d-m-Y')."] REQUEST MADE FROM: ".$_SERVER['REMOTE_ADDR']."\n");
// var_dump($_SERVER['REMOTE_ADDR']);
$allowed_ip = array(
	'82.94.237.8', // live
	'82.94.240.8' // test (sandbox)
);

if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ip)) {
	fwrite($log,"REQUEST DENIED: INVALID SERVER\n\n");
	fclose($log);
	exit;
}

require 'php/config.php';

sql::insert("log",array("content"=>json_encode($_POST)));

// Thumbnailing function

function thumb_from_web($source,$target,$width,$height){

	list($old_w,$old_h) = getimagesize($source);	
	$image 				= imagecreatefromjpeg($source);
	$result				= imagecreatetruecolor($width,$height);
	
	imagecopyresampled($result,$image,0,0,0,0,$width,$height,$old_w,$old_h);
	imagejpeg($result,$target,100);
	
	imagedestroy($image);
	imagedestroy($result);

}

// Request POST data

$action = $_POST['actie'];

$values = array('voertuignr_hexon'						=> $_POST['voertuignr_hexon'],
				'voertuignr'							=> $_POST['voertuignr'],
				'voertuignr_klant'						=> $_POST['voertuignr_klant'],
				'kenteken'								=> $_POST['kenteken'],
				'voertuigsoort'							=> $_POST['voertuigsoort'],
				'klantnummer'							=> $_POST['klantnummer'],
				'merk'									=> $_POST['merk'],
				'model'									=> $_POST['model'],
				'type'									=> $_POST['type'],
				'carrosserie'							=> $_POST['carrosserie'],
				'aantal_deuren'							=> $_POST['aantal_deuren'],
				'tellerstand'							=> $_POST['tellerstand'],
				'tellerstand_eenheid'					=> $_POST['tellerstand_eenheid'],
				'transmissie'							=> $_POST['transmissie'],
				'brandstof'								=> $_POST['brandstof'],
				'aantal_versnellingen'					=> $_POST['aantal_versnellingen'],
				'btw_marge'								=> $_POST['btw_marge'],
				'nieuw'									=> $_POST['nieuw'],
				'verwacht'								=> $_POST['verwacht'],
				'laktint'								=> $_POST['laktint'],
				'basiskleur'							=> $_POST['basiskleur'],
				'kleur'									=> $_POST['kleur'],
				'laksoort'								=> $_POST['laksoort'],
				'bouwjaar'								=> $_POST['bouwjaar'],
				'datum_deel_1'							=> $_POST['datum_deel_1'],
				'datum_deel_1a'							=> $_POST['datum_deel_1a'],
				'datum_deel_1b'							=> $_POST['datum_deel_1b'],
				'prijzen_in_ex'							=> $_POST['prijzen_in_ex'],
				'verkoopprijs_particulier'				=> $_POST['verkoopprijs_particulier'],
				'actieprijs'							=> $_POST['actieprijs'],
				'verkoopprijs_handel'					=> $_POST['verkoopprijs_handel'],
				'opmerkingen'							=> $_POST['opmerkingen'],
				'opmerkingen_engels'					=> $_POST['opmerkingen_engels'],
				'opmerkingen_duits'						=> $_POST['opmerkingen_duits'],
				'opmerkingen_frans'						=> $_POST['opmerkingen_frans'],
				'opmerkingen_spaans'					=> $_POST['opmerkingen_spaans'],
				'opmerkingen_portugees'					=> $_POST['opmerkingen_portugees'],
				'opmerkingen_italiaans'					=> $_POST['opmerkingen_italiaans'],
				'opmerkingen_grieks'					=> $_POST['opmerkingen_grieks'],
				'opmerkingen_russisch'					=> $_POST['opmerkingen_russisch'],
				'opmerkingen_roemeens'					=> $_POST['opmerkingen_roemeens'],
				'opmerkingen_hongaars'					=> $_POST['opmerkingen_hongaars'],
				'opmerkingen_pools'						=> $_POST['opmerkingen_pools'],
				'opmerkingen_tsjechisch'				=> $_POST['opmerkingen_tsjechisch'],
				'opmerkingen_bulgaars'					=> $_POST['opmerkingen_bulgaars'],
				'opmerkingen_kroatisch'					=> $_POST['opmerkingen_kroatisch'],
				'opmerkingen_handel'					=> $_POST['opmerkingen_handel'],
				'opmerkingen_handel_engels'				=> $_POST['opmerkingen_handel_engels'],
				'opmerkingen_handel_duits'				=> $_POST['opmerkingen_handel_duits'],
				'opmerkingen_handel_frans'				=> $_POST['opmerkingen_handel_frans'],
				'opmerkingen_handel_spaans'				=> $_POST['opmerkingen_handel_spaans'],
				'opmerkingen_handel_portugees'			=> $_POST['opmerkingen_handel_portugees'],
				'opmerkingen_handel_italiaans'			=> $_POST['opmerkingen_handel_italiaans'],
				'opmerkingen_handel_grieks'				=> $_POST['opmerkingen_handel_grieks'],
				'opmerkingen_handel_russisch'			=> $_POST['opmerkingen_handel_russisch'],
				'opmerkingen_handel_roemeens'			=> $_POST['opmerkingen_handel_roemeens'],
				'opmerkingen_handel_hongaars'			=> $_POST['opmerkingen_handel_hongaars'],
				'opmerkingen_handel_pools'				=> $_POST['opmerkingen_handel_pools'],
				'opmerkingen_handel_tsjechisch'			=> $_POST['opmerkingen_handel_tsjechisch'],
				'opmerkingen_handel_bulgaars'			=> $_POST['opmerkingen_handel_bulgaars'],
				'opmerkingen_handel_kroatisch'			=> $_POST['opmerkingen_handel_kroatisch'],
				'apk_tot'								=> $_POST['apk_tot'],
				'apk_bij_aflevering'					=> $_POST['apk_bij_aflevering'],
				'massa'									=> $_POST['massa'],
				'max_trekgewicht'						=> $_POST['max_trekgewicht'],
				'cilinderinhoud'						=> $_POST['cilinderinhoud'],
				'aantal_cilinders'						=> $_POST['aantal_cilinders'],
				'vermogen_motor'						=> $_POST['vermogen_motor'],
				'aantal_zitplaatsen'					=> $_POST['aantal_zitplaatsen'],
				'bpm_bedrag'							=> $_POST['bpm_bedrag'],
				'interieurkleur'						=> $_POST['interieurkleur'],
				'bekleding'								=> $_POST['bekleding'],
				'aantal_sleutels'						=> $_POST['aantal_sleutels'],
				'aantal_handzenders'					=> $_POST['aantal_handzenders'],
				'code_pas_sleutel'						=> $_POST['code_pas_sleutel'],
				'nap_weblabel'							=> $_POST['nap_weblabel'],
				'onderhoudsboekjes'						=> $_POST['onderhoudsboekjes'],
				'locatie_voertuig'						=> $_POST['locatie_voertuig'],
				'exportprijs'							=> $_POST['exportprijs'],
				'meeneemprijs'							=> $_POST['meeneemprijs'],
				'opknapkosten'							=> $_POST['opknapkosten'],
				'kosten_rijklaar'						=> $_POST['kosten_rijklaar'],
				'gemiddeld_verbruik'					=> $_POST['gemiddeld_verbruik'],
				'schadevoertuig'						=> $_POST['schadevoertuig'],
				'consignatie'							=> $_POST['consignatie'],
				'demovoertuig'							=> $_POST['demovoertuig'],
				'fabrieksgarantie_tot'					=> $_POST['fabrieksgarantie_tot'],
				'merkgarantie'							=> $_POST['merkgarantie'],
				'bovag_garantie'						=> $_POST['bovag_garantie'],
				'garantie_maanden'						=> $_POST['garantie_maanden'],
				'garantie_km'							=> $_POST['garantie_km'],
				'opmerkingen_garantie'					=> $_POST['opmerkingen_garantie'],
				'wegenbelasting_kwartaal_min'			=> $_POST['wegenbelasting_kwartaal_min'],
				'wegenbelasting_kwartaal_max'			=> $_POST['wegenbelasting_kwartaal_max'],
				//'verkocht'								=> $_POST['verkocht'],
				'wielbasis'								=> $_POST['wielbasis'],
				'laadvermogen'							=> $_POST['laadvermogen'],
				'gvw'									=> $_POST['gvw'],
				'aantal_assen'							=> $_POST['aantal_assen'],
				'lengte'								=> $_POST['lengte'],
				'breedte'								=> $_POST['breedte'],
				'hoogte'								=> $_POST['hoogte'],
				'nieuwprijs'							=> $_POST['nieuwprijs'],
				'netto_catalogusprijs'					=> $_POST['netto_catalogusprijs'],
				'verkocht_datum'						=> $_POST['verkocht_datum'],
				'eigen_garantielabel'					=> $_POST['eigen_garantielabel'],
				'verlengd'								=> $_POST['verlengd'],
				'verhoogd'								=> $_POST['verhoogd'],
				'assen_aangedreven'						=> $_POST['assen_aangedreven'],
				'datum_binnenkomst'						=> $_POST['datum_binnenkomst'],
				'verhuur'								=> $_POST['verhuur'],
				'meldcode'								=> $_POST['meldcode'],
				'land'									=> $_POST['land'],
				'topsnelheid'							=> $_POST['topsnelheid'],
				'tankinhoud'							=> $_POST['tankinhoud'],
				'max_trekgewicht_ongeremd'				=> $_POST['max_trekgewicht_ongeremd'],
				'acceleratie'							=> $_POST['acceleratie'],
				'max_dakbelasting'						=> $_POST['max_dakbelasting'],
				'inhoud_laadruimte_banken_weggeklapt'	=> $_POST['inhoud_laadruimte_banken_weggeklapt'],
				'verbruik_stad'							=> $_POST['verbruik_stad'],
				'verbruik_snelweg'						=> $_POST['verbruik_snelweg'],
				'co2_uitstoot'							=> $_POST['co2_uitstoot'],
				'fijnstof_uitstoot'						=> $_POST['fijnstof_uitstoot'],
				'munteenheid'							=> $_POST['munteenheid'],
				'vin'									=> $_POST['vin'],
				'emissieklasse'							=> $_POST['emissieklasse'],
				'energielabel'							=> $_POST['energielabel'],
				'accessoires'							=> $_POST['accessoires'],
				'afbeeldingen'							=> $_POST['afbeeldingen']);
				
$post = array();

foreach($values as $key => $val)
	$post[$key] = sql::escape($val); // Escape all input				

list($bigthumb)     = explode(",",$post['afbeeldingen']);
$post['bigthumb']   = $bigthumb;
$post['voertuignr'] = ltrim($post['voertuignr'],0);
$post['sortname']   = core::slug($post['merk'].' '.$post['model'].' '.$post['type'],' ');

$exists = sql::exists("cars",array("voertuignr"=>$post['voertuignr']));

if($action == 'add' && $exists) // If action is add but the car exists, change action to 'change'
	$action = 'change';

if($action == 'change' && !$exists) // If action is change but the car doesn't exist, change action to 'add'
	$action = 'add';
	
fwrite($log,'HEXON_CAR_ID: '.$post['voertuignr_hexon']."\n");
fwrite($log,'ACTION: '.$action."\n");

$thumbdir 	   = 'img/cars/';
$extra_thumbs  = array(
	'[car]_deal.jpg'		=> '392-294',				   
	'[car]_thumb.jpg'	=> '200-147', 
	'[car]_fb.jpg)' => '312,232'
);

$create_thumbs = false;
$post_social   = false;
$hexon         = sql::escape($post['voertuignr_hexon']);

if($action == 'add'){

	$create_thumbs = true;
	
	$result 	   = sql::insert("cars",$post);

 	list($car)	   = sql::fetch("array","cars","WHERE `voertuignr_hexon` = '".$hexon."'");
 	$post_social   = $car;	
	 			 
 	if($post['actieprijs'] != 0){

 		sql::insert("template_bdd",array("car" => $post['voertuignr_hexon'])); 		
 		sql::insert("bdd",array("voertuignr" => $post['voertuignr']));

	}
	
}elseif($action == 'change'){

	$create_thumbs     = true;
	unset($post['voertuignr_hexon']);
	
	$old_images        = sql::fetch("object","cars","WHERE `voertuignr_hexon` = '".$hexon."'")->afbeeldingen;	
	$result 	       = sql::update("cars",$post,"WHERE `voertuignr_hexon` = '".$hexon."'");
    
    list($post_social) = sql::fetch("array","cars","WHERE `voertuignr_hexon` = '".$hexon."'");
	
	if($post['actieprijs'] != 0 && !sql::exists("template_bdd",array("car" => $post['voertuignr_hexon']))) // Create newsletter for this car
 		sql::insert("template_bdd",array("car" => $post['voertuignr_hexon']));
 		
	if($post['actieprijs'] != 0 && !sql::exists("bdd",array("voertuignr" => $post['voertuignr'])))
		sql::insert("bdd",array("voertuignr" => $post['voertuignr']));

}elseif($action == 'delete'){

	$result 	   = sql::update("cars",array("active"=>'0'),"WHERE `voertuignr_hexon` = '".$hexon."'");
	
	/*foreach($extra_thumbs as $file => $null)	
		if($file != '[car]_thumb.jpg') // Skip _thumb for possible links to old newsletters
			if(file_exists($thumbdir.str_replace('[car]',$post['voertuignr'],$file)))
				unlink($thumbdir.str_replace('[car]',$post['voertuignr'],$file));
	
	$count = 0; 
	
	do { // Delete thumbnails for this car

		if(file_exists($thumbdir.$post['voertuignr'].'_'.$count.'.jpg')){
		
			unlink($thumbdir.$post['voertuignr'].'_'.$count.'.jpg');
			$stop = false;
			$count++;
		
		}else
			$stop = true;
	
	} while($stop = false);*/

}

if($create_thumbs){// && $old_images != $post['afbeeldingen']){

	fwrite($log,"CREATING THUMBNAILS\n");
	$images = explode(",",$post['afbeeldingen']);
	
	foreach($extra_thumbs as $target => $dimensions){
	
		list($x,$y) = explode("-",$dimensions);
		$target     = str_replace('[car]',$post['voertuignr'],$target);
		
		thumb_from_web($images[0],$thumbdir.$target,$x,$y);
	
	}
	
	for($i = 1;$i < count($images);$i++) // Skip first image
		thumb_from_web($images[$i],$thumbdir.$post['voertuignr'].'_'.$i.'.jpg',79,59);
		
	if(count($images) < 4)
		$post_social = false;
	else
		sql::update("cars",array("showticker"=>1),"WHERE `voertuignr_hexon` = '".$hexon."'"); 

}

if($post_social != false && $post_social['posted_to_social'] == '0'){

	$social = new social;
	
	$car = $post_social;      
    
    sql::update("cars",array("posted_to_social"=>1),"WHERE `id` = '".$car['id']."'");

    $social->set_fb_app_id('1408813742720787');
    $social->set_fb_app_secret('caf61b12ee4c09a5bed6294ea9e64cee');
    //$social->set_fb_app_token('0a307c7c197c877c24a372e88276e0d4');
    $social->set_fb_app_token('CAAUBTwKwhxMBADWEVPWKAGIejptH2ZAnTSELfjQm9pJtC7D81ZBC8dFvappdknytDLtdwkvY0lELthrbU5HUvHmojs0e2qtIR20ZCgMZCr7yg2ZCWBVYBa94XYUdaZCtxC5iT26t3RkSVaZAUSRtIfLC8UMZC11ADXE7XGmvDPb4Wfhqzvv9eOc2yl3HH7RzymAZD');

    //$social->set_fb_app_id('226021717452283');
	//$social->set_fb_app_secret('caf61b12ee4c09a5bed6294ea9e64cee');
	//$social->set_fb_app_token('AAACh5lXk3qABAKTJPdddb44eGvyKTZBRkOZASoVZAVI0Wjm08Atf9Jcx8Xb6pDlgglzeEVhmXoZBZArsPrMuPlFH6PbFWzsSDxwx6b1Bs8gZDZD');
	
	fwrite($log,"POSTING TO FACEBOOK\n");	
			
	$social->fb_wall_post('659924380731374', $car['merk'].' '.$car['model'].' '.$car['type'],'http://www.autototaalhaarlem.nl/'.core::car_url($car));
	
	$social->set_twit_key('24Y6GVsvuxELT8RxHoZ2dQ');
	$social->set_twit_secret('Kf1A4gIUbJmvu6jlesjikgSskr9wuFD35dqoMG8kAU');
	$social->set_twit_token('125346402-JfJuqFq3TmOREm95cvD6LHNMdj1BAwgMq5KsvIso');
	$social->set_twit_token_secret('xZ3dCzJ7eRfrsBKM9CI8a1q8ym0wiZM4jsZkZCPsP8');
	
	fwrite($log,"POSTING TO TWITTER\n");
	
	//$social->twitter_post($car[merk].' '.$car[model].' '.$car[type],'http://www.autoservicehaarlem.nl/'.core::car_url($car));

}

fwrite($log,"\n");
fclose($log);

echo 1; // Tell Autodata the import was successful
?>
