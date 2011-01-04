<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: newtag.php
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
if(isset($_POST['submit'])){
openside("Neuer Tag erstellt");
echo'<div class="admin-message">Neuer Tag erstellt</div>';
$edit=dbquery("INSERT INTO ".DB_HUE_TAG." SET name='".$_POST['tag']."',nohue=0,kl='".$_POST['kl']."'");
closeside();
footer_hue();
} elseif(isset($_GET['klasse'])) {
openside("Neuen Tag erstellen");
echo'Du bist gerade im Begriff, einen neuen Tag zu erstellen.Dieser kann dann f&uuml;r neue Haus&uuml;bungsinformationen verwendet werden.Bitte vergewissere dich, dass der zu erstellende Tag noch nicht existiert!';
echo'<br />Automatische Pr&uuml;fung:';
$einlesen = dbquery("SELECT COUNT(*) FROM ".DB_HUE_TAG." WHERE name='".date("d.m.y")."'");
$einzeln = mysql_fetch_row($einlesen);
if($einzeln[0]==1) echo "<div style='color:red;'>Tag existiert schon!</div>";
else echo "<div style='color:green;'>Tag existiert noch nicht!</div>";
echo'Der letzte existierende Tag:';
$id=dbquery("SELECT LAST_INSERT_ID() FROM ".DB_HUE_TAG.""); 
$id=dbquery("SELECT name FROM ".DB_HUE_TAG." WHERE id='".$id."'");
$id=mysql_fetch_array($id);
$id=$id[0];
$id=($id == "") ? "Noch kein Tag angelegt!" : $id;
echo'<div style="color:orange;">'.$id.'</div>';
echo"Tag erstellen:<form name='inputform' action='newtag.php' method='post'><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>";
echo"<tr class='tbl1'><td>Datum:</td><td><input name='tag' type='text' class='textbox' value='".date("d.m.y")."' /></td></tr>
<tr><td><input type='hidden' name='kl' value='".$_GET['klasse']."' /></td><td><input type='submit' name='submit' value='Tag erstellen' class='button' /></td></tr></table></form>";
closeside();
footer_hue();
} elseif(isset($_GET['delete'])) {
openside("Tag l&ouml;schen");
echo'<a href="newtag.php?del=true&day='.$_GET['day'].'">Wirklich l&ouml;schen?</a>';
closeside();
footer_hue();
} elseif(isset($_GET['del'])) {
openside("Tag gel&ouml;scht");
dbquery("DELETE FROM ".DB_HUE_TAG." WHERE id='".$_GET['day']."'");
echo'gel&ouml;scht.';
closeside();
footer_hue();
} else {
openside("Tag erstellen&#187;Klassenauswahl");
echo'Um einen neuen Tag zu erstellen, w&auml;hle bitte zuerst, f&uuml;r welche Klasse der Tag erstellt werden soll.';
echo"<form name='inputform' action='newtag.php' method='get'><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr class='tbl1'><td>";
klassenliste();
echo'</td></tr><tr><td><input type="submit" value="A B S E N D E N" class="button" /></td></tr></table></form>';
closeside();
footer_hue();
}
?>