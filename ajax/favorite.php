<?
require '../php/config.php';

if($_POST['action'] == 'get')
	die($_COOKIE['favorites']);

if(sql::exists("cars",array("voertuignr"=>$_POST['car'])))
	$car = sql::escape($_POST['car']);
else
	exit;

$favorites = $_COOKIE['favorites'] ? json_decode($_COOKIE['favorites'])
								   : array();

if(is_object($favorites))
	$favorites = get_object_vars($favorites);

if($favorites[$car])
	unset($favorites[$car]);
else
	$favorites[$car] = time();
	
$favorites = json_encode($favorites);

setcookie('favorites',$favorites,time()+60*60*24*30,COOKIE_DOMAIN);

echo $favorites;
?>