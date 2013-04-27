<?php
error_reporting(1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function rref($matrix)
{
    $keys = array_keys($matrix);
    $matrix = array_values($matrix);
    $lead = 0;
    $rowCount = count($matrix);
    if ($rowCount == 0)
        return $matrix;
    $columnCount = 0;
    if (isset($matrix[0])) {
        $columnCount = count($matrix[0]);
    }
    for ($r = 0; $r < $rowCount; $r++) {
        if ($lead >= $columnCount)
            break;        {
            $i = $r;
            while ($matrix[$i][$lead] == 0) {
                $i++;
                if ($i == $rowCount) {
                    $i = $r;
                    $lead++;
                    if ($lead == $columnCount)
                        return $matrix;
                }
            }
            $temp = $matrix[$r];
            $matrix[$r] = $matrix[$i];
            $matrix[$i] = $temp;
        }        {
            $lv = $matrix[$r][$lead];
            for ($j = 0; $j < $columnCount; $j++) {
                $matrix[$r][$j] = $matrix[$r][$j] / $lv;
            }
        }
        for ($i = 0; $i < $rowCount; $i++) {
            if ($i != $r) {
                $lv = $matrix[$i][$lead];
                for ($j = 0; $j < $columnCount; $j++) {
                    $matrix[$i][$j] -= $lv * $matrix[$r][$j];
                }
            }
        }
        $lead++;
    }
    $matrix = array_combine($keys, $matrix);
    return $matrix;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function scrap($iter)
{
    foreach($iter as $key=>$val)
    {
        echo "$key, $val<br />";
    }
    foreach($iter as $key=>$val)
    {
        echo "::$key<br />";
        $s = $val["score"];
        $x = count($val["playedWith"]);
        echo "::::score, $s. playedWith $x<br />";

        foreach($val["playedWith"] as $k=>$v)
        {
            echo "::::$k, $v <br />";
        }
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function read($event)
{
    $html = file_get_contents("http://www2.usfirst.org/2013comp/events/$event/matchresults.html");
    $html = strstr($html, 'Qualification Matches');
    $html = array_shift(explode('</table>', $html));
    
    $rows = array();
    $html = strip_tags($html);
    $html = strstr($html, "M");
    $rows = array();
    $rows = explode('M',$html);
    unset($rows[0]);
    unset($rows[1]);
    unset($rows[2]);
    $rows = array_values($rows);
    $matches = array();
    foreach($rows as $match)
    {
        $row = array();
        $row = preg_split('[\D]',$match);
        unset($row[0]);
        for ($i = 1; $i < count($row); $i = $i + 2)
        {
            unset($row[$i]);
        }
        $row = array_splice($row,0,9);
        $row = array_values($row);
        $m = array("red1"=>$row[1],"red2"=>$row[2],"red3"=>$row[3],"blu1"=>$row[4],"blu2"=>$row[5],"blu3"=>$row[6],"Red Score"=>$row[7],"Blue Score"=>$row[8]);
        $matches[] = $m;
    }

    $i = count($matches) - 1;
    while ($i >= 0)
    {
        if (isset($matches[$i][7])) break;
        $i = $i - 1;
    }
    $matches = array_splice($matches, 0, $i);

    return $matches;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function genMatrix($array)
{
    $scores = array();
    foreach($array as $row)
    {
        $red1 = $row['red1'];
        $red2 = $row['red2'];
        $red3 = $row['red3'];
        $blu1 = $row['blu1'];
        $blu2 = $row['blu2'];
        $blu3 = $row['blu3'];
        $RS = $row['Red Score'];
        $BS = $row['Blue Score'];


        $scores = updateMtrx($scores,$red1,$red2,$red3,$RS);
        $scores = updateMtrx($scores,$red2,$red1,$red3,$RS);
        $scores = updateMtrx($scores,$red3,$red2,$red1,$RS);
        $scores = updateMtrx($scores,$blu1,$blu2,$blu3,$BS);
        $scores = updateMtrx($scores,$blu2,$blu1,$blu3,$BS);
        $scores = updateMtrx($scores,$blu3,$blu2,$blu1,$BS);
    }

    foreach($scores as $team=>$data)
    {
        foreach ($scores as $key=>$value)
        {
            if (!isset($scores["$team"]["playedWith"]["$key"]))
                $scores["$team"]["playedWith"]["$key"] = 0;
        }
    }

    $matrix = array();

    foreach($scores as $team=>$data)
    {
        $r = array();
        
        foreach($scores as $t=>$d)
        {
            $r[] = $scores["$team"]["playedWith"]["$t"];
        }
        $r[] = $data["score"];
        $matrix["$team"] = $r;
    }

    return $matrix;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function updateMtrx($matrix,$t1,$t2,$t3,$score)
{
    if(isset($matrix["$t1"])) {
        $matrix["$t1"]["score"] += $score;
        if(isset($matrix["$t1"]["playedWith"]["$t1"]))
        {
            $matrix["$t1"]["playedWith"]["$t1"] += 1; 
        } else
        {
            $matrix["$t1"]["playedWith"]["$t1"] = 1;
        }
        if(isset($matrix["$t1"]["playedWith"]["$t2"]))
        {
            $matrix["$t1"]["playedWith"]["$t2"] += 1; 
        } else
        {
            $matrix["$t1"]["playedWith"]["$t2"] = 1;
        }
        if(isset($matrix["$t1"]["playedWith"]["$t3"]))
        {
            $matrix["$t1"]["playedWith"]["$t3"] += 1; 
        } else
        {
            $matrix["$t1"]["playedWith"]["$t3"] = 1;
        }
        
        
    }
    else
    {
        $matrix["$t1"] = array(
                                    "score"=>$score,
                                    "playedWith"=>array(
                                                    "$t1"=>1,
                                                    "$t2"=>1,
                                                    "$t3"=>1));
    }
    return $matrix;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getstats($event) {
    $data = read("$event");

    $matrix = genMatrix($data);


    $json = json_encode($matrix);

    $file = fopen('matchdata.json','w') or die('failed to open or matchdata.json');
    fwrite($file, $json);
    fclose($file);

    echo '<br /><br />';
    $matrix = rref($matrix);
    $json = json_encode($matrix);
    $file = fopen('opr.json','w') or die('failed to open or create opr.json');
    fwrite($file, $json);
    fclose($file);
}
?>
