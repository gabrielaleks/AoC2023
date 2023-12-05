<?php

namespace AoC;

class Day4c1
{
    public function run(): void
    {
        $data = $this->loadFile();
        $sum = $this->getTotalSum($data);
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

    private function getTotalSum(array $data): int
    {
        return array_reduce(
            $data,
            function ($carry, $card) {
                $wins = 0;

                foreach ($card["winningNumbers"] as $key => $winningNumber) {
                    if (in_array($winningNumber, $card["myNumbers"])) {
                        $wins++;
                    }
                }

                $result = $wins ? (2 ** ($wins - 1)) : 0;
                return ($carry + $result);
            },
            0
        );
    }
}

try {
    $ch = new Day4c1();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
