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