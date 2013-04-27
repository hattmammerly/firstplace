<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="180" />
<link rel="stylesheet" type="text/css" href="opr.css">
<title>Calculated Average Score</title>
</head>
<body>

<?php

include 'opr.php';

if(isset($_GET['event']))
{
    $event = strtoupper($_GET['event']);
    showTables($event);
} else
{
    echo "<p>supply an event code in the url as shown below to see opr data for that event.<br />
          <a href='?event=MIKET'>http://whereveryouhostthis/index.php?event=MIKET</a></p>
          <p>event codes are listed at <a href='http://frclinks.frclinks.com/'>http://frclinks.frclinks.com/</a>";
}


function printArray($iter)
{
    echo '<table border=0>';
    echo "<tr><th></th>";
    foreach($iter as $team=>$val)
    {
        echo "<th class=\"col\">$team</th>";
    }
    echo '<th style="text-align:left">score</th></tr>';
    foreach($iter as $key=>$row)
    {
        echo "<tr><th class=\"row\">$key</th>";
        foreach($row as $v)
        {
            echo "<td class=\"row\">$v</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}

function showTables($event)
{
    echo "<h6 style=\"text-align:center\">$event</h6>";

    getstats("$event");

    $opr = file_get_contents('opr.json');
    $matchdata = file_get_contents('matchdata.json');

    $opr = json_decode($opr);
    $matchdata = json_decode($matchdata);

    echo '<p>opr</p>';
    printArray($opr);
    echo '<hr>';
    printArray($matchdata);
    echo '<hr>';
    echo "<h6 style='text-align:center;'>match schedule</h6><iframe src='http://www2.usfirst.org/2013comp/Events/$event/matchresults.html' style='width:100%;height:600px;'></iframe>";
}

?>

</body>
