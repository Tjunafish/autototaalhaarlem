<?
require '../config.php';

if($templates[$_GET['temp']] && sql::exists($templates[$_GET['temp']]['table'],array("id"=>$_GET['id']))){

	$tmp  = $templates[$_GET['temp']];	
	$html = file_get_contents('../'.$tmp['file']);

	$info = sql::fetch("object",$tmp['table'],"WHERE `id` = '".sql::escape($_GET['id'])."'");
	
	if(!$info)
		exit;
		
	$conditions = "WHERE `template` = '".sql::escape($_GET['temp'])."' 
					&& 	 `letter` 	= '".sql::escape($_GET['id'])."' 
					&& 	 `date` 	= '".date('Y-m-d')."'
					&&	 `type`		= 'online'";
		   
	$current 	= sql::fetch("object",VIEWS_TABLE,$conditions);
	
	if(!$current)
		sql::insert(VIEWS_TABLE,array("template"	=> $_GET['temp'],
									  "letter"		=> $_GET['id'],
									  "count"		=> 1,
									  "date"		=> date('Y-m-d'),
									  "type"		=> "online"));
	elseif($current->last_update < date('Y-m-d H:i:s',strtotime('-10 seconds')))	
		sql::update(VIEWS_TABLE,array("count" => $current->count+1),$conditions);
	
	if($_GET['email']){
		
		$cur = sql::fetch("object",LOG_TABLE,"WHERE `datetime` = '".date('Y-m-d H:i:s',$_GET['stamp'])."' && `email` = '".sql::escape($_GET['email'])."'");
		sql::update(LOG_TABLE,array("readcount_online"=>($cur->readcount_online+1)),"WHERE `id` = ".$cur->id);
	
	}
	?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$info->{$tmp['titlefield']}?></title>
</head>  
	<?		
	define(LINKSTYLE,$tmp['linkstyle']);
	
	echo fill_template($_GET['temp'],$html,$info,$_GET['email']);

}
?>
</html>