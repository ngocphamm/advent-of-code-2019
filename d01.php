<?php

include 'helpers.php';

function getFuelForModule($moduleMass)
{
    return floor(intval($moduleMass)/3) - 2;
}

function d1p1()
{
    $sumFuel = 0;

    foreach (getDayInputByLine(1) as $n => $line) {
        $sumFuel += getFuelForModule($line);
    }

    return $sumFuel;
}

function d1p2()
{
    $sumFuel = 0;

    foreach (getDayInputByLine(1) as $n => $line) {
        $moduleFuel = $line;

        do {
            $moduleFuel = getFuelForModule($moduleFuel);

            if ($moduleFuel > 0) $sumFuel += $moduleFuel;
        } while ($moduleFuel > 0);
    }

    $sumFuel;
}

echo d1p1() . PHP_EOL;
echo d1p2() . PHP_EOL;