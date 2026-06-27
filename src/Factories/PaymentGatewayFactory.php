<?php

namespace Hammam\PaymentGateways\Factories;

use Hammam\PaymentGateways\Contracts\PaymentGatewayInterface;
use Hammam\PaymentGateways\Gateways\PaymobGateway;

class PaymentGatewayFactory
{
    public static function make(string $gateway): PaymentGatewayInterface{
        return match($gateway){
            'paymob' => app(PaymobGateway::class),
            // Fawry
            // Kashier
            // OPay
            // Tap Payments
            // PayTabs
            // HyperPay
            // Moyasar
            // Geidea
            // MyFatoorah
            // Stripe
            // PayPal
            // Braintree
            // Checkout.com
            // Adyen
            // Authorize.Net
            // Square
            // Verifone (formerly 2Checkout)
            // Worldpay
            // Mollie
            // Razorpay
            // dLocal
            // Mercado Pago
        };
    }
}