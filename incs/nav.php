<nav class="navbar navbar-fixed-top vihreepohja" ng-controller="kello">
  <a class="navbar-brand vihreepohja" href="http://byts.fi/kopla">KOPLA</a>
  <span class="navbar-brand">{{ kello | date:'HH:mm:ss'}}</span>
    <form class="navbar-form" role="search" action="aikataulu.php" method="get">
      <div class="input-group col-xs-6 pull-right">
        <input class="form-control" type="number" name="n" placeholder="Haku...">
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </form>
  </div>
</nav>
