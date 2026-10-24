<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storefront Details
    |--------------------------------------------------------------------------
    |
    | Contact information and currency used across the storefront, the footer
    | and the admin panel.
    |
    */

    'name' => env('APP_NAME', 'StationeryHub'),

    'currency_code' => env('STORE_CURRENCY_CODE', 'BDT'),

    'currency_symbol' => env('STORE_CURRENCY_SYMBOL', '৳'),

    /*
    | Order value above which delivery is free, in the currency above.
    */
    'free_delivery_threshold' => (float) env('STORE_FREE_DELIVERY_THRESHOLD', 2000),

    'contact' => [
        'email' => env('STORE_CONTACT_EMAIL', 'hello@stationeryhub.test'),
        'phone' => env('STORE_CONTACT_PHONE', '+880 1712 345678'),
        'address' => env('STORE_CONTACT_ADDRESS', '42 Elephant Road, Dhaka 1205, Bangladesh'),
    ],

    'social' => [
        'facebook' => env('STORE_SOCIAL_FACEBOOK', 'https://facebook.com'),
        'instagram' => env('STORE_SOCIAL_INSTAGRAM', 'https://instagram.com'),
        'twitter' => env('STORE_SOCIAL_TWITTER', 'https://x.com'),
    ],

];
