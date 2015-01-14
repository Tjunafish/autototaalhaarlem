<?
function send_email($to,$subject,$message){
		
	$m = new phpmailer;

	$m->From 		= FROM_EMAIL;
	$m->FromName 	= FROM_NAME;	
	$to 			= explode(",",$to);
	
	if(count($to) == 1){
		
		list($email,$name) = explode("|",$to[0]);	
		if(!$email)
			$email = $to[0];
		$m->AddAddress($email,$name);
		$m->SingleTo = true;
	
	}else{
		
		$m->AddAddress(FROM_EMAIL,FROM_NAME);
	
		foreach($to as $tmp)
			if($tmp != ""){
				
				list($email,$name) = explode("|",$tmp);	
				$m->AddBcc($email,$name);
				
			}
		
	}
		
	$m->AddReplyTo(REPLY_EMAIL);
	
	if($attachment)
		$m->AddAttachment($attachment,$aname);
	
	$m->Body = $message;
				
	$m->IsHTML(true);
	$m->Subject = $subject;
	
	$m->send();
	
}

function stat_image($temp,$id,$email){

	return '<img src="'.ROOT.CMSROOT.'/newsletter/counter.php?tmp='.$temp.'&id='.$id.'&stamp='.strtotime(SENDDATE).'&email='.$email.'" />';
	
}

// fill_template
// Insert html with pointers and an $info object or array containing the needed variables
// ======================================================================================
// $$$$field$$$$					data from users table
// @@@@field@@@@					data from template table
// %%%%name%%%%						start of a block
// %%%%/name%%%%					end of a block
// ????table?field?input?output???? DB data from table, where field = input, echo output
// !!!!name!!!!						one of the following prespecified blocks:
//									signoutlink
//									viewonline
// ^^^^special^var1^var2^etc^^^^ 	start of special block with parameters
// ^^^^/special^^^^					end of special block
//									Available special blocks:
//									substr start end
//									numformat dec thousands_separator dec_separator

function fill_template($name,$html,$info,$user=false){

	$info = (array)$info;
	if($user && is_object($user))
		$user = (array)$user;
		
	if(!is_array($user) && filter_var($user,FILTER_VALIDATE_EMAIL) && sql::exists(PEOPLE_TABLE,array(EMAIL_FIELD => $user)))
		list($user) = sql::fetch("array",PEOPLE_TABLE,"WHERE `".EMAIL_FIELD."` = '".sql::escape($user)."'");
	elseif(!is_array($user))
		$user 		= false;
		
	// Match prespecified fields
	preg_match_all('/\!\!\!\!(.*?)\!\!\!\!/',$html,$fields);

	$data = array();
	foreach($fields[1] as $key => $field)
		switch($field){
			
			case "signoutlink":
			$data[$key] = ROOT.'unsubscribe/'.$name.'/'.$info['id'].'/'.(($user[EMAIL_FIELD])? $user[EMAIL_FIELD] : '&lt;email&gt;');
			break;
			
			case "viewonline":
			$data[$key] = ROOT.'nieuwsbrief/'.$name.'/'.$info['id'].'/'.(($user[EMAIL_FIELD])? $user[EMAIL_FIELD] : '&lt;email&gt;');
			break;
			
			case "root":
			$data[$key] = ROOT;
			break;
			
		}
	
	for($i = 0;$i < count($data);$i++)
		$html = preg_replace('/\!\!\!\!(.*?)\!\!\!\!/',$data[$i],$html,1);
		
	// Match unique fields outside of blocks
	$rest = preg_replace('/%%%%(.*?)%%%%(.*?)%%%%\/\1%%%%/sm','',$html);	
	preg_match_all('/@@@@(.*?)@@@@/',$rest,$fields);
	
	// Populate unique fields into array and then fill them with data from $info
	$data = array();
	foreach($fields[1] as $field)	
		$data[$field] = $info[$field];
				
	foreach($data as $field => $val)
		$html = preg_replace('/@@@@('.preg_quote($field).')@@@@/',$val,$html);
		
	// Match db fields outside of blocks
	preg_match_all('/\?\?\?\?(.*?)\?(.*?)\?(.*?)\?(.*?)\?\?\?\?/',$rest,$fields);
	
	$data = array();
	foreach($fields[1] as $key => $table)		
		$data[$key] = sql::fetch("object",$table,"WHERE `".$fields[2][$key]."` = '".$info[$fields[3][$key]]."'")->{$fields[4][$key]};
		
	foreach($data as $val)
		$html = preg_replace('/\?\?\?\?(.*?)\?(.*?)\?(.*?)\?(.*?)\?\?\?\?/',$val,$html,1);
		
	// Match personal data fields and fill them with data from $user
	preg_match_all('/\$\$\$\$(.*?)\$\$\$\$/',$html,$fields);

	$data = array();
	foreach($fields[1] as $key => $field)
		$data[$key] = ($user[$field])? $user[$field] 
									 : '&lt;'.$field.'&gt;';
	
	foreach($data as $val)
		$html = preg_replace('/\$\$\$\$(.*?)\$\$\$\$/',$val,$html,1);
		
	// Match blocks
	preg_match_all('/%%%%(.*?)%%%%(.*?)%%%%\/\1%%%%/sm',$html,$blocks);
	
	foreach($blocks[1] as $key => $block){
	
		$blockhtml = $blocks[2][$key];
			
		// Match unique fields within each block
		preg_match_all('/@@@@(.*?)@@@@/',$blockhtml,$fields);					

		// Determine amount of blocks
		foreach($fields[1] as $field){
		
			$tmp = count(explode("####",$info[$field]));
			if($tmp > $blockcount)
				$blockcount = $tmp;
			
		}
		
		// Match db fields within each block
		preg_match_all('/\?\?\?\?(.*?)\?(.*?)\?(.*?)\?(.*?)\?\?\?\?/',$blockhtml,$dbfields);
		
		// Determine amount of blocks
		foreach($dbfields[1] as $subkey => $table){
		
			$tmp = count(explode("####",$info[$dbfields[3][$subkey]]));
			if($tmp > $dbblockcount)
				$dbblockcount = $tmp;
			
		}
		
		$blockcount += $dbblockcount;
	
		// Populate data for each block
		$data  = array();
		$empty = array();
		
		for($x = 0;$x < $blockcount;$x++)		
			foreach($fields[1] as $subkey => $field){
				
				$tmp 				= explode("####",$info[$field]);
				$data[$x][$subkey] 	= $tmp[$x];
				$empty[$x]			= empty($tmp[$x]);

			}		
		
		// Populate data for each block		
		$dbdata = array();
		
		for($y = 0;$y < $blockcount;$y++)		
			foreach($dbfields[1] as $subkey => $table){		
				
				$tmp 				 = explode("####",$info[$dbfields[3][$subkey]]);
				$dbdata[$y][$subkey] = @sql::fetch("object",$table,"WHERE `".$dbfields[2][$subkey]."` = '".$tmp[$y]."'")->{$dbfields[4][$subkey]};
				
				if($empty[$y])
					$empty[$y] = empty($dbdata[$y][$subkey]);

			}
		
		// Replace block html with pointers for fields
		$replace = '';
		
		for($x = 0;$x < count($data)+count($dbdata);$x++)
			$replace .= '%%%%BLOCK-'.$x.'%%%%';
			
		$html = preg_replace('/%%%%(.*?)%%%%(.*?)%%%%\/\1%%%%/sm',$replace,$html,1);
				
		// Go through pointers and fill them with data from $info
		for($x = 0;$x < count($data)+count($dbdata);$x++){
			
			if($empty[$x])
				$tmp = '';
			else{	
					
				$tmp = $blockhtml;
				
				// Fill unique fields
				for($i = 0;$i < count($data[$x]);$i++)						
					$tmp = preg_replace('/@@@@(.*?)@@@@/',$data[$x][$i],$tmp,1);
				
				// Fill db fields
				for($i = 0;$i < count($dbdata[$x]);$i++)
					$tmp = preg_replace('/\?\?\?\?(.*?)\?(.*?)\?(.*?)\?(.*?)\?\?\?\?/',$dbdata[$x][$i],$tmp,1);
				
			}
						
			$html = str_replace('%%%%BLOCK-'.$x.'%%%%',$tmp,$html);
		
		}
		
	}
	
	// Go through all special blocks
	preg_match_all('/\^\^\^\^(.*)\^\^\^\^(.*)\^\^\^\^\/(.*)\^\^\^\^/Usm',$html,$fields);

	foreach($fields[0] as $num => $replace){
	
		$special = explode("^^",$fields[1][$num]);
		$input	 = $fields[2][$num];

		if($input)
			switch($special[0]){
			
				case "substr":
				$output = substr(html_entity_decode($input),$special[1],$special[2]);
				break;
				
				case "numformat":
				$output = number_format($input,$special[1],$special[2],$special[3]);
				break;
				
				case "slug":
				$output = html_entity_decode(utf8_encode($input),ENT_COMPAT,"UTF-8");
	
				setlocale(LC_CTYPE, 'en_US.UTF-8');
				$output = iconv("UTF-8","ASCII//TRANSLIT",$output);
				
				$output = strtolower(str_replace(' ','-',trim(preg_replace('/[^a-zA-Z0-9]+/',' ',$output))));
				break;
				
				default:
				$output = $input;
				break;
			
			}
		
		$html = str_replace($replace,$output,$html);
	
	}
	
	// Style unstyled links
	libxml_use_internal_errors(true);
	
	$dom 	= new DOMDocument();
	$dom->loadHtml($html);
	$dom->normalizeDocument();	
	$xpath 	= new DOMXPath($dom);
	
	foreach($xpath->query('//a[not(@style)]') as $node)
		$node->setAttribute('style',LINKSTYLE);
	
	$html = $dom->saveHTML();
		
	return $html;

}
?>