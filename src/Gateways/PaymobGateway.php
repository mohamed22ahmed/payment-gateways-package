<?php

namespace Hammam\PaymentGateways\Gateways;

use Hammam\PaymentGateways\Contracts\PaymentGatewayInterface;
use Hammam\PaymentGateways\DTOs\PaymentData;
use Hammam\PaymentGateways\DTOs\PaymentResponse;
use Illuminate\Support\Facades\Http;

class PaymobGateway implements PaymentGatewayInterface
{
    private string $baseUrl;
    private array $config;
    private $httpClient;

    public function __construct() {
        $this->config = config('payments.gateways.paymob');
        $this->baseUrl = $this->config['base_url'];
        $this->httpClient = Http::getFacadeRoot();
    }

    public function setHttpClient($httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function authenticate(): array|\Exception
    {
        try {
            $response = $this->httpClient->post("{$this->baseUrl}/auth/tokens", [
                'api_key' => $this->config['api_key']
            ]);

            return $response->json();
        }catch (\Exception $e) {
            throw new \Exception('Paymob Authentication Failed: ' . $e->getMessage());
        }
    }

    public function pay(PaymentData $data): string|\Exception
    {
        $token = $this->authenticate()['token'];
        $billingData = $data->billingData ?? [];

        $orderResponse = $this->httpClient->post("{$this->baseUrl}/ecommerce/orders", [
            'auth_token' => $token,
            'delivery_needed' => 'false',
            'amount_cents' => $data->amount * 100,
            'currency' => $data->currency ?? $this->config['currency'],
            'merchant_order_id' => $data->orderId ?? uniqid(),
        ]);

        if ($orderResponse->failed()) {
            throw new \Exception('Paymob Order Registration Failed: ' . $orderResponse->body());
        }

        $finalBillingData = [
            'first_name'   => !empty($billingData['first_name']) ? $billingData['first_name'] : 'NA',
            'last_name'    => !empty($billingData['last_name']) ? $billingData['last_name'] : 'NA',
            'email'        => !empty($billingData['email']) ? $billingData['email'] : 'na@example.com',
            'phone_number' => !empty($billingData['phone_number']) ? $billingData['phone_number'] : '00000000000',
            'apartment'    => !empty($billingData['apartment']) ? $billingData['apartment'] : 'NA',
            'floor'        => !empty($billingData['floor']) ? $billingData['floor'] : 'NA',
            'street'       => !empty($billingData['street']) ? $billingData['street'] : 'NA',
            'building'     => !empty($billingData['building']) ? $billingData['building'] : 'NA',
            'shipping_method' => 'NA',
            'postal_code'  => !empty($billingData['postal_code']) ? $billingData['postal_code'] : 'NA',
            'city'         => !empty($billingData['city']) ? $billingData['city'] : 'NA',
            'country'      => !empty($billingData['country']) ? $billingData['country'] : 'NA',
            'state'        => !empty($billingData['state']) ? $billingData['state'] : 'NA',
        ];

        $orderId = $orderResponse->json()['id'];

        $paymentKeyResponse = $this->httpClient->post("{$this->baseUrl}/acceptance/payment_keys", [
            'auth_token' => $token,
            'amount_cents' => $data->amount * 100,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $finalBillingData,
            'currency' => $data->currency ?? $this->config['currency'],
            'integration_id' => $this->config['integration_id'],
        ]);

        if ($paymentKeyResponse->failed()) {
            throw new \Exception('Paymob Payment Key Generation Failed: ' . $paymentKeyResponse->body());
        }

        $paymentToken = $paymentKeyResponse->json()['token'];

        return "{$this->baseUrl}/acceptance/iframes/{$this->config['iframe_id']}?payment_token={$paymentToken}";
    }

    public function verify(array $callbackData): PaymentResponse
    {
        $isWebhook = isset($callbackData['obj']);
        $obj = $isWebhook ? $callbackData['obj'] : $callbackData;
        $hmacSource = $callbackData['hmac'] ?? null;

        if (!$hmacSource) {
            return new PaymentResponse(false, null, null, 0.00, 'HMAC source is missing.');
        }

        $dataToHash =
            ($obj['amount_cents'] ?? '') .
            ($obj['created_at'] ?? '') .
            ($obj['currency'] ?? '') .
            ($obj['error_occured'] ?? '') .
            ($obj['has_parent_transaction'] ?? '') .
            ($obj['id'] ?? '') .
            ($obj['integration_id'] ?? '') .
            ($obj['is_3d_secure'] ?? '') .
            ($obj['is_auth'] ?? '') .
            ($obj['is_capture'] ?? '') .
            ($obj['is_refunded'] ?? '') .
            ($obj['is_standalone_payment'] ?? '') .
            ($obj['is_voided'] ?? '') .
            ($isWebhook ? ($obj['order']['id'] ?? '') : ($obj['order'] ?? '')) .
            ($obj['owner'] ?? '') .
            ($obj['pending'] ?? '') .
            ($obj['source_data_pan'] ?? $obj['source_data']['pan'] ?? '') .
            ($obj['source_data_sub_type'] ?? $obj['source_data']['sub_type'] ?? '') .
            ($obj['source_data_type'] ?? $obj['source_data']['type'] ?? '') .
            ($obj['success'] ?? '');

        $calculatedHmac = hash_hmac('sha512', $dataToHash, $this->config['hmac_secret']);

        if (!hash_equals($calculatedHmac, $hmacSource)) {
            return new PaymentResponse(false, null, null, 0.00, 'HMAC verification failed.');
        }

        $isSuccess = filter_var($obj['success'], FILTER_VALIDATE_BOOLEAN);

        return new PaymentResponse(
            $isSuccess,
            $obj['id'],
            $isWebhook ? ($obj['order']['merchant_order_id'] ?? null) : ($obj['merchant_order_id'] ?? null),
            $obj['amount_cents'] / 100,
            $isSuccess ? 'Payment completed successfully.' : 'Payment failed or remains pending.'
        );
    }

    public function refund(string $transactionId, float $amount): bool
    {
        $token = $this->authenticate()['token'];

        $response = $this->httpClient->post("{$this->baseUrl}/acceptance/void_refund/refund", [
            'auth_token' => $token,
            'transaction_id' => $transactionId,
            'amount_cents' => $amount * 100
        ]);

        return $response->json()['success'];
    }
}