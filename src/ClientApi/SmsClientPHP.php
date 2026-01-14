<?php

namespace SmsClientPhp\ClientApi;

use SmsClientPhp\ClientApi\DTO\SmsResponseDTO;

class SmsClientPHP
{
    public function __construct(protected SmsSender $smsSender)
    {
    }

    public function clientSenderSms(string $phoneNumber, string $messageText): SmsResponseDTO
    {
        return $this->smsSender->sendSms($phoneNumber, $messageText);
    }
}
