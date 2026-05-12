<?php

namespace App\Services;

class SustainabilityLogic
{
    private array $factors = [
        'cotton' => 10.0,
        'polyester' => 15.0,
        'wool' => 8.0,
        'silk' => 12.0,
    ];

    public function calculateCarbonSavings(float $weight, string $material): float
    {
        $factor = $this->factors[strtolower($material)] ?? 10.0;
        return $weight * $factor;
    }
}
