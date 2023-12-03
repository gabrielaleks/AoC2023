<?php

namespace AoC;

class Day3c1
{
    private $arrayfiedFile;

    public function run(): void
    {
        $this->arrayfiedFile = $this->loadFile();

        $symbols = $this->getSymbolsPositions($this->arrayfiedFile);
        $numbers = $this->getNumbers();
        $numbersNextToSymbols = $this->getNumbersNextToSymbols($symbols, $numbers);
        $sum = $this->sumNumbers($numbersNextToSymbols);
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
                if (!is_numeric($file[$x][$y]) && $file[$x][$y] !== '.') {
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

    private function getNumbersNextToSymbols(array $symbols, array $numbers): array
    {
        $numbersNextToSymbols = [];
        foreach ($numbers as $number) {
            foreach ($number['coordinates'] as $coordinate) {
                foreach ($symbols as $symbol) {
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
                            $numbersNextToSymbols[] = $number;
                        }
                    }
                }
            }
        }

        return $this->removeDuplicates($numbersNextToSymbols);
    }

    private function removeDuplicates(array $numbersNextToSymbols): array
    {
        return array_values(array_unique($numbersNextToSymbols, SORT_REGULAR));
    }

    private function sumNumbers(array $numbers): int
    {
        print_r($numbers);
        $result = array_reduce(
            $numbers,
            function ($carry, $number) {
                $carry += $number['fullNumber'];
                return $carry;
            },
            0
        );

        return $result;
    }
}

try {
    $ch = new Day3c1();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
