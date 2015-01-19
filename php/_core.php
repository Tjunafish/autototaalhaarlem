<?
// --------------------------------------
// _core.php
// main engine class
// --------------------------------------
 
class core {
 
	public static $favicon;
 
	public static $jslibs;
 
	public static $mail_name;
	public static $mail_from;
	public static $mail_reply;
 
	public static $metatitle;
	public static $metadesc;
	public static $metakeys;
	public static $fbimg;
 
	public static $pages;
	public static $default_page;
	public static $cur_page;
	
	public static $log;
	
	public static $languages;
	public static $default_lang;
	
	public static $email_header;
	public static $email_footer;
	
	public static $social;
 
	// Calculate everything
	// --------------------
 
	static function calculate(){

		define('CURRENT_URL',	'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
 
		sql::connect();
 
		if(!headers_sent() && count(self::$languages) > 1)
			self::calc_language();
 
		self::calc_sections();
		self::calc_metadata();
 
	}
 
	// Calculate language
	// ------------------
 
	static function calc_language(){
 
		if($_GET['language'] && in_array($_GET['language'],self::$languages))
			$lang = $_GET['language'];
 
		if(!$_GET['language'] && $_COOKIE['language'] && in_array($_COOKIE['language'],self::$languages))	
			$lang = $_COOKIE['language'];
		elseif(!$_GET['language']){
 
			$langs = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
 
			foreach($langs as $l)				
				if(in_array(substr($l,0,2),self::$languages)){
 
					$lang = substr($l,0,2);
					break;
 
				}
 
		}
 
		if(!$lang)
			$lang = self::$default_lang;
 
		$locales = array("nl"	=> "nl_NL",
						 "en"	=> "en_EN");
 
		if($lang && $lang != $_COOKIE['language'])			
			self::set_language($lang);
 
		define('LANGUAGE',$lang);
 
		setlocale(LC_TIME,$locales[$lang]);
 
	}
 
	// Calculate pages
	// ---------------
 
	static function calc_sections(){

		self::$pages = sql::fetch("array","sections","WHERE `active` = 1".(defined('LANGUAGE') ? " && `language` = '".LANGUAGE."'" : ''));
 
		if(sql::exists("sections",array("slug"=>$_GET['sub'])))
			list(self::$cur_page) = sql::fetch("array","sections","WHERE `slug` = '".sql::escape($_GET['sub'])."'");
		elseif(sql::exists("sections",array("slug"=>str_replace('/','',$_GET['section']))))
			list(self::$cur_page) = sql::fetch("array","sections","WHERE `slug` = '".sql::escape(str_replace('/','',$_GET['section']))."'");
		else
			list(self::$cur_page) = sql::fetch("array","sections","WHERE `".self::$default_page[0]."` = '".self::$default_page[1]."'");
			
		if(!is_array(self::$cur_page))
			list(self::$cur_page) = sql::fetch("array","sections","ORDER BY `id` ASC LIMIT 1");

	}
 
	// Set the language
	// ----------------
 
	static function set_language($l){
 
		if(!headers_sent()){
 
			setcookie('language',$l,time()+60*60*24*365,"/");
 
			header('Location: '.str_replace('&language='.$l,'',CURRENT_URL));
			header('Content-Length: 0');
			exit;
 
		}
 
	}
 
	// Draw the metadata
	// -----------------
 
	static function draw_metadata(){
 
		if(self::$metatitle)
			echo '<title>'.self::$metatitle.'</title>'."\n";
 
		if(self::$metadesc)
			echo '<meta name="description"			content="'.utf8_encode(self::$metadesc).'" />'."\n";
 
		if(self::$metakeys)
			echo '<meta name="keywords"   			content="'.utf8_encode(self::$metakeys).'" />'."\n";
 
		if(self::$fbimg)
			echo '<meta property="og:image" 		content="'.self::$fbimg.'" />
    			  <meta property="og:site_name" 	content="'.utf8_encode(self::$metatitle).'" />'."\n";
    			  
  		if(self::$favicon)
  			echo '<link rel="shortcut icon" 		href="'.self::$favicon.'" />'."\n";

        echo '<link rel="apple-touch-icon" sizes="120x120" href="m_120x120-precomposed.png" />';

	}
 
	// Include javascript libraries
	// ----------------------------
 
	static function draw_js_libs(){
 
		foreach(self::$jslibs as $lib)
			switch($lib){
 
				case "jquery":
				echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>';
				break;
 
				case "jquery_ui":
				echo '<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js" type="text/javascript"></script>';
				break;
 
				case "webfont":
				echo '<script src="//ajax.googleapis.com/ajax/libs/webfont/1.0.22/webfont.js" type="text/javascript"></script>';
				break;
 
				case "gmaps":
				echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>';
				break;
 
			}
 
	}
 
	// Create slug
	// -----------
 
	static function slug($input,$divide='-'){
	
		$string = html_entity_decode($input,ENT_COMPAT,"UTF-8");
		
		setlocale(LC_CTYPE, 'en_US.UTF-8');
		$string = iconv("UTF-8","ASCII//TRANSLIT",$string);
 		
		return    strtolower(str_replace(' ',$divide,trim(preg_replace('/[^a-zA-Z0-9]+/',' ',$string))));
 
	}
 
	// Parse urls from text
	// --------------------
 
	static function parse_urls($text,$extra=''){
 
		$text = preg_replace('#</?a(\s[^>]*)?>#i', '', $text);
		return  preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a '.$extra.' target="_blank" href="http://$2$3">$2$4</a>', $text);
 
	}
 
	// Check if current page is included
	// ---------------------------------
 
	static function is_included(){
 
		return !(strtolower(realpath(__FILE__)) == strtolower(realpath($_SERVER['SCRIPT_FILENAME'])));
 
	}
	
	// Return page url based on field and value
	// ----------------------------------------
	
	static function page_url($field,$val){
	
		$page = sql::fetch("object","sections","WHERE `".sql::escape($field)."` = '".sql::escape($val)."'");
		$url  = $page->slug;
		
		if($page->parent != 0)
			$url = sql::fetch("object","sections","WHERE `id` = '".$page->parent."'")->slug.'/'.$url;
			
		return $url;
	
	}
	
	// Format numbers
	// --------------
	
	static function num_format($input){
	
		return number_format($input,0,'.','.');
	
	}
	
	// Calculate item pages
	// --------------------
	// current - current page number
	// perpage - amount of items per page
	// total   - total amount of items
	// limit   - limit amount of returned pages (i.e. for 4 5 6 7 8, limit would be 5), limit lower than 3 won't have an effect
	
	static function calc_pages($current,$perpage,$total,$limit=0){
	
		$return = array();
	
		if(!is_numeric($current) || $current < 1)
			$current = 1;
			
		$return['perpage'] = $perpage;
		$return['total']	 = ceil($total / $perpage);
		
		if($return['total'] < 1)
			$return['total'] = 1;
		
		if($current > $return['total'])
			$current = $return['total'];
			
		$return['current'] = $current;
		
		if($limit > 0){
		
			$start = $current > $limit - 2	? $current - 2
											: 1;
											
			if($start < 1)
				$start = 1;
								  
			$end   = $current < $total - 2 	? $current + 2
											: $return['total'];
			
			if($end > $return['total'])
				$end = $return['total'];
			
			$diff  = $end - $start;
			
			if($diff < $limit - 1){
			
				if($current - $start > 1)
					$start -= $limit - 1 -$diff;
				else
					$end   += $limit - 1 -$diff;
			
			}
			
			if($start < 1)
				$start = 1;
				
			if($end > $return['total'])
				$end = $return['total'];
			
			$return['start'] = $start;
			$return['end']   = $end;			
		
		}else{
		
			$return['start'] = 1;
			$return['end']   = $return['total'];
		
		}
		
		$minlimit  = $perpage * ($current - 1);
		
		if($minlimit < 0)
			$minlimit = 0;
			
		$return['limit'] = $minlimit.','.$perpage;
		
		return $return;
	
	}
	
	// Validate function
	// -----------------
	// $input syntax:
	// $input[key] = array("value","check1 check2","defaultvalue");
	// where check1, check2 etc are one of the following:
	// empty, email
	// defaultvalue is not required but if provided a check will be done if value is not equal to it
 
	static function validate($input){
	 
		$return = array();
 
		foreach($input as $key => $i){
 
			$error = false;
 
			list($val,
			 	 $checks,
	  			 $default) = $i;
 
 	 		$val 	 = trim($val);
 	 		$default = trim($default);
 	 		
 	 		if(!preg_match('/empty/',$checks) && (empty($val) || $val == $default))
 	 			continue;
 
			foreach(explode(" ",$checks) as $check)
				switch($check){
				
 					// NOT EMPTY VALIDATION //
 					
					case "empty":
 
					if(empty($val))						
						$error = $check;
 
					break;
					
 					// E-MAIL ADDRESS VALIDATION //
 					
					case "email":
 
					if(!filter_var($val,FILTER_VALIDATE_EMAIL))
						$error = $check;
 
					break;
					
					// LICENSE PLATE VALIDATION //
					
					case "license_plate_nl": // The Netherlands
						
					$patterns = array('[a-z]{2}-?[0-9]{2}-?[0-9]{2}', // XX-99-99 / XX9999
									  '[0-9]{2}-?[0-9]{2}-?[a-z]{2}', // 99-99-XX / 9999XX
									  '[a-z]{2}-?[0-9]{2}-?[a-z]{2}', // XX-99-XX / XX99XX
									  '[a-z]{2}-?[a-z]{2}-?[0-9]{2}', // XX-XX-99 / XXXX99
									  '[0-9]{2}-?[a-z]{2}-?[a-z]{2}', // 99-XX-XX / 99XXXX
									  '[0-9]{2}-?[a-z]{3}-?[0-9]{1}', // 99-XXX-9 / 99XXX9
									  '[0-9]{1}-?[a-z]{3}-?[0-9]{2}', // 9-XXX-99 / 9XXX99
									  '[a-z]{2}-?[0-9]{3}-?[a-z]{1}', // XX-999-X / XX999X
									  '[a-z]{1}-?[0-9]{3}-?[a-z]{2}');// X-999-XX / X999XX
					
					if(!preg_match('/^('.implode('|',$patterns).')$/i',$val))
						$error = $check;
					
					break;
					
					// PHONE NUMBER VALIDATION //
					
					case "phone_nl": // The Netherlands
					
					$val 	   = preg_replace('/[^0-9]/','',$val);
					$check_num = 10;
					
					if(substr($val,0,2) == '31')
						$check_num = 11;
						
					if(strlen($val) != $check_num)
						$error = $check;
					
					break;					

				}
  
			if(!empty($default) && $val == $default)
				$return[$key] = 'defaultvalue';
 
			if($error != false)
				$return[$key] = $error;
 
		}
 
 		return $return;
 
	}
	
	// Get neighbours (previous and next) from database entry
	// ------------------------------------------------------
	
	static function get_neighbours($item,$table,$field,$extra=""){
	
        $field = str_replace('`','',$field);
    
		if(is_object($item)){
		
			$item = get_object_vars($item);
			$type = 'object';
			
		}else
			$type = 'array';
			
		if(sql::num($table,sql::clean_where($extra)) == 1)
			$prev = $next = $item;
		else{
			
			$prev = sql::fetch('object',$table,"WHERE    `".$field."` != '".$item[$field]."' 
												&&       `".$field."` <  '".$item[$field]."' ".$extra." 
												ORDER BY `".$field."` DESC 
												LIMIT 1");
												
			$next = sql::fetch('object',$table,"WHERE    `".$field."` != '".$item[$field]."' 
												&&       `".$field."` >  '".$item[$field]."' ".$extra." 
												ORDER BY `".$field."` ASC  
												LIMIT 1");
			
			if(!$prev)
				$prev = sql::fetch('object',$table,"WHERE    `".$field."` != '".$item[$field]."' ".$extra."
													ORDER BY `".$field."` DESC 
													LIMIT 1");
			
			if(!$next)
				$next = sql::fetch('object',$table,"WHERE    `".$field."` != '".$item[$field]."' ".$extra."
			 										ORDER BY `".$field."` ASC 
			 										LIMIT 1");
			
			if($type == 'array'){
			
				$prev = get_object_vars($prev);
				$next = get_object_vars($next);
			
			}
		
		}
				
		return array($prev,$next);				
		
	}
	
	// Get similar from database entry
	// -------------------------------
	
	static function get_similar($item,$table,$key,$fields,$limit = 1,$order = false){
	
		if(!is_array($fields))
			$fields = explode(" ",$fields);
		
		if($limit < 1)
			$limit = 1;
			
		if($limit > sql::num($table,$extra))
			$limit = sql::num($table,$extra);
		
		foreach($fields as $field)
			$values[] = sql::escape($item[$field]);
			
		$backup['fields'] = $fields;
		$backup['values'] = $values;
		
		$return = array();
		$diff   = $limit;
		$keys   = array($item[$key]);
		
		while(count($return) < $limit && count($backup['fields']) > 0){
		
			if(count($fields) == 0){
			
				array_shift($backup['fields']);
				array_shift($backup['values']);
				
				$fields = $backup['fields'];
				$values = $backup['values'];
			
			}
				
			$query  = "WHERE ";
									
			foreach($keys as $k)
				$query .= "`".$key."` != '".$k."' && ";			
												
			$query .= implode(" && ",
					  array_map(function($x,$y){					  			
	  								return "`".$x."` = '".$y."'";					  			
   								},$fields,$values)).
					  " LIMIT ".$diff;				  					  
					  			
			$items  = sql::fetch("array",$table,$query);				
			$diff  -= count($items);
			
			foreach($items as $i){
			
				$keys[]   = $i[$key];
				$return[] = $i;
							
			}											  									  	
							  	
			array_pop($fields);
			array_pop($values);
		
		}
		
		return $return;		
	
	}
 
	// Send e-mail
	// -----------
 
	static function send_mail($to,$subject,$msg,$head=false){
 
        if(!self::$mail_name || !self::$mail_from)
            die('E-mail name and/or e-mail are not defined.');

        if(!self::$mail_reply)
            self::$mail_reply = self::$mail_from;
 
		if($head)
			$headers = $head;
		else
			$headers = 'From: 		'.self::$mail_name.' <'.self::$mail_from.">\r\n".
			   	   	   'Reply-To: 	'.self::$mail_reply."\r\n".
			  	  	   'X-Mailer: 	PHP/'."\r\nContent-type: text/html\r\n"; 
 
		mail($to,$subject,self::$email_header.$msg.self::$email_footer,$headers);
 
	}
	
	///////////////////////////
	// SITE CUSTOM FUNCTIONS //
	///////////////////////////
	
	static function draw_papertrail(){
	
		$trail   = array();
		$trail[] = array(sql::fetch("object","sections","WHERE `id` = 1")->title,
					     sql::fetch("object","sections","WHERE `id` = 1")->slug);
					   
 		if(core::$cur_page[id] != 1) 		
 			$trail[] = array(self::$cur_page['title'],$_GET['section']);
 			
		if(core::$cur_page['item_file'] == 'sub/detail.php' && $_GET['item']){
		
			$tmp   		 		= explode("-",$_GET['item']);	
			$nr    		 		= array_pop($tmp);
						
			list($car)   		= sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($nr)."'");
			
			if(!$car)
				list($car) 		= sql::fetch("array","cars","ORDER BY `created_at` DESC LIMIT 1");
				
			$trail[] = array($car['merk'].' '.$car['model'],self::car_url($car));
					
		}
	
		echo '<div id="papertrail"> U bevind zich: ';
		
		$last = end($trail);
		
		foreach($trail as $t)		
			if($t != $last)
				echo '<a href="'.$t[1].'">'.$t[0].'</a> | ';
			else
				echo $t[0];
		
		echo '</div>';
	
	}
	
	static function calc_metadata(){
	
		$extra = self::$cur_page['title'] ? ' - '.self::$cur_page['title'] : '';
		
		if(self::$cur_page['item_file'] == 'sub/detail.php' && $_GET['item']){
		
			$tmp   		 		= explode("-",$_GET['item']);	
			$nr    		 		= array_pop($tmp);
						
			list($car)   		= sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($nr)."'");
			
			if(!$car)
				list($car) 		= sql::fetch("array","cars","ORDER BY `created_at` DESC LIMIT 1");
			
			$extra 		 		= ' - '.$car['merk'].' '.$car['model'];
		
			list(self::$fbimg)  = self::car_thumbs_fb($car);
			self::$fbimg		= ROOT.self::$fbimg;

			self::$metadesc		= substr(str_replace('*','',preg_replace('/(\s*,\s*([^\s])\s*)+/',"$2, ",$car['accessoires'])),0,150);
			
			$keys				= array($car['merk'],
										$car['merk'].' '.$car['model'],
										$car['carrosserie'],
										self::string('transmissie '.$car['transmissie']),
										self::string('brandstof '.$car['brandstof']),
										$car['aantal_zitplaatsen'].' zitplaatsen',
										$car['aantal_deuren'].' deurs',
										($car['kleur'] ? $car['kleur'] : $car['basiskleur']),
										$car['bouwjaar']);
										
			self::$metakeys    .= ", ".implode(", ",$keys);
		
		}elseif(self::$cur_page['id'] == 9){ // Landingspages
		
			$domain = str_replace('http://','',$_SERVER[HTTP_REFERER]);
			$domain = str_replace('www.',	'',$domain);
			$domain = str_replace('/',		'',$domain);
			
			if(sql::exists("landing",array("domain"=>$domain)))
				list($landing) = sql::fetch("array","landing","WHERE `domain` = '".sql::escape($domain)."'");
			else
				$landing = false;
				
			if($landing)
				$extra = '- '.$landing['title'];
		
		}
		
		self::$metatitle = self::$metatitle.$extra;
	
	}
	
	static function draw_cars($rowlimit = 3,$cars = false,$forcetype = false){
/*
		$urlxxx = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$filtersql = "WHERE `active` = 1 && `milieu_bewust` = '1'";
		$sort  = implode(" ".$search[sort][dir].", ",explode(",",$search[sortsql]));

		if (false !== strpos($urlxxx,'milieubewust')) {
		    if (strpos($filtersql, "`milieu_bewust` = '1'") === false) {
			     $filtersql .= " && `milieu_bewust` = '1'";
		    } 
		   $cars  = sql::fetch("array","cars",$filtersql." ORDER BY `sortname` ASC LIMIT 0,51");
		}

		
		
		echo '<!-- #### '.$urlxxx.' -->';
*/
	
	
		if($cars === false)
			$cars = sql::fetch("array","cars","WHERE `active` = 1 ORDER BY `created_at` DESC");
			
		if(!is_numeric($rowlimit) || $rowlimit < 1)
			$rowlimit = 1;
			
		if(count($cars) == 0){
		
			echo '<div class="nofound">Geen auto\'s gevonden</div>';
			return;
		
		}
			
		if($rowlimit > 1 || $forcetype == 'grid'){
		
			$count = 1;
		
			foreach($cars as $car){
			
				$color = $car['milieu_bewust'] ? 'green'
											 : 'orange';
						
				list($thumb) = self::car_thumbs($car);
				?>
				<div class="car block <?= $count == $rowlimit ? 'last' : ''?>">
				
					<a href="<?= self::car_url($car) ?>" class="full"></a>
					
					<?= $car['newprice'] == 1 ? '<div class="newprice"></div>' : '' ?>
					<?= $car['verkocht'] == 'j' ? '<div class="sold '.$color.'"></div>' : '' ?>
				
					<img src="<?= $thumb ?>" alt="<?= $car['merk'].' '.$car['model'] ?>" />
					
					<p class="textfit" rel="190"><?= $car['merk'].' '.$car['model'] ?></p>
					
					<div class="label <?= $color ?>">
			
						<div class="left"><div class="corner"></div></div>
						<div class="arrow_right"></div>
					
						<p>&euro; <?= self::car_price($car) ?> ,-</p>
					
						<div class="shadow"></div>
						
					</div>
					
				</div>
				<?
			
				if($count == $rowlimit)
					$count = 0;
					
				$count++;
				
			}
		
		}else
			foreach($cars as $car){
			
				$color       = $car['milieu_bewust'] ? 'green'
											 	   : 'orange';
		
				list($thumb) = self::car_thumbs($car);
				$favorites   = json_decode($_COOKIE['favorites']);
				
				if(is_object($favorites))
					$favorites = get_object_vars($favorites);
				?>
				<div class="car list">
					
					<a href="<?= self::car_url($car) ?>" class="full" target="_top"></a>
					
					<?= $car['verkocht'] == 'j' ? '<div class="sold '.$color.'"></div>' : '' ?>
					<?= $car['newprice'] == 1 ? '<div class="newprice"></div>' : '' ?>
					
					<img src="<?= $thumb ?>" alt="<?= $car['merk'].' '.$car['model'] ?>" />
					
					<div class="details">
					
						<p class="textfit" rel="514"><?= $car['merk'].' '.$car['model'].' '.$car['type'] ?></p>
										
						<div class="col">
		
							<strong>Model:</strong> 			<?= $car['model'] 		?><br />
							<strong>Carosserievorm:</strong> 	<?= $car['carrosserie'] 	?><br />
							<strong>Brandstof:</strong> 		<?= self::string("brandstof ".$car['brandstof']) ?>
						
						</div>
						
						<div class="col">
						
							<strong>Bouwjaar:</strong> 			<?= $car['bouwjaar'] 		?><br />
							<strong>Transmissie:</strong> 		<?= self::string("transmissie ".$car['transmissie']) 	?><br />
							<strong>Aantal deuren:</strong> 	<?= $car['aantal_deuren'] ?>
						
						</div>
						
						<div class="col">
						
							<strong>Kilometerstand:</strong> 	<?= $car['tellerstand'].' '.self::string("eenheden ".$car['tellerstand_eenheid']) ?><br />
							<strong>Cilinderinhoud:</strong> 	<?= $car['cilinderinhoud'] 							?> cc<br />
							<strong>Kleur:</strong>  			<?= $car['basiskleur'].' '.$car['laksoort']	?>
						
						</div>
						
						
					<div class="greentext">
					
					<?php 
					if ($color == "green" && $car["newprice"]!=1) {
					print("Green Deal<br/>Milieubewuste keus");
					}
					?>
					
				</div>
					
					</div>
					
					<div class="label <?= $color ?>">
			
						<div class="right"><div class="corner"></div></div>
					
						<p>&euro; <?= self::car_price($car) ?> ,-</p>
					
						<div class="shadow"></div>
						
					</div>			
					
					<div class="share">
					
						<a href="#" class="toggle_fav" <?= core::$cur_page['id'] == 10 ? 'rel="fav_page"' : '' ?> title="Favorieten" data-car="<?=$car['voertuignr']?>"><img src="img/icon_fav<?= $favorites[$car['voertuignr']] ? '_min' : '' ?>.jpg" rel="fav"  alt="Favorieten"/></a>
						<a href="<?= social::share_url('twitter', $car['merk'].' '.$car['model'].' op Autoservicehaarlem.nl',ROOT.self::car_url($car)) ?>" target="_blank" title="Deel op Twitter">	<img src="img/icon_twit.jpg" alt="Twitter" 	 /></a>
						<a href="<?= social::share_url('facebook',$car['merk'].' '.$car['model'].' op Autoservicehaarlem.nl',ROOT.self::car_url($car)) ?>" target="_blank" title="Deel op Facebook"> <img src="img/icon_fb.jpg"	 alt="Facebook"	 /></a>
						
					</div>
					
					<div class="more_info">
						meer informatie
					</div>
					
				</div>	
				<?
			}
	
	}
	
	static function car_url($car){
	
		if(is_object($car))
			$car = get_object_vars($car);
	
		return ($car['milieu_bewust'] == 1 ? core::page_url('id',4) : core::page_url('id',2)).'/'.self::slug($car['merk']." ".$car['model']." ".$car['voertuignr']).".html";
	
	}
	
	static function string($input){
		
		return sql::fetch("object","strings","WHERE `input` = '".sql::escape($input)."'")->output;
		
	}
 
 	static function car_price($car){
 	
 		if($car['actieprijs'] > 0)
 			$price = $car['actieprijs'];
		else
			$price = $car['verkoopprijs_particulier'];
 	
 		return self::num_format($price);
 	
 	}
 	
 	static function car_thumbs($car){
 	
 		$thumbs = array('img/cars/'.$car['voertuignr'].'_thumb.jpg');
 		
 		for($i = 0;$i < count(explode(",",$car['afbeeldingen']));$i++) 
		 	if(file_exists(dirname(__FILE__).'/../img/cars/'.$car['voertuignr'].'_'.$i.'.jpg'))		
				$thumbs[] = 'img/cars/'.$car['voertuignr'].'_'.$i.'.jpg';
			
		return $thumbs;
 	
 	}

 	static function car_thumbs_fb($car){
 	
 		$thumbs = array('img/cars/'.$car['voertuignr'].'_fb.jpg');
 		
 		for($i = 0;$i < count(explode(",",$car['afbeeldingen']));$i++) 
		 	if(file_exists(dirname(__FILE__).'/../img/cars/'.$car['voertuignr'].'_'.$i.'.jpg'))		
				$thumbs[] = 'img/cars/'.$car['voertuignr'].'_'.$i.'.jpg';
			
		return $thumbs;
 	
 	}
 
}
?>