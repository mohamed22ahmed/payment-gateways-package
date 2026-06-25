<?php

namespace Tests\Feature;

use Hammam\PaymentGateways\Facades\Payment;
use Tests\TestCase;

class FacadeTest extends TestCase
{
    //TODO: Facade resolves manager
    //TODO: Facade methods work

    public function test_resolves_facade() {
        expect(Payment::getFacadeRoot())->not->toBeNull();
    }
}