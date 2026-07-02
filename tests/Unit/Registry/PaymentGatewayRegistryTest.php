<?php

namespace Tests\Unit\Registry;

use Hammam\PaymentGateways\Registry\PaymentGatewayRegistry;
use Tests\TestCase;

class PaymentGatewayRegistryTest extends TestCase
{
    public function test_retrieves_gateway() {
        $registry = new PaymentGatewayRegistry();
        $gateways = $registry->getAllGateways();
        expect($gateways)->toBeArray()
            ->and($gateways)->toHaveKey('paymob')
            ->and($gateways)->toHaveKey('stripe');
    }

    public function test_get_gateway()
    {
        $registry = new PaymentGatewayRegistry();
        $response = $registry->getGateway('paymob');
        expect($response)->toBeArray()
            ->and($response['name'])->toBe('paymob');
    }

    public function test_unknownt_gateway_returns_null() {
        $registry = new PaymentGatewayRegistry();
        $response = $registry->getGateway('unknown');
        expect($response)->toBeNull();
    }
}