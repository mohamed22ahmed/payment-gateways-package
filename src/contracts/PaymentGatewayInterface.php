<?php
namespace Hammam\PaymentGateways\contracts;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\DTOs\PaymentResponse;

interface PaymentGatewayInterface
{
    public function pay(PaymentData $data): PaymentResponse;
    public function refund(string $transactionId, float $amount): bool;
    public function verify($data): bool;
}