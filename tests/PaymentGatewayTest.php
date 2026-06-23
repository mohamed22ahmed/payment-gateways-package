<?php

namespace Tests;
use Hammam\PaymentGateways\Managers\PaymentManager;

class PaymentGatewayTest extends TestCase
{
    public function test_manager_can_be_resolved()
    {
        $manager = app('payment-manager');

        $this->assertInstanceOf(
            PaymentManager::class,
            $manager
        );
    }
}