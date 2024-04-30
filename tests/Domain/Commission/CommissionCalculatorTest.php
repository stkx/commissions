<?php

namespace Tests\Domain\Commission;

use App\Domain\Commission\CommissionCalculator;
use App\Domain\Currency\EuCurrencyChecker;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->checker = $this->createMock(EuCurrencyChecker::class);

        $this->object = new CommissionCalculator($this->checker);

    }

    public function testEuro()
    {
        $this->checker->method('isEUR')->willReturn(true);
        $this->checker->method('isEUCountry')->willReturn(true);

        $result = $this->object->calculate('100', 'US', 'EUR', '0');

        $this->assertEquals('1.00', $result);

    }

    public function testNonEuro()
    {
        $this->checker->method('isEUR')->willReturn(false);
        $this->checker->method('isEUCountry')->willReturn(true);

        $result = $this->object->calculate('500', 'US', 'EUR', '5');

        $this->assertEquals('1.00', $result);
    }

    public function testNonZeroRate()
    {
        $this->checker->method('isEUR')->willReturn(false);
        $this->checker->method('isEUCountry')->willReturn(true);

        $result = $this->object->calculate('200', 'US', 'EUR', '2');

        $this->assertEquals('1.00', $result);

    }

    public function testEUCountry()
    {

        $this->checker->method('isEUR')->willReturn(true);
        $this->checker->method('isEUCountry')->willReturn(true);

        $result = $this->object->calculate('100', 'US', 'EUR', '0');

        $this->assertEquals('1.00', $result);
    }

    public function testNonEUCountry()
    {

        $this->checker->method('isEUR')->willReturn(true);
        $this->checker->method('isEUCountry')->willReturn(false);

        $result = $this->object->calculate('100', 'US', 'EUR', '0');

        $this->assertEquals('2.00', $result);
    }
}
