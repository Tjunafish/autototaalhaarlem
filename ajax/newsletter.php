<?
if(!$_POST)
	exit;
	
require dirname(__FILE__).'/../php/config.php';

$error = array();

$checks 	  	= array();
$checks[name] 	= array($_POST[name],	'empty',		'Je naam');
$checks[email]	= array($_POST[email],	'empty email');

$error 			= core::validate($checks);

$return 		= $error;
$return[status] = 'failed';

if(count($error) == 0){

	if(!sql::exists("newsletter",array("email"=>$_POST[email])))
		sql::insert("newsletter",array("name" 		 => $_POST[name],
									   "email"		 => $_POST[email],
									   "signup_date" => date('Y-m-d H:i:s')));

	$return[status] 	= 'success';
	$return[returnText] = '<h3 class="return">U bent succesvol ingeschreven voor onze nieuwsbrief.</h3>';

}

echo json_encode($return);
?>