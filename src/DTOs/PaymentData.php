<?php

namespace Hammam\PaymentGateways\DTOs;

class PaymentData
{
    public function __construct(
        public readonly string $orderId,
        public readonly float $amount,
        public readonly string $currency,
        public readonly ?array $billingData = [],
        public readonly string $customerName,
        public readonly ?string $customerEmail = null,
        public readonly ?string $customerPhone = null,
    ) {}
}