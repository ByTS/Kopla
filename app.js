var app = angular.module("kopla", []);
app.controller('kello', function($scope, $interval) {
  var tick = function() {
    $scope.kello = Date.now();
  }
  tick();
  $interval(tick, 1000);
});
app.controller("etusivu", function ($scope, $http) {
    $http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=HKI&departed_trains=0&departing_trains=40&arriving_trains=0&arrived_trains=0').
		success(function (data, status, headers, config) {
		    $scope.departures = data;
		});
		$http.get('stations.json').
      success(function(data, status, headers, config) {
        $scope.stations = data;
        });
		
    this.interval = setInterval(function () {
        $http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=HKI&departed_trains=0&departing_trains=40&arriving_trains=0&arrived_trains=0').
		success(function (data, status, headers, config) {
		    $scope.departures = data;
		});
    }, 20000);
    this.endLongPolling = function () { clearInterval(this.interval); };
    $http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=HKI&departed_trains=0&departing_trains=0&arriving_trains=40&arrived_trains=0').
		success(function (data, status, headers, config) {
		    $scope.arrivals = data;
		});
    this.interval = setInterval(function () {
        $http.get('https://rata.digitraffic.fi/api/v1/live-trains?station=HKI&departed_trains=0&departing_trains=0&arriving_trains=80&arrived_trains=0').
		success(function (data, status, headers, config) {
		    $scope.arrivals = data;
		});
    }, 20000);
    this.endLongPolling = function () { clearInterval(this.interval); };
});
app.controller("asemanimet", function($scope, $http) {
	$http.get('https://rata.digitraffic.fi/api/v1/metadata/stations').
	success(function(data, status, headers, config) {
		$scope.stations = data;
		});
		});
app.controller("kaikki", function($scope, $http) {
	$http.get('https://rata.digitraffic.fi/api/v1/live-trains').
	success(function(data, status, headers, config) {
		$scope.trains = data;
		});
		});
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-66075537-1', 'auto');
  ga('send', 'pageview');