<?php
namespace Hammam\PaymentGateways\contracts;

interface PaymentGatewayInterface
{
    public function pay($data);
    public function refund(string $transactionId, float $amount): bool;
    public function verify($data): bool;
}