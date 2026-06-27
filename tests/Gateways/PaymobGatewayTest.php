<?php

namespace Tests\Gateways;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\Factories\PaymentGatewayFactory;
use Hammam\PaymentGateways\Gateways\PaymobGateway;
use Tests\TestCase;

class PaymobGatewayTest extends TestCase
{
    public function test_can_instantiate_paymob_gateway()
    {
        $gateway = new PaymobGateway();
        expect($gateway)->toBeInstanceOf(PaymobGateway::class);
    }

    public function test_authenticate()
    {
        $gateway = new PaymentGatewayFactory();
        $gateway = $gateway->make('paymob');

        $response = $gateway->authenticate();

        expect($response)->toBeArray()
            ->and($response)->toHaveKey('token')
            ->and($response['profile']['user']['first_name'])->toBe('Mohamed')
            ->and($response['profile']['user']['last_name'])->toBe('Hammam');
    }

    public function test_pay_function()
    {
        $gateway = new PaymentGatewayFactory();
        $gateway = $gateway->make('paymob');

        $response = $gateway->pay(new PaymentData(
                '12345',
                200,
                'EGP',
                ['first_name' => 'Alex', 'email' => 'alex@example.com', 'phone_number' => '01000000000'],
                'Mohamed Ali'
            )
        );

        expect($response)->toBeUrl()
            ->and($response)->toContain('https://accept.paymob.com/api/acceptance/iframes/');
    }

    public function test_refund_function()
    {
        $transactionId = '486147527';
        $gateway = new PaymentGatewayFactory();
        $gateway = $gateway->make('paymob');

        $response = $gateway->refund($transactionId, 8000);
        expect($response)->toBeTrue();
    }
}