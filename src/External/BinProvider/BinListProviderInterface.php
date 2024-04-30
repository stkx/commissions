<?php

namespace App\External\BinProvider;

interface BinListProviderInterface
{

    /**
     * @throws \Exception
     */
    public function getByBinValue(string $binValue): BinData;
}