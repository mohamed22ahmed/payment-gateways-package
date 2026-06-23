<?php

namespace Hammam\PaymentGateways\Managers;

use Hammam\PaymentGateways\Factories\PaymentGatewayFactory;

class PaymentManager
{
    public function __construct(private PaymentGatewayFactory $factory) {}

    public function gateway(?string $gateway = null) {
        $gateway ??= config('payments.default');

        return $this->factory->make($gateway);
    }
}