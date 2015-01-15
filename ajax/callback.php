<?
if(!$_POST)
	exit;
	
require dirname(__FILE__).'/../php/config.php';

$error = array();

$checks 	  	= array();
$checks['name'] 	= array($_POST['name'],	'empty',	'Je naam');
$checks['phone']	= array($_POST['phone'],	'empty',	'Je telefoonnummer');

$error 			= core::validate($checks);

$return 		= $error;
$return['status'] = 'failed';

if(count($error) == 0){

	core::send_mail(core::$mail_from,'Terugbelverzoek op Autototaalhaarlem.nl',
					'Er is een terugbelverzoek ingediend op Autototaalhaarlem.nl<br/>
					 <br/>
					 <b>Naam:</b> '.$_POST['name'].'<br/>
					 <b>Telefoonnummer:</b> '.$_POST['phone'].'<br/>
					 <b>Datum:</b> '.date('H:i:s d-m-Y'));

	$return['status'] 	= 'success';
	$return['returnText'] = '<h3 class="return">Uw terugbelverzoek is verstuurd</h3>';

}

echo json_encode($return);
?>