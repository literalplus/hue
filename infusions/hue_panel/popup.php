<?php
require_once "../../maincore.php";
require_once INFUSIONS."hue_panel/infusion_db.php";
switch ($_GET['page']){
case "comment":
if(!isset($_GET['id'])) die('ID nicht angegeben!!');
$data=dbquery("SELECT comment FROM ".DB_HUE." WHERE id='".$_GET['id']."'");
$data=mysql_fetch_array($data);
if($data[0]==""){
echo'Keine zus&auml;tzlichen Anweisungen vorhanden.';
} else {
echo $data[0];
}
break;
}