<?php

$start = 100;
$percent = 3.1;
$days = 44;

//$start = 186;
//$percent = 2.9;
//$days = 50;

for($x = 1; $x<= $days; $x++)
{
    echo "    Day $x: ".round($start, 4)." Millicents \n";
    $percentOfStart = ($start * ($percent / 100) );
    echo "        $percent % of ".round($start, 4)." = ".round($percentOfStart, 4)." \n";
    $start = $start - $percentOfStart;
}
