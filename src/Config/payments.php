<?php
return [
    'default' => 'paymob',

    'gateways' => [
        'paymob' => [
            'enabled' => true,
            'base_url' => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/api'),
            'api_key' => env('PAYMOB_API_KEY'),
            'integration_id' => env('PAYMOB_INTEGRATION_ID'),
        ],
    ]

];