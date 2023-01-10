<?php
// Meraki Webhook on API and via MQTT bridge (noreRED)
/*

API v3:
{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":

{"networkId":"L_674976994152158449","observations":

[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:01:49Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-60},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"3c:e1:a1:ad:ea:18","latestRecord":{"time":"2021-11-23T14:01:17Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-76},"ipv6":null,"manufacturer":"Universal Global..."},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"28:16:ad:db:b7:12","latestRecord":{"time":"2021-11-23T14:01:42Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Intel"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:01:42Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-90},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:01:42Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-86},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"f0:79:60:86:03:b7","latestRecord":{"time":"2021-11-23T14:01:43Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-88},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"a4:38:cc:8c:fe:c9","latestRecord":{"time":"2021-11-23T14:01:32Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-86},"ipv6":null,"manufacturer":"Nintendo"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"c8:14:51:98:20:e7","latestRecord":{"time":"2021-11-23T14:01:38Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-89},"ipv6":null,"manufacturer":"Huawei Technologies"}]}}{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":{"networkId":"L_674976994152158449","observations":[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"a4:38:cc:8c:fe:c9","latestRecord":{"time":"2021-11-23T14:02:33Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-85},"ipv6":null,"manufacturer":"Nintendo"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:02:58Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-59},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"f0:79:60:86:03:b7","latestRecord":{"time":"2021-11-23T14:02:27Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-70},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:02:27Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-85},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"f8:b4:6a:06:45:6d","latestRecord":{"time":"2021-11-23T14:02:27Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-91},"ipv6":null,"manufacturer":"Hewlett-Packard"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:02:27Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-90},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"a8:88:08:b8:11:71","latestRecord":{"time":"2021-11-23T14:02:27Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"b8:27:eb:98:82:a9","latestRecord":{"time":"2021-11-23T14:02:27Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-75},"ipv6":null,"manufacturer":"Raspberry Pi Foundation"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:04:20:2e:12:81","latestRecord":{"time":"2021-11-23T14:02:45Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-85},"ipv6":null,"manufacturer":"Slim Devices"}]}}{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":{"networkId":"L_674976994152158449","observations":[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:03:53Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-86},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:04:20:2e:12:81","latestRecord":{"time":"2021-11-23T14:03:54Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-83},"ipv6":null,"manufacturer":"Slim Devices"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"a4:50:46:f2:82:e6","latestRecord":{"time":"2021-11-23T14:03:49Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-73},"ipv6":null,"manufacturer":"Xiaomi Communications"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"b4:ab:2c:08:8a:ee","latestRecord":{"time":"2021-11-23T14:03:10Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-88},"ipv6":null,"manufacturer":"MtM Technology"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:03:53Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-90},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:03:58Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-68},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"f8:b4:6a:06:45:6d","latestRecord":{"time":"2021-11-23T14:03:53Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Hewlett-Packard"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"e0:b9:e5:d3:d4:49","latestRecord":{"time":"2021-11-23T14:03:36Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-90},"ipv6":null,"manufacturer":"Technicolor Delivery..."}]}}{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":{"networkId":"L_674976994152158449","observations":[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:04:41Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-58},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:04:39Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-86},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"f0:79:60:86:03:b7","latestRecord":{"time":"2021-11-23T14:04:38Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-89},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:04:39Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-89},"ipv6":null,"manufacturer":"Lite-On"}]}}{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":{"networkId":"L_674976994152158449","observations":[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:04:20:29:70:37","latestRecord":{"time":"2021-11-23T14:05:25Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Slim Devices"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:05:50Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-59},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:05:25Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-85},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:05:25Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-89},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"28:16:ad:db:b7:12","latestRecord":{"time":"2021-11-23T14:05:25Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-93},"ipv6":null,"manufacturer":"Intel"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"dc:71:96:dd:47:94","latestRecord":{"time":"2021-11-23T14:05:10Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-89},"ipv6":null,"manufacturer":"Intel"}]}}{"version":"3.0","secret":"fs!24Get","type":"Bluetooth","data":{"networkId":"L_674976994152158449","startTime":"2021-11-23T14:06:56Z","reportingAps":[{"serial":"Q3AJ-T77E-JWZD","mac":"a8:46:9d:27:56:63","name":"","lng":-122.098531723022,"tags":[],"floorPlan":null,"lat":37.4180951010362}],"endTime":"2021-11-23T14:07:56Z","observations":[{"locations":[],"name":"","bleBeacons":[{"rawData":"1bff7500420401806024fce590974d26fce590974c014a0a00000000","bleType":"unknown"}],"clientMac":"24:fc:e5:90:97:4d","latestRecord":{"time":"2021-11-23T14:07:40Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-93}}]}}{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":{"networkId":"L_674976994152158449","observations":[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"f0:79:60:86:03:b7","latestRecord":{"time":"2021-11-23T14:06:12Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-72},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"b8:27:eb:98:82:a9","latestRecord":{"time":"2021-11-23T14:06:57Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-76},"ipv6":null,"manufacturer":"Raspberry Pi Foundation"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"70:1a:04:d5:c8:07","latestRecord":{"time":"2021-11-23T14:06:10Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-93},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"50:2f:9b:e8:33:93","latestRecord":{"time":"2021-11-23T14:06:57Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Intel"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"74:42:8b:ad:ca:bf","latestRecord":{"time":"2021-11-23T14:06:51Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-77},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:04:20:2e:12:81","latestRecord":{"time":"2021-11-23T14:06:59Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-83},"ipv6":null,"manufacturer":"Slim Devices"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:06:57Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-86},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:06:59Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-58},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:06:57Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"c8:14:51:98:20:e7","latestRecord":{"time":"2021-11-23T14:06:26Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-90},"ipv6":null,"manufacturer":"Huawei Technologies"}]}}{"version":"3.0","secret":"fs!24Get","type":"Bluetooth","data":{"networkId":"L_674976994152158449","startTime":"2021-11-23T14:07:56Z","reportingAps":[{"serial":"Q3AJ-T77E-JWZD","mac":"a8:46:9d:27:56:63","name":"","lng":-122.098531723022,"tags":[],"floorPlan":null,"lat":37.4180951010362}],"endTime":"2021-11-23T14:08:56Z","observations":[{"locations":[],"name":"","bleBeacons":[{"rawData":"1bff7500420401806024fce590974d26fce590974c014a0a00000000","bleType":"unknown"}],"clientMac":"24:fc:e5:90:97:4d","latestRecord":{"time":"2021-11-23T14:08:22Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92}}]}}{"version":"3.0","secret":"fs!24Get","type":"WiFi","data":{"networkId":"L_674976994152158449","observations":[{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"d0:df:9a:d4:18:0a","latestRecord":{"time":"2021-11-23T14:07:38Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-90},"ipv6":null,"manufacturer":"Lite-On"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"34:7e:5c:3f:b9:db","latestRecord":{"time":"2021-11-23T14:07:53Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-59},"ipv6":null,"manufacturer":"Sonos"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"74:42:8b:ad:ca:bf","latestRecord":{"time":"2021-11-23T14:07:38Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-91},"ipv6":null,"manufacturer":"Apple"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"a4:38:cc:8c:fe:c9","latestRecord":{"time":"2021-11-23T14:07:22Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-85},"ipv6":null,"manufacturer":"Nintendo"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"e0:b9:e5:d3:d4:49","latestRecord":{"time":"2021-11-23T14:07:56Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-92},"ipv6":null,"manufacturer":"Technicolor Delivery..."},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"b4:ab:2c:08:8a:ee","latestRecord":{"time":"2021-11-23T14:07:00Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-83},"ipv6":null,"manufacturer":"MtM Technology"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:04:20:2e:12:81","latestRecord":{"time":"2021-11-23T14:07:55Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-83},"ipv6":null,"manufacturer":"Slim Devices"},{"locations":[],"ipv4":null,"ssid":null,"os":null,"clientMac":"00:0e:58:7f:79:4b","latestRecord":{"time":"2021-11-23T14:07:38Z","nearestApMac":"a8:46:9d:27:56:63","nearestApRssi":-86},"ipv6":null,"manufacturer":"Sonos"}]}}



MQTT:
{"mrMac":"A8:46:9D:27:56:63","clientType":"visible","clientMac":"E6:59:DD:E4:B3:F1","timestamp":"2021-11-23 15:29:32.116","networkId":"N_674976994152169992","rssi":"-74","radio":"wifi"}

*/
// PHP Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
$debug = "N"; // "Y"
$deblevel = 1; // 1 shows variables, 7 also the POST

// Load Dependencies
include_once('../www/conf/config.php');

// Connect to DB
$DB=mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
mysqli_select_db($DB,mysqli_DB);  

function log_scan_data($MAC, $rssi, $MAC_Status) {
	global $DB, $debug, $current;
	// Logs Latest Scan data
	$dt = date("Ymd H:i:s");
    $sql = "SELECT COUNT(*) AS county, scan_time FROM `clinicsensor` WHERE `MAC_Addr`='".$MAC."';";
	$return = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($return, MYSQLI_BOTH);
    if ($row['county']==0) {
      $sql = "INSERT INTO `clinicsensor` (`id`, `MAC_Addr`, `Status`, `rssi`, `rssi_back`, `scan_time`, `prev_scan`) VALUES (NULL, '".$MAC."', '".$MAC_Status."', '".$rssi."', '".$rssi."', '".$dt."', '".$dt."');";
    } else {
	  $prev_scan = $row["scan_time"];
      $sql = "UPDATE `clinicsensor` SET `rssi`='".$rssi."',`rssi_back`='".$rssi."', `scan_time`='".$dt."', `prev_scan`='".$prev_scan."', `Status`='".$MAC_Status."' WHERE `MAC_Addr`='".$MAC."';";
    }
	$current .= "URIpush = " . URIPUSH . "\n";
    $return = mysqli_query($DB,$sql);
	if (strtoupper($debug) == "Y") { $current .= "sql=".$sql."\n result=".$return . "\n"; }
    // PUSH on Web Interface
    $ch = curl_init(URIPUSH);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "SIGNAL;" . $rssi);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
	$current .= "Ret = " . $ret . "\n";
    curl_close($ch);

    if ($MAC_Status=="OUT") {
	  // PUSH in Message Area
      $ch = curl_init(URIPUSH);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "message;" . "ALERT: Patient OUT of Clinic!");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $ret = curl_exec($ch);
      curl_close($ch);
      $result=exec('sudo php /data/www/smartclinic/bin/clinic-message.php ZoneOUT');
	  $current .= "Result = " . $result . "\n";
	} // END IF ($MAC_Status=="OUT")
} // END FUNCTION


// Get POST value, if empty sends authenticator changellenge back
$request = file_get_contents('php://input');

// If debig ON: Open the file to get existing content
if (strtoupper($debug) == "Y") {
  $file = $_SERVER['DOCUMENT_ROOT'].'/smartcan/webhook/test-Meraki.txt';
  $current = file_get_contents($file);
  if ($deblevel==7) { $current .= "\n" . $request . "\n"; }
}
 
// JSON decode
$input = json_decode($request, true);

// Determine Target MAC and behaviour from DB
$sql = "SELECT * FROM `clinicsensor_target` WHERE 1;";
$return = mysqli_query($DB,$sql);
$row = mysqli_fetch_array($return, MYSQLI_BOTH);
$target_MAC = $row["MAC_Addr"];
$Pivot_rssi = abs($row["Pivot_rssi"]);
$Comparator = $row["Comparator"];
	
if ($request=="") {
  // Initial answer:
  echo("f36ece5a64ab1a47c3cfaf9670cf878e5d88dbe2");
  if (strtoupper($debug) == "Y") { $current .= "Initial Handshake received.\n"; }
} else {
  // Determine if API or MQTT Call
  $API_Mode = "MQTT"; $version = "";
  if (array_key_exists('version', $input)) { $API_Mode = "API"; $version = $input["version"];}

  // Handle Both Call types (API & MQTT)
  if ($API_Mode == "API") {
    // API Call
	if (strtoupper($debug) == "Y") { $current .= "API Mode json received\n"; }
    $out = "";
	$network = $input["type"];
    $in = $input["data"]["observations"];
    foreach ($in as $key=>$value) {
	  $ip = ""; $unc = "";
	  $MAC     = $value["clientMac"];
	  $manufacturer = $value["manufacturer"];
	  $ssid    = $value["ssid"]; $connected = "YES"; if ($ssid == null) { $connected = "no"; }
	  if ($version == "3.0") {
	    // API v3
		$rssi =  abs($value["latestRecord"]["nearestApRssi"]);
	  } else {
	    // API v2
		$rssi = abs($value["rssi"]);
	  }
	  if ((strtoupper($debug) == "Y") && ($MAC==$target_MAC)) { $current .= "Network : " . $network . ",ssid = " . $ssid . ",connected:" . $connected . ", MAC = " . $MAC . ", Manufacturer = " . $manufacturer . ", rssi=" . $rssi ."\n"; }
	  // Matching Target MAC? Any Action required?
	  if ($MAC==$target_MAC) { // Cardio sensor: f5:1c:14:85:9b:03 // Tablet: 84:98:66:19:1d:ed
	    // Update DB or Insert into DB
	    $MAC_Status = "IN";
	    if ((($Comparator=="<") && ($rssi<=$Pivot_rssi)) || (($Comparator==">") && ($rssi>$Pivot_rssi))) {
		  $MAC_Status = "OUT";
		  if (strtoupper($debug) == "Y") { $current .= " ########## ALARM: Patient OUT of clinic!!! ###################\n"; }
	    } // END IF
		log_scan_data($MAC, $rssi, $MAC_Status);
	  } // END IF ($MAC==$target_MAC)
    } // END FOREACH
  } else {
    // MQTT Call
	if (strtoupper($debug) == "Y") { $current .= "MQTT Mode json received\n"; }
	$MAC = $input["clientMac"];
	$network = $input["radio"];
	$rssi = abs($input["rssi"]);
	if ((strtoupper($debug) == "Y") && ($MAC==$target_MAC)) { $current .= "\nMQTT: Network = " . $network . ", MAC = " . $MAC . ", rssi=" . $rssi ."\n\n"; }
	// Matching Target MAC? Any Action required?
	if ($MAC==$target_MAC) { // Cardio sensor: f5:1c:14:85:9b:03 // Tablet: 84:98:66:19:1d:ed
	  // Update DB or Insert into DB
	  $MAC_Status = "IN";
	  if ((($Comparator=="<") && ($rssi<=$Pivot_rssi)) || (($Comparator==">") && ($rssi>$Pivot_rssi))) {
		$MAC_Status = "OUT";
		if (strtoupper($debug) == "Y") { $current .= " ########## ALARM: Patient OUT of clinic!!! ###################\n"; }
	  } // END IF
     log_scan_data($MAC, $rssi, $MAC_Status);
	} // END IF ($MAC==$target_MAC)
  } // END IF ($API_Mode == "API")
} // END IF ($request=="")

// Write the contents back to the file
if (strtoupper($debug) == "Y") {
  file_put_contents($file, $current);
}
?>