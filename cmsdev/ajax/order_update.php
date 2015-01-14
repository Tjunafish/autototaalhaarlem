<?
require_once('../config.php');
if(core::$USER !== false)
	foreach($_POST['order'] as $num => $id)	
		sql::update(sql::escape($_POST['page']),array("order"=>$num),"WHERE `id` = '".sql::escape($id)."'");
?>