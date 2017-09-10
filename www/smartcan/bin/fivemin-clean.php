<?php
// Tasks run evry 5 minutes

// Includes
include_once '/var/www/smartcan/www/conf/config.php';

// Connect DB
$DB = mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
mysqli_set_charset($DB,'utf8'); 
mysqli_select_db($DB,mysqli_DB);

// Remove UnDeleted One Time Heating Requests
// date("d-m-Y H:i:s", time()+3600) '".date("Y-m-d H:i:s", time()+3600)."'
$sql = "DELETE FROM `ha_cameras_temp` WHERE `Create_Date` < (NOW() - INTERVAL 5 MINUTE);";
$query = mysqli_query($DB,$sql);
//echo("Camera Reverse Proxy Configs deleted! " . $query.chr(10));

?>