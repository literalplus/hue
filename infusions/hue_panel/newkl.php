<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: newkl.php
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
openside("Neue Klasse erstellt");
echo'<div class="admin-message">Neue Klasse erstellt</div>';
$edit=dbquery("INSERT INTO ".DB_HUE_KLASSEN." SET name='".$_POST['klasse']."'");
closeside();
footer_hue();
} elseif(isset($_GET['delete'])) {
openside("Klasse l&ouml;schen");
echo'<a href="newkl.php?del=true&kl='.$_GET['kl'].'">Wirklich l&ouml;schen?</a>';
closeside();
footer_hue();
} elseif(isset($_GET['del'])) {
openside("Klasse gel&ouml;scht");
$query="DELETE FROM ".DB_HUE_KLASSEN." WHERE id='".$_GET['kl']."'";
$del=dbquery($query);
echo'gel&ouml;scht.';
closeside();
footer_hue();
} else {
openside("Neuen Tag erstellen");
echo'Du bist gerade im Begriff, eine neue Klasse zu erstellen.Diese kann dann f&uuml;r neue Haus&uuml;bungsinformationen verwendet werden.Bitte vergewissere dich, dass die zu erstellende Klasse noch nicht existiert!';
echo"Klasse erstellen:<form name='inputform' action='newkl.php' method='post'><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>";
echo"<tr class='tbl1'><td>Name:</td><td><input name='klasse' type='text' class='textbox' /></td></tr>
<tr><td></tr><td><input type='submit' name='submit' value='Klasse erstellen' class='button' /></td></tr></table></form>";
closeside();
footer_hue();
}
?>