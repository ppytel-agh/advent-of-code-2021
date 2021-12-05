<?php

require_once 'vents.php';

$testVentLines = VentLinesFactory::createFromFile('test-input.txt');
$hvdVents = VentsFilter::getHorizontalVerticalAndDiagonalVents($testVentLines);
$dangerMap = $hvdVents->getDangerMap();
echo $dangerMap->draw();
$risk2AndMore = $dangerMap->countNumberOfCorrdsWithRiskAbove(1);
echo "|{risk > 2}| = ".$risk2AndMore.PHP_EOL;
if($risk2AndMore == 12)
{
    echo 'test passed!'.PHP_EOL;
    $ventLines = VentLinesFactory::createFromFile('input.txt');
    $hvdVents = VentsFilter::getHorizontalVerticalAndDiagonalVents($ventLines);
    $dangerMap = $hvdVents->getDangerMap();
    $risk2AndMore = $dangerMap->countNumberOfCorrdsWithRiskAbove(1);
    echo "|{risk > 2}| = ".$risk2AndMore.PHP_EOL;
} else {
    echo 'test FAILED!';
}