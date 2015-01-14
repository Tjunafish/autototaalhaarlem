<?
if(!class_exists('sql')){
	
	set_include_path(dirname(__FILE__).'/../');
	require 'config.php';		
	set_include_path(dirname(__FILE__));
	
}

/////////////////////////////
// CONFIGURATION VARIABLES //
/////////////////////////////
		
define(ROOT,				'http://www.autoservicehaarlem.nl/');
define(CMSROOT,				'cms/');

define(PEOPLE_TABLE,		'newsletter');
define(LOG_TABLE,			'newsletter_log');
define(VIEWS_TABLE,			'newsletter_views');
define(UNSUBSCRIBE_TABLE,	'newsletter_unsubscribes');

define(NAME_FIELD,			'name');
define(EMAIL_FIELD,			'email');
define(GROUP_FIELD,			false);

define(SUBJECT,				'Auto Service Haarlem - ');
define(FROM_EMAIL,			'nieuwsbrief@autoservicehaarlem.nl');
define(FROM_NAME,			'Auto Service Haarlem');
define(REPLY_EMAIL,			'noreply@autoservicehaarlem.nl');

/////////////////////////////
		
require 'functions.php';
require '_phpmailer.php';
require 'templates.php';
?>