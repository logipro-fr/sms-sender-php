<?php

namespace Tests\SmsClientPhp\ClientApi;

use PHPUnit\Framework\TestCase;
use SmsClientPhp\ClientApi\SmsClientPHP;
use SmsClientPhp\ClientApi\SmsSender;
use SmsClientPhp\ClientApi\DTO\SmsResponseDTO;

class SmsClientPHPTest extends TestCase
{
    public function testSendSmsToSmsSender(): void
    {
        $phoneNumber = '+1234567890';
        $messageText = 'Hello world';
        $expectedResponse = new SmsResponseDTO('message-id-123');

        $smsSenderMock = $this->createMock(SmsSender::class);
        $smsSenderMock->expects($this->once())
            ->method('sendSms')
            ->with($phoneNumber, $messageText)
            ->willReturn($expectedResponse);

        $smsClient = new SmsClientPHP($smsSenderMock);
        $result = $smsClient->clientSenderSms($phoneNumber, $messageText);

        $this->assertSame($expectedResponse, $result);
    }
}
