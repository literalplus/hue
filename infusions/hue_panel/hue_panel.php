<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: hue_panel.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if(!defined("PIMPED_FUSION")) {
require_once "../../maincore.php";
require_once TEMPLATES."header.php";
}
$nooff=true;
require_once "hue.icl.php";
echo'<br />';
if(HUE_WARTUNG){
add_to_head('<link rel="stylesheet" href="'.HUE.'includes/themes/ui-lightness/jquery.ui.all.css">');
echo'<div class="ui-widget">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>H&Uuml;:</strong> Wartungsmodus aktiviert.';
				if(checkrights("HUE")) echo '[ <a href="'.HUE.'hue_admin.php'.$aidlink.'">Admin</a> ]';
				echo'</p>
			</div>
		</div>';
} else {
add_to_head('<link rel="stylesheet" href="'.HUE.'includes/themes/ui-lightness/jquery.ui.all.css">
	<script src="'.HUE.'includes/jquery144.js"></script>
	<script src="'.HUE.'includes/ui/jquery.ui.core.js"></script>
	<script src="'.HUE.'includes/ui/jquery.ui.widget.js"></script>
	<script src="'.HUE.'includes/ui/jquery.ui.tabs.js"></script>
	<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>');

$lis="";
$klc=0;
$klassen=array();
$dayc=0;
$db=dbquery("SELECT * FROM ".DB_HUE_KLASSEN);
while($kl= dbarray($db)){
$klc++;
$lis .= '<li><a href="#tabs-'.$klc.'">'.$kl['name'].'</a></li>';
$klassen[$klc]='<div id="tabs-'.$klc.'"><p>';
$dayresult=dbquery("SELECT * FROM ".DB_HUE_TAG." WHERE kl='".$kl['id']."' ORDER BY id DESC LIMIT 1");
while ($day = dbarray($dayresult)) {

$dayc++;
$klassen[$klc] .="<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td style='text-align:center;'><strong>".$day['name']." [Klasse ".$kl['name']."]</strong></td></tr>";

$klassen[$klc] .= "<tr><td><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n";
$klassen[$klc] .= "<tr><td></td><td><strong>Fach</strong></td><td><strong>H&Uuml;(Kurzfassung)</strong></td><td><strong>Abgabetermin</strong></td></tr>";
$hc=0;
$ac=0;
$result=dbquery("SELECT * FROM ".DB_HUE." WHERE status='1' AND dayid=".$day['id']."");
while ($data = dbarray($result)) {
$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
$fach=getfach($data['fach']);
if($data['typ']=="hu"){
	$klassen[$klc] .='<tr><td class="'.$cell_color.'"><a href="'.HUE.'index.php?page=hue&hue='.$data['id'].'" title="Haus&uuml;bung anzeigen"><img src="'.HUE_IMAGES.'hu.png" alt="H&Uuml;" title="Dieser Eintrag ist vom Typ \'Haus&uuml;bungsinformation\'." /></td><td class="'.$cell_color.'">'.$fach.'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$hc++;
	} else {
	$klassen[$klc] .='<tr><td class="'.$cell_color.'"><a href="'.HUE.'index.php?page=ank&ank='.$data['id'].'" title="Haus&uuml;bung anzeigen"><img src="'.HUE_IMAGES.'a.png" alt="Ank&uuml;ndigung" title="Dieser Eintrag ist vom Typ \'Ank&uuml;ndigung\'." /></td><td class="'.$cell_color.'">'.$data['fach'].'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$ac++;
	}
}
$klassen[$klc] .= "</table>";
if($hc==0 && $ac==0){
if($day['nohue']==1 || $day['name'] != date("d.m.y")){
$klassen[$klc] .='<tr><td><div style="text-align:center">Heute, '.$day['name'].' keine Haus&uuml;bung!</div>';
} else $klassen[$klc] .='<tr><td><div style="text-align:center"><strong>Bis jetzt</strong> sind f&uuml;r den Tag '.$day['name'].' noch keine Haus&uuml;bungsinformationen verf&uuml;gbar.</div>';
}
$klassen[$klc] .= "</td></tr></table>";
}
if($dayc==0){
$klassen[$klc] .="<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td style='text-align:center;'><strong>Kein Tag vorhanden [Klasse ".$kl['name']."]</strong></td></tr>";
$klassen[$klc] .="<tr><td><strong>F&uuml;r die Klasse ".$kl['name']." sind im Moment weder Tage noch Haus&uuml;bungen verf&uuml;gbar.</td></tr></table>";
}
$klassen[$klc] .= "</p></div>";
}
echo'<div class="demo"><div id="tabs"><ul>';
echo $lis;
//if(checkrights("HUE")) echo '<li><a href="'.HUE.'hue_admin.php'.$aidlink.'" target="_parent">H&Uuml; Admin</a></li>'; //deaktiviert weil öffnet in div :( //)
echo '</ul>';
$i=1;
while(isset($klassen[$i])){
echo $klassen[$i];
$i++;
}
echo'</div></div>';
}
echo'<br />';
?>