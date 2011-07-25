<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: infusion.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

// The folder in which the infusion resides.
$infusion_folder = "hue_panel";

// Load Definitions
include INFUSIONS.$infusion_folder."/infusion_db.php";

/*// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS.$infusion_folder."/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS.$infusion_folder."/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS.$infusion_folder."/locale/German.php";
	}*/


// Infusion general information
$inf_title = /*$locale['hue_title']*/"H&Uuml;"; // Your Infusion's Titel
$inf_description = "Infusion zur Verwaltung von Haus&Uuml;bungen."; // Description of your Infusion
$inf_version = "0.1 final"; // Your Infusion's Version
$inf_developer = "xxyy"; // Your Name
$inf_email = "supertux22@gmail.com"; // Your E-Mail
$inf_weburl = "http://blacktigers.bplaced.net/"; // Your Website

// This is needed too:
$inf_folder = $infusion_folder; // The folder in which the infusion resides.

// Some Definitions for the tables in the database:
if (!defined("ENGINE")) define("ENGINE", "ENGINE=MyISAM");
if (!defined("CHARSET")) define("CHARSET", "CHARACTER SET utf8 COLLATE utf8_general_ci");


// Delete any items not required in your infusion below.

// Create some tables in database:
$inf_newtable[1] = DB_HUE." (
id INT(10) UNSIGNED AUTO_INCREMENT,
fach TEXT NOT NULL,
hue VARCHAR(260) NOT NULL,
hue_short VARCHAR(140) NOT NULL,
comment TEXT NOT NULL,
status INT(1) UNSIGNED NOT NULL,
user INT(10) UNSIGNED NOT NULL,
abgabe TEXT NOT NULL,
rate INT UNSIGNED,
comments INT UNSIGNED,
klasse INT UNSIGNED NOT NULL,
typ VARCHAR(2) NOT NULL,
dayid INT UNSIGNED NOT NULL,
name TEXT,
uid INT(100),
PRIMARY KEY (id)
) ".ENGINE." ".CHARSET.";";

$inf_newtable[2] = DB_HUE_SETTINGS." (
hue_set_name TEXT NOT NULL,
hue_set VARCHAR(10) DEFAULT '0'
) ".ENGINE." ".CHARSET.";";

$inf_newtable[3] = DB_HUE_KLASSEN." (
id INT(10) UNSIGNED AUTO_INCREMENT,
name TEXT NOT NULL,
PRIMARY KEY (id)
) ".ENGINE." ".CHARSET.";";

$inf_newtable[4] = DB_HUE_FACH." (
id INT(10) UNSIGNED AUTO_INCREMENT,
name TEXT NOT NULL,
kurz TEXT NOT NULL,
timestamp TEXT,
PRIMARY KEY (id)
) ".ENGINE." ".CHARSET.";";

$inf_newtable[5] = DB_HUE_TAG." (
id INT(10) AUTO_INCREMENT,
name TEXT NOT NULL,
nohue INT(10) NOT NULL,
kl INT(10) NOT NULL,
monat INT(2),
jahr INT(4),
day INT(2),
PRIMARY KEY (id)
) ".ENGINE." ".CHARSET.";";

$inf_newtable[5] = DB_HUE_ABGABE." (
id INT(10) AUTO_INCREMENT,
name TEXT NOT NULL,
PRIMARY KEY (id)
) ".ENGINE." ".CHARSET.";";

// Insert something in your tables:




$inf_insertdbrow[1] = DB_PANELS." SET panel_name='H&Uuml;-Panel', panel_filename='".$inf_folder."', panel_content='', panel_side=2, panel_order='1', panel_type='file', panel_access='0', panel_display='0', panel_status='1' ";

$inf_insertdbrow[2] = DB_HUE_SETTINGS." SET hue_set_name='on',hue_set='0' ";
$inf_insertdbrow[3] = DB_HUE_SETTINGS." SET hue_set_name='showcopy',hue_set='1' ";
$inf_insertdbrow[4] = DB_HUE_SETTINGS." SET hue_set_name='showbanner',hue_set='1' ";
$inf_insertdbrow[12] = DB_HUE_SETTINGS." SET hue_set_name='seo',hue_set='0' ";
$inf_insertdbrow[5] = DB_HUE_KLASSEN." SET name='Beispiel',id='1' ";
$inf_insertdbrow[6] = DB_HUE_FACH." SET name='Mathematik',kurz='M' ";
$inf_insertdbrow[7] = DB_HUE_FACH." SET name='Deutsch',kurz='D' ";
$inf_insertdbrow[8] = DB_HUE_FACH." SET name='Englisch',kurz='E' ";
$inf_insertdbrow[9] = DB_HUE_SETTINGS." SET hue_set_name='free',hue_set='1' ";
$inf_insertdbrow[10] = DB_HUE_SETTINGS." SET hue_set_name='mods',hue_set='0' ";
$inf_insertdbrow[11] = DB_HUE_SETTINGS." SET hue_set_name='lehrer',hue_set='0' ";


// If the infusion gets deinstalled, we have to drop the tables again:
$inf_droptable[1] = DB_HUE;
$inf_droptable[2] = DB_HUE_SETTINGS;
$inf_droptable[3] = DB_HUE_KLASSEN;
$inf_droptable[4] = DB_HUE_FACH;
$inf_droptable[5] = DB_HUE_TAG;
// alter some tables

$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";


// infusions can have multiple admin panels and navigation links.
// Each admin panel and link is specified through an array.

// For admin panels we use $inf_adminpanel[] like so:

// There are 4 items available:

// title - the name of the link shown in the infusions admin panel.
// image - the image displayd in the infusions admin panel.
// link - the name of the admin panel file.
// rights - infusions must have a unique access rights value, can be up to 4 letters (uppercase only).

$inf_adminpanel[1] = array(
	"title" => "H&Uuml; Admin",
	"image" => "hue.gif",
	"panel" => "hue_admin.php",
	"rights" => "HUE"
);


// Site links are defined in similar fashion using $inf_sitelink[] like so:

// Again there are 3 items available:

// title - the name of the link shown in the navigation menu.
// url - the name of the infusion panel file.
// visibility - defines visibility (nGUEST, nMEMBER, nMODERATOR, nADMIN or nSUPERADMIN).


$inf_sitelink[1] = array(
	"title" => "H&Uuml;",
	"url" => "index.php",
	"visibility" => nGUEST
);


?>