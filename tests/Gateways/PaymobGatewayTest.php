<?php

namespace Tests\Gateways;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\Gateways\PaymobGateway;
use Tests\TestCase;

class PaymobGatewayTest extends TestCase
{
    //TODO: authenticate()
    //TODO: createOrder()
    //TODO: createPaymentKey()
    //TODO: pay()
    //TODO: refund()
    //TODO: verifyWebhook()
    public function test_pay_function()
    {
        $gateway = new PaymobGateway();
        $data = new PaymentData('123', 100, 'USD', '', '', '1234567890');
        $response = $gateway->pay($data);
        $this->assertTrue($response->success);
        $this->assertTrue(true);
    }
}