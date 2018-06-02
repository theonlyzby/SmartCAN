<?php
// PHP Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');


// Load Dependencies
include_once('/var/www/smartcan/www/conf/config.php');


// Get var values:
$request = print_r( $_REQUEST, true );
	
if ($request!="") {
  $file = '/var/www/test-out.txt';
  // Open the file to get existing content
  $current = file_get_contents($file);
  // Append a new person to the file
  $current .= $request."\n";
  // Write the contents back to the file
  file_put_contents($file, $current);
} // END IF


# Config Controller:
# http://172.27.10.76/controllers?index=1&protocol=8&usedns=0&controllerip=172.27.10.115&controllerport=80&controllerpublish=/smartcan/webhook/ESPeasy-WebHook.php%3Fname%3D%25sysname%25%26task%3D%25tskname%25%26valuename%3D%25valname%25%26value%3D%25value%25&controllerenabled=on
# http://172.27.10.76/devices?index=1&page=1&edit=1&TDNUM=5&TDN=DHT22&TDE=on&taskdevicepin1=0&plugin_005_dhttype=22&TDSD1=on&TDT=60&TDVN1=Temperature&TDF1=%25value%25&TDVD1=2&TDVN2=Humidity&TDF2=%25value%25&TDVD2=2
# DHT22 Connection: http://lazyzero.de/_media/elektronik/esp8266/dht_deepsleep/esp8266_dht_deepsleep_steckplatine.png
# Send Text: https://diyprojects.io/jeedom-create-oled-ssd1306-display-esp-easy-http-request/#.WiPRDkqnGUk
# Factory Reset: http://172.27.10.76/?cmd=reset
# Develop own Plugin: https://diyprojects.io/esp-easy-develop-plugins/#.WiPQaUqnGUk

# http://cricfree.cc/watch/site/update/btespn

?>