<?php

namespace Tests\Domain\Currency;

use App\Domain\Currency\EuCurrencyChecker;
use PHPUnit\Framework\TestCase;

class EuCurrencyCheckerTest extends TestCase
{

    public static function eurCases()
    {
        return [
            ['EUR', true],
            ['USD', false]
        ];
    }

    /**
     * @dataProvider eurCases
     */
    public function testIsEUROk(string $currency, bool $expected)
    {
        $euCurrencyChecker = new EuCurrencyChecker();

        $actual = $euCurrencyChecker->isEUR($currency);

        $this->assertEquals($expected, $actual);
    }


    public static function countryCases()
    {
        return [
            ['SK', true],
            ['US', false],
            ['UK', false],
            ['DK', true],
            ['LT', true],
        ];
    }

    /**
     * @dataProvider countryCases
     */
    public function testIsEUCountry(string $country, bool $expected)
    {

        $euCurrencyChecker = new EuCurrencyChecker();

        $actual = $euCurrencyChecker->isEUCountry($country);

        $this->assertEquals($expected, $actual);
    }
}
