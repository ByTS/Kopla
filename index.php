<!DOCTYPE html>
<html lang="fi" ng-app="kopla">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="KOPLA on sovellus junaliikenteen aikataulujen tarkasteluun.">
    <meta name="keywords" content="KOPLA,juna,VR,Fenniarail,aikataulu,junaliikenne,täsmällisyys, raideliikenne, aikataulut">
    <meta name="author" content="Tuomas Savela">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="57x57" href="icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
    <link rel="manifest" href="icons/manifest.json">
    <title>KOPLA</title>
    <link href='https://fonts.googleapis.com/css?family=Exo:400,100,200,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.1/angular.min.js" type="text/javascript"></script>
    <script src="app.js" type="text/javascript"></script>
  </head>
  <body>
    <?php include 'incs/nav.php';?>
    <div class="container-fluid">
      <div class="row container">
      <div class="alert alert-info">Moi. Uhraisitko pari minuuttia <a href="https://fi.surveymonkey.com/r/KD5HDNT"><strong>käyttäjäkyselyyn</strong></a> KOPLAsta?</div>
        <h2>Helsinki Asema</h2>
      </div>
      <div class="row" ng-controller="etusivu">
        <div class="col-md-6">
          <h4>Lähtevät</h4>
          <table class="table table-striped table-sm">
            <thead class="vihreepohja">
              <th>Numero</th>
              <th>Määränpää</th>
              <th>Lähtöaika</th>
              <th>
                <center>Raide</center>
              </th>
            </thead>
            <tbody>
              <tr ng-repeat="x in departures | filter:{'cancelled':false}: true | filter:{'trainCategory':'Long-distance'}: true | orderBy:'timeTableRows[0].scheduledTime':false | limitTo:5">
                <td>
                  <a ng-href="aikataulu.php?n={{x.trainNumber}}">
                  <span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
                  <span class="label label-primary line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}}</a>
                </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:-1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span>
	            </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:1" class="min-{{y.scheduledTime | date:'HH:mm'}}">{{y.scheduledTime | date:'HH:mm'}}</span>
	            </td>
                <td>
                  <span ng-repeat="y in x.timeTableRows | limitTo:1">
                  <center>{{y.commercialTrack}}</center>
                  </span>
                </td>
              </tr>
              <tr ng-repeat="x in departures | filter:{'cancelled':false}: true | filter:{'trainCategory':'Commuter'}: true | orderBy:'timeTableRows[0].scheduledTime':false | limitTo:10">
                <td>
                  <a href="aikataulu.php?n={{x.trainNumber}}">
                  <span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
                  <span class="label label-primary line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}}</a>
                </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:-1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span>
	            </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:1" class="min-{{y.scheduledTime | date:'HH:mm'}}">{{y.scheduledTime | date:'HH:mm'}}</span>
	            </td>
                <td>
                  <span ng-repeat="y in x.timeTableRows | limitTo:1">
                  <center>{{y.commercialTrack}}</center>
                  </span>
                </td>
              </tr>
            </tbody>
            <thead class="vihreepohja" ng-show="(departures | filter:{'cancelled':true}: true).length">
              <th>Perutut</th>
              <th>Määränpää</th>
              <th>Lähtöaika</th>
              <th></th>
            </thead>
            <tbody>
            <tr ng-repeat="x in departures | filter:{'cancelled':true}: true | orderBy:'timeTableRows[0].scheduledTime':false | limitTo:10">
              <td>
                <a href="aikataulu.php?n={{x.trainNumber}}">
                <span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
                <span class="label label-primary line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}}</a>
              </td>
              <td>
	            <span ng-repeat="y in x.timeTableRows | limitTo:-1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span>
	          </td>
              <td>
	            <span ng-repeat="y in x.timeTableRows | limitTo:1" class="min-{{y.scheduledTime | date:'HH:mm'}}">{{y.scheduledTime | date:'HH:mm'}}</span>
	          </td>
              <td>
	            <span class="show-{{x.cancelled}} label label-danger">Peruttu</span>
	            <span ng-repeat="y in x.timeTableRows | limitTo:1">{{y.commercialTrack}}</span>
	          </td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
          <h4>Saapuvat (Kaukoliikenne)</h4>
          <table class="table table-striped table-sm">
            <thead class="vihreepohja">
              <th>Numero</th>
              <th>Lähtöasema</th>
              <th>Tuloaika</th>
              <th>Arvioitu</th>
              <th>
                <center>Raide</center>
              </th>
            </thead>
            <tbody>
              <tr ng-repeat="x in arrivals | filter:{'cancelled':false}: true | filter:{'trainCategory':'Long-distance'}: true | limitTo:15">
                <td>
                  <a href="aikataulu.php?n={{x.trainNumber}}">
                  <span class="label label-success type-{{x.trainType}}">{{x.trainType}}</span>
                  <span class="label label-primary line-{{x.commuterLineID}}">{{x.commuterLineID}}</span> {{x.trainNumber}}</a>
                </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:1"><span ng-repeat="station in stations | filter:{'stationShortCode':y.stationShortCode}: true">{{station.stationName}}</span></span>
	            </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:-1">{{y.scheduledTime | date:'HH:mm'}}</span>
	            </td>
                <td>
	              <span ng-repeat="y in x.timeTableRows | limitTo:-1">{{y.liveEstimateTime | date:'HH:mm'}}</span>
	            </td>
                <td>
                  <span ng-repeat="y in x.timeTableRows | limitTo:-1">
                  	<center>{{y.commercialTrack}}</center>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><hr>
      <div class="row">
      <div class="col-md-12">
        <div class="alert alert-info hidden" role="alert">Moi. Uhraisitko pari minuuttia <a href="https://fi.surveymonkey.com/r/KD5HDNT"><strong>käyttäjäkyselyyn</strong></a> KOPLAsta?</div>
        <p>KOPLA on junaliikenteen aikataulujen tarkasteluun suunniteltu sovellus. KOPLA näyttää reaaliaikaisesti junan aikataulun, toteutuneen aikataulun sekä kokoonpanon.</p>
        <p>Voit hakea yläpalkin hakutoiminnolla tiettyä junaa tai asemaa. Haku hyväksyy junien osalta junanumeron ilman kirjaintunnuksia, liikennepaikkojen osalta virallisen nimen, yleistyneen (tai ei-niin yleistyneen) lempinimen tai lyhenteen.</p>
        <p>Huomioithan, että rautateillä lyhenteet eroavat joiltakin osin yleisistä lyhenteistä, esimerkiksi Tampere tunnetaan lyhenteellä <code>TPE</code>, eikä <code>TRE</code>. Helpotuksen vuoksi kone ymmärtää myös Mansesterit sun muut, rakkaalla lapsellahan on monta nimeä.</p>
        <p>Otan mielelläni palautetta vastaan, ensisijaisesti <a href="http://www.facebook.com/KOPLAapp">KOPLAn Facebook-sivun</a> kautta, myös sähköpostitse tavoittaa tehokkaasti. Tavoitteena kuitenkin on toteuttaa työtä sekä matkustamista helpottava sovellus!</p>
        <div class="alert alert-danger" role="alert">KOPLA ei tarjoa vahvistettua aikataulua, joten sitä EI saa käyttää JT-tehtävissä!</div>
        <div class="alert alert-info">
          Liikennetiedot <strong><a href="http://www.liikennevirasto.fi">Liikennevirasto</a></strong> / Digitraffic <a href="http://creativecommons.org/licenses/by/4.0/">CC 4.0 BY</a>
        </div>
      </div>
      <div class="col-md-6 hidden">
        <form action="historia.php" method="get">
          <div class="input-group">
            <span class="input-group-addon" id="sizing-addon1">Historia</span>
            <input type="text" class="form-control" placeholder="junanro" name="n">
            <input type="text" class="form-control" placeholder="vvvv-kk-pp" name="pvm">
            <span class="input-group-btn">
            <button class="btn btn-secondary btn-lg" type="submit" type="button">Hae</button>
            </span>
          </div>
        </form>
        <br>
      </div>
    </div>
  </body>
</html>