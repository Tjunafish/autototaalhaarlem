<?
if(!$_POST)
	exit;
	
require dirname(__FILE__).'/../php/config.php';

$error 			= array();

$checks 	  	= array();
$checks[name] 	= array($_POST[name],	'empty',		'Je naam');
$checks[email]	= array($_POST[email],	'empty email');
$checks[phone]	= array($_POST[phone],	'phone_nl',		'Je telefoonnummer');
$checks[plate]	= array($_POST[plate],	'empty license_plate_nl');

$error 			= core::validate($checks);

$return 		= $error;
$return[status] = 'failed';

if(count($error) == 0){

	$msg  = 'Er is een taxatieaanvraag gedaan via Autototaalhaarlem.nl<br/><br/>'.
			'<b>Naam: </b>'.$_POST[name].'<br/>'.
   		    ($_POST[phone] ? '<b>Telefoonnummer:</b> '.$_POST[phone].'<br/>' : '').
   		    '<b>E-mailadres: </b>'.$_POST[email].'<br/>'.
   		    '<b>Kenteken: </b>'.$_POST[plate].'<br/>'.
   		    '<b>Bericht:</b><br/>'.$_POST[msg];
   		    
   $return[status] 		= 'success';
   $return[trigger]		= 'a.tax_toggle';
   $return[triggerType] = 'click';
   $return[returnText]  = 'Je taxatie aanvraag is succesvol naar ons verstuurd!';
   $return[special]  	= 'tax';

	core::send_mail(core::$mail_from,'Taxatieaanvraag via Autototaalhaarlem.nl',$msg);

}

echo json_encode($return);
?>