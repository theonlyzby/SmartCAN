<?php
// Meraki Webhook on API and via MQTT bridge (noreRED)
/*

Presence Sensor:
{"Payload":"{\"battery\":100,\"battery_low\":false,\"linkquality\":63,\"occupancy\":false,\"tamper\":false,\"voltage\":3200}","topic":"zigbee2mqtt/Presence01"}
{"Payload":"{\"battery\":100,\"battery_low\":false,\"linkquality\":63,\"occupancy\":true,\"tamper\":false,\"voltage\":3200}","topic":"zigbee2mqtt/Presence01"}

Contact Sensor:
{"Payload":"{\"battery\":84.5,\"battery_low\":false,\"contact\":false,\"linkquality\":69,\"tamper\":false,\"voltage\":2900}","topic":"zigbee2mqtt/Door01"}
{"Payload":"{\"battery\":84.5,\"battery_low\":false,\"contact\":true,\"linkquality\":54,\"tamper\":false,\"voltage\":2900}","topic":"zigbee2mqtt/Door01"}

Thermostat:
{"Payload":"{\"battery\":93,\"boost_heating\":\"OFF\",\"child_lock\":\"UNLOCK\",\"current_heating_setpoint\":\"22.5\",\"friday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"heating\":\"OFF\",\"linkquality\":96,\"local_temperature\":\"22.0\",\"local_temperature_calibration\":\"-1.0\",\"max_temperature\":\"25.0\",\"min_temperature\":\"5.0\",\"monday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"position\":\"0.0\",\"preset\":\"manual\",\"saturday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"sunday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"system_mode\":\"auto\",\"thursday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"tuesday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"wednesday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"window\":\"CLOSED\",\"window_detection\":\"OFF\"}",
"topic":"zigbee2mqtt/Vanne-Salon"}

*/
// PHP Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
$debug = "N"; // "Y"
$deblevel = 7; // 1 shows variables, 7 also the POST

// Load Dependencies
include_once('../www/conf/config.php');

// Connect to DB
$DB=mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
mysqli_select_db($DB,mysqli_DB);  



// Get POST value, if empty sends authenticator changellenge back
if (strtoupper($debug) == "Y") { $request = file_get_contents('php://input'); }

// If debig ON: Open the file to get existing content
if (strtoupper($debug) == "Y") {
  $file = $_SERVER['DOCUMENT_ROOT'].'/smartcan/webhook/test-Zigbee.txt';
  $current = file_get_contents($file);
  if ($deblevel==7) { $current .= "\n" . $request . "\n"; }
}
 
// JSON decode
$input = json_decode($request, true);


// Request OK?
if (array_key_exists('topic', $input)) {
  if (strtoupper($debug) == "Y") { $current .= "1 ok\n"; }
  if (substr($input["topic"],0,11)=="zigbee2mqtt") {
	if (strtoupper($debug) == "Y") { $current .= "Well coming from:" . substr($input["topic"],12) . "\n"; }
    // Start payload, analysis
	$payload = json_decode($input["Payload"], true);
	//$current .= "Sensor Type:" . $payload["current_heating_setpoint"] . "\n";echo("ok");
  } // EDN IF (substr($input["topic"],0,11)=="zigbee2mqtt")
} // END IF (array_key_exists('topic', $input))

// Thermostat?
if (array_key_exists('current_heating_setpoint', $payload)) {
  // Zygbee Thermostat Element Type exists?
  $sql = "SELECT COUNT(*) AS County FROM `ha_element_types` WHERE `Manufacturer`='Zigbee' AND `role`='Thermostat';";
  $query = mysqli_query($DB,$sql);
  $row = mysqli_fetch_array($query, MYSQLI_BOTH);
  if ($row['County']=="0") {
    $sql = "INSERT INTO `ha_element_types` (`id`, `Manufacturer`, `subsystem_type`, `role`, `Type`, `Description`) VALUES (NULL, 'Zigbee', '0xA0', 'Thermostat', '0x41', 'Zigbee Wall Radiator Thermostat');";
    $query = mysqli_query($DB,$sql);
  }
  if (strtoupper($debug) == "Y") { $current .= "Thermostat ;-) \n"; }
  $sql = "SELECT * FROM `ha_element` WHERE `Manufacturer`='Zigbee' AND `card_id`='".substr($input["topic"],12)."' AND `element_type`='0x41';";
  $query = mysqli_query($DB,$sql);
  $row = mysqli_fetch_array($query, MYSQLI_BOTH);
  if (!$row['id']) {
    $sql2 = "INSERT INTO `".TABLE_ELEMENTS."` (`id`, `Manufacturer`, `card_id`, `element_type`, `element_reference`, `element_name`) VALUES (NULL, 'Zigbee', '".substr($input["topic"],12)."', '0x41', '', '" . substr($input["topic"],12) ."');";
  } else {
    $sql2 = "UPDATE `ha_element` SET `Manufacturer` = 'Zigbee', `card_id` = '".substr($input["topic"],12)."', `element_type` = '0x41', `element_name` = '" . substr($input["topic"],12) ."' WHERE `id` = ".$row['id'].";";
  }
  $query = mysqli_query($DB,$sql2);
  
  // Get temperature
  $temp = $payload["local_temperature"] + $payload["local_temperature_calibration"];
  $battery = $payload["battery"];
  $sql3 = "UPDATE `chauffage_sonde` AS CS, `chauffage_temp` AS CT SET CT.`valeur` = '".$temp."', CT.`battery` ='".$battery."',  CT.`update` = now() WHERE CS.`id_sonde` = 'Zigbee_".substr($input["topic"],12)."' AND CT.`id` = CS.`id`;";
  $query = mysqli_query($DB,$sql3);
  if (strtoupper($debug) == "Y") { $current .= $query . ", SQL=".$sql3 ."\n"; }
} // END IF



// Write the contents back to the file
if (strtoupper($debug) == "Y") {
  file_put_contents($file, $current);
}

?>