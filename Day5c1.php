<?php

namespace AoC;

class Day5c1
{
    private $data;
    private $seeds;
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
        $this->seeds = $this->getSeeds();
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

    private function getSeeds(): array
    {
        $pattern = '/seeds: (.+?)\n/';
        preg_match($pattern, $this->data, $matches);
        $seeds = array_filter(
            explode(' ', $matches[1]),
            function ($element) {
                return is_numeric($element) ? $element : null;
            },
        );

        return array_values($seeds);
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
        $finalResult = [];
        foreach ($this->seeds as $seed) {
            $soil = $this->getUpdatedDestination($this->seedToSoilMapping, $seed);
            $fertilizer = $this->getUpdatedDestination($this->soilToFertilizerMapping, $soil);
            $water = $this->getUpdatedDestination($this->fertilizerToWater, $fertilizer);
            $light = $this->getUpdatedDestination($this->waterToLight, $water);
            $temperature = $this->getUpdatedDestination($this->lightToTemperature, $light);
            $humidity = $this->getUpdatedDestination($this->temperatureToHumidity, $temperature);
            $location = $this->getUpdatedDestination($this->humidityToLocation, $humidity);
            $finalResult[] = $location;
        }

        print_r(min($finalResult));
    }

    private function getUpdatedDestination(array $mappings, int $currentValue): int
    {
        $updatedDestination = $currentValue;
        foreach ($mappings as $mapping) {
            if ($currentValue <= ($mapping['source'] + $mapping['range'] - 1) && $mapping['source'] <= $currentValue) {
                $updatedDestination = $currentValue - $mapping['source'] + $mapping['destination'];
            }
        }
        return $updatedDestination;
    }
}

try {
    $ch = new Day5c1();
    $ch->run();
} catch (\Exception $e) {
    print_r($e);
}
