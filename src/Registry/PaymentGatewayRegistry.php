<?php

namespace Hammam\PaymentGateways\Registry;

class PaymentGatewayRegistry
{
    public function enabled(): array
    {
        return collect(config('payments.gateways'))
            ->filter(fn ($gateway) => $gateway['enabled'])
            ->keys()
            ->toArray();
    }
}