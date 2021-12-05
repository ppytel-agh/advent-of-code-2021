<?php

require_once 'vents.php';

$testVentLines = VentLinesFactory::createFromFile('test-input.txt');
$horizontalAndVerticalVents = VentsFilter::getHorizontalAndVerticalVents($testVentLines);
$dangerMap = $horizontalAndVerticalVents->getDangerMap();
echo $dangerMap->draw();
$risk2AndMore = $dangerMap->countNumberOfCorrdsWithRiskAbove(1);
echo "|{risk > 2}| = ".$risk2AndMore.PHP_EOL;
if($risk2AndMore == 5)
{
    echo 'test passed!'.PHP_EOL;
    $ventLines = VentLinesFactory::createFromFile('input.txt');
    $horizontalAndVerticalVents = VentsFilter::getHorizontalAndVerticalVents($ventLines);
    $dangerMap = $horizontalAndVerticalVents->getDangerMap();
    $risk2AndMore = $dangerMap->countNumberOfCorrdsWithRiskAbove(1);
    echo "|{risk > 2}| = ".$risk2AndMore.PHP_EOL;
} else {
    echo 'test FAILED!';
}