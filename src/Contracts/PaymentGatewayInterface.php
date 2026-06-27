<?php
namespace Hammam\PaymentGateways\Contracts;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\DTOs\PaymentResponse;

interface PaymentGatewayInterface
{
    public function authenticate(): array | \Exception;
    public function pay(PaymentData $data): string | \Exception;
    public function refund(string $transactionId, float $amount): bool;
    public function verify(array $callbackData): PaymentResponse;
}