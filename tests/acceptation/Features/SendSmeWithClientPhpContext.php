<?php

namespace Tests\Features\SmsClientPhp;

use Behat\Behat\Context\Context;
use SmsClientPhp\ClientApi\SmsClientPHP;
use SmsClientPhp\ClientApi\SmsSender;
use SmsClientPhp\ClientApi\DTO\SmsResponseDTO;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;

final class SendSmeWithClientPhpContext implements Context
{
    private SmsClientPHP $smsClient;
    private string $phoneNumber = '';
    private string $message = '';
    /** @var array<string, string|null>|null */
    private ?array $responseData = null;

    public function __construct()
    {
        $smsSenderMock = new class extends SmsSender {
            public function __construct()
            {
            }
            public function sendSms(string $phoneNumber, string $messageText): SmsResponseDTO
            {
                return new SmsResponseDTO('mocked_sms_id_123');
            }
        };

        $this->smsClient = new SmsClientPHP($smsSenderMock);
    }

    #[Given('I have a valid phone number :phone')]
    public function iHaveAValidPhoneNumber(string $phone): void
    {
        $this->phoneNumber = $phone;
    }

    #[Given('I have a message :message')]
    public function iHaveAMessage(string $message): void
    {
        $this->message = $message;
    }

    #[When('I send the SMS')]
    public function iSendTheSms(): void
    {
        $dto = $this->smsClient->clientSenderSms($this->phoneNumber, $this->message);

        $this->responseData = [
            'statusMessage' => $dto->getStatus(),
            'smsId' => $dto->getMessageId(),
        ];
    }

    #[Then('a message ID should be returned')]
    public function aMessageIdShouldBeReturned(): void
    {
        if (empty($this->responseData['smsId'])) {
            throw new \RuntimeException('No message ID was returned.');
        }
    }
}
