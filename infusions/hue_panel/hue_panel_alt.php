<?php
/*-------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+--------------------------------------------------------+
| Filename: hue_panel.php
| Author: xxyy
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$nooff=true;

require_once INFUSIONS."hue_panel/hue.icl.php";

$nop=true;
$panel="";


$klasse=(isset($_POST['klasse'])) ? $_POST['klasse'] : 0;
$where=(isset($_POST['klasse'])) ? " WHERE kl='".$_POST['klasse']."'" : "";



$panel .= '<form name="hue_panel" action="'.$PHP_SELF.'" method="post"><table class="noborder" cellpadding="0" cellspacing="0" width="100%"><tr>';
$db=dbquery("SELECT * FROM ".DB_HUE_KLASSEN);
while($data= dbarray($db)){
$cellcol=($klasse==$data['id']) ? "tbl1" : "tbl2";
if(!isset($_POST['klasse']) && $data['id']==0){
$cellcol="tbl1";
} else {
$panel .= '<td class="'.$cellcol.'"><input type="hidden" name="klasse" value="'.$data['id'].'" /><input type="submit" name="hue_panel_submit_'.$klasse.'" value="'.$data['name'].'" /></td>';
}
}
$panel .='</tr></table></form><br />';

//HÜ
$dayc=0;

$dayresult=dbquery("SELECT * FROM ".DB_HUE_TAG.$where." ORDER BY id DESC LIMIT 1");
while ($day = dbarray($dayresult)) {
$klassentxt=(isset($_POST['klasse'])) ? "" : " [Klasse ".getkl($day['kl'])."] ";
$dayc++;
if($day['name']==date("d.m.y")){
$heute=" [HEUTE] ";
} else {
$heute="";
}
$panel .= "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n<tr><td style='text-align:center;'><strong>".$day['name'].$heute.$klassentxt."</strong></td></tr>";
$panel .= "<tr><td><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n";
$panel .= "<tr><td></td><td><strong>Fach</strong></td><td><strong>H&Uuml;(Kurzfassung)</strong></td><td><strong>Abgabetermin</strong></td></tr>";
$hc=0;
$ac=0;
$result=dbquery("SELECT * FROM ".DB_HUE." WHERE status='1' AND dayid=".$day['id']."");
while ($data = dbarray($result)) {
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
$fach=dbquery("SELECT name FROM ".DB_HUE_FACH." WHERE kurz='".$data['fach']."'");
$fach=mysql_fetch_array($fach);
$fach=$fach[0];
if($data['typ']=="hu"){
	$panel .='<tr'/* bgcolor="'.$color.'"*/.'><td class="'.$cell_color.'"><a href="'.HUE.'index.php?page=hue&hue='.$data['id'].'" title="Haus&uuml;bung anzeigen:'.$fach.' bis '.$data['abgabe'].'"><img src="'.HUE_IMAGES.'hu.png" alt="H&Uuml;" title="Dieser Eintrag ist vom Typ \'Haus&uuml;bungsinformation\'." /></td><td class="'.$cell_color.'">'.$fach.'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$hc++;
	} else {
	$panel .='<tr'/* bgcolor="'.$color.'"*/.'><td class="'.$cell_color.'"><a href="'.HUE.'index.php?page=ank&ank='.$data['id'].'" title="Haus&uuml;bung anzeigen:'.$data['fach'].'  bis '.$data['abgabe'].'"><img src="'.HUE_IMAGES.'a.png" alt="Ank&uuml;ndigung" title="Dieser Eintrag ist vom Typ \'Ank&uuml;ndigung\'." /></td><td class="'.$cell_color.'">'.$data['fach'].'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$ac++;
	}
}
if($hc==0 && $ac==0){
if($day['nohue']==1 || $day['name'] != date("d.m.y")){
$panel .='</table></td></tr><tr class="tbl1"><td><div class="text-align:center">Heute, '.$day['name'].' keine Haus&uuml;bung!</div></td></tr>';
} else $panel .='</table></td></tr><tr class="tbl1"><td><div class="text-align:center"><strong>Bis jetzt</strong> sind f&uuml;r den Tag '.$day['name'].' noch keine Haus&uuml;bungsinformationen verf&uuml;gbar.</div></td></tr>';
$panel .= "</td></tr></table><br />";
} else {
$panel .= "</table></td></tr></table><br />";
}
}
//ende HÜ
if(defined("HUE_WARTUNG")){
$nop=false;
echo'<div class="admin-message">Panel kann nicht angezeigt werden:H&Uuml; ist im Wartungsmodus!</div>';
}
if($dayc==0){
$panel .= "<div class='admin-message'>Keine Tage vorhanden!</div>";
}

if($nop){
opentable("Aktuelle Haus&uuml;bungen",true,"on");
echo $panel;
footer_hue(false);
closetable();
}


?>