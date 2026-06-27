<?php

namespace Tests\Unit\DTOs;

use Hammam\PaymentGateways\DTOs\PaymentResponse;
use Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    public function test_creates_successful_response(){
        $response = new PaymentResponse(
            success: true,
            transactionId: '123'
        );

        expect($response->success)->toBeTrue();
    }

    public function test_creates_failed_response(){
        $response = new PaymentResponse(
            success: false,
            transactionId: '123'
        );

        expect($response->success)->toBeFalse();
    }

    public function test_creates_response_with_transaction_id(){
        $response = new PaymentResponse(
            success: true,
            transactionId: '123'
        );

        expect($response->transactionId)->toBe('123');
    }

    public function test_redirect_url_exists()
    {
        $response = new PaymentResponse(
            success: true,
            transactionId: '123',
            checkoutUrl: 'https://accept.paymob.com'
        );
        expect($response->checkoutUrl)->toBe('https://accept.paymob.com');
    }
}