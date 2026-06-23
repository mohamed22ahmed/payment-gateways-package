<?php

namespace Hammam\PaymentGateways\DTOs;

class PaymentResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $transactionId,
        public readonly ?string $checkoutUrl = null,
        public readonly ?string $message = null
    ) {}
}