<?	
class social {
 
	// Changeable variables
	///////////////////////
 
	private $cachedir;
	private $cachetime;
	private $limit			= 50;
	private $textlimit		= 0;
	private $twitter 		= array('user');
	private $facebook   	= array('user');
	private $allowed_tags 	= '<a><br>';
	private $sort			= array('key' 		=> 'time',
									'direction' => 'desc');
	private $timeformat		= 'H:i:s d-m-Y';
	private $language		= 'en';
	
	private $fb_appid;
	private $fb_appsecret;
	private $fb_apptoken;
	
	private $twit_key;
	private $twit_secret;
	private $twit_token;
	private $twit_token_secret;
 
	// Class variables
	//////////////////	
 
	private $output		= array();
 
	function __construct(){
	
		if(is_dir(__DIR__.'/facebook_sdk'))
			require __DIR__.'/facebook_sdk/facebook.php';
	
		if(is_dir(__DIR__.'/twitter_sdk')){

			require __DIR__.'/twitter_sdk/EpiCurl.php';
			require __DIR__.'/twitter_sdk/EpiOAuth.php';
			require __DIR__.'/twitter_sdk/EpiTwitter.php';
		
		}
 
		$this->cachedir  = __DIR__.'/cache/';
		$this->cachetime = strtotime('-10 minutes');
 
		if(!is_dir($this->cachedir))
			mkdir($this->cachedir,0755);
			
	}

	public function set_allowed_tags($input){
		$this->allowed_tags = $input;
	}
 
	public function set_cachetime($input){	
		$this->cachetime = strtotime($input);			
	}				
 
	public function set_limit($input){	
		$this->limit = $input;	
	}
 
	public function set_text_limit($input){
		$this->textlimit = $input;
	}
 
	public function set_twitter_user($input){	
		$this->twitter['user'] = $input;	
	}
 
	public function set_facebook_user($input){
		$this->facebook['user'] = $input;
	}
 
	public function set_sorting($key,$direction="asc"){
		$this->sort = array("key" => $key, "direction" => $direction);
	}
 
	public function set_language($input){
		$this->language = $input;
	}
	
	public function set_fb_app_id($input){
		$this->fb_appid = $input;
	}
	
	public function set_fb_app_secret($input){
		$this->fb_appsecret = $input;
	}
	
	public function set_fb_app_token($input){
		$this->fb_apptoken = $input;
	}
	
	public function set_twit_key($input){
		$this->twit_key = $input;
	}
	
	public function set_twit_secret($input){
		$this->twit_secret = $input;
	}
	
	public function set_twit_token($input){
		$this->twit_token = $input;
	}
	
	public function set_twit_token_secret($input){
		$this->twit_token_secret = $input;
	}
 
	private function format_date($input){	
		return date($this->timeformat,$input);	
	}
 
	private function time_elapsed($input){
 
		$time_values = array(1			=> array("en" => array('second',	'seconds'),
										 		 "nl" => array('seconde',	'seconden')),
	 						 60			=> array("en" => array('minute',	'minutes'),
	 						 					 "nl" => array('minuut',	'minuten')),
 							 3600		=> array("en" => array('hour',		'hours'),
							  			   		 "nl" => array('uur',		'uur')),
			   		 		 86400		=> array("en" => array('day',		'days'),
 		 										 "nl" => array('dag',		'dagen')),
  							 604800		=> array("en" => array('week',		'weeks'),
  							 					 "nl" => array('week',		'weken')),		
	 						 2592000	=> array("en" => array('month',		'months'),
	 						 					 "nl" => array('maand',		'maanden')),
  							 31536000	=> array("en" => array('year',		'years'),
					 					   array("nl" => array('jaar',		'jaren'))));
 
 		krsort($time_values,SORT_NUMERIC);
 
 		$ago_text = array("nl" => "geleden",
 						  "en" => "ago");
 
		if(!is_numeric($input)) // Make sure $input is a timestamp
			$input = strtotime($input);
 
		$diff = time() - $input;
 
		foreach($time_values as $time => $text){
 
			if($diff < $time)
				continue;
 
			$return  = floor($diff / $time);			
			$return .= ' '.($return == 1 ? $time_values[$time][$this->language][0]
										 : $time_values[$time][$this->language][1]);
			$return .= ' '.$ago_text[$this->language];
 
			return $return;
 
		}
 
 
	}
 
	private function sort_output(){
 
		$tmp = array();
 
		foreach($this->output as $key => $o)
			$tmp[strtoupper($o[$this->sort['key']])][] = $key;
 
		if($this->sort['direction'] == 'asc')
			ksort($tmp,SORT_REGULAR);
		else
			krsort($tmp,SORT_REGULAR);
 
		$new = array();
 
		foreach($tmp as $val)
			foreach($val as $v)
				$new[] = $this->output[$v];
 
		$this->output = $new;
 
	}
 
	// Substract while leaving 'a' elements intact, removing useless / potentially dangerous attributes and adding target="_blank"
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
	private function substr_html($input,$limit){
 
		$original = $input;
 
		if(strlen($input) <= $limit)
			return $input;
 
		$pattern = '#<a\s+.*?href=[\'"]([^\'"]+)[\'"]\s*?.*?>((?:(?!</a>).)*)</a>#i';	
 
		// Match all 'a' elements
		preg_match_all($pattern,$input,$matches);
 
		// If no links were found, perform a simple substr()
		if(count($matches[0]) == 0)
			return substr($input,0,$limit).'...';
 
		$strlen  = strlen($input);
		$uni     = sha1(uniqid()); 		
 
		// Replace all links with a generated separator
		$input   = preg_replace($pattern,$uni,$input);
 
		$input  = explode($uni,$input);
		$length = 0;
		$output = '';
 
		// Go through the splitted input		
		foreach($input as $i){
 
			if($length+strlen($i) < $limit){
 
				// If we can fit the next text value without reaching the limit, do it	
				$length += strlen($i);
				$output .= $i;
 
			}else{
 
				// Add whatever we can fit from the last text value and break the loop
				$diff    = abs($limit - $length);
				$output .= substr($i,0,$diff);
				break;
 
			}
 
			if(strlen($tmp) < $limit){ // Do we still have room before we reach the limit?
 
				$nextlink = array_shift($matches[1]);
				$nexttext = array_shift($matches[2]);
 
				if(strip_tags($nexttext,$this->allowed_tags) != '')
					if($length + strlen($nexttext) < $limit){		
 
						// Add the next link if it fits
						$length	+= strlen($nexttext);
						$output .= '<a href="'.$nextlink.'" target="_blank">'.$nexttext.'</a>';
 
					}else{
 
						// Add whatever we can fit from the last link and break the loop
						$diff    = abs($limit - $length);
						$output .= '<a href="'.$nextlink.'" target="_blank">'.substr($nexttext,0,$diff).'</a>';
						break;
 
					}
 
			}
 
		}
 
		// Trim string and remove linebreaks
		$output = trim(preg_replace('/((<br>|<br\/>|<br \/>){1,})/'," ",$output));
 
		return $output.(strip_tags($original) != strip_tags($output) ? '...' : '');
 
	}
 
	private function parse_text($input,$type){
 
		// Convert to ISO-8859-1//TRANSLIT
		$text = iconv("UTF-8", "ISO-8859-1//TRANSLIT",$input);
 
		// Convert URLs into hyperlinks
		$text = preg_replace("/[^(href=\")](http:\/\/)(.*?)\/([\w\.\/\&\=\?\-\,\:\;\#\_\~\%\+]*)/", "<a href=\"\\0\">\\0</a>", $text);
 
		// Apply textlimit	
		if($type == 'twitter'){
 
			// Convert usernames (@) into links 
			$text = preg_replace("(@([a-zA-Z0-9\_]+))", '<a href="http://www.twitter.com/\\1">\\0</a>', $text);
 
			// Convert hash tags (#) to links 
			$text = preg_replace('/(^|\s)#(\w+)/', '\1<a href="http://search.twitter.com/search?q=%23\2">#\2</a>', $text);
 
		}elseif($type == 'facebook') // Remove (in a terrible way) the picture at the end	
			list($text) = explode("<br/><br/><a href=\"http://www.facebook.com/photo.php?fbid=",$text);
 
		if($this->textlimit > 0)
			$text = $this->substr_html($text,$this->textlimit);
 
		// Strip HTML
		$text = strip_tags($text,$this->allowed_tags);
 
		return $text;
 
	}
 
	private function cache_feed($feed,$type){
 
		$cache = $this->cachedir.$type.'-'.$this->{$type}['user'].'.tmp';
 
		if(file_exists($cache)){
 
			$tmp 	 = explode("\n",file_get_contents($cache));
			$date    = array_shift($tmp);
			$content = implode("\n",$tmp);
 
			if(!empty($date) && $date > $this->cachetime) 
				$return = $content;
 
		}	
 
		if(!$return){
 
			$return = @file_get_contents($feed,true);
 
			if($return != null){
 
				$fh = fopen($cache,'w');
				fwrite($fh, time()."\n".$return);
				fclose($fh);
 
			}else{
 
				$tmp  	= explode("\n",file_get_contents($cache));
				$date  	= array_shift($tmp);
				$return = implode("\n",$tmp);
 
			}
 
		}
 
		return $return;
 
	}
 
	// Function that gets the Twitter feed
	//////////////////////////////////////
 
	private function get_twitter_feed(){
 
		// Decode JSON input
		$json = json_decode($this->cache_feed("http://www.twitter.com/statuses/user_timeline/".$this->twitter['user'].".json",'twitter'),true);
 
		if(is_array($json))
			foreach($json as $j)
				$this->output[] = 
					array("source" 	=> "twitter",
						  "time"	=> strtotime($j['created_at']),
						  "date"	=> $this->format_date(strtotime($j['created_at'])),
						  "elapsed" => $this->time_elapsed(strtotime($j['created_at'])),
						  "url"		=> 'http://twitter.com/'.$this->twitter['user'].'/status/'.$j['id_str'],
						  "account" => $this->twitter['user'],
						  "name"	=> $j['user']['name'],
						  "text"	=> $this->parse_text($j['text'],'twitter'),
						  "avatar"	=> $j['user']['profile_image_url_https']);
 
	}
 
	// Function that gets the Facebook feed
	///////////////////////////////////////
 
	private function get_facebook_feed(){
 
		// Spoof user_agent so Facebook accepts our request
		ini_set('user_agent', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3');
 
		// Load RSS into SimpleXML
		$xml 	= simplexml_load_string($this->cache_feed("https://www.facebook.com/feeds/page.php?id=".$this->facebook['user']."&format=rss20",'facebook'));
 
		// Retrieve general info from user
		$fbinfo = $this->get_facebook_info($this->facebook['user']);
 
		foreach($xml->channel->item as $item)
			$this->output[] =
				array("source"	=> "facebook",
					  "time"	=> strtotime($item->pubDate),
					  "date"	=> $this->format_date(strtotime($item->pubDate)),
					  "elapsed" => $this->time_elapsed(strtotime($item->pubDate)),
					  "url"		=> (string)$item->link,
					  "account"	=> $fbinfo['name'],
					  "name"	=> (string)$item->author,
					  "text"	=> $this->parse_text($item->description[0],'facebook'),
					  "avatar"	=> $fbinfo['picture']);
 
	}
 
	private function calculate(){
 
		if(!empty($this->twitter['user']))
			$this->get_twitter_feed();
 
		if(!empty($this->facebook['user']))
			$this->get_facebook_feed();
 
		$this->sort_output();
 
	}
 
	public function get_feed(){
 
		$this->calculate();
 
		return array_slice($this->output,0,$this->limit);
 
	}
	
	// Post to Facebook wall
	////////////////////////
	
	public function fb_wall_post($sPage,$message,$link=false){
	
		if(!$this->fb_appid || !$this->fb_appsecret || !$this->fb_apptoken)
			die('Missing Application Info');
			
		$facebook = new Facebook(array(
			'appId'	 => $this->fb_appid,
			'secret' => $this->fb_appsecret,
			'cookie' => true
		));
		
		$post = array('access_token' => $this->fb_apptoken,
					  'message'		 => $message,
					  'link'		 => $link);  		

		$facebook->api('/me/feed','POST',$post);
		
	}
	
	// Post to Twitter
	//////////////////
	
	public function twitter_post($message,$link=false){
	
		if(!$this->twit_key || !$this->twit_secret || !$this->twit_token || !$this->twit_token_secret)
			die('Missing Application Info');
	
		$twit 	 = new EpiTwitter($this->twit_key,$this->twit_secret);
		$twit->setToken($this->twit_token,$this->twit_token_secret);
		
		$max 	 = $link ? 115 : 140;
		$message = substr($message,0,$max).($link ? ' '.$link : '');
		
		$return = $twit->post_statusesUpdate(array('status' => $message));
		
		$return->response;

	}
	
	//////////////////////////
	// Standalone functions //
	//////////////////////////
	
	// Get general facebook info from user
	//////////////////////////////////////
	
	public static function get_facebook_info($user){
 
		$json = @file_get_contents('https://graph.facebook.com/'.$user);
		return json_decode($json,true);
 
	}
	
	// Prints Facebooks like button with the current or a defined url
	/////////////////////////////////////////////////////////////////
	
	public static function like_button($url = false){
	
		if(!$url)
			$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
		echo '<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			  <fb:like href="'.$url.'" layout="button_count" show_faces="false" font="trebuchet ms"></fb:like>';
	
	}
	
	// Prints Tweet button with current or defined url
	//////////////////////////////////////////////////
	
	public static function tweet_button($url = false){
	
		echo '<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" '.($url ? 'data-url="'.$url.'"' : '').'>Tweet</a>
		      <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>';
	
	}
	
	// Returns share link
	/////////////////////
	
	public static function share_url($network = 'facebook',$title = '',$url = false){
	
		if(!$url)
			$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
		if($network == 'facebook')
			return 'http://www.facebook.com/sharer.php?u='.rawurlencode($url).'&t='.urlencode($title);
		else
			return 'http://twitter.com/home?source=webclient&status='.urlencode($title).'+-+'.$url;
	
	}
 
}
?>