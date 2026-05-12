<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SustainabilityLogic;
use App\Services\SwapAgreementService;

class LogicTests extends TestCase
{
    /** @test */
    public function it_calculates_correct_carbon_savings()
    {
        $logic = new SustainabilityLogic();
        // Case: 0.5kg of Cotton
        $result = $logic->calculateCarbonSavings(0.5, 'cotton');
        $this->assertEquals(5.0, $result); // Based on 10kg CO2 per 1kg factor
    }

    /** @test */
    public function it_calculates_correct_swap_top_up()
    {
        $itemA_price = 1000; // Initiator Item
        $itemB_price = 1200; // Target Item
        $balancer = new SwapAgreementService();
        $topUp = $balancer->suggestValueBalancer($itemA_price, $itemB_price);
        $this->assertEquals(200, $topUp);
    }
}
