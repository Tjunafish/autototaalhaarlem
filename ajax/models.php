<?
require dirname(__FILE__).'/../php/config.php';

if(!sql::exists("cars",array("merk" => $_POST[brand])))
	die('error');
	
echo '<option value="">Selecteer een model</option>';

sql::$select = 'SELECT DISTINCT(`model`)';
	
foreach(sql::fetch("array","cars","WHERE `merk` = '".sql::escape($_POST[brand])."'") as $car)
	echo '<option value="'.$car[model].'">'.$car[model].'</option>';
	
sql::reset_select();
?>