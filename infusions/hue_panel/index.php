<?php
//&#187; --> &raqou; --> »
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: index.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
//klasse,hue,ank,einsenden,send
require_once "../../maincore.php";
require_once TEMPLATES."header.php";

require_once "hue.icl.php";
if(!isset($_GET['page'])){
$_GET['page']="klasse";
}

switch ($_GET['page']) {
case "klasse":
$db1="SELECT id FROM ".DB_HUE_KLASSEN." ORDER BY id";
$db1=dbquery($db1);
$db1=mysql_fetch_array($db1);
$klasse=(isset($_GET['klasse'])) ? $_GET['klasse'] : $db1[0];
//$klasse=$_GET['klasse'];
navi_hue();


add_to_title(" - Haus&uuml;bungsinformationssystem");
opentable("Haus&uuml;bungsinformationssystem");
echo'<table class="noborder" cellpadding="0" cellspacing="0" width="100%"><tr>';
$db=dbquery("SELECT * FROM ".DB_HUE_KLASSEN." ORDER BY id");
$kcount=0;
while($data= dbarray($db)){
$cellcol=($klasse==$data['id']) ? "tbl1" : "tbl2";
if(!isset($_GET['klasse']) && $kcount==0){
$cellcol="tbl1";
$klasse=$data['id'];
}
echo'<td class="'.$cellcol.'"><a href="'.HUE.'index.php?page=klasse&klasse='.$data['id'].'">'.$data['name'].'</a></td>';
$kcount++;
}
echo'</tr></table>';

showhuelist($klasse);

footer_hue();
closetable();

break;
case "hue":
navi_hue();
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung anzeigen&#187;".$_GET['hue']);
$sql=dbquery("SELECT * FROM ".DB_HUE." WHERE id='".$_GET['hue']."'");
while($data = dbarray($sql)){


	opentable(getfach($data['fach'])."-Haus&uuml;bung f&uuml;r den Tag ".getday($data['dayid']));
	echo "<!--hue_poster --><div class='floatfix'>".$data['hue']."</div>
	<div class='news-footer'>
		";
			
	echo THEME_BULLET." ";
	if($data['uid']=="0"){
	echo huehtml($data['name']);
	} else {
	$status=dbquery("SELECT user_status FROM ".DB_USERS." WHERE user_id=".$data['uid']);
	$status=mysql_fetch_array($status);
	$status=$status[0];
	echo profile_link($data['uid'], $data['name'], $status);
	}
	echo " ";
	echo "am ".getday($data['dayid'])." ";
	echo THEME_BULLET." Abgabetermin: ".huehtml($data['abgabe']);
	/*echo "<script type='text/javascript'>
	function showWindow() {
  win = new Window( { className: 'spread', url: 'popup.php?page=comment&id=".$data['id']."',
    title: 'Zus&auml;tzlicher Kommentar', width:400,
    height:300, destroyOnClose: true, recenterAuto:false } ); 
  win.showCenter();
}</script>";*/
	//echo THEME_BULLET." <a href='javascript:showWindow();'>Zus&auml;tzliche Anweisungen</a> ";
	echo THEME_BULLET." Klasse ".getkl($data['klasse'])." ";
	if (checkrights("HUE")) { echo "&middot; <a href='".HUE."hue_admin.php".$aidlink."&amp;page=edit&amp;id=".$data['id']."'><img src='".get_image("edit")."' alt='Editieren' title='Editieren' style='vertical-align:middle;border:0;' /></a>\n"; }
	
	"</div>\n";
closetable();
		if ($data['comments']=="1") {
			require_once INCLUDES."comments_include.php";
			showcomments("H", DB_HUE, "id", (int)$_GET['hue'], FUSION_SELF."?page=ank&ank=".(int)$_GET['hue']);
		}
		if ($data['rate']=="1") {
			require INCLUDES."ratings_include.php";
			showratings("H", (int)$_GET['hue'], FUSION_SELF."?page=ank&ank=".(int)$_GET['hue']); 
		}
footer_hue();
closetable();
//}
}
break;
case "einsenden":
navi_hue(3);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung einsenden");
opentable("Haus&uuml;bung einsenden");
echo "<form name='inputform' action='index.php?page=send' id='hueforms' method='post'>";
echo'<fieldset><legend class="legend1">Schritt 1</legend><div id="kl"><fieldset><legend class="legend1">Klasse:</legend>';
echo'<div class="tbl1"><input type="radio" name="klasse" value="exists" />existierende Klasse w&auml;hlen:<br /><span id="exkl">';
klassenliste();//klassen
echo'</span></div><div class="tbl2"><input type="radio" name="klasse" value="new" />neue Klasse erstellen:<br />
Name der Klasse:<input type="text" name="newkl_a" placeholder="Name der Klasse" class="textbox" /></div></fieldset></div>';
echo'<fieldset><legend class="legend1">Fach:</legend><div class="tbl1"><input type="radio" name="fach" value="exists" />existierendes Fach w&auml;hlen:<br />';
fachliste();//fachs
echo'</div><div class="tbl2"><input type="radio" name="fach" value="new" />neues Fach erstellen:<br />
Name des Fachs:<input type="text" name="fach_a" placeholder="Name des Fachs" class="textbox" /><br />
K&uuml;rzel des Fachs:<input type="text" name="fach_b" placeholder="K&uuml;rzel des Fachs" class="textbox" /></div></fieldset>';
echo'<fieldset><legend class="legend1">Tag:</legend>
<div class="tbl1"><input type="radio" name="day" value="exists" />existierenden Tag w&auml;hlen:<br />';
tagliste();
echo'</div><div class="tbl2"><input type="radio" name="day" value="new" />neuen Tag erstellen:<br />
Name des Tages(Datum!):<input type="text" name="newday_a" placeholder="Name des Tages(Datum!)" class="textbox" /></div></fieldset>';
echo'<fieldset><legend class="legend1">Abgabetermin(YYYY-MM-DD):</legend><div class="tbl1"><input class="textbox" type="date" min="2010" max="3000" value="'.date("Y-m-d").'" onInput="abgabe.value=value" name="abgabe2">
<output name="abgabe"></output></div></fieldset>';
echo'</fieldset>
<fieldset><legend class="legend2">Schritt 2</legend>
<fieldset><legend class="legend2">H&Uuml;:</legend><div class="tbl1"><textarea name="hue" rows="5" cols="60" class="textbox"></textarea>
'.display_bbcodes("70%","hue","inputform").'</div></fieldset>
<fieldset><legend class="legend2">H&Uuml;(Kurzfassung, maximal 140 Zeichen)</legend><div class="tbl2"><textarea id="hue_short" name="hue_short" class="textbox" rows="5" cols="60" maxlenght="140"></textarea></div></fieldset></fieldset>';
$name=(isset($userdata['user_name'])) ? $userdata['user_name'] : "";
$disable=(iMEMBER) ? " readonly='readonly'" : "";
$id=(iMEMBER) ? $userdata['user_id'] : 0;
echo'<fieldset><legend class="legend3">Sonstiges</legend>';
echo'<fieldset><legend class="legend3">Name:</legend><div class="tbl1"><input type="text" class="textbox"'.$disable.' value="'.$name.'" name="name" /><input type="hidden" name="uid" value="'.$id.'" /></div></fieldset>';
echo'<fieldset><legend class="legend3">Optionen:</legend><div class="tbl2"><input type="checkbox" name="comments" value="1" checked="checked" />Kommentare erlauben?<br />
<input type="checkbox" name="rate" value="1" checked="checked" />Bewertungen erlauben?</div></fieldset>';
if($hue['free']=="1"){
if(iHUE){
echo'<br /><fieldset><legend class="legend3">Haus&uuml;bung sofort freischalten?</legend><div class="tbl2">
<input type="radio" name="free" value="1" checked="checked" />Ja<input type="radio" name="free" value="0" />Nein</div></fieldset>';
} else {
echo'<br /><input type="hidden" name="free" value="0" />
<fieldset><legend class="legend3">Information</legend>
Deine Haus&uuml;bungsinformation muss zuerst von einem Administrator &uuml;berpr&uuml;ft werden.</fieldset>';
}
} else {
echo'<br /><input type="hidden" name="free" value="1" />';
}
echo'';

echo'<fieldset><legend class="legend3">Absenden</legend>Gesch&uuml;tzt mit 
<a href="http://german-210644433597.spampoison.com">
<img src="http://pics5.inxhost.com/images/sticker.gif" border="0" width="80" height="15" />
</a><input type="submit" name="submit" value="Einsenden" class="button" /> oder <input type="reset" name="reset" value="Reset" class="button" /></fieldset>';
echo"</fieldset></form>";
footer_hue();
closetable();
break;
case "send":
	navi_hue(3);
	add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bungsinformation absenden");
	if(isset($_POST['submit'])){
		$_POST['comments'] = (int) $_POST['comments'];
		$_POST['rate'] = (int) $_POST['rate'];
		$klasse;
		$id=md5(date("U"));
		if($_POST['klasse'] == "exists"){
			$klasse=$_POST['klassen'];
		} else {
			$dbq="INSERT INTO ".DB_HUE_KLASSEN." SET name='".$_POST['newkl_a']."',foobar='".$id."'";
			dbquery($dbq);
			$query=dbquery("SELECT * FROM ".DB_HUE_KLASSEN." WHERE foobar='".$id."'");
			$query=mysql_fetch_array($query);
			$klasse=$query['id'];
		}
		$fach;
		if($_POST['fach'] == "exists"){
			$fach=$_POST['fachs'];
		} else {
			$dbq="INSERT INTO ".DB_HUE_FACH." SET name='".$_POST['fach_a']."',kurz='".$_POST['fach_b']."',foobar='".$id."'";
			dbquery($dbq);
			$query=dbquery("SELECT * FROM ".DB_HUE_FACH." WHERE foobar='".$id."'");
			$query=mysql_fetch_array($query);
			$fach=$query['id'];
		}
		$day;
		if($_POST['day'] == "exists"){
			$day = $_POST['dayid'];
		} else {
			$dbq="INSERT INTO ".DB_HUE_TAG." SET name='".$_POST['newday_a']."',foobar='".$id."'";
			dbquery($dbq);
			$query=dbquery("SELECT * FROM ".DB_HUE_TAG." WHERE foobar='".$id."'");
			$query=mysql_fetch_array($query);
			$day=$query['id'];
		}
		$db="INSERT INTO ".DB_HUE." SET name='".$_POST['name']."',hue_short='".$_POST['hue_short']."',fach='".$fach."',hue='".$_POST['hue']."',status='".$_POST['free']."',abgabe='".date_format(date_create($_POST['abgabe2']),"d.m.y")."',comments=".$_POST['comments'].",rate=".$_POST['rate'].",dayid='".$day."',klasse='".$klasse."',uid='".$_POST['uid']."'";
		if(!dbquery($db)){
			echo'<div class="admin-message">Haus&uuml;bung <strong>nicht</strong> erfolgreich eingesendet.<br />
Fehler 1:dbquery('.$db.') gescheitert.</div>';
			navi_hue(3);
		} else {
			echo'<div class="admin-message">Haus&uuml;bung erfolgreich eingesendet!</div>';

		}
	} else {
		echo'<div class="admin-message">Du hast keine Haus&uuml;bung eingesendet!</div>';
		echo'<script language="text/javascript">
document.write "Du wirst weitergeleitet...";
window.setTimeout("location.href=\'index.php?page=einsenden\'", 10000);
</script>';
	}
	footer_hue();
	break;
case "einsenden_alt":
navi_hue(3);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung einsenden");
opentable("Haus&uuml;bung einsenden");
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<form name='inputform' action='index.php?page=send' method='post'>";
echo'<tr class="tbl2"><td><fieldset><legend>Schritt 1</legend>Klasse:</td><td>';
echo'<input type="radio" name="klasse" value="exists" />existierende Klasse w&auml;hlen:<br /><span id="exkl">';
klassenliste();//klassen
echo'</span><br /><input type="radio" name="klasse" value="new" />neue Klasse erstellen:<br />
<span id="newkl">Name der Klasse:<input type="text" name="newkl_a" placeholder="Name der Klasse" class="textbox" /></span>';
echo'</td></tr>';
echo'<tr class="tbl1"><td>Fach:</td><td><input type="radio" name="fach" value="exists" />existierendes Fach w&auml;hlen:<br />';
fachliste();//fachs
echo'<br /><input type="radio" name="fach" value="new" />neues Fach erstellen:<br />
Name des Fachs:<input type="text" name="fach_a" placeholder="Name des Fachs" class="textbox" /><br />
K&uuml;rzel des Fachs:<input type="text" name="fach_b" placeholder="K&uuml;rzel des Fachs" class="textbox" /></fieldset></td></tr>
<tr class="tbl2"><td>H&Uuml;:</td><td><textarea name="hue" rows="5" cols="60" class="textbox"></textarea></td></tr>
<tr class="tbl1"><td></td><td>'.display_bbcodes("70%","hue","inputform").'</td></tr>
<tr class="tbl2"><td>H&Uuml;(Kurzfassung, maximal 140 Zeichen)</td><td><textarea id="hue_short" name="hue_short" class="textbox" rows="5" cols="60" maxleght="140" onKeyDown=\"textCounter(this,\'count_display_hue_short\',140);\" onKeyUp=\"textCounter(this,\'count_display_hue_short\',140);\"></textarea></td></tr>
<!--<tr class="tbl1"><td>Verbleibende Zeichen:</td><td><span id="count_display_hue_short" style="padding : 1px 3px 1px 3px; border:1px solid;"><strong>140</strong></span></td></tr>!-->
<tr class="tbl1"><td>Zus&auml;tzliche Anweisungen:</td><td><textarea name="comment" rows="5" cols="60" class="textbox"></textarea></td></tr>
<tr class="tbl2"><td></td><td>'.display_bbcodes("70%","comment","inputform").'</td></tr>';


echo'<tr class="tbl1"><td>Abgabetermin(YYYY-MM-DD):</td><td><input class="textbox" type="date" min="2010" max="3000" value="'.date("Y-m-d").'" onInput="abgabe.value=value" name="abgabe2">
<output name="abgabe"></output>&nbsp;&nbsp;Falls du Opera nutzt, kannst du das Datum ausw&auml;hlen.</td></tr>';
echo'<tr class="tbl2"><td>Tag[<a href="newtag.php" target="_blank" onclick="NeuerTag(); return false">Neu</a>]:</td><td>';
tagliste();
echo'</td></tr>';
$name=(isset($userdata['user_name'])) ? $userdata['user_name'] : "";
$disable=(iMEMBER) ? " readonly='readonly'" : "";
$id=(iMEMBER) ? $userdata['user_id'] : 0;

echo'<tr class="tbl1"><td>Name:</td><td><input type="text" class="textbox"'.$disable.' value="'.$name.'" name="name" /><input type="hidden" name="uid" value="'.$id.'" /></td></tr>';
echo'<tr class="tbl2"><td>Optionen:</td><td><input type="checkbox" name="comments" value="1" checked="checked" />Kommentare erlauben?<br />
<input type="checkbox" name="rate" value="1" checked="checked" />Bewertungen erlauben?';
if($hue['free']=="1"){
	if(iHUE){
		echo'<br />Haus&uuml;bung sofort freischalten? <input type="radio" name="free" value="1" checked="checked" />Ja<input type="radio" name="free" value="0" />Nein';
	} else {
		echo'<br /><input type="hidden" name="free" value="0" />Deine Haus&uuml;bungsinformation muss zuerst von einem Administrator &uuml;berpr&uuml;ft werden.';
	}
} else {
	echo'<br /><input type="hidden" name="free" value="1" />';
}
echo'</td></tr>';

if(isset($userdata['user_name'])){
	echo'<tr class="tbl2"><td>Gesch&uuml;tzt mit <a href="http://german-210644433597.spampoison.com"><img src="http://pics5.inxhost.com/images/sticker.gif" border="0" width="80" height="15" /></a></td><td><input type="submit" name="submit" value="Einsenden" class="button" /> oder <input type="reset" name="reset" value="Reset" class="button" /></td></tr>';
} else echo'<tr class="tbl2"><td>Gesch&uuml;tzt mit <a href="http://german-210644433597.spampoison.com"><img src="http://pics5.inxhost.com/images/sticker.gif" border="0" width="80" height="15" /></a></td><td><span id="submitbutton"><input type="submit" value="Ich bin kein Spambot! " class="button" name="submit" /></span> oder <input type="reset" value="Reset" class="button" /></td></tr>';
echo"</table></form>";
footer_hue();
closetable();
break;
case "send_alt":
navi_hue(3);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung absenden");
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
$db="INSERT INTO ".DB_HUE." SET name='".$_POST['name']."',hue_short='".$_POST['hue_short']."',fach='".$_POST['fach']."',hue='".$_POST['hue']."',comment='".$_POST['comment']."',status='".$_POST['free']."',abgabe='".date_format(date_create($_POST['abgabe2']),"d.m.y")."',comments=".$comments.",rate=".$rate.",dayid='".$_POST['dayid']."',typ='hue',klasse='".$_POST['klasse']."',uid='".$_POST['uid']."'";
if(!dbquery($db)){
echo'<div class="admin-message">Haus&uuml;bung <strong>nicht</strong> erfolgreich eingesendet.<br />
Fehler 1:dbquery('.$db.') gescheitert.</div>';
navi_hue(3);
} else {
echo'<div class="admin-message">Haus&uuml;bung erfolgreich eingesendet!</div>';

}
} else {
echo'<div class="admin-message">Du hast keine Haus&uuml;bung eingesendet!</div>';
echo'<script language="text/javascript">
document.write "Du wirst weitergeleitet...";
window.setTimeout("location.href=\'index.php?page=einsenden\'", 10000);
</script>';
}
footer_hue();
}


require_once TEMPLATES."footer.php";

?>