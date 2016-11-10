<!DOCTYPE html>
<html lang="fi">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>Raidetiedot</title>
		<link href='https://fonts.googleapis.com/css?family=Exo:400,100,200,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="main.css">
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.1/angular.min.js" type="text/javascript"></script>
		<script>
var app = angular.module("asema", []);
    app.controller("liveTrains", function($scope, $http) {
      $http.get('https://rata.digitraffic.fi/api/v1/train-tracking?station=HKI&track_section=001a').
      success(function(data, status, headers, config) {
        $scope.001a = data;
        });
      this.interval = setInterval(function() {
      $http.get('https://rata.digitraffic.fi/api/v1/train-tracking?station=HKI&track_section=001a').
      success(function(data, status, headers, config) {
        $scope.001a = data;
        });
      }, 5000);
      this.endLongPolling = function() {
        clearInterval(this.interval);
      };
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
				<h2>Helsinki</h2>
			</span>
			<table class="table table-striped table-sm">
				<thead class="vihreepohja">
					<th><center>Raide</center></th>
					<th>Numero</th>
					<th>Lähtöaika</th>
				</thead>
				<tbody>
				</tbody>
			</table>
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
