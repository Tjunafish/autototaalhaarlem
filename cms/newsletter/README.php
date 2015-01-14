<?
/*
///////////////////////////////////////////////////////////////////////
// Add this to your .htaccess (be sure to doublecheck the cms path): //
///////////////////////////////////////////////////////////////////////

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^nieuwsbrief/([^/]+)/([^/]+)/?([^/]+)?/?$ cms/newsletter/user/view.php?temp=$1&id=$2&email=$3 [L,QSA]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^unsubscribe/([^/]+)/([^/]+)/([^/]+)/?$ cms/newsletter/user/unsubscribe.php?temp=$1&id=$2&email=$3 [L,QSA]

//////////////////////////////////
// Table structure: VIEWS_TABLE //
//////////////////////////////////

CREATE TABLE IF NOT EXISTS `newsletter_views` (

  	`id` 			int(11) 		NOT NULL AUTO_INCREMENT,
  	`type` 			varchar(255) 	NOT NULL,
  	`template` 		varchar(255) 	NOT NULL,
  	`letter` 		int(11)			NOT NULL,
  	`count` 		bigint(20) 		NOT NULL,
  	`date` 			date 			NOT NULL,
  	`last_update` 	timestamp 		NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	
  	PRIMARY KEY (`id`)
  
) 	ENGINE=InnoDB 
	DEFAULT CHARSET=utf8;

////////////////////////////////
// Table structure: LOG_TABLE //
////////////////////////////////


CREATE TABLE IF NOT EXISTS `newsletter_log` (

  	`id` 				int(11) 		NOT NULL AUTO_INCREMENT,
  	`template` 			varchar(255) 	NOT NULL,
  	`letter` 			int(11) 		NOT NULL,
  	`email` 			varchar(255) 	NOT NULL,
  	`datetime` 			datetime 		NOT NULL,
  	`type` 				varchar(255) 	NOT NULL,
  	`readcount_email` 	int(11) 		NOT NULL DEFAULT '0',
  	`readcount_online` 	int(11) 		NOT NULL,
  
  	PRIMARY KEY (`id`)
  
) 	ENGINE=InnoDB  
	DEFAULT CHARSET=utf8;

////////////////////////////////////////
// Table structure: UNSUBSCRIBE_TABLE //
////////////////////////////////////////

CREATE TABLE IF NOT EXISTS `newsletter_unsubscribes` (

  	`id` 		int(11) 		NOT NULL AUTO_INCREMENT,
  	`type` 		varchar(255) 	NOT NULL,
  	`template` 	varchar(255) 	NOT NULL,
  	`letter` 	int(11) 		NOT NULL,
  	`email` 	varchar(255) 	NOT NULL,
  	`datetime` 	datetime 		NOT NULL,
  	`value` 	int(11) 		NOT NULL DEFAULT '1',
  
  	PRIMARY KEY (`id`)
  
) 	ENGINE=InnoDB  
	DEFAULT CHARSET=utf8;

/////////////////////////////////////
// STARTING TEMPLATE FOR CMS PAGES //
/////////////////////////////////////

##############
# NEWSLETTER #
##############

$p = new page();

$p->TITLE = "Heart 4 Earth";
$p->NAME  = "template_h4e";
$p->TABLE = "template_h4e";
$p->TAB	  = "Nieuwsbrief archief";

$p->addField("Titel","title","text","","150px");

$p->addField("Plaatje","blockimg","img|1|images/uploads/newsletter|resize:200-200","","100px");
$p->addField("Kleur","blockcolor","color","","80px");
$p->addField("Titel","blocktitle","text","","100px");
$p->addField("Subtitel","blocksubtitle","text","","hide");
$p->addField("Tekst","blocktext","textarea","","200px|limit");
$p->addField("Link","blocklink","text","!required","200px");

$p->combineFields("Blocks",array("blockimg","blockcolor","blocktitle","blocksubtitle","blocktext","blocklink"),4);

$p->ADD_TEXT  = "Nieuwe nieuwsbrief opmaken";
$p->EDIT_TEXT = "Nieuwsbrief aanpassen";

$p->ORDER = "id DESC";

engine::addPage($p);

########

$p = new page();

$p->TITLE = "Inschrijvingen";
$p->NAME  = "newsletter_people";
$p->TABLE = "newsletter_real";
$p->TAB	  = "Nieuwsbrief";

$p->addField("Naam","name","text","","150px");
$p->addField("E-mail adres","email","text","","300px");
$p->addField("Groep","group","text","","100px");
$p->addField("Inschrijfdatum","date","date","!required","150px");

$p->ORDER = "email ASC";

$p->MODULES["right_top"][] = "exporter";

engine::addPage($p);

########

$p = new unique_page();

$p->TITLE = "Versturen";
$p->NAME  = "versturen";
$p->FILE  = "newsletter/archive.php";
$p->TAB	  = "Nieuwsbrief";

engine::addPage($p);

########

$p = new unique_page();

$p->TITLE = "Statistieken";
$p->NAME  = "stats";
$p->FILE  = "newsletter/stat_first.php";
$p->TAB	  = "Nieuwsbrief";

engine::addPage($p);
	
*/
?>