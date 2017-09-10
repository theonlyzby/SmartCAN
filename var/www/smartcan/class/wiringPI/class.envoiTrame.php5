<?php

class wiringPI_envoiTrame {

  /* TOGGLE Raspberry GPIO Output */
  function GPIO_Set($var1,$var2,$intensite,$progression) {
	$Value = "0"; $intensite="0x0"; if ($intensite>0) { $Value = "1"; $intensite="0x32"; }
    // Set Value ON or OFF
	$retstatus = exec('sudo gpio -1 write $var2 $Value', $Output, $retval);
	// Return Status if OK
	if ($retstatus=="") {
	  $trigger = new trigger();
	  $trigger->OUTtrigger("wiringPI", "RaspBerryPI", $var2, $intensite);
	} // END IF

  } // END FUNCTION
  
  
  /* TOGGLE Raspberry GPIO Output */
  function GPIO_Toggle($var1,$var2,$intensite,$progression) {
    // Determine GPIO Output Status ON or OFF
	$retstatus = exec("sudo gpio -1 read $var2", $Output, $retval);
	$NewValue="0"; $intensite="0x0"; if ($retstatus=="0") { $NewValue = "1"; $intensite="0x32";}
	// Set new Value (Opposite)
	$retstatus = exec("sudo gpio -1 write $var2 $NewValue", $Output, $retval);
	// Return Status if OK
	if ($retstatus=="") {
	  $trigger = new trigger();
	  $trigger->OUTtrigger("wiringPI", "RaspBerryPI", $var2, $intensite);
	} // END IF
	
	
  } // END FUNCTION
  
} // END CLASS

?>
