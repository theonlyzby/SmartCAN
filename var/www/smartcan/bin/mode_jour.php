<?php

  /*
    PASSAGE DE LA STRUCTURE EN MODE JOUR (POUR EVITER CERTAINS ALLUMAGE DE LAMPE AVEC CAPTEUR)
  */


  /* DEPENDANCES */
  $base_URI = "/var";
  if (isset($_SERVER['RESIN_HOST_OS_VERSION'])) { $base_URI = "/data"; }
  include_once($base_URI.'/www/smartcan/www/conf/config.php');

  /* ACTIVATION DU MODE JOUR */
  if ( $argv['1'] == 'on' ) {
    $DB=mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
    mysqli_select_db($DB,mysqli_DB);
    mysqli_query($DB,"UPDATE `" . TABLE_ENTREE . "` SET `actif` = '0' WHERE `id` = '10'");
    mysqli_close($DB);
  }

  /* DESACTIVATION DU MODE JOUR */
  if ( $argv['1'] == 'off' ) {
    $DB=mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
    mysqli_select_db($DB,mysqli_DB);
    mysqli_query($DB,"UPDATE `" . TABLE_ENTREE . "` SET `actif` = '1' WHERE `id` = '10'");
    mysqli_close($DB);
  }

?>
