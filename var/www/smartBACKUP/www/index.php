<?php
// PHP Error Reporting
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
if ( ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) )
{
 echo("Local IP!");
 exit();
}
*/

  /* CONFIGURATIONS ET DEPENDANCES */
  $Lang="";
  include_once './conf/config.php';
  include_once PATH . 'lib/xajax/xajax_core/xajax.inc.php';
  include_once PATH . 'lib/xtemplate/xtemplate.class.php';
  // Client's IP address ... Private = local, ifnot ... Inetrnet => Protect! ;-)
  $client_ip = $_SERVER["REMOTE_ADDR"];

// Connect DB
$DB = mysqli_connect(mysqli_HOST, mysqli_LOGIN, mysqli_PWD);
mysqli_set_charset($DB,'utf8'); 
mysqli_select_db($DB,mysqli_DB);
  
// Security
$Access_Level = 0;     // => Visitor
$PassOK=0;            // Password NOK
// Do we need to authenticate local users?
$sql = "SELECT * FROM `" . TABLE_VARIABLES . "` WHERE `variable`='local_user_auth';";
$query = mysqli_query($DB,$sql);
$row = mysqli_fetch_array($query, MYSQLI_BOTH);
$auth_local_user = $row['value'];
$User_ID=0;

//echo("Params:<br>IP=" . $client_ip . "<br>1=".filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)."<br>2=".(!filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE))."<br>3=".$auth_local_user."<br>4=".((filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) || ((!filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) && ($auth_local_user=='Y'))));
//exit();
$div_sess="";

// Remote client OR Local with Auth Request
if ((filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) || ((!filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) && ($auth_local_user=='Y'))) {
  // Authentication
  session_start();
  $div_sess="YES";
  if(isset($_GET['logout'])) {
    unset($_SESSION["login"]);
    session_destroy();
    echo "<font color='black' size='16pt'>Acc&egrave;s Interdit ... ";
    echo "[<a style='color:#000000; font-style: bold; size: 16pt;' href='" . $_SERVER['PHP_SELF'] . "'>Login</a>]</font>";
    exit();
  } // END IF
  
  $user  = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "";
  $pass  = isset($_SERVER['PHP_AUTH_PW'])   ? $_SERVER['PHP_AUTH_PW']   : "";
  $login = isset($_SESSION["login"])        ? $_SESSION["login"]        : "";
  if (($user=="") || ($pass=="") || ($login=="")) {
    header('WWW-Authenticate: basic realm="resindevice.io"');
    header("HTTP/1.0 401 Unauthorized");
	$_SESSION["login"] = true; // " user=".$user.", pass=".$pass.", login=".$login .
	exit("<font color='black' size='16pt'>Acc&egrave;s Interdit ..." .  " user=".$user.", pass=".$pass.", login=".$login .
			"[<a style='color:#000000; font-style: bold; size: 16pt;' href='" . $_SERVER['PHP_SELF'] . "'>Login</a>]</font>");
    //session_destroy();
    //exit();
  } else {
    $SubmitUser = $_SERVER['PHP_AUTH_USER'];
    $SubmitPass = $_SERVER['PHP_AUTH_PW'];
    $sql = "SELECT COUNT(*) AS PassOK FROM `users` WHERE (Alias='" . $SubmitUser . "' AND Password=PASSWORD('". $SubmitPass ."'));";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
    $PassOK = $row['PassOK'];
   
    if($PassOK==1) {
      //echo "You have logged in ... ";
	  $sql = "SELECT * FROM `users` WHERE Alias='" . $SubmitUser . "';";
      $query = mysqli_query($DB,$sql);
      $row = mysqli_fetch_array($query, MYSQLI_BOTH);
	  $Access_Level = $row['Access_Level'];
	  $User_ID = $row['ID'];
	  $Lang = $row['Lang'];
      //echo "[<a href='" . $_SERVER['PHP_SELF'] . "?logout'>Logout</a>]";
    } else {
      unset($_SESSION["login"]);
	  session_destroy();
      header("Location: " . $_SERVER['PHP_SELF']);
    } // END IF
  } // END IF

} // END IF

// Acess Level OK?
// Remote client OR Local with Auth Request
if (((filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) && ($Access_Level>=1)) || ((!filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) && ($auth_local_user=='Y') && ($Access_Level>=1))
		 || ((!filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) && ($auth_local_user=='N'))) {

  // Start page ... Secu OK
  /* CONFIGURATIONS ET DEPENDANCES */
  include_once PATH . 'lib/xajax/xajax_core/xajax.inc.php';
  include_once PATH . 'lib/xtemplate/xtemplate.class.php';

  // User Language?
  if ($Lang=="") {
    $sql = "SELECT * FROM `users` WHERE Access_Level='1';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
	$Lang = $row['Lang'];
	if ($Lang=="") { $Lang="fr"; }
  } // END IF

  /* XAJAX */
  $xajax = new xajax();
  $xajax->configure("javascript URI", URI . "/lib/xajax/");
  //$xajax->setFlag('debug', DEBUG_AJAX);
  $xajax->configure('debug', DEBUG_AJAX);

  /* AUCUN THEME > TABLET BY DEFAULT */
  if ( !isset($_GET['theme']) ) { $_GET['theme'] = 'tablet'; }

  /* OUVRE LE XTEMPLATE */
  //include_once('./lang/www.index.php');
  $_XTemplate = new XTemplate(PATH . 'html/' . $_GET['theme'] . '/'.$Lang.'/structure.html');


  /* SI AUCUNE PAGE N'EST DEMANDEE OU QUE L'ARGUMENT CONTIENT UNE ERREUR */
  if ( !isset($_GET['page']) || !preg_match('`[[:alnum:]]{4,20}$`', $_GET['page']) )
  {
    //$_GET['page'] = 'thermostat';
	$sql          = "SELECT * FROM `ha_settings` WHERE `variable`='default_page'";
	$query        = mysqli_query($DB,$sql);
    $row          = mysqli_fetch_array($query, MYSQLI_BOTH);
    $_GET['page'] = $row['value'];
  }
  
  // Backward COmpatibility
  if ($_GET['page']=="lumieres") { $_GET['page'] = "lights"; }
  if ($_GET['page']=="meteo") { $_GET['page'] = "weather"; }
  if ($_GET['page']=="ambiances") { $_GET['page'] = "vibes"; }
  if ($_GET['page']=="divers") { $_GET['page'] = "misc"; }

  /* INCLUSION DU FICHIER HTML (PAGE DEMANDEE) */
  $HTMLext=".html";
  if ( is_file('./html/' . $_GET['theme'] . '/' . $Lang . '/' . $_GET['page'] . $HTMLext) )
  {
    if ($_GET['page']=="thermotest") { $HTMLext=".php";}
    $_XTemplate->assign_file('content', './html/' . $_GET['theme'] . '/' . $Lang . '/' . $_GET['page'] . $HTMLext);
  }
  else {
    $_XTemplate->assign_file('content', './html/' . $_GET['theme'] . '/' . $Lang . '/' . '/notinstalled.html');
  }


  /* DEFINIR PAGE DEMANDEE (POUR APPEL CSS & JS SPECIFIQUE) */
  $_XTemplate->assign('PAGE', $_GET['page']);
  $_XTemplate->assign('THEME', $_GET['theme']);
  
  
  //echo("CSS_URL=./lang/css/" . $_GET['theme'] . "/" . $Lang ."/" . $_GET['page'] . ".css ".$Lang);
  // CSS URL
  if ( is_file('./lang/css/' . $Lang .'/' . $_GET['page'] . '.css') ) {
    //echo("11");
    $CSS_URL = "./lang/css/" . $Lang ."/" . $_GET['page'] . ".css"; 
	$_XTemplate->assign('CSU', $CSS_URL);
  } else {
	if ( is_file('./css/' . $_GET['theme'] . '/' . $_GET['page'] . '.css') ) {
	  $CSS_URL = "./css/" . $_GET['theme'] . "/" . $_GET['page'] . ".css";
	  $_XTemplate->assign('CSU', $CSS_URL);
	} // END IF
  } // END IF
  

  if ( is_file('./js/' . $_GET['page'] . '.js') )
  {
    $_XTemplate->assign_file('JAVASCRIPT', './js/' . $_GET['page'] . '.js');
  }

  if ( is_file('./lang/css/' . $_GET['theme'] . '/' . $Lang .'/' . $_GET['page'] . '.css') ) {
    $_XTemplate->parse('main.css_specific_file');
  } else {
    if ( is_file('./css/' . $_GET['theme'] . '/' . $_GET['page'] . '.css') ) {
      $_XTemplate->parse('main.css_specific_file');
	} // END IF
  } // END IF


  /* INCLUSION DU FICHIER PHP (PAGE DEMANDEE) */
  if ( file_exists('./php/' . $_GET['page'] . '.php') ) {
    include_once('./lang/www.' . $_GET['page'] . '.php');
	include_once('./php/' . $_GET['page'] . '.php');
  }

  //$_XTemplate->assign('TITRE', $titre);
  $_XTemplate->assign('TITRE', $msg[$_GET['page']]["Title"][$Lang]);
  $_XTemplate->assign('DIV_SESS', $div_sess);

  /* PROCESSEUR ET AFFICHAGE XAJAX */
  $xajax->processRequest();
  $_XTemplate->assign('XAJAX', $xajax->getJavascript());


  /* PARSE LE BLOC 'main' */
  $_XTemplate->parse('main');


  /* AFFICHE LE RESULTAT */
  $_XTemplate->Out('main');

  // Thermostat Config Jidden frame
  if ($_GET['page']=="thermostat") { 
    include_once('./lang/www.thermostat.php');
	include_once('./html/' . $_GET['theme'] . '/thermostatconfig.php');
  } // END IF
  
  
  } // END IF

?>
