<?php

class CalledNumbers
{
    private array $numbers;
    private int $pool;
    private int $nextNumberId = 0;

    private function addNumber(int $number)
    {
        $this->numbers[] = $number;
    }
    
    public function __construct(array $numbers)
    {
        $this->numbers = [];
        foreach($numbers as $number)
        {
            $this->addNumber($number);
        }
        $this->pool = count($this->numbers);
    }

    public function getNextNumber()
    {
        if($this->nextNumberId >= $this->pool)
        {
            throw new Exception('empty pool');
        }
        $number = $this->numbers[$this->nextNumberId];
        $this->nextNumberId++;
        return $number;
    }
}

class Board
{
    private array $valueIndexes;
    private array $values;
    private array $rowMarks;
    private array $columnMarks;
    private array $winningSequence;

    private function inializeIndexes($numbers)
    {
        $this->valueIndexes = [];
        for($i = 0; $i < 100; $i++)
        {
            $this->valueIndexes[] = -1;
        }
        foreach($numbers as $index => $number)
        {
            $this->valueIndexes[$number - 1] = $index;
        }
    }
    private function initializeMarks()
    {
        $this->rowMarks = [];
        $this->columnMarks = [];
        for($i = 0; $i < 5; $i++)
        {
            $this->rowMarks[] = [];
            $this->columnMarks[] = [];
            for($j = 0; $j < 5; $j++)
            {
                $this->rowMarks[$i][] = false;
                $this->columnMarks[$i][] = false;
            }
        }
    }

    public function __construct(array $numbers)
    {
        $this->values = $numbers;
        $this->inializeIndexes($numbers);
        $this->initializeMarks();
    }

    private function markRowAndColumn(int $numberIndex)
    {
        $rowIndex = intval($numberIndex / 5);
        $columnIndex = $numberIndex % 5;
        $this->rowMarks[$rowIndex][$columnIndex] = true;
        $this->columnMarks[$columnIndex][$rowIndex] = true;
    }

    public function processNumber(int $number)
    {
        if($this->valueIndexes[$number - 1] != -1)
        {
            $numberIndex = $this->valueIndexes[$number - 1];
            $this->markRowAndColumn($numberIndex);
        }
    }

    private function rowHasBingo(int $rowIndex)
    {
        foreach($this->rowMarks[$rowIndex] as $rowMark)
        {
            if(!$rowMark)
            {
                return false;
            }
        }
        return true;
    }

    private function columnHasBingo(int $columnIndex)
    {
        foreach($this->columnMarks[$columnIndex] as $columnMark)
        {
            if(!$columnMark)
            {
                return false;
            }
        }
        return true;
    }

    private function extractWinningSequenceFromRow(int $rowIndex)
    {
        $this->winningSequence = [];
        for($colIndex = 0; $colIndex < 5; $colIndex++)
        {
            $this->winningSequence[] = $this->values[$rowIndex*5 + $colIndex];
        }
    }

    private function extractWinningSequenceFromColumn(int $columnIndex)
    {
        $this->winningSequence = [];
        for($rowIndex = 0; $rowIndex < 5; $rowIndex++)
        {
            $this->winningSequence[] = $this->values[$rowIndex*5 + $columnIndex];
        }
    }

    public function hasBingo()
    {
        for($rowIndex = 0; $rowIndex < 5; $rowIndex++)
        {
            if($this->rowHasBingo($rowIndex))
            {
                $this->extractWinningSequenceFromRow($rowIndex);
                return true;
            }
        }
        for($colIndex = 0; $colIndex < 5; $colIndex++)
        {
            if($this->columnHasBingo($colIndex))
            {
                $this->extractWinningSequenceFromColumn($colIndex);
                return true;
            }
        }
        return false;
    }

    public function getWinningSequence()
    {
        return $this->winningSequence;
    }

    public function getSumOfUnmarkedNumbers()
    {
        $sum = 0;
        for($rowIndex = 0; $rowIndex < 5; $rowIndex++)
        {
            for($columnIndex = 0; $columnIndex < 5; $columnIndex++)
            {
                if(!$this->rowMarks[$rowIndex][$columnIndex])
                {
                    $valueIndex = $rowIndex*5 + $columnIndex;
                    $sum += $this->values[$valueIndex];
                }
            }
        }
        return $sum;
    }
}

class Game
{
    private CalledNumbers $called;
    private array $boards;
    private int $lastCalledNumber;
    private $winningBoard;

    private function addBoard(Board $board)
    {
        $this->boards[] = $board;
    }

    public function __construct(CalledNumbers $called, array $boards)
    {
        $this->called = $called;
        $this->boards = [];
        foreach($boards as $board)
        {
            $this->addBoard($board);
        }
    }

    private function announceNumberToBoards(int $number)
    {
        foreach($this->boards as $board)
        {
            $board->processNumber($number);
        }
    }

    private function noBoardWon()
    {
        foreach($this->boards as $board)
        {
            if($board->hasBingo())
            {
                $this->winningBoard = $board;
                return false;
            }
        }
        return true;
    }

    public function playUntilBoardWins()
    {
        do
        {
            try{
                $number = $this->called->getNextNumber();
            }catch(Exception $e)
            {
                throw $e;
            }
            $this->announceNumberToBoards($number);
            $this->lastCalledNumber = $number;
        } while($this->noBoardWon());
    }

    public function getWinningScore()
    {
        return $this->winningBoard->getSumOfUnmarkedNumbers() * $this->lastCalledNumber;
    }
}

function parseInputIntoGame(string $inputFilename)
{
    $testInputString = file_get_contents($inputFilename);
    $inputLines = explode(PHP_EOL, $testInputString);
    $numberOfInputLines = count($inputLines);
    $numberOfBoards = intval($numberOfInputLines / 6);
    $boards = [];
    for($boardId = 0; $boardId < $numberOfBoards; $boardId++)
    {
        $boardValues = [];
        for($i = 0; $i < 5; $i++)
        {
            $inputRowIndex = 2 + ($boardId * 6) + $i;
            $bingoRow = $inputLines[$inputRowIndex];
            for($j = 0; $j < 5; $j++)
            {
                $numberSubstring = substr($bingoRow, 3*$j, 3);
                $numberValue = intval($numberSubstring);
                $boardValues[] = $numberValue;
            }
        }
        $boards[] = new Board($boardValues);
    }

    $calledNumbersStrings = explode(',', $inputLines[0]);
    $calledNumbers = [];
    foreach($calledNumbersStrings as $calledNumbersString)
    {
        $calledNumbers[] = intval($calledNumbersString);
    }
    $called = new CalledNumbers($calledNumbers);
    return new Game($called, $boards);
}

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

