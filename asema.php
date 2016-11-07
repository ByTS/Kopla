<?php
$as = $_GET["as"];
if (is_numeric($as)):
header("Location: aikataulu.php?n=".$as);
endif;
?>
<!DOCTYPE html>
<html lang="fi">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>KOPLA - Lähtevät, <?php echo $as;?></title>
		<link href='https://fonts.googleapis.com/css?family=Exo:400,100,200,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="main.css">
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.1/angular.min.js" type="text/javascript"></script>
		<script>
var app = angular.module("asema", []);
app.controller("yhteydet", function($scope, $http, orderByFilter) {
	$http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=<?php echo $as;?>&departed_trains=0&departing_trains=25&arriving_trains=0&arrived_trains=0').
    success(function(data, status, headers, config) {
      $scope.rows = data;
      $scope.sortedRows = orderByFilter($scope.rows, '+scheduledTime');
      console.log($scope.sortedRows);
      });
      $http.get('stations.json').
      success(function(data, status, headers, config) {
        $scope.stations = data;
        });
		});
app.controller("asemat", function($scope, $http) {
  $http.get('https://rata.digitraffic.fi/api/v1/metadata/stations').
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
});
		</script>
		<script src="app.js" type="text/javascript"></script>
	</head>
	<body ng-app="asema">
		<div class="container-fluid">
			<?php include 'incs/nav.php';?>
		</div>
		<div class="container-fluid">
			<span ng-controller="asemat">
				<h2><span ng-hide="!(stations| filter:{'stationShortCode':'<?php echo $as;?>'}: true).length">Lähtevät,</span>
					<small class="text-muted">
						<span ng-repeat="x in stations | filter:{'stationShortCode':'<?php echo $as;?>'}: true | groupBy: !'scheduledTime'">{{x.stationName}} ({{x.stationShortCode}})</span>
						<div ng-show="!(stations| filter:{'stationShortCode':'<?php echo $as;?>'}: true).length">Liikennepaikkaa ei valitettavasti löydy. Tarkista oikeinkirjoitus?<br>Huomioi, että mikäli haet tiettyä junaa ja jouduit tälle sivulle, syötä pelkästään numerot ilman etukirjainta.</div>
					</small>
				</h2>
			</span>
			<table class="table table-striped table-sm" ng-controller="yhteydet" ng-hide="!rows.length">
				<thead class="vihreepohja">
					<th>Numero</th>
					<th>Määränpää</th>
					<th>Lähtöaika</th>
					<th>Arvioitu</th>
					<th><center>Raide</center></th>
				</thead>
				<tbody>
					<tr ng-show="!rows.length"><td>Ei lähteviä junia!</td></tr>
<tr ng-repeat="x in rows | orderBy:'scheduledTime'" 
						ng-init="x.scheduledTime = (x.timeTableRows | filter:{stationShortCode:'<?php echo $as;?>', 'type':'DEPARTURE'})[0].scheduledTime">
						<td>
							<a href="aikataulu.php?n={{x.trainNumber}}">
							<span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
							<span class="label label-primary line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}}</a>
						</td>
						<td><span ng-repeat="y in x.timeTableRows | limitTo:-1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span></td>
						<td>{{x.scheduledTime | date:'HH:mm'}}</td>
						<td><span ng-repeat="y in x.timeTableRows | filter:{'stationShortCode':'<?php echo $as;?>', 'type':'DEPARTURE'}: true | limitTo:1" class="min-{{y.scheduledTime | date:'HH:mm'}}">{{y.liveEstimateTime | date:'HH:mm'}}</span></td>
						<td><span class="show-{{x.cancelled}} label label-danger">Peruttu</span><span ng-repeat="y in x.timeTableRows | filter:{'stationShortCode':'<?php echo $as;?>', 'type':'DEPARTURE'}: true | limitTo:1">{{y.commercialTrack}}</span></td>
        			</tr>				</tbody>
			</table>
			<h4><small class="text-muted">Junat toistaiseksi vielä numerojärjestyksessä.</small></h4>
				<br><br>
		<nav class="navbar navbar-fixed-bottom navbar-light bg-faded vihreepohja">
			<a class="nav-item valkoinen">Liikennetiedot</a>
			<a class="nav-item valkoinen" href="http://www.liikennevirasto.fi">Liikennevirasto</a>
			<a class="nav-item valkoinen" href="http://creativecommons.org/licenses/by/4.0/">CC 4.0 BY</a>
		</nav>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    </div>
	</body>
</html>
