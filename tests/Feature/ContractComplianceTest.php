<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContractComplianceTest extends TestCase
{
    public function test_loads_config_file() {
        expect(config('payments.default'))->not->toBeNull();
    }
}