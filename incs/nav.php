
<nav class="navbar navbar-fixed-top vihreepohja" ng-controller="kello">
  <a class="navbar-brand vihreepohja" href="http://byts.fi/kopla">KOPLA {{ kello | date:'HH:mm:ss'}}</a>
  <form class="form-inline pull-xs-right" role="search" action="aikataulu.php" method="get">
    <input class="form-control pull-xs-right" type="tel" name="n" placeholder="Haku...">
    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
  </form>
</nav>