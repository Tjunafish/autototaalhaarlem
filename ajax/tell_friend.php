<?
if(!$_POST)
	exit;
	
require dirname(__FILE__).'/../php/config.php';

$error = array();

$checks 	  	= array();
$checks[name] 	= array($_POST[name],	'empty',		'Je naam');
$checks[email]	= array($_POST[email],	'empty email');
$checks[fname]  = array($_POST[fname],	'empty',		'Naam vriend');
$checks[fmail]	= array($_POST[fmail],	'empty email');

$error = core::validate($checks);

$return 		= $error;
$return[status] = 'failed';

if(count($error) == 0)
	if($_POST[car] && sql::exists("cars",array("voertuignr"=>$_POST[car]))){
	
		list($car) = sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($_POST[car])."'");	
		$msg 	   = '<b>'.$_POST[name].'</b> ('.$_POST[email].') wil je deze auto laten zien:</b><br/><a href="'.ROOT.core::car_url($car).'" target="_blank">'.$car[merk].' '.$car[model].' '.$car[type].'</a><br/><br/>';
		
		if($_POST[msg] && $_POST[msg] != 'Persoonlijk bericht')
			$msg  .= $_POST[msg];
	
		core::send_mail($_POST[fmail],'Bekijk deze auto op Autototaalhaarlem.nl!',$msg);
		
		$return[status] 	 = 'success';
		$return[trigger]	 = 'span.tell_friend a';
		$return[triggerType] = 'click';	
	
	}else	
		$return[status] = 'failed';

echo json_encode($return);
?>