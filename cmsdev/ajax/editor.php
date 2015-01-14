<?
require_once('../config.php');

core::checkLogin();

if(core::$USER == false || ($_POST['secure'] != sha1(md5($_POST['x'].$_POST['y']."code".$_POST['target'])) && $_POST['secure'] != sha1(md5($_POST['img']."code".$_POST['target']))))
	die('error');
	
$oldfile = false;

if(count(explode("|",$_POST['img'])) > 1){
	
	list($imgtitle,$realimg) = explode("|",$_POST['img']);
	
	$img 			= "../".$realimg;
	$relativeimg	= $realimg;
	
	if(!file_exists($img))
		if(file_exists("../../".$_POST['target'].$imgtitle)){
		
			$img 			= "../../".$_POST['target'].$imgtitle;
			$relativeimg    = "../".$_POST['target'].$imgtitle;
		
		}
	
}elseif($_POST['oldfile'] == "true"){
	
	$imgtitle 		= str_replace($_POST['target'],'',$_POST['img']);
	$img 			= "../tmp/temp_image_copy.tmp";
	$relativeimg 	= "tmp/temp_image_copy.tmp";
	$oldfile		= $_POST['img'];
	
	copy("../../".$_POST['img'],"../tmp/temp_image_copy.tmp");	
	
}else{
	
	$img 			= "../../".$_POST['img'];
	$relativeimg	= "../../".$_POST['img'];
		
}

$boxwidth		= 480;
$boxheight		= 480;

$contrain 		= false;

if(!file_exists($img))
	die("error: ".$img);

if($_POST['action'] == "edit" && $_POST['data']){
	
	if($_POST['oldfile'] != ""){
	
		$img 		= "../tmp/temp_image_copy.tmp";
		$imgtitle 	= $_POST['oldfile'];
		$tmp 		= explode('.',$imgtitle);
		
	}else		
		$tmp 		= explode('.',$img);
	
	$extension  = array_pop(explode("/",mime_content_type($img)));
	
	// HANDLE EDITED FILE		
	if($extension == "jpg" || $extension == "jpeg")
		$src_img = imagecreatefromjpeg($img);
	
	if($extension == "png")			
		$src_img = imagecreatefrompng($img);		
	
	$or_x = imagesx($src_img);
	$or_y = imagesy($src_img);

	list($x,$y,$w,$h) = explode("|",$_POST['data']);
	
	if($w > $h)
		$ratio = $w / $h;
	else
		$ratio = $h / $w;
	
	if($_POST['type'] == "max"){
		
		$final_x = $w;
		$final_y = $h;
				
		if($w > $h || ($w == $h && (!is_numeric($_POST['final_y']) || (is_numeric($_POST['final_x']) && is_numeric($_POST['final_y']) && $_POST['final_x'] > $_POST['final_y'])))){
			
			if(is_numeric($_POST['final_x']) && $w > $_POST['final_x']){
				
				$final_x = $_POST['final_x'];
				$final_y = $final_x/$ratio;
			
				if(is_numeric($_POST['final_y']) && $final_y > $_POST['final_y']){
				
					$final_y = $_POST['final_y'];
					$final_x = $final_y/$ratio;
					
				}
				
			}elseif(is_numeric($_POST['final_y']) && $h > $_POST['final_y']){
			
				$final_y = $_POST['final_y'];
				$final_x = $final_y/$ratio;
				
			}
			
		}elseif($h > $w || ($w == $h && (!is_numeric($_POST['final_x']) || (is_numeric($_POST['final_y']) && is_numeric($_POST['final_x']) && $_POST['final_y'] > $_POST['final_x'])))){
		
			if(is_numeric($_POST['final_y']) && $h > $_POST['final_y']){
				
				$final_y = $_POST['final_y'];
				$final_x = $final_y/$ratio;
				
				if($final_x > $_POST['final_x']){
				
					$final_x = $_POST['final_x'];
					$final_y = $final_x/$ratio;
					
				}
			
			}elseif(is_numeric($_POST['final_x']) && $w > $_POST['final_x']){
			
				$final_x = $_POST['final_x'];
				$final_y = $final_x/$ratio;
				
			}
			
		}
		
	}else{
	
		if(!is_numeric($_POST['final_x']) && !is_numeric($_POST['final_y'])){
			
			$final_x = $w;
			$final_y = $h;
			
		}elseif(!is_numeric($_POST['final_x'])){
			
			$final_y = $_POST['final_y'];
			
			if($w > $h)
				$final_x = $final_y*$ratio;
			else
				$final_x = $final_y/$ratio;
			
		}elseif(!is_numeric($_POST['final_y'])){
			
			$final_x = $_POST['final_x'];
			
			if($w > $h)
				$final_y = $final_x/$ratio;
			else
				$final_y = $final_x*$ratio;
			
		}else{
			
			$final_x = $_POST['final_x'];
			$final_y = $_POST['final_y'];
			
		}
	
	}
	
	$dst_img   = imagecreatetruecolor($w,$h);
	$final_img = imagecreatetruecolor($final_x,$final_y);
	
	imagecopyresampled($dst_img,$src_img,0,0,$x,$y,$or_x,$or_y,$or_x,$or_y);
	imagecopyresampled($final_img,$dst_img,0,0,0,0,$final_x,$final_y,$w,$h);
	
	imagealphablending($final_img,false);
	imagesavealpha($final_img,true);
	
	if($extension == "png")
		imagepng($final_img,$img); 
	else
		imagejpeg($final_img,$img,100); 

	imagedestroy($final_img);
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
	
	$filepath = $_POST['target'];
	
	if(substr($filepath,-1) != "/")
		$filepath .= "/";
		
	$file_tmp	= explode(".",$imgtitle);	
	$ext		= array_pop($file_tmp);	
	$imgtitle 	= implode(".",$file_tmp);
	$imgtitle  .= "-".round($final_x)."x".round($final_y).".".$ext;	
	
	if($_POST['oldfile'])
		$filepath = "";
	
	copy($img,"../../".$filepath.$imgtitle);
	
	if($_POST['oldfile'])
		unlink("../tmp/temp_image_copy.tmp");
	else
		unlink($img);
	
	die("../../".$filepath.$imgtitle);

}else{

// FILE EDITOR

$target_x 	= $_POST['x'];
$target_y	= $_POST['y'];

list($x,$y) = getimagesize($img);

if((is_numeric($target_x) && is_numeric($target_y)) && $_POST['type'] != "max"){
	
	if($target_x > $boxwidth || $target_y > $boxheight)	
	
		if($target_x > $target_y){
		
			$ratio 	= $target_x / $target_y;
			$wx 	= $boxwidth;
			$wy 	= $boxheight*$ratio;

		}else{
		
			$ratio 	= $target_y / $target_x;
			$wy 	= $boxheight;
			$wx 	= $boxwidth*$ratio;

		}	
	
	else{
		
		if($target_x > $target_y)
			$ratio = $target_x / $target_y;
		else
			$ratio = $target_y / $target_x;
		
		$wx = $target_x;	
		$wy = $target_y;
		
	}
	
	$constrain = $target_x / $target_y;

}else	
	$ratio = false;
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js">		</script>
<script type="text/javascript" src="../js/jquery.jcrop.js"></script>
<link type="text/css" rel="stylesheet" href="../js/jquery.jcrop.css" />
    
<h3><b>File:</b> <?=$imgtitle?></h3>

<h3>

	<div class="floatleft">
		
        <b class="fixed">Breedte:</b> <?=$x?>px <br/>
    	<b class="fixed">Hoogte:</b> <?=$y?>px
    
    </div>
    
    <div class="floatright">
    
    	<b class="fixed"><?= ($_POST['type'] == "max")? 'Max ' : 'Doel'; ?>breedte:</b> <span class="fixed"><? if(is_numeric($target_x)) echo $target_x.'px'; else echo 'n.v.t.';?></span><br/>
        <b class="fixed"><?= ($_POST['type'] == "max")? 'Max ' : 'Doel'; ?>hoogte:</b>  <span class="fixed"><? if(is_numeric($target_y)) echo $target_y.'px'; else echo 'n.v.t.';?></span>
    
    </div>
    
    <div class="clear"></div>
    
</h3>

<div id="full_image" class="block">

	<img src="<?=$relativeimg."?".time()?>" id="cropbox" />
    
</div>

<div id="preview_block" class="block">

    <div id="preview_inside" style=" <? if($constrain) echo 'width:'.$wx.'px;height:'. $wy.'px;'; ?>overflow:hidden;margin:auto;">
    
        <img src="<?=$relativeimg."?".time()?>" id="preview" />
        
    </div>
    
</div>

<h3 id="notice_bot">

	<a class="button" id="cancel">annuleren</a>

	<a class="button inactive" id="save">opslaan</a>
    <a class="button inactive" id="show_preview">voorbeeld</a>
    
    <div class="clear"></div>

</h3>

<input type="hidden" id="values" value="" />


<script type="text/javascript">

	$(function(){

		$('#cropbox').Jcrop({
			boxWidth: 	<?=$boxwidth-12?>,
			boxHeight: 	<?=$boxheight-12?>,
			onSelect: 	showPreview
			<? if($constrain) echo ',aspectRatio: '.$constrain; ?>
		});

	});

	function showPreview(coords){
		
		$('#values').val(coords.x+"|"+coords.y+"|"+coords.w+"|"+coords.h);
		$('#save').removeClass('inactive');
		$('#show_preview').removeClass('inactive');
		
		$('#save').unbind('click').click(function(event){
			
			event.preventDefault();

			$.post('ajax/editor.php?anticache='+new Date().getTime(),{	action: 'edit', 
																		img: 	'<?=$_POST['img']?>', 
																		data: 	$('#values').val(), 
																		final_x:'<?=$target_x?>',
																		final_y:'<?=$target_y?>', 
																		type: 	'<?=$_POST['type']?>',
																		target: '<?=$_POST['target']?>',
																		oldfile: '<?=$oldfile?>',
																		secure:	'<?=sha1(md5($_POST['img']."code".$_POST['target']))?>'
																		},function(data){
				
				$('#uploader').show();
				$('#editor').hide();
				$('h1').html('Bestandsbeheer');	
				$('#<?=$_POST['unique']?>').removeClass('invalid');
				$('#<?=$_POST['unique']?> .img_del').attr('rel',data);
				$('#<?=$_POST['unique']?> .img_preview').attr('rel',data);
				$('#<?=$_POST['unique']?> .img_preview').attr('truepath','true');
				$('#upload_data').val($('#upload_data').val()+"|"+data);
				$('#<?=$_POST['unique']?> .img_edit').remove();
				$('#<?=$_POST['unique']?> .img_auto_edit').remove();			
				
				var warning = false;
				
				$('ul#uploaded li.invalid').each(function(){
					
					warning = true;
					
				});
				
				if(!warning){
					
					$('#warning').hide();
					$('#save_all').removeClass('inactive');
						
				}
				
			});
			
		});
		
		$('#show_preview').unbind('click').click(function(){
		
			if($('#preview_block').css('display') == "none"){
				
				$('#preview_block').show();
				$('#full_image').hide();
				
				$(this).html('aanpassen');
			
			}else{
				
				$('#preview_block').hide();
				$('#full_image').show();
				
				$(this).html('voorbeeld');
				
			}
			
		});
		
		if(parseInt(coords.w) > 0){
			
			var endwidth;
			var endheight;
			var rx;
			var ry;
			
			<? if($ratio){	?>
			
				endwidth  = (<?=$wx?> / coords.w) * <?=$x?>;
				endheight = (<?=$wy?> / coords.h) * <?=$y?>;
				rx 		  = <?=$wx?> / coords.w;
				ry 		  = <?=$wy?> / coords.h;
				
			<? }else{ 
			
				if(is_numeric($target_x) && $target_x < ($boxwidth - 12)){	?>
				
					endwidth  = <?=$target_x?>;
					endheight = (endwidth / coords.w)*coords.h; 
										
			<?  }elseif(is_numeric($target_x) && $target_y < ($boxheight - 12)){	?>
				
					endheight = <?=$target_y?>;
					endwidth  = (endheight / coords.h)*coords.w;
					
			<?  }else{	?>		
					
					if(coords.w > <?= ($boxwidth - 12) ?> || coords.h > <?= ($boxheight - 12) ?>){
					
						if(coords.w > coords.h){
	
							endwidth  = <?= ($boxwidth - 12) ?>;
							endheight = coords.h*(<?= ($boxheight - 12) ?> / coords.w);
							
						}else if(coords.h > coords.w){
							
							endheight = <?= ($boxheight - 12) ?>;
							endwidth  = coords.w*(<?= ($boxwidth - 12) ?> / coords.h);
							
						}else{
						
							endwidth  = <?= ($boxwidth - 12) ?>;
							endheight = <?= ($boxheight - 12) ?>;
							
						}
						
					}else{
					
						endwidth  = coords.w;
						endheight = coords.h;
					
					}
					
			<?  } ?>
				
				rx = endwidth / coords.w;
				ry = endheight / coords.h;
				
				$('#preview_inside').css({width: endwidth+'px', height: endheight+'px'});
				
		<? } ?>
			
			$('#preview').css({
				width: Math.round(rx * <?=$x?>) + 'px',
				height: Math.round(ry * <?=$y?>) + 'px',
				marginLeft: '-' + Math.round(rx * coords.x) + 'px',
				marginTop:  '-' + Math.round(ry * coords.y) + 'px'
			});
			
		}
		
	}
	
	$('#cancel').click(function(){
		
		$('#uploader').show();
		$('#editor').html('');
		
	});

</script>
<?
}
?>