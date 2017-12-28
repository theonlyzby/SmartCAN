<?php

  /*
    SCRIPT D'ENVOI DE MESSAGE AU PORTAIL WEB (SOIT L'HEURE => SI AUCUN ARGUMENT, SOIT LE MESSAGE PASSE EN ARGUMENT)
  */

  /* DEPENDANCES */
  include_once('/data/www/smartcan/www/conf/config.php');

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
      $argv[1] = date('H:i');
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
