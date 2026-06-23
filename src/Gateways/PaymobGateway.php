<?php

namespace Hammam\PaymentGateways\Gateways;

use Hammam\PaymentGateways\Contracts\PaymentGatewayInterface;
use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\DTOs\PaymentResponse;

class PaymobGateway implements PaymentGatewayInterface
{
    public function pay(PaymentData $data): PaymentResponse {
        return new PaymentResponse(
            success: true,
            transactionId: 'PM123',
            checkoutUrl: 'https://accept.paymob.com/...'
        );
    }

    public function refund(string $transactionId, float $amount): bool {
        return true;
    }

    public function verify($data): bool {
        return true;
    }
}