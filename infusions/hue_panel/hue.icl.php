<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: hue.icl.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once INCLUDES."bbcode_include.php";
if(!defined('HUE')){
	define('HUE',INFUSIONS."hue_panel/");
}
if(!defined('HUE_VERSION')){
	define('HUE_VERSION',"0.1");
}
if(!defined('HUE_IMAGES')){
	define('HUE_IMAGES',HUE."images/");
}
if(!defined('HUE_INCLUDES')){
	define('HUE_INCLUDES',HUE."includes/");
}


// Load Definitions
include INFUSIONS."hue_panel/infusion_db.php";

/*// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."infusion_folder/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."infusion_folder/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."infusion_folder/locale/English.php";
}*/

//Einstellungen
$hue=array();
$result=dbquery("SELECT * FROM ".DB_HUE_SETTINGS,false);
if(!dbrows($result)) die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><strong>Einstellungen konnten nicht geladen werden!<br />Settings could not been loaded!</strong><br />".mysql_errno()." : ".mysql_error()."</div>");
while ($data = dbarray($result)) {
	$hue[$data['hue_set_name']] = parseubb($data['hue_set']);
}
//ende EINSTELLUNGEN


if(checkgroup($hue['mods']) || checkrights("HUE")){
	define("iHUE",true);
} else {
	define("iHUE",false);
}



// deaktiviert
if($hue['on']==0 && !checkrights("HUE") && !isset($nooff)){
	add_to_title(" - Haus&uuml;bungsinformation&#187;Mitteilung");
	openside("Haus&uuml;bungsinformationssystem deaktiviert");
	echo "Im Moment ist das Haus&uuml;bungsinformationssystem deaktiviert.";
	echo "<a href=".BASEDIR." title=".BASEDIR.">Zur&uuml;ck zur Hauptseite</a>";
	closeside();
	require_once THEMES."templates/footer.php";
	die();
} elseif($hue['on']==0&&checkrights("HUE") && !isset($nooff)){
	echo'<div class="admin-message" id="hue_wartung">H&Uuml; ist im Wartungsmodus!</div>';
	if(!defined("HUE_WARTUNG")){
		define("HUE_WARTUNG",true);
	}
} elseif($hue['on']==0 && isset($nooff)) {
	if(!defined("HUE_WARTUNG")){
		define("HUE_WARTUNG",true);
	}
} else {
	if(!defined("HUE_WARTUNG")){
		define("HUE_WARTUNG",false);
	}
}

add_to_head('<script type="text/javascript" src="includes/window/javascripts/prototype.js"></script> 
<script type="text/javascript" src="includes/window/javascripts/effects.js"></script>
<script type="text/javascript" src="includes/window/javascripts/window.js"></script>
<script type="text/javascript" src="includes/window/javascripts/window_effects.js"></script>');

//popups

add_to_head('<script type="text/javascript">
function oeffnefenster (url,width,height) {
 fenster = window.open(url, "fenster1", "width=400,height=235,status=yes,scrollbars=yes,resizable=yes");
 fenster.focus();
}
</script>');

//NAVI
function navi_hue($site=1)
{
	global $userdata,$hue,$aidlink;

	opentable("Haus&uuml;bungsinformationssystem&#187;Navi",true,"on");
	echo'<center>';
	if($hue['showbanner']=="1"){
		echo'<img src="'.HUE.'images/banner.png" title="Haus&uuml;bungsinformationssystem-Banner" alt="Haus&uuml;bungsinformationssystem-Banner" /><br />';
	}
	if(checkrights("HUE")){
		$dbresult=dbquery("SELECT status FROM ".DB_HUE." WHERE status='0'");
		$array1=mysql_fetch_array($dbresult);
		$einsendungenuebrig=count($array1);
		$einsendungenuebrig=$einsendungenuebrig-1;
		if($einsendungenuebrig >=1){
			$text="Es sind  noch ".$einsendungenuebrig."Haus&uuml;bungsinformationseinsendungen zu &uuml;berpr&uuml;fen.";
		} else {
			$text="Alles ist gut.";
		}
	}
	//echo $locale['raet_004'] . ' <strong>'.$userdata['user_name'].'</strong>'.$locale['raet_005'].'<strong>'.$userdata['user_id'].'</strong>.<br />';
	if($site==1){
		echo'<a href="'.HUE.'index.php"><strong>Haus&uuml;bungsinformationssystem - Startseite</strong></a>';
	} else {
		echo'<a href="'.HUE.'index.php">Haus&uuml;bungsinformationssystem - Startseite</a>';
	}
	if(checkrights("HUE")){
		if($site==2){
			echo'  ||  <a href="'.HUE.'hue_admin.php'.$aidlink.'"><strong>Administration</strong></a>';
		} else {
			echo'  ||  <a href="'.HUE.'hue_admin.php'.$aidlink.'">Administration</a>';
		}
	}
	if($site==3){
		echo'||<a href="'.HUE.'index.php?page=einsenden"><strong>Haus&uuml;bungsinformation einsenden</strong></a></center>';
	} else {
		echo'||<a href="'.HUE.'index.php?page=einsenden">Haus&uuml;bungsinformation einsenden</a></center>';
	}
	if(checkrights("HUE")){
		echo'<br />'.$text;
	}
	closetable();
	}
 function footer_hue($showtxt=true){
 global $_SERVER,$v,$settings;
 
 echo'<!--HÜ v0.1 (C) by xxyy - http://blacktigers.bplaced.net/ !-->';
 //Einstellungen
$hue=array();
$result=dbquery("SELECT * FROM ".DB_HUE_SETTINGS,false);
if(!dbrows($result)) { die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><strong>Einstellungen konnten nicht geladen werden!<br />Settings could not been loaded!</strong><br />".mysql_errno()." : ".mysql_error()."</div>"); }
while ($data = dbarray($result)) {
	$hue[$data['hue_set_name']] = parseubb($data['hue_set']);
}
//ende EINSTELLUNGEN
 if($hue['showcopy']=="1"){
  if(strstr($_SERVER["HTTP_USER_AGENT"] ,"Firefox"))
 {
    $browser = "Dein Browser:Mozilla Firefox";
 }
 else
 {
 $browser="Dein Browser ist NICHT Mozilla Firefox!!!";
 }
 echo"<script type='text/javascript' language='JavaScript' src='".BASEDIR."infusions/hue_panel/includes/sb_boxover.js'></script>";
 echo'<div style="text-align:left;">';
//Entfernen dieses Copyright-Hinweises ist ohne gültige Lizenz strafbar! aber in den Einstellungen möglich und legal!
//echo'<a href="http://blacktigers.bplaced.net/" title="H&Uuml; v'.$v.' wurde entwickelt von xxyy und optimiert f&uuml;r Mozilla Firefox.">&#187;</a><br />';
echo "<div class='main-body'><a href='http://blacktigers.bplaced.net/' target='_blank' title=\"cssbody=[tbl1] cssheader=[tbl2] fade=[on] fadespeed=[0.06] header=[<img src='".HUE."images/info.png' /><b>H&Uuml; v.".HUE_VERSION."</b>] body=[H&Uuml; wurde entwickelt von xxyy.<br />H&Uuml; wurde optimiert f&uuml;r Mozilla Firefox 3.6.<br /><!--Auf dieser Seite wurden teilweise Grafiken von <a href='http://www.famfamfam.com/'>http://www.famfamfam.com/</a> verwendet.<br />!-->".$browser."]\"><span class='small'>&#187;</span></a>";
if($showtxt) echo "<br />".$settings['sitename']." garantiert nicht f&uuml;r die Richtigkeit und/oder das Vorhandensein der Informationen.Alle Angaben ohne Gew&auml;hr. F&uuml;r die eingesendete Haus&uuml;bungsinformation ist alleine der einsendende User verantwortlich.";
//ende mit Lizenz
//Ab hier ist das Entfernen strafbar(auch mit Lizenz)!
 //echo'<a href="http://www.famfam.com/" title="Manche Icons auf dieser Seite sind von famfamfam.com(Silk Icon Set)">Icons&copy;</a><br>';
 //Ende Copyright
echo'</div></div>';
 }
}
#admin functions
function navi_admin($page=0) {
global $aidlink;
openside("Administrationsnavigation",true,"on");
$tbl=($page==0) ? "tbl1" : "tbl2";
$tbl2=($page==1) ? "tbl1" : "tbl2";
$tbl3=($page==2) ? "tbl1" : "tbl2";
$tbl4=($page==3.1 || $page==3.2 || $page==3 || $page==3.3 || $page==3.4) ? "tbl1" : "tbl2";
$tbl4x1=($page==3.1) ? "tbl1" : "tbl2";
$tbl4x2=($page==3.2) ? "tbl1" : "tbl2";
$tbl4x3=($page==3.3) ? "tbl1" : "tbl2";
$tbl4x4=($page==3.4) ? "tbl1" : "tbl2";
$unter=($page==3.1 || $page==3.2 || $page==3 || $page==3.3 || $page==3.4) ? "<tr><td class='".$tbl4x1."'><a href='".HUE."hue_admin.php".$aidlink."&page=day'>Tage verwalten</a></td><td class='".$tbl4x1."'><a href='".HUE."hue_admin.php".$aidlink."&page=huea'>Haus&uuml;bungen verwalten</a></td><td class='".$tbl4x3."'><a href='".HUE."hue_admin.php".$aidlink."&page=klasse'>Klassen verwalten</a></td><td class='".$tbl4x4."'><a href='".HUE."hue_admin.php".$aidlink."&page=fach'>F&auml;cher verwalten</a></td></tr>" : "";
echo"<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td class='".$tbl."'><a href='".HUE."hue_admin.php".$aidlink."'>Administration - Home</a></td><td class='".$tbl2."'><a href='".HUE."hue_admin.php".$aidlink."&page=set'>Einstellungen</a></td><td class='".$tbl3."'><a href='".HUE."hue_admin.php".$aidlink."&page=ank'>Ank&uuml;ndigung erstellen</td><td class='".$tbl4."'><a href='".HUE."hue_admin.php".$aidlink."&page=ues'>Usereinsendungen verwalten</a></td></tr>
".$unter."
</table>";
closeside();
}
function klassenliste($select=false){
echo'<select name="klasse" size="1" class="textbox"><optgroup label="Klassen:">';
$result=dbquery("SELECT * FROM ".DB_HUE_KLASSEN);
while($data = dbarray($result)){
if($data['id']==$select){
	echo'<option label="'.$data['name'].'" value="'.$data['id'].'" checked="checked">'.$data['name'].'</option>';
	} else {
	echo'<option label="'.$data['name'].'" value="'.$data['id'].'">'.$data['name'].'</option>';
	}
}
echo'</optgroup></select>';
}
function fachliste($select=false){
echo'<select name="fach" size="1" class="textbox"><optgroup label="F&auml;cher:">';
$result2=dbquery("SELECT * FROM ".DB_HUE_FACH);
while ($data = dbarray($result2)) {
if($data['fach']==$select){
	echo'<option label="'.$data['name'].'" value="'.$data['kurz'].'" checked="checked">'.$data['name'].'</option>';
	} else {
	echo'<option label="'.$data['name'].'" value="'.$data['kurz'].'">'.$data['name'].'</option>';
	}
}
echo'</optgroup></select>';
}
function getday($day){
$db=dbquery("SELECT name FROM ".DB_HUE_TAG." WHERE id='".$day."'");
$db=mysql_fetch_array($db);
return huehtml($db[0]);
}
function huebb($text){
$text=nl2br(parseubb(parsesmileys($text)));
return $text;
}
function huehtml($text){
$text=nl2br(htmlspecialchars($text));
return $text;
}
function getkl($kl){
$db=dbquery("SELECT name FROM ".DB_HUE_KLASSEN." WHERE id='".$kl."'");
$db=mysql_fetch_array($db);
return huehtml($db[0]);
}

function getfach($kurz){
$db=dbquery("SELECT name FROM ".DB_HUE_FACH." WHERE kurz='".$kurz."'");
$db=mysql_fetch_array($db);
return huehtml($db[0]);
}
function newpopup($url,$function="showWindow",$name="Popup",$class="spread"){
	if(!popupclass_exists($class)) newpopupclass($class);
	if(!isset($wc)) static $wc=0;
	$wc++;
	echo"<script type='text/javascript'>
		function ".$function."() {
			win_".$wc." = new Window( { className: '".$class."', url: '".$url."',
			title: '".$name."', width:400,
			height:300, destroyOnClose: true, recenterAuto:false } ); 
			win_".$wc.".showCenter();
		}
		</script>";
}

function newpopupclass($class='spread'){
	if(file_exists(HUE."includes/window/themes/".$class.".css")){
		if(!defined("POPUP_".strtoupper($class))){
			define("POPUP_".strtoupper($class),true);
			add_to_head('<link href="includes/window/themes/'.$class.'.css" rel="stylesheet" type="text/css" ></link>');
			return true;
		} else {
		return false;
		}
	} else {
	echo himg(1)."Popupklasse '".$class."' existiert nicht!";
	return false;
	}
}


function popupclass_exists($class="spread"){
if(!defined("POPUP_".strtoupper($class))){
return false;
} else {
return true;
}
}
newpopupclass('default');
newpopupclass();
function himg($img){
switch ($img) {
case 1:
return '<img src="'.HUE_IMAGES.'achtung.png" alt="!" title="!" />';
break;
}
}
newpopup(HUE."newkl.php","NeueKlasse","Neue Klasse erstellen");
newpopup(HUE."newtag.php","NeuerTag","Neuen Tag erstellen");
newpopup(HUE."newfach.php","NeuesFach","Neues Fach erstellen");
function tagliste($select=false){
echo'<select name="dayid" size="3" class="textbox">';
$result2=dbquery("SELECT * FROM ".DB_HUE_TAG);
while ($day = dbarray($result2)) {
if($day['id']==$select){
	echo'<option label="'.$day['name'].'" value="'.$day['id'].'" checked="checked">'.$day['name'].'['.getkl($day['kl']).']</option>';
	} else {
	echo'<option label="'.$day['name'].'" value="'.$day['id'].'">'.$day['name'].'['.getkl($day['kl']).']</option>';
	}
}
echo'</select>';
}
function showhuelist($klassen=false,$where=""){
//HÜ
$db1="SELECT id FROM ".DB_HUE_KLASSEN." ORDER BY id";
$db1=dbquery($db1);
$db1=mysql_fetch_array($db1);
$klasse=($klassen == false) ? $db1[0] : $klassen;
$dayc=0;
if($klassen != false){
$dayresult=dbquery("SELECT * FROM ".DB_HUE_TAG." WHERE kl='".$klassen."' ORDER BY id DESC");
} else {
$dayresult=dbquery("SELECT * FROM ".DB_HUE_TAG.$where);
}
while ($day = dbarray($dayresult)) {
$dayc++;
if($day['name']==date("d.m.y")){
$heute=" [HEUTE] ";
} else {
$heute="";
}
if(iHUE) {
newpopup(HUE."nohue.php?kl=".$klasse."&day=".$day['id'],"NoHue_".$day['id'],"Keine Haus&uuml;bung?");
newpopup(HUE."newtag.php?delete=true&day=".$day['id'],"del_".$day['id'],"Tag l&ouml;schen");
}
if(iHUE) $heute .= " [<a href='nohue.php?kl=".$klasse."&day=".$day['id']."' target='_blank' onclick='NoHue_".$day['id']."(); return false'>Keine Haus&uuml;bung?</a>]";
if(iHUE) $heute .= " [<a href='newtag.php?delete=true&day=".$day['id']."' target='_blank' onclick='del_".$day['id']."(); return false'>L&ouml;schen</a>]";
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n<tr><td style='text-align:center;'><strong>".$day['name'].$heute."</strong></td></tr>";
echo "<tr><td><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n";
echo "<tr><td></td><td><strong>Fach</strong></td><td><strong>H&Uuml;(Kurzfassung)</strong></td><td><strong>Abgabetermin</strong></td></tr>";
$hc=0;
$ac=0;
$result=dbquery("SELECT * FROM ".DB_HUE." WHERE status='1' AND dayid=".$day['id']."");
$i=0;
while ($data = dbarray($result)) {
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
$fach=dbquery("SELECT name FROM ".DB_HUE_FACH." WHERE kurz='".$data['fach']."'");
$fach=mysql_fetch_array($fach);
$fach=$fach[0];
if($data['typ']=="hu"){
	echo'<tr'/* bgcolor="'.$color.'"*/.' style="text-align:left;"><td class="'.$cell_color.'"><a href="index.php?page=hue&hue='.$data['id'].'" title="Haus&uuml;bung anzeigen:'.$fach.' bis '.$data['abgabe'].'"><img src="'.HUE_IMAGES.'hu.png" alt="H&Uuml;"  /></a></td><td class="'.$cell_color.'">'.$fach.'</td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$hc++;
	} else {
	echo'<tr'/* bgcolor="'.$color.'"*/.' style="text-align:left;"><td class="'.$cell_color.'"><a href="index.php?page=ank&ank='.$data['id'].'" title="Haus&uuml;bung anzeigen:'.$fach.'  bis '.$data['abgabe'].'"><img src="'.HUE_IMAGES.'a.png" alt="Ank&uuml;ndigung" /></a></td><td class="'.$cell_color.'">'.$fach.'</td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$ac++;
	}
}
if($hc==0 && $ac==0){
if($day['nohue']==1 || $day['name'] != date("d.m.y")){
echo'</table></td></tr><tr class="tbl1"><td><div class="text-align:center">Heute, '.$day['name'].' keine Haus&uuml;bung f&uuml;r die Klasse '.getkl($klasse).'!!!</div></td></tr>';
} else echo'</table></td></tr><tr class="tbl1"><td><div class="text-align:center">Bis jetzt sind f&uuml;r den Tag '.$day['name'].' noch keine Haus&uuml;bungsinformationen verf&uuml;gbar, dies kann sich allerdings im Laufe des Tages noch &auml;ndern, deswegen &uuml;berpr&uuml;fe den Stand der Haus&uuml;bungen sp&auml;ter noch einmal.</div></td></tr>';
echo "</td></tr></table><br />";
} else {
echo "</table></td></tr></table><br />
<div class='small' style='text-align:right'>".$hc." Haus&uuml;bungsinformation(en), ".$ac." Ank&uuml;ndigung(en)";
}
}
//ende HÜ

if($dayc==0){
echo "<center><strong><font color='maroon'>Keine Tage vorhanden! Sende eine Haus&uuml;bungsinformation ein und klicke bei Tag auf [Neu], um einen Tag zu erstellen.</font></strong></center>";
}
}
?>