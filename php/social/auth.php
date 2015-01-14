<?php
require 'twitter_sdk/EpiCurl.php';
require 'twitter_sdk/EpiOAuth.php';
require 'twitter_sdk/EpiTwitter.php';

$twitterObj  = new EpiTwitter('24Y6GVsvuxELT8RxHoZ2dQ', 'Kf1A4gIUbJmvu6jlesjikgSskr9wuFD35dqoMG8kAU');
$oauth_token = $_GET['oauth_token'];

if($oauth_token == ''){

	$url = $twitterObj->getAuthorizationUrl();
	echo "<div  style='width:200px;margin-top:200px;margin-left:auto;margin-right:auto'>";
	echo "<a href='$url'>Sign In with Twitter</a>";
	echo "</div>";
	
}else{

 $twitterObj->setToken($_GET['oauth_token']);
 $token 	= $twitterObj->getAccessToken();
 
 echo 'Token: '.$token->oauth_token.'<br/>Secret: '.$token->oauth_token_secret;

}
?>