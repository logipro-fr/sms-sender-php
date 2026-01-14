<?php

namespace Tests\SmsClientPhp\ClientApi;

use PHPUnit\Framework\TestCase;
use SmsClientPhp\ClientApi\ApiUrls;

class ApiUrlsTest extends TestCase
{
    public function testSendSmsUrlAddsSuffixWhenNoTrailingSlash(): void
    {
        $baseUrl = 'https://example.com';
        $apiUrls = new ApiUrls($baseUrl);

        $expected = 'https://example.com/api/v1/sms/send';
        $this->assertSame($expected, $apiUrls->sendSmsUrl());
    }

    public function testSendSmsUrlRemovesTrailingSlashBeforeAddingSuffix(): void
    {
        $baseUrl = 'https://example.com/';
        $apiUrls = new ApiUrls($baseUrl);

        $expected = 'https://example.com/api/v1/sms/send';
        $this->assertSame($expected, $apiUrls->sendSmsUrl());
    }
}
