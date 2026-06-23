<?php

namespace Hammam\PaymentGateways\Providers;

use Hammam\PaymentGateways\Factories\PaymentGatewayFactory;
use Hammam\PaymentGateways\Managers\PaymentManager;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/payments.php',
            'payments'
        );

        $this->app->singleton(
            PaymentGatewayFactory::class,
            fn () => new PaymentGatewayFactory()
        );

        $this->app->singleton(
            'payment-manager',
            fn ($app) => new PaymentManager(
                $app->make(PaymentGatewayFactory::class)
            )
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Config/payments.php'
            => config_path('payments.php'),
        ], 'payment-gateways-config');
    }
}