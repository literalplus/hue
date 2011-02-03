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
	echo "<script type='text/javascript'>
	function showWindow() {
  win = new Window( { className: 'spread', url: 'popup.php?page=comment&id=".$data['id']."',
    title: 'Zus&auml;tzlicher Kommentar', width:400,
    height:300, destroyOnClose: true, recenterAuto:false } ); 
  win.showCenter();
}</script>";
	echo THEME_BULLET." <a href='javascript:showWindow();'>Zus&auml;tzliche Anweisungen</a> ";
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
case "ank":
navi_hue();
add_to_title("Haus&uuml;bungsinformationssystem&#187;Ank&uuml;ndigung anzeigen&#187;".$_GET['ank']);
$sql=dbquery("SELECT * FROM ".DB_HUE." WHERE id='".$_GET['ank']."'");
while($data = dbarray($sql)){
$klasse=dbquery("SELECT name FROM ".DB_HUE_KLASSEN." WHERE id=".$data['klasse']."");
$klasse=mysql_fetch_array($klasse);
opentable("Haus&uuml;bungsinformationssystem - Ank&uuml;ndigung anzeigen");
echo "<font size='4'><table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>";
echo'<tr class="tbl2"><td>eingesendet von:</td><td>'.$data['name'].'</td></tr>
<tr class="tbl1"><td>Klasse:</td><td>'.huehtml($klasse[0]).'</td></tr>
<tr class="tbl2"><td>Fach:</td><td>'.huehtml($data['fach']).'</td></tr>
<tr class="tbl1"><td>Ank&uuml;ndigung:</td><td>'.huebb($data['hue']).'</td></tr>
<tr class="tbl2"><td>Kommentar:</td><td>'.huebb($data['comment']).'</td></tr>
<tr class="tbl1"><td>Tag:</td><td>'.getday($data['dayid']).'</td></tr>
<tr class="tbl2"><td>Abgabetermin:</td><td>'.huehtml($data['abgabe']).'</td></tr></table></font>';
closetable();

		if ($data['comments']=="1") {
			require_once INCLUDES."comments_include.php";
			showcomments("H", DB_HUE, "id", (int)$_GET['ank'], FUSION_SELF."?page=ank&ank=".(int)$_GET['ank']);
		}
		if ($data['rate']=="1") {
			require INCLUDES."ratings_include.php";
			showratings("H", (int)$_GET['ank'], FUSION_SELF."?page=ank&ank=".(int)$_GET['ank']); 
		}
footer_hue();
closetable();
}
break;
case "einsenden":
navi_hue(3);
add_to_title("Haus&uuml;bungsinformationssystem&#187;Haus&uuml;bung einsenden");
opentable("Haus&uuml;bung einsenden");
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<form name='inputform' action='index.php?page=send' method='post'>";
echo'<tr class="tbl2"><td>Klasse[<a href="newkl.php" target="_blank" onclick="NeueKlasse(); return false">Neu</a>]:</td><td>';
klassenliste();
echo'</td></tr>';
echo'<tr class="tbl1"><td>Fach[<a href="newfach.php" target="_blank" onclick="NeuesFach(); return false">Neu</a>]:</td><td>';
fachliste();
echo'</td></tr>
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
echo'<tr class="tbl2"><td>Gesch&uuml;tzt mit <a href="http://german-210644433597.spampoison.com"><img src="http://pics5.inxhost.com/images/sticker.gif" border="0" width="80" height="15"/></a></td><td><input type="submit" name="submit" value="Einsenden" class="button" /> oder <input type="reset" name="reset" value="Reset" class="button" /></td></tr>';
} else echo'<tr class="tbl2"><td>Gesch&uuml;tzt mit <a href="http://german-210644433597.spampoison.com"><img src="http://pics5.inxhost.com/images/sticker.gif" border="0" width="80" height="15"/></a></td><td><span id="submitbutton"><input type="submit" value="Ich bin kein Spambot! " class="button" name="submit" /></span> oder <input type="reset" value="Reset" class="button" /></td></tr>';
echo"</table></form>";
footer_hue();
closetable();
break;
case "send":
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