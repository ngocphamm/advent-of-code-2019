<?php

function getDayInputByLine(int $day)
{
    $dayWithZero = str_pad($day, 2, '0', STR_PAD_LEFT);

    $file = fopen("input/{$dayWithZero}.txt", 'r');

    try {
        while ($line = fgets($file)) {
            yield $line;
        }
    } finally {
        fclose($file);
    }
}

function getDayInput(int $day)
{
    $dayWithZero = str_pad($day, 2, '0', STR_PAD_LEFT);

    return trim(file_get_contents("input/{$dayWithZero}.txt"));
}

function runIntcodeInstructions(array $intCode, array $inputValues)
{
    $i = 0;
    $relativeBase = 0;
    $instruction = strval($intCode[$i]);

    while (true) {
        $opCode = intval(substr($instruction, -2));
        $param1Mode = strlen($instruction) > 2 ? substr($instruction, -3, 1) : 0;
        $param2Mode = strlen($instruction) > 3 ? substr($instruction, -4, 1) : 0;

        switch ($opCode) {
            case 1:
                // Add instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1, $relativeBase) + getParamValue($intCode, $param2Mode, $i + 2, $relativeBase);
                $i += 4;
                break;

            case 2:
                // Multiply instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1, $relativeBase) * getParamValue($intCode, $param2Mode, $i + 2, $relativeBase);
                $i += 4;
                break;

            case 3:
                // Input instruction
                $param1Mode = strlen($instruction) > 2 ? substr($instruction, -3, 1) : 0;
                $intCode[getParamIndex($intCode, $param1Mode, $i + 1, $relativeBase)] = array_shift($inputValues);
                $i += 2;
                break;

            case 4:
                // Output instruction
                $param1Mode = strlen($instruction) > 2 ? substr($instruction, -3, 1) : 0;
                $diagCode = getParamValue($intCode, $param1Mode, $i + 1, $relativeBase);
                $i += 2;
                break;

            case 5:
                // Jump-if-true instruction
                if (getParamValue($intCode, $param1Mode, $i + 1, $relativeBase) !== 0) {
                    $i = getParamValue($intCode, $param2Mode, $i + 2, $relativeBase);
                } else {
                    $i += 3;
                }
                break;

            case 6:
                // Jump-if-false instruction
                if (getParamValue($intCode, $param1Mode, $i + 1, $relativeBase) === 0) {
                    $i = getParamValue($intCode, $param2Mode, $i + 2, $relativeBase);
                } else {
                    $i += 3;
                }
                break;

            case 7:
                // Less than instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1, $relativeBase) < getParamValue($intCode, $param2Mode, $i + 2, $relativeBase) ? 1 : 0;
                $i += 4;
                break;

            case 8:
                // Equals than instruction
                $intCode[$intCode[$i + 3]] = getParamValue($intCode, $param1Mode, $i + 1, $relativeBase) === getParamValue($intCode, $param2Mode, $i + 2, $relativeBase) ? 1 : 0;
                $i += 4;
                break;

            case 9:
                // Relative base adjustment
                $relativeBase += getParamValue($intCode, $param1Mode, $i + 1, $relativeBase);
                $i += 2;
                break;

            case 99:
                // Halt instruction
                return $diagCode;
        }

        $instruction = strval($intCode[$i]);
    }
}

function getParamValue(array $intCode, $mode, $index, $relativeBase)
{
    $mode = intval($mode);

    if ($mode === 0) {
        // Position mode
        return $intCode[$intCode[$index]];
    } else if ($mode === 1) {
        // Immediate mode
        return $intCode[$index];
    } else if ($mode === 2) {
        // Relative mode
        return $intCode[$intCode[$index]] + $relativeBase;
    }
}

function getParamIndex(array $intCode, $mode, $index, $relativeBase)
{
    $mode = intval($mode);

    if ($mode === 0) {
        // Position mode
        return $intCode[$index];
    } else if ($mode === 1) {
        // Immediate mode
        return $index;
    } else if ($mode === 2) {
        // Relative mode
        return $intCode[$index] + $relativeBase;
    }
}