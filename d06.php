<?php

include 'helpers.php';

function d6p1()
{
    $nodes = [];

    foreach (getDayInputByLine(6) as $line) {
        $orbit = explode(')', trim($line));

        $nodes[$orbit[1]] = [
            'orbits' => $orbit[0]
        ];
    }

    $orbitCount = 0;

    foreach ($nodes as $node => $data) {
        if ($data['orbits'] === 'COM') {
            $orbitCount++;
        } else {
            while (true) {
                $orbitCount++;
                $data = $nodes[$data['orbits']];

                if ($data['orbits'] === 'COM') {
                    $orbitCount++;
                    break;
                }
            }
        }
    }

    return $orbitCount;
}

function getNodeLinkChain($nodes, $nodeName)
{
    $chain = [];
    $node = $nodes[$nodeName];

    if ($node['orbits'] === 'COM') {
        array_push($chain, 'COM');
    } else {
        while (true) {
            array_push($chain, $node['orbits']);
            $node = $nodes[$node['orbits']];

            if ($node['orbits'] === 'COM') {
                array_push($chain, 'COM');
                break;
            }
        }
    }

    return $chain;
}

function d6p2()
{
    $nodes = [];

    foreach (getDayInputByLine(6) as $line) {
        $orbit = explode(')', trim($line));

        $nodes[$orbit[1]] = [
            'orbits' => $orbit[0]
        ];
    }

    $youChain = getNodeLinkChain($nodes, 'YOU');
    $sanChain = getNodeLinkChain($nodes, 'SAN');

    $transferCount = 0;
    foreach ($youChain as $ycNode) {
        $transferCount++;

        if (in_array($ycNode, $sanChain)) {
            $transferCount += array_search($ycNode, $sanChain) - 1;
            break;
        }
    }

    return $transferCount;
}

echo d6p1() . PHP_EOL;
echo d6p2() . PHP_EOL;