<?php

function findLowestRiskPath($inputFilename) {
    $inputText = file_get_contents($inputFilename);
    $inputLines = explode(PHP_EOL, $inputText);
    $rows = count($inputLines);
    $columns = strlen($inputLines[0]);
    echo "board size $rows x $columns \n";
    $riskValues = array();
    for($i = 0; $i < $rows; $i++) {
        $riskChars = str_split($inputLines[$i]);
        for($j = 0; $j < $columns; $j++) {
            $riskValues[$i][$j] = intval($riskChars[$j]);
        }
    }
    $optimalRisks = array();
    $optimalPaths = array();
    for($i = 0; $i < $rows; $i++) {
        $optimalRisks[$i] = array();
        $optimalPaths[$i] = array();
        for($j = 0; $j < $columns; $j++) {
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
                    if($optimalRisks[$i-1][$j] <= $optimalRisks[$i][$j-1]) {
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
    echo "RISK VALUES\n";
    for($i = 0; $i < $rows; $i++) {
        for($j = 0; $j < $columns; $j++) {
            echo $optimalRisks[$i][$j] . ' ';
        }
        echo "\n";
    }
    echo "\n";
    echo "RISK PATHS\n";
    for($i = 0; $i < $rows; $i++) {
        for($j = 0; $j < $columns; $j++) {
            echo $optimalPaths[$i][$j];
        }
        echo "\n";
    }
    echo "\n";
    $optimalPath = array();
    for($i = 0; $i < $rows; $i++) {
        $optimalPath[$i] = array();
        for($j = 0; $j < $columns; $j++) {
            $optimalPath[$i][$j] = '.';
        }
    }
    $optimalPath[$rows-1][$columns-1] = '#';
    $currentRow = $rows-1;
    $currentCol = $columns-1;
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
                $currentRow--;
                $currentCol--;
        }
        $optimalPath[$currentRow][$currentCol] = '#';
    }
    echo "OPTIMAL PATH\n";
    for($i = 0; $i < $rows; $i++) {
        for($j = 0; $j < $columns; $j++) {
            echo $optimalPath[$i][$j];
        }
        echo "\n";
    }
    echo "\n";
    $optimalRisk = $optimalRisks[$rows-1][$columns-1];
    echo "optimal risk - $optimalRisk \n";
}

//findLowestRiskPath('test-input.txt');
findLowestRiskPath('input.txt');
