<?php

namespace App\Services;

class SwapAgreementService
{
    public function suggestValueBalancer(float $itemAPrice, float $itemBPrice): float
    {
        return abs($itemBPrice - $itemAPrice);
    }
}
