<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: hue_admin.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
//set-settings,sende-edit einsenden,hue - HÜ erstellen,ank - ANK erstellen,edit - edit,send - ank senden,day - tage verwalten,ues - Usereinsendungen,huea - hü/as verwalten,klasse - klassen,fach - fächer
require_once "../../maincore.php";
require_once TEMPLATES."admin_header.php";
require_once INFUSIONS."hue_panel/hue.icl.php";
// Load Definitions


// Some security checks:
// XXX must be replaced with the admin rights you did define in infusion.php
if (!checkrights("HUE") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }

/*// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."infusion_folder/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."infusion_folder/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."infusion_folder/locale/English.php";
}*/

navi_hue(2);
if(!isset($_GET['page'])){
navi_admin(0);
opentable("H&Uuml;&#187;Administration");
add_to_title("&#187;H&Uuml;");

echo'Willkommen in der Administration von Haus&uuml;bungsinformationssystem.<br />';
echo'Version:';
$v = HUE_VERSION; // Alter $version to whatever variable your version is stored in!
echo "<!-- Version Checker 2.0.0 @ http://version.starefossen.com - Copyright Starefossen 2007-2009 -->";
echo "<script type='text/javascript' src='http://version.starefossen.com/infusions/version_updater/checker/js.php?ps=hue&amp;v=".$v."'></script>";
echo "<noscript><a href='http://blacktigers.bplaced.net/' target='_blank'><strong>JavaScript disabled:</strong> Check version manually!</a></noscript>";

closetable();
} else {
switch ($_GET['page']) {
case "seo":
if(isset($_POST['submit'])){
if($_POST['on'] != 'x'){
$edit=dbquery("UPDATE ".DB_HUE_SETTINGS." SET hue_set='".$_POST['seo']."' WHERE hue_set_name='seo'");
}
}

opentable("H&Uuml; SEO URL-Rewrite (mod_rewrite)");
$error=true;
if(MOD_REWRITE_ABLE) {
echo "<div class='tbl2'>Wenn du SEO URL-Rewrite aktivierst, solltest du sicher sein, dass mod_rewrite auf deinem Webserver aktiviert ist, da sonst fast alle Seiten einen 404-Fehler ausgeben!<br />
Du musst zuerst die .htaccess-Datei (infusions/hue_panel/.htaccess) einstellen, bevor du diese Funktion benutzen kannst!<br />
</div><br /><br />";
$error=false;
} elseif(!file_exists(INFUSIONS."hue_panel/.htaccess")) {
echo "<div class='tbl2'>Vorsicht Fehler!<br />
Die .htaccess-Datei konnte im H&Uuml;-Verzeichnis nicht gefunden werden! Bitte lade sie (erneut) hoch. Wenn sie in deinem H&Uuml;-Paket nicht enthalten war, bitte downloade das Paket erneut.<br />
Leider kannst du ohne .htaccess SEO-Url-Rewrite nicht aktivieren.</div><br /><br />";
} else {
echo "<div class='tbl2'>Vorsicht Fehler!<br />
MOD_REWRITE scheint nicht aktiviert zu sein!<br />
Bitte kontaktiere deinen Webhoster, ob es m&ouml;glich w&auml;re, MOD_REWRITE zu aktivieren, wenn du es ben&ouml;tigst.</div><br /><br />";
}

echo "<form name='seo' method='post' action='hue_admin.php?page=seo&aid=".iAUTH."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>SEO URL-Rewrite aktivieren?</td>\n";
echo "<td width='50%' class='tbl'><select name='seo' class='textbox'".(MOD_REWRITE_ABLE ? "" : " disabled='disabled'").">\n";
echo "<option value='1'".($hue['seo'] == "1" ? " selected='selected'" : "").">Ja</option>\n";
echo "<option value='0'".($hue['seo'] == "0" ? " selected='selected'" : "").">Nein</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
if(!$error) {
echo "<input type='submit' name='submit' value='Absenden!' class='button' />";
} else {
echo "<input type='submit' name='submit' value='Vorsicht, Fehler!' disabled='disabled' class='button' />";
}
echo "</td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

break;
case "set":
if(isset($_POST['submit'])){
if($_POST['on'] != 'x'){
$edit=dbquery("UPDATE ".DB_HUE_SETTINGS." SET hue_set='".$_POST['on']."' WHERE hue_set_name='on'");
}
if($_POST['showbanner'] != 'x'){
$edit=dbquery("UPDATE ".DB_HUE_SETTINGS." SET hue_set='".$_POST['showbanner']."' WHERE hue_set_name='showbanner'");
}
if($_POST['free'] != 'x'){
$edit=dbquery("UPDATE ".DB_HUE_SETTINGS." SET hue_set='".$_POST['free']."' WHERE hue_set_name='free'");
}
if($_POST['mods'] != '0'){
$edit=dbquery("UPDATE ".DB_HUE_SETTINGS." SET hue_set='".$_POST['mods']."' WHERE hue_set_name='mods'");
}
if($_POST['showcopy'] != 'x'){
$edit=dbquery("UPDATE ".DB_HUE_SETTINGS." SET hue_set='".$_POST['showcopy']."' WHERE hue_set_name='showcopy'");
}
echo'<div class="admin-message" id="close-message">Einstellungen erfolgreich gespeichert!</div>';
}
navi_admin(1);
add_to_title("&#187;H&Uuml;&#187;Einstellungen");
opentable("&#187;H&Uuml;&#187;Einstellungen");
$result=dbquery("SELECT hue_set FROM ".DB_HUE_SETTINGS." WHERE hue_set_name='on'");
$result=mysql_fetch_array($result);
$on=$result[0];
$result=dbquery("SELECT hue_set FROM ".DB_HUE_SETTINGS." WHERE hue_set_name='showcopy'");
$result=mysql_fetch_array($result);
$showcopy=$result[0];
$result=dbquery("SELECT hue_set FROM ".DB_HUE_SETTINGS." WHERE hue_set_name='showbanner'");
$result=mysql_fetch_array($result);
$showbanner=$result[0];
$result=dbquery("SELECT hue_set FROM ".DB_HUE_SETTINGS." WHERE hue_set_name='free'");
$result=mysql_fetch_array($result);
$free=$result[0];
$result=dbquery("SELECT hue_set FROM ".DB_HUE_SETTINGS." WHERE hue_set_name='mods'");
$result=mysql_fetch_array($result);
$mods=$result[0];
echo"<form action='hue_admin.php".$aidlink."&page=set' method='post' name='xy'>
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr><strong><td style='text-align:left'>Einstellung</td><td style='text-align:center;'>Aktueller Wert</td><td style='text-align:right'>&Auml;ndern</td></strong></tr>
<tr class='tbl1'><td style='text-align:left;' class='tbl1'>H&Uuml; aktivieren?</td><td style='text-align:center;'>".$on."</td><td style='text-align:right;' class='tbl1'><select name='on' size='1' class='textbox'><option value='x' label='Nicht &auml;ndern' selected='selected'>Nicht &auml;ndern</option><option value='1' label='Ja'>Ja</option><option value='0' label='Nein'>Nein</option></select></td></tr>
<tr class='tbl2'><td style='text-align:left;' class='tbl2'>&copy; anzeigen (&copy; xxyy)?</td><td style='text-align:center;'>".$showcopy."</td><td style='text-align:right;' class='tbl2'><select name='showcopy' size='1' class='textbox'><option value='x' label='Nicht &auml;ndern' selected='selected'>Nicht &auml;ndern</option><option value='1' label='Ja'>Ja</option><option value='0' label='Nein'>Nein</option></select></td></tr>
<tr class='tbl1'><td style='text-align:left;' class='tbl1'>Banner anzeigen (Navigation)?</td><td style='text-align:center;'>".$showbanner."</td><td style='text-align:right;' class='tbl1'><select name='showbanner' size='1' class='textbox'><option value='x' label='Nicht &auml;ndern' selected='selected'>Nicht &auml;ndern</option><option value='1' label='Ja'>Ja</option><option value='0' label='Nein'>nein</option></select></td></tr>
<tr class='tbl2'><td style='text-align:left;' class='tbl2'>Haus&uuml;bungsinformationen durch H&Uuml;-Moderator freischalten?</td><td style='text-align:center;'>".$free."</td><td style='text-align:right;' class='tbl2'><select name='free' size='1' class='textbox'><option value='x' label='Nicht &auml;ndern' selected='selected'>Nicht &auml;ndern</option><option value='1' label='Ja'>Ja</option><option value='0' label='Nein'>nein</option></select></td></tr>
<tr class='tbl1'><td style='text-align:left;' class='tbl1'>H&Uuml;-Moderator-Gruppe(GruppenID):</td><td style='text-align:center;'>".$mods."</td><td style='text-align:right;' class='tbl1'><input type='text' name='mods' class='textbox' value='".$mods."' /></td></tr>
<tr class='tbl1'><td style='text-align:left;' class='tbl1'></td><td style='text-align:center;'></td><td style='text-align:right;'><input type='submit' name='submit' class='button' value='A B S E N D E N' /></td></tr>
</table></form>";
closetable();
break;
case "sende":
navi_admin(2);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung/Ank&uuml;ndigung editiert");
if(!isset($_POST['submit']) || !isset($_POST['id'])){
redirect("hue_admin.php".$aidlink);
}
if(isset($_POST['comments'])){
$comments="1";
} else {
$comments="0";
}
if(isset($_POST['rate'])){
$rate="1";
} else {
$rate="0";
}
$db="UPDATE ".DB_HUE." SET hue_short='".$_POST['hue_short']."',fach='".$_POST['fach']."',hue='".$_POST['hue']."',comment='".$_POST['comment']."',status='".$_POST['free']."',abgabe='".date_format(date_create($_POST['abgabe2']),"d.m.y")."',comments=".$comments.",rate=".$rate.",dayid='".$_POST['dayid']."',typ='".$_POST['typ']."',klasse='".$_POST['klasse']."',uid='".$_POST['uid']."' WHERE id='".$_POST['id']."'";
if(!dbquery($db)){
echo'<div class="admin-message">&Auml;nderung <strong>nicht</strong> erfolgreich eingetragen.<br />
Fehler 1:dbquery('.$db.') gescheitert.</div>';
navi_admin(2);
} else {
echo'<div class="admin-message">&Auml;nderung erfolgreich eingetragen!</div>';

}
break;
case "hue":
add_to_title("&#187;H&Uuml;&#187;Haus&uuml;bungsinformation erstellen");
openside("H&Uuml;&#187;Haus&uuml;bungsinformation erstellen");
echo'Zum Erstellen einer Haus&uuml;bungsinformation benutze bitte das "Haus&uuml;bungsinformation einsenden"-Formular.<br />
Du wirst in 5 Sekunden zum Formular weitergeleitet.';
add_to_head('<meta http-equiv="refresh" content="5; URL='.HUE.'index.php?page=einsenden">');
closeside();
break;
case "edit":
if(!isset($_GET['id'])) redirect("admin_hue.php".$aidlink);
navi_admin(2);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung/Ank&uuml;ndigung bearbeiten");
opentable("editieren");
$db=dbquery("SELECT * FROM ".DB_HUE." WHERE id='".$_GET['id']."'");
while($data=dbarray($db)){
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<form name='inputform' action='hue_admin.php".$aidlink."&page=sende' method='post'>";
if($data['typ']=="hue"){
$hue=" checked='checked'";
$ank="";
} else {
$hue="";
$ank= "checked='checked'";
}
echo'<tr class="tbl1"><td><input type="hidden" name="id" value="'.$_GET['id'].'" />Typ:</td><td><select name="typ" size="1" class="textbox"><option value="hue" label="Haus&uuml;bung"'.$hue.'>Haus&uuml;bung</option><option value="ank" label="Ank&uuml;ndigung"'.$ank.'>Ank&uuml;ndigung</option></select></td></tr>
<tr class="tbl2"><td>Klasse[<a href="newkl.php" target="_blank" onclick="javascript:NeueKlasse(); return false;">Neu</a>]:</td><td>';
klassenliste($data['klasse']);
echo'</td></tr>';
echo'<tr class="tbl1"><td>Fach[<a href="newfach.php" target="_blank" onclick="javascript:NeuesFach(); return false;">Neu</a>]:</td><td>';
fachliste($data['fach']);
echo'</td></tr>
<tr class="tbl2"><td>Haus&uuml;bung/Ank&uuml;ndigung:</td><td><textarea name="hue" rows="5" cols="60" class="textbox">'.$data['hue'].'</textarea></td></tr>
<tr class="tbl1"><td></td><td>'.display_bbcodes("70%","hue","inputform").'</td></tr>
<tr class="tbl2"><td>Haus&uuml;bung/Ank&uuml;ndigung(Kurzfassung, maximal 140 Zeichen)</td><td><textarea id="hue_short" name="hue_short" class="textbox" rows="5" cols="60" maxleght="140">'.$data['hue_short'].'</textarea></td></tr>
<tr class="tbl2"><td>Kommentar:</td><td><textarea name="comment" rows="5" cols="60" class="textbox">'.$data['comment'].'</textarea></td></tr>
<tr class="tbl1"><td></td><td>'.display_bbcodes("70%","comment","inputform").'</td></tr>';


echo'<tr class="tbl2"><td>Abgabetermin(YYYY-MM-DD):</td><td><input class="textbox" type="date" min="2010" max="3000" value="'.date("Y-m-d").'" onInput="abgabe.value=value" name="abgabe2" value="'.$data['abgabe'].'">
<output name="abgabe"></output>&nbsp;&nbsp;Falls du Opera nutzt, kannst du das Datum ausw&auml;hlen.</td></tr>';
echo'<tr class="tbl1"><td>Tag[<a href="newtag.php" target="_blank" onclick="javascript:NeuerTag(); return false;">Neu</a>]:</td><td>';
tagliste($data['dayid']);
echo'</td></tr>';


echo'<tr class="tbl2"><td>Name:</td><td><input type="text" class="textbox" value="'.$data['name'].'" name="name" /><input type="hidden" name="uid" value="'.$data['uid'].'" /></td></tr>';
echo'<tr class="tbl1"><td>Optionen:</td><td><input type="checkbox" name="comments" value="1" checked="checked" />Kommentare erlauben?<br />
<input type="checkbox" name="rate" value="1" checked="checked" />Bewertungen erlauben?';
echo'<br /><input type="hidden" name="free" value="1" />';
echo'</td></tr>
<tr class="tbl2"><td></td><td><input type="submit" name="submit" value="Einsenden" class="button" /> oder <input type="reset" name="reset" value="Reset" class="button" /></td></tr>';
echo"</table></form>";
}
closetable();
break;
case "ank":
navi_admin(2);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Ank&uuml;ndigung einsenden");
opentable("Ank&uuml;ndigung einsenden");
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<form name='inputform' action='hue_admin.php".$aidlink."&page=send' method='post'>";
echo'<tr class="tbl2"><td>Klasse[<a href="newkl.php" target="_blank" onclick="NeueKlasse(); return false">Neu</a>]:</td><td>';
klassenliste();
echo'</td></tr>';
echo'<tr class="tbl1"><td>Fach[<a href="newfach.php" target="_blank" onclick="NeuerTag(); return false">Neu</a>]:</td><td>';
fachliste();
echo'</td></tr>
<tr class="tbl2"><td>Ank&uuml;ndigung:</td><td><textarea name="hue" rows="5" cols="60" class="textbox"></textarea></td></tr>
<tr class="tbl1"><td></td><td>'.display_bbcodes("70%","hue","inputform").'</td></tr>
<tr class="tbl2"><td>Ank&uuml;ndigung(Kurzfassung, maximal 140 Zeichen)</td><td><textarea id="hue_short" name="hue_short" class="textbox" rows="5" cols="60" maxleght="140" onKeyDown=\"textCounter(this,\'count_display_hue_short\',140);\" onKeyUp=\"textCounter(this,\'count_display_hue_short\',140);\"></textarea></td></tr>
<!--<tr class="tbl1"><td>Verbleibende Zeichen:</td><td><span id="count_display_hue_short" style="padding : 1px 3px 1px 3px; border:1px solid;"><strong>140</strong></span></td></tr>!-->
<tr class="tbl2"><td>Kommentar:</td><td><textarea name="comment" rows="5" cols="60" class="textbox"></textarea></td></tr>
<tr class="tbl1"><td></td><td>'.display_bbcodes("70%","comment","inputform").'</td></tr>';


echo'<tr class="tbl2"><td>Abgabetermin(YYYY-MM-DD):</td><td><input class="textbox" type="date" min="2010" max="3000" value="'.date("Y-m-d").'" onInput="abgabe.value=value" name="abgabe2">
<output name="abgabe"></output>&nbsp;&nbsp;Falls du Opera nutzt, kannst du das Datum ausw&auml;hlen.</td></tr>';
echo'<tr class="tbl1"><td>Tag[<a href="newtag.php" target="_blank" onclick="NeuerTag(); return false">Neu</a>]:</td><td>';
tagliste();
echo'</td></tr>';
echo'<tr class="tbl2"><td>Optionen:</td><td><input type="checkbox" name="comments" value="1" checked="checked" />Kommentare erlauben?<br />
<input type="checkbox" name="rate" value="1" checked="checked" />Bewertungen erlauben?';
echo'<br /><input type="hidden" name="free" value="1" /><input type="hidden" name="name" value="'.$userdata['user_name'].'" /><input type="hidden" name="uid" value="'.$userdata['user_id'].'" />';
echo'</td></tr>
<tr class="tbl1"><td></td><td><input type="submit" name="submit" value="Einsenden" class="button" /> oder <input type="reset" name="reset" value="Reset" class="button" /></td></tr>';
echo"</table></form>";
closetable();
break;
case "send":
navi_admin(2);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Ank&uuml;ndigung absenden");
if(isset($_POST['submit'])){
if(isset($_POST['comments'])){
$comments="1";
} else {
$comments="0";
}
if(isset($_POST['rate'])){
$rate="1";
} else {
$rate="0";
}
$db="INSERT INTO ".DB_HUE." SET hue_short='".parseubb($_POST['hue_short'])."',fach='".$_POST['fach']."',hue='".$_POST['hue']."',comment='".$_POST['comment']."',status='".$_POST['free']."',abgabe='".date_format(date_create($_POST['abgabe2']),"d.m.y")."',comments=".$comments.",rate=".$rate.",dayid='".$_POST['dayid']."',typ='ank'";
if(!dbquery($db)){
echo'<div class="admin-message">Ank&uuml;ndigung <strong>nicht</strong> erfolgreich eingesendet.<br />
Fehler 1:dbquery('.$db.') gescheitert.</div>';
navi_admin(2);
} else {
echo'<div class="admin-message">Ank&uuml;ndigung erfolgreich eingesendet!</div>';

}
} else {
echo'<div class="admin-message">Du hast keine Ank&uuml;ndigung eingesendet!</div>';
echo'<script language="text/javascript">
document.write "Du wirst weitergeleitet...";
window.setTimeout("location.href=\'hue_admin.php'.$aidlink.'&page=ank\'", 10000);
</script>';
}
break;
case "day":
navi_admin(3.1);
opentable("Tage verwalten");
echo'Hier kannst du die erstellten Tage verwalten.';
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>";
echo '<tr><td style="text-align:center;">erstellte Tage:[<a href="newtag.php" target="_blank" onclick="NeuerTag(); return false">Neu</a>]</td><td style="text-align:center;">Klasse</td><td style="text-align:center;">Optionen</td></tr>';
$result=dbquery("SELECT * FROM ".DB_HUE_TAG."");
$dc=0;
$i=0;
while ($data = dbarray($result)){
$dc++;
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
newpopup(HUE."newtag.php?delete=true&day=".$data['id'],"del_".$data['id'],"Tag l&ouml;schen");
echo'<tr class="'.$cell_color.'"><td>'.$data['name'].'</td><td>'.$data['kl'].'</td><td>'."[<a href='newtag.php?delete=true&day=".$data['id']."' target='_blank' onclick='del_".$data['id']."(); return false'>L&ouml;schen</a>]</td></tr>";
}
if($dc==0){
echo'<tr><td>Keine Tage in der DB vorhanden!</td><td></td><td></td></tr>';
}
echo'</table>';

closetable();
footer_hue();
break;
case "ues":
navi_admin(3);
opentable("Usereinsendungen verwalten");
echo'Bitte w&auml;hle oben, was du verwalten willst!';
closetable();
break;
case "huea":
navi_admin(3.2);
if(isset($_GET['action'])){
if($_GET['action']=="del"){
$del=dbquery("DELETE FROM ".DB_HUE." WHERE id='".$_GET['id']."'");
echo'<div class="admin-message" id="close-message">Haus&uuml;bungsinformation erfolgreich gel&ouml;scht!</div>';
} elseif($_GET['action']=="free") {
$free=dbquery("UPDATE ".DB_HUE." SET status='1' WHERE id='".$_GET['id']."'");
echo'<div class="admin-message" id="close-message">Haus&uuml;bungsinformation erfolgreich freigeschaltet!</div>';
}
}
opentable("Haus&uuml;bungsinformationen und Ank&uuml;ndigungenen verwalten");
echo'Hier kannst du die erstellten Haus&uuml;bungsinformationen und Ank&uuml;ndigungen verwalten.';
showhuelist(false,"",true);

closetable();
break;
case "klasse":
navi_admin(3.3);
opentable("Klassen verwalten");
echo'Hier kannst du die erstellten Klassen verwalten.';
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>";
echo '<tr><td style="text-align:center;">erstellte Klassen:[<a href="newkl.php" target="_blank" onclick="oeffnefenster(this.href); return false">Neu</a>]</td><td style="text-align:center;">Optionen</td></tr>';
$result=dbquery("SELECT * FROM ".DB_HUE_KLASSEN."");
$dc=0;
$i=0;
while ($data = dbarray($result)){
$dc++;
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
echo'<tr class="'.$cell_color.'"><td>'.$data['name'].'</td><td>'."[<a href='newkl.php?delete=true&kl=".$data['id']."' target='_blank' onclick='oeffnefenster(this.href); return false'>L&ouml;schen</a>]</td></tr>";
}
if($dc==0){
echo'<tr><td><div class="color:maroon">Keine Klassen in der DB vorhanden!Bitte lege eine an, damit das Haus&uuml;bungsinformationssystem benutzbar ist!</div></td><td></td><td></td></tr>';
}
echo'</table>';

closetable();
break;
case "fach":
navi_admin(3.4);
opentable("F&auml;cher verwalten");
echo'Hier kannst du die erstellten F&auml;cher verwalten.';
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>";
echo '<tr><td style="text-align:center;">erstellte F&auml;cher:[<a href="newfach.php" target="_blank" onclick="oeffnefenster(this.href); return false">Neu</a>]</td><td style="text-align:center;">K&uuml;rzel</td><td style="text-align:center;">Optionen</td></tr>';
$result=dbquery("SELECT * FROM ".DB_HUE_FACH."");
$dc=0;
$i=0;
while ($data = dbarray($result)){
$dc++;
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
echo'<tr class="'.$cell_color.'"><td>'.$data['name'].'</td><td>'.$data['kurz'].'</td><td>'."[<a href='newfach.php?delete=true&fach=".$data['id']."' target='_blank' onclick='oeffnefenster(this.href); return false'>L&ouml;schen</a>]</td></tr>";
}
if($dc==0){
echo'<tr><td><div class="color:maroon">Keine Klassen in der DB vorhanden!Bitte lege eine an, damit das Haus&uuml;bungsinformationssystem benutzbar ist!</div></td><td></td><td></td></tr>';
}
echo'</table>';

closetable();
}
}

footer_hue();

require_once TEMPLATES."footer.php";

?>