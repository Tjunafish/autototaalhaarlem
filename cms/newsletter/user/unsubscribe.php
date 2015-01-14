<?
require '../config.php';

if(!$_GET['email'])
	header('Location: http://'.$_SERVER[HTTP_HOST].'/');

$email = sql::escape($_GET['email']);

if(sql::exists(PEOPLE_TABLE,array(EMAIL_FIELD => $email))){

	sql::del(PEOPLE_TABLE,	array(EMAIL_FIELD => $email));
	
	$info = array("type"		=> "unsubscribe",
				  "template"	=> $_GET['temp'],
				  "letter"		=> $_GET['id'],
				  "email"		=> $email);
	
	if(sql::exists(UNSUBSCRIBE_TABLE,$info))
		mysql_query("UPDATE `".UNSUBSCRIBE_TABLE."` 
						SET `value` 	= `value` + 1,
							`datetime` 	= '".date('Y-m-d H:i:s')."'
						WHERE `type` 		= 'unsubscribe'
						   && `template` 	= '".sql::escape($_GET['temp'])."'
						   && `letter`		= '".sql::escape($_GET['id'])."'
						   && `email`		= '".$email."'");
	else{
	
		$info['datetime'] = date('Y-m-d H:i:s');
		sql::insert(UNSUBSCRIBE_TABLE,$info);
	
	}
	
	echo 'U bent succesvol uitgeschreven.';

}else
	header('Location: http://'.$_SERVER[HTTP_HOST].'/');
?>