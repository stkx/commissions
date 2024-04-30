<?php

namespace Tests\External\BinProvider;

use App\External\BinProvider\BinListProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class BinListProviderTest extends TestCase
{

    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);

        $this->cache = $this->createMock(CacheInterface::class);

        $this->object = new BinListProvider('', $this->client, $this->cache);
    }

    public function testDecodedProperly()
    {
        $this->cache
            ->method('get')
            ->willReturn(
                '{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}'
            );


        $result = $this->object->getByBinValue('USD');

        $this->assertEquals('DK', $result->countryAlpha);

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
        $this->client
            ->method('request')
            ->willReturn(
                new Response(body: $text)
            );

        $this->expectException(\Throwable::class);

        $this->object->getByBinValue('USD');
    }
}
