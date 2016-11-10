<nav class="navbar navbar-fixed-top vihreepohja" ng-controller="kello">
<a class="navbar-brand vihreepohja" href="https://byts.fi/kopla">KOPLA {{ kello | date:'HH:mm:ss'}}</a>
<form class="form-inline pull-xs-right pull-sm-right col-xs-6 col-sm-4" role="search" action="aikataulu.php" method="get">
<div class="input-group navbar-right">
<input type="tel" class="form-control" placeholder="Junanro" name="n">
<span class="input-group-btn">
<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
</span>
</div>
</form>
</nav>
