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


//Fehler in der lightbox_head.php umgehen
function add_to_head() {
}

// Fetch the Site Settings from the database and store them in the $settings variable // Pimped: optimised
$settings = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS, false);
if(!dbrows($result)) { die($die1."Settings could not been loaded</strong><br />".mysql_errno()." : ".mysql_error()."</div>"); }
while ($data = dbarray($result)) {
	$settings[$data['settings_name']] = $data['settings_value'];
}



include_once INCLUDES."theme_functions_include.php";
require_once INCLUDES."bbcode_include.php";
if(!defined('HUE')){
	define('HUE',INFUSIONS."hue_panel/");
}
if(!defined('HUE_IMAGES')){
	define('HUE_IMAGES',HUE."images/");
}
if(!defined('HUE_INCLUDES')){
	define('HUE_INCLUDES',HUE."includes/");
}


// Load Definitions
include INFUSIONS."hue_panel/infusion_db.php";


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
/*	add_to_title(" - Haus&uuml;bungsinformation&#187;Mitteilung");
	openside("Haus&uuml;bungsinformationssystem deaktiviert");
	echo "Im Moment ist das Haus&uuml;bungsinformationssystem deaktiviert.";
	echo "<a href=".BASEDIR." title=".BASEDIR.">Zur&uuml;ck zur Hauptseite</a>";
	closeside();
	require_once THEMES."templates/footer.php";*/
	redirect("cmd.php?wartung=true");
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

if($hue['seo']==1){
if(!defined("HUE_SEO")) define("HUE_SEO",true);
} else {
if(!defined("HUE_SEO")) define("HUE_SEO",false);
}

function huem_header(){
global $settings,$hue,$userdata,$locale;
if ($settings['maintenance'] == "1" && ((iMEMBER && $settings['maintenance_level'] == "1" && $userdata['user_id'] != "1") || ($settings['maintenance_level'] > $userdata['user_level']))) { redirect(BASEDIR."maintenance.php"); }
if (iMEMBER) { $result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".(int)$userdata['user_id']."'"); }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>H&Uuml; mobile</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset='utf-8' />\n";
echo "<meta name='Language' content='".$locale['xml_lang']."' />\n";
echo "<meta name='description' content='H&Uuml; mobile' />\n";
echo "<meta name='keywords' content='".$settings['keywords'].",H&Uuml; mobile' />\n";
echo "<meta name='generator' content='Pimped-Fusion - Open Source Content Management System - v".$settings['version_pimp']." - H&Uuml; mobile' />\n";
echo "<meta name='robots' content='index, follow' />\n";
echo "<link rel='stylesheet' href='".THEMES."CityFusion/styles.css' type='text/css' media='screen' />\n";
//if (file_exists(IMAGES."favicon.ico")) { echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n"; }
echo "<script type='text/javascript' src='".INCLUDES_JS."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES_JS."jquery.js'></script>\n";
//if(iADMIN) echo "<script type='text/javascript' src='".INCLUDES_JS."admin-msg.js'></script>\n";
require_once INCLUDES."header_includes.php";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "</head>\n<body>\n";
}
function huem_footer(){

global $db_connect, $settings;


echo "</body>\n</html>\n";

// Cron Job (6 MIN)
if ($settings['cronjob_minute'] < (time()-360)) {
	$result = dbquery("DELETE FROM ".DB_FLOOD_CONTROL." WHERE flood_timestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_CAPTCHA." WHERE captcha_datestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_USERS." WHERE user_joined='0' AND user_ip='0.0.0.0' AND (user_level='".nADMIN."' OR user_level='".nSUPERADMIN."')");
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".time()."' WHERE settings_name='cronjob_minute'");
}

// Cron Job (1 HOUR)
if ($settings['cronjob_hour'] < (time()-3600)) { // TODO
	$time_now = time();

	$new_logs = dbcount("(log_id)", DB_FAILED_LOGINS, "datestamp > '".(int)$settings['cronjob_hour']."'");
	if($new_logs) {
	
		$result = dbquery(
			"SELECT COUNT(fl.log_id) AS tries, fl.user_id, fl.datestamp, MIN(fl.datestamp) AS mindate, MAX(fl.datestamp) AS maxdate,
			tu.user_language
			FROM ".DB_FAILED_LOGINS." fl
			LEFT JOIN ".DB_USERS." tu ON fl.user_id=tu.user_id
			WHERE datestamp > '".(int)$settings['cronjob_hour']."'
			GROUP BY user_id"
		);
	
		$reinc = false;
		
		while ($data = dbarray($result)) {
			if($data['user_language'] != "" && $data['user_language'] != $settings['locale'] && file_exists(LOCALE.$data['user_language']."/global.php")) {
				include LOCALE.$data['user_language']."/global.php";
				$reinc = true;
			}
			
			$message = sprintf($locale['flogins_101'], $data['tries']);
			if($data['tries'] == 1 ) {
				$message .= sprintf($locale['flogins_102'], showdate($settings['longdate'], $data['datestamp']));
			} else {
				$message .= sprintf($locale['flogins_103'], showdate($settings['longdate'], $data['mindate']), showdate($settings['longdate'], $data['maxdate']));
			}
			send_pm($data['user_id'], "0", $locale['flogins_100'], $message, "0");
		}
		if($reinc) include LOCALE.LOCALESET."global.php";
	}
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(int)$time_now."' WHERE settings_name='cronjob_hour'");
}

// Cron Job (24 HOUR)
if ($settings['cronjob_day'] < (time()-86400)) {
	$new_time = time();
	
	$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_datestamp < '".(time()-1209600)."'");
	$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_datestamp < '".(time()-86400)."'");
	
// Pimped: Optimize Tables
if($new_time != $settings['cronjob_day']) { // Don't optimize if we already took a lot of resources
	$optimize_result = dbquery("SHOW TABLE STATUS");
	while($data = dbarray($optimize_result)) {
	   if ($data['Data_free']!=0) {
	      $result = dbquery("OPTIMIZE TABLE ".$data['Name']);
	   }
	}
}

	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".$new_time."' WHERE settings_name='cronjob_day'");
}
if ($settings['login_method'] == "sessions") {
	session_write_close();
}

mysql_close($db_connect);

}


function m_checktheme(){



if(!file_exists(THEMES."CityFusion/theme.php")){
echo'<b>H&Uuml;: Fataler Fehler:</b> Theme &quot;CityFusion&quot; ben&ouml;tigt! Download auf <a href="http://ptown67.de/">http://ptwon67.de/</a>';
die();
}
require_once INCLUDES."theme_functions_include.php";
require_once THEMES."CityFusion/includes/functions.php";
  echo "<table cellspacing='0' cellpadding='0' align='center'>\n";
  echo "<tr><td class='container'>\n";

  # Obere Navigation
  echo "<table cellspacing='0' cellpadding='0' width='100%' class='openborder'><tr>\n";
  echo "<td class='cap' width='50%' style='padding: 0px;'>";
  
	echo "<table cellpadding='0' cellspacing='5'><tr>\n";
	echo "<td class='headerlink'><a href='hue_mobile.php?page=home'><span>H&Uuml;-Home</span></a></td>\n";
	echo "<td class='headerlink'><a href='hue_mobile.php?page=einsenden'><span>H&Uuml; einsenden</span></a></td>\n";
	if(iHUE) echo "<td class='headerlink'><a href='hue_mobile.php?page=home'><span>Mobile Admin</span></a></td>\n";
  	echo "</tr></table>\n";
  
  echo"</td>";
  echo "<td class='cap' width='50%' align='right'><span class='headerdate'>".date("j.n.Y")."&nbsp;&middot;&nbsp;".date("H:i:s")."</span></td>";
  echo "</td></tr></table>\n";
  # Logo
  echo "<table cellspacing='0' cellpadding='0' width='100%' class='openborder'>\n";
  echo "<tr><td class='title' align='center'><img src='".HUE_IMAGES."banner.png' alt='H&Uuml;-Banner' /></td>\n";
  echo "</tr></table>\n";

  # Untere Navigation
  echo "<table cellspacing='0' cellpadding='0' width='100%' class='openborder'><tr>\n";
  echo "<td class='cap' width='50%' style='padding: 0px;'>";
  echo "</td><td class='cap' width='50%' align='right' style='padding: 0px;'>";
  if(!iMEMBER) {
    echo "<form name='loginform' method='post'>\n";
    echo "<input type='text' name='user_name' class='textbox' />\n";
    echo "<input type='password' name='user_pass' class='textbox' />\n";
	echo "<input type='checkbox' name='remember_me' value='y' title='Remember Me' class='textbox' />\n";
    echo "<input type='submit' name='login' class='button' value='Login' style='margin-right: 5px;' />";
    echo "</form>\n";
  } else {
    //echo userlinks();
  }
  echo "</td></tr></table>\n";
}
function huem_content($side=1){
  # Content
  if($side==1){
	echo "<table cellpadding='0' cellspacing='0' width='100%'><tr>\n";
	echo "<td class='content-center' valign='top'>";
	} else {
	echo "</td>";
	echo "</tr>\n</table>\n";
	}
	
	 /* # Content
	echo "<table cellpadding='0' cellspacing='0' width='100%'><tr>\n";
	echo "<td class='content-center' valign='top'>";*/

}

 function footer_hue_mobile($showtxt=true){
 global $_SERVER,$v,$settings;
 
 echo'<!--HÜ mobile v'.HUE_VERSION.' (C) by xxyy - http://blacktigers.bplaced.net/ !-->';
if($showtxt) echo "<br />".$settings['sitename']." garantiert nicht f&uuml;r die Richtigkeit und/oder das Vorhandensein der Informationen.Alle Angaben ohne Gew&auml;hr.";

 }
#admin functions
function navi_mobile_admin($page=0) {
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
if($data['id']==$select){
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
function showhuelist($klassen=false,$where="",$admin=false){
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
if($data['status']=="0" && $data['typ'] == "hu" && $admin==true){
	echo'<tr style="text-align:left;"><td class="'.$cell_color.'"><img src="'.HUE_IMAGES.'hu.png" alt="H&Uuml;" title="Dieser Eintrag ist vom Typ \'Haus&uuml;bungsinformation\'." /></td><td class="'.$cell_color.'">'.$data['fach'].'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td><td class="'.$cell_color.'">[<a href="'.HUE.'hue_admin.php'.$aidlink.'&action=free&id='.$data['id'].'&page=huea">Freischalten</a>] - [<a href="'.HUE.'hue_admin.php'.$aidlink.'&action=del&id='.$data['id'].'&page=huea">L&ouml;schen</a>]</tr>';
} elseif($data['status']=="0" && $data['typ'] == "ank" && $admin==true){
	echo'<tr style="text-align:left;"><td class="'.$cell_color.'"><img src="'.HUE_IMAGES.'hu.png" alt="a" title="Dieser Eintrag ist vom Typ \'Ank&uuml;ndigung\'." /></td><td class="'.$cell_color.'">'.$data['fach'].'</a></td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td><td class="'.$cell_color.'">[<a href="'.HUE.'hue_admin.php'.$aidlink.'&action=free&id='.$data['id'].'&page=huea">Freischalten</a>] - [<a href="'.HUE.'hue_admin.php'.$aidlink.'&action=del&id='.$data['id'].'&pgae=huea">L&ouml;schen</a>]</tr>';

	} elseif($data['typ']=="hu"){
	echo'<tr style="text-align:left;"><td class="'.$cell_color.'"><a href="index.php?page=hue&hue='.$data['id'].'" title="Haus&uuml;bung anzeigen:'.$fach.' bis '.$data['abgabe'].'"><img src="'.HUE_IMAGES.'hu.png" alt="H&Uuml;"  /></a></td><td class="'.$cell_color.'">'.$fach.'</td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
$hc++;
	} else {
	echo'<tr style="text-align:left;"><td class="'.$cell_color.'"><a href="index.php?page=ank&ank='.$data['id'].'" title="Haus&uuml;bung anzeigen:'.$fach.'  bis '.$data['abgabe'].'"><img src="'.HUE_IMAGES.'a.png" alt="Ank&uuml;ndigung" /></a></td><td class="'.$cell_color.'">'.$fach.'</td><td class="'.$cell_color.'">'.$data['hue_short'].'</td><td class="'.$cell_color.'">'.$data['abgabe'].'</td></tr>';
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



//SEO
function hue_seo($url,$seourl) {

if(HUE_SEO){
return $seourl;
} else {
return $url;
}

}
?>