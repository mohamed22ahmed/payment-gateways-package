<?php

namespace Tests\Unit\Managers;

use Hammam\PaymentGateways\Gateways\PaymobGateway;
use Tests\TestCase;

class PaymentManagerTest extends TestCase
{
    //TODO: Uses requested gateway
    //TODO: Delegates payment creation
    //TODO: Delegates refund
    //TODO: Delegates status check

    public function test_uses_default_gateway()
    {
        config()->set('payments.default', 'paymob');
        $manager = app('payment-manager');
        expect($manager->gateway())->toBeInstanceOf(PaymobGateway::class);
    }
}