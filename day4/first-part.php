<?php

require_once 'game.php';

$testGame = parseInputIntoGame('test-input.txt');
try{
    $testGame->playUntilBoardWins();
    $testScore = $testGame->getWinningScore();
    if($testScore == 4512)
    {
        echo "test passed!".PHP_EOL;
        $game = parseInputIntoGame('input.txt');
        $game->playUntilBoardWins();
        $score = $game->getWinningScore();
        echo "score: ".$score.PHP_EOL;
    }
    else
    {
        echo "test failed!".PHP_EOL;
    }
}catch(Exception $e)
{
    var_dump($e);
}

