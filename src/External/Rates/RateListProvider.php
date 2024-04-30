<?php

namespace App\External\Rates;

use GuzzleHttp\ClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class RateListProvider implements RateListProviderInterface
{


    public function __construct(
        private readonly string          $exchangeRateUrl,
        private readonly string          $apiKey,
        private readonly ClientInterface $client,
        private readonly CacheInterface  $cache)
    {

    }

    public function getLatestRateByCurrency(string $currencyCode): string
    {
        $result = $this->cache->get(
            'rates',
            fn() => $this->client
                ->request('GET', sprintf('%s/latest?access_key=%s', $this->exchangeRateUrl, $this->apiKey))
                ->getBody()
                ->getContents()

        );


        $decoded = json_decode($result, true);

        if (!$decoded) {
            throw new \Exception('Rate retrieval error');
        }

        return $decoded['rates'][$currencyCode];


    }
}