<?php

include 'helpers.php';

function d5p1(array $intCode)
{
    return runIntcodeInstructions($intCode, [ 1 ]);
}

function d5p2(array $intCode)
{
    return runIntcodeInstructions($intCode, [ 5 ]);
}

$intCode = array_map(fn($n) => intval($n), (array)explode(',', getDayInput(5)));

echo d5p1($intCode) . PHP_EOL;
echo d5p2($intCode) . PHP_EOL;