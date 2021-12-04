<?php

require_once 'game.php';

$testGame = parseInputIntoGame('test-input.txt');
try{
    $testGame->playUntilAllBoardsWin();
    $testScore = $testGame->getLastWinningScore();
    if($testScore == 1924)
    {
        echo "test passed!".PHP_EOL;
        $game = parseInputIntoGame('input.txt');
        $game->playUntilAllBoardsWin();
        $score = $game->getLastWinningScore();
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

