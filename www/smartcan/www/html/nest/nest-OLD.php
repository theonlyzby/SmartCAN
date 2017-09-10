<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width; initial-scale=0.8; maximum-scale=0.8; user-scalable=no;"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<title>Nest Thermostat for DomoCAN - Original Source: http://homeautomategorilla.blogspot.be</title>

<style>

body, div {
  -moz-user-select: none;
  user-select: none;  
  -webkit-user-select: none;
  -webkit-tap-highlight-color: rgba(0,0,0,0);    
}

a img {		border: none;
  	  }
a {		color: #FFF;
  }

 /* Fontes utilisées */  
@font-face {
    font-family: "N_E_B";
    src: url(N_E_B.TTF) format("truetype");
    }
	
@font-face {
    font-family: "MANDATOR";
    src: url(MANDATOR.TTF) format("truetype");
    }
	
.desc {
 position:relative;
 left:22;
 top:22;
 }
 /* Le grand cercle noir glossy */
.full-circle {
 position:relative;
 left:22;
 top:22;
 border: 3px solid #333;
 height: 350px;
 width: 350px;
 
 -moz-border-radius:350px;
 -webkit-border-radius: 350px;
 
  background: #cbcefb;
 /* Permet de ne pas pouvoir être sélectionné */
 -webkit-touch-callout: none;
 -webkit-user-select: none;
 -khtml-user-select: none;
 -moz-user-select: none;
 -ms-user-select: none;
 user-select: none;
 /* Permet de mettre un dégradé sur le cercle en fonction de tous les navigateurs */
 background: #eaeaea; /* Old browsers */
 background: -webkit-radial-gradient(top left, ellipse cover, #eaeaea 0%,#eaeaea 11%,#0e0e0e 61%); /* Chrome10+,Safari5.1+ */
 background: -moz-radial-gradient(top left, ellipse cover,  #eaeaea 0%, #eaeaea 11%, #0e0e0e 61%); /* FF3.6+ */
 background: -webkit-gradient(radial, top left, 0px, top left, 100%, color-stop(0%,#eaeaea), color-stop(11%,#eaeaea), color-stop(61%,#0e0e0e)); /* Chrome,Safari4+ */
 background: -o-radial-gradient(top left, ellipse cover,  #eaeaea 0%,#eaeaea 11%,#0e0e0e 61%); /* Opera 12+ */
 background: -ms-radial-gradient(top left, ellipse cover,  #eaeaea 0%,#eaeaea 11%,#0e0e0e 61%); /* IE10+ */
 background: radial-gradient(top left, ellipse cover,  #eaeaea 0%,#eaeaea 11%,#0e0e0e 61%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eaeaea', endColorstr='#0e0e0e',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}

/* Fond chromé, j'utilise ici une image d'un disque brossé */
.fond {
  position:relative;
  background-image: url(fond.png);
  background-repeat: no-repeat;
  width: 400px;
  height: 400px;
  /*left: 45%;
  /*top:20%;
  /* Permet de replir la totalité de la zone */
  background-size:cover;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* La petite feuille nest, affichée en cas d'économie d'énergie, ici lorsqu'on baisse la t° ou le ventilo */
.feuille {
  position:relative;
  top:-220px;
  left:90px;
  background-image: url(feuille.png);
  background-repeat: no-repeat;
  width: 32px;
  height: 32px;
  z-index:auto;
  /* non affichée par défaut */
  opacity:0;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  /* Alignement */
  text-align: center;
}

/* La petite flamme, affichée en cas de demande de chauffage immédiate, disparait en cliquant desssus */
.fire {
  position:relative;
  top:-180px;
  left:90px;
  background-image: url(fire.png);
  background-repeat: no-repeat;
  width: 32px;
  height: 32px;
  z-index:auto;
  /* non affichée par défaut */
  opacity:0;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  text-align: center;
}

/* La valeur NEST affichée, soit Température, soit % ventilo soit demande chauffage immédiat */
.nestValue {
position:relative;
  top:-100;
  left:-10;
  font-family: "MANDATOR", Verdana, Tahoma;
  font-size:60px;
  color:#ffffff;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  text-align: center;
}

/* En cas de demande immédiate de chauffage, durée de demande ici 1h  */
.hour {
  position:relative;
  top:-160;
  left:60;
  font-family: "MANDATOR", Verdana, Tahoma;
  font-size:20px;
  color:#ffffff;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

  opacity:0;
  z-index:500;
}

/* En cas de demande immédiate de chauffage, durée de demande ici 2h  */
.hour2 {
  position:relative;
  top:-206;
  left:120;
  font-family: "MANDATOR", Verdana, Tahoma;
  font-size:20px;
  color:#ffffff;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

  opacity:0;
  z-index:500;
}

* En cas de demande immédiate de chauffage, durée de demande ici 1h  */
.consigne {
  position:relative;
 
  font-family: "MANDATOR", Verdana, Tahoma;
  font-size:10px;
  color:#ffffff;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  display:none;
  opacity:0;
  z-index:500;
}

/* Mode Nest: Température, Fan, ou demande chauffage */
.nestMode {
position:relative;
  top:-30;
  left:-10;
  font-family: "MANDATOR", Verdana, Tahoma;
  font-size:20px;
  color:#ffffff;
  /* Permet de ne pas pouvoir être sélectionné */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

  text-align: center;
}

/* Titre nest, en cliquant les fonctions apparaissent */
.nestTitle {
  position:relative;
  top:-50;
  left:85;
  font-family: "N_E_B", Verdana, Tahoma;
  font-size:30px;
  color:#ffffff;
  z-index:100;
}


/* Le cercle bleu interne */
.center-circle-cold{
 position:relative;
 left:65;
 top:35;
 border: 0px solid #333;
 height: 220px;
 width: 220px;
 -moz-border-radius:220px;
 -webkit-border-radius: 220px;

  
 /* Dégradé circulaire */
 background: #3e61a8; /* Old browsers */
 background: -webkit-radial-gradient(top left, ellipse cover, #fff9f9 10%,#0338ac 60%); /* Chrome10+,Safari5.1+ */
 background: -moz-radial-gradient(top left, ellipse cover,  #eaeaea 0%, #fff9f9 10%, #0338ac 60%); /* FF3.6+ */
 background: -webkit-gradient(radial, top left, 0px, top left, 100%, color-stop(0%,#eaeaea), color-stop(10%,#fff9f9), color-stop(60%,#0338ac)); /* Chrome,Safari4+ */
 background: -o-radial-gradient(top left, ellipse cover,  #eaeaea 0%,#fff9f9 10%,#0338ac 60%); /* Opera 12+ */
 background: -ms-radial-gradient(top left, ellipse cover,  #eaeaea 0%,#fff9f9 10%,#0338ac 60%); /* IE10+ */
 background: radial-gradient(top left, ellipse cover,  #eaeaea 0%,#fff9f9 10%,#0338ac 60%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eaeaea', endColorstr='#0338ac',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

 /* Permet de ne pas pouvoir être sélectionné */
 -webkit-touch-callout: none;
 -webkit-user-select: none;
 -khtml-user-select: none;
 -moz-user-select: none;
 -ms-user-select: none;
 user-select: none;
}

/*----------------------------
	Barres de progression colorées
-----------------------------*/


#bars{
    
	height: 212px;
	margin: 0 auto;
	position: relative;
	top: 10px;
	left: 2px;
	width: 228px;
	-webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
     user-select: none;
}

.colorBar{
	width:15px;
	height:1px;
	position:absolute;
	opacity:0;
	background-color : #F4F4F4;
	-moz-transition:1s;
	-webkit-transition:1s;
	-o-transition:1s;
	transition:1s;
	-webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.colorBar.active{
	opacity:1;
}


</style>

<script src="./20121223-jquery.min.js" type="text/javascript"></script>
<!-- <script src="jquery.min.js" type="text/javascript"></script> !-->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./jQueryRotateCompressed.js"></script>

<style type="text/css"></style>


<script type="text/javascript">
// Fonction permettant de dessiner les bars de progression
$(document).ready(function(){
	var rad2deg = 180/Math.PI;
	var deg = 0;
	var bars = $('#bars');
	var j=0;
	for(var i=-20;i<82;i++){
		deg = i*3;
		//console.log(deg);
		// Creation des barres
		mytop =(-Math.sin(deg/rad2deg)*95+100);
		myleft = Math.cos((180 - deg)/rad2deg)*95+100;
		// On ajoute ici 82 barres en indiquant à chacune l'angle de rotation
	$('<div id=barre' + j + ' name=barre' + j + ' class="colorBar" style="-webkit-transform: rotate(' + deg + 'deg); -moz-transform: rotate(' + deg + 'deg) scale(1.25, 0.5); -ms-transform: rotate(' + deg + 'deg) scale(1.25, 0.5);transform: rotate(15deg) scale(1.25, 0.5);top: '+ mytop + '; left: ' + myleft+ ' ; color:red" >')
		.appendTo(bars);	
		j++;
	}
	var colorBars = bars.find('.colorBar');
	var numBars = 0, lastNum = -1;
	// ici on les désactive toutes en utilisant la css active sur les éléments de 0 à 0. donc rien.
    colorBars.removeClass('active').slice(0, 0).addClass('active');
})
</script>

<script type="text/javascript">  
$(document).ready(function(){    
$("body").on("touchmove", false);

// Fonction principale, ici un tableau de couleurs dégradées
var grad = [
		'243594','2c358f','373487','44337e','513174',
		'5c306c','6b2f62','792e58','892d4d','9e2b3d',
		'b4292e','c9271f','e0250e'];

// Dernier angle calculé
var lastAngle=0;
// Savoir si le bouton de souris est pressé.
var mouseDown="";

// temperature par défaut
var temperature=window.parent.temp_default;
// temperature affichée sur le controleur Nest pas défaut.
var temperatureNest=window.parent.temp_nest;
// Temperature courante remontée par une sonde 
var currentTemperature=window.parent.temp_house;
// soufflante par défaut
var airwave=50;
// soufflante affichée sur le controleur Nest par défaut.
var airwaveNest=50;
// ratio utilisé pour synchroniser les barres et le mode température
var ratioTemperature=4;
// ratio utilisé pour synchroniser les barres et le mode soufflante
var ratioAirwave=1;
// couleur de fond pour la temperature (au départ)
var couleurFondTemperature="#243594";
// Nest mode = thermostat ONLY? (noAirwave)
if (window.parent.nest_mode=="thermostat") {
  var couleurFondTemperature="#e0250e";
  if (temperatureNest<currentTemperature) { couleurFondTemperature="#243594"; };
  //console.log("Nest:"+temperatureNest+", Temp:"+(currentTemperature)+", color("+grad[temperatureNest-currentTemperature+6]+"):"+(temperatureNest-currentTemperature+6));
  if ((temperatureNest>currentTemperature) && ((temperatureNest-currentTemperature)<=6) ) { couleurFondTemperature=grad[temperatureNest-currentTemperature+6]; }
  // Pas de demande de chauffage pour le moment
  var heating_timeslot = window.parent.periode_chauffe;
  if (heating_timeslot==0) { couleurFondTemperature="#000000"; }
  // Heating timeslots
  var fin_chauffe = window.parent.fin_chauffe;
  prochaine_chauffe = window.parent.prochaine_chauffe;
}
// couleur de fond pour la soufflante (par défaut)
var couleurFondAirwave="#243594";
// couleur de fond pour autoaway
var couleurFondAutoAway="#000000";
// mode par défaut ici TEMP
var currentMode=window.parent.thermostat_mode;
var initialMode=currentMode;
console.log("Mode="+currentMode);
// Savoir si on est en mode Demande de chauffage Now
var heatNow=window.parent.heat_now;
// absence?
var absence=window.parent.absence;
console.log("absence="+absence);

// Heure de fin de chauffe ou suivante
var periode_fin = "";
if (heating_timeslot==0) {
  
  var qualif="Next: "; 
  if (prochaine_chauffe.substr(0,2)<window.parent.hour) {
    var wd=new Array(7); wd[6]="Sun "; wd[0]="Mon "; wd[1]="Tue "; wd[2]="Wed "; wd[3]="Thu "; wd[4]="Fri "; wd[5]="Sat ";wd[7]="Mon ";
    qualif = wd[window.parent.d];
  }
  periode_fin = "<font size='3'><br>" + qualif + prochaine_chauffe + "</font>";
  //absence = "0";
} else {
  if (fin_chauffe) { periode_fin = "<font size='4'><br>End: " + fin_chauffe + "</font>"; } 
}

var consigne=$('#consigne');

// Calibrage des rotations, pour l'affichage uniquement, rien de fonctionnel
for(var i=0;i<3600;i++){
  $('#full-circle').rotate(Math.round(i));
}

// Permet de positionner la temperature de consigne sur les barres
function poseConsigne(numBar,val)
{
        var rad2deg = 180/Math.PI;
		deg = numBar*3;
		//console.log(deg);
		// Creation des barres
		mytop =(-Math.sin(deg/rad2deg)*95+100);
		myleft = Math.cos((180 - deg)/rad2deg)*95+100;
		
		//console.log("myleft=" + Math.round(myleft));
		var colorbar = $('#barre' + numBar)
		
		if  ( colorbar != null )
		{
		var colorbarOffset = colorbar.offset();
		//console.log("LEFT: " + colorbar.left);
		if ( colorbarOffset != null )
		{
		consigne.css("position","absolute");
		consigne.css("left",colorbarOffset.left);
		consigne.css("top",colorbarOffset.top);
		//for(var i=0;i<102;i++){
		//var colorbarTmp = $('#barre' + i);
		//colorbarTmp.css("height",1);
		//}
		//colorbar.css("height",4);
		}
		//console.log("NUMBAR: " + numBar);
		}
		consigne.css("font-family","MANDATOR");
		consigne.html(val);
};


// Définition de la fonction pour gestion de temperature
function manageTemperature(e) {
    var offset = $('#full-circle').offset();
    var width=$('#full-circle').width();
    var height=$('#full-circle').height();
    var center_x = (offset.left) + (width/2);
    var center_y = (offset.top) + (height/2);
    var mouse_x = e.pageX; var mouse_y = e.pageY;
	var bars = $('#bars');
	var centerCircle = $('#center-circle-cold');
	var colorBars = bars.find('.colorBar');
	var feuille = $('#feuille');

	//console.log("width="+ width + " height="+height + " center_x=" + center_x + " center_y=" + center_y + " offsetLeft=" + offset.left + " this.offsetTop="+ offset.top);
	// Choix de la couleur de fond en fonction de la temperature
	if ( temperatureNest < 12 )
	 {
	  couleurFondTemperature='#' + grad[0];
	 }
	if ( temperatureNest > 24 )
	 {
	  couleurFondTemperature='#' + grad[11];
	 }
	if (  (temperatureNest <= 24 ) && (temperatureNest >= 12) )
	  {
	  //console.log(temperatureNest - 12);
	  couleurFondTemperature='#' + grad[temperatureNest - 12];
	  }
	 
     // Arrondi au Dixième
     temperature=Math.round(temperature*10)/10;

	
     var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
     degree = (radians * (180 / Math.PI) * -1) + 180; 
	 
	 // On regarde le dernier angle pour savoir si on est en mode + ou -
	 if ( degree - lastAngle > 0)
	 {
	   //console.log("lastAngle=" + lastAngle + " degree=" + degree + "+");
	   temperature+=0.1;
	   
	   //feuille.css("opacity","0");
	   $( "#nestMode" ).html("HEATING (" + currentTemperature + "°)");
	 } else
	 {
	   //console.log("lastAngle=" + lastAngle + " degree=" + degree + "-");
	   temperature-=0.1;
	   
       //feuille.css("opacity","1");
	   $( "#nestMode" ).html("COOLING (" + currentTemperature + "°)");
	 }
	 majCouleurCercle(couleurFondTemperature);
	 poseConsigne(ratioTemperature*temperatureNest,temperatureNest);
	 majBarres(temperature,ratioTemperature);
	 lastAngle=degree;
	 temperatureNest=Math.round(temperature);
	 $( "#nestValue" ).html(temperatureNest + periode_fin);
};


// Fonction mettant à jour les barresn en passant la valeur et le ratio
function majBarres(value,ratio)
{
var bars = $('#bars');
var colorBars = bars.find('.colorBar');
colorBars.removeClass('active').slice(0, Math.round(value*ratio)).addClass('active');

}

// Fonction mettant à jour le degradé sur cercle central en passant la couleur de fond à obtenir
function majCouleurCercle(couleurFond)
{
       var centerCircle = $('#center-circle-cold');
	   centerCircle.css("background", "-webkit-radial-gradient(top left, ellipse cover, #fcf7f7 10%," + couleurFond + " 60%)"); /* Chrome 10 */
	   centerCircle.css("background", "-moz-radial-gradient(top left, ellipse cover, #fcf7f7 10%," + couleurFond + " 60%)"); /* FF */
	   centerCircle.css("background", "-webkit-gradient(radial, top left, 0px, top left, 100%, color-stop(10%,fff9f9), color-stop(60%,"+ couleurFond +"))"); /* Safari */
	   centerCircle.css("background", "-o-radial-gradient(top left, ellipse cover, #fcf7f7 10%," + couleurFond + " 60%)"); /* Opera 12+ */
	   centerCircle.css("background", "-ms-radial-gradient(top left, ellipse cover, #fcf7f7 10%," + couleurFond + " 60%)"); /* IE10+ */
       centerCircle.css("background", "radial-gradient(top left, ellipse cover, #fcf7f7 10%," + couleurFond + " 60%)"); /* W3C */
}

// Définition de la fonction pour gestion du recyclage de l'air
function manageAirwave(e) {
    var offset = $('#full-circle').offset();
    var width=$('#full-circle').width();
    var height=$('#full-circle').height();
    var center_x = (offset.left) + (width/2);
    var center_y = (offset.top) + (height/2);
    var mouse_x = e.pageX; var mouse_y = e.pageY;
	var bars = $('#bars');
	var centerCircle = $('#center-circle-cold');
	var colorBars = bars.find('.colorBar');
	var feuille = $('#feuille');
	

	//console.log("width="+ width + " height="+height + " center_x=" + center_x + " center_y=" + center_y + " offsetLeft=" + offset.left + " this.offsetTop="+ offset.top);
	// Choix de la couleur en fonction de la valeur de la soufflante
	if ( airwaveNest < 10 )
	 {
	  var couleurFondAirwave='#' + grad[0];
	 }
	if ( airwaveNest > 90 )
	 {
	  var couleurFondAirwave='#' + grad[11];
	 }
	if (  (airwaveNest <= 90 ) && (airwaveNest >= 10) )
	  {
	  //console.log(temperatureNest - 12);
	  var couleurFondAirwave='#' + grad[(airwaveNest/3)- 12];
	  }
	 
     // Arrondi au Dixième
     airwave=Math.round(airwave*10)/10;

	
     var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
     degree = (radians * (180 / Math.PI) * -1) + 180; 
	 var ratio=1;
	 // Mode + ou - en analysant le dernier angle calculé et le nouveau
	 if ( degree - lastAngle > 0)
	 {
	   //console.log("lastAngle=" + lastAngle + " degree=" + degree + "+");
	   airwave+=1;
	   majCouleurCercle(couleurFondAirwave);
	   feuille.css("opacity","0");
	   $( "#nestMode" ).html("WIND");
	 } else
	 {
	   //console.log("lastAngle=" + lastAngle + " degree=" + degree + "-");
	   airwave-=1;
	   majCouleurCercle(couleurFondAirwave);
	   feuille.css("opacity","1");
	   $( "#nestMode" ).html("CALM");
	 }
	 majBarres(airwave,ratioAirwave);
	 lastAngle=degree;
	 airwaveNest=Math.round(airwave);
	 // On plafonne les valeurs
	 if ( airwaveNest > 100 )
	 {
	 airwaveNest=100;
	 airwave=100;
	 }
	 if ( airwaveNest < 0 )
	 {
	 airwaveNest=0;
	 airwave=0;
	 }
	 
	 
	 $( "#nestValue" ).html(airwaveNest );
};


$('#full-circle').mousedown(function(e){
  // Lorsqu'on appuie sur le bouton de gauche, on autorise le thermostat à bouger
  mouseDown = "ok";
});

$('*').mouseup(function(e){
  // Lorsqu'on relache le bouton de gauche, on n'autorise plus le thermostat à bouger
  
  // Update Consigne
  if ((currentMode=="TEMP") && (mouseDown=="ok") && (temperature!=temperatureNest)) {
    //console.log("TEMP Update!");
	window.parent.xajax_updateConsigne(temperatureNest);
	//window.parent.traitement();
	TimeOUT_RELOAD=setTimeout(function(){window.parent.location.reload(true)},500);
  }
  mouseDown="";
  //console.log("Mouse UP");
});

// Lorsque l'on clique sur l'icone 1h, on sauvegarde et on fait tout disparaitre
$('#hour').click(function(){ 
  $("#hour").css("opacity","0");
  $("#hour2").css("opacity","0");
  $("#fire").css("opacity","1");
  heatNow=1;
   $( "#nestValue" ).html("1h");
   window.parent.xajax_HeatNow(1);
   TimeOUT_RELOAD=setTimeout(function(){window.parent.location.reload(true)},500);
});
// Lorsque l'on clique sur l'icone 2h, on sauvegarde et on fait tout disparaitre
$('#hour2').click(function(){ 
  $("#hour").css("opacity","0");
  $("#hour2").css("opacity","0");
  $("#fire").css("opacity","1");
  heatNow=2;
   $( "#nestValue" ).html("2h");
   //console.log("2h?");
   window.parent.xajax_HeatNow(2);
   TimeOUT_RELOAD=setTimeout(function(){window.parent.location.reload(true)},500);
});

// Lorsque l'on clique sur l'icone fire, le mode de demande immédiate de chauffage disparait
$('#fire').click(function(){ 
$("#fire").css("opacity","0");
heatNow="";
});

// Lorsque l'on clique sur l'icone feuille, passe en mode presence (Absence-1)
$('#feuille').click(function(){ 
  $("#feuille").css("opacity","0");
  //console.log("absence=1");
  window.parent.xajax_autoBack();
  TimeOUT_RELOAD=setTimeout(function(){window.parent.location.reload(true)},500);
});

// Init
$(document).ready(function(){

  //if (heating_timeslot==0) { currentMode = "TEMP"; }
  // console.log("INIT" + " currentMode=" + currentMode);
  if ( currentMode == "AIRWAVE" )
   {
	majBarres(airwave,ratioAirwave);
	majCouleurCercle(couleurFondAirwave);
	$( "#nestValue" ).html(airwaveNest);
	$("#hour").css("opacity","0");
	$("#hour2").css("opacity","0");
	$( "#consigne" ).css("opacity","0");
	$('#feuille').css("opacity",absence);
  }
  if ( currentMode == "HEAT NOW" )
   {
	$( "#nestValue" ).html('for');
	majCouleurCercle(couleurFondTemperature);
	majBarres(0,ratioTemperature);
	$("#hour").css("opacity","1");
	$("#hour2").css("opacity","1");
	$( "#consigne" ).css("opacity","0");
	$('#feuille').css("opacity",absence);
   }
   if ( currentMode == "TEMP" )
   {
    $( "#nestMode" ).html("TEMP");
	$( "#nestValue" ).html(temperatureNest + periode_fin);
	majCouleurCercle(couleurFondTemperature);
	majBarres(temperature,ratioTemperature);
	$( "#consigne" ).html("");
	$( "#consigne" ).css("opacity","1");
	$("#hour").css("opacity","0");
	$("#hour2").css("opacity","0");
	$('#feuille').css("opacity",absence);
	console.log("absence=" + absence);
    TimeOUT_AWAY=setTimeout(function(){AUTO_Away()},1000);
	clearTimeout(TimeOUT_AWAY);
   }
   if ( currentMode == "Auto" )
   {
    $( "#nestMode" ).html(currentMode);
	$( "#nestValue" ).html("AWAY");
	majCouleurCercle(couleurFondAutoAway);
	$( "#consigne" ).html("");
	$( "#consigne" ).css("opacity","0");
	$("#hour").css("opacity","0");
	$("#hour2").css("opacity","0");
	$('#feuille').css("opacity",absence);
	majBarres(0,ratioTemperature);
	TimeOUT_AWAY=setTimeout(function(){AUTO_Away()},1000);
	clearTimeout(TimeOUT_AWAY);
   }
  
  
  $("#nestMode" ).slideUp();
  
  // Texte spécific TEMP + ou -
  if (currentMode=="TEMP") {
    if (temperatureNest > currentTemperature) {
	  $( "#nestMode" ).html("HEATING (" + currentTemperature + "°)");
	  //console.log("HEATING" + currentTemperature);
	} else if ((temperatureNest < currentTemperature)) {
	  $( "#nestMode" ).html("COOLING (" + currentTemperature + "°)");
	  //console.log("COOLING" + currentTemperature);
	} else { $( "#nestMode" ).html(currentMode + " (" + currentTemperature + "°)");}
  } else { $( "#nestMode" ).html(currentMode + " (" + currentTemperature + "°)");}
  if  ((heating_timeslot==0) && (initialMode!="Auto")) { $( "#nestMode" ).html("OFF<font size='2'> (" + currentTemperature + "°)</font>"); currentMode = "TEMP";}
  $("#nestMode" ).slideDown();
});

// END Init






// Lorsque l'on clique sur le texte nest, on switch d'un mode à l'autre
$('#nestTitle').click(function(){ 

  console.log("clic" + " currentMode=" + currentMode);
  if ( currentMode == "Auto" )
//   {
//    currentMode="AIRWAVE"
//	majBarres(airwave,ratioAirwave);
//	majCouleurCercle(couleurFondAirwave);
//	$( "#nestValue" ).html(airwaveNest );
//	$("#hour").css("opacity","0");
//	$("#hour2").css("opacity","0");
//	$( "#consigne" ).css("opacity","0");
//	$('#feuille').css("opacity","0");
 //  } else if ( currentMode == "AIRWAVE" )
   {  
    currentMode="TEMP";
	$( "#nestValue" ).html(temperatureNest  + periode_fin);
	majCouleurCercle(couleurFondTemperature);
	majBarres(temperature,ratioTemperature);
	$( "#consigne" ).html("");
	$( "#consigne" ).css("opacity","1");
	$("#hour").css("opacity","0");
	$("#hour2").css("opacity","0");
	$('#feuille').css("opacity",absence);
	// Cancels Auto AWAY
	//console.log("CLEAR Auto AWAY!");
	clearTimeout(TimeOUT_AWAY);
   } else if ( currentMode == "TEMP" )
   {
    currentMode="HEAT NOW";
	$( "#nestValue" ).html('for');
	majCouleurCercle(couleurFondTemperature);
	majBarres(0,ratioTemperature);
	$("#hour").css("opacity","1");
	$("#hour2").css("opacity","1");
	$( "#consigne" ).css("opacity","0");
	$('#feuille').css("opacity",absence);
	// Cancels auto AWAY
	//console.log("CLEAR Auto AWAY!");
	clearTimeout(TimeOUT_AWAY);
	//if  ((heating_timeslot==0) && (initialMode!="Auto")) { currentMode="Auto"; }
   }else if ( currentMode == "HEAT NOW" )
   {
   currentMode="Auto";
	$( "#nestValue" ).html("AWAY");
	majCouleurCercle(couleurFondAutoAway);
	$( "#consigne" ).html("");
	$( "#consigne" ).css("opacity","0");
	$("#hour").css("opacity","0");
	$("#hour2").css("opacity","0");
	$('#feuille').css("opacity",absence);
	majBarres(0,ratioTemperature);
	// Arret Chaudière ... Auto AWAY
	//console.log("Auto AWAY!");
	TimeOUT_AWAY=setTimeout(function(){AUTO_Away()},3000);
   }
  
  
  $("#nestMode" ).slideUp();
  if (currentMode=="TEMP") {
    if (heating_timeslot==0) { $( "#nestMode" ).html("OFF (" + currentTemperature + "°)"); } else {
      if (temperatureNest > currentTemperature) {
	    $( "#nestMode" ).html("HEATING (" + currentTemperature + "°)");
	    //console.log("HEATING" + currentTemperature);
	  } else if ((temperatureNest < currentTemperature)) {
	    $( "#nestMode" ).html("COOLING (" + currentTemperature + "°)");
	    //console.log("COOLING" + currentTemperature);
	  } else {  $( "#nestMode" ).html(currentMode);}
	}
  } else { $( "#nestMode" ).html(currentMode);}
  
  $("#nestMode" ).slideDown();
});

function AUTO_Away() {
  //alert("TimeOUT!");
  window.parent.xajax_autoAway();
  TimeOUT_RELOAD=setTimeout(function(){window.parent.location.reload(true)},500);
}

// En fonction du mode, on fait varier les couleurs et les barres différement
$('#full-circle').mousemove(function(e){ 
  // Si on est autorisé à bouger	
  if ( mouseDown == "ok" )
   {
    if ( currentMode == "TEMP" )
	{
	manageTemperature(e);
    } 
    if ( currentMode == "AIRWAVE" )
     {
	manageAirwave(e);
	}
	// On transmet au système domotique une requête AJAX HTTP, le système (PHP, JAVA, ASP, DOT ou autres) devra "parser" la requête HTTP
    // On récupère ainsi les 3 paramètres, la température, la soufflante et si on est en mode demande de chauffage, heatNow aura la valeur vide (Rien), 2 ou 4 respectivement 2 ou 4h
    //window.parent.xajax_descendreTemperature();
	//$.ajax("http://monsite.org&ObjectAction?temperatureNest=" + temperatureNest + "&airwaveNest=" + airwaveNest+"&heatNow=" + heatNow);
     }
});


$('#full-circle').bind( "touchstart", function(e){
  // Lorsqu'on touche l'écran, on autorise le thermostat à bouger
  mouseDown="ok";
  console.log("touchstart");
});

$('*').bind( "touchend", function(e){
       // Lorsqu'on relache l'écran, on n'autorise plus le thermostat à bouger
  mouseDown="";
  console.log("touchend");
});

$('#full-circle').bind( "touchmove", function(e){
    var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
  // Si on est autorisé à bouger   
  if ( mouseDown == "ok" )
   {
    if ( currentMode == "TEMP" )
   {
   manageTemperature(touch);
    } 
    if ( currentMode == "AIRWAVE" )
     {
   manageAirwave(touch);
   }
 }
});
});
</script>

</head>
<body style="background: #000; color: #FFF;">	


<div class="fond">
	<div id="full-circle" class="full-circle">
		<div id="center-circle-cold" class="center-circle-cold">
			  <div id="bars">
	          <p id="nestTitle" class="nestTitle">nest</p>
			  <p name="nestMode" id="nestMode" class="nestMode"></p>
			  <p name="nestValue" id="nestValue" class="nestValue"></p>
			  <p name="hour" id="hour" class="hour">1H</p>
			  <p name="hour2" id="hour2" class="hour2">2H</p>
			
			  <div id="feuille" class="feuille"></div>
			  <div id="fire" class="fire"></div>
		</div>
	</div>
</div>
</div>
  <div id="consigne" name="consigne" ></div>
</body>

</html>