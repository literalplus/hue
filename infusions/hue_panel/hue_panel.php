<?php
$nooff=true;
require_once "hue.icl.php";

if(defined("HUE_WARTUNG") && HUE_WARTUNG==true) {
if(iHUE){
echo'<div class="admin-message">H&Uuml; ist im Wartungsmodus</div>';
}
} else {
add_to_head('<script type="text/javascript" language="javascript">
 
    var http_request = false;
 
    function macheRequest(url) {
 
        http_request = false;
 
        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request = new XMLHttpRequest();
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }
 
        if (!http_request) {
            alert(\'Ende :( Kann keine XMLHTTP-Instanz erzeugen\');
            return false;
        }
        http_request.open(\'GET\', url, true);
        http_request.onreadystatechange = alertInhalt;
        http_request.send(null);
 
    }
 
    function alertInhalt() {
        if (http_request.readyState == 4) {
              var answer = http_request.responseText;
              if(document.getElementById("inhalt").innerHTML != answer){
                document.getElementById("inhalt").innerHTML = answer;
              }
              else{
                document.getElementById("inhalt").innerHTML = "";
              }
        }
 
    }
</script>');
openside("H&Uuml;-Panel");
echo'<div style="text-align:right;"><select size="1" name="hueselect" id="hueselect" onchange="macheRequest(this.value);" class="textbox">';
echo'<option value="kl" selected="selected">Bitte Klasse ausw&auml;hlen</option>';
$db=dbquery("SELECT * FROM ".DB_HUE_KLASSEN." ORDER BY id");
while($data = dbarray($db)){
echo'<option value="'.HUE.'hue_panel_sk.php?kl='.$data['id'].'">'.$data['name'].'</option>';
}
echo'</select></div><script type="text/javascript">
if(getElementById("select").value != "kl"){
getElementById("select").value="kl";
}</script>';
echo'<div id="inhalt"><center>Ajax-Inhalt.<br />
Bitte w&auml;hle eine Klasse oben aus der Liste, um sie anzusehen.
</center></div>';
echo'<noscript><span style="color:red; font-decoration:bold; text-align:center;">Javascript deaktiviert.<br />
Bitte aktiviere es oder downloade die neueste Version deines Browsers.<br />
Dieses Panel ist kann mit Textbrowsern leider nicht verwendet werden.</span></noscript>';
closeside();
}
?>