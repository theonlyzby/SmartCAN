<?php

  /*
    SCRIPT D'ENVOI DE MESSAGE AU PORTAIL WEB (SOIT L'HEURE => SI AUCUN ARGUMENT, SOIT LE MESSAGE PASSE EN ARGUMENT)
  */

  /* DEPENDANCES */
  $base_URI = substr($_SERVER['SCRIPT_FILENAME'],0,strpos(substr($_SERVER['SCRIPT_FILENAME'],1),"/")+1);
  include_once($base_URI.'/www/smartcan/www/conf/config.php');

  /* SI AUCUN MESSAGE, ENVOI DE L'HEURE ACTUELLE */
  if ( !isset($argv[1]) ) {

    /* CONNEXION */
    $DB=mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
    mysqli_select_db($DB,mysqli_DB);

    $retour = mysqli_query($DB,"SELECT `prenom`,DATE_FORMAT(date, '%d/%m') FROM `meteo_anniversaire` WHERE DATE_FORMAT(date, '%m%D') = DATE_FORMAT(NOW( ), '%m%D') LIMIT 1");
    $row = mysqli_fetch_array($retour, MYSQLI_BOTH);
    if ( $row[0] != "" ) {
      $argv[1] = date('H:i') . " (Anniversaire de : " . $row[0] . ")";
    }
    else {
	  // Sensor NOK?
	  $retour = mysqli_query($DB,"SELECT COUNT(*) FROM `" . TABLE_CHAUFFAGE_TEMP . "` WHERE (`moyenne` = '1');");
	  $row2 = mysqli_fetch_array($retour, MYSQLI_BOTH);
	  $Tot_Sensors = $row2[0];
	  $retour = mysqli_query($DB,"SELECT COUNT(*) FROM `" . TABLE_CHAUFFAGE_TEMP . "` WHERE (`moyenne` = '1' AND `valeur`<>0 AND `update`>=DATE_SUB(now(), INTERVAL 2 MINUTE));");
	  $row2 = mysqli_fetch_array($retour, MYSQLI_BOTH);
	  $NOK_Sensors  = $Tot_Sensors - $row2[0];
      if ($NOK_Sensors!=0) {
	    $argv[1] = date('H:i') . " ! " . $NOK_Sensors . " Sensor(s) NOK!" ;
	  } else {
	    $argv[1] = date('H:i');
	  } // END IF
    }
  }

  /* PROCESSUS D'ENVOI */
  $ch = curl_init(URIPUSH);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message;" . $argv[1]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $ret = curl_exec($ch);
  curl_close($ch);


?>
