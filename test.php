<?
exit;
require 'php/config.php';

list($car) = sql::fetch("array","cars","WHERE `voertuignr_hexon` = '3729703'");

$social = new social;
	      
$social->set_fb_app_id('226021717452283');
$social->set_fb_app_secret('caf61b12ee4c09a5bed6294ea9e64cee');
$social->set_fb_app_token('AAACh5lXk3qABAKTJPdddb44eGvyKTZBRkOZASoVZAVI0Wjm08Atf9Jcx8Xb6pDlgglzeEVhmXoZBZArsPrMuPlFH6PbFWzsSDxwx6b1Bs8gZDZD');
		
$social->fb_wall_post($car[merk].' '.$car[model].' '.$car[type],'http://www.autoservicehaarlem.nl/'.core::car_url($car));

$social->set_twit_key('24Y6GVsvuxELT8RxHoZ2dQ');
$social->set_twit_secret('Kf1A4gIUbJmvu6jlesjikgSskr9wuFD35dqoMG8kAU');
$social->set_twit_token('125346402-JfJuqFq3TmOREm95cvD6LHNMdj1BAwgMq5KsvIso');
$social->set_twit_token_secret('xZ3dCzJ7eRfrsBKM9CI8a1q8ym0wiZM4jsZkZCPsP8');

$social->twitter_post($car[merk].' '.$car[model].' '.$car[type],'http://www.autoservicehaarlem.nl/'.core::car_url($car));
?>