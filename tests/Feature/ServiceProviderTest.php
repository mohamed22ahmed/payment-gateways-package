<?php

namespace Tests\Feature;

use Hammam\PaymentGateways\Managers\PaymentManager;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_registers_payment_manager() {
        expect(app('payment-manager'))
            ->toBeInstanceOf(PaymentManager::class);
    }
}