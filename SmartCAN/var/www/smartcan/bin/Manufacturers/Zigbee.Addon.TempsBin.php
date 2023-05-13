<?php

class Zigbee_class {
  
  function get_Temp($sensor) {
	global $DB;
	
	$debug = "Y"; // "Y";
	$Now    = date("H:i:00");
	$DayBit = date("N");
        $Today  = str_pad(str_pad("1",$DayBit,"_",STR_PAD_LEFT),8,"_");
	//$sql = "SELECT * FROM `" . TABLE_CHAUFFAGE_SONDE . "` WHERE (`id_sonde`='".$sensor."');";
        $sql = "SELECT CS.`id`, CS.`id_sonde`, CT.`valeur`, CS.`moyenne` FROM `chauffage_sonde` AS CS, `chauffage_temp` AS CT WHERE (CS.`id_sonde`='".$sensor."') AND CS.`id`=CT.`id`;";

	if (strtoupper($debug) == "Y") { echo("\n\n ===================Zigbee ===================\n"); echo("Zigbee sql = " . $sql . "\n"); }
    $return = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($return, MYSQLI_BOTH);
	
	$node = substr($row["id_sonde"],7);
	$moyenne = $row["moyenne"];
        $measuredTemp = $row["valeur"];
	if (strtoupper($debug) == "Y") { echo("Node=".$node.", moyenne=".$moyenne.", measured Value=".$measuredTemp.CRLF); }
	
	if ($moyenne<=90) { $zone = $row["moyenne"]; } else { $zone = $row["moyenne"]-90; }
	if ($zone=="1") { $zone_select = "1111111"; } else { $zone_select = str_pad("",(intval($zone)-1),"_")."1".str_pad("",(7-intval($zone)),"_"); }
	$id   = $row["id"];
	
	// Any Heating Active period?
	$sql2 = "SELECT COUNT(*) AS County FROM `" . TABLE_HEATING_TIMSESLOTS . "` WHERE (((`days` LIKE '" . $Today . "') OR (`days` LIKE '_______1')) ".
                 "AND (`function`='HEATER') AND (`active`='Y') AND ('" . $Now . "' BETWEEN `start` AND `stop`) AND (`zones` LIKE '".$zone_select."'));";
	if (strtoupper($debug) == "Y") { echo("sql2 =".$sql2.CRLF); }
	$retour2 = mysqli_query($DB,$sql2);
	$row2 = mysqli_fetch_array($retour2, MYSQLI_BOTH);
	// Away?
    $sql3="SELECT * FROM `" . TABLE_CHAUFFAGE_CLEF . "` WHERE `clef`='absence';";
    $return3 = mysqli_query($DB,$sql3);
    $row3 = mysqli_fetch_array($return3, MYSQLI_BOTH);
    $Away = $row3['valeur'];
	if (strtoupper($debug) == "Y") { echo("Timeslots?".$row2["County"].",Away?".$Away . CRLF); }
	//if (($row2["County"]!=0) && ($Away=='0')) { 
        //  $clef = "temperature";
          $delta = 0;
//          if (strtoupper($debug) == "Y") { echo("\n Consigne = ".$clef.CRLF); }
          if ($moyenne>=90) { // Valve not use for Average => Delta compensation
            $sql3 = "SELECT AVG(valeur) AS `avgZone` FROM `chauffage_temp` WHERE `moyenne`='".$zone."' AND `valeur`<>0;";
            $retour3 = mysqli_query($DB,$sql3);
            $row3 = mysqli_fetch_array($retour3, MYSQLI_BOTH);
            $avgZone = $row3["avgZone"];
            if (strtoupper($debug) == "Y") { echo("avgZone=".$avgZone.", "); }
            $sql3 = "SELECT `valeur` AS setpoint FROM `chauffage_clef` WHERE `clef`='temperature' AND `ZoneNber`='".$zone."';";
            $retour3 = mysqli_query($DB,$sql3);
            $row3 = mysqli_fetch_array($retour3, MYSQLI_BOTH);
            $setPoint = $row3["setpoint"];
            if ($measuredTemp<>0) { 
              $delta = round($measuredTemp-$avgZone,1);
              $sql3 = "UPDATE `chauffage_temp` SET `tempCompensation` = ".$delta." WHERE `id`='".$id."';";
              mysqli_query($DB,$sql3);
            }
            if (strtoupper($debug) == "Y") { echo("ValveMeasuredTemp=".$measuredTemp." - AvgZone =".$avgZone." = DELTA=".$delta.CRLF."sql=".$sql3.CRLF); }
          } // END IF ($moyenne<=90)

        if (($row2["County"]!=0) && ($Away=='0')) { 
          $clef = "temperature";
        } else { $clef = "tempminimum"; } // $delta = 0; }
        if (strtoupper($debug) == "Y") { echo("\n Consigne = ".$clef.CRLF); }

	$sql3 = "SELECT * FROM `chauffage_clef` WHERE `ZoneNber`=".$zone." AND `clef`='".$clef."';";
	if (strtoupper($debug) == "Y") { echo("sql3 =".$sql3.CRLF); }
	$retour3 = mysqli_query($DB,$sql3);
	$row3 = mysqli_fetch_array($retour3, MYSQLI_BOTH);
	$temp = $row3["valeur"];

    // curl -X POST http://127.0.0.1:1880/toMQTT -H 'Content-Type: application/json' -d '{"target":"Vanne-Salon","cmd":"{\"current_heating_setpoint\":\"20.5\"}"}'

    // Valve (element type=0x41)? => Send CMD
	$sql = "SELECT * FROM `ha_element` WHERE `Manufacturer`='Zigbee' AND `card_id`='".$node."';";
	if (strtoupper($debug) == "Y") { echo("sql =".$sql.CRLF); }
	$retour = mysqli_query($DB,$sql);
	$row = mysqli_fetch_array($retour, MYSQLI_BOTH);
	$element_type = $row["element_type"];
	if (strtoupper($debug) == "Y") { echo("element_type =".$element_type.", temp =".$temp.CRLF); }
	
	if ($element_type=="0x41") {
	  $ch = curl_init( "http://127.0.0.1:1880/toMQTT" );
          # Setup request to send json via POST.
          $temp = $temp + $delta;
          $payload = json_encode( array( "target"=> $node , "cmd"=>"{\"current_heating_setpoint\":\"".round($temp,1)."\"}"  ) );
          curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
          curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
          # Return response instead of printing.
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
          # Send request.
          $result = curl_exec($ch);
          curl_close($ch);
          # Print response.
         if (strtoupper($debug) == "Y") { echo "=> Zigbee CMD SENT for ".$node.", temp=".round($temp,1)." => $result \n"; }
       } else {
         if (strtoupper($debug) == "Y") { echo "=> Zigbee NO CMD SENT, because ".$node." is NOT a Valve ;-) \n"; }
       }
	 
      // Return Temperature "N/A" if not used
      return("N/A");

  } // END Function
  

} // END Class

?>
