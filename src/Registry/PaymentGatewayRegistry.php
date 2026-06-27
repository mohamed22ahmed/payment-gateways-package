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

    public function register(array $data): void
    {
        config()->set("payments.gateways.{$data['name']}", $data['config']);
    }

    public function getAllGateways(): array
    {
        return config('payments.gateways');
    }

    public function getGateway(string $name): array | null
    {
        return config("payments.gateways.{$name}");
    }
}