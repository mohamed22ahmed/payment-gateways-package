<?php

namespace Tests\Gateways;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\Factories\PaymentGatewayFactory;
use Hammam\PaymentGateways\Gateways\StripeGateway;
use Tests\TestCase;
use Mockery;
use Stripe\StripeClient;

class StripeGatewayTest extends TestCase
{
    public function test_can_instantiate_stripe_gateway()
    {
        $gateway = new StripeGateway();
        expect($gateway)->toBeInstanceOf(StripeGateway::class);
    }

    public function test_pay_function()
    {
        $mockSession = Mockery::mock();
        $mockSession->url = 'https://checkout.stripe.com/c/pay/test123';
        
        $mockSessions = Mockery::mock();
        $mockSessions->shouldReceive('create')
            ->once()
            ->andReturn($mockSession);

        $mockCheckout = Mockery::mock();
        $mockCheckout->sessions = $mockSessions;

        $mockStripe = Mockery::mock(StripeClient::class);
        $mockStripe->checkout = $mockCheckout;

        $gateway = new StripeGateway();
        $gateway->setStripeClient($mockStripe);

        $response = $gateway->pay(new PaymentData(
                '12345',
                20.00,
                'USD',
                [
                    'first_name' => 'Alex',
                    'email' => 'alex@example.com',
                    'phone_number' => '01000000000',
                    'success_url' => 'https://example.com/success',
                    'cancel_url' => 'https://example.com/cancel'
                ],
                'Mohamed Ali'
            )
        );

        expect($response)->toBeUrl()
            ->and($response)->toContain('https://checkout.stripe.com');
    }

    public function test_refund_function()
    {
        $mockRefund = Mockery::mock();
        
        $mockRefunds = Mockery::mock();
        $mockRefunds->shouldReceive('create')
            ->once()
            ->andReturn($mockRefund);

        $mockStripe = Mockery::mock(StripeClient::class);
        $mockStripe->refunds = $mockRefunds;

        $gateway = new StripeGateway();
        $gateway->setStripeClient($mockStripe);

        $response = $gateway->refund('pi_test_payment_intent_id', 10.00);
        
        expect($response)->toBeTrue();
    }
}
