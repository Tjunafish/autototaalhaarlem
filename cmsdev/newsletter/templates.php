<?
////////////////////////////
// TEMPLATE CONFIGURATION //
////////////////////////////

$templates["cars"] = 
	array(
	"title"			=> "Auto nieuwsbrief",
	"table"			=> "template_cars",
	"file"			=> "templates/cars.html",
	"titlefield"	=> "title",
	"linkstyle"		=> "color:#ff5702;text-decoration:none;"
	);

$templates["text"] = 
	array(
	"title"			=> "Tekst nieuwsbrief",
	"table"			=> "template_text",
	"file"			=> "templates/text.html",
	"titlefield"	=> "title",
	"linkstyle"		=> "color:#ff5702;text-decoration:none;"
	);
	
$templates["bdd"] =
	array(
	"title"			=> "Best day deal",
	"table"			=> "template_bdd",
	"file"			=> "templates/bestdaydeal.html",
	"titlefield"	=> "date",
	"titletype"		=> "title"
	);
	
/*//////////////////////////////////////////////////////////////

Add a new template:

$templates['name'] =
	array(
	"title"			=> 'Template title',
	"table"			=> 'Template table',
	"file"			=> 'Template html file',
	"titlefield"	=> 'Template title field in template table',
	"linkstyle"		=> 'Style for user-added links'
	"titletype"		=> 'title' (takes from "title") or 'titlefield' (default, takes from titlefield)
	);
	
*///////////////////////////////////////////////////////////////
?>