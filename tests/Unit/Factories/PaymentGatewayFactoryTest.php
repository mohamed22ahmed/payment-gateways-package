<?php

namespace Tests\Unit\Factories;

use Hammam\PaymentGateways\Contracts\PaymentGatewayInterface;
use Hammam\PaymentGateways\Factories\PaymentGatewayFactory;
use Hammam\PaymentGateways\Gateways\PaymobGateway;
use Tests\TestCase;

class PaymentGatewayFactoryTest extends TestCase
{
    public function test_creates_paymob_gateway() {
        $gateway = app(PaymentGatewayFactory::class)->make('paymob');
        expect($gateway)->toBeInstanceOf(PaymobGateway::class);
    }
}