<?php

namespace Tests\Unit\DTOs;

use Hammam\PaymentGateways\DTOs\PaymentResponse;
use Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    public function test_creates_successful_response(){
        $response = new PaymentResponse(
            success: true,
            transactionId: '123',
            merchant_order_id: null,
            amount: 20.00,
            message: 'Payment completed successfully.'
        );

        expect($response->success)->toBeTrue();
    }

    public function test_creates_failed_response(){
        $response = new PaymentResponse(
            success: false,
            transactionId: '123',
            merchant_order_id: null,
            amount: 00.00,
            message: 'Payment Failed.'
        );

        expect($response->success)->toBeFalse();
    }

    public function test_creates_response_with_transaction_id(){
        $response = new PaymentResponse(
            success: true,
            transactionId: '123',
            merchant_order_id: null,
            amount: 20.00,
            message: 'Payment completed successfully.'
        );

        expect($response->transactionId)->toBe('123');
    }
}