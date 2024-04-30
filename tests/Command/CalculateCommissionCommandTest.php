<?php

namespace Tests\Command;

use App\Command\CalculateCommissionCommand;
use App\Domain\Commission\CommissionCalculator;
use App\Domain\Currency\EuCurrencyChecker;
use App\External\BinProvider\BinListProvider;
use App\External\Rates\RateListProvider;
use App\File\FileReader;
use App\File\InputDataProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CalculateCommissionCommandTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fileReader = $this->createMock(FileReader::class);
        $inputFileReader = new InputDataProvider($this->fileReader);

        $this->binListClient = $this->createMock(ClientInterface::class);
        $binListProvider = new BinListProvider('', $this->binListClient, new ArrayAdapter());

        $this->rateProviderClient = $this->createMock(ClientInterface::class);
        $rateProvider = new RateListProvider(
            '',
            '',
            $this->rateProviderClient,
            new ArrayAdapter(),
        );
        $commissionCalculator = new CommissionCalculator(new EuCurrencyChecker());

        $this->object = new CalculateCommissionCommand($inputFileReader, $binListProvider, $rateProvider, $commissionCalculator);

        parent::setUp();
    }


    public function testIsOk()
    {
        $this->fileReader
            ->method('readFile')
            ->willReturn([
                '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                '{"bin":"516793","amount":"50.00","currency":"USD"}',
                '{"bin":"45417360","amount":"10000.00","currency":"JPY"}'
            ]);


        $this->binListClient
            ->method('request')
            ->willReturnOnConsecutiveCalls(
                new Response(body: '{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}'),
                new Response(body: '{"number":{},"scheme":"mastercard","type":"debit","brand":"Debit Mastercard","country":{"numeric":"440","alpha2":"LT","name":"Lithuania","emoji":"🇱🇹","currency":"EUR","latitude":56,"longitude":24},"bank":{"name":"Swedbank Ab"}}'),
                new Response(body: '{"number":{},"scheme":"visa","type":"credit","brand":"Visa Classic","country":{"numeric":"392","alpha2":"JP","name":"Japan","emoji":"🇯🇵","currency":"JPY","latitude":36,"longitude":138},"bank":{"name":"Credit Saison Co., Ltd."}}'),
            );


        $this->rateProviderClient
            ->method('request')
            ->willReturn(
                new Response(body: '{"success":true,"timestamp":1714493164,"base":"EUR","date":"2024-04-30","rates":{"AED":3.923189,"AFN":76.37225,"ALL":100.244975,"AMD":414.440446,"ANG":1.925901,"AOA":890.897185,"ARS":936.478834,"AUD":1.64653,"AWG":1.923997,"AZN":1.814599,"BAM":1.948077,"BBD":2.157546,"BDT":117.275054,"BGN":1.950592,"BHD":0.402702,"BIF":3065.576887,"BMD":1.068145,"BND":1.454434,"BOB":7.383864,"BRL":5.531602,"BSD":1.068564,"BTC":1.7556116e-5,"BTN":89.184989,"BWP":15.167866,"BYN":3.497036,"BYR":20935.647034,"BZD":2.154031,"CAD":1.46886,"CDF":2990.806553,"CHF":0.980455,"CLF":0.037007,"CLP":1021.339413,"CNY":7.734549,"CNH":7.747712,"COP":4168.575724,"CRC":543.256391,"CUC":1.068145,"CUP":28.305849,"CVE":110.606266,"CZK":25.142857,"DJF":190.289147,"DKK":7.458078,"DOP":62.496542,"DZD":143.596073,"EGP":51.111285,"ERN":16.022179,"ETB":61.108358,"EUR":1,"FJD":2.441245,"FKP":0.857466,"GBP":0.853939,"GEL":2.867976,"GGP":0.857466,"GHS":14.58035,"GIP":0.857466,"GMD":72.366844,"GNF":9181.775996,"GTQ":8.306225,"GYD":223.687363,"HKD":8.354039,"HNL":26.516684,"HRK":7.56264,"HTG":141.698227,"HUF":390.994323,"IDR":17366.493066,"ILS":3.988667,"IMP":0.857466,"INR":89.160325,"IQD":1399.270286,"IRR":44928.862208,"ISK":149.914169,"JEP":0.857466,"JMD":166.822524,"JOD":0.756992,"JPY":168.288954,"KES":144.199918,"KGS":94.725368,"KHR":4340.942652,"KMF":490.592432,"KPW":961.331105,"KRW":1479.242282,"KWD":0.329245,"KYD":0.89047,"KZT":472.994776,"LAK":22804.900814,"LBP":95705.815275,"LKR":316.853809,"LRD":206.846206,"LSL":19.910097,"LTL":3.153955,"LVL":0.64611,"LYD":5.207203,"MAD":10.795209,"MDL":18.860667,"MGA":4741.497076,"MKD":61.366707,"MMK":2244.003477,"MNT":3685.101506,"MOP":8.608731,"MRU":42.35229,"MUR":49.524975,"MVR":16.503093,"MWK":1858.572504,"MXN":18.242618,"MYR":5.092381,"MZN":67.832128,"NAD":19.972981,"NGN":1471.371945,"NIO":39.253936,"NOK":11.842986,"NPR":142.693282,"NZD":1.807457,"OMR":0.411192,"PAB":1.068564,"PEN":4.010901,"PGK":4.06723,"PHP":61.653876,"PKR":297.477129,"PLN":4.326049,"PYG":7985.490436,"QAR":3.88913,"RON":4.97544,"RSD":117.095413,"RUB":99.796798,"RWF":1383.248108,"SAR":4.006101,"SBD":9.056777,"SCR":14.541681,"SDG":625.932474,"SEK":11.740609,"SGD":1.456891,"SHP":1.349548,"SLE":24.404238,"SLL":22398.475878,"SOS":610.447836,"SRD":36.117725,"STD":22108.45024,"SVC":9.349931,"SYP":2683.747397,"SZL":19.910451,"THB":39.699219,"TJS":11.669255,"TMT":3.74919,"TND":3.358228,"TOP":2.543414,"TRY":34.602181,"TTD":7.253379,"TWD":34.818653,"TZS":2761.155822,"UAH":42.220215,"UGX":4073.848916,"USD":1.068145,"UYU":40.94766,"UZS":13496.015475,"VEF":3869412.307361,"VES":38.860882,"VND":27072.141535,"VUV":126.812381,"WST":2.994661,"XAF":653.366678,"XAG":0.04045,"XAU":0.000465,"XCD":2.886716,"XDR":0.810786,"XOF":652.099875,"XPF":119.331742,"YER":267.436868,"ZAR":20.090477,"ZMK":9614.586505,"ZMW":28.612101,"ZWL":343.942337}}')
            );

        $result = $this->object->run('');

        $this->assertEquals([
            '1.00',
            '0.46',
            '1.18',
        ], $result);

    }
}
