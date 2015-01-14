<?
require_once __DIR__.'/../../php/_core.php';




class mobile_core extends core {

		// Calculate pages
	// ---------------
 
	static function calc_sections(){

		self::$pages = sql::fetch("array","m_sections","WHERE `active` = 1".(defined('LANGUAGE') ? " && `language` = '".LANGUAGE."'" : ''));
 
		if(sql::exists("sections",array("slug"=>$_GET['sub'])))
			list(self::$cur_page) = sql::fetch("array","m_sections","WHERE `slug` = '".sql::escape($_GET['sub'])."'");
		elseif(sql::exists("sections",array("slug"=>str_replace('/','',$_GET['section']))))
			list(self::$cur_page) = sql::fetch("array","m_sections","WHERE `slug` = '".sql::escape(str_replace('/','',$_GET['section']))."'");
		else
			list(self::$cur_page) = sql::fetch("array","m_sections","WHERE `".self::$default_page[0]."` = '".self::$default_page[1]."'");
			
		if(!is_array(self::$cur_page))
			list(self::$cur_page) = sql::fetch("array","m_sections","ORDER BY `id` ASC LIMIT 1");
 
	}

	

	static function draw_cars($rowlimit = 3,$cars = false,$forcetype = false, $start_limit = 0){
	
		$add_container = true;
	
		if($cars === false)
			$cars = sql::fetch("array","cars","WHERE `active` = 1 ORDER BY `created_at` DESC");
			
		if(!is_numeric($rowlimit) || $rowlimit < 1)
			$rowlimit = 1;
			
		if(count($cars) == 0 && $start_limit == 0){
		
			echo '<div class="nofound">Geen auto\'s gevonden.</div>';
			return;
			$add_container = false;
		
		} elseif(count($cars) == 0) {
		
			$add_container = false;
			
		}
			
		foreach($cars as $car){
		
			$color       = $car[milieu_bewust] ? 'green'
											   : 'orange';
	
			list($thumb) = self::car_thumbs($car);
			$favorites   = json_decode($_COOKIE['favorites']);
			
			if(is_object($favorites))
				$favorites = get_object_vars($favorites);
			?>
			<div class="car list">
				
				<a href="<?= self::car_url($car) ?>" class="full" target="_top"></a>
				
				<?= $car[verkocht] == 'j' ? '<div class="sold '.$color.'"></div>' : '' ?>
				<?= $car[newprice] == 1 ? '<div class="newprice"></div>' : '' ?>
				
				<img src="<?= $thumb ?>" alt="<?= $car[merk].' '.$car[model] ?>" />
				
				<div class="details">
				
					<p><?= $car[merk].' '.$car[model].' '.$car[type] ?> <br /></p>
					<br />
									
					<p class="subtext">
					Brandstof:		<?= self::string("brandstof ".$car[brandstof]) ?>
					 | 		
					Bouwjaar: 			<?= $car[bouwjaar] ?></p>

				</div>
				
				<div class="label <?= $color ?>">
		
					<p>&euro; <?= self::car_price($car) ?> ,-</p>
					
				</div>			

				<div class="more_info">
					meer informatie
				</div>
				
			</div>	
			<?
		}

		if($add_container) {
			?>
			<div class="ajax_container" rel="<?=$start_limit+10?>"></div>
			<?
		}
	}

}

?>