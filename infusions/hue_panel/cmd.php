<?php
//benötigt:
/*---------------------------------------------------------------------------+
| Pimped Fusion Content Management System
| http://pimped-fusion.net
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01, Copyright (C) 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| Filename: cmd.php
| Authors: Nick Jones (Digitanium),xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if(!function_exists("add_to_head")){
function add_to_head(){
}
}
$nooff=true;
require_once "../../maincore.php";
include THEME."theme.php";
require_once INFUSIONS."hue_panel/hue.icl.php";
$content="Fehler!";
if(isset($_GET['wartung']) && HUE_WARTUNG){
$content="Das Haus&uuml;bungsinformationssystem ist im Wartungsmodus!";
$redirect="<a href='".BASEDIR."'>Zur&uuml;ck zu ".$settings['sitename']."</a>";
} else {
$redirect="<a href='".BASEDIR."'>Zur&uuml;ck zu ".$settings['sitename']."</a>&nbsp;::&nbsp;<a href='".HUE."index.php'>Zur&uuml;ck zum Haus&uuml;bungsinformationssystem</a>&nbsp;::&nbsp;<a href='".HUE."index.php?page=einsenden'>Haus&uuml;bungsinformation einsenden</a>";
}


echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']." - Haus&uuml;bungsinformationssystem</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset='utf8' />\n";
//echo "<meta http-equiv='refresh' content='5'; url='index.php' />\n";
echo "<style type='text/css'>html, body { height:100%; }</style>\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' />\n";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "</head>\n<body class='tbl2 setuser_body'>\n";

echo "<table style='width:100%;height:100%'>\n<tr>\n<td>\n";

echo "<table cellpadding='0' cellspacing='1' width='80%' class='tbl-border center'>\n<tr>\n";
echo "<td class='tbl1'>\n<div style='text-align:center'><!--setuser_pre_logo--><br />\n";
echo "<img src='".HUE."images/banner.png' title='H&Uuml;' alt='H&Uuml;-Banner' /><br /><br />\n";
echo $content."<br /><br />\n";
echo $redirect."<br /><br />\n";
echo "</div>\n</td>\n</tr>\n</table>\n";

echo "</td>\n</tr>\n</table>\n";

echo "</body>\n</html>\n";

if (ob_get_length() !== FALSE){
	ob_end_flush();
}


mysql_close($db_connect);
?>