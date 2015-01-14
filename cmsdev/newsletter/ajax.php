<?
require '../config.php';
require 'config.php';

if($_POST['template']){
	
	// Define general variables
	list($name,$table,$file,$titlefield,$titletype,$title) = explode("|",$_POST['template']);
	
	define(LINKSTYLE,$templates[$name]['linkstyle']);
	
	if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE '".sql::escape($table)."'")) || !file_exists($file))
		exit;
		
}

if($_POST['id'])
	if(!sql::exists($table,array("id"=>$_POST['id'])))
		exit;
	
if($_POST['action'] == "select_template" && $_POST['template']){
	
	// Generate template newsletters
	echo '<option></option>';

	foreach(sql::fetch("array",$table) as $info)
		echo '<option value="'.$info['id'].'">'.$info[$titlefield].'</option>';
	
}elseif($_POST['action'] == "load_preview" && $_POST['template'] && $_POST['id'])	

	// Load preview
	echo '<iframe src="newsletter/user/view.php?temp='.$name.'&id='.$_POST['id'].'" frameborder="0"></iframe>';	
	
elseif($_POST['action'] == "load_stats" && $_POST['template'] && $_POST['id'])

	// Load stats
	require 'stats.php';
	
elseif($_POST['action'] == "load_log" && $_POST['template'] && $_POST['id'])

	// Load stats
	require 'log.php';
	
elseif($_POST['action'] == "send_single" && $_POST['email'] && $_POST['template'] && $_POST['id']){
	
	// Send single e-mail	
	if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
		die('<h1>Fout opgetreden</h1><p>Onjuist e-mailadres ingevuld</p>');
		
	$html = file_get_contents($file);
	$info = sql::fetch("object",$table,"WHERE `id` = '".sql::escape($_POST['id'])."'");

	define(SENDDATE,	date('Y-m-d H:i:s'));

	send_email($_POST['email'], // email
			   SUBJECT.($titletype == 'title' ? $title : $info->{$titlefield}), // subject
			   stat_image($name,$_POST['id'],$_POST['email']).fill_template($name,$html,$info,sql::fetch("object",PEOPLE_TABLE,"WHERE `".EMAIL_FIELD."` = '".sql::escape($_POST['email'])."'"))); // message
	
	sql::insert(LOG_TABLE,array("template"		=> $name,
								"letter"		=> $_POST['id'],
								"email"			=> $_POST['email'],
								"datetime"		=> SENDDATE,
								"type"			=> "Enkel"));

	echo '<h1>Succes!</h1><p>Nieuwsbrief succesvol verstuurd naar '.$_POST['email'].'</p>';
	
}elseif($_POST['action'] == "send_re" && $_POST['emails'] && $_POST['template'] && $_POST['id']){
	
	// Resend e-mails
	define(SENDDATE,date('Y-m-d H:i:s'));		
	
	$count = 0;
	
	foreach(explode(",",$_POST['emails']) as $email){
		
		if($email == "")
			continue;
		
		if(!filter_var($email,FILTER_VALIDATE_EMAIL))
			die('<h1>Fout opgetreden</h1><p>Onjuist e-mailadres ingevuld</p>');
			
		$html = file_get_contents($file);
		$info = sql::fetch("object",$table,"WHERE `id` = '".sql::escape($_POST['id'])."'");
	
		send_email($email, // email
				   SUBJECT.($titletype == 'title' ? $title : $info->{$titlefield}), // subject
				   stat_image($name,$_POST['id'],$email).fill_template($name,$html,$info,sql::fetch("object",PEOPLE_TABLE,"WHERE `".EMAIL_FIELD."` = '".sql::escape($email)."'"))); // message
		
		sql::insert(LOG_TABLE,array("template"		=> $name,
									"letter"		=> $_POST['id'],
									"email"			=> $email,
									"datetime"		=> SENDDATE,
									"type"			=> "Opnieuw versturen"));
		
		$count++;
								
	}

	echo '<h1>Succes!</h1><p>Nieuwsbrief succesvol verstuurd naar '.$count.' e-mailadressen</p>';
	
}elseif($_POST['action'] == "send_mult" && $_POST['group'] && $_POST['template'] && $_POST['id']){
	
	// Send group e-mail		
	$html	= file_get_contents($file);
	$info 	= sql::fetch("object",$table,"WHERE `id` = '".sql::escape($_POST['id'])."'");	
	$groups = explode(",",substr(sql::escape($_POST['group']),0,-1));
	$extra 	= "WHERE ";
	
	foreach($groups as $group)
		$extra .= "`".GROUP_FIELD."` = '".$group."' ||";
		
	$extra 	= substr($extra,0,-2);
	$people = ($_POST['group'] == "all")? sql::fetch("array",PEOPLE_TABLE)
										: sql::fetch("array",PEOPLE_TABLE,$extra);
		
	define(SENDDATE,date('Y-m-d H:i:s'));
	$sent   = array();
	$count 	= 0;
	
	foreach($people as $i){
	
		if(!filter_var($i[EMAIL_FIELD],FILTER_VALIDATE_EMAIL))
			continue;
	
		if($sent[$i[EMAIL_FIELD]])			
			continue;
		else
			$sent[$i[EMAIL_FIELD]] = true;
			
		$group = ($_POST['group'] == "all")? $group = "Iedereen"
										   : $_POST['group'];
							
		$tmp   = explode(",",$group);
		
		if(array_pop($tmp) == "")
			$group = substr($group,0,-1);
			
		if(sql::exists(LOG_TABLE,array("template"	=> $name,
									   "letter"		=> $_POST['id'],
									   "email"		=> $i[EMAIL_FIELD])))
			echo $i[EMAIL_FIELD]."#";		
		else{
			
			send_email($i[EMAIL_FIELD], // email
					   SUBJECT.($titletype == 'title' ? $title : $info->{$titlefield}), // subject
					   stat_image($name,$_POST['id'],$i[EMAIL_FIELD]).fill_template($name,$html,$info,$i)); // message
			
			sql::insert(LOG_TABLE,array("template"		=> $name,
									"letter"		=> $_POST['id'],
									"email"			=> $i[EMAIL_FIELD],
									"datetime"		=> SENDDATE,
									"type"			=> "Groep: ".$group));
									
			$count++;		
				   
		}
	
	}
	
	
	echo '|';
	
	if($count > 0)
		echo '<h1>Succes!</h1><p>Nieuwsbrief succesvol verstuurd naar '.$count.' e-mailadressen</p>';
	
}else
	die('<h1>Fout opgetreden</h1><p>Er mist informatie, heeft u alles ingevuld?</p>');
?>