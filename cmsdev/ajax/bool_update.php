<?
require_once('../config.php');
if(core::$USER !== false)	
	sql::update(sql::escape($_POST['page']),array($_POST['field']=>$_POST['val']),"WHERE `id` = '".sql::escape($_POST['id'])."'");
?>