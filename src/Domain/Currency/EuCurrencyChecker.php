<?php

namespace App\Domain\Currency;

class EuCurrencyChecker
{
    private const EUR_CURRENCY_CODE = 'EUR';

    public function isEUR(string $currencyCode): bool
    {
        return $currencyCode === self::EUR_CURRENCY_CODE;
    }

    public function isEUCountry(string $currencyIso): bool
    {
        switch ($currencyIso) {
            case 'AT':
            case 'BE':
            case 'BG':
            case 'CY':
            case 'CZ':
            case 'DE':
            case 'DK':
            case 'EE':
            case 'ES':
            case 'FI':
            case 'FR':
            case 'GR':
            case 'HR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LT':
            case 'LU':
            case 'LV':
            case 'MT':
            case 'NL':
            case 'PO':
            case 'PT':
            case 'RO':
            case 'SE':
            case 'SI':
            case 'SK':
                return true;
            default:
                return false;
        }
    }

}