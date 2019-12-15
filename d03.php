<?php

include 'helpers.php';

function getPointsInPath($wiringDirections)
{
    $points = [];

    $startX = 0;
    $startY = 0;

    foreach ($wiringDirections as $wiring) {
        $dir = substr($wiring, 0, 1);
        $counter = intval(substr($wiring, 1));

        switch ($dir) {
            case 'U':
                while ($counter > 0) {
                    $startY--;
                    $counter--;
                    array_push($points, "{$startX}:{$startY}");
                }
                break;

            case 'R':
                while ($counter > 0) {
                    $startX++;
                    $counter--;
                    array_push($points, "{$startX}:{$startY}");
                }
                break;

            case 'D':
                while ($counter > 0) {
                    $startY++;
                    $counter--;
                    array_push($points, "{$startX}:{$startY}");
                }
                break;

            case 'L':
                while ($counter > 0) {
                    $startX--;
                    $counter--;
                    array_push($points, "{$startX}:{$startY}");
                }
                break;
        }
    }

    return $points;
}

function d3p1()
{
    $wirePaths = [];

    foreach (getDayInputByLine(3) as $wiringDirections) {
        $wirePaths[] = getPointsInPath(explode(',', $wiringDirections));
    }

    $intersections = array_intersect(...$wirePaths);

    $minManhattanDist = PHP_INT_MAX;

    foreach ($intersections as $intersection) {
        $parts = explode(':', $intersection);
        $minManhattanDist = min($minManhattanDist, abs($parts[0]) + abs($parts[1]));
    }

    return $minManhattanDist;
}

function d3p2()
{
    $wirePaths = [];

    foreach (getDayInputByLine(3) as $wiringDirections) {
        $wirePaths[] = getPointsInPath(explode(',', $wiringDirections));
    }

    $intersections = array_intersect(...$wirePaths);

    $minSteps = PHP_INT_MAX;

    foreach ($intersections as $intersection) {
        // Because we have an array of points for the path of each wire, the intersection
        // point's index in the array is the number of steps it takes to go from central
        // point to the intersection.
        // + 2 because indices start at 0.
        $minSteps = min($minSteps, array_search($intersection, $wirePaths[0]) + array_search($intersection, $wirePaths[1]) + 2);
    }

    return $minSteps;
}

echo d3p1() . PHP_EOL;
echo d3p2() . PHP_EOL;