<?
if($_POST['secure'] != sha1(md5(date("Y-m-d")."code")))
	die();
	
if(file_exists($_POST['tmpfile']))
	echo 'tmp';
elseif(file_exists($_POST['realfile']))
	echo 'real';
else
	echo 'false';
?>