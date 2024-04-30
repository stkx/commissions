<?php

namespace Tests\External\Rates;

use App\External\Rates\RateListProvider;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class RateListProviderTest extends TestCase
{


    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);

        $this->cache = $this->createMock(CacheInterface::class);

        $this->object = new RateListProvider('', '', $this->client, $this->cache);
    }

    public function testDecodedProperly()
    {
        $this->cache->method('get')->willReturn('{"rates":{"USD":150}}');

        $result = $this->object->getLatestRateByCurrency('USD');

        $this->assertEquals('150', $result);

    }

    public static function badTextCases()
    {
        return [['asdasdasd'], ['']];
    }

    /**
     * @dataProvider badTextCases
     */
    public function testExceptionIsThrown(string $text)
    {
        $this->cache->method('get')->willReturn($text);

        $this->expectException(\Throwable::class);

        $this->object->getLatestRateByCurrency('USD');
    }
}
