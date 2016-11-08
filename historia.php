<?php
	date_default_timezone_set( 'Europe/Helsinki');
	$juna = $_GET["n"];
	$pvm = $_GET["pvm"];
    $querypvm = date("Y-m-d", strtotime($pvm));
    $siisti = date("d.m.Y", strtotime($pvm));
	$eilen = date("Y-m-d", strtotime($pvm) - 60 * 60 * 24);
	$siistieilen = date("d.m.Y", strtotime($eilen));
	$huomenna = date("Y-m-d", strtotime($pvm) + 60 * 60 * 24);
	$siistihuomenna = date("d.m.Y", strtotime($huomenna));
	$nyt=date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="fi">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>KOPLA - Junan <?php echo $juna;?> kulkutiedot (<?php echo $siisti;?>)</title>
		<link href='https://fonts.googleapis.com/css?family=Exo:400,100,200,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="main.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.1/angular.min.js" type="text/javascript"></script>
        <script src="app.js" type="text/javascript"></script>
		<script>
var app = angular.module("historia", []);
app.controller("toteuma", function($scope, $http) {
		$http.get('https://rata.digitraffic.fi/api/v1/history/<?php echo $juna;?>?departure_date=<?php echo $querypvm;?>').
		success(function(data, status, headers, config) {
			$scope.rows = data;
		});
		$http.get('stations.json').
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
	<body ng-app="historia" onload="startTime()">
		<div class="container-fluid">
			<?php include 'incs/nav.php';?>
		</div>
		<div class="container-fluid" ng-controller="toteuma">
			<a href="historia.php?pvm=<?php echo $eilen;?>&n=<?php echo $juna;?>" class="btn btn-info-outline"><?php echo $siistieilen;?></a>
			<span ng-hide="'<?php echo $huomenna;?>'=='<?php echo $nyt;?>'">
				<a href="historia.php?pvm=<?php echo $huomenna;?>&n=<?php echo $juna;?>" class="btn btn-info-outline"><?php echo $siistihuomenna;?></a>
			</span>
		<h2 ng-show="!(rows | filter:{'trainNumber':<?php echo $juna;?>}: true).length">Ladataan...</h2>
			<div ng-repeat="x in rows | filter:{'trainNumber':<?php echo $juna;?>}: true" autoscroll="false">
			<h2><span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
                <span class="label line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}} 
                <small class="text-muted"><span ng-repeat="y in x.timeTableRows | limitTo:1">{{y.stationShortCode}}</span> -
                    <span ng-repeat="y in x.timeTableRows | limitTo:-1">{{y.stationShortCode}}</span></span>
                        
                </small></h2>
                <h4>
                    <span class="hide-{{x.cancelled}} label label-danger">Peruttu</span>
                    <small class="text-muted">
                        <?php echo $siisti;?>
                    </small>
                </h4>
			<table class="table table-striped table-sm">
				<thead class="vihreepohja">
					<th>Asema</th>
					<th>Lähtöaika</th>
					<th>Toteutunut</th>
					<th><center>Raide</center></th>
				</thead>
				<tbody>
					<tr ng-repeat="y in x.timeTableRows | filter:{'type':'DEPARTURE'}: true | filter:{'commercialStop':true}: true" autoscroll="false">
						<td><a href="asema.php?as={{y.stationShortCode}}"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></a></td>
						<td>{{y.scheduledTime | date:'HH:mm'}}</td>
						<td>{{y.actualTime | date:'HH:mm:ss'}} <span class="label label-default pull-right dif{{y.differenceInMinutes}}">{{y.differenceInMinutes}}</span></td>
						<td><center>{{y.commercialTrack}}</center><!-- <i class="starrow {{y.stationShortCode}}{{y.commercialTrack}}V fa fa-arrow-left"></i><i class="starrow {{y.stationShortCode}}{{y.commercialTrack}}O fa fa-arrow-right"></i>--></td>
					</tr>
					<tr ng-repeat="y in x.timeTableRows | limitTo:-1">
						<td><a href="asema.php?as={{y.stationShortCode}}"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></a></td>
						<td>{{y.scheduledTime | date:'HH:mm'}}</td>
						<td>{{y.actualTime | date:'HH:mm:ss'}} <span class="label label-default pull-right dif{{y.differenceInMinutes}}">{{y.differenceInMinutes}}</span></td>
						<td><center>{{y.commercialTrack}}</center><!-- <i class="starrow {{y.stationShortCode}}{{y.commercialTrack}}V fa fa-arrow-left"></i><i class="starrow {{y.stationShortCode}}{{y.commercialTrack}}O fa fa-arrow-right"></i>--></td>
					</tr>
				</tbody>
			</table>
			</div>
                <ul class="list-group" ng-repeat="y in x.timeTableRows">
                    <li class="list-group-item" ng-repeat="cause in y.causes">
                        {{y.stationShortCode}} <span class="depShow{{y.type}}">lähtö :</span><span class="arrShow{{y.type}}">tulo :</span>
                        <span class="cause CE{{cause.categoryCode}}">Etuajassakulku</span>
                        <span class="cause CH{{cause.categoryCode}}">Henkilökunta</span>
                        <span class="cause CI{{cause.categoryCode}}">Muut syyt</span>
                        <span class="cause CJ{{cause.categoryCode}}">Junan kokoonpano</span>
                        <span class="cause CK{{cause.categoryCode}}">Moottorijunat ja vaunut</span>
                        <span class="cause CL{{cause.categoryCode}}">Liikennetekniset syyt</span>
                        <span class="cause CM{{cause.categoryCode}}">Matkustajapalvelu</span>
                        <span class="cause CO{{cause.categoryCode}}">Liikenneonnettomuudet</span>
                        <span class="cause CP{{cause.categoryCode}}">Turva-, valvonta- ja viestilaitteet</span>
                        <span class="cause CR{{cause.categoryCode}}">Rata</span>
                        <span class="cause CS{{cause.categoryCode}}">Sähköistys</span>
                        <span class="cause CT{{cause.categoryCode}}">Tavarapalvelu</span>
                        <span class="cause CV{{cause.categoryCode}}">Veturit</span>
                         ({{cause.detailedCategoryCode}})<span class="cause{{cause.detailedCategoryCode}}"></span>
                    </li>
                </ul>
			</div>
		<nav class="navbar navbar-fixed-bottom navbar-light bg-faded vihreepohja">
			<a class="nav-item valkoinen">Liikennetiedot</a>
			<a class="nav-item valkoinen" href="http://www.liikennevirasto.fi">Liikennevirasto</a>
			<a class="nav-item valkoinen" href="http://creativecommons.org/licenses/by/4.0/">CC 4.0 BY</a>
		</nav>
		</div>
        <script></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
	</body>
</html>