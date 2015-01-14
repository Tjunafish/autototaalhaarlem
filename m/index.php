<?
require 'php/config.php';
/*
$class_string 	= '';
$img_string 	= '';

if($_GET['section'] == 'milieubewust') {
	$class_string 	= ' green';
	$img_string 	= '-green';
} else {
	$class_string	= '';
	$img_string 	= '';
}*/
?>

<!DOCTYPE HTML>

<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<base href="<?= ROOT ?>" />

	<?
    mobile_core::draw_metadata();
	?>
	
	<link rel="stylesheet" href="css/style.css" />
	
	<link rel="apple-touch-icon-precomposed"  href="/m/images/AutoServiceHaarlem_icon.png" />
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/swipe.js?2"></script>
	
	<script type="text/javascript" src="js/functions.js?6"></script>
	
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-18278644-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	  
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		
	  })();
	
	</script>
	
	<script type="text/javascript">
	
		if ('standalone' in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
		
			document.write('<link rel="stylesheet" href="css\/add2home.css">');
			document.write('<script type="application\/javascript" src="js\/add2home.js" charset="utf-8"><\/s' + 'cript>');
			
		}
		
	</script>
    
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>	
	
</head>

<body>

	<div id="wrapper">
	
		<div id="header">
		
			<div class="nav<?=$class_string?>" <?= mobile_core::$cur_page['slug'] === 'zoeken' ? 'style="border-bottom: 1px solid #434343 !important;"': ''?>>
			
				<div class="center">
			
					<div class="logo">
					
						<a href="home" class="ajax_link"><img width="102" data-default="images/logo.png" data-green="images/logo-green.png" src="images/logo.png" /></a>
					
					</div>

                    <div id="navhold" style="<?= mobile_core::$cur_page['slug'] == 'home' ? 'display: none;' : '' ?>">
				
    					<div class="but">
    					
    						<a href="home" class="<?= mobile_core::$cur_page['slug'] == 'home' ? 'active ' : '' ?> ajax_link">
    						
    							<img style="vertical-align: center" width="16" data-default="images/icon_home.png" data-green="images/icon_home-green.png" src="images/icon_home.png" />
    						
    						</a>
    					
    					</div>
    					
    					<div class="but">
    					
    						<a href="contact" class="<?= mobile_core::$cur_page['slug'] == 'contact' ? 'active' : '' ?> ajax_link">
    						
    							<img style="vertical-align: center" width="16" data-default="images/icon_contact.png" data-green="images/icon_contact-green.png" src="images/icon_contact.png" />
    						
    						</a>
    					
    					</div>
    					
    					<div class="but">
    					
    						<a href="search" class="<?= mobile_core::$cur_page['slug'] == 'zoeken' ? 'active ' : '' ?>ajax_link">
    						
    							<img style="vertical-align: center" width="16" data-default="images/icon_search.png" data-green="images/icon_search-green.png" src="images/icon_search.png" />
    						
    						</a>
    					
    					</div>
					
					</div>
				
				</div>
			
			</div>
			
		</div>	
		
		<div id="content">
		
		<?
			//print_r($_GET);
		
			if($_GET['item'] && mobile_core::$cur_page[item_file] && file_exists(mobile_core::$cur_page[item_file]))
				require mobile_core::$cur_page[item_file];
			elseif(mobile_core::$cur_page[file] && file_exists(mobile_core::$cur_page[file]))
				require mobile_core::$cur_page[file];
			
		?>
		
		</div>
	
	</div>

</body>

</html>
	