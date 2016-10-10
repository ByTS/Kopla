<?php
	date_default_timezone_set('Europe/Helsinki');
	$pvm = date("Y-m-d");
	$pvm2 = "2016-05-22";
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
app.controller("junat", function($scope, $http, $filter) {
	$http.get('http://rata.digitraffic.fi/api/v1/schedules?departure_date=<?php echo $pvm2;?>').
    success(function(data, status, headers, config) {
      $scope.trains = data;
      });
      });
app.controller("asemat", function($scope, $http) {
  $http.get('http://rata.digitraffic.fi/api/v1/metadata/stations').
    success(function(data, status, headers, config) {
      $scope.stations = data;
    });
    });
            app.controller('kello', function($scope, $interval) {
  var tick = function() {
    $scope.kello = Date.now();
  }
  tick();
  $interval(tick, 1000);
});R
		</script>
		<script src="app.js" type="text/javascript"></script>
				<script>
function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('kello').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
function etusivulle() {
    document.getElementById('brandi').innerHTML =
    "Etusivulle";
}
		</script>
	</head>
	<body ng-app="kaikki" onload="startTime()">
		<div class="container-fluid">
			<?php include 'incs/nav.php';?>
		</div>
		<div class="container-fluid" ng-controller="junat">
				<h2>Kaikki junat <small class="text-muted"><?php echo $tanaan;?></small></h2>
				
				<h4 ng-show="trains.length">Tänään {{trains.length}} junaa, joista {{(trains | filter:{'runningCurrently':true}: true).length}} tällä hetkellä kulussa. Peruttuja yhteensä {{(trains | filter:{'cancelled':true}: true).length}} kpl ({{((trains | filter:{'cancelled':true}: true).length / trains.length)*100 | number:2}}%).</h4>
			<table class="table table-striped table-sm">
				<tbody>
					<tr>
						<td>
							Lähiliikenne {{(trains | filter:{'trainType':'HL'}: true).length}} yhteensä
						</td>
					</tr>
				</tbody>
			</table>
		<nav class="navbar navbar-fixed-bottom navbar-light bg-faded vihreepohja">
			<a class="nav-item valkoinen">Liikennetiedot</a>
			<a class="nav-item valkoinen" href="http://www.liikennevirasto.fi">Liikennevirasto</a>
			<a class="nav-item valkoinen" href="http://creativecommons.org/licenses/by/4.0/">CC 4.0 BY</a>
            <a class="nav-item valkoinen pull-right" id="kello"></a>
		</nav>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    </div>
	</body>
</html>