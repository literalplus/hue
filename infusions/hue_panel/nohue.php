<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: nohue.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/header.php";
$nooff=true;
require_once "hue.icl.php";
openside("Keine Haus&uuml;bung?");
if(!iHUE || !isset($_GET['day'])){
echo 'Zugriff verweigert!';
closeside();
footer_hue();
die();
}
dbquery("UPDATE ".DB_HUE_TAG." SET nohue=1 WHERE id=".$_GET['day']."");
echo'Erfolg!';
closeside();
footer_hue();
?>