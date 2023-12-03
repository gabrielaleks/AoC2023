<?php

namespace AoC;

class Day3c2
{
    private $arrayfiedFile;

    public function run(): void
    {
        $this->arrayfiedFile = $this->loadFile();

        $symbols = $this->getSymbolsPositions($this->arrayfiedFile);
        $numbers = $this->getNumbers();
        $numbersNextToTwoStars = $this->getNumbersNextToTwoStars($symbols, $numbers);
        $sum = $this->sumNumbers($numbersNextToTwoStars);
        print_r($sum);
    }

    private function loadFile(): array
    {
        $filePath = 'resources/day3_partnumbers.txt';
        $arrayfiedFile = file($filePath);
        return $arrayfiedFile;
    }

    private function getSymbolsPositions(array $file): array
    {
        $symbols = [];
        for ($x = 0; $x < count($file); $x++) {
            for ($y = 0; $y < strlen($file[$x]) - 1; $y++) {
                if (!is_numeric($file[$x][$y]) && $file[$x][$y] == '*') {
                    $symbols[] = [
                        'symbol' => $file[$x][$y],
                        'x' => $x,
                        'y' => $y
                    ];
                }
            }
        }

        return $symbols;
    }

    private function getNumbers(): array
    {
        $fullNumbers = [];
        for ($x = 0; $x < count($this->arrayfiedFile); $x++) {
            $number = '';
            $coordinates = [];
            for ($y = 0; $y < strlen($this->arrayfiedFile[$x]) - 1; $y++) {
                if (is_numeric($this->arrayfiedFile[$x][$y])) {
                    $number .= $this->arrayfiedFile[$x][$y];
                    $coordinates[] = [
                        'x' => $x,
                        'y' => $y
                    ];

                    if ($y == strlen($this->arrayfiedFile[$x]) - 2) {
                        if (is_numeric($number)) {
                            $fullNumbers[] = [
                                "fullNumber" => $number,
                                "coordinates" => $coordinates
                            ];
                            $number = '';
                            $coordinates = [];
                        }
                    }
                } else {
                    if (is_numeric($number)) {
                        $fullNumbers[] = [
                            "fullNumber" => $number,
                            "coordinates" => $coordinates
                        ];
                        $number = '';
                        $coordinates = [];
                    }
                }
            }
        }

        return $fullNumbers;
    }

    private function getNumbersNextToTwoStars(array $symbols, array $numbers): array
    {
        $numbersNextToTwoStars = [];
        foreach ($symbols as $symbol) {
            $touchingStar = [];
            foreach ($numbers as $number) {
                foreach ($number['coordinates'] as $coordinate) {
                    if (
                        $coordinate['x'] == $symbol['x'] - 1 ||
                        $coordinate['x'] == $symbol['x']     ||
                        $coordinate['x'] == $symbol['x'] + 1
                    ) {
                        if (
                            $coordinate['y'] == $symbol['y'] - 1 ||
                            $coordinate['y'] == $symbol['y']     ||
                            $coordinate['y'] == $symbol['y'] + 1
                        ) {
                            $touchingStar[] = $number;
                        }
                    }
                }
            }
            $touchingStar = $this->removeDuplicates($touchingStar);
            if (count($touchingStar) == 2) {
                $numbersNextToTwoStars[] = $touchingStar;
            }
        }

        return $numbersNextToTwoStars;
    }

    private function removeDuplicates(array $numbersNextToTwoStars): array
    {
        return array_values(array_unique($numbersNextToTwoStars, SORT_REGULAR));
    }

    private function sumNumbers(array $pairsOfNumbers): int
    {
        $result = array_reduce(
            $pairsOfNumbers,
            function ($carry, $pairOfNumbers) {
                $carry += $pairOfNumbers[0]['fullNumber'] * $pairOfNumbers[1]['fullNumber'];
                return $carry;
            },
            0
        );

        return $result;
    }
}

try {
    $ch = new Day3c2();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
