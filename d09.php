<?php

include 'helpers.php';

function d9p1(array $intCode)
{
    // return runIntcodeInstructions($intCode, [ 1 ]);
    return runIntcodeInstructions([109, 1, 204, -1, 1001, 100, 1, 100, 1008, 100, 16, 101, 1006, 101, 0, 99], []);
}

$intCode = array_map(fn ($n) => intval($n), (array) explode(',', getDayInput(9)));

echo d9p1($intCode) . PHP_EOL;