<?				
if($_POST['secure'] != sha1(md5($_POST['target']."code".$_POST['ext'])))
	die();
	
$target		= $_POST['target'];
$files 		= scandir("../../".$target);
$thumbsize 	= 98;

foreach($files as $file){

	$tmp 		= explode(".",$file);
	$extension 	= array_pop($tmp);
	$name 		= implode($tmp);
	
	if(in_array($extension,explode(",",$_POST['ext'])))					
		if($_POST['type'] == "img"){
			
			list($oldx,$oldy) = getimagesize("../../".$target."/".$file);
			
			if(($oldx > $oldy || $oldx == $oldy) && $oldx > $thumbsize){
				
				$extra = 'width='.$thumbsize;
				$newx  = $thumbsize;
				$newy  = $oldy / ($oldx / $thumbsize); 
				
			}elseif($oldy > $oldx && $oldy > $thumbsize){
				
				$extra = 'height='.$thumbsize;
				$newx  = $oldx / ($oldy / $thumbsize);
				$newy  = $thumbsize;
				
			}else
				$extra = '';
				
			if($extra)
				$offset = "margin-top:-".($newy/2)."px;margin-left:-".($newx/2)."px;";
			else
				$offset = "margin-top:-".($oldy/2)."px;margin-left:-".($oldx/2)."px;";
				
			$style = 'style="width:'.$thumbsize.'px;height:'.$thumbsize.'px;"';
				
			echo '<div class="img_block" info-size="'.$oldx.'|'.$oldy.'" rel="'.$target."/".$file.'" rev="'.$file.'" '.$style.'>'. 
				 '<img src="../'.$target.$file.'" '.$extra.' alt="'.$name.'" style="'.$offset.'" />'.
				 '<div class="img_info" '.$style.'><b>Breedte:</b> '.$oldx.'px<br/> <b>Hoogte:</b> '.$oldy.'px</div>'.
				 '</div>';
			
		}else
			echo $file."<br/>";
	
}
?>