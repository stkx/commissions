<?php

namespace App\External\Rates;

interface RateListProviderInterface
{

    /**
     * @throws \Exception
     */
    public function getLatestRateByCurrency(string $currencyCode): string;
}