<?
//==================================
// INSTALL INFO
//==================================
/*

 The CMS folder should be placed in the root of the managed site, the folder doesn't have to be called cms

 Your database should contain a table for the login system with four fields:
 username, password, displayname and super
 username should be an e-mail address for forgot password to work
 When super is set to 1, this account is a superadmin, you can set pages only to show for superadmins
 
 Put the name of this table in core::$LOGINTABLE below

 All the tables that can have fields deleted, modified or added should contain a primary key field called id
 
*/
//==================================
// CMS LOADER 	   ( DO NOT CHANGE )
//==================================

session_start();
require_once '_sql.php';
require_once '_core.php';

//==================================
// CMS SETTINGS
//==================================
// MYSQL CONNECT INFORMATION

sql::connect('localhost',
			 'root',
			 '!jQKuEQQdV2Xax!',
			 'autototaal');

// SITE SETTINGS

//core::$DOMAIN     	= "autoservicehaarlem.nl";
core::$DOMAIN = 'www.autototaalhaarlem.nl';
// ROOT only has to be set if the site managed by the CMS is in a subfolder of the domain, so if the site managed by the cms is in site.com/root/rootb set ROOT to root/rootb
core::$ROOT			= "";
core::$LOGINTABLE 	= "admin";
core::$TITLE 	 	= "Auto Service Haarlem CMS";

//==================================
// CMS PAGES (for syntax see bottom)
//==================================

$p = new page();

$p->TITLE = "Banners";
$p->NAME  = "banners";
$p->TABLE = "banners";
$p->TAB   = "Homepage";

$p->addField("Plaatje",	"img",		"img|1|img/banners|resize:1280-264",		"",			"200px");
$p->addField("Tekst",	"text",		"text",									"!required","400px");
$p->addField("Link",	"link",		"text",									"!required","100px");
$p->addField("Actief",	"active",	"bool",									"",			"75px");

$p->CUSTOM_ORDER = true;

core::addPage($p);

######

$p = new page();

$p->TITLE = "Wist u dat";
$p->NAME  = "didyouknow";
$p->TABLE = "didyouknow";
$p->TAB   = "Homepage";

$p->addField("Tekst",	"text","text","",			"600px");
$p->addField("Link",	"link","text","!required",	"100px");

$p->CUSTOM_ORDER = true;

core::addPage($p);	

######

$p = new page();

$p->TITLE = "Slideshow";
$p->NAME  = "home_slideshow";
$p->TABLE = "home_slideshow";
$p->TAB   = "Homepage";

$p->addField("Tekst",	"text",	"text",								"!required",	"100px");
$p->addField("Plaatje", "img",	"img|1|img/blocks|resize:392-325",	"",				"200px");
$p->addField("Link",	"link",	"text",								"!required",	"300px");

$p->CUSTOM_ORDER = true;

core::addPage($p);

######

$p = new page();

$p->TITLE = "Video";
$p->NAME  = "home_video";
$p->TABLE = "home_video";
$p->TAB   = "Homepage";

$p->addField("Video URL",		"video",	"text",	"!required", "225px");
$p->addField("Image", "img",	"img|1|img/blocks|resize:508-306",	"!required",				"225px");
$p->addField("Actief",	"active",	"bool",	"!required", "75px");
//$p->addField("Video",	"type",		"bool",	"!required", "150px");
$p->addField("Image Link",	"link",		"text",	"!required", "150px");

core::addPage($p);

######

$p = new page();

$p->TITLE = "Pakketten";
$p->NAME  = "packages";
$p->TABLE = "packages";

$p->addField("Titel",		"title","text",								"","200px");
$p->addField("Omschrijving","desc",	"textarea|nl2br",					"","300px|limit:400");
$p->addField("Plaatje",		"img",	"img|1|img/packages|resize:149-@",	"","149px");
$p->addField("Prijs",		"text",	"text",								"","100px");

$p->ORDER = "`price` ASC";

core::addPage($p);

######

$p = new page();

$p->TITLE = "Over ons";
$p->NAME  = "people";
$p->TABLE = "people";

$p->addField("Naam",		"name",	"text",								"","200px");
$p->addField("Omschrijving","desc",	"textarea|nl2br",					"","300px|limit:400");
$p->addField("Plaatje",		"img",	"img|1|img/packages|resize:149-@",	"","149px");

$p->CUSTOM_ORDER = true;

core::addPage($p);

######

$p = new page();

$p->TITLE = "NAP";
$p->NAME  = "nap";
$p->TABLE = "nap";

$p->addField("Auto",    "car",	"link_select",		"",	"500px",true,"cars|voertuignr|merk model type kenteken|sortname ASC");	
$p->addField("NAP pdf",	"file",	"file|1|nap|pdf",	"",	"350px");

$p->ORDER = "`car` ASC";

core::addPage($p);

######

/*$p = new page();

$p->TITLE = "Best day deal";
$p->NAME  = "bdd";
$p->TABLE = "bdd";
$p->TAB   = "Homepage";

$p->addField("Auto",		 "voertuignr",	"link_select","","300px",false,"cars|voertuignr|merk model type|sortname ASC");
$p->addField("Best day deal","active","bool","","75px");

$p->CAN_ADD = false;
$p->CAN_MOD = false;
$p->CAN_DEL = false;

core::addPage($p);*/

######

$p = new page();

$p->TITLE = "Auto opties";
$p->NAME  = "sold";
$p->TABLE = "cars";

$p->addField("Kenteken",	 "kenteken",	 "text",		"","60px",false);
$p->addField("Merk",		 "merk",		 "text",		"","100px");
$p->addField("Model",		 "model",		 "text",		"","100px");
$p->addField("Type",		 "type",		 "text",		"","100px");
$p->addField("Verkocht",	 "verkocht",     "bool|j,n",	"","75px");
$p->addField("Milieubewust", "milieu_bewust","bool",	    "","90px");
$p->addField("Aanbieding",	 "newprice",     "bool",		"","75px");
$p->addField("Best day deal","best_day_deal","bool",        "","75px");
$p->addField("Caroussel",	 "showticker",   "bool",		"","90px");
$p->addField("Actief",	     "active",       "bool",	    "","75px");

$p->CAN_ADD = false;
$p->CAN_MOD = false;

$p->ORDER = "`sortname` ASC";

core::addPage($p);

######

$p = new page();

$p->TITLE = "Pagina's";
$p->NAME  = "sections";
$p->TABLE = "sections";

$p->addField("Titel",				"title",	"text",				"",			"150px",false);
$p->addField("Tekst",				"text",		"textarea|nl2br",	"!required","400px|limit:200");	
$p->addField("Achtergrondkleur",	"bg_color",	"color",			"!required","100px");
$p->addField("Achtergrondplaatje",	"bg_image",	"img|1|img/bg",		"!required","200px");

$p->CAN_ADD = false;
$p->CAN_DEL = false;

$p->ORDER = "`title` DESC";

core::addPage($p);

######

$p = new page();

$p->TITLE = "Landingspagina's";
$p->NAME  = "landing";
$p->TABLE = "landing";

$p->addField("Domein",				"domain",	"text",								"",			"150px");
$p->addField("Banner",				"img",		"img|1|img/banners|resize:990-300",	"!required","200px");
$p->addField("Tekst",				"title",	"text",								"",			"150px");
$p->addField("Merk",				"merk",	"link_select",						"!required","100px");
$p->addField("Model",				"model",	"link_select",						"!required","100px");
$p->addField("Brandstof",			"fuel",		"link_select",						"!required","100px");
$p->addField("Achtergrondkleur",	"bg_color",	"color",							"!required","hide");
$p->addField("Achtergrondplaatje",	"bg_image",	"img|1|img/bg",						"!required","hide");

/*  $p->addField("Merk",				"brand",	"link_select",						"!required","100px",true,"cars|merk|merk"); */
/*  $p->addField("Model",				"model",	"link_select",						"!required","100px",true,"cars|model|model"); */
/*  $p->addField("Brandstof",			"fuel",		"link_select",						"!required","100px",true,"cars|brandstof|brandstof"); */

$p->ORDER = "`domain` ASC";

core::addPage($p);

######

$p = new page();

$p->TITLE = "Footer blok";
$p->NAME  = "footerblock";
$p->TABLE = "footerblock";

$p->TAB	  = "Homepage";

$p->addField("Titel",	"title",	"text",							"","150px");
$p->addField("Tekst",	"text",		"textarea",						"","300px");
$p->addField("Plaatje",	"img",		"img|1|img/other|resize:230-109","","100px");
$p->addField("Link",	"link",		"text",							"","150px");

$p->CAN_ADD = false;
$p->CAN_DEL = false;

core::addPage($p);

########

$p = new unique_page();

$p->TITLE = "Autokaart";
$p->NAME  = "kaart";
$p->FILE  = "unique/kaart.php";

core::addPage($p);
	
##############
# NEWSLETTER #
##############

$p = new page();

$p->TITLE = "Auto nieuwsbrief";
$p->NAME  = "template_cars";
$p->TABLE = "template_cars";
$p->TAB	  = "Nieuwsbrief archief";

$p->addField("Titel","title","text","","150px");

$p->addField("Auto 1","car_one",	"link_select","","100px",true,"cars|voertuignr_hexon|merk model type|created_at|desc");
$p->addField("Auto 2","car_two",	"link_select","","100px",true,"cars|voertuignr_hexon|merk model type|created_at|desc");
$p->addField("Auto 3","car_three",	"link_select","","100px",true,"cars|voertuignr_hexon|merk model type|created_at|desc");

$p->combineFields("Rij",array("car_one","car_two","car_three"),0);

$p->ADD_TEXT  = "Nieuwe nieuwsbrief opmaken";
$p->EDIT_TEXT = "Nieuwsbrief aanpassen";

$p->ORDER = "id DESC";

core::addPage($p);

########

$p = new page();

$p->TITLE = "Tekst nieuwsbrief";
$p->NAME  = "template_text";
$p->TABLE = "template_text";
$p->TAB	  = "Nieuwsbrief archief";

$p->addField("Titel","title","text","","150px");
$p->addField("Tekst","text","textarea|nl2br","","400px|limit:500");

$p->ADD_TEXT  = "Nieuwe nieuwsbrief opmaken";
$p->EDIT_TEXT = "Nieuwsbrief aanpassen";

$p->ORDER = "id DESC";

core::addPage($p);

########

$p = new page();

$p->TITLE = "Best day deal nieuwsbrief";
$p->NAME  = "template_bestdaydeal";
$p->TABLE = "template_bdd";
$p->TAB   = "Nieuwsbrief archief";

$p->addField("Auto","car",	"link_select","","100px",true,"cars|voertuignr_hexon|merk model type|created_at|desc");

$p->ADD_TEXT  = "Nieuwe nieuwsbrief opmaken";
$p->EDIT_TEXT = "Nieuwsbrief aanpassen";

$p->ORDER = "id DESC";

core::addPage($p);

########

$p = new page();

$p->TITLE = "Inschrijvingen";
$p->NAME  = "newsletter_people";
$p->TABLE = "newsletter";
$p->TAB	  = "Nieuwsbrief";

$p->addField("Naam","name","text","","150px");
$p->addField("E-mail adres","email","text","","300px");
$p->addField("Inschrijfdatum","signup_date","date","!required","150px");

$p->ORDER = "email ASC";

$p->MODULES["right_top"][] = "exporter";

core::addPage($p);

########

$p = new unique_page();

$p->TITLE = "Versturen";
$p->NAME  = "versturen";
$p->FILE  = "newsletter/archive.php";
$p->TAB	  = "Nieuwsbrief";

core::addPage($p);

########

$p = new unique_page();

$p->TITLE = "Statistieken";
$p->NAME  = "stats";
$p->FILE  = "newsletter/stat_first.php";
$p->TAB	  = "Nieuwsbrief";

core::addPage($p);
	
/*
	###############
	CMS PAGE SYNTAX
	###############
	Parameters between [block brackets] are optional
	
	--
	
	$p = new page();
	
	$p->TITLE = "Displayed title";
	$p->NAME  = "Short name used for code and urls";
	$p->TABLE = "MySQL table";
	$p->TAB   = "New or existing tab that this page should fall under";
   
    --
    
	$p->addField(
	field title,			string
	
	field name,				string
	
	edit type,				text
							bool|[1,0]								this shows a toggleable tick or cross whether it's on 1 or 0 for boolean values (you can fill in other yes,no as parameter)
							check|option,option,option				creates checkboxes named and valued by each option
							textarea|[nl2br/rich]					if you add nl2br the users input will go through an nl2br function, if you add rich a html editor will be loaded in the textarea	
							file|num|upload_path|ext,ext			file upload field, which uploads to upload_path and can have extensions ext,ext,ext etc
							img|num|upload_path|ext,ext|[resize:x-y]|	when you add resize, 2 extra forms will be added that allow the user to resize the image when uploading
																	if you also add :x-y then the img will have a locked resize
																	use @ instead of a number if you don't want to restrict one axis, example: 50-@ will resize to a width of 50px with y being resized proportionally
																	if you type max instead of resize the image will only be resized if the dimensions exceed the provided limits
																	Note: if somebody picks an already uploaded file and the sizes don't match the new sizes, a new file will be created with the new dimensions leaving the original intact
							date|[now]								without now provides a date picker, with now automatically enters the current datetime
 							color|[default]							creates a field with a color picker, if [default] is provided it should be a hex color code
							link_select								creates a selectbox linked to values from another table defined by the "link to other table" parameter
							link_select_mult|num					creates num amount selectboxes where you can pick a value taken from the first two values in the "link to other table" parameter
							
	check requirements,		various keywords to define what should be checked on form submission, use like this: keyword|keyword|keyword:value etc [] means optional
							!required 								this allows the field to be left blank
							min:value								input should be at least value long
							max:value								input can't be longer than value
							num[:x-y]								input has to be numeric, if :x-y is provided the number has to be between x and y, use @ instead of x or y to remove min or max
							values:"value1"-"value2"-"value3"   	input can only be one of the provided values, NOTE: also use " " for numbers!
							
							new check requirements can be added in _core.php -> class page -> function handleErrors()
	column width,			number + px or %, you can also type hide to only show this field in the forms when adding or editing
							you can also add |limit or |limit:num to limit the number of characters shown. When no num is provided the limit will be decided from the column width (ONLY WORKS WITH A FIXED (PX) WIDTH)
	
	editable,				true / false								[default=true]
	
	link to other table 	linked_table|linked_field|returned_field|orderfield|orderdir 	[default=""] 
	);
	
	EXAMPLE:
	
	$p->addField("field_title","field_name",'img|uploads/images|resize',"min:4|max:10",true);
   
	NOTES: 
	Fields will show up in the order that they are added
	
	--
	
	When some fields need to be combined to fall under the same category (such as multiple titles for different languages), you can use combineFields();
   	
	$p->combineFields("Title",array("field","field"));
	
	Note that these fields still have to be put in through addField, this function currently only works for textareas and text inputs
	
	--
	
	Sometimes you only want to show some fields when a certain type is selected, with the following function you can add a dropdown that filters the input fields
	
	$p->createSelectFilter("Title","field",array("Option"=>"shown fields"),"size"));
	
    --
	ADD_TEXT and EDIT_TEXT can be used to change the "Item aan x toevoegen" and "Item aanpassen" to something different, for example:
	
	$p->ADD_TEXT  = 'Nieuwe nieuwsbrief opmaken';
	$p->EDIT_TEXT = 'Nieuwsbrief aanpassen'; 
	
	--
    
	You can either order the fields on one certain field, or allow custom ordering by drag and drop (to support this an extra integer field called `order` should be created in the SQL)
   
	$p->ORDER = "`field` ASC/DESC";
	
	or
	
	$p->CUSTOM_ORDER = true;
	
	--
	
	If the user input needs to be slugified to a field you can use the following:
	
	$p->slug('slug field','field to be slugged',['additional fields to be slugged if not unique']);
	
	This will slugify whatever was entered in 'field to be slugged' and check if its unique, if its not unique it will add what was entered in the 'additional fields' to the slug.
	
	--
	
	MODULES
	Modules are blocks of information posted in either the left or right column of a page depending on the value of position
	position can be: 
	left 		(left column)
	right_top 	(right column, above everything)
	right_mid	(right column, between forms and data)
	right_bot	(right column, below everything)
	Some modules require parameters, [paramater] means optional
	Available modules:
	structure							 - Shows the table structure
	selector|field						 - Divides the page per unique 'field' and provides links to subpages, linked fields are supported
	counter|[source_field]|[value_field] - If only 'counter' is provided, the number of fields will be returned,
										   With extra parameters shows the count of rows that unique 'source_field' has [in 'value_field']
											  Tip: counter looks best in the left column
											  Example: counter|field			will show how many rows every unique `field` has	
													   counter|field|field2 	will show the count of all unique `field2` by every unique `field` 
	sum|field							 - Shows the sum of all `field`s
													   
	you can change the title of any module by adding ,title to the end
	you can add sql conditions by putting them on a new line (works for counter and sum)
	new modules can be added in _core.php -> class page -> function printModules()
	
	EXAMPLE:
	
	$p->MODULES["position"][] = "module_name|parameters";
   
    --
	
	OPTIONAL VARIABLES:
   
	$p->CAN_ADD 	(default: true) 	set to false to disallow addition of new fields
	$p->CAN_DEL 	(default: true) 	set to false to disallow deletion of fields
	$p->CAN_MOD 	(default: true) 	set to false to disallow editing of fields
	$p->CAN_VIEW 	(default: true)		set to false to disallow viewing of fields (useful when only wanting to show modules)
	$p->COMMENTS 	(default: true)		set to false to disable showing comments from table	
	$p->SEARCH 		(default: true)		set to false to disable searchbar			
	$p->SUPER		(default: false)	set to true to make this page only visible for superadmins
	$p->HIDE		(default: false)	set to true to hide this page from the navigation, this is used for pages that are linked to from other pages but can't be accessed individually
	
	--
	The following line is needed to make the system work, make sure it's added after each page entry.
	
	core::addPage($p);
	
	#########
	
*/
?>
