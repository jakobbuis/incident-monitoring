<?php

return [
    'account' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_ACCOUNT_TOKEN'),
    ],
    'from' => config('TWILIO_FROM_NUMBER'),
];
