<?php

namespace Hammam\PaymentGateways\DTOs;

class PaymentResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $transactionId,
        public readonly ?string $merchant_order_id = null,
        public readonly float $amount,
        public readonly ?string $message = null
    ) {}
}