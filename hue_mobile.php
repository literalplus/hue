<?php
$nooff=true;
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
require_once "maincore.php";
require_once INFUSIONS."hue_panel/hue_mobile.icl.php";

huem_header();

m_checktheme();

huem_content(1);

if(!isset($_GET['page'])) $_GET['page'] = "einsenden";

switch ($_GET['page']){

case "einsenden":
$select=false;
if((isset($_GET['schritt']) && $_GET['schritt'] == 1) || !isset($_GET['schritt'])){

opentable("H&Uuml; mobile: H&Uuml;info einsenden");
  echo "<table cellpadding='0' cellspacing='5'><tr>\n";
  echo "<td class='headerlink2'><a href='".hue_seo("hue_mobile.php?page=einsenden&schritt=1","hue/mobile/einsenden/schritt-1.html")."'><span>Schritt 1:Tag, Klasse und Fach</span></a></td><td>&#187;</td>\n";
  echo "<td class='headerlink'><a><span>Schritt 2:Daten eingeben</span></a></td><td>&#187;</td>\n";
  echo "<td class='headerlink'><a><span>Schritt 3:Daten absenden</span></a></td>\n";
  echo "</tr></table>\n";
  
  
  echo'Willkommen beim mobilen H&Uuml;info-Einsendungs-Assistenten! Hier kannst du deine Haus&uuml;bungsinformation von einem mobilen Ger&auml;t aus einsenden.';
  echo'<form name="hm_1" action="'.hue_seo("hue_mobile.php?page=einsenden&schritt=2","hue/mobile/einsenden/schritt-2.html").'" method="post">';
  echo'<br />';
  openside("Tag festlegen");
  echo'<input type="radio" name="dayselect" value="e" checked="checked" />Existierenden Tag w&auml;hlen:<br />';/*.tagliste().'*/
  
  $dayselect='<select name="dayid" size="3" class="textbox">';
$result2=dbquery("SELECT * FROM ".DB_HUE_TAG);
$dayc=0;
while ($day = dbarray($result2)) {
if($day['id']==$select){
	$dayselect .='<option label="'.$day['name'].'" value="'.$day['id'].'" checked="checked">'.$day['name'].'['.getkl($day['kl']).']</option>';
	} else {
	$dayselect .='<option label="'.$day['name'].'" value="'.$day['id'].'">'.$day['name'].'['.getkl($day['kl']).']</option>';
	}
	$dayc++;
}
if($dayc==0){

echo'<div style="text-align: center; color: #eee; background-image: url(infusions/hue_panel/images/orange.png); border: 1px solid #555; padding: 5px; margin-bottom: 10px;">Keine Tage vorhanden! Bitte erstelle einen, um eine H&Uuml;info einzusenden.</div>';

} else {
echo $dayselect;
echo'</select>';
}
  
  echo'<br /> <b>oder</b> </br /><input type="radio" name="dayselect" value="n" />neuen Tag erstellen: Name(YYYY-MM-DD):<input type="text" class="textbox" name="newday_a" /><br />';
  
  echo'Klasse:';
  $kls2='<select name="newday_b" size="1" class="textbox"><optgroup label="Klassen:">';
  $kc2=0;
$result=dbquery("SELECT * FROM ".DB_HUE_KLASSEN);
while($data = dbarray($result)){

	$kls2 .='<option label="'.$data['name'].'" value="'.$data['id'].'">'.$data['name'].'</option>';
$kc2++;
}

if($kc2==0){

echo'<br /><div style="text-align: center; color: #eee; background-image: url(infusions/hue_panel/images/orange.png); border: 1px solid #555; padding: 5px; margin-bottom: 10px;">Keine Klassen vorhanden! Bitte erstelle eine, um eine H&Uuml;info einzusenden:<input type="text" class="textbox" name="newday_b2" placeholder="Name f&uuml;r die Klasse eingeben" /></div>';

} else {
echo $kls2;

echo "</select>";
}
  
  closeside();
  
  openside("Klasse festlegen");
  echo'<input type="radio" name="klselect" value="e" checked="checked" />Existierende Klasse w&auml;hlen:<br />';/*.klassenliste().*/
  
  echo'<select name="klasse" size="1" class="textbox"><optgroup label="Klassen:">';
$result=dbquery("SELECT * FROM ".DB_HUE_KLASSEN);
while($data = dbarray($result)){
if($data['id']==$select){
	echo'<option label="'.$data['name'].'" value="'.$data['id'].'" checked="checked">'.$data['name'].'</option>';
	} else {
	echo'<option label="'.$data['name'].'" value="'.$data['id'].'">'.$data['name'].'</option>';
	}
}

echo "</select>";
  
  echo'<br /><b> oder</b><br /> <input type="radio" name="klselect" value="n" />neue Klasse erstellen:<br /><input type="text" class="textbox" name="newkl" />';
  closeside();
  
  openside("Fach festlegen");
  echo'<input type="radio" name="fachselect" value="e" checked="checked" />Existierendes Fach w&auml;hlen:<br />';/*.fachliste().*/
  
  echo'<select name="fach" size="1" class="textbox"><optgroup label="F&auml;cher:">';
$result2=dbquery("SELECT * FROM ".DB_HUE_FACH);
while ($data = dbarray($result2)) {
if($data['id']==$select){
	echo'<option label="'.$data['name'].'" value="'.$data['kurz'].'" checked="checked">'.$data['name'].'</option>';
	} else {
	echo'<option label="'.$data['name'].'" value="'.$data['kurz'].'">'.$data['name'].'</option>';
	}
}
echo'</optgroup></select>';
  
  echo'<br /> <b>oder</b> <br /><input type="radio" name="fachselect" value="n" />neues Fach erstellen:<br />Name:<input type="text" class="textbox" name="newfach_a" /><br />K&uuml;rzel:<input type="text" name="newfach_b" class="textbox" />';
  closeside();
  
  openside("Absenden");
  echo'<input type="submit" name="s1_check" value="Absenden &amp; Weiter zu Schritt 2" class="button" />';
  closeside();
  echo'</form>';
  
  closetable();

} elseif($_GET['schritt'] == 2){

opentable("H&Uuml; mobile: H&Uuml;info einsenden");

  echo "<table cellpadding='0' cellspacing='5'><tr>\n";
  echo "<td class='headerlink2'><a href='".hue_seo("hue_mobile.php?page=einsenden&schritt=1","hue/mobile/einsenden/schritt-1.html")."'><span>Schritt 1:Tag, Klasse und Fach</span></a></td><td>&#187;</td>\n";
  echo "<td class='headerlink2'><a href='".hue_seo("hue_mobile.php?page=einsenden&schritt=2","hue/mobile/einsenden/schritt-2.html")."'><span>Schritt 2:Daten eingeben</span></a></td><td>&#187;</td>\n";
  echo "<td class='headerlink'><a><span>Schritt 3: Daten absenden</span></a></td>\n";
  echo "</tr></table>\n";
  
  echo'<form name="s2form" action="'.hue_seo("hue_mobile.php?page=einsenden&schritt=3","hue/mobile/einsenden/schritt-1.html").'" method="post">';
  
  if(!isset($_POST['s1_check'])){ 
  echo'<b>Fehler!</b> Schritt 1 nicht ausgef&uuml;hrt! <div class="headerlink2"><a href="'.hue_seo("hue_mobile.php?page=einsenden&schritt=1","hue/mobile/einsenden/schritt-1.html").'"><span>Bitte versuche es noch einmal!</span></a></div>';
  closetable();
  huem_footer();
  
  closetable();
  
  huem_content(2);
  
  huem_footer();
  
  die();
  
  } else {
   if(!isset($_POST['newday_b'])){
  $query=dbquery("INSERT INTO ".DB_HUE_KLASSEN." (name) VALUE('".$_POST['newday_b2']."')");
  $query2=dbarray(dbquery("SELECT id FROM ".DB_HUE_KLASSEN." LIMIT 1 ORDER BY id"));
  $newday_b=$query2[0];
  } else {
  
  $newday_b=$_POST['newday_b'];
  
  }
  if($_POST['dayselect'] == "e"){
  echo'<input type="hidden" name="dayid" value="'.$_POST['dayid'].'" />';
  } else {
  $query=dbquery("INSERT INTO ".DB_HUE_TAG." (name,kl,nohue) VALUES('".$_POST['newday_a']."','".$newday_b."','0')");
  $query2=dbarray(dbquery("SELECT id FROM ".DB_HUE_TAG." LIMIT 1 ORDER BY id"));
  echo'<input type="hidden" name="dayid" value="'.$query2[0].'" />';
  }
 
  
   if($_POST['fachselect'] == "e"){
  echo'<input type="hidden" name="fach" value="'.$_POST['fach'].'" />';
  } else {
  $query=dbquery("INSERT INTO ".DB_HUE_FACH." (name,kurz) VALUES('".$_POST['newfach_a']."','".$_POST['newfach_b']."')");
  $query2=dbarray(dbquery("SELECT id FROM ".DB_HUE_FACH." LIMIT 1 ORDER BY id"));
  echo'<input type="hidden" name="fach" value="'.$query2[0].'" />';
  }
  
}
  
 } else {
 
 opentable("H&Uuml; mobile: H&Uuml;info einsenden");
  echo "<table cellpadding='0' cellspacing='5'><tr>\n";
  echo "<td class='headerlink2'><span>Schritt 1:Tag, Klasse und Fach</span></td><td>&#187;</td>\n";
  echo "<td class='headerlink2'><span>Schritt 2:Daten eingeben</span></td><td>&#187;</td>\n";
  echo "<td class='headerlink2'><span>Fertig!</span></td>\n";
  echo "</tr></table>\n";
  
  closetable();
  
 }


}
  
huem_content(2);

huem_footer();

?>