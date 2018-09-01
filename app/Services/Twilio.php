<?php

namespace App\Services;

class Twilio
{
    private $twilio;

    public function __construct()
    {
        $sid = config('twilio.account.sid');
        $token = config('twilio.account.token');

        $this->twilio = new \Twilio\Rest\Client($sid, $token);
    }

    public function sendSMS(string $number, string $message) : void
    {
        $this->twilio->messages->create($number, [
            'from' => config('twilio.from'),
            'body' => $body,
        ]);
    }
}
