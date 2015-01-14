<?
require_once('../config.php');

core::checkLogin();

if(core::$USER != false){
	
	if($_POST['action'] == "del" || $_POST['action'] == "autosize" || $_POST['action'] == "getlink"){
	
		// Determine image
		
		$tmp = explode("|",$_POST['img']);
		
		if(count($tmp) > 1){
			
			list($img,$tmpimg) = $tmp;
			
			if(substr($tmpimg,0,3) == "tmp")
				if(file_exists("../".$tmpimg))
					$imglink = "../".$tmpimg;
				
			$tmp = explode(".",$img);
			$ext = array_pop($tmp);
			
			$img = implode(".",$tmp);
			
			if(file_exists("../../".$img.".".$ext))
				$imglink = "../../".$img.".".$ext;
			
		}elseif(file_exists($_POST['img']))
			$imglink = $_POST['img'];
		elseif(file_exists("../../".$_POST['img']))
			$imglink = "../../".$_POST['img'];
		
	}
	
	if($_POST['upload_form']){
		
		// Handle incoming upload

		$filepath = $_POST['path'];
	
		if(substr($filepath,-1) != "/")
			$filepath .= "/";
		
		$filepath = "../../".$filepath.basename($_FILES['uploaded_file']['name']);
		
		$tmp = explode(".",$_FILES['uploaded_file']['name']);
		$ext = array_pop($tmp);
		
		if($_POST['type'] == "img"){
			
			list($x,$y) = getimagesize($_FILES['uploaded_file']['tmp_name']);
			list($type,$reqx,$reqy) = explode("|",$_POST['req']);
			
			$wrongsize = false;
			
			if($type == "max"){
		
				if((is_numeric($reqx) && $x > $reqx) || (is_numeric($reqy) && $y > $reqy))
					$wrongsize = true;
		
			}else{
			
				if((is_numeric($reqx) && $x != $reqx) || (is_numeric($reqy) && $y != $reqy))
					$wrongsize = true;
		
			}
			
			if($wrongsize == true){
				
				move_uploaded_file($_FILES['uploaded_file']['tmp_name'],"../tmp/".$_POST['tmpname'].".".$ext);
				echo "../tmp/".$_POST['tmpname'].".".$ext;
				
			}else{
				
				move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$filepath);
				echo $filepath."-".round($x)."x".round($y).".".$ext;
				
			}
		
		}else{
		
			move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$filepath);
			echo $filepath."-".round($x)."x".round($y).".".$ext;
			
		}
		
		
	}elseif($_POST['action'] == "autosize"){
		
		$ext = "";
		
		if(count(explode("|",$_POST['img'])) > 1)	
			list($realimg,$tmpimg) = explode("|",$_POST['img']);			
		elseif($_POST['oldfile'] == "true"){
			
			$realimg 		= str_replace($_POST['target'],'',$_POST['img']);
			$tmpimg	 		= "tmp/temp_image_copy.tmp";
			$oldfile		= $_POST['img'];
			
			$tmp 			= explode(".",$realimg);
			$ext			= array_pop($tmp);
			
			copy("../../".$_POST['img'],"../tmp/temp_image_copy.tmp");	
			
		}		
		
		// Handle automatic file resize		
		if(!file_exists("../".$tmpimg))
			$tmpimg = "../".$realimg;
		elseif(file_exists("../".$tmpimg))
			$tmpimg = "../".$tmpimg;
			
		list($old_x,$old_y) = getimagesize($tmpimg);
		$new_x 				= $_POST['x'];
		$new_y 				= $_POST['y'];
		
		if($_POST['type'] == "max"){
								
			if(!is_numeric($new_x) || $old_x < $new_x)
				$new_x = 0;		
			else
				$x_bigger = true;
			
			if(!is_numeric($new_y) || $old_y < $new_y)
				$new_y = 0;
			elseif($new_x != 0)
				$y_bigger = true;					
			
			if($x_bigger == true && $y_bigger == true){
				
				if($old_x > $old_y)									
					$new_y = ($new_x / $old_x)*$old_y;
				elseif($old_y < $old_x)
					$new_x = ($new_y / $old_y)*$old_x;
				else{

					if($new_x > $new_y)				
						$new_x = ($new_y / $old_y)*$old_x;
					elseif($new_y > $new_x)
						$new_y = ($new_x / $old_x)*$old_y;
					
				}
				
			}elseif($x_bigger == true)									
				$new_y = ($new_x / $old_x)*$old_y;									
			elseif($y_bigger == true)								
				$new_x = ($new_x / $old_x)*$old_y;
				
			if($new_x == 0)
				$resize_x = false;
			else
				$resize_x = true;
				
			if($new_y == 0)
				$resize_y = false;
			else
				$resize_y = true;
			
		}else{
		
			if(!is_numeric($new_x) || $new_x == $old_x)
				$resize_x = false;
			else
				$resize_x = true;
				
			if(!is_numeric($new_y) || $new_y == $old_y)
				$resize_y = false;
			else
				$resize_y = true;
				
		}
		
		if(!$resize_x)
			$new_x = 0;
			
		if(!$resize_y)
			$new_y = 0;
			
		core::imgResize($tmpimg,$new_x,$new_y,$ext);
		
		$filepath = $_POST['target'];
	
		if(substr($filepath,-1) != "/")
			$filepath .= "/";
		
		$file_tmp	= explode(".",$realimg);	
		$ext		= array_pop($file_tmp);	
		$imgtitle 	= implode(".",$file_tmp);
		$imgtitle  .= "-".round($new_x)."x".round($new_y).".".$ext;	
		
		copy($tmpimg,"../../".$filepath.$imgtitle);
		
		if(!$_POST['oldfile'])
			unlink("../tmp/temp_image_copy.tmp");
		else
			unlink($tmpimg);
		
		echo "../../".$filepath.$imgtitle;
		
	}elseif($_POST['action'] == "del"){
		
		// Handle file deletion
		if(file_exists($imglink))
			unlink($imglink);
		elseif(file_exists("../../".$imglink))
			unlink("../../".$imglink);
			
	}elseif($_POST['action'] == "checkext"){

		$extensions = explode(",",$_POST['extensions']);
		$tmp 		= explode(".",$_POST['file']);
		$ext 		= array_pop($tmp);
		
		if(in_array(strtolower($ext),$extensions))
			echo 'valid';
		else
			echo 'invalid';
	
	}		

}
?>