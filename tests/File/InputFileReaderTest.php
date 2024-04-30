<?php

namespace Tests\File;

use App\File\FileReader;
use App\File\InputDataProvider;
use PHPUnit\Framework\TestCase;

class InputFileReaderTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fileReader = $this->createMock(FileReader::class);

        $this->object = new InputDataProvider($this->fileReader);

        parent::setUp();
    }


    public function testIsDecodedProperly()
    {

        $this->fileReader
            ->method('readFile')
            ->willReturn([
                '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                '{"bin":"516793","amount":"50.00","currency":"USD"}',
            ]);

        $result = $this->object->readFile('');

        $this->assertEquals([
            ['bin' => '45717360', 'amount' => '100.00', 'currency' => 'EUR'],
            ['bin' => '516793', 'amount' => '50.00', 'currency' => 'USD'],
        ], $result);
    }

    public static function badTextCases()
    {
        return [['asdasdasd'], ['','asd']];
    }

    /**
     * @dataProvider badTextCases
     */
    public function testNoLineOnEmptyValue(string $text)
    {
        $this->fileReader
            ->method('readFile')
            ->willReturn([
                $text,
            ]);

        $result = $this->object->readFile('');

        $this->assertEquals([], $result);
    }
}
