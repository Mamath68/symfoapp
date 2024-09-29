<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YugiohProdeck
{
    private HttpClientInterface $httpClient;
    private string $apiUrl = 'https://db.ygoprodeck.com/api/v7/cardinfo.php?';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCards(array $params = []): array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl, [
            'query' => $params,
        ]);

        return $response->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCardById(int $id): array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl, [
            'query' => ['id' => $id],
        ]);

        $data = $response->toArray();
        return $data['data'][0] ?? [];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCardsByArchetype(string $archetype): array
    {
        $archetype = str_replace('-', '/', $archetype);
        $response = $this->httpClient->request('GET', $this->apiUrl, [
            'query' => ['archetype' => $archetype],
        ]);

        return $response->toArray()['data'] ?? [];
    }
}