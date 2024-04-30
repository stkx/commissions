<?php

namespace App\Domain\Commission;


use App\Domain\Currency\EuCurrencyChecker;
use App\Math\ValueComparator;

class CommissionCalculator
{
    private EuCurrencyChecker $checker;

    public function __construct(EuCurrencyChecker $checker)
    {
        $this->checker = $checker;
    }

    public function calculate(string $amount, string $countryCode, string $currencyCode, string $rate): string
    {
        if ($this->checker->isEUR($currencyCode) ||
            !ValueComparator::isBiggerThanNull($rate)
        ) {
            $amountInEuros = $amount;
        } else {
            $amountInEuros = bcdiv($amount, $rate);
        }

        $isEU = $this->checker->isEUCountry($countryCode);
        $commissionModifier = $isEU ? 0.01 : 0.02;

        return bcmul($amountInEuros, $commissionModifier, 2);

    }
}