<?
require_once('../config.php');

if(core::$USER !== false)
	if($_GET['id'] && file_exists('../tmp/'.$_GET['id'].'.txt')){
		
		switch($_GET['type']){
		
			case "txt":
			$file = fopen('../tmp/'.$_GET['id'].'.txt','r');
			$content = fread($file, filesize('../tmp/'.$_GET['id'].'.txt'));
			fclose($file);
			unlink('../tmp/'.$_GET['id'].'.txt');
			header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
			header("Pragma: no-cache");
			header("Content-type: text/tab-separated-values\n\n");
			header("Content-Disposition: attachment; filename=export.txt");
			header("Content-Length: ".strlen($content));
			echo $content;
			break;
			
			case "excel":
			$file = fopen('../tmp/'.$_GET['id'].'.txt','r');
			$content = fread($file, filesize('../tmp/'.$_GET['id'].'.txt'));
			fclose($file);
			unlink('../tmp/'.$_GET['id'].'.txt');
			header("Content-type: application/vnd.ms-excel");
			header('Content-Transfer-Encoding: binary');
			header("Content-Disposition: attachment; filename=export.xls");
			header("Content-Length: ".strlen($content));
			echo $content;
			break;
		
		}

	}

?>