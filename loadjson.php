<?php
header('Content-Type: text/json; charset=utf-8');
date_default_timezone_set('Europe/Helsinki');
$datetime = new DateTime('tomorrow');
$tmrw = $datetime->format('Y-m-d');
$link = "http://rata.digitraffic.fi/api/v1/schedules?departure_date=$tmrw";
print file_get_contents($link);
?>
