<?php

namespace AoC;

class Day2c1
{
    private const MAX_RED = 12;
    private const MAX_GREEN = 13;
    private const MAX_BLUE = 14;

    public function run()
    {
        $data = $this->assembleData();
        $this->calculateSum($data);
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

    private function calculateSum(array $data): void
    {
        $game = 1;
        $sum = 0;

        for ($i = 0; $i < count($data); $i++) {
            if ($this->isGamePossible($data[$i])) {
                $sum = $sum + $game;
            }
            $game++;
        }

        print_r($sum);
    }

    private function isGamePossible(array $game): bool
    {
        foreach ($game as $subset) {
            if (isset($subset["red"]) && $subset["red"] > self::MAX_RED) {
                return false;
            }
            if (isset($subset["green"]) && $subset["green"] > self::MAX_GREEN) {
                return false;
            }
            if (isset($subset["blue"]) && $subset["blue"] > self::MAX_BLUE) {
                return false;
            }
        }
        return true;
    }
}

try {
    $ch = new Day2c1();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
