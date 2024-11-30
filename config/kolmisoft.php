<?php

// config for CODEIQBV/Kolmisoft
return [
    'api_url' => env('KOLMISOFT_API_URL', 'http://yourdomain.com/billing'),
    'username' => env('KOLMISOFT_USERNAME', 'admin'),
    'password' => env('KOLMISOFT_PASSWORD', 'admin1'),
    'auth_key' => env('KOLMISOFT_AUTH_KEY', 'asdfasdf'),
    'use_hash' => env('KOLMISOFT_USE_HASH', true),
    'return_raw' => env('KOLMISOFT_RETURN_RAW', false),
];
