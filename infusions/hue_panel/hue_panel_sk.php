<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: hue_panel_sk.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if(!defined("PIMPED_FUSION")){
require_once "../../maincore.php";
require_once TEMPLATES."header.php";
}
if(!defined("HUE")){
require_once "hue.icl.php";
}
if(!isset($_GET['kl'])){
echo'kl fehlt!';
} else {
$lis="";
$klc=0;
$dayc=0;
$db=dbquery("SELECT * FROM ".DB_HUE_KLASSEN." WHERE id='".$_GET['kl']."'");
while($kl = dbarray($db)){
$dayresult=dbquery("SELECT * FROM ".DB_HUE_TAG." WHERE kl='".$kl['id']."' ORDER BY id DESC LIMIT 1");
while ($day = dbarray($dayresult)) {

$dayc++;
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td style='text-align:center;'><strong>".$day['name']." [Klasse ".$kl['name']."]</strong></td></tr>";

echo "<tr><td><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n";
echo  "<tr><td></td><td><strong>Fach</strong></td><td><strong>H&Uuml;(Kurzfassung)</strong></td><td><strong>Abgabetermin</strong></td></tr>";
$hc=0;
$ac=0;
$result=dbquery("SELECT * FROM ".DB_HUE." WHERE status='1' AND dayid=".$day['id']."");
while ($data = dbarray($result)) {
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
$fach=getfach($data['fach']);
if($data['typ']=="hu"){
	echo'<tr><td class="'.$cell_color.'"><a href="index.php?page=hue&hue='.$data['id'].'" title="Haus&uuml;bung anzeigen"><img src="images/hu.png" alt="H&Uuml;" /></td><td class="'.$cell_color.'">'.$fach.'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$hc++;
	} else {
	echo'<tr><td class="'.$cell_color.'"><a href="'.HUE.'index.php?page=ank&ank='.$data['id'].'" title="Haus&uuml;bung anzeigen"><img src="'.HUE_IMAGES.'a.png" alt="Ank&uuml;ndigung" title="Dieser Eintrag ist vom Typ \'Ank&uuml;ndigung\'." /></td><td class="'.$cell_color.'">'.$data['fach'].'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$ac++;
	}
}
echo"</table>";
if($hc==0 && $ac==0){
if($day['nohue']==1 || $day['name'] != date("d.m.y")){
echo'<tr><td><div style="text-align:center">Heute, '.$day['name'].' keine Haus&uuml;bung!</div>';
} else echo'<tr><td><div style="text-align:center"><strong>Bis jetzt</strong> sind f&uuml;r den Tag '.$day['name'].' noch keine Haus&uuml;bungsinformationen verf&uuml;gbar.</div>';
}
echo"</td></tr></table>";
}
if($dayc==0){
echo"<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td style='text-align:center;'><strong>Kein Tag vorhanden [Klasse ".$kl['name']."]</strong></td></tr>";
echo"<tr><td><strong>F&uuml;r die Klasse ".$kl['name']." sind im Moment weder Tage noch Haus&uuml;bungen verf&uuml;gbar.</td></tr></table>";
}
}
}
?>