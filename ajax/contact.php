<?
if(!$_POST)
	exit;
	
require dirname(__FILE__).'/../php/config.php';

$error = array();

$checks 	  	= array();
$checks['name'] 	= array($_POST['name'],	'empty',		'voornaam');
$checks['email']	= array($_POST['email'],	'empty email');
$checks['phone']	= array($_POST['phone'],	'phone_nl',		'telefoonnummer');
$checks['msg']	= array($_POST['msg'],	'empty',		'vraag of opmerking');

$error = core::validate($checks);

// die('hoi');

$result 		= $error;
// var_dump($result); die();
$result[status] = 'failed';

if(count($error) == 0){

	$msg = '';

	if($_POST['car'] && sql::exists("cars",array("voertuignr"=>$_POST['car']))){
	
		list($car) = sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($_POST['car'])."'");	
		$msg 	   = '<b>Contactformulier verzonden vanaf autopagina:</b><br/><a href="'.ROOT.core::car_url($car).'" target="_blank">'.$car['merk'].' '.$car['model'].' '.$car['type'].'</a><br/><br/>';
	
	}
	
	$msg .= '<b>Naam: </b>'.$_POST['name'].' '.$_POST['lastname'].'<br/>'.
   		    ($_POST['phone'] ? '<b>Telefoonnummer:</b> '.$_POST['phone'].'<br/>' : '').
   		    '<b>E-mailadres: </b>'.$_POST['email'].'<br/>'.
   		    '<b>Bericht:</b><br/>'.$_POST['msg'];
	
	core::send_mail('info@autototaalhaarlem.nl','Nieuw bericht via Autototaalhaarlem.nl',$msg);

	$result['status'] 	= 'success';
	$result['returnText'] = '<h2>Uw bericht is verstuurd</h2>';

}

echo json_encode($result);
?>

