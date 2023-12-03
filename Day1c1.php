<?php

namespace AoC;

class Day1c1
{
    private const NUMBERS_SIMPLE = [
        'one' => '1',
        'two' => '2',
        'three' => '3',
        'four' => '4',
        'five' => '5',
        'six' => '6',
        'seven' => '7',
        'eight' => '8',
        'nine' => '9'
    ];

    public function run()
    {
        $data = $this->loadFile();
        $this->calculateSum($data);
    }

    private function loadFile(): array
    {
        $filePath = 'resources/day1_calibration_document.json';
        $jsonData = file_get_contents($filePath);
        $arrayData = json_decode($jsonData, true);
        return $arrayData;
    }

    private function calculateSum(array $data): void
    {
        $sum = array_reduce(
            $data,
            function ($carry, $line) {
                print_r($line . "\n");
                $carry += $this->onlyIntsAlgorithm($line);
                return $carry;
            },
            0
        );

        print_r($sum . "\n");
    }

    private function onlyIntsAlgorithm(string $line): int
    {
        print_r($line . "\n");
        $characters = str_split($line);

        $firstNumber = null;
        foreach ($characters as $character) {
            if (is_numeric($character)) {
                $firstNumber = $character;
                break;
            }
        }

        $reversedCharacters = array_reverse($characters);

        $lastNumber = null;
        foreach ($reversedCharacters as $character) {
            if (is_numeric($character)) {
                $lastNumber = $character;
                break;
            }
        }

        $finalNumber = (int) ($firstNumber . $lastNumber);

        print_r($finalNumber . "\n\n");
        return $finalNumber;
    }
}

try {
    $ch = new Day1c1();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
