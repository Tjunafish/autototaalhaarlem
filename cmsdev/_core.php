<?
// core CLASS

class core {
	
	public static $NOTIFICATION;
	public static $USER;
	public static $TITLE;
	public static $FOOTER;
	public static $LOGINTABLE;
	public static $DOMAIN;
	public static $PAGES 			= array();
	public static $PAGE;
	public static $END_SCRIPT 		= "";
	public static $ROOT;
	public static $UPLOAD_FOLDERS 	= array();
	public static $FILE_MANAGER 	= 1;
	public static $tabs				= array();
	
	// Function used to create a notification
	
	static function notify($msg){
	
		self::$NOTIFICATION = str_replace("'","\'",$msg);
		
	}
	
	// Function used to add a page
	
	static function addPage($p){
			
		self::$PAGES[$p->NAME] = $p;
		
	}
	
	// Print the navigation
	
	static function print_navigation(){
								
        foreach(self::$PAGES as $page)					
            if($page->HIDE == false)
                if($page->TAB)	
                    self::$tabs[$page->TAB][] 		= $page;
                else							
                    self::$tabs[$page->TITLE]['page'] = $page;
        
        foreach(self::$tabs as $key => $tab)					
            if($tab['page'])                
                echo '<a href="index.php?page='.$tab['page']->NAME.'" '.
                	 ($tab['page']->NAME == self::$PAGE->NAME && !$_GET['filemanager'] ? 'class="no-sub-active"' : '').
                	 '>'.$key.'</a>';
            else            
                echo '<a id="tab-'.str_replace(" ","_",$key).'" href="index.php?page='.$tab[0]->NAME.'" class="tab'.
					 ($key == self::$PAGE->TAB && !$_GET['filemanager'] ? ' active' : '').
            		 '">'.$key.'</a>';
	
	}
	
	// Print the subnavigation
	
	static function print_subnavigation(){
	
		foreach(self::$tabs as $key => $tab){
    
	        echo '<div id="sub-'.str_replace(" ","_",$key).'" class="submenu" '.
				 ($key == self::$PAGE->TAB ? 'style="display:block;"' : '').
				 '>';
	    
	        foreach($tab as $x => $info)            
	            echo '<a href="index.php?page='.$info->NAME.'"'.
	            	 (($info == self::$PAGE)? ' class="active"' : '').
	            	 '>'.$info->TITLE.'</a>';	
	        
	        echo '</div>';
	        
	    }
	
	}
	
	// Function that gets the correct page
	
	static function handlePage($override=""){
		
		if(self::$USER['super'] != 1){
		
			$tmp = self::$PAGES;
			self::$PAGES = array();
		
			foreach($tmp as $page)
				if($page->SUPER == false)
					self::$PAGES[] = $page;
		
		}
	
		if($override == ""){
				
			if(isset($_GET['page']))				
				$current_page = $_GET['page'];			
		
		}else		
			$current_page = $override;

		self::$PAGE = reset(self::$PAGES);
		
		foreach(self::$PAGES as $page)
			if($page->NAME == $current_page)	
				self::$PAGE = $page;
	
	}
	
	// Login function
	
	static function login($user,$pass){
			
		$user = sql::escape($user);
		$pass = sql::escape($pass);
		
		if(sql::exists(self::$LOGINTABLE,array("username"=>$user,"password"=>$pass))){
		
			list(self::$USER) = sql::fetch("array",self::$LOGINTABLE,"WHERE `username` = '".$user."' && `password` = '".$pass."'");

			setcookie("cms_login",self::$USER['username'],time()+60*60,"/",self::$DOMAIN);
			setcookie("cms_secure",sha1(md5(self::$USER['username'].self::$USER['password'])),time()+60*60,"/",self::$DOMAIN);
		
		}else		
			return "Uw gebruikersnaam of wachtwoord is onjuist";
	
	}
	
	// Logout function
	
	static function logout(){
	
		self::$USER = false;
		
		if(isset($_COOKIE['cms_login'])){
		
			setcookie("cms_login","",time()-1,"/",self::$DOMAIN);
			unset($_COOKIE['cms_login']);
		
		}
			
		if(isset($_COOKIE['cms_secure'])){
		
			setcookie("cms_secure","",time()-1,"/",self::$DOMAIN);
			unset($_COOKIE['cms_secure']);
		
		}
	
	}
	
	// Function to check if a user is logged in
	
	static function checkLogin(){
			
		if(isset($_COOKIE['cms_login']) && isset($_COOKIE['cms_secure'])){
	
			list($user) = sql::fetch("array",self::$LOGINTABLE,"WHERE `username` = '".sql::escape($_COOKIE['cms_login'])."'");
			
			if($_COOKIE['cms_secure'] == sha1(md5($user['username'].$user['password']))){
				
				self::$USER = $user;
				
				if(!headers_sent()){
					
					setcookie("cms_login",$_COOKIE['cms_login'],time()+60*60,"/",self::$DOMAIN);
					setcookie("cms_secure",$_COOKIE['cms_secure'],time()+60*60,"/",self::$DOMAIN);
				
				}
				
			}else				
				self::logout();
				
		}else		
			self::$USER = false;

	
	}
	
	// Function for forgetting password
	
	static function forgotPass($user){
		
		$user = sql::escape($user);
		
		if(sql::exists(self::$LOGINTABLE,array("username"=>$user)))		
			list($user) = sql::fetch("array",self::$LOGINTABLE,"WHERE `username` = '".$user."'");			
		else	
			return "Deze gebruiker staat niet in het systeem.";
			
		$message = "U heeft aangegeven op het cms van http://".self::$DOMAIN."/".self::$ROOT." dat u uw wachtwoord vergeten bent.\n\nUw wachtwoord is: ".$user['password'];
		
		self::sendMail($user['username'],"CMS Mailer","cms-mailer@".self::$DOMAIN,self::$TITLE." - Wachtwoord vergeten",$message);
		
		return "Uw wachtwoord is naar u gemaild";
	
	}
	
	// Function that sends mail
	
	static function sendMail($target,$from_name,$from_mail,$subject,$message){
		
		$headers = "From: ".$from_name." <".$from_mail.">\r\nReply-To: ".$from_mail."\r\n X-Mailer: PHP/";
		
		mail($target,$subject,$message,$headers);
		
	}
	
	static function formField($type,$id,$value="",$title="",$no_error=false){
	
		if(isset(self::$PAGE->FORM_ERROR[$id]) && $no_error==false)
			$class = 'error_input';
			
		$type = explode("|",$type);
	
		switch ($type[0]){
		
			case "text":
			
				$return = '<input type="text" class="'.$class.'" name="'.$id.'" value="'.$value.'" title="'.$title.'"/>';
			
			break;
			
			case "check":
			
				$count		= 1;
				$checked 	= explode(",",$value);
				$grp		= uniqid();
				
				$return 	= '<input type="hidden" data-group="'.$grp.'" name="'.$id.'" value="'.$value.'" title="'.$title.'">';
				
				foreach(explode(",",$type[1]) as $val){
					
					$return .= '<input type="checkbox" class="input_checkbox" data-group="'.$grp.'" '.
							   ((in_array($val,$checked))? 'checked ' : '').'/><p>'.$val.'</p>';
					
					$count++;
								
				}
				
			break;
			
			case "textarea":
			
				if($type[1] == "nl2br")
					$value = str_replace("<br />","",$value);
					
				$return = '<textarea  name="'.$id.'" rows="8" title="'.$title.'" class="'.$class;
				
				if($type[1] == "rich"){
					
					$return .= ' rich_field';
					$value 	 = preg_replace('/<span class=\"(.*?)\">(.*?)<\/span>/is','[$1]$2[/$1]',$value);
				}
				
				$tmp = explode("-",$id);
				
				if(!array_pop($tmp) == 0)
					$return .= '" id="textarea-'.$id;
					
				$return .= '">'.$value.'</textarea>';
				
			break;
			
			case "date":
			
				$value  = ($value && $value != "0000-00-00" && $value != "0000-00-00 00:00:00") ? date('j F Y H:i:s',strtotime($value))
	   																							: '';
					
				$return = ($type[1] == "now") ? '<p>'.date('H:i:s j F Y').'</p><input type="hidden" class="'.$class.'" name="'.$id.'" title="'.$title.'" value="'.date('Y-m-d H:i:s').'"/>'
											  : '<input type="text" id="date-'.$id.'" class="datepicker '.$class.'" name="'.$id.'" readonly="readonly" value="'.$value.'" title="'.$title.'"/>';				
			break;
			
			case "img":
			case "file":
			
				$tmp 	= explode("####",$value);
				$count  = 0;
				
				foreach($tmp as $i)
					if($i != "")
						$count++;
						
				$value  = str_replace('####','|',$value);
							
				$return = '<div id="files_uploaded_'.$id.'" class="files_uploaded '.$class.'">'.
						  '<span>'.(($count == 1)? '1 bestand' : $count.' bestanden').'</span>'.
						  '<a class="button file_upload_button" fieldtype="'.$type[0].'" rel="'.$type[1].'|'.$type[2].'|'.$type[3].(($type[4])? '|'.$type[4] : '').
						  '">Bestandsbeheer</a></div>'.
						  '<input id="upload_data_'.$id.'" class="upload_data" type="hidden" name="'.$id.'" value="'.$value.'" />';					  
			
			break;
			
			case "link_select":
			
				$return    = '<select class="'.$class.'" name="'.$id.'"><option value=""></option>';
				$link 	   = explode("|",$value);
				
				list($key) = explode("-",$id);				
				
				if($link[3] && $link[4]){
				
					$extra = "ORDER BY ".$link[3]." ".$link[4];
					$val   = $link[5];
				
				}else{
				
					$extra = false;
					$val   = $link[3];
						
				}
				
				$tmp 	   = sql::fetch("array",$link[0],"GROUP BY `".$link[1]."` ".$extra);
				
				foreach($tmp as $field){
								
					$return .= '<option value="'.$field[$link[1]].'"'.
			  				   ($field[$link[1]] == $val || (self::$PAGE->SELECTOR_FIELD == $key && self::$PAGE->SELECTOR == $field[$link[1]]) ? ' selected="selected"' : '').
							   '>';
		   
		   			$vals    = explode(" ",$link[2]);
		   			
		   			foreach($vals as $v)							   
		   				$return .= $field[$v].' ';
					   
		   			$return .= '</option>';
		   			
  				}
				
				$return .= '</select>';	
				
			break;
			
			case "link_select_mult":
			
				$link 	= explode("|",$value);
				$values = array_slice($link,2);
				
				$count 	= $type[1] ? $type[1]
	  							   : 1; // when no count is selected, this should be on an 'add extra' basis //
	
				for($i = 1;$i <= $count;$i++){	
					
					$tmp 	 = explode("-",$id);
					$num 	 = array_pop($tmp);
					$tmp[] 	 = $i;
					$name 	 = implode("-",$tmp);
					
					$return .= '<select class="'.$class.'" name="'.$name.'-'.$num.'">'.
							   '<option value=""></option>';
							   
					foreach(sql::fetch("array",$link[0],"GROUP BY `".$link[1]."`") as $option)				
						$return .= '<option value="'.$option[$link[1]].'"'.(($option[$lin[1]] == $values[($i-1)])? 'selected="selected"' : '').'>'.$option[$link[1]].'</option>';
	
					$return .= '</select> ';
				
				}
				
			break;
			
			case "values":
			
				$return = '<select class="'.$class.'" name="'.$id.'">';
				$values = explode("|",$value);
				$current_value = array_pop($values);
				
				list($key) = explode("-",$id);
				
				foreach($values as $value){
				
					$tmp = explode(":",$value);
					
					if($tmp[0] == "values"){
					
						$tmp = explode('"-"',$tmp[1]);
					
						foreach($tmp as $value){
						
							$value = str_replace('"','',$value);
							
							$return .= '<option value="'.$value.'"';
								if($value == $current_value || (self::$PAGE->SELECTOR_FIELD == $key && self::$PAGE->SELECTOR == $value))
									$return .= ' selected="selected"';
									
							$return .= '>'.$value.'</option>';
						
						}
					
					}
				
				}
				
				$return .= '</select>';
				
			break;
			
			case "bool":
			
				$tmp 		= explode("-",$id);
				$table_info = sql::getColumns(self::$PAGE->TABLE);
				
				if($value === 0 || (empty($value) && $table_info[$tmp[0]]['Default'] == 0)){	
				
					$return = '<img src="images/cross.png" id="bool_'.$tmp[1].'" class="bool_click_form" rel="'.$tmp[0].'|'.$tmp[1].'" />';
					$value  = 0;
					
				}else{
					
					$value  = 1;
					$return = '<img src="images/tick.png" id="bool_'.$tmp[1].'" class="bool_click_form" rel="'.$tmp[0].'|'.$tmp[1].'" />';	
										
				}
					
				$return .= '<input type="hidden" id="bool_'.$id.'" name="'.$id.'" value="'.$value.'" />';
			
			break;
				
			case "color":
						
				if(!$value)
					$value = ($type[1])? $type[1] : '';
						
				$return .= '<input type="text" id="colorselect_'.$id.'" class="color" name="'.$id.'" value="'.$value.'" />'; 
				
			break;
		
		}
			
		return $return;		
	
	}
	
	static function imgResize($file,$new_w,$new_h,$pextension=""){
				
		if(!is_array($file)){
			
			$tmp = $file;
			unset($file);
			
			$file['tmp_name'] = $tmp;
			$tmp = explode('.',$file['tmp_name']);
			$extension = $tmp[(count($tmp)-1)];
		
		}elseif($file['type'] == ""){
			
			$tmp = explode('.',$file['tmp_name']);
			$extension = $tmp[(count($tmp)-1)];
		
		}else
			list($null,$extension) = explode("/",$file['type']);
		
		if($file['destination'])
			$file['tmp_name'] = "../".$file['destination'];
		
		if($pextension)
			$extension = $pextension;
				
		$extension = strtolower($extension);

		if($extension == "jpg" || $extension == "jpeg")
			$src_img = imagecreatefromjpeg($file['tmp_name']);
		
		if($extension == "png")			
			$src_img = imagecreatefrompng($file['tmp_name']);			
		
		list($old_x,$old_y) = getimagesize($file['tmp_name']);
		
		$thumb_w = $new_w;
		$thumb_h = $new_h;		
		
		if($new_h == 0)
			$thumb_h = ($new_w / $old_x)*$old_y; 
		elseif($new_w == 0)
			$thumb_w = ($new_h / $old_y)*$old_x; 		
		
		$dst_img = imagecreatetruecolor($thumb_w,$thumb_h);
		imagealphablending($dst_img,false);
		imagesavealpha($dst_img,true);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
		
		if($extension == "png")
			imagepng($dst_img,$file['tmp_name']); 
		else
			imagejpeg($dst_img,$file['tmp_name'],100); 

		imagedestroy($dst_img); 
		imagedestroy($src_img); 
		
	}
	
	static function slug($input){
	
		$string = html_entity_decode($input,ENT_COMPAT,"UTF-8");
		
		setlocale(LC_CTYPE, 'en_US.UTF-8');
		$string = iconv("UTF-8","ASCII//TRANSLIT",$string);
 		
		return strtolower(str_replace(' ','-',trim(preg_replace('/[^a-zA-Z0-9]+/',' ',$string))));
 
	}
	
}

// PAGE CLASS

class page {

	public $TITLE;
	public $TABLE;
	public $NAME;
	public $ORDER;
	public $FIELDS 			= array();
	public $MODULES 		= array();	
	public $CONDITIONS;
	public $SELECTOR;
	public $SELECTOR_FIELD;
	public $LIMIT			= '0,50';
	public $LOAD_MORE		= false;
	public $FIRST_CALC 		= 1;
		
	public $FORM_ERROR 		= array();
	public $FORM_SUCCESS 	= false;
	public $LIGHTBOX 		= 0;
	
	public $CAN_ADD 		= true;
	public $CAN_DEL 		= true;
	public $CAN_MOD 		= true;
	public $CAN_VIEW 		= true;
	public $SEARCH 			= true;
	public $SUPER 			= false;
	public $CUSTOM_ORDER 	= false;
	public $SELECTFILTERS	= array();
	
	private $INFO;
	private $SHOWN_FIELDS;
	private $NUM_FORMS;
	private $SUB_FORM		= array();
	private $OLD_INFO 		= false;
	private $NEW_ENTRIES 	= array();
	private $NOT_USED 		= array();
	private $ERROR_TYPE;
	private $FILTERED		= array();
	private $FIRST_FILTER;
	private $SLUG			= array();
	
	function calculate(){
	
		if($this->CUSTOM_ORDER == true)
			$this->ORDER = "`order` ASC";
			
		if(($_REQUEST['search'] && $_REQUEST['search_field']) && $this->FIRST_CALC == 0){
			
			$search_field = sql::escape($_REQUEST['search_field']);
			$search 	  = sql::escape($_REQUEST['search']);
			
			if(!$this->FIELDS[$search_field]['link']){
			
				$query = $this->CONDITIONS.
						 (preg_match('/WHERE/',$query) ? ' && ' : ' WHERE ').
						 '`'.$search_field."` LIKE '%".$search."%'";
						 
		  		if($this->SELECTOR && $this->SELECTOR != "show_all")
		  			$query .= (preg_match('/WHERE/',$query) ? ' && ' : ' WHERE ').
		  					  $this->SELECTOR_FIELD." = '".$this->SELECTOR."'";
		  					  
    			$this->INFO = sql::fetch("array",$this->TABLE,$query.
															  ($this->ORDER ? ' ORDER BY '.$this->ORDER : '').
															  ($this->LIMIT ? ' LIMIT '.$this->LIMIT : ''));
															  
		  		$num		= sql::num($this->TABLE,$this->CONDITIONS);
			
			}else{
			
				$link	= explode("|",$this->FIELDS[$search_field]['link']);
				$join 	= "INNER JOIN `".$link[0]."` t2 ON t1.".$search_field." = t2.".$link[1]." ";
				
				$search = (preg_match('/WHERE/',$this->CONDITIONS)? ' && ' : ' WHERE ').				
						  "CONCAT(t2.`".implode("`,t2.`",explode(" ",$link[2]))."`) LIKE '%".sql::escape($_REQUEST['search'])."%'";
				
				$select = "t1.id,";
				
				foreach($this->SHOWN_FIELDS as $field)											  
					$select .= "t1.".$field.",";
					
				$select = substr($select,0,(strlen($select)-1));
				
				if(preg_match("/(\(\()/",$link[2]))
					list($link[2]) = explode("((",$link[2]);
				
				$query = "SELECT ".$select.",t2.`".implode("`,t2.`",explode(" ",$link[2]))."` AS `linkedvalue` FROM `".$this->TABLE."` t1 ".$join." ".$this->CONDITIONS.$search;
				
				if($this->SELECTOR && $this->SELECTOR != "show_all")					
					$query .= (preg_match('/WHERE/',$query)? ' && ' : ' WHERE ').
							  "t2.".$this->SELECTOR_FIELD." = '".$this->SELECTOR."'";
				
				$query .= ($this->ORDER ? " ORDER BY t1.".$this->ORDER : "").
		  		  		  ($this->LIMIT ? " LIMIT ".$this->LIMIT : '');
				
				$this->INFO = sql::fetch("array","","",$query);
				$num		= sql::num("","",$query);
					
			}
			
		}else{
			
			$query = $this->CONDITIONS.$search;
			
			if($this->SELECTOR && $this->SELECTOR != "show_all")					
				$query .= (preg_match('/WHERE/',$query)? ' && ' : ' WHERE ').
						  $this->SELECTOR_FIELD." = '".$this->SELECTOR."'";
			
			$this->INFO = sql::fetch("array",$this->TABLE,$query.($this->ORDER ? " ORDER BY ".$this->ORDER : '').($this->LIMIT ? " LIMIT ".$this->LIMIT : ''));
			$num		= sql::num($this->TABLE,$query);
			
		}
		
		$tmp = explode(",",$this->LIMIT);
		
		$this->LOAD_MORE = ($tmp[0] + $tmp[1] < $num) ? 'data-start="'.($tmp[0]+$tmp[1]).'"' 
													  : false; 
			
		if($this->OLD_INFO != false){
			
			$count = 0;
			
			$tmp_old = $this->OLD_INFO;
			$tmp_new = $this->INFO;
			
			sort($tmp_old);
			sort($tmp_new);
			
			foreach($tmp_new as $info){
			
				if($info != $tmp_old[$count])				
					$this->NEW_ENTRIES[] = $info['id'];					

				$count++;
			
			}
		
		}
		
		$this->OLD_INFO = $this->INFO;
		$shown_fields 	= array();
		
		foreach($this->FIELDS as $fields)
			$shown_fields[$fields['field']] = $fields['field'];
			
		$this->SHOWN_FIELDS = $shown_fields;		
		$this->FIRST_CALC 	= 0;
		
		if($_REQUEST['search'] && $_REQUEST['search_field'])
			$this->NEW_ENTRIES = array();
	
	}
	
	function linkValue($field,$input){
		
		if(!empty($this->FIELDS[$field]['link']) && substr($this->FIELDS[$field]['type'],0,16) != "link_select_mult"){
			
			$link 	= explode("|",$this->FIELDS[$field]['link']);					
			$fields = explode(" ",$link[2]);
			$tmp 	= sql::fetch("object",$link[0],"WHERE `".$link[1]."` = '".$input."'");
			
			$return = '';
			
			foreach($fields as $f)
				$return .= $tmp->{$f}.' ';
				
			return substr($return,0,-1);
		
		}else		
			return $input;
	
	}
	
	function addField($title,$field,$type,$req,$size,$edit=true,$link=""){
	
		$this->FIELDS[$field] = array("title" => $title,
									  "field" => $field,
								      "type"  => $type,
									  "req"	  => $req,
									  "size"  => $size,
								      "edit"  => $edit,
								      "link"  => $link);
									  
		if(substr($type,0,3) == "img"){
					
			list($null,$null,$folder) = explode("|",$type);
			if(in_array($folder,core::$UPLOAD_FOLDERS) === false)
				core::$UPLOAD_FOLDERS[$folder] = $folder;
				
		}
	
	}
	
	function createSelectFilter($title,$field,$filter,$size){
		
		$req = 'values:';
		
		foreach($filter as $key => $value)
			$req .= '"'.$key.'"-';
			
		$req = substr($req,0,-1);
		
		$this->FIELDS[$field] = array("title" => $title,
									  "field" => $field,
									  "type"  => 'text',
									  "req"	  => $req,
									  "size"  => $size,
									  "edit"  => true,
									  "link"  => '');
									  
 		$this->SELECTFILTERS[] = array("field"  => $field,
	 								   "filter" => $filter);
	 								   
  		foreach($filter as $field => $filters){ 			
  		
		  	if(!$this->FIRST_FILTER)
		  		$this->FIRST_FILTER = $field;
		  
		  	foreach(explode(" ",$filters) as $f)  				
  				$this->FILTERED[$f] .= $field.' ';			  			
		
		}
	}
	
	function combineFields($title,$fields,$limit=1){
		
		$this->COMBOFIELDS[$title]['fields'] = $fields;
		$this->COMBOFIELDS[$title]['limit']	 = $limit;
		
		foreach($fields as $field)
			$this->COMBINEDFIELDS[$field] = $title;
		
	}
	
	function slug($slugfield,$main,$extra=false){
	
		$this->SLUG[$slugfield] = array("main"	=> $main,
										"extra" => $extra);
	
	}
	
	function printSearch(){
		
		$printsearch = false;
	
		foreach($this->FIELDS as $key => $value){	
	
			$size = explode("|",$value['size']);
			
			if($size[0] != "hide" && substr($value['type'],0,3) != "img" && substr($value['type'],0,4) != "file")				
				$printsearch = true;
				
		}
	
		if($this->SEARCH != false && $printsearch == true){ 
			
			echo '<div id="search">
				  <form name="search_form" id="search_form" method="get">
				  <input type="hidden" name="page" value="'.$this->NAME.'">
				  <input type="hidden" name="selector" value="'.$this->SELECTOR.'">
				  <input type="text" name="search" value="'.$_GET['search'].'">
				  <select name="search_field" id="search_field">';
			
			foreach($this->FIELDS as $key => $value){	
	
				$size = explode("|",$value['size']);
				
				if($size[0] != "hide" && substr($value['type'],0,3) != "img" && substr($value['type'],0,4) != "file"){
					
					$title = ($this->COMBINEDFIELDS[$key])? $this->COMBINEDFIELDS[$key]." (".$this->FIELDS[$key]['title'].")"
														  : $this->FIELDS[$key]['title'];
					
					echo '<option value="'.$key.'"'.
						 ($_GET['search_field'] && $_GET['search_field'] == $key ? ' selected="selected"' : '').
						 '>'.$title.'</option>';
					
				}
				
			}
			
			echo '</select>
				  <a id="search_button">Zoeken</a>
				  </form></div>';
		
		}
	
	}
	
	function printForm($type,$id=""){
				
		if($type == "edit" && $this->CAN_MOD == false)	
			$type = "add";
		
		if($type == "edit")
			$this->NUM_FORMS = 1;
		
		if($type == "add")
			$entry_info["default"] = "";
			
		if($type == "edit" && is_numeric($id))		
			$entry_info[$id] = sql::fetch("array",$this->TABLE,"WHERE `id` = '".sql::escape($id)."'");

		if($type == "mult_edit"){
			
			$tmp = $id;
			unset($id);
			
			foreach($tmp as $i){
				
				$id[] 		  = $i;
				$entry_info[] = sql::fetch("array",$this->TABLE,"WHERE `id` = '".sql::escape($i)."'");
				
			}
			
			$this->NUM_FORMS = count($entry_info);
		
		}
		
		if($type != "add")
			core::$END_SCRIPT .= "slide_form(0,0);";

		if($this->NUM_FORMS < 1)
			$this->NUM_FORMS = 1;	
			
		$linkedimgs = array();	
		
		for($i=0;$i<($this->NUM_FORMS+1);$i++){
			
			if($this->FIELDS_DONE)
				unset($this->FIELDS_DONE);
			
			$block[$i] = '<div class="form_block">';
			
			if($i == 0){
				
				$num 	  = $this->NUM_FORMS;
				$no_error = true;
			
			}else{
				
				$num 	  = $i;
				$no_error = false;
			
			}
			
			$first 		= true;
			$count 		= $dup_count = $combocount = 0;
			
			foreach($this->SHOWN_FIELDS as $field)				
				if($this->FIELDS_DONE[$field] != true){
				
					if($count != 0)
						$block[$i] .= '<div class="form_divider"></div>';
					
					if($type == "mult_edit"){
						
						$num 	 = $id[$i-1];
						$real_id = $i-1;
						
					}elseif($type == "edit"){
						
						$num 	 = $id;
						$real_id = $id;
						
					}else				
						$real_id = $id;
					
					if($i == 0)
						$num = 0;
											
					$new_info = $entry_info[$real_id];
					
					if($fieldinfo)
						unset($fieldinfo);
					
					if($this->COMBINEDFIELDS[$field]){
						
						$combo 	= $this->COMBINEDFIELDS;
						$c 		= 0;
						
						foreach($this->COMBOFIELDS[$combo[$field]]['fields'] as $f){
	
							$fieldinfo[$c] 			= $this->FIELDS[$f];
							$fieldinfo[$c]['title'] = $combo[$f];
							$fieldinfo[$c]['limit'] = $this->COMBOFIELDS[$combo[$field]]['limit'];
							$c++;
							
						}
					
					}else
						$fieldinfo[0] = $this->FIELDS[$field];
					
					$duplicate  = false;
						
					if(count($fieldinfo) > 1){
					
						$duplicate['limit'] = $fieldinfo[0]['limit'];
						$duplicate['title'] = $fieldinfo[0]['title'];
						$extra				= '-0';
						$block[$i] 	 	   .= '<input type="hidden" id="dup_limit-'.$duplicate['title'].'" class="duplicate_limit" value="'.$duplicate['limit'].'" />';
						
					}
					
					$block[$i] .= '<div class="row';
					
					if($this->FILTERED[$fieldinfo[0]['field']])
						foreach(explode(" ",$this->FILTERED[$fieldinfo[0]['field']]) as $tmpf)
							if($tmpf)
								$block[$i] .= ' filter_'.$tmpf;
					
					$block[$i] .= '"><div class="left">'.$fieldinfo[0]['title'].':</div>'. 
								  '<div class="right'.((count($fieldinfo) > 1)? ' combo_right' : '' ).'" id="right_'.$fieldinfo[0]['field'].$extra.'-'.$num.'">';
					
					$firstc 	= true;
						
					if($duplicate && is_array($new_info[0])){
						
						$dupc = array();
						
						foreach($this->COMBOFIELDS as $combo => $null)						
							foreach($new_info[0] as $key => $tmp_info)							
								if($this->COMBINEDFIELDS[$key] == $combo){
									
									str_replace('####','',$tmp_info,$tmpc);
									
									if(!$dupc[$key])
										$dupc[$key] = 0;
										
									if($tmpc > $dupc[$key])
										$dupc[$key] = $tmpc;		
										
									for($x=0;$x<=$dupc[$key];$x++)
										$this->SUB_FORM[$num][$combo][$x] = true;						

								}		
													
					}
					
					if(!$this->SUB_FORM[$num][$this->COMBINEDFIELDS[$field]])
						$this->SUB_FORM[$num][$this->COMBINEDFIELDS[$field]][0] = true;					
								
					foreach($this->SUB_FORM[$num][$this->COMBINEDFIELDS[$field]] as $subnum => $null){		
					
						$extra = '-'.$subnum;
						
						if($i == 0)
							$dupcount = 0;
						else
							$dupcount++;
						
						if(count($fieldinfo) > 1 && !$firstc)							
							$block[$i]	.= '<div id="inner_duplicate-'.$dupcount.'" class="inner_dup"><div class="floatright" style="margin-top:5px">'.
										   '<a href="#" id="dup_remove-'.$dupcount.'" class="duplicate_remove" info-id="#inner_duplicate-'.$dupcount.'"><img src="images/delete.png"></a>'.
										   '</div><div class="inner_dup_divide"></div>';
					
						if(count($fieldinfo) > 1){
												
							$block[$i] .= '<div id="combo_field_active'.$extra.'-'.$num.'" class="combo_field_active" rel="'.$fieldinfo[0]['title'].$extra.'-'.$num.'" value="'.$fieldinfo[0]['field'].$extra.'-'.$num.'"></div>';							
							$firstb 	= true;	
							
							foreach($fieldinfo as $info){						
								
								$block[$i] .= '<div id="combo_top_'.$info['field'].$extra."-".$num.'" class="combo_field'.
											  (($firstb)? ' combo_active' : '').
											  ((isset($this->FORM_ERROR[$info['field'].$extra.'-'.$num]) && $no_error == false)? ' combo_error' : '').
											  '" target="'.$info['field'].$extra."-".$num.'">'.$this->FIELDS[$info['field']]['title'].'</div>';
								
								$firstb		= false;
								$combocount++;
								
							}
							
						}
							
						$firstb 	= true;				
						
						foreach($fieldinfo as $info){
													
							if(count($fieldinfo) > 1){
								
								$block[$i] .= '<div id="combo_tab_'.$info['field'].$extra."-".$num.'"'.(($firstb != true)? ' style="display:none"' : '').' class="combo_tab">';							
								$firstb 	= false;
								
							}
							
							$this->FIELDS_DONE[$info['field']] = true;
							
							$field 		= $info['field'];						
							$field_type = explode("|",$info['type']);
							
							if(isset($_POST[$field.$extra."-".$num]))
								$value = $_POST[$field.$extra."-".$num];
							/*elseif($field_type[0] == "check"){
								
								$total 		 = count(explode(",",$field_type[1]));					
								$check_found = false;
								
								for($y = 1;$y<=$total;$y++)					
									if(isset($_POST[$field."-".$y."-".$num])){
										
										$value 		.= $_POST[$field."-".$y."-".$num].",";
										$check_found = true;
										
									}
								
								if(!$check_found){
									$value = ($type == "edit" || $type == "mult_edit" ? $new_info[0][$info['field']] : '');
								}else
									$value = substr($value,0,-1);
			
							}*/else
								$value = ($type == "edit" || $type == "mult_edit" ? $new_info[0][$info['field']] : '');
							
							if($this->FORM_SUCCESS == true)
								$value = "";
							
							if($type == "edit" || $type == "mult_edit"){
								
								$block[$i] .= '<input type="hidden" name="'.$info['field'].$extra.'-'.$num.'-original" value="'.htmlentities($value).'" />';
								
								if($duplicate){
																	
									$value = explode("####",$value);
									$value = ($_POST && $i > 0)? $value[0] : $value[$subnum];

								}
								
							}
							
							if($type == "add" && !$_POST && $info['field'] == $this->SELECTOR_FIELD && $this->SELECTOR != "show_all")
								$value = $this->SELECTOR;
							
							$col_info 	= sql::getColumns($this->TABLE);						
							$title 		= htmlentities($col_info[$info['field']]['Comment']);
							
							if($info['edit'] == true){
								
								if(!empty($info['link']))			
									$block[$i] .= core::formField($info['type'],$info['field'].$extra."-".$num,$info['link']."|".$value,$title,$no_error);
								elseif(preg_match("/values:/",$info['req']))
									$block[$i] .= core::formField("values",$info['field'].$extra."-".$num,$info['req']."|".$value,$title,$no_error);
								else									
									$block[$i] .= core::formField($info['type'],$info['field'].$extra."-".$num,$value,$title,$no_error);		
								
								$block[$i] .= (($title != "")? '<div class="comment">&nbsp;'.$title.'</div>' : '').
											  ((isset($this->FORM_ERROR[$field.$extra."-".$num]) && $no_error == false)? '<div class="form_error">'.$this->FORM_ERROR[$field.$extra."-".$num].'</div>' : '');
															
								$addafter = "";
									   
							}else						
								$block[$i] .= '<p>'.$this->linkValue($info['field'],$value).'</p>';
							
							if($duplicate)							
								$block[$i] .= '</div>';
							
						}
						
						if(count($fieldinfo > 1) && !$firstc)
							$block[$i] .= '</div>';
							
						$firstc = false;
						
						if(!$this->COMBINEDFIELDS[$field])
							break;
					
					}
										
					$block[$i] .= '</div><div class="clear"></div>'.
								  (($duplicate && $duplicate['limit'] != 1 && ($duplicate['limit'] > count($this->SUB_FORM[$num]) || $duplicate['limit'] == 0))? 
								  '<a class="form_button duplicate_inner" id="dup_inner-'.$duplicate['title'].'">Extra item</a>'.
								  '<div class="clear"></div>' : '').
								  $addafter.'</div>';
					
					$count++;						
						
				}
			
			$block[$i] .= '</div>';
		
		}
		
		// Hidden block used for duplicate calculations
		echo '<div id="block_hidden">'.array_shift($block).'</div>';
		
		// Start of the main form
		echo ($type == "add") ? '<div class="slide_form top_row pointer">'.($this->ADD_TEXT ? $this->ADD_TEXT : 'Item aan "'.$this->TITLE.'" toevoegen').
								'<div id="slide_img">+</div>' 								
							  : '<div class="top_row">'.($this->EDIT_TEXT ? $this->EDIT_TEXT : 'Item aanpassen');

		echo '</div>'.	
			 '<form id="input_form" enctype="multipart/form-data" method="post" action="index.php">'.	
			 '<div id="form_block">'.
			 '<input type="hidden" name="form_type" value="'.$type.'">'.
			 '<input type="hidden" name="page" 		value="'.($_GET['action_override'] ? $_GET['action_override'] : $this->NAME).'">';
		
		$i = 0;
		
		foreach($block as $key => $b){
			
			echo (($i != 0)? 
				 '<div id="form_extra-'.$key.'"><div class="top_row" style="border-top:0;">Nog een item '.(($type == "add")? 'toevoegen' : 'aanpassen').
				 '<div class="floatright"><a href="#" id="dup_remove-'.$key.'" info-id="#form_extra-'.$key.'" class="duplicate_remove"><img src="images/delete.png"></a></div></div>' : '').
				 $b.(($i != 0)? '</div>' : '');	
			
			$i++;
		
		}
		
		echo '</div><input type="submit" class="form_button" value="Opslaan">'.
			 (($type == "add")? '<a class="form_button extra_item">Extra item</a>' : '').
			 (($type == "mod" || $type == "edit" || $type == "mult_edit")? '<a class="form_button" href="index.php?page='.(($_GET['action_override'])? $_GET['action_override'] : $this->NAME).'">Annuleren</a>' : '').
			 '</form>';
			 
		$count = 0;
		
		foreach($this->NOT_USED as $key => $num){
			
			echo '<script type="text/javascript">duplicate_remove(\'form_extra-'.($key-$count).'\')</script>';
			$count++;
			
		}
	
	}
	
	function handleForm(){

		if(count($_POST) > 0 && isset($_POST['form_type']) && isset($_POST['page'])){
			
			// First go through all $_POST variables to determine old files and if we are editing a field,  remove fields that haven't changed
			$check_array = array();
		
			foreach($_POST as $post_key => $post_value){
					
				$tmp 		= explode("-",$post_key);
				$num 		= array_pop($tmp);
				$real_key 	= implode("-",$tmp);
				
				if($_POST['form_type'] != "add")
					if(in_array($real_key,$this->SHOWN_FIELDS))					
						if(sql::exists($this->TABLE,array("id"=>$num,$real_key=>$post_value),true))
							unset($_POST[$post_key]);
											
			}
			
			$dup_handled = array();
	
			// Go through all $_POST variables
			foreach($_POST as $post_key => $post_value){
				
				$tmp = explode("|",$post_key);
				
				$between 	= ($tmp[0] == "upload")? $tmp[1] : $post_key;				
				$full_key	= $tmp_key = explode("-",$between);
				$real_key 	= array_shift($tmp_key);
				$num 		= array_pop($tmp_key);

				// Check if this field is among the fields shown (to prevent form injection)
				if(in_array($real_key,$this->SHOWN_FIELDS) && $num != "original"){
					
					// Handle errors according to the given requirements					
					$this->handleError($post_value,$post_key,$this->FIELDS[$real_key]['req']);
					
					switch($this->FIELDS[$real_key]['type']){
					
						case "textarea|nl2br":
						$post_value = nl2br($post_value);
						break;
						/////////////////
						case "textarea|rich": 
						// Take [x]...[/x] and change it to <span class="x">...</span>
						$post_value = preg_replace('/\[(.*?)\](.*?)\[\/(.*?)\]/is','<span class="$1">$2</span>',$post_value);
						break;
						/////////////////
						case "date":
						if($post_value)
							$post_value = date('Y-m-d H:i:s',strtotime($post_value));
						break;
						
					}
					
					if(count($tmp_key) > 0){
						
						$subnum = array_pop($tmp_key);						
						$this->SUB_FORM[$num][$this->COMBINEDFIELDS[$real_key]][$subnum] = true;
					
					}
					
					if(!$done[$post_key]) // Add this value to the $post array
						if(isset($post[$num][$real_key]))					
							$post[$num][$real_key] .= "####".$post_value;	
						else
							$post[$num][$real_key] = $post_value;			
									
				}elseif($real_key == "form_type")										
					$form_type = $post_value;
				
			}
																												
			if(count($this->FORM_ERROR) == 0){
				
				// Check what kind of form we're looking at and shoot info to database
				
				if(is_array($post) && count($post) > 0){
				
					foreach($this->SLUG as $target => $slug)					
						foreach($post as $id => $info){
						
							$final = '';
							
							foreach(explode(" ",$slug['main']) as $s)
								$final .= $info[$s]." ";
						
							$finalslug = core::slug(trim($final));
							
							if(!sql::exists($this->TABLE,array($target=>$finalslug))){
								
								$post[$id][$target] = $finalslug;
								continue;
						
							}
							
							foreach(explode(" ",$slug['extra']) as $s)
								$final .= $info[$s]." ";
								
							$finalslug = core::slug(trim($final));
							
							if(!sql::exists($this->TABLE,array($target=>$finalslug))){
								
								$post[$id][$target] = $finalslug;
								continue;
						
							}
							
							do{	$finalslug .= '-'.rand(0,9); }	
								while(sql::exists($this->TABLE,array($target=>$finalslug)));
								
							$post[$id][$target] = $finalslug;
							
						}
							
					switch($_POST['form_type']){
					
						case "add":
						if($this->CAN_ADD){
							
							foreach($post as $info)
								sql::insert($this->TABLE,$info);
								
							$title = ($this->TAB) ? $this->TAB.' <span class="paper_p">&gt;</span> '.$this->TITLE 
												  : $this->TITLE;	

							core::notify(((count($post) == 1)? "<h1>Rij toegevoegd!</h1><p>Er is succesvol 1 rij toegevoegd aan ".$title."</p>" 
															   : "<h1>Rijen toegevoegd!</h1><p>Er zijn succesvol ".count($post)." rijen toegevoegd aan ".$title."</p>"));
								
						}
						break;
						/////////////////
						case "mult_edit":
						case "edit":
						if($this->CAN_MOD){
							
							foreach($post as $id => $info)
								sql::update($this->TABLE,$info,"WHERE `id` = '".$id."'");
							
							$title = ($this->TAB) ? $this->TAB.' <span class="paper_p">&gt;</span> '.$this->TITLE 
												  : $this->TITLE;							
							
							core::notify(((count($post) == 1)? "<h1>Rij aangepast!</h1><p>Er is succesvol 1 rij aangepast in ".$title ."</p>"
															   : "<h1>Rijen aangepast!</h1><p>Er zijn succesvol ".count($post)." rijen aangepast in ".$title."</p>"));
								
						}
						break;
					
					}
					
				}
				
				$this->FORM_SUCCESS = true;
			
			}else		
				core::$END_SCRIPT .= "slide_form(0,0);";
		
		}
		
		$this->NUM_FORMS = $num;
		
		if($this->FORM_SUCCESS == true)
			$this->NUM_FORMS = 1;
			
		foreach($_POST as $key => $value){
				
			$tmp = explode("-",$key);			
			$num = $tmp[1];
			
			for($i=2;$i<$this->NUM_FORMS+1;$i++)
				if(!$this->NOT_USED[$i] && !$used[$i])
					$this->NOT_USED[$i] = true;
				
		}			
	
		$this->calculate();
		
	}
	
	function handleError($input,$key,$required){
		
		$required 	= explode("|",$required);
		
		$tmp 		= explode("-",$key);
		$real_key	= $tmp[0];
				
		$title 		= $this->FIELDS[$real_key]['title'];
			
		if(!in_array("!required",$required))
			if($input === ""){

				$this->FORM_ERROR[$key] = 'Dit veld is vereist';
				$this->ERROR_TYPE[$key] = "empty";
				
			}
		
		if(!(in_array("!required",$required) && $input === "")){
		
			foreach($required as $req){
				
				$tmp = explode(":",$req);
				$req = $tmp;
			
				switch($req[0]){
				
					case "min":
					if(strlen($input) < $req[1])
						$this->FORM_ERROR[$key] = '"'.$title.'" moet minimaal '.$req[1].' tekens lang zijn';
					break;
					/////////////////
					case "max":
					if(strlen($input) > $req[1])
						$this->FORM_ERROR[$key] = '"'.$title.'" mag maximaal '.$req[1].' tekens lang zijn';
					break;
					/////////////////
					case "num":
					if(is_numeric($input)){
						
						if($req[1]){
						
							$tmp = explode("-",$req[1]);
							if(!is_numeric($tmp[0])){
								
								if($input > $tmp[1])
									$this->FORM_ERROR[$key] = '"'.$title.'" kan niet hoger zijn dan '.$tmp[1];
									
							}elseif(!is_numeric($tmp[1])){
								
								if($input < $tmp[0])
									$this->FORM_ERROR[$key] = '"'.$title.'" kan niet lager zijn dan '.$tmp[0];
									
							}elseif($input < $tmp[0] || $input > $tmp[1])
								$this->FORM_ERROR[$key] = '"'.$title.'" moet lager zijn dan '.$tmp[1].' en hoger dan '.$tmp[0];	
						
						}
						
					}else						
						$this->FORM_ERROR[$key] = '"'.$title.'" moet een getal zijn';
					break;
					/////////////////
					case "values":
					$tmp = explode('"-"',substr(substr($req[1],1),0,-1));
					
					if(!in_array($input,$tmp)){
						
						$count 					= 1;					
						$this->FORM_ERROR[$key] = '"'.$title.'" kan alleen ';
						
						foreach($tmp as $value){
							
							$this->FORM_ERROR[$key] .= '"'.$value.'"';
							
							if($count == (count($tmp)-1))
								$this->FORM_ERROR[$key] .= ' of ';
							elseif($count != count($tmp))
								$this->FORM_ERROR[$key] .= ', ';
							else
								$this->FORM_ERROR[$key] .= ' zijn';
							
							$count++;
							
						}
	
					}
					break;
									
				}
				
			}
		
		}
	
	}
	
	function printCols(){
	
		echo '<div id="sticktop_placeholder"></div><div id="sticktop" class="top_row'.(($this->CAN_ADD)? ' printvalues' : '').'">'.
			 (($this->CAN_MOD || $this->CAN_DEL)? '<div class="col"><input type="checkbox" name="check_all"></div>' : '');
	
		foreach($this->SHOWN_FIELDS as $field){
			
			$size = explode("|",$this->FIELDS[$field]['size']);
			
			if($size[0] != "hide"){
				
				$title = (($this->COMBINEDFIELDS[$field]) ? 
						 $this->COMBINEDFIELDS[$field]." (".$this->FIELDS[$field]['title'].")" 
						 : $this->FIELDS[$field]['title']);
					
				echo '<div class="col bold" style="width:'.$size[0].((substr($this->FIELDS[$field]['type'],0,3) == "img")? ';text-align:center;' : '').'">'.$title.'</div>';	
			
			}
		
		}
			
		echo (($this->CAN_MOD)? '<div class="col bold floatright"'.(($this->CAN_DEL)? 'style="margin-right:20px"' : '').'>Edit</div>' : '').
			 '</div>';
	
	}
	
	function printValues($inline = false){
		
		$i = 0;	
		
		if(!$inline)
			echo '<form method="post" action="index.php" id="check_form">'.
				 '<input type="hidden" name="page" value="'.$this->NAME.'">'.
				 '<input type="hidden" name="check_type" id="check_form_type" value="mod">'.
				 (($this->CUSTOM_ORDER && !isset($_GET['search']))? '<ul id="sortable">' : '');
			
		$fields = false;
		
		foreach($this->INFO as $entries){

			if($this->CUSTOM_ORDER && !isset($_REQUEST['search'])) 
				echo '<li id="fields_'.$entries['id'].'">';
			
			echo '<div class="row'.(($i&1)? ' odd' : '').((in_array($entries['id'],$this->NEW_ENTRIES))? ' changed' : '').'" id="check_'.$entries['id'].'">';
			
			if($this->CAN_MOD || $this->CAN_DEL)		
				echo '<div class="col"><input type="checkbox" class="field_check" name="check_'.$entries['id'].'"></div>';
			
			$x 			= 1;				
			$print 		= array();				
			$has_image 	= false;
			
			foreach($entries as $field => $value)				
				if(in_array($field,$this->SHOWN_FIELDS) && !is_numeric($field)){

					$value 	= $this->linkValue($field,$value);						
					$size 	= explode("|",$this->FIELDS[$field]['size']);
					
					$substr = 0;
					
					if($size[1]){
					
						$tmp = explode(":",$size[1]);
						
						if(!$tmp[1]){
						
							$px 	= str_replace("px","",$size[0]);
							$substr = floor($px / 5);
						
						}else							
							$substr = $tmp[1];
					
					}
					
					if($substr > 0)
						if(strlen($value) > $substr)					
							$value = substr($value,0,$substr);
					
					$value = strip_tags($value,'<b><i><u>');	
					
					list($field_type) = explode("|",$this->FIELDS[$field]['type']);
					
					$value = array_shift(explode("####",$value));
					
					if($field_type == "img"){
												
						$max_width 	= $size[0];
						$max_height = "50px";							
						$newvalue 	= "";
							
						if((file_exists("../".$value) || file_exists("../../".$value)) && $value != ""){
						
							$max_width 		= $size[0];
							$max_height 	= "50px";
							
							if(file_exists("../../".$value))
								list($w,$h)	= getimagesize("../../".$value);	
							else
								list($w,$h) = getimagesize("../".$value);
																	
							$newvalue 		= '<img title="'.$value.'" alt="'.$value.'" src="../'.$value.'" style="max-width:'.$max_width.';max-height:'.$max_height.';">';
							
							if($w > $max_width || $h > $max_height){
								
								$newvalue 		= '<a class="lightbox_'.$this->LIGHTBOX.'" href="../'.$value.'">'.$newvalue.'</a>';
								$has_lightbox 	= true;
							
							}
	
							$value = $newvalue;
						
						}
						
					}elseif($field_type == "bool")	
										
						$value = ($value == 1) ? '<img src="images/tick.png" id="bool_'.$entries['id'].'"  class="bool_click" rel="'.$field.'|'.$entries['id'].'" />'
											   : '<img src="images/cross.png" id="bool_'.$entries['id'].'" class="bool_click" rel="'.$field.'|'.$entries['id'].'" />';
											  
					elseif($field_type == "date" || $field_type == "date|now"){	
						if($value != "" && $value != "0000-00-00" && $value != "0000-00-00 00:00:00")						
							$value = str_replace('00:00:00','',date('j F Y  H:i:s',strtotime($value)));
						else
							$value = "";
					}elseif($field_type == "color")
						if($value != "")
							$value = '<div class="color_block" style="background-color:#'.$value.';color:#'.$value.';border-color:#'.$value.';">#'.$value.'</div>';

					if($size[0] != "hide"){
						
						if(isset($_REQUEST['search']) && $field == $_REQUEST['search_field'])							
							$value = preg_replace("/(".$_REQUEST['search'].")/i","<span class='found'>$0</span>",$value);						
						
						$print[$field] = '<div class="col" style="width:'.$size[0];
						if(substr($this->FIELDS[$field]['type'],0,3) == "img")
							$print[$field] .= ";text-align:center;";
						$print[$field] .= '">'.$value.'</b></i></u></div>';
					
					}
					
				}
			
			if($has_lightbox)
				$this->LIGHTBOX++;
			
			foreach($this->SHOWN_FIELDS as $field){
				
				$fields = true;
				echo $print[$field];
			
			}
			
			$pagename = ($_GET['action_override']) ? $_GET['action_override'] 
												   : core::$PAGE->NAME;
				
			$url = 'index.php?page='.$pagename.'&id='.$entries['id'].'&select='.$this->SELECTOR;
			
			if($this->CAN_DEL)
				echo '<div class="col floatright" style="margin:0 0 0 5px;"><a class="confirm_del" href="'.$url.'&action=del"><img src="images/delete.png" alt="delete"></a></div>';
			
			if($this->CAN_MOD)
				echo '<div class="col floatright"><a href="'.$url.'&action=edit"><img src="images/edit.png" alt="edit"></a></div>';
			
			echo '</div>';
			
			if($this->CUSTOM_ORDER && !isset($_REQUEST['search']))
				echo '</li>';
				
			$i++;
					
		}
		
		if($inline)
			return;
			
		if($this->CUSTOM_ORDER && !isset($_GET['search'])) 
			echo '</ul>';
		
		echo '</form>';
		
		if(!$fields)				
			echo '<div class="row">Geen velden gevonden</div>';	
		else{
			
			if($this->LOAD_MORE)
				echo '<div id="ajax_loader" '.$this->LOAD_MORE.'>Loading</div>';
			
			if($this->CAN_DEL || $this->CAN_MOD)
				echo '<div id="stickbot_placeholder"></div><div id="stickbot" class="top_row">';
				
			if($this->CAN_DEL)
				echo '<a class="form_button confirm_multdel">Verwijderen</a>';
		
			if($this->CAN_MOD)
				echo '<a class="form_button check_submit">Aanpassen</a>';
				
			if($this->CAN_MOD || $this->CAN_DEL)
				echo '<div class="select_text">Met geselecteerd:</div> </div>';
			
		}
	
	}
	
	function printModules($position){
		
		if(is_array($this->MODULES[$position])){
			
			echo '<div class="module">';
			
			if($position == "right_bot")
				echo '<div class="spacer _special"></div>';
			
			$counter = 0;
			
			foreach($this->MODULES[$position] as $module){
				
				$tmp		  = explode("\n",$module);
				$conds		  = $tmp[1];
				
				$tmp 		  = explode(",",$tmp[0]);				
				$module_title = $tmp[1];
				
				$module 	  = explode("|",$tmp[0]);
				$module_name  = $module[0];
				
				switch($module_name){
				
					case "structure":
					
						echo '<div class="top_row">'.($module_title ? $module_title : 'Table Structure').'</div>';
						
						$table_info = sql::getColumns($this->TABLE);	
						
						$i = 0;
						
						foreach($table_info as $field => $info){
						
							echo '<div class="row'.($i & 1 ? ' odd ': '').'">'.
								 '<h1'.($info['Key'] == "PRI" ? ' style="text-decoration:underline;"' : '').'>'.$field.'</h1>'.
								 '<h2>'.$info['Type'].'</h2>'.
								 (!empty($info['Comment']) ? '<h2><i>&ldquo;'.$info['Comment'].'&rdquo;</i></h2>' : '').
								 '</div>';
								 
							$i++;
						
						}	
					
					break;
					/////////////////
					case "sum":
					
						echo '<div class="top_row">'.($module_title ? $module_title : 'Totaal').'</div>';
						
						if(!$this->SELECTOR || $this->SELECTOR == "show_all")					
							list($count) = mysql_fetch_row(mysql_query(
										   "SELECT SUM(`".sql::escape($module[1])."`) 
											FROM `".$this->TABLE."` ".
											preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$this->CONDITIONS." ".$conds),1)));
						else
							list($count) = mysql_fetch_row(mysql_query(
										   "SELECT SUM(`".sql::escape($module[1])."`) 
											FROM `".$this->TABLE."` ".
											preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$this->CONDITIONS." ".$conds." && `".$this->SELECTOR_FIELD."` = '".$this->SELECTOR."'"),1)));
											
						if(!$count)
							$count = 0;
							
						echo '<div class="row">'.
							 '<div class="col">'.($this->FIELDS[$module[1]]['title'] ? $this->FIELDS[$module[1]]['title'] : $module[1]).'</div><div class="col floatright">'.$count.'</div></div>';
							
					break;	
					/////////////////
					case "counter":
					
						echo '<div class="top_row">'.($module_title ? $module_title : 'Counter').'</div>';
						
						if(!isset($module[1])){
							
							if(!$this->SELECTOR || $this->SELECTOR == "show_all")							
								$count = sql::num($this->TABLE,$this->CONDITIONS." ".$conds);
							else
								$count = sql::num($this->TABLE,$this->CONDITIONS." ".$conds." && `".$this->SELECTOR_FIELD."` = '".$this->SELECTOR."'");
								
							echo '<div class="row">'.
								 '<div class="col">Fields:</div><div class="col floatright">'.$count.'</div></div>';
							
						}else{
						
							$tmp = sql::fetch("array",$this->TABLE,$this->CONDITIONS." ".$conds." GROUP BY `".$module[1]."`");
							
							if(isset($module[2])){
								
								$i = 1;
								
								foreach($tmp as $info){
													
									$title = $this->linkValue($module[1],$info[$module[1]]);
									
									echo '<div class="row'.
										 (($i&1)? ' odd' : '').
										 '"><h1>'.$this->FIELDS[$module[1]]['title'].': '.$title.'</h1></div>';
									
									$conditions = "WHERE `".$module[1]."` = '".$info[$module[1]]."'";								
									$sub_info 	= sql::fetch("array",$this->TABLE,$conditions." ".$this->CONDITIONS." ".$conds." ORDER BY `".$module[2]."` ASC");
									
									foreach($sub_info as $sub)							
										if(!$values[$sub[$module[2]]]['count'])
											$values[$sub[$module[2]]]['count'] = 1;
										else
											$values[$sub[$module[2]]]['count']++;
											
									$i++;
											
									foreach($values as $field => $value){						
										
										echo '<div class="row'.
											 (($i&1)? ' odd' : '').
											 '" style="clear:both;">'.
											 '<div class="col">'.$field.'</div><div class="col floatright">'.$value['count'].'</div></div>';
										
										$i++;
									
									}
										
									unset($values);
								
								}
								
							}else{													
								
								$i = 0;
								
								foreach($tmp as $info){
													
									$title = $this->linkValue($module[1],$info[$module[1]]);
								
									echo '<div class="row'.
										 (($i&1)? ' odd' : '').
										 '"><div class="col"><h1>'.$this->FIELDS[$module[1]]['title'].': '.$title.'</h1></div>';
									
									$conditions = "WHERE `".$module[1]."` = '".$info[$module[1]]."'";								
									$sub_info	= sql::fetch("array",$this->TABLE,$conditions." ".$this->CONDITIONS." ".$conds." ORDER BY `".$module[1]."` ASC");
									
									echo '<div class="col floatright">'.count($sub_info).'</div></div>';
									
									$i++;
								
								}
							
							}
						
						}
						
					break;	
					/////////////////
					case "selector":
					
						echo '<div class="top_row">'.($module_title ? $module_title : $this->FIELDS[$module[1]]['title']).'</div>';
						
						$i 	  = 0;	
						$tmp  = sql::fetch("array",$this->TABLE,$this->CONDITIONS." GROUP BY `".$module[1]."` ORDER BY `".$module[1]."` ASC");									
						$link = explode("|",$this->FIELDS[$module[1]]['link']);	
						
						if($link[0] != "")
							$tmp = sql::fetch("array",$link[0],$this->CONDITIONS." GROUP BY `id`");
						
						foreach($tmp as $info){
														
							$wherefield = (($link[0] == $this->TABLE)? $module[1] : $link[1]);
							
							if($link[0] != ""){
								
								$select_value = $info['id'];
								$value 		  = $this->linkValue($module[1],$info['id']);
																
								if(sql::num($link[0],"WHERE `".$wherefield."` = '".$info['id']."'") <= 0)
									continue;
								
							}else								
								$select_value = $value = $info[$module[1]];
							
							if($value){
								
								echo '<div class="row'.
									 (($i&1)? ' odd' : '').
									 (($this->SELECTOR == $select_value)? ' bold' : '').
									 '"><a href="index.php?page='.core::$PAGE->NAME.'&select='.$select_value.'"'.
									 (($this->SELECTOR == $select_value)? ' class="marked" ' : '').
									 '>'.$value.'</a></div>';
								
								$i++;
							
							}
						
						}
						
						echo '<div class="row'.
							 (($i&1)? 							' odd'	: '').
							 (($this->SELECTOR == "show_all")? 	' bold' : '').
							 '"><a href="index.php?page='.core::$PAGE->NAME.'&select=show_all"'.
							 (($this->SELECTOR == "show_all")? 	' class="marked"' : '').
							 '>Show all</a></div>';

					break;
					/////////////////
					case "exporter":
					
						echo '<div class="top_row">'.($module_title ? $module_title : 'Exporter').'</div>
							 <div class="form_block">
							 
								<form method="post" action="'.$_SERVER["PHP_SELF"].'">
								<input type="hidden" name="export_form" value="true" />
								<div class="row">
								
									<div class="left">
										Selecteer velden:
									</div>
									
									<div class="log_right">';
						
						foreach($this->FIELDS as $field => $info)
							if(in_array($field,$this->SHOWN_FIELDS) && $field != false)
								echo 	'<div class="right">
							
											<input type="checkbox" id="exp_check_'.$field.'" name="exp_field_'.$field.'" value="true" '.
											((!$_POST['export_form'] || $_POST['exp_field_'.$field])? 'checked="checked"' : '').'> 
											<label for="exp_check_'.$field.'" style="position:relative;bottom:2px;">'.$info['title'].'</label>
									
										</div> ';
										
						echo		'</div>
						
								</div>
								
								<div class="form_divider"></div>
								
								 <div class="row">
								 
									 <input type="hidden" name="page" value="'.$this->NAME.'">
									 <input type="hidden" name="selector" value="'.$this->SELECTOR.'">
									 
									 <div class="left">
										Exporteer data als:
									 </div> 
									 
									 <div class="right">
									 
										 <select name="export_type">
										 
											<option value="excel">Excel</option>
											<option value="txt" '.(($_POST['export_type'] == "txt")? 'selected="selected"' : '').'>Tekst</option>
											
										 </select> 
										 
										 <input name="export_submit" type="submit" value="Export" class="form_button" style="margin-top:0;padding:1px 3px !important;" />
										 
									 </div>
																		 
								 </div>
								 
								 </form>
								 
							 </div>';
						
						if($_POST['export_submit']){
							
							$select 	= ($this->SELECTOR && $this->SELECTOR != "show_all")? "&& `".$this->SELECTOR_FIELD."` = '".$this->SELECTOR."'" : '';					
							$info 		= sql::fetch("array",$this->TABLE,$this->CONDITIONS.$select." ORDER BY ".$this->ORDER);
							$content 	= "";						
							$count 		= 1;
													
							switch($_POST['export_type']){
							
								case "txt":
								foreach($info as $inside){
									
									$content .= "[".$count."]\r\n".
												"--------------";						
									foreach($inside as $key => $c)									
										if(in_array($key,$this->SHOWN_FIELDS) && $key != false && $_POST['exp_field_'.$key]){
											
											if($this->FIELDS[$key]['link'])											
												$c = $this->linkValue($key,$c);
												
											$count++;
											$c		  = str_replace('&euro;','€',$c); // convert &euro; to euro symbol
											$content .= "\r\n".$this->FIELDS[$key]['title'].": ".
														strip_tags(preg_replace('/(<br\s*\/?>\s*)/',"\r\n",$c))."\r\n";
											
										}
										
									$content .= "--------------\r\n";	
									
								}
								
								$file = fopen('tmp/'.sha1(md5(time())).'.txt', 'w');
								fwrite($file, $content);
								fclose($file);
								
								core::$END_SCRIPT .= '$(function(){ location.href = \'ajax/create_file.php?type=txt&id='.sha1(md5(time())).'\' });';
								break;
								
								case "excel":
								$fields = array();
								foreach($info as $inside){	
												
									foreach($inside as $key => $c)						
										if(in_array($key,$this->SHOWN_FIELDS) && $key != false && $_POST['exp_field_'.$key]){
											
											$fields[$key] = true;	
											
											if($this->FIELDS[$key]['link'])											
												$c = $this->linkValue($key,$c);
												
											$count++;
											$content .= preg_replace('/(<br\s*\/?>\s*)/',"",$c)."\t";
											
										}
									
									$content .= "\n";
									
								}
								
								$file 	= fopen('tmp/'.sha1(md5(time())).'.txt', 'w');
								$tmp 	= "";
								
								foreach($fields as $key => $null)
									if(in_array($key,$this->SHOWN_FIELDS) && $key != false)
										$tmp .= $this->FIELDS[$key]['title']."\t";
										
								$content = $tmp."\n".$content;
								fwrite($file, $content);
								fclose($file);
								
								core::$END_SCRIPT .= '$(document).ready(function(){ location.href = \'ajax/create_file.php?type=excel&id='.sha1(md5(time())).'\' });';
								break;
								
							}
							
						}
					
					break;
				
				}
				
				$counter++;
				
				if(count($this->MODULES[$position]) != $counter)
					echo '<div class="spacer"></div>';
				
			}
			
			if($position == "right_top" || $position == "right_mid")
				echo '<div class="spacer _special"></div>';
				
			echo '</div>';
		
		}
		
	}
	
	function handleSelector(){
		
		$selector = $_POST['selector'];		
		$continue = false;
		
		foreach($this->MODULES as $positions)			
			foreach($positions as $module){
			
				$module = explode("|",$module);
				if($module[0] == "selector"){
					
					$continue 			  = true;
					$this->SELECTOR_FIELD = $module[1];	
					
				}
				
			}				
		
		if($continue == true && $selector)			
			$this->SELECTOR = $selector;			
		elseif($continue == true){
			
			if(isset($_GET['select']) && $_GET['select'] != '')
				$select = $_GET['select'];
			elseif(isset($_GET['search']))						
				$select = "show_all";
			elseif(isset($_POST['form_type'])){
					
				foreach($_POST as $key => $value)
					$flat .= $key.",";
				
				preg_match("/".$module[1]."-[0-9]{0,}/",$flat,$return);
				
				$select = sql::escape($_POST[$return[0]]);
				
				if($select == "")
					$select = sql::escape($_POST[$return[0].'-original']);
				
			}elseif(isset($_POST['check_type'])){
				
				$select_id = 0;
				
				foreach($_POST as $key => $p)						
					if(preg_match("/check_[0-9]{0,}/",$key,$return) && !$select_id)
						list($empty,$select_id) = explode("_",$return[0]);
				
				$info 	= sql::fetch("array",$this->TABLE,"WHERE `id` = '".sql::escape($select_id)."'");
			
				$select = sql::escape($info[0][$this->SELECTOR_FIELD]);
				
			}else{

				$link = explode("|",$this->FIELDS[$this->SELECTOR_FIELD]['link']);	
					
				if($link[0] != "")
					list($tmp) = sql::fetch("array",$link[0],$this->CONDITIONS." GROUP BY `id`");
				
				$select = $this->CONDITIONS ? $tmp['id']
											: "show_all";
				
				if($select == ""){
				
					$reqs = explode("|",$this->FIELDS[$this->SELECTOR_FIELD]['req']);
					
					foreach($reqs as $req){
					
						list($req_name,$req_value) = explode(":",$req);
						if($req_name == "values")						
							list($select) = explode('"-"',$req_value);
						
						$select = str_replace('"','',$select);
						
					}
					
				}
				
			}

			$this->SELECTOR = $select;
			
		}
		
	}

}

class unique_page {

	public $TITLE;
	public $NAME;
	public $FILE;
	
	function draw_page(){
	
		include $this->FILE;
		
	}
	
}

/*
stats_page
Work in progress
*/
class stats_page {

	private $FIELDS = array();
	private $_SQL;
	private $GRAPHS;
	
	public $STATS_PAGE = true;
	public $TITLE;
	public $NAME;
	public $TAB;
	public $END_SCRIPT;
	
	public function setSql($sql){
	
		$this->_SQL = $sql;
		
	}
	
	public function addField($title,$field,$type,$subtype=""){
	
		$this->FIELDS[$field] = array("title"=>$title,
									  "field"=>$field,
									  "type"=>$type,
									  "subtype"=>$subtype);
		
	}
	
	public function addGraph($id,$title,$table,$x_axis,$y_axis,$order,$width,$height){
		
		$this->GRAPHS[$id]['x'] 	= $x_axis;
		$this->GRAPHS[$id]['y'] 	= $y_axis;
		$this->GRAPHS[$id]['title'] = $title;
		$this->GRAPHS[$id]['w'] 	= $width;
		$this->GRAPHS[$id]['h'] 	= $height;
		$this->GRAPHS[$id]['table'] = $table;
		$this->GRAPHS[$id]['order']	= $order;
	}
	
	public function calculate(){
		
		foreach($this->GRAPHS as $graph){

			list($x) = explode("|",$graph['x']);
			list($y) = explode("|",$graph['y']);

			$this->FIELDS[$x]['info'] = sql::fetch("array",$graph['table'],"ORDER BY ".$graph['order']);
			$this->FIELDS[$y]['info'] = sql::fetch("array",$graph['table'],"ORDER BY ".$graph['order']);
			
		}
		
	}
	
	public function draw(){
	
		foreach($this->GRAPHS as $id => $graph){	
			
			$opt = 1;
			
			$block[$id] = '<div id="graph-'.$id.'" style="width:'.$graph['w'].'px;height:'.$graph['h'].'px;"></div>';
		
			$script = '$.jqplot("graph-'.$id.'",  [[';
			
			list($x,$x_subtype) = explode("|",$graph['x']);
			list($y,$y_subtype) = explode("|",$graph['y']);
			
			$x_field = $this->FIELDS[$x];
			$y_field = $this->FIELDS[$y];
			
			// Build Coordinates
			foreach($x_field['info'] as $info)			
				$x_pts[] = $info[$x];
				
			foreach($y_field['info'] as $info)			
				$y_pts[] = $info[$y];
				
			if($y_subtype && $y_field['type'] == "num"){
			
				$tmp = array();
			
				foreach($x_pts as $key => $x)
					if($tmp[$x])
						$tmp[$x]++;
					else
						$tmp[$x] = 1;

				$new_x = array();
				$new_y = array();
				
				foreach($tmp as $key => $count){	

					$new_x[] = $key;				
					$y = 0;
					
					for($i=1;$i<=$count;$i++)				
						$y += array_shift($y_pts);	
					
					if($y_subtype == "avg")
						$new_y[] = $y/$count;
					elseif($y_subtype == "sum")
						$new_y[] = $y;
						
				
					
				}
				
				$x_pts = $new_x;
				$y_pts = $new_y;
				
			}
				
			foreach($x_pts as $key => $x){

				$y = $y_pts[$key];

				if(!is_numeric($x))
					$x = "'$x'";
					
				if(!is_numeric($y))
					$y = "'$y'";
					
				$script .= '['.$x.','.$y.'],';
				
			}
			
			// Build Options
			$script = substr($script,0,-1);
			
			$options = array();
			$axe_options = array();
			
			if($x_field['type'] == "date"){
				$axe_options['x'] .= 'renderer:$.jqplot.DateAxisRenderer';
				$axe_options['x'] .= ',tickOptions: {formatString:\'%#d %b, %Y\'}';
			}
			
			if($y_field['type'] == "date"){
				$axe_options['y'] .= 'renderer:$.jqplot.DateAxisRenderer';
				$axe_options['y'] .= ',tickOptions: {formatString:\'%#d %b, %Y\'}';
			}

			$options[$opt] = "title: '".$graph['title']."'";
			$opt++;				
						
			if($axe_options){
			
				$options[$opt] = 'axes:{';
				
				if($axe_options['x'])
					$options[$opt] .= 'xaxis:{'.$axe_options['x'].'}';
					
				if($axe_options['x'] && $axe_options['y'])
					$options[$opt] .= ',';
					
				if($axe_options['y'])
					$options[$opt] .= 'yaxis:{'.$axe_options['y'].'}';
			
				$options[$opt] .= '}';	
				
				$opt++;
			}
			
			$script .= ']]';
			
			if(count($options) > 0){
			
				$script .= ',{';
				foreach($options as $o)
					$script .= $o.",";
				$script = substr($script,0,-1);
				$script .= '}';
				
			}			
			
			$script .= ');';
		
		}
		
		// Draw Blocks
		foreach($block as $b)
			echo $b;	

		echo '<script type="text/javascript">'.
			 '$(document).ready(function(){
				
				 $.jqplot.config.enablePlugins = true;
				 '.$script.'
				 
			  });
			  </script>';
		
	}
	
}
?>