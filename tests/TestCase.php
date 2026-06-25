<?php
namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Hammam\PaymentGateways\Providers\PaymentGatewayServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PaymentGatewayServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('payments.default', 'paymob');

        $app['config']->set('paymob.api_key', 'test_key');
        $app['config']->set('paymob.integration_id', '123456');
    }
}