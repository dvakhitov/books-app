<?php

namespace app\services;

final readonly class SmsService
{
    private string $endpoint;
    private string $apiKey;

    public function __construct(string $apiKey = 'EMULATOR', string $endpoint = 'https://smspilot.ru/api.php')
    {
        $this->apiKey   = $apiKey;
        $this->endpoint = $endpoint;
    }

    public function send(string $phone, string $message): bool
    {
        $url = $this->endpoint
            . '?send=' . urlencode($message)
            . '&to=' . urlencode($phone)
            . '&apikey=' . urlencode($this->apiKey);

        $result = @file_get_contents($url);

        return $result !== false;
    }
}
