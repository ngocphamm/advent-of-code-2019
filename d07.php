<?php

include 'helpers.php';

function runInstructions(array &$intCode, array $inputs, bool $returnOnOuput = false, bool &$halted = false)
{
    $i = 0;
    $output = [];
    $instruction = strval($intCode[$i]);

    while (true) {
        $opCode = intval(substr($instruction, -2));
        $param1Mode = strlen($instruction) > 2 ? substr($instruction, -3, 1) : 0;
        $param2Mode = strlen($instruction) > 3 ? substr($instruction, -4, 1) : 0;

        switch ($opCode) {
            case 1:
                // Add instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1) + getParamValue($intCode, $param2Mode, $i + 2);
                $i += 4;
                break;

            case 2:
                // Multiply instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1) * getParamValue($intCode, $param2Mode, $i + 2);
                $i += 4;
                break;

            case 3:
                // Input instruction
                $param1Mode = strlen($instruction) > 2 ? substr($instruction, -3, 1) : 0;
                $intCode[intval($param1Mode) === 0 ? $intCode[$i + 1] : $i + 1] = array_shift($inputs);
                $i += 2;
                break;

            case 4:
                // Output instruction
                $param1Mode = strlen($instruction) > 2 ? substr($instruction, -3, 1) : 0;

                if (!$returnOnOuput) {
                    array_push($output, getParamValue($intCode, $param1Mode, $i + 1));
                    $i += 2;
                    break;
                } else {
                    return getParamValue($intCode, $param1Mode, $i + 1);
                }

            case 5:
                // Jump-if-true instruction
                if (getParamValue($intCode, $param1Mode, $i + 1) !== 0) {
                    $i = getParamValue($intCode, $param2Mode, $i + 2);
                } else {
                    $i += 3;
                }
                break;

            case 6:
                // Jump-if-false instruction
                if (getParamValue($intCode, $param1Mode, $i + 1) === 0) {
                    $i = getParamValue($intCode, $param2Mode, $i + 2);
                } else {
                    $i += 3;
                }
                break;

            case 7:
                // Less than instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1) < getParamValue($intCode, $param2Mode, $i + 2) ? 1 : 0;
                $i += 4;
                break;

            case 8:
                // Equals than instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1) === getParamValue($intCode, $param2Mode, $i + 2) ? 1 : 0;
                $i += 4;
                break;

            case 99:
                // Halt instruction
                if ($returnOnOuput) $halted = true;

                return $output;
        }

        $instruction = strval($intCode[$i]);
    }
}

// Get all permutations of the phase setting sequence
// https://stackoverflow.com/a/36768114/514015
function getPhaseSettingSequences($items, $perms = [], &$ret = [])
{
    if (empty($items)) {
        $ret[] = $perms;
    } else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newitems = $items;
            $newperms = $perms;
            list($foo) = array_splice($newitems, $i, 1);
            array_unshift($newperms, $foo);
            getPhaseSettingSequences($newitems, $newperms, $ret);
        }
    }

    return $ret;
}

function d7p1(array $intCode)
{
    $settingSequence = [0,1,2,3,4];
    $highestSignalToThrusters = 0;

    $sequences = getPhaseSettingSequences($settingSequence);

    foreach ($sequences as $sequence)
    {
        $input = 0;

        do {
            $tmpIntCode = $intCode; // PHP array is assigned by copy
            $ampInput = [array_shift($sequence), $input];

            $input = runIntCodeInstructions($tmpIntCode, $ampInput);
        } while (count($sequence) > 0);

        $highestSignalToThrusters = max($input, $highestSignalToThrusters);
    }

    return $highestSignalToThrusters;
}

function d7p2(array $intCode)
{
    $settingSequence = [5,6,7,8,9];
    $highestSignalToThrusters = 0;

    $sequences = getPhaseSettingSequences($settingSequence);

    foreach ($sequences as $sequence) {
        $nextInput = 0;
        $halted = false;

        do {
            $ampInput = [];

            if (count($sequence) > 0) {
                array_push($ampInput, array_shift($sequence));
            }

            array_push($ampInput, $nextInput);

            $nextInput = runInstructions($intCode, $ampInput, true, $halted);
        } while (!$halted);

        $highestSignalToThrusters = max($nextInput, $highestSignalToThrusters);
    }

    return $highestSignalToThrusters;
}

$intCode = array_map(fn ($n) => intval($n), (array) explode(',', getDayInput(7)));

// echo d7p1($intCode) . PHP_EOL;
echo d7p2($intCode) . PHP_EOL;