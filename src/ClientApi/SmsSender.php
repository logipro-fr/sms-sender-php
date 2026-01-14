<?php

namespace SmsClientPhp\ClientApi;

use SmsClientPhp\ClientApi\ApiUrls;
use SmsClientPhp\ClientApi\DTO\SmsResponseDTO;
use SmsClientPhp\ClientApi\Exceptions\SmsApiBadReceiversException;
use SmsClientPhp\ClientApi\Exceptions\SmsApiInvalidApiKeyException;
use SmsClientPhp\ClientApi\Exceptions\SmsApiNoCreditException;
use SmsClientPhp\ClientApi\Exceptions\SmsException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsSender
{
    public function __construct(
        protected HttpClientInterface $client,
        protected ApiUrls $apiUrls
    ) {
    }

    public function sendSms(string $phoneNumber, string $messageText): SmsResponseDTO
    {
        $options = [
            'json' => [
                'phoneNumber' => $phoneNumber,
                'messageText' => $messageText,
            ],
        ];

        $response = $this->client->request(
            'POST',
            $this->apiUrls->sendSmsUrl(),
            $options
        );

        $contentRaw = $response->getContent(false);

        $content = json_decode($contentRaw, true);

        if (!is_array($content)) {
            throw new SmsException('Réponse JSON invalide');
        }

        if (!isset($content['success']) || $content['success'] !== true) {
            $error = $content['error'] ?? 'Unknown error';

            match ($error) {
                'badReceivers' => throw new SmsApiBadReceiversException('Numéro de téléphone invalide ou non supporté.'),
                'noCredit' => throw new SmsApiNoCreditException('Crédit insuffisant pour envoyer le SMS.'),
                'invalidApiKey' => throw new SmsApiInvalidApiKeyException('Clé API invalide ou non autorisée.'),
                default => throw new SmsException($error),
            };
        }


        return new SmsResponseDTO($content['data']['smsId'] ?? '');
    }
}
