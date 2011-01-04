<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: infusion_db.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
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