<?php

namespace Tests\Unit\DTOs;

use Hammam\PaymentGateways\DTOs\PaymentData;
use Tests\TestCase;

class PaymentDataTest extends TestCase
{
    public function test_creates_payment_data_with_valid_data() {
        $dto = new PaymentData(
            orderId: '123',
            amount: 100,
            currency: 'EGP',
            customerName: 'Memo',
            customerEmail: 'memo@gmail.com',
            customerPhone: '+20123654799',
        );

        expect($dto->amount)->toBe(100.0)
            ->and($dto->currency)->toBe('EGP');
    }
}