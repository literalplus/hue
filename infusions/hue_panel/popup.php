<?php
/*---------------------------------------------------------------------------+
| Hausübungsinformationssystem HÜ
| Copyright (C) 2010 - 2010 
| http://blacktigers.bplaced.net/
+----------------------------------------------------------------------------+
| Filename: popup.php
| Author: xxyy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
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