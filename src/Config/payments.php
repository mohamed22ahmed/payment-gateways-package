<?php
return [
    'default' => 'paymob',

    'gateways' => [
        'paymob' => [
            'enabled' => true,
            'api_key' => env('PAYMOB_API_KEY', 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRFNE9EazBNU3dpYm1GdFpTSTZJbWx1YVhScFlXd2lmUS5PZkI3WndnRmtkWng4VU1jSk9mQjlmNlVFaDlSaVY1blRuWDd2LU9CUTlDVVFLcmR6aThuUW8wbVFMd3VvTW9pUUJhX2NmaEdqd1QxcnFOZjMyQ2Vkdw=='),
            'base_url' => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/api'),
            'integration_id' => env('PAYMOB_INTEGRATION_ID', '5753631'),
            'iframe_id' => env('PAYMOB_IFRAME_ID', '1056220'),
            'hmac_secret' => env('PAYMOB_HMAC_SECRET', '1AE6DBD5DEA73E409979BB2A5E0DDCFA'),
            'currency' => env('PAYMOB_CURRENCY', 'EGP')
        ],

        'stripe' => [
            'enabled' => true,
            'secret_key' => env('STRIPE_SECRET_KEY', 'sk_test_your_stripe_secret_key'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', 'whsec_your_webhook_secret'),
            'currency' => env('STRIPE_CURRENCY', 'USD')
        ],
    ]
];