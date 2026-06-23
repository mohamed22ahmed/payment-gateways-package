<?php

namespace Hammam\PaymentGateways\Factories;

use Hammam\PaymentGateways\Contracts\PaymentGatewayInterface;
use Hammam\PaymentGateways\Gateways\PaymobGateway;

class PaymentGatewayFactory
{
    public static function make(string $gateway): PaymentGatewayInterface{
        return match($gateway){
            'paymob' => app(PaymobGateway::class),
        };
    }
}