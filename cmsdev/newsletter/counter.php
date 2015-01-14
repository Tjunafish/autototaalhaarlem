<?
include 'config.php';

if(!$templates[$_GET['tmp']])
	exit;
	
$tmp = $templates[$_GET['tmp']];

if(!sql::exists($tmp['table'],array("id"=>$_GET['id'])))
	exit;

$conditions = "WHERE `template` = '".sql::escape($_GET['tmp'])."' 
			   	&& 	 `letter` 	= '".sql::escape($_GET['id'])."' 
			  	&& 	 `date` 	= '".date('Y-m-d')."'
			   	&&	 `type`		= 'email'";
			   
$current 	= sql::fetch("object",VIEWS_TABLE,$conditions);

if(!$current)
	sql::insert(VIEWS_TABLE,array("template"	=> $_GET['tmp'],
								  "letter"		=> $_GET['id'],
								  "count"		=> 1,
								  "date"		=> date('Y-m-d'),
								  "type"		=> "email"));
elseif($current->last_update < date('Y-m-d H:i:s',strtotime('-10 seconds')))	
	sql::update(VIEWS_TABLE,array("count" => $current->count+1),$conditions);
	
$cur = sql::fetch("object",LOG_TABLE,"WHERE `datetime` = '".date('Y-m-d H:i:s',$_GET['stamp'])."' && `email` = '".sql::escape($_GET['email'])."'");
sql::update(LOG_TABLE,array("readcount_email"=>($cur->readcount_email+1)),"WHERE `id` = ".$cur->id);
	
// Generate a 1px white image so there is no 'x' in the email client
header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Expires: 0");
header("content-type: image/gif");
echo chr(71).chr(73).chr(70).chr(56).chr(57).chr(97).chr(1).chr(0).chr(1).chr(0).chr(128).chr(0).
     chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(33).chr(249).chr(4).chr(1).chr(0).chr(0).
     chr(0).chr(0).chr(44).chr(0).chr(0).chr(0).chr(0).chr(1).chr(0).chr(1).chr(0).chr(0).chr(2).chr(2).
     chr(68).chr(1).chr(0).chr(59);
?>