<?php

namespace AoC;

class Day2c2
{
    public function run()
    {
        $data = $this->assembleData();
        $this->calculateTotalPower($data);
    }

    private function assembleData(): array
    {
        $filePath = 'resources/day2_cubes.txt';
        $assembledArray = [];
        $lines = file($filePath);
        foreach ($lines as $line) {
            $game = explode(';', trim($line));
            $formattedGame = array_map(function ($row) {
                $subset = explode(',', trim($row));
                foreach ($subset as $key => $colors) {
                    $j[] = explode(' ', $colors);
                }
                $arr = [];
                foreach ($j as $colors) {
                    $arr[] = [
                        $colors[1] => $colors[0]
                    ];
                }
                return array_merge(...$arr);
            }, $game);

            $assembledArray[] = $formattedGame;
        }

        return $assembledArray;
    }

    private function calculateTotalPower(array $data): void
    {
        $power = 0;

        for ($i = 0; $i < count($data); $i++) {
            $power = $power + $this->calculateGamePower($data[$i]);
        }

        print_r($power);
    }

    private function calculateGamePower(array $game): int
    {
        $maxRed = 0;
        $maxBlue = 0;
        $maxGreen = 0;

        foreach ($game as $subset) {
            if (isset($subset["red"]) && $subset["red"] > $maxRed) {
                $maxRed = $subset["red"];
            }
            if (isset($subset["blue"]) && $subset["blue"] > $maxBlue) {
                $maxBlue = $subset["blue"];
            }
            if (isset($subset["green"]) && $subset["green"] > $maxGreen) {
                $maxGreen = $subset["green"];
            }
        }

        return $maxRed * $maxBlue * $maxGreen;
    }
}

try {
    $ch = new Day2c2();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
