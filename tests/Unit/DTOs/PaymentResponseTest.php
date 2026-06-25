<?php

namespace Tests\Unit\DTOs;

use Hammam\PaymentGateways\DTOs\PaymentResponse;
use Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    //TODO: Failed response
    //TODO: Redirect URL exists
    //TODO: Metadata serialization

    public function test_creates_successful_response(){
        $response = new PaymentResponse(
            success: true,
            transactionId: '123'
        );

        expect($response->success)->toBeTrue();
    }
}