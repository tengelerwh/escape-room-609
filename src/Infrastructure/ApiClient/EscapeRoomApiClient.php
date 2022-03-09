<?php

declare(strict_types = 1);

namespace App\Infrastructure\ApiClient;

use App\DomainModel\ApiClient;
use App\DomainModel\Uuid;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JsonSerializable;

class EscapeRoomApiClient implements ApiClient
{
    private string $host;
    private string $apiVersion;
    private string $protocol;

    public function __construct(string $protocol, string $host, string $apiVersion)
    {
        $this->host = $host;
        $this->apiVersion = trim($apiVersion, '/:');
        $this->protocol = trim($protocol, '/');
    }

    public function getTimeLeft(Uuid $gameId): array
    {
        $payload = [
            'gameId' => $gameId->toString(),
        ];
        $response = $this->doGet('game/831ebf8b-a574-4f39-a77a-865aef07d103/show', []);
        return [];
    }

    private function doPost(string $endpoint, array $payload): Response
    {
        $url = $this->getUrl($endpoint);
        $headers = ['X-Foo' => 'Bar'];
        $body = json_encode($payload);
        $request = new Request('POST', $url, $headers, $body);
        $client = new Client([
            'timeout'  => 2.0,
        ]);
        return $client->send($request);
    }

    private function doGet(string $endpoint, array $additionalParameters): Response
    {
        $url = $this->getUrl($endpoint);

        $request = new Request('GET', $url);
        $client = new Client([
            'timeout'  => 2.0,
        ]);
        return $client->send($request);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getUrl(string $path): string
    {
        $path = ltrim($path,'/');
        return $this->protocol . '://' . $this->host . '/api/' . $this->apiVersion . '/' . $path;
    }
}
