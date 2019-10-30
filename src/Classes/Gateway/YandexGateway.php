<?php

namespace App\Classes\Gateway;

use Symfony\Component\HttpClient\HttpClient;

class YandexGateway implements IGateway
{
    /** @const GATEWAY_URL */
    private const GATEWAY_URL = 'https://ya.ru';
    /** @const GATEWAY_METHOD */
    private const GATEWAY_METHOD = 'GET';
    /** @const GATEWAY_CORRECT_RESPONSE_STATUS */
    private const GATEWAY_CORRECT_RESPONSE_STATUS = 200;

    /** @var  $httpClient HttpClient */
    private $httpClient;

    /**
     * YandexGateway constructor.
     */
    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    /**
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function process(): bool
    {
        $response = $this->httpClient->request(self::GATEWAY_METHOD, self::GATEWAY_URL);
        return self::GATEWAY_CORRECT_RESPONSE_STATUS === $response->getStatusCode();
    }
}