<?php

namespace SmsClientPhp\ClientApi\DTO;

class SmsResponseDTO
{
    private string $messageId;
    private string $status;

    public function __construct(string $smsId, string $status = 'success')
    {
        $this->messageId = $smsId;
        $this->status = $status;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
