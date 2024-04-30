<?php

use App\External\BinProvider\BinListProvider;
use App\External\BinProvider\BinListProviderInterface;
use App\External\Rates\RateListProvider;
use App\External\Rates\RateListProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

return [
    BinListProviderInterface::class => fn() => new BinListProvider(
        $_ENV['BIN_URL'],
        new Client(),
        new ArrayAdapter()
    ),
    RateListProviderInterface::class => fn() => new RateListProvider(
        $_ENV['EXCHANGE_RATE_URL'],
        $_ENV['EXCHANGE_RATE_API_KEY'],
        new Client(),
        new ArrayAdapter()
    )
];