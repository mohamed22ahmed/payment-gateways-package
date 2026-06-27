<?php

namespace Tests\Feature;

use Hammam\PaymentGateways\Facades\Payment;
use Tests\TestCase;

class FacadeTest extends TestCase
{
    public function test_resolves_facade() {
        expect(Payment::getFacadeRoot())->not->toBeNull();
    }
}