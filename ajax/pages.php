<?
require dirname(__FILE__).'/../php/cars_settings.php';

$pages 	   = core::calc_pages($_POST[page],$search[limit],$search[total],5);
$html  	   = '';

for($i = $pages[start];$i <= $pages[end];$i++)
	$html .= '<a class="control totop'.
			 ($i == $pages[current] ? ' active' : '').
			 '" rel="page" href="'.$i.'">'.$i.'</a> ';
			 
$pages[html] = $html;

echo json_encode($pages);
?>