<?
exit;
require 'php/config.php';

$thumbdir 	   = 'img/cars/';
$extra_thumbs  = array('[car]_deal.jpg'		=> '392-294',
					   '[car]_thumb.jpg'	=> '200-147');
					   
function thumb_from_web($source,$target,$width,$height){

	list($old_w,$old_h) = @getimagesize($source);	
	$image 				= imagecreatefromjpeg($source);
	$result				= imagecreatetruecolor($width,$height);
	
	imagecopyresampled($result,$image,0,0,0,0,$width,$height,$old_w,$old_h);
	imagejpeg($result,$target,100);
	
	imagedestroy($image);
	imagedestroy($result);

}
					   
$cars = sql::fetch("array","cars","ORDER BY `created_at` DESC");
					   
foreach($cars as $car){

	$images = explode(",",$car['afbeeldingen']);
	
	foreach($extra_thumbs as $target => $dimensions){
	
		list($x,$y) = explode("-",$dimensions);
		$target     = str_replace('[car]',$car['voertuignr'],$target);
		thumb_from_web($images[0],$thumbdir.$target,$x,$y);
	
	}
	
	for($i = 1;$i < count($images);$i++) // Skip first image
		thumb_from_web($images[$i],$thumbdir.$car['voertuignr'].'_'.$i.'.jpg',79,59);

}
?>