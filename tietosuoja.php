<?php
	date_default_timezone_set('Europe/Helsinki');
	$pvm = date("Y-m-d");
	$tanaan = date("d.m.Y");
?>
<!DOCTYPE html>
<html lang="fi">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>KOPLA - Kaikki junat, <?php echo $tanaan;?></title>
		<link href='https://fonts.googleapis.com/css?family=Exo:400,100,200,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="main.css">
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.1/angular.min.js" type="text/javascript"></script>
		<script>
var app = angular.module("kaikki", []);
app.controller('kello', function($scope, $interval) {
  var tick = function() {
    $scope.kello = Date.now();
  }
  tick();
  $interval(tick, 1000);
});R
		</script>
		<script src="app.js" type="text/javascript"></script>
	</head>
	<body ng-app="kaikki" onload="startTime()">
		<div class="container-fluid">
			<?php include 'incs/nav.php';?>
		</div>
		<div class="container-fluid" ng-controller="junat">
				<h2>Rekisteri- ja tietosuojaseloste</h2>
<h5>Tämä on KOPLAn, sen verkkosivujen sekä mobiilisovellusten yhdistetty Henkilötietolain (523/1999) 10 ja 24 § mukainen rekisteriseloste sekä tietosuojaseloste.</h5>
<p>Rekisterinpitäjänä sekä yhteyshenkilönä toimii Tuomas Savela, tuomas@byts.fi</p>
<p>Rekisterin nimi on KOPLAn käyttäjätietorekisteri. Rekisteriä käytetään tulevaisuudessa yksilökohtaisen tiedon välitykseen. Rekisterin käyttöönottotilanteessa tämä rekisteriseloste muutetaan ajankohtaiseksi. Tällä hetkellä käyttäjätietorekisteriin ei tallenneta mitään tietoa. Käyttäjärekisterin tietoja luovutetaan ainoastaan viranomaisille lain edellyttämissä tapauksissa. Henkilötiedot säilytetään aina Euroopan unionin tai Euroopan talousalueen sisäpuolella, eikä niitä siirretä edellämainittujen alueiden ulkopuolelle.

Sivusto käyttää evästeitä toimintojen toteuttamiseksi. Sivustoa ei voi käyttää ilman evästeitä. Käyttämällä sivustoa tämän sivun ulkopuolella hyväksyt evästeiden käytön. Evästeitä käytetään myös sivuston käytön analysointiin sekä kehittämiseen.
</p>
		</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    </div>
	</body>
</html>