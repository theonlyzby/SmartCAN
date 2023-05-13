<?php
include_once '../www/lang/www.thermostat.php';
class Zigbee_class {
  
  function new() {
	  global $Moyenne, $id_sonde, $Temp_Name, $DB;
	  //$DHT22_pin = html_get("DHT22_Pin");
	  //echo("New Zigbee, id_sonde=".$id_sonde.", DHT22_pin=".$DHT22_pin);
	

	  //$id_sonde  = substr(html_get("id_sonde"),4);
	  $ZoneID    = html_get("ZoneID");
	  $Zone_Name = html_get("Zone_Name");
	  $ZoneNber  = html_get("ZoneNber");
	  $NewZigbee  = html_get("NewZigbee"); $NewZigbee  = "Zigbee_" . substr($NewZigbee, 14);
	  
	  //echo("ZoneID=".$ZoneID . ", ZoneNber=".$ZoneNber.", NewZigbee=" . $NewZigbee . "END<br>".CRLF);
	  // Assign Zone if new
	  if ($ZoneID=="0") {
	    $sql = "UPDATE `ha_thermostat_zones` SET `Name` = '" . $Zone_Name . "' WHERE `ZoneNber` = " . $ZoneNber . ";";
		//echo("sql=".$sql ."<br>");
		mysqli_query($DB,$sql);
		$ZoneID = $ZoneNber;
	  } // END IF
	  
	  //echo("Moyenne Before=".$Moyenne.",After=".$ZoneID."<br>".CRLF);
      if ($Moyenne==1) { $Moyenne=0; } else { $Moyenne=1; }
	  $Moyenne = $ZoneID + ($Moyenne*90);
	  //echo("Moyenne ,After=".$Moyenne."<br>".CRLF);
	  
	  // Return ID value to save into DB
	  return($NewZigbee);
	  //return("id".$id_sonde."ZID".$ZoneID."ZN".$Zone_Name."ZC".$ZoneColor."NZW".$NewZigbee);

  }
  
  function HTMLoption() {
	  GLOBAL $DB, $msg, $Lang;
	  $sql = "SELECT * FROM `".TABLE_ELEMENTS."` WHERE ((`element_type`='0x41' OR `element_type`='0x42') AND `Manufacturer`='Zigbee')  ORDER BY `id` ASC;";
	  $query = mysqli_query($DB,$sql);
	  while ($row = mysqli_fetch_array($query, MYSQLI_BOTH)) {
		$sql2 = "SELECT COUNT(*) AS County FROM `".TABLE_CHAUFFAGE_SONDE."` WHERE `id_sonde`='Zigbee_".$row['card_id']."'";
		$query2 = mysqli_query($DB,$sql2);
		$row2 = mysqli_fetch_array($query2, MYSQLI_BOTH);
		if ($row2['County']==0) {
	      echo("<option value='Zigbee_".$row['card_id']."' >" . $row['Manufacturer'] . " " . $row['element_name'] . "</option>" . CRLF);
		}
	  } // End While


	  
	  
	  
	  
	  
  } // END FUNCTION HTMLselect
  
  
  function HTMLconfig() {
	  
	  GLOBAL $msg, $Lang, $DB;
	  // Determine Local IP Range
	  $ip = shell_exec("ifconfig eth0| grep 'inet ' | cut -d: -f2");
	  $ip=substr($ip,0, strpos($ip, " netmask")-1);
	  $ip=substr($ip,(strrpos($ip," ")-strlen($ip)+1));
	  $ip=substr($ip,0,strrpos($ip,".")+1);
	  

	  // Existing Zone ? 
	  
	  echo('<tr id="NewZigbee0" style="display: none;"><td width="20%">&nbsp;</td>' . CRLF);
	  echo('<td width="30%" align="right">' . $msg["TEMPS"]["Zone"][$Lang] . '&nbsp;&nbsp;&nbsp;<br><br></td>' . CRLF);
	  echo('<td width="50%"><select id="ZoneIDZigbee" name="ZoneIDZigbee" onchange="NewZoneZigbee()"><option value="">Select</option>');
	  
      echo('<option value="1">' . $msg["thermostat"]["MainZone"][$Lang] .'</option>' . CRLF);
	  // Already defined Zones?
	  $sql = "SELECT * FROM `ha_thermostat_zones`;";
	  $query = mysqli_query($DB,$sql);
	  while ($row = mysqli_fetch_array($query, MYSQLI_BOTH)) {
		if ($row["Name"]!="") {
	      echo('<option value="' . $row["ZoneNber"] . '" style="background-color: #' . $row["Color_Code"] . ';color: white;">' . $row["Name"] .'</option>' . CRLF);
		} // END IF
	  } // END WHILE
	  echo('<option value="0" onselect="javascript:NewZoneZigbee();">' . $msg["TEMPS"]["NewZone"][$Lang] . '</option></select></td></tr>' . CRLF);

	  
	  // New Zone Name
	  echo('<tr id="NewZigbee1" style="display: none;"><td width="20%">&nbsp;</td>' . CRLF); # table-row
	  echo('<td width="30%" align="right">' . $msg["TEMPS"]["ZoneName"][$Lang] . '&nbsp;&nbsp;&nbsp;<br><br></td>' . CRLF);
	  echo('<td width="50%"><input id="Zone_NameZigbee" name="Zone_NameZigbee" type="text"/></td></tr>' . CRLF);
	  
	  // New Zone Color
	  echo('<tr id="NewZigbee2" style="display: none;"><td width="20%">&nbsp;</td>' . CRLF); # table-row
	  echo('<td width="30%" align="right">' . $msg["TEMPS"]["ZoneColor"][$Lang] . '&nbsp;&nbsp;&nbsp;<br><br></td>' . CRLF);
	  echo('<td width="50%"><select id="ZoneNberZigbee" name="ZoneNberZigbee"><option value="">Select</option>' . CRLF);
	  $sql = "SELECT * FROM `ha_thermostat_zones`;";
	  $query = mysqli_query($DB,$sql);
	  while ($row = mysqli_fetch_array($query, MYSQLI_BOTH)) {
		if ($row["Name"]=="") {
	      echo('<option value="'.$row["ZoneNber"].'" style="background-color: #'.$row["Color_Code"].';color: white;">' . $msg["TEMPS"][$row["Color_Name"]][$Lang] .'</option>' . CRLF);
		} // END IF
	  } // END WHILE

	  echo('</select></td></tr>' . CRLF);
	  echo('<input type="hidden" id="NewZigbee" name="NewZigbee" value="3487">' . CRLF);
	  echo('<input type="hidden" id="ZoneID" name="ZoneID" value="3487">' . CRLF);
	  echo('<input type="hidden" id="ZoneNber" name="ZoneNber" value="3487">' . CRLF);
	  echo('<input type="hidden" id="Zone_Name" name="Zone_Name" value="3487">' . CRLF);
	  
  } // END FUNCTION HTMLconfig
  
  function javaChange() {
	  //echo('alert("sel="+selected.substring(0, 7));');
	  /*
	  echo('if (selected.substring(0, 7)==="Zigbee_") { document.getElementById("Mean01").style.display = "none";document.getElementById("Mean02").style.display = "none";'.
	       'document.getElementById("NewZigbee0").style.display = "table-row";'.
		   '} else { document.getElementById("Mean01").style.display = "inline";document.getElementById("Mean02").style.display = "inline";' .
		   'document.getElementById("NewZigbee0").style.display = "none";document.getElementById("NewZigbee1").style.display = "none";}' . CRLF);
	  */
	  echo('if (selected.substring(0, 7)==="Zigbee_") { '.
	       'document.getElementById("NewZigbee0").style.display = "table-row";'.
		   '} else { ' .
		   'document.getElementById("NewZigbee0").style.display = "none";document.getElementById("NewZigbee1").style.display = "none";}' . CRLF);
      echo('}' . CRLF);
	 
/*
	  echo('function cloneSel(id,targid){' . CRLF);
	  echo('  var count=document.getElementsByTagName(doc(id).tagName).length;' . CRLF);
	  echo('  cl=doc(id).cloneNode(true);' . CRLF);
	  echo('  cl.id=cl.title=id+'_'+count;' . CRLF);
	  echo('  doc(targid).appendChild(cl);' . CRLF);
	  echo('  // just for testing' . CRLF);
	  echo('  cl.onchange=function(){alert(this.id);}' . CRLF);
	  echo('}' . CRLF);
*/
	  
      echo('function NewZoneZigbee() {' . CRLF);
	  echo('  var value = document.getElementById("ZoneIDZigbee").value;');
      echo('  if (value==0) { document.getElementById("NewZigbee1").style.display = "table-row";document.getElementById("NewZigbee2").style.display = "table-row";  document.getElementById("Zone_NameZigbee").focus();}' . CRLF);
      echo('  else { document.getElementById("NewZigbee1").style.display = "none";document.getElementById("NewZigbee2").style.display = "none"; }' . CRLF);
      
	  
  } // END FUNCTION javaChange

  function HTMLcheck() {
	global $msg, $Lang;
    //echo('  cloneSel();' . CRLF);
	  
	echo('  var e = document.getElementById("id_sonde");' . CRLF);
	echo('  var t = e.options[e.selectedIndex].text;' . CRLF);
	//echo('alert("id sonde="+t.substring(0,6));' . CRLF);
	echo(' if (t.substring(0,6)=="Zigbee") {  ' . CRLF);	
	//echo('alert("Zigbee Check");' . CRLF);
    echo('  var e = document.getElementById("ZoneIDZigbee");' . CRLF);
    echo('  var ZoneID = e.options[e.selectedIndex].value;' . CRLF);
	echo('  document.getElementById("ZoneID").value = ZoneID;' . CRLF);
	//echo('alert("1");' . CRLF);
	
	echo('  if ((document.getElementById("NewZigbee0").style.display=="table-row") && (ZoneID=="")) { alert("' . $msg["TEMPS"]["NOZoneError"][$Lang] .'");return; }' . CRLF);
    echo('  var e = document.getElementById("ZoneNberZigbee");' . CRLF);
    echo('  var ZoneNber = e.options[e.selectedIndex].value;' . CRLF);
	echo('  document.getElementById("ZoneNber").value = ZoneNber;' . CRLF);
	//echo('alert("2");' . CRLF);
	
	echo('  var ZoneName = document.getElementById("Zone_NameZigbee").value;' . CRLF);
	echo('  document.getElementById("Zone_Name").value = ZoneName;' . CRLF);

	//echo('alert("ZoneID=" + ZoneID + ", ZoneNber=" + ZoneNber + ", Zone_Name=" + ZoneName);' . CRLF);
	
	echo('  if ((document.getElementById("NewZigbee1").style.display=="table-row") && ((document.getElementById("Zone_NameZigbee").value=="") || (ZoneNber==""))) { alert("' . $msg["TEMPS"]["ZoneSelectError"][$Lang] .'");return; }' . CRLF);
    echo('  var e = document.getElementById("id_sonde");' . CRLF);
	echo('  var t = e.options[e.selectedIndex].text;' . CRLF);
	//echo(' alert(t);');
	
    echo('  e.options[e.selectedIndex].value="NewZigbee";' . CRLF);
	echo('  document.getElementById("NewZigbee").value="Zigbee_"+t ;' . CRLF);
	echo('}' . CRLF);
  } // END FUNCTION HTMLcheck

} // END Class

?>
