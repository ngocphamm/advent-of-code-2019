<?php

use PhpParser\Node\Stmt\Continue_;

include 'helpers.php';

function d8p1(int $wide, int $tall, array $input)
{
    $digitsPerLayer = $wide * $tall;

    $layers = array_chunk($input, $digitsPerLayer);

    $minZeros = $digitsPerLayer;
    $result = 0;

    foreach ($layers as $layer) {
        $arrayValueCounts = array_count_values($layer);

        if ($arrayValueCounts[0] > $minZeros) continue;

        $minZeros = $arrayValueCounts[0];
        $result = $arrayValueCounts[1] * $arrayValueCounts[2];
    }

    return $result;
}

function d8p2(int $wide, int $tall, array $input)
{
    $digitsPerLayer = $wide * $tall;
    $layerCount = count($input) / $digitsPerLayer;
    $colors = [];

    for ($i = 0; $i < $digitsPerLayer; $i++) {
        for ($j = 0; $j < $layerCount; $j++) {
            $layerColor = intval($input[$i + $j * $digitsPerLayer]);

            // Transparent, go to next layer
            if ($layerColor === 2) continue;

            array_push($colors, $layerColor);
            break;
        }
    }

    foreach (array_chunk($colors, $wide) as $row) {
        echo implode('', $row) . PHP_EOL;
    }
}

$wide = 25;
$tall = 6;
$input = str_split(getDayInput(8));

echo d8p1($wide, $tall, $input) . PHP_EOL;

d8p2($wide, $tall, $input);