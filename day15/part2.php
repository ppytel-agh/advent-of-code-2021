<?php

function findLowestRiskPath($inputFilename, $print = false) {
    $inputText = file_get_contents($inputFilename);
    $inputLines = explode(PHP_EOL, $inputText);
    $rows = count($inputLines);
    $columns = strlen($inputLines[0]);
    $fullRows = 5 * $rows;
    $fullCols = 5 * $columns;
    echo "board size $fullRows x $fullCols \n";
    $riskValues = array();
    for($i = 0; $i < $rows; $i++) {
        $riskChars = str_split($inputLines[$i]);
        for($j = 0; $j < $columns; $j++) {
            $riskValues[$i][$j] = intval($riskChars[$j]);
        }
        for($j = $columns; $j < $fullCols; $j++) {
            $prevColIndex = $j - $columns;
            $prevValue = $riskValues[$i][$prevColIndex];
            $newValue = $prevValue + 1;
            if($newValue == 10) {
                $newValue = 1;
            }
            $riskValues[$i][$j] = $newValue;
        }
    }
    for($i = $rows; $i < $fullRows; $i++) {
        $prevRowIndex = $i - $rows;
        for($j = 0; $j < $fullCols; $j++) {
            $prevValue = $riskValues[$prevRowIndex][$j];
            $newValue = $prevValue + 1;
            if ($newValue == 10) {
                $newValue = 1;
            }
            $riskValues[$i][$j] = $newValue;
        }
    }
    if($print) {
        echo "BOARD\n";
        for($i = 0; $i < $fullRows; $i++) {
            for($j = 0; $j < $fullCols; $j++) {
                echo $riskValues[$i][$j];
            }
            echo "\n";
        }
    }
    $optimalRisks = array();
    $optimalPaths = array();
    for($i = 0; $i < $fullRows; $i++) {
        $optimalRisks[$i] = array();
        $optimalPaths[$i] = array();
        for($j = 0; $j < $fullCols; $j++) {
            if($i == 0) {
                if($j == 0) {
                    $optimalRisks[$i][$j] = 0;
                    $optimalPaths[$i][$j] = '.';
                } else {
                    $optimalRisks[$i][$j] = $optimalRisks[$i][$j-1] + $riskValues[$i][$j];
                    $optimalPaths[$i][$j] = '-';
                }
            } else {
                if($j == 0) {
                    $optimalRisks[$i][$j] = $optimalRisks[$i-1][$j] + $riskValues[$i][$j];
                    $optimalPaths[$i][$j] = '|';
                } else {
                    if($optimalRisks[$i-1][$j] < $optimalRisks[$i][$j-1]) {
                        $optimalRisks[$i][$j] = $optimalRisks[$i-1][$j] + $riskValues[$i][$j];
                        $optimalPaths[$i][$j] = '|';
                    } else {
                        $optimalRisks[$i][$j] = $optimalRisks[$i][$j-1] + $riskValues[$i][$j];
                        $optimalPaths[$i][$j] = '-';
                    }
                }
            }
        }
    }
    if($print) {
        echo "RISK VALUES\n";
        for($i = 0; $i < $fullRows; $i++) {
            for($j = 0; $j < $fullCols; $j++) {
                echo $optimalRisks[$i][$j] . ' ';
            }
            echo "\n";
        }
        echo "\n";
        echo "RISK PATHS\n";
        for($i = 0; $i < $fullRows; $i++) {
            for($j = 0; $j < $fullCols; $j++) {
                echo $optimalPaths[$i][$j];
            }
            echo "\n";
        }
        echo "\n";
    }
    $optimalPath = array();
    for($i = 0; $i < $fullRows; $i++) {
        $optimalPath[$i] = array();
        for($j = 0; $j < $fullCols; $j++) {
            $optimalPath[$i][$j] = '.';
        }
    }
    $optimalPath[$fullRows-1][$fullCols-1] = '#';
    $currentRow = $fullRows-1;
    $currentCol = $fullCols-1;
    while($currentRow > 0 || $currentCol > 0) {
        //echo "row $currentRow , col $currentCol \n";
        switch($optimalPaths[$currentRow][$currentCol]) {
            case '|':
                $currentRow--;
                break;
            case '-':
                $currentCol--;
                break;
            default:
                echo "ERROR $currentRow , $currentCol \n";
                $currentRow--;
                $currentCol--;
        }
        $optimalPath[$currentRow][$currentCol] = '#';
    }
    if($print) {
        echo "OPTIMAL PATH\n";
        for ($i = 0; $i < $fullRows; $i++) {
            for ($j = 0; $j < $fullCols; $j++) {
                echo $optimalPath[$i][$j];
            }
            echo "\n";
        }
        echo "\n";
    }
    $optimalRisk = $optimalRisks[$fullRows-1][$fullCols-1];
    echo "optimal risk - $optimalRisk \n";
}

//findLowestRiskPath('test-input.txt', true);
findLowestRiskPath('input.txt');
