<?php

namespace App\External\BinProvider;


use GuzzleHttp\ClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class BinListProvider implements BinListProviderInterface
{
    public function __construct(
        private readonly string          $binUrl,
        private readonly ClientInterface $client,
        private readonly CacheInterface  $cache
    )
    {
    }

    public function getByBinValue(string $binValue): BinData
    {
        $response = $this->cache->get(
            sprintf('rates:%s', $binValue),
            fn() => $this->client
                ->request('GET', sprintf('%s/%s', $this->binUrl, $binValue))
                ->getBody()
                ->getContents()
        );

        $decoded = json_decode($response, true);

        if (!$decoded) {
            throw new \Exception('Empty response');
        }

        return new BinData($decoded['country']['alpha2']);
    }
}