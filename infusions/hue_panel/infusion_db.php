<?php
/*-------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+--------------------------------------------------------+
| Filename: infusion_db.php
| Author: xxyy
+--------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if (!defined("DB_HUE")) {
	define("DB_HUE", DB_PREFIX."hue");
}

if (!defined("DB_HUE_SETTINGS")) {
	define("DB_HUE_SETTINGS", DB_PREFIX."hue_settings");
}
if (!defined("DB_HUE_KLASSEN")) {
	define("DB_HUE_KLASSEN", DB_PREFIX."hue_klassen");
}
if (!defined("DB_HUE_FACH")) {
	define("DB_HUE_FACH", DB_PREFIX."hue_fach");
}
if (!defined("DB_HUE_TAG")) {
	define("DB_HUE_TAG", DB_PREFIX."hue_tag");
}
?>