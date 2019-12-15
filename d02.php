<?php

include 'helpers.php';

function runInstructions(array $intCode, int $rep1, int $rep2)
{
    $intCode[1] = $rep1;
    $intCode[2] = $rep2;

    $i = 0;
    $opCode = $intCode[$i];

    while (true) {
        switch ($opCode) {
            case 1:
                $intCode[$intCode[$i + 3]] = $intCode[$intCode[$i + 1]] + $intCode[$intCode[$i + 2]];
                break;
            case 2:
                $intCode[$intCode[$i + 3]] = $intCode[$intCode[$i + 1]] * $intCode[$intCode[$i + 2]];
                break;
            case 99:
                return $intCode[0];
        }

        $i += 4;
        $opCode = $intCode[$i];
    }
}

function d2p1(array $intCode)
{
    return runInstructions($intCode, 12, 2);
}

function d2p2(array $intCode)
{
    for ($i = 0; $i <= 99; $i++) {
        for ($j = 0; $j <= 99; $j++) {
            if (runInstructions($intCode, $i, $j) === 19690720){
                return 100 * $i + $j;
            }
        }
    }
}

$intCode = array_map(fn($n) => intval($n), (array)explode(',', getDayInput(2)));

echo d2p1($intCode) . PHP_EOL;
echo d2p2($intCode) . PHP_EOL;