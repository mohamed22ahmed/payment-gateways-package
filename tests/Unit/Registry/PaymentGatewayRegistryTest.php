<?php

namespace Tests\Unit\Registry;

use Hammam\PaymentGateways\Registry\PaymentGatewayRegistry;
use Tests\TestCase;

class PaymentGatewayRegistryTest extends TestCase
{
    //TODO: Unknown gateway throws exception
    //TODO: Overwrite protection (if implemented)

    public function test_registers_gateway() {
        $registry = new PaymentGatewayRegistry();
        $data = [
            'name' => 'fawry',
            'config' => [
                'enabled' => true,
                'base_url' => 'PAYMOB_BASE_URL',
                'api_key' => 'sdfghjhrewqwfghmh',
                'integration_id' => 'PAYMOB_INTEGRATION_ID',
            ]
        ];
        $registry->register($data);
        expect($registry->enabled())->toBe(['paymob', 'fawry']);
    }

    public function test_retrieves_gateway() {
        $registry = new PaymentGatewayRegistry();
        $gateways = $registry->getAllGateways();
        expect($gateways)->toBeArray();
        expect($gateways['paymob'])->toBeArray();
    }

    public function test_unknownt_gateway_returns_null() {
        $registry = new PaymentGatewayRegistry();
        $registry->getGateway('unknown');
        expect($registry->getGateway('unknown'))->toBeNull();
    }
}