<?php

include 'helpers.php';

function isPasswordGoodP1(string $number)
{
    if (
        $number[0] > $number[1]
        || $number[1] > $number[2]
        || $number[2] > $number[3]
        || $number[3] > $number[4]
        || $number[4] > $number[5]
    ) {
        return false;
    }

    if (
        $number[0] === $number[1]
        || $number[1] === $number[2]
        || $number[2] === $number[3]
        || $number[3] === $number[4]
        || $number[4] === $number[5]
    ) {
        return true;
    }

    return false;
}

function isPasswordGoodP2(string $number)
{
    if (
        $number[0] > $number[1]
        || $number[1] > $number[2]
        || $number[2] > $number[3]
        || $number[3] > $number[4]
        || $number[4] > $number[5]
    ) {
        return false;
    }

    // Match 2 or more repeated digits
    preg_match_all('/(\d)\1+/', $number, $matches);

    if ($matches === 0) return false;

    // From the matches, check if there's one with only 2 digits. If yes that's a valid case.
    foreach ($matches[0] as $match) {
        if (strlen($match) === 2) return true;
    }

    return false;
}

function d4p1($range)
{
    $count = 0;
    $inputRange = explode('-', $range);

    foreach (range($inputRange[0], $inputRange[1]) as $number) {
        if (isPasswordGoodP1(strval($number))) $count++;
    }

    return $count;
}

function d4p2($range)
{
    $count = 0;
    $inputRange = explode('-', $range);

    foreach (range($inputRange[0], $inputRange[1]) as $number) {
        if (isPasswordGoodP2(strval($number))) $count++;
    }

    return $count;
}

$input = '246515-739105';

echo d4p1($input) . PHP_EOL;
echo d4p2($input) . PHP_EOL;