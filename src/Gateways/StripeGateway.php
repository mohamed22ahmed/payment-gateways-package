<?php

namespace Hammam\PaymentGateways\Gateways;

use Hammam\PaymentGateways\Contracts\PaymentGatewayInterface;
use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\DTOs\PaymentResponse;
use Stripe\StripeClient;
class StripeGateway implements PaymentGatewayInterface
{

    protected StripeClient $stripe;
    protected array $config;

    public function __construct()
    {
        $this->config = config('payments.gateways.stripe');
        $this->stripe = new StripeClient($this->config['secret_key']);
    }

    public function setStripeClient(StripeClient $stripe): void
    {
        $this->stripe = $stripe;
    }

    public function pay(PaymentData $data): string
    {
        try {
            $billingData = $data->billingData ?? [];
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'customer_email' => $data->customerEmail ?? ($billingData['email'] ?? null),
                'client_reference_id' => $data->orderId,
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($this->config['currency']),
                        'product_data' => [
                            'name' => 'Order #' . $data->orderId,
                        ],
                        'unit_amount' => $data->amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $billingData['success_url'] ?? 'https://example.com/success',
                'cancel_url' => $billingData['cancel_url'] ?? 'https://example.com/cancel',
            ]);

            return $session->url;
        } catch (\Exception $e) {
            throw new \Exception('Stripe Payment Initiation Failed: ' . $e->getMessage());
        }
    }

    public function verify(array $callbackData): PaymentResponse
    {
        try {
            $signatureHeader = $callbackData['signature'] ?? '';
            $rawPayload = json_encode($callbackData);

            $event = \Stripe\Webhook::constructEvent(
                $rawPayload,
                $signatureHeader,
                $this->config['webhook_secret']
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                return new PaymentResponse(
                    true,
                    $session->payment_intent,
                    $session->client_reference_id,
                    $session->amount_total / 100,
                    'Payment completed successfully via Stripe.'
                );
            }

            return new PaymentResponse(false, null, '', 0.0, 'Unhandled Stripe event type: ' . $event->type);
        } catch (\UnexpectedValueException $e) {
            return new PaymentResponse(false, null, null, 0.0, 'Invalid Stripe payload data structure.');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return new PaymentResponse(false, null, null, 0.0, 'Stripe signature verification failed.');
        }
    }

    public function refund(string $transactionId, float $amount): bool
    {
        try {
            $this->stripe->refunds->create([
                'payment_intent' => $transactionId,
                'amount' => $amount * 100,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Stripe Refund Execution Failed: ' . $e->getMessage());
        }
    }
}