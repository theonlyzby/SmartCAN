<?php
// Zigbee Webhook on API and via MQTT bridge (noreRED)
/*

Presence Sensor:
{"Payload":"{\"battery\":100,\"battery_low\":false,\"linkquality\":63,\"occupancy\":false,\"tamper\":false,\"voltage\":3200}","topic":"zigbee2mqtt/Presence01"}
{"Payload":"{\"battery\":100,\"battery_low\":false,\"linkquality\":63,\"occupancy\":true,\"tamper\":false,\"voltage\":3200}","topic":"zigbee2mqtt/Presence01"}

Contact Sensor:
{"Payload":"{\"battery\":84.5,\"battery_low\":false,\"contact\":false,\"linkquality\":69,\"tamper\":false,\"voltage\":2900}","topic":"zigbee2mqtt/Door01"}
{"Payload":"{\"battery\":84.5,\"battery_low\":false,\"contact\":true,\"linkquality\":54,\"tamper\":false,\"voltage\":2900}","topic":"zigbee2mqtt/Door01"}

Thermostatic Valve:
{"Payload":"{\"battery\":93,\"boost_heating\":\"OFF\",\"child_lock\":\"UNLOCK\",\"current_heating_setpoint\":\"22.5\",\"friday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"heating\":\"OFF\",\"linkquality\":96,\"local_temperature\":\"22.0\",\"local_temperature_calibration\":\"-1.0\",\"max_temperature\":\"25.0\",\"min_temperature\":\"5.0\",\"monday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"position\":\"0.0\",\"preset\":\"manual\",\"saturday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"sunday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"system_mode\":\"auto\",\"thursday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"tuesday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"wednesday_schedule\":\" 6h:0m 19.5°C,  12h:0m 15°C,  18h:0m 19.5°C,  22h:0m 15°C \",\"window\":\"CLOSED\",\"window_detection\":\"OFF\"}",
"topic":"zigbee2mqtt/Vanne-Salon"}
OR
{"Payload":"{\"auto_lock\":\"MANUAL\",\"away_mode\":\"OFF\",\"away_preset_days\":2,\"away_preset_temperature\":15,\"battery_low\":false,
\"boost_time\":300,\"child_lock\":\"UNLOCK\",\"comfort_temperature\":20,\"current_heating_setpoint\":11.5,\"eco_temperature\":15,
\"force\":\"normal\",\"holidays\":[{\"hour\":6,\"minute\":0,\"temperature\":20},{\"hour\":8,\"minute\":0,\"temperature\":15},{\"hour\":11,
\"minute\":30,\"temperature\":15},{\"hour\":12,\"minute\":30,\"temperature\":15},{\"hour\":17,\"minute\":30,\"temperature\":20},{\"hour\":22,
\"minute\":0,\"temperature\":15}],\"holidays_schedule\":\"06:00/20°C 08:00/15°C 11:30/15°C 12:30/15°C 17:30/20°C 22:00/15°C\",\"linkquality\":98,
\"local_temperature\":18.5,\"local_temperature_calibration\":-1,\"max_temperature\":35,\"min_temperature\":5,\"position\":0,\"preset\":\"manual\",
\"running_state\":\"idle\",\"system_mode\":\"auto\",\"week\":\"5+2\",\"window_detection\":\"OFF\",\"window_detection_params\":{\"minutes\":0,
\"temperature\":118},\"window_open\":false,\"workdays\":[{\"hour\":6,\"minute\":0,\"temperature\":20},{\"hour\":8,\"minute\":0,\"temperature\":15},
{\"hour\":11,\"minute\":30,\"temperature\":15},{\"hour\":12,\"minute\":30,\"temperature\":15},{\"hour\":17,\"minute\":30,\"temperature\":20},
{\"hour\":22,\"minute\":0,\"temperature\":15}],\"workdays_schedule\":\"06:00/20°C 08:00/15°C 11:30/15°C 12:30/15°C 17:30/20°C 22:00/15°C\"}",
"topic":"zigbee2mqtt/Vanne-Nicolas"}

Thermometer (and Humidity)
{"Payload":"{\"battery\":0.5,\"humidity\":63.67,\"linkquality\":80,\"temperature\":23.58,\"voltage\":2500}","topic":"zigbee2mqtt/Temperature01"}

Sirene
{"Payload":"{\"alarm\":false,\"battery_low\":false,\"humidity\":45,\"humidity_alarm\":false,\"humidity_max\":80,\"humidity_min\":30,
\"linkquality\":247,\"melody\":5,\"power_type\":\"usb\",\"temperature\":18.7,\"temperature_alarm\":false,\"temperature_max\":35,
\"temperature_min\":10,\"volume\":\"high\"}","topic":"zigbee2mqtt/Sirene01"}

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
$request = file_get_contents('php://input');

// If debug ON: Open the file to get existing content
if (strtoupper($debug) == "Y") {
  $file = $_SERVER['DOCUMENT_ROOT'].'/smartcan/webhook/test-Zigbee.txt';
  $current = file_get_contents($file);
  $current .= date("Y-m-d H:i:s") . "\n";
  if ($deblevel==7) { $current .= "\n" . $request . "\n"; }
}
 
// JSON decode
$input = json_decode($request, true);


// Request OK?
if (array_key_exists('topic', $input)) {
  if (strtoupper($debug) == "Y") { $current .= "Topic ok\n"; }
  if (substr($input["topic"],0,11)=="zigbee2mqtt") {
	if (strtoupper($debug) == "Y") { $current .= "Coming from:" . substr($input["topic"],12) . "\n"; }
    // Start payload, analysis
	$payload = json_decode($input["Payload"], true);
	//$current .= "Sensor Data:" . $payload["current_heating_setpoint"] . "\n";echo("ok");
  } // EDN IF (substr($input["topic"],0,11)=="zigbee2mqtt")
} // END IF (array_key_exists('topic', $input))

// Renamed Object? (NOT 0x...)
if (substr($input["topic"],12,2)!="0x") {
  // Extract Common Variables (Battery)
  $battery = $payload["battery"];
  if ($battery=="") { if ($payload["battery_low"]=="true") { $battery=10; } else { $battery=90; } }
  if (array_key_exists('power_type', $payload)) {
    if ($payload["power_type"]=="usb") {$battery = 101;}
  }
  // Thermometer? (& Humidity)
  if ((array_key_exists('temperature', $payload)) && (array_key_exists('humidity', $payload))) {
    // Column tempCompensation exists?
    $result = mysqli_query($DB, "SHOW COLUMNS FROM `chauffage_temp` LIKE `ValvePosition`;");
    if (!$result) {
      $sql = "ALTER TABLE `chauffage_temp` ADD `valvePosition` FLOAT NOT NULL DEFAULT '101' AFTER `valeur`, " .
                "ADD `tempCompensation` FLOAT NOT NULL DEFAULT '101' AFTER `valvePosition`;";
      mysqli_query($DB,$sql);
    }
    // Zigbee Thermometer Element Type exists?
    $sql = "SELECT COUNT(*) AS County FROM `ha_element_types` WHERE `Manufacturer`='Zigbee' AND `role`='Thermometer';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
    if ($row['County']=="0") {
	  $sql = "ALTER TABLE `ha_element_types` CHANGE `role` `role` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Describes the subsystem role (IN, OUT, FUNCTION)';";
      $query = mysqli_query($DB,$sql);
	  $sql = "INSERT INTO `ha_element_types` (`id`, `Manufacturer`, `subsystem_type`, `role`, `Type`, `Description`) VALUES (NULL, 'Zigbee', '0xA1', 'Thermometer', '0x42', 'Zigbee Wall Thermomter and Humidity Sensor');";
      $query = mysqli_query($DB,$sql);
    } // END IF
    if (strtoupper($debug) == "Y") { $current .= "Thermometer ;-) \n"; }
    $sql = "SELECT * FROM `ha_element` WHERE `Manufacturer`='Zigbee' AND `card_id`='".substr($input["topic"],12)."' AND `element_type`='0x42';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
    if (!$row['id']) {
      $sql2 = "INSERT INTO `".TABLE_ELEMENTS."` (`id`, `Manufacturer`, `card_id`, `element_type`, `element_reference`, `element_name`) VALUES (NULL, 'Zigbee', '".substr($input["topic"],12)."', '0x42', '', '" . substr($input["topic"],12) ."');";
      $query = mysqli_query($DB,$sql2);
    } // END IF

    // Humidity Column exists?
    $sql = "SELECT count(*) AS County FROM information_schema.COLUMNS WHERE COLUMN_NAME = 'humidity' and TABLE_NAME = 'chauffage_temp';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
    if ($row['County']==0) {
      $sql = "ALTER TABLE `chauffage_temp` ADD `humidity` FLOAT NOT NULL DEFAULT '101' AFTER `valeur`;";
	  $query = mysqli_query($DB,$sql);
    } // END IF
    // Get temperature and Humidity
    $temp = round($payload["temperature"],1);
    $humidity = round($payload["humidity"],1);
    $sql3 = "UPDATE `chauffage_sonde` AS CS, `chauffage_temp` AS CT SET CT.`valeur` = ".$temp.", CT.`humidity` = ".$humidity.", CT.`battery` =".$battery.",  CT.`update` = now() WHERE CS.`id_sonde` = 'Zigbee_".substr($input["topic"],12)."' AND CT.`id` = CS.`id`;";
    $query = mysqli_query($DB,$sql3);
    if (strtoupper($debug) == "Y") { $current .= $query . ", SQL=".$sql3 ."\n"; }
  
  } // END IF


  // Thermostat?
  if (array_key_exists('current_heating_setpoint', $payload)) {
    // Zigbee Thermostat Element Type exists?
    $sql = "SELECT COUNT(*) AS County FROM `ha_element_types` WHERE `Manufacturer`='Zigbee' AND `role`='Thermostat';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
    if ($row['County']=="0") {
      $sql = "INSERT INTO `ha_element_types` (`id`, `Manufacturer`, `subsystem_type`, `role`, `Type`, `Description`) VALUES (NULL, 'Zigbee', '0xA0', 'Thermostat', '0x41', 'Zigbee Wall Radiator Thermostat');";
      $query = mysqli_query($DB,$sql);
    } // END IF
    if (strtoupper($debug) == "Y") { $current .= "Thermostat ;-) \n"; }
    $sql = "SELECT * FROM `ha_element` WHERE `Manufacturer`='Zigbee' AND `card_id`='".substr($input["topic"],12)."' AND `element_type`='0x41';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
    if (!$row['id']) {
      $sql2 = "INSERT INTO `".TABLE_ELEMENTS."` (`id`, `Manufacturer`, `card_id`, `element_type`, `element_reference`, `element_name`) VALUES (NULL, 'Zigbee', '".substr($input["topic"],12)."', '0x41', '', '" . substr($input["topic"],12) ."');";
      $query = mysqli_query($DB,$sql2);
    } // END IF
    // Get Valve Position
    $valvePos =  "";
    if (array_key_exists('position', $payload)) {
      $valvePos = "CT.`valvePosition`='".$payload["position"]."'";
    }
    // Get temperature
    $temp = round($payload["local_temperature"],1); // + $payload["local_temperature_calibration"];
    $sql3 = "UPDATE `chauffage_sonde` AS CS, `chauffage_temp` AS CT SET CT.`valeur` = '".$temp."', CT.`battery` =".$battery.",  CT.`update` = now(), " .
            $valvePos . " WHERE CS.`id_sonde` = 'Zigbee_".substr($input["topic"],12)."' AND CT.`id` = CS.`id`;";
    $query = mysqli_query($DB,$sql3);
    if (strtoupper($debug) == "Y") { $current .= $query . ", SQL=".$sql3 ."\n"; }
  } // END IF
  
} else { if (strtoupper($debug) == "Y") { $current .= "Default Name (0x...), no Action!!!\n"; } } // END IF Not 0x


 // Write the contents back to the file
if (strtoupper($debug) == "Y") {
  file_put_contents($file, $current);
}

?>
