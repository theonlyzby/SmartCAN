<script type="text/javascript">

function receptionmodule(cle,valeur) {

  //console.log("JS/cle="+cle+", value="+valeur);
  
  if ( cle == 'boiler' ) {
    boiler(valeur);
  }
  if ( cle == 'THERMOSTAT' ) {
    thermostat(valeur);
  }
};

function boiler(data) {
   if (data=="ON") {
	$('#divchaudiere').text("BOILER");
	//console.log("JS/Boiler="+data);
   }
}

function thermostat(data) {
	const json = JSON.parse(data);
	// Zones
	zoneNum = $('#divheatzone').text();
	//document.getElementById("divheatzone"];
	console.log("Zone = "+ zoneNum);
    for(var zone in json.Zones) {
	  if (parseInt(zoneNum) == parseInt(json.Zones[zone].zoneNber)) {
        console.log(json.Zones[zone].zoneNber+"/"+json.Zones[zone].zoneName);
	    $('#divprochainechauffe').text(json.Zones[zone].zoneHeatNext);
		$('#divconsigne').text(json.Zones[zone].ZoneSetPoint);
		$('#temperature').text(json.Zones[zone].zoneMeanTemp);
		$('#divchaudiere').text(json.Zones[zone].ZoneHeater);
		$('#divperiodechauffe').text(json.Zones[zone].zoneHeating);
		$('#divfinchauffe').text(json.Zones[zone].zoneHeatEND);
		$('#divHumidity').text(json.Zones[zone].zoneHumidity);
        $('#divCompensation').text(json.Zones[zone].zoneCompensation);
        $('#divValvePosition').text(json.Zones[zone].valvePosition);
	  }
    }
	// Sensors
	for(var sensor in json.sensors) {
		console.log(json.sensors[sensor].sensorName+"/"+json.sensors[sensor].sensorTemp);
		$('#' + json.sensors[sensor].sensorName).text(json.sensors[sensor].sensorTemp);
	}
}

</script>
