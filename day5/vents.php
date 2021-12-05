<?php

class Coord2D
{
    private int $x;
    private int $y;
    public function __construct(int $x, int $y)
    {
        if ($x >= 0 && $y >= 0)
        {
            $this->x = $x;
            $this->y = $y;
        }
        else
        {
            throw new Exception("Negative coordinates are not allowed");
        }
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}

class VentLine
{
    private Coord2D $start;
    private Coord2D $end;

    public function __construct(Coord2D $start, Coord2D $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): Coord2D
    {
        return $this->start;
    }

    public function getEnd(): Coord2D
    {
        return $this->end;
    }

    public function isHorizontal(): bool
    {
        return $this->start->getY() == $this->end->getY();
    }

    public function isVertical(): bool
    {
        return $this->start->getX() == $this->end->getX();
    }

    public function interpolateCoords(): array
    {
        if ($this->isVertical())
        {
            $delta = $this->end->getY() - $this->start->getY();
            $steps = abs($delta);
            if($steps > 0)
            {
                $step = intval($delta / $steps);
                $coords = [];
                for($i = 0; $i <= $steps; $i++)
                {
                    $coords[] = new Coord2D($this->start->getX(), $this->start->getY() + ($i * $step));
                }
                return $coords;
            }
            else
            {
                return [$this->start];
            }
        }
        else if($this->isHorizontal())
        {
            $delta = $this->end->getX() - $this->start->getX();
            $steps = abs($delta);
            if($steps > 0) {
                $step = intval($delta / $steps);
                $coords = [];
                for ($i = 0; $i <= $steps; $i++) {
                    $coords[] = new Coord2D($this->start->getX() + ($i * $step), $this->start->getY());
                }
                return $coords;
            } else {
                return [$this->start];
            }
        }
        else
        {
            return [];
        }
    }
}

class DangerMap
{
    private $riskValues;
    private $width;
    private $height;

    private function addCoordRiskValue(int $rowIndex, int $colIndex, int $coordValue)
    {
        $this->riskValues[$rowIndex][$colIndex] = $coordValue;
    }

    private function addRowRiskValue(int $rowIndex, array $rowRiskValue)
    {
        $rowLength = count($rowRiskValue);
        if($rowLength != $this->width)
        {
            throw new Exception('Inconsistent width');
        }
        foreach($rowRiskValue as $colIndex => $coordValue)
        {
            $this->addCoordRiskValue($rowIndex, $colIndex, $coordValue);
        }
    }

    public function __construct(array $riskValues)
    {
        $this->height = count($riskValues);
        $this->width = count($riskValues[0]);
        $this->riskValues = [];
        foreach($riskValues as $rowIndex => $rowRiskValue)
        {
            $this->addRowRiskValue($rowIndex, $rowRiskValue);
        }
    }

    public function draw(): string
    {
        $mapString = '';
        foreach($this->riskValues as $riskRow)
        {
            foreach($riskRow as $coordRisk)
            {
                if($coordRisk > 0)
                {
                    $mapString .= $coordRisk;
                }
                else
                {
                    $mapString .= '.';
                }
            }
            $mapString .= PHP_EOL;
        }
        return $mapString;
    }

    public function countNumberOfCorrdsWithRiskAbove(int $threshold): int
    {
        $count = 0;
        foreach($this->riskValues as $riskRow)
        {
            foreach($riskRow as $coordRisk)
            {
                if($coordRisk > $threshold)
                {
                    $count++;
                }
            }
        }
        return $count;
    }
}

class DangerMapBuilder
{
    private $width;
    private $height;
    private $riskValues;

    private function initializeRiskValues()
    {
        $this->riskValues = [];
        for($i = 0; $i < $this->height; $i++)
        {
            $rowRisk = [];
            for($j = 0; $j < $this->width; $j++)
            {
                $rowRisk[] = 0;
            }
            $this->riskValues[] = $rowRisk;
        }
    }

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->initializeRiskValues();
    }

    public function increaseRisk(Coord2D $point)
    {
        if($point->getX() < $this->width && $point->getY() < $this->height)
        {
            $this->riskValues[$point->getY()][$point->getX()]++;
        }
        else
        {
            throw new Exception("Coordinate out of bounds");
        }
    }

    public function getDangerMap(): DangerMap
    {
        return new DangerMap($this->riskValues);
    }
}

class VentLinesContainer
{
    private $ventLines;

    private function addVentLine(VentLine $ventLine)
    {
        $this->ventLines[] = $ventLine;
    }

    public function __construct(array $ventLines)
    {
        $this->ventLines = [];
        foreach($ventLines as $ventLine)
        {
            $this->addVentLine($ventLine);
        }
    }

    public function getVentLines()
    {
        return $this->ventLines;
    }

    public function getDangerMap(): DangerMap
    {
        $maxX = 0;
        $maxY = 0;
        foreach($this->ventLines as $ventLine)
        {
            if($ventLine->getStart()->getX() > $maxX)
            {
                $maxX = $ventLine->getStart()->getX();
            }
            if($ventLine->getEnd()->getX() > $maxX)
            {
                $maxX = $ventLine->getEnd()->getX();
            }
            if($ventLine->getStart()->getY() > $maxY)
            {
                $maxY = $ventLine->getStart()->getY();
            }
            if($ventLine->getEnd()->getY() > $maxY)
            {
                $maxY = $ventLine->getEnd()->getY();
            }
        }
        $width = $maxX + 1;
        $height = $maxY + 1;
        $builder = new DangerMapBuilder($width, $height);
        foreach($this->ventLines as $ventLine)
        {
            foreach($ventLine->interpolateCoords() as $coord)
            {
                $builder->increaseRisk($coord);
            }
        }
        return $builder->getDangerMap();
    }

}

class VentsFilter
{
    public static function getHorizontalAndVerticalVents(VentLinesContainer $ventsContainer)
    {
        $horizontalAndVerticalVents = [];
        foreach($ventsContainer->getVentLines() as $ventLine)
        {
            if($ventLine->isHorizontal() || $ventLine->isVertical())
            {
                $horizontalAndVerticalVents[] = $ventLine;
            }
        }
        return new VentLinesContainer($horizontalAndVerticalVents);
    }
}

class VentLinesFactory
{
    const VENT_LINE_PATTERN = '/(\d+),(\d+) -> (\d+),(\d+)/';
    public static function createFromLines(string $inputLines)
    {
        preg_match_all(self::VENT_LINE_PATTERN, $inputLines, $outputArray);
        $numberOfResults = count($outputArray[0]);
        $ventLines = [];
        for($i = 0; $i < $numberOfResults; $i++)
        {
            $x1 = intval($outputArray[1][$i]);
            $y1 = intval($outputArray[2][$i]);
            $x2 = intval($outputArray[3][$i]);
            $y2 = intval($outputArray[4][$i]);
            $startCoord = new Coord2D($x1, $y1);
            $endCoord = new Coord2D($x2, $y2);
            $ventLines[] = new VentLine($startCoord, $endCoord);
        }
        return new VentLinesContainer($ventLines);
    }

    public static function createFromFile(string $filename)
    {
        $inputLines = file_get_contents($filename);
        return self::createFromLines($inputLines);
    }
}

