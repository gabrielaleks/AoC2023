<?php

namespace AoC;

class Day4c2
{
    public function run(): void
    {
        $data = $this->loadFile();
        $totalInstances = $this->getTotalInstances($data);
        $sum = $this->calculateSum($totalInstances);
        print_r($sum);
    }

    private function loadFile(): array
    {
        $filePath = 'resources/day4.json';
        // $filePath = 'resources/day4_test.json';
        $jsonData = file_get_contents($filePath);
        $arrayData = json_decode($jsonData, true);
        return $arrayData;
    }

    private function getTotalInstances(array $games): array
    {
        $repeat = [];
        $totalInstances = [];

        foreach ($games as $i => $game) {
            $instances = isset($repeat[$i]) ? $repeat[$i] + 1 : 1;

            for ($j = 0; $j < $instances; $j++) {
                $wins = 0;
                $winningNumbers = $game["winningNumbers"];
                $myNumbers = $game["myNumbers"];

                foreach ($winningNumbers as $winningNumber) {
                    if (in_array($winningNumber, $myNumbers)) {
                        $wins++;
                        $repeat[$i + $wins] = isset($repeat[$i + $wins]) ? $repeat[$i + $wins] + 1 : 1;
                    }
                }
            }

            $totalInstances[] = [
                'index' => $i,
                'totalInstances' => $instances,
            ];
        }

        return $totalInstances;
    }

    private function calculateSum(array $totalInstances): int
    {
        $sum = array_reduce(
            $totalInstances,
            function ($carry, $game) {
                $carry += $game['totalInstances'];
                return $carry;
            },
            0
        );

        return $sum;
    }
}

try {
    $ch = new Day4c2();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
