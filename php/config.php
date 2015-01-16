<?php

// Unless we are on localhost / testbyte, make sure the domain starts with www.
/*if($_SERVER[HTTP_HOST] != "localhost" && !preg_match('/testbyte/',$_SERVER[HTTP_HOST]) && substr($_SERVER[HTTP_HOST],0,4) != 'www.')
	header('Location: '.
           (@$_SERVER[HTTPS] == 'on' ? 'https://' : 'http://').
           'www.'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);*/




// --------------------------------------
// config.php
// site configuration
// --------------------------------------
 
session_start();
 
require_once '_sql.php';
require_once '_core.php';
require_once 'social/_social.php';
 
// General settings
// ----------------
 
if (isset($_SERVER['HTTP_HOST'])) {
	define('ROOT', $_SERVER['HTTP_HOST']);
} else {
	define('ROOT',		  		'http://www.autototaalhaarlem.nl/');
}
define('COOKIE_DOMAIN',		'/');
define('LUXUS',        		$_SERVER['REMOTE_ADDR'] == '90.145.41.202');

core::$favicon 				= 'favicon.png';
 
core::$metatitle			= 'Auto Totaal Haarlem';
core::$metadesc				= 'Auto Totaal Haarlem. Leverancier van jonge occasions , waarvan wij er altijd 150 op voorraad hebben. De scherpste prijzen, Service en kwaliteit.';
core::$metakeys				= 'Auto Totaal Haarlem, milieubewust, milieubewust rijden, groen en verantwoord, zorgeloos rijden, service en zekerheid, kwaliteit, scherpe prijzen,  zijlweg 294, haarlem';
 
core::$languages			= array('nl');
core::$default_lang			= 'nl';
 
core::$default_page			= array('id',1); // default page, table field then value

core::$social['facebook']		= 'http://www.facebook.com/autototaalhaarlem';
core::$social['twitter']		= 'http://twitter.com/ASH_cars';
core::$social['youtube']		= 'http://www.youtube.com/user/mlo369';
 
// Javascript libraries
// --------------------
 
core::$jslibs['jquery'] 		= true; // Look at core to see what libraries are available
 
// Database settings
// -----------------
 
sql::$host 					= 'localhost';
sql::$user 					= 'root';
sql::$pass 					= '!jQKuEQQdV2Xax!';
sql::$db   					= 'autototaal';
 
core::calculate();

if(core::$cur_page['id'] == 3) // Aanbiedingen pagina
    $test = 'actieprijs';
elseif(core::$cur_page['id'] == 4) // Milieubewust pagina
    $test = 'milieu_bewust';  
else    
    $test = 'voorraad';
    
define('SESSION_MATCH',       $_SESSION['special'] == $test);

// E-mail settings
// ---------------

core::$mail_name            = 'Auto Totaal Haarlem';
core::$mail_from            = 'info@autototaalhaarlem.nl';
core::$mail_reply           = 'info@autototaalhaarlem.nl';
 
ob_start(); // EMAIL HEADER
?>
<body style="margin: 0;background-color:#4b4b4b;" bgcolor="#4b4b4b">
<table bgcolor="#4b4b4b" width="100%" height="100%" style="background-color:#4b4b4b;width: 100%;height:100%;" cellpadding="0" cellspacing="0">
<tr><td>
<center>

	<div style="height:20px;width:100%;"></div>
	<table cellpadding="0" cellspacing="0" style="text-align: left;">
	
		<tr>
			<td width="7" 	background="<?= ROOT ?>img/newsletter_shadow_left.jpg" style="background-image: url('<?= ROOT ?>img/newsletter_shadow_left.jpg');background-repeat: repeat-y;"></td>
			<td width="764" background="<?= ROOT ?>img/newsletter_bg.jpg" 		 style="background-color: #f4f4f4;background-image: url('<?= ROOT ?>img/newsletter_bg.jpg');background-repeat: repeat-x;">
			
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top">
							<a href="<?= ROOT ?>" target="_blank"><img src="<?= ROOT ?>img/newsletter_logo.jpg" border="0" /></a>
						</td>		
						<td height="174" valign="top">
						
							<table cellpadding="0" cellspacing="0" width="527" style="font-family: 'Impact',sans-serif;text-transform:uppercase;">
							
								<tr><td height="63" colspan="3"></td></tr>
								<tr style="color:#fff;font-size:18px;" bgcolor="#dc3600" background="<?= ROOT ?>img/newsletter_orange_bar.jpg" style="background-color: #dc3600;">
									<td height="46" width="400" valign="center" style="padding-left: 15px;font-family: 'Impact',sans-serif;text-transform:uppercase;background-image: url('<?= ROOT ?>img/newsletter_orange_bar.jpg');background-position:left;">
									</td>
									<td height="46" width="332" align="right" valign="center" style="font-family: 'Impact',sans-serif;text-transform:uppercase;background-image: url('<?= ROOT ?>img/newsletter_orange_bar.jpg');background-position:-250px;">
										volg ons op
										<a target="_blank" href="http://twitter.com/ASH_cars"><img src="<?= ROOT ?>img/twitter.png"  border="0" alt="Twitter"  style="vertical-align:bottom;" /></a> 
										<a target="_blank" href="http://www.facebook.com/people/Ash-Deinum/100000800897737"><img src="<?= ROOT ?>img/facebook.png" border="0" alt="Facebook" style="vertical-align:bottom;" /></a> 
										<a target="_blank" href="http://www.youtube.com/user/mlo369"><img src="<?= ROOT ?>img/youtube.png"  border="0" alt="Youtube"  style="vertical-align:bottom;" /></a> 
									</td>
									<td width="66" style="background-image: url('<?= ROOT ?>img/newsletter_orange_bar.jpg');background-position:250px;">
									</td>
								</tr>
								<tr>
									<td height="45" style="color: #737373;font-size:11px;padding-left:15px;font-family: 'Impact',sans-serif;text-transform:uppercase;" colspan="3">
									ASH  |  Zijlweg 294  |  2015 CN Haarlem  |  T. 023-5392024  |  F. 023-5269898  |   <a href="mailto:info@autoservicehaarlem.nl" style="color:#ff5702;text-decoration:none;">info@autoservicehaarlem.nl</a>
									</td>
								</tr>
								<tr><td height="20" colspan="2"></td></tr>
							
							</table>
						
						</td>			
					</tr>
				</table>
			<div style="margin:20px 30px 30px;font-size:12px;font-family:'Arial';color:#737373;">
<?
core::$email_header = ob_get_clean();
 
ob_start(); // EMAIL FOOTER
?>
			</div>
			</td>
			<td width="7" background="<?= ROOT ?>img/newsletter_shadow_right.jpg" style="background-image: url('<?= ROOT ?>img/newsletter_shadow_right.jpg');background-repeat: repeat-y;"></td>
		</tr>
		<tr height="28">
			<td width="7" height="28" 	background="<?= ROOT ?>img/newsletter_shadow_left.jpg" style="background-image: url('<?= ROOT ?>img/newsletter_shadow_left.jpg');background-repeat: repeat-y;"></td>
			<td height="28" align="center" bgcolor="#dc3600" background="<?= ROOT ?>img/newsletter_bg_bot.jpg" style="font-size:11px;font-family:'Trebuchet MS';color:#fff;line-height:28px;height:28px;background-color: #dc3600;background-image: url('<?= ROOT ?>img/newsletter_bg_bot.jpg');background-repeat: repeat-x;">
			&copy; 2011 Auto Service Haarlem. Alle rechten voorbehouden. | Created by <a href="http://www.codecreators.nl/" target="_blank" style="color: #fff;">CodeCreators</a>
			</td>
			<td width="7" height="28" background="<?= ROOT ?>img/newsletter_shadow_right.jpg" style="background-image: url('<?= ROOT ?>img/newsletter_shadow_right.jpg');background-repeat: repeat-y;"></td>
		</tr>
		<tr></a><td colspan="3" height="15"></td></tr>
	
	</table>

</center>
</td></tr>
</table>
</body>
<?
core::$email_footer = ob_get_clean();
?>
