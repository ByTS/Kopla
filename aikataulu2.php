<?php
    date_default_timezone_set( 'Europe/Helsinki');
    $juna=$_GET["n"];
    $pvm=date("Y-m-d");
    $eilen=date("Y-m-d", time() - 60 * 60 * 24);
    $huomenna=date("Y-m-d", time() + 60 * 60 * 24);
?>
<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>KOPLA - Junan <?php echo $juna;?> aikataulu</title>
  <link href='https://fonts.googleapis.com/css?family=Exo:400,100,200,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="angular.min.js"></script>
  <script src="app.js"></script>
<script>
    var app = angular.module("aikataulu", []);
	
    app.controller("liveTrains", function($scope, $http) {
      $http.get('https://rata.digitraffic.fi/api/v1/live-trains/<?php echo $juna;?>').
      success(function(data, status, headers, config) {
        $scope.rows = data;
        });
		$http.get('stations.json').
      success(function(data, status, headers, config) {
        $scope.stations = data;
        });
      $http.get('https://rata.digitraffic.fi/api/v1/metadata/cause-category-codes').
	success(function(data, status, headers, config) {
		$scope.causes = data;
		});
      $http.get('https://rata.digitraffic.fi/api/v1/metadata/detailed-cause-category-codes').
	success(function(data, status, headers, config) {
		$scope.detailedcauses = data;
		});
        $http.get('https://rata.digitraffic.fi/api/v1/train-tracking/<?php echo $juna;?>').
        success(function(data, status, headers, config) {
          $scope.sections = data;
      });
        $http.get('https://rata.digitraffic.fi/api/v1/metadata/track-sections').
        success(function(data, status, headers, config) {
          $scope.tracksections = data;
      });
      this.interval = setInterval(function() {
        $http.get('https://rata.digitraffic.fi/api/v1/live-trains/<?php echo $juna;?>').
        success(function(data, status, headers, config) {
          $scope.rows = data;
        });
        $http.get('https://rata.digitraffic.fi/api/v1/train-tracking/<?php echo $juna;?>').
        success(function(data, status, headers, config) {
          $scope.sections = data;
        });
      }, 5000);
      this.endLongPolling = function() {
        clearInterval(this.interval);
      };
    });
    app.controller("kokoonpano", function($scope, $http) {
      $http.get('https://rata.digitraffic.fi/api/v1/compositions/<?php echo $juna;?>?departure_date=<?php echo $pvm;?>').
      success(function(data, status, headers, config) {
        $scope.results = data;
      });
    });
app.controller("yhteydet", function($scope, $http, orderByFilter) {
	$http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=TKL&departed_trains=0&departing_trains=5&arriving_trains=0&arrived_trains=0').
    success(function(data, status, headers, config) {
      $scope.tkl = data;
      $scope.sortedtkl = orderByFilter($scope.tkl, '+scheduledTime');
      console.log($scope.sortedtkl);
      });
	$http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=HPL&departed_trains=0&departing_trains=5&arriving_trains=0&arrived_trains=0').
    success(function(data, status, headers, config) {
      $scope.hpl = data;
      $scope.sortedhpl = orderByFilter($scope.hpl, '+scheduledTime');
      console.log($scope.sortedhpl);
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
</head>

<body ng-app="aikataulu">

    <?php include 'incs/nav.php';?>
  <div class="col-md-6" ng-controller="liveTrains">
    <!--<h2 ng-show="!(rows | filter:{'trainNumber':<?php echo $juna;?>}: true).length">Junaa ei löydy! <small class="text-muted">Tarkista junanumero.<br> Junan tietoja ei näytetä, mikäli juna lähtee vasta yli vuorokauden kuluttua.</small></h2>-->
    <div ng-repeat="x in rows | filter:{'trainNumber':<?php echo $juna;?>}: true">
	    <a href="historia.php?pvm=<?php echo $eilen;?>&n=<?php echo $juna;?>" class="btn btn-info-outline">Edellinen päivä</a>
      <h2>
        <span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
        <span class="label line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}} 
        <small class="text-muted">
          <span ng-repeat="y in x.timeTableRows | limitTo:1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span>
          </span> -
          <span ng-repeat="y in x.timeTableRows | limitTo:-1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span>
        </small>
      </h2>
      <h4>
        <span ng-show="{{x.runningCurrently}}"><span class="label label-success">Kulussa</span>
          <span ng-repeat="section in sections | limitTo:-1">
	    <span ng-repeat="station in stations | filter:{'stationShortCode':section.station}: true | limitTo:1">{{station.stationName}}</span> {{section.trackSection}}
	    </span>
          </span>
        <span ng-show="{{x.cancelled}}" class="label label-danger">Peruttu</span>
      </h4>
      <table class="table table-striped table-sm">
        <thead class="vihreepohja">
          <tr>
            <td>Asema</td>
            <td>Lähtöaika</td>
            <td>Toteutunut</td>
            <td class="text-center">Raide</td>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="y in x.timeTableRows | filter:{'type':'DEPARTURE'}: true | filter:{'commercialStop':true}: true" autoscroll="false" class="tot{{y.actualTime | date:'HH'}}">
            <td><a href="asema.php?as={{y.stationShortCode}}"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></a>
			<span ng-show="x.trainCategory=='Long-distance'">

<ul ng-show="y.stationShortCode=='TKL'" ng-controller="yhteydet">
	<li ng-repeat="x in tkl | orderBy:'scheduledTime' | filter:'cancelled':false" ng-init="x.scheduledTime = (x.timeTableRows | filter:{stationShortCode:'TKL', 'type':'DEPARTURE'})[0].scheduledTime">
		{{x.commuterLineID}} <span ng-repeat="y in x.timeTableRows | limitTo:-1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span> {{x.scheduledTime | date:'HH:mm'}} <span ng-repeat="y in x.timeTableRows | filter:{'stationShortCode':'TKL', 'type':'DEPARTURE'}: true | limitTo:1">{{y.commercialTrack}}</span>
	</li>
</ul>

				<ul ng-show="y.stationShortCode=='TKL'">
					<li>K, N -> Ke R.5</li>
					<li>I -> Len R.4</li>
					<li>P, K, N -> Pla R.6</li>
				</ul>
				<ul ng-show="y.stationShortCode=='LPV'">
					<li>A -> Hki, L -> Kkn R.4</li>
					<li>E, U, X, Y -> Hpl R.2</li>
					<li>E, U, X, Y -> Klh & Kkn R.1</li>
				</ul>
			</span>
            </td>
            <td>{{y.scheduledTime | date:'H:mm'}}</td>
            <td>{{y.actualTime | date:'H:mm:ss'}}&nbsp;&nbsp;
              <span class="label label-default pull-right dif{{y.differenceInMinutes}}">{{y.differenceInMinutes}}</span>
              <span class="label label-default cause{{y.causes[0].categoryCode}}">{{y.causes[0].detailedCategoryCode}}</span>
            </td>
            <td class="text-center">{{y.commercialTrack}}</td>
          </tr>
          <tr ng-repeat="y in x.timeTableRows | limitTo:-1" class="tot{{y.actualTime | date:'HH'}}">
            <td><a href="asema.php?as={{y.stationShortCode}}"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></a>
            </td>
            <td>{{y.scheduledTime | date:'H:mm'}}</td>
            <td>{{y.actualTime | date:'H:mm:ss'}} <span class="label label-default pull-right dif{{y.differenceInMinutes}}">{{y.differenceInMinutes}}</span>
            </td>
            <td class="text-center">{{y.commercialTrack}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-6" ng-controller="liveTrains">
      <div ng-repeat="x in rows">
    <ul class="list-group" ng-repeat="y in x.timeTableRows">
      <li class="list-group-item" ng-repeat="cause in y.causes">
        <span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span>:
        <span ng-repeat="class in causes | filter:{'categoryCode':cause.categoryCode}: true">{{class.categoryName}}</span> 
          <small ng-show="cause.detailedCategoryCode">(<span ng-repeat="name in detailedcauses | filter:{'detailedCategoryCode':cause.detailedCategoryCode}: true | limitTo:1">{{name.detailedCategoryName}}</span>)</small>
      </li>
    </ul>
          </div>
  </div>
<div ng-controller="kokoonpano" class="col-md-6">
	<div ng-show="results.trainType=='HL'">
	<ul class="list-group" ng-repeat="x in results.journeySections">
		<li class="list-group-item" ng-repeat="y in x.locomotives | limitTo:-1">{{y.location}} {{y.locomotiveType}}</li>
	</ul>
	</div>
	<div ng-hide="results.trainType=='HL'">
	<ul class="list-group" ng-repeat="x in results.journeySections">
	<li class="list-group-item" ng-hide="x.length=='4'">Kokoonpano {{x.beginTimeTableRow.stationShortCode}}-{{x.endTimeTableRow.stationShortCode}}</li>
		<li class="list-group-item" ng-repeat="y in x.locomotives">{{y.locomotiveType}}</li>
		<li class="list-group-item" ng-repeat="y in x.wagons">{{y.location}} {{y.wagonType}} 
		<i ng-show="y.catering" class="fa fa-cutlery"></i>
		<i ng-show="y.pet" class="fa fa-paw"></i>
		<i ng-show="y.luggage" class="fa fa-suitcase"></i>
		<i ng-show="y.disabled" class="fa fa-wheelchair"></i>
		<i ng-show="y.playground" class="fa fa-gamepad"></i>
		<i ng-show="y.wagonType=='Edm'" class="fa fa-bed"></i>
		<i ng-show="y.wagonType=='CEmt'" class="fa fa-bed"></i>
		<i ng-show="y.wagonType=='Gd'" class="fa fa-car"></i>
		<i ng-show="y.wagonType=='Gfot'" class="fa fa-car"></i>
		</li>
	</ul>
	</div>
</div>
  </div>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
</body>

</html>
