<?
require_once __DIR__.'/../../php/_core.php';

class mobile_core extends core {
    
	// Calculate pages
	// ---------------
	static function calculate_mob(){
 
		define(CURRENT_URL,	'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
 
		sql::connect();
 
		if(!headers_sent() && count(self::$languages) > 1)
			self::calc_language();
 
		self::calc_mob_sections();
		self::calc_metadata();
 
	}
	
	
	static function calc_mob_sections(){

		self::$pages = sql::fetch("array","m_sections","WHERE `active` = 1".(defined('LANGUAGE') ? " && `language` = '".LANGUAGE."'" : ''));
 
		if(sql::exists("m_sections",array("slug"=>$_GET['sub']))) {
			list(self::$cur_page) = sql::fetch("array","m_sections","WHERE `slug` = '".sql::escape($_GET['sub'])."'");
		} elseif(sql::exists("m_sections",array("slug"=>str_replace('/','',$_GET['section'])))) {
			list(self::$cur_page) = sql::fetch("array","m_sections","WHERE `slug` = '".sql::escape(str_replace('/','',$_GET['section']))."'");
		} else {
			list(self::$cur_page) = sql::fetch("array","m_sections","WHERE `".self::$default_page[0]."` = '".self::$default_page[1]."'");
		}	
		if(!is_array(self::$cur_page))
			list(self::$cur_page) = sql::fetch("array","m_sections","ORDER BY `id` ASC LIMIT 1");
	}

	

	static function draw_cars($rowlimit = 3,$cars = false,$forcetype = false, $start_limit = 0,$add_container=true){
		
		if($cars === false)
			$cars = sql::fetch("array","cars","WHERE `active` = 1 ORDER BY `created_at` DESC");
			
		if(!is_numeric($rowlimit) || $rowlimit < 1)
			$rowlimit = 1;
			
		if(count($cars) == 0 && $start_limit == 0){
		
			echo '<div class="ajax_container">
    				<div class="temp" style="line-height:50px;">
    					Geen auto\'s gevonden.
    				</div>
    			  </div>';
                
			return;
		
		}elseif(count($cars) == 0)		
			$add_container = false;
			
		foreach($cars as $car){
		
			$color       = $car[milieu_bewust] ? ' green'
											   : ' ';
	
			list($thumb) = self::car_thumbs($car);
			$favorites   = json_decode($_COOKIE['favorites']);
			
			if(is_object($favorites))
				$favorites = get_object_vars($favorites);
			?>
			<div class="car<?=$color;?>">

				<img width="111" height="82" src="../../<?= $thumb ?>" alt="<?= $car[merk].' '.$car[model] ?>" />
                
                <?
                if($car[verkocht] == 'j')
                    echo '<div class="sold '.$color.'"></div>';
                ?>
				
				<div class="details">
				
					<p><?= $car[merk].' '.$car[model].' '.$car[type] ?></p>
					
					<span class="year">Bouwjaar: <?= $car[bouwjaar] ?></span>
					
					<div class="label">
						
						<div class="text">&euro; <?= self::car_price($car) ?>,-</div>
						<div class="corner_right"></div>
						
					</div>
                    
					<? 
                    if($car[newprice] == 1) { 
                    ?>
					<div class="new_label">
						
						<div class="text">new price</div>
						<div class="corner_right"></div>
						
					</div>
					<? 
                    } 
                    ?>
					
					<span class="info">meer info</span>
				
				</div>
				
				<a class="stretch ajax_link" href="detail" data-car="<?= $car[voertuignr] ?>" data-green="<?= $car[milieu_bewust] ?>"></a>

			</div>
			
			<?/*
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
				
			</div>*/?>
			<?
		}

		if($add_container) {
			?>
			<div class="ajax_container" rel="<?=$start_limit+10?>">
				<div class="temp">
					<img src="ajax/load.gif" />
				</div>
			</div>
			<?
		}
	}

}

?>