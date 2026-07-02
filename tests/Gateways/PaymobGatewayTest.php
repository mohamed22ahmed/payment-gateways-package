<?php

namespace Tests\Gateways;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\Factories\PaymentGatewayFactory;
use Hammam\PaymentGateways\Gateways\PaymobGateway;
use Illuminate\Support\Facades\Http;
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
        Http::fake([
            'https://accept.paymob.com/api/auth/tokens' => Http::response([
                'token' => 'test_token_123',
                'profile' => [
                    'user' => [
                        'first_name' => 'Mohamed',
                        'last_name' => 'Hammam'
                    ]
                ]
            ], 200)
        ]);

        $gateway = new PaymobGateway();
        $response = $gateway->authenticate();

        expect($response)->toBeArray()
            ->and($response)->toHaveKey('token')
            ->and($response['profile']['user']['first_name'])->toBe('Mohamed')
            ->and($response['profile']['user']['last_name'])->toBe('Hammam');
    }

    public function test_pay_function()
    {
        Http::fake([
            'https://accept.paymob.com/api/auth/tokens' => Http::response([
                'token' => 'test_token_123'
            ], 200),
            'https://accept.paymob.com/api/ecommerce/orders' => Http::response([
                'id' => 12345
            ], 200),
            'https://accept.paymob.com/api/acceptance/payment_keys' => Http::response([
                'token' => 'payment_token_67890'
            ], 200)
        ]);

        $gateway = new PaymobGateway();

        $response = $gateway->pay(new PaymentData(
                '12345',
                200,
                'EGP',
                ['first_name' => 'Alex', 'email' => 'alex@example.com', 'phone_number' => '01000000000'],
                'Mohamed Ali'
            )
        );

        expect($response)->toBeUrl()
            ->and($response)->toContain('https://accept.paymob.com/api/acceptance/iframes/')
            ->and($response)->toContain('payment_token=payment_token_67890');
    }

    public function test_refund_function()
    {
        $transactionId = '486147527';
        
        Http::fake([
            'https://accept.paymob.com/api/auth/tokens' => Http::response([
                'token' => 'test_token_123'
            ], 200),
            'https://accept.paymob.com/api/acceptance/void_refund/refund' => Http::response([
                'success' => true
            ], 200)
        ]);

        $gateway = new PaymobGateway();
        $response = $gateway->refund($transactionId, 80.00);
        
        expect($response)->toBeTrue();
    }
}