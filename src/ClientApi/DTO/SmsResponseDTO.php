<?php

namespace SmsClientPhp\ClientApi\DTO;

class SmsResponseDTO
{
    public function __construct(
        public readonly string $smsId
    ) {
    }
}
