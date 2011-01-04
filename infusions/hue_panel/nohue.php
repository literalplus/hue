<?php
/*-------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+--------------------------------------------------------+
| Filename: infusion_db.php
| Author: xxyy
+--------------------------------------------------------*/
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