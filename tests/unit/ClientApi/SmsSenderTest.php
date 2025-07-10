<?php

namespace Tests\SmsClientPhp\ClientApi;

use PHPUnit\Framework\TestCase;
use SmsClientPhp\ClientApi\SmsSender;
use SmsClientPhp\ClientApi\ApiUrls;
use SmsClientPhp\ClientApi\DTO\SmsResponseDTO;
use SmsClientPhp\ClientApi\Exceptions\SmsException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SmsSenderTest extends TestCase
{
    /**
     * @var HttpClientInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $clientMock;

    /**
     * @var ApiUrls&\PHPUnit\Framework\MockObject\MockObject
     */
    private $apiUrlsMock;

    /**
     * @var ResponseInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $responseMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(HttpClientInterface::class);
        $this->apiUrlsMock = $this->createMock(ApiUrls::class);
        $this->apiUrlsMock->method('sendSmsUrl')->willReturn('https://fake-api.test/send');
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }

    public function testSendSmsSuccess(): void
    {
        $jsonResponse = json_encode([
            'success' => true,
            'data' => [
                'smsId' => 'abc123'
            ]
        ]);

        $this->responseMock
            ->method('getContent')
            ->willReturn($jsonResponse);

        $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://fake-api.test/send',
                $this->callback(function ($options) {
                    return isset($options['json']['phoneNumber']) && isset($options['json']['messageText']);
                })
            )
            ->willReturn($this->responseMock);

        $smsSender = new SmsSender($this->clientMock, $this->apiUrlsMock);

        $result = $smsSender->sendSms('+1234567890', 'Hello world');

        $this->assertInstanceOf(SmsResponseDTO::class, $result);
        $this->assertEquals('abc123', $result->smsId);
    }

    public function testSendSmsInvalidJsonThrowsException(): void
    {
        $this->responseMock
            ->method('getContent')
            ->willReturn('invalid json');

        $this->clientMock
            ->method('request')
            ->willReturn($this->responseMock);

        $smsSender = new SmsSender($this->clientMock, $this->apiUrlsMock);

        $this->expectException(SmsException::class);
        $this->expectExceptionMessage('Réponse JSON invalide');

        $smsSender->sendSms('+1234567890', 'Hello world');
    }

    public function testSendSmsFailureResponseThrowsException(): void
    {
        $jsonResponse = json_encode([
            'success' => false,
            'error' => 'Something went wrong'
        ]);

        $this->responseMock
            ->method('getContent')
            ->with(false)
            ->willReturn($jsonResponse);

        $this->clientMock
            ->method('request')
            ->willReturn($this->responseMock);

        $smsSender = new SmsSender($this->clientMock, $this->apiUrlsMock);

        $this->expectException(SmsException::class);
        $this->expectExceptionMessage('Something went wrong');

        $smsSender->sendSms('+1234567890', 'Hello world');
    }
}
