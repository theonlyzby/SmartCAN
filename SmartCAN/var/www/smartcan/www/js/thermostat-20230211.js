<script type="text/javascript">

function receptionmodule(cle,valeur) {

  console.log("JS/cle="+cle+", value="+valeur);
  
  if ( cle == 'sonde' ) {
    sonde(valeur);
  }
  if ( cle == 'temperaturevoulue' ) {
    temperaturevoulue(valeur);
  }
  if ( cle == 'heater' ) {
    heater(valeur);
  }
  if ( cle == 'boiler' ) {
    boiler(valeur);
  }
  if ( cle == 'PERIODECHAUFFE' ) {
    periodechauffe(valeur);
  }
  if ( cle == 'FINCHAUFFE' ) {
    finchauffe(valeur);
  }
  if ( cle == 'PROCHAINECHAUFFE' ) {
    prochainechauffe(valeur);
  }  
  if ( cle == 'CHAUDIERE' ) {
    chaudiere(valeur);
  }
  if ( cle == 'CONSIGNE' ) {
    consigne(valeur);
  }
  if ( cle == 'THERMOSTATINFOS' ) {
    thermostatinfos(valeur);
  }
};

function sonde(data) {
  tab = data.split(',');
  idsonde = tab[0];
  valeur = tab[1];
  //if (idsonde=="moyennemaison") { idsonde = "moyenne"; }
  $('#' + idsonde).text(valeur);
  //xajax_moyenne();
  //console.log("JS/"+idsonde+"="+valeur+", OUT="+out);
}


function temperaturevoulue(data) {
  $('#temperature').text(data);
}

function heater(data) {
   $('#divchaudiere').text(data);
   //console.log("JS/Heater="+data);
}

function boiler(data) {
   if (data=="ON") {
	$('#divchaudiere').text("BOILER");
	//console.log("JS/Boiler="+data);
   }
}

function periodechauffe(data) {
	$('#divperiodechauffe').text(data);
}

function finchauffe(data) {
	$('#divfinchauffe').text(data);
}

function prochainechauffe(data) {
	console.log("JS/Prochaine Chauffe="+data);
	$('#divprochainechauffe').text(data);
}

function chaudiere(data) {
	console.log("JS/Chaudiere="+data);
	$('#divchaudiere').text(data);
}

function consigne(data) {
	console.log("JS/Consigne="+data);
	$('#divconsigne').text(data);
}

function thermostatinfos(data) {
        tab = data.split(',');
        humidity      = tab[0];
        compensation  = tab[1];
        valveposition = tab[2];
        console.log("JS/Humidity="+humidity+", compensation="+compensation+", ValvePosition="+valveposition);
        $('#divHumidity').text(humidity);
        $('#divCompensation').text(compensation);
        $('#divValvePosition').text(valveposition);
}


</script>
