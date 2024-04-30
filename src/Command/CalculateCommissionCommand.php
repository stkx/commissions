<?php

namespace App\Command;

use App\Domain\Commission\CommissionCalculator;
use App\External\BinProvider\BinListProviderInterface;
use App\External\Rates\RateListProviderInterface;
use App\File\InputDataProvider;

class CalculateCommissionCommand
{
    public function __construct(
        private readonly InputDataProvider         $fileReader,
        private readonly BinListProviderInterface  $binListProvider,
        private readonly RateListProviderInterface $rateListProvider,
        private readonly CommissionCalculator      $commissionCalculator,
    )
    {
    }

    public function run(string $fileName): array
    {
        $results = [];

        $rows = $this->fileReader->readFile($fileName);
        foreach ($rows as $rowValue) {
            try {

                $binData = $this->binListProvider->getByBinValue($rowValue['bin']);

                $rateValue = $this->rateListProvider->getLatestRateByCurrency($rowValue['currency']);

                $results[] = $this->commissionCalculator->calculate(
                    $rowValue['amount'],
                    $binData->countryAlpha,
                    $rowValue['currency'],
                    $rateValue
                );
            } catch (\Exception $exception) {
                $results[] = sprintf('Failure for `%s`: %s', $rowValue['bin'], $exception->getMessage());
                continue;
            }
        }

        return $results;
    }
}