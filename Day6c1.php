<?php

namespace AoC;

class Day6c1
{
    public function run(): void
    {
        $data = $this->parseFile();
        $this->calculate($data);
    }

    private function parseFile(): array
    {
        // $filePath = 'resources/day6_test.txt';
        $filePath = 'resources/day6.txt';
        $file = file($filePath);

        preg_match_all('/\b\d+\b/', $file[0], $times);
        preg_match_all('/\b\d+\b/', $file[1], $distances);

        $times = $times[0];
        $distances = $distances[0];

        $arrayfiedFile = [];
        for ($i = 0; $i < count($times); $i++) {
            $arrayfiedFile[] = [
                'time' => $times[$i],
                'distance' => $distances[$i]
            ];
        }
        return $arrayfiedFile;
    }

    private function calculate(array $data)
    {
        $sum = array_reduce(
            $data,
            function ($carry, $race) {
                $numberOfWaysToBeatRecord = 0;
                for ($i = 1; $i <= $race['time']; $i++) {
                    $buttonTime = $i;
                    $speed = $i;
                    $distance = $speed * ($race['time'] - $buttonTime);

                    if ($distance > $race['distance']) {
                        $numberOfWaysToBeatRecord++;
                    }
                }
                $carry *= $numberOfWaysToBeatRecord;
                return $carry;
            },
            1
        );

        print_r($sum);
    }
}

try {
    $start = microtime(true);
    $ch = new Day6c1();
    $ch->run();
    // print_r("\nexecution time: " . microtime(true) - $start . "\n");
} catch (\Exception $e) {
    print_r($e);
}
