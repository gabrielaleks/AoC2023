<?php

namespace AoC;

class Day5c2
{
    private $data;
    private $seedRanges;
    private $seedToSoilMapping;
    private $soilToFertilizerMapping;
    private $fertilizerToWater;
    private $waterToLight;
    private $lightToTemperature;
    private $temperatureToHumidity;
    private $humidityToLocation;

    public function run(): void
    {
        $this->data = $this->loadFile();
        $this->seedRanges = $this->getSeedRanges();
        $this->seedToSoilMapping = $this->getDataMappingWithRegexpPattern('/seed-to-soil map:\n(?:\d+ \d+ \d+\n)+/');
        $this->soilToFertilizerMapping = $this->getDataMappingWithRegexpPattern('/soil-to-fertilizer map:\n(?:\d+ \d+ \d+\n)+/');
        $this->fertilizerToWater = $this->getDataMappingWithRegexpPattern('/fertilizer-to-water map:\n(?:\d+ \d+ \d+\n)+/');
        $this->waterToLight = $this->getDataMappingWithRegexpPattern('/water-to-light map:\n(?:\d+ \d+ \d+\n)+/');
        $this->lightToTemperature = $this->getDataMappingWithRegexpPattern('/light-to-temperature map:\n(?:\d+ \d+ \d+\n)+/');
        $this->temperatureToHumidity = $this->getDataMappingWithRegexpPattern('/temperature-to-humidity map:\n(?:\d+ \d+ \d+\n)+/');
        $this->humidityToLocation = $this->getDataMappingWithRegexpPattern('/humidity-to-location map:\n(?:\d+ \d+ \d+\n)+/');

        $this->calculate();
    }

    private function loadFile()
    {
        $filePath = 'resources/day5.txt';
        $file = file_get_contents($filePath);
        return $file;
    }

    private function getSeedRanges(): array
    {
        $pattern = '/seeds: (.+?)\n/';
        preg_match($pattern, $this->data, $matches);
        $seeds = array_filter(
            explode(' ', $matches[1]),
            function ($element) {
                return is_numeric($element) ? $element : null;
            },
        );

        $ranges = [];
        for ($i = 0; $i < count($seeds); $i++) {
            if ($i % 2 != 0) {
                $ranges[] = [
                    'min' => $seeds[$i - 1],
                    'max' => $seeds[$i] + ($seeds[$i - 1] - 1),
                ];
            }
        }

        return array_values($ranges);
    }

    private function getDataMappingWithRegexpPattern(string $pattern)
    {
        preg_match($pattern, $this->data, $matches);
        $explodedSection = array_filter(explode("\n", $matches[0]));
        array_shift($explodedSection); // removes first element ('... map' text) from array

        $result = array_map(
            function ($line) {
                $map = explode(' ', $line);
                return [
                    'source' => $map[1],
                    'destination' => $map[0],
                    'range' => $map[2]
                ];
            },
            $explodedSection,
        );

        return $result;
    }

    private function calculate()
    {
        $finalRanges = [];
        $soilRanges = $this->getUpdatedRanges($this->seedToSoilMapping, $this->seedRanges);
        $fertilizerRanges = $this->getUpdatedRanges($this->soilToFertilizerMapping, $soilRanges);
        $waterRanges = $this->getUpdatedRanges($this->fertilizerToWater, $fertilizerRanges);
        $lightRanges = $this->getUpdatedRanges($this->waterToLight, $waterRanges);
        $temperatureRanges = $this->getUpdatedRanges($this->lightToTemperature, $lightRanges);
        $humidityRanges = $this->getUpdatedRanges($this->temperatureToHumidity, $temperatureRanges);
        $locationRanges = $this->getUpdatedRanges($this->humidityToLocation, $humidityRanges);
        $finalRanges[] = $locationRanges;

        $minValues = min(array_column($finalRanges[0], 'min'));
        print_r($minValues);
        // answer: 20283860
    }

    private function getUpdatedRanges(array $mappings, array $currentRanges): array
    {
        $finalSeeds = [];
        foreach ($mappings as $mapping) {
            $convertedSeeds = [];
            $newSeeds = [];
            foreach ($currentRanges as $currentRange) {
                $min = $currentRange['min'];
                $max = $currentRange['max'];
                $sourceStart = $mapping['source'];
                $destinationStart = $mapping['destination'];
                $range = $mapping['range'];
                $sourceEnd = $sourceStart + $range - 1;

                if ($min < min($max, $sourceStart - 1)) {
                    $newSeeds[] = [
                        'min' => $min,
                        'max' => min($max, $sourceStart - 1)];
                }

                if ($max > max($sourceEnd + 1, $min)) {
                    $newSeeds[] = [
                        'min' => max($sourceEnd + 1, $min),
                        'max' => $max
                    ];
                }

                if (max($min, $sourceStart) < min($max, $sourceEnd)) {
                    $convertedSeeds[] = [
                        'min' => $destinationStart + max($min, $sourceStart) - ($sourceStart),
                        'max' => $destinationStart + min($max, $sourceEnd) - ($sourceStart)
                    ];
                }
            }
            $currentRanges = $newSeeds;
            $finalSeeds = array_merge($finalSeeds, $convertedSeeds);
        }

        $finalSeeds = array_merge($finalSeeds, $currentRanges);
        return $finalSeeds;
    }
}

try {
    $start = microtime(true);
    $ch = new Day5c2();
    $ch->run();
    print_r("\nexecution time: " . microtime(true) - $start . "\n");
} catch (\Exception $e) {
    print_r($e);
}
