<?php

namespace SmsClientPhp\ClientApi;

class ApiUrls
{
    public function __construct(
        protected string $baseUrl
    ) {
    }

    public function sendSmsUrl(): string
    {
        return rtrim($this->baseUrl, '/') . '/api/v1/sms/send';
    }
}
