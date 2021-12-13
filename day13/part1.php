<?php

function printCoords($coordRows, $width, $height) {
    for($i = 0; $i < $height; $i++) {
        for($j = 0; $j < $width; $j++) {
            echo $coordRows[$i][$j];
        }
        echo "\n";
    }
    echo "\n";
}

function countDots($coordRows, $width, $height) {
    $dotsCount = 0;
    for($i = 0; $i < $height; $i++) {
        for($j = 0; $j < $width; $j++) {
            if($coordRows[$i][$j] == '#') {
                $dotsCount++;
            }
        }
    }
    return $dotsCount;
}

function processInput($inputFilename, $folds, $printCoords)
{
    $input = file_get_contents($inputFilename);
    $inputLines = explode(PHP_EOL, $input);
    $inputLinesCount = count($inputLines);
    for ($i = 0; $i < $inputLinesCount; $i++) {
        if ($inputLines[$i] == '') {
            $coordsSeparatorIndex = $i;
            break;
        }
    }
    $maxX = 0;
    $maxY = 0;
    for ($i = 0; $i < $coordsSeparatorIndex; $i++) {
        [$x, $y] = explode(',', $inputLines[$i]);
        if ($x > $maxX) {
            $maxX = $x;
        }
        if($y > $maxY) {
            $maxY = $y;
        }
    }
    $width = $maxX + 1;
    $height = $maxY + 1;
    echo "max X = $maxX, max y = $maxY, size = $width x $height\n";
    $coordRows = array();
    for($i = 0; $i < $height; $i ++) {
        $coordRows[$i] = array_fill(0, $width, '.');
    }
    for ($i = 0; $i < $coordsSeparatorIndex; $i++) {
        [$x, $y] = explode(',', $inputLines[$i]);
        $coordRows[$y][$x] = '#';
    }
    if($printCoords) {
        printCoords($coordRows, $width, $height);
    }
    $lastFoldIndex = $coordsSeparatorIndex + 1 + $folds;
    for($i = $coordsSeparatorIndex+1, $foldNo = 1; $i < $lastFoldIndex; $i++, $foldNo++) {
        preg_match('/fold along (x|y)=(\d+)/', $inputLines[$i], $output);
        $foldType = $output[1];
        $foldLine = $output[2];
        switch($foldType) {
            case 'x':
                for($j = 0; $j < $height; $j++) {
                    $coordRows[$j][$foldLine] = '|';
                }
                if($printCoords) {
                    printCoords($coordRows, $width, $height);
                }
                $columnsRight = $width - $foldLine - 1;
                if($printCoords) {
                    echo "cols right $columnsRight\n";
                }
                //badaj każdą kolumną na prawo od linii zgięcia
                for($j = 0; $j < $columnsRight; $j++) {
                    //jedź wzłóż kolumny
                    for($k = 0; $k < $height; $k++) {
                        $coordRows[$k][$foldLine - 1 - $j] = (($coordRows[$k][$foldLine - 1 - $j] == '#') || ($coordRows[$k][$foldLine + 1 + $j] == '#')) ? '#' : '.';
                    }
                }
                $width = $foldLine;
                break;
            case 'y':
                for($j = 0; $j < $width; $j++) {
                    $coordRows[$foldLine][$j] = '-';
                }
                if($printCoords) {
                    printCoords($coordRows, $width, $height);
                }
                $rowsUnder = $height - $foldLine - 1;
                if($printCoords) {
                    echo "rows under $rowsUnder\n";
                }
                for($j = 0; $j < $rowsUnder; $j++) {
                    for($k = 0; $k < $width; $k++) {
                        $coordRows[$foldLine - 1 - $j][$k] = (($coordRows[$foldLine - 1 - $j][$k] == '#') || ($coordRows[$foldLine + 1 + $j][$k] == '#')) ? '#' : '.';
                    }
                }
                $height = $foldLine;
                break;
        }
        if($printCoords) {
            printCoords($coordRows, $width, $height);
        }
        $dotsCount = countDots($coordRows, $width, $height);
        echo "fold: $foldNo, dots count $dotsCount\n";
    }
}

//processInput('test-input.txt', 1, true);
processInput('input.txt', 1, false);