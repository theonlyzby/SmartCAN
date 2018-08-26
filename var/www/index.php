<?php

// https://www.codeproject.com/Tips/1076176/Login-logout-and-Session-Id-Cookies-in-PHP-for-Beg


// PHP Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');



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

// Remote client OR Local with Auth Request?
if (((filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) || ((!filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) && ($auth_local_user=='Y'))) && (!isset($_SESSION["login"]))) {
  //gets session id from cookies, or initiate
  session_start();
  // Login OUT
  if (isset($_GET['logout'])) {
    setcookie(session_name(), "", time() - 3600); //send browser command remove sid from cookie
    session_destroy(); //remove sid-login from server storage
    session_write_close();
    echo("<a href='".$_SERVER['PHP_SELF']."'>Login</a>");
	exit();
  } // END IF
  //if sid exists and login for sid exists
  if (session_id() == '' || !isset($_SESSION['username'])) { 
    // Login Submitted?
    if (isset($_POST['login']) && isset($_POST['password'])) {
	  echo("Form Submitted<br>");
      $sql = "SELECT COUNT(*) AS PassOK FROM `users` WHERE (Alias='" . $_POST['username'] . "' AND Password=PASSWORD('". $_POST['password'] ."'));";
      $query = mysqli_query($DB,$sql);
      $row = mysqli_fetch_array($query, MYSQLI_BOTH);
      $PassOK = $row['PassOK'];	  
	  //echo($sql ."<br>"); exit();
      if ($PassOK==1) {
		echo("OK to display!<br>");
		$sql = "SELECT * FROM `users` WHERE Alias='" . $_POST['login'] . "';";
        $query = mysqli_query($DB,$sql);
        $row = mysqli_fetch_array($query, MYSQLI_BOTH);
	    $Access_Level = $row['Access_Level'];
	    $User_ID = $row['ID'];
	    $Lang = $row['Lang'];
        $_SESSION['username'] = $_POST['username']; //write login to server storage
        // OK to display
      } else {
        echo "<script>alert('Wrong login or password');</script>";
        echo "<noscript>Wrong login or password</noscript>";
		header("Location: " . $_SERVER['PHP_SELF']);
		exit();
      }
    } else {
      	echo("<!DOCTYPE html>" . CRLF . "<html>" . CRLF . "<head>" . CRLF . "<style>" . CRLF . "body {" . CRLF . "    background-color:#000000" . CRLF . "}" . CRLF . "</style>" . CRLF . "</head>" . CRLF . "<body>" . CRLF);
	    echo("<body>" . CRLF . "<p align='center'><font color='white' size='16pt'><a style='color:#ffffff; font-style: bold; size: 16pt;' href='" . $_SERVER['PHP_SELF'] . "'>Login Required</a></p><br>"  );
	    echo("<form action = '' method = 'post' autocomplete='on'><table width=100%><tr><td width=30%>Username</td><td><input type='text' name='username' style='font-size:25pt;' autofocus/></td></tr>" . CRLF);
	    echo("<tr><td width=30%>Password</td><td><input type='password' name='password' style='font-size:25pt;'/></td></tr>" . CRLF);
	    echo("<tr><td width=30%>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='hidden' id='login' name='login' value='1'>" . CRLF);
	    echo("<input type='submit' style='font-size:25pt;' value='Login'></td></tr></table></form>" . CRLF);
	    echo("</font></form></body>" . CRLF);
    exit();
	}
  }
  // Session Opened
  if (session_id()!='') {
	//echo("Session");
	$sql = "SELECT * FROM `users` WHERE Alias='" . $_SESSION['username'] . "';";
    $query = mysqli_query($DB,$sql);
    $row = mysqli_fetch_array($query, MYSQLI_BOTH);
	$Access_Level = $row['Access_Level'];
	$User_ID = $row['ID'];
	$Lang = $row['Lang'];
	$div_sess="YES";
	//echo($sql ."Access_Level=".$Access_Level.", User_ID=".$User_ID.", Lang=".$Lang."<br>"); exit();
  } // END IF Session Opened
  
} // END IF Requires Auth

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
  
  // Backward Compatibility
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
  $_XTemplate->assign('USEDTHEME', $_GET['theme']);
  
  
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
  
  
  } else {
	// NOK TO DISPLAY => LOGIN
	echo("<!DOCTYPE html>" . CRLF . "<html>" . CRLF . "<head>" . CRLF . "<style>" . CRLF . "body {" . CRLF . "    background-color:#000000" . CRLF . "}" . CRLF . "</style>" . CRLF . "</head>" . CRLF . "<body>" . CRLF);
	echo("<body>" . CRLF . "<p align='center'><font color='white' size='16pt'><a style='color:#ffffff; font-style: bold; size: 16pt;' href='" . $_SERVER['PHP_SELF'] . "'>NOK, Login Required</a></p><br>"  );
	echo("<form action = '' method = 'post' autocomplete='on'><table width=100%><tr><td width=30%>Username</td><td><input type='text' name='username' style='font-size:25pt;' autofocus/></td></tr>" . CRLF);
	echo("<tr><td width=30%>Password</td><td><input type='password' name='password' style='font-size:25pt;'/></td></tr>" . CRLF);
	echo("<tr><td width=30%>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='hidden' id='login' name='login' value='1'>" . CRLF);
	echo("<input type='submit' style='font-size:25pt;' value='Login'></td></tr></table></form>" . CRLF);
	echo("</font></form></body>" . CRLF);		 
  
  
  } // END IF

  //echo("END");

?>
