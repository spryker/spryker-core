<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderThreshold
 * @group Persistence
 * @group SalesOrderThresholdRepositoryTest
 * Add your own group annotations below this line
 */
class SalesOrderThresholdRepositoryTest extends Unit
{
    protected const COUNTRY_ISO2_CODE = 'DE';

    protected const TAX_SET_NAME_1 = 'TAX_SET_NAME_1';
    protected const TAX_RATE_1 = 1;

    protected const TAX_SET_NAME_2 = 'TAX_SET_NAME_2';
    protected const TAX_RATE_2 = 2;

    /**
     * @var \SprykerTest\Zed\SalesOrderThreshold\SalesOrderThresholdPersistenceTester
     */
    protected $tester;

    /**
     * @dataProvider getFindMaxTaxRateByCountryIso2CodeData
     *
     * @param array $taxSetData
     * @param float $expectedRate
     *
     * @return void
     */
    public function testFindMaxTaxRateByCountryIso2CodeShouldReturnMaxTaxRate(array $taxSetData, float $expectedRate): void
    {
        // Arrange
        $country = $this->tester->haveCountry([
            CountryTransfer::ISO2_CODE => static::COUNTRY_ISO2_CODE,
        ]);

        foreach ($taxSetData as $taxSetName => $taxRate) {
            $taxSetTransfer = $this->tester->haveTaxSetWithTaxRates([TaxSetTransfer::NAME => $taxSetName], [
                [
                    TaxRateTransfer::FK_COUNTRY => $country->getIdCountry(),
                    TaxRateTransfer::RATE => $taxRate,
                    TaxRateTransfer::NAME => SalesOrderThresholdConfig::TAX_EXEMPT_PLACEHOLDER,
                ],
            ]);

            $this->tester->createSalesOrderThresholdTaxSetEntity($taxSetTransfer->getIdTaxSet());
        }

        $salesOrderThresholdRepository = new SalesOrderThresholdRepository();

        // Act
        $maxRate = $salesOrderThresholdRepository->findMaxTaxRateByCountryIso2Code(static::COUNTRY_ISO2_CODE);

        // Assert
        $this->assertSame($expectedRate, $maxRate);
    }

    /**
     * @return array
     */
    public function getFindMaxTaxRateByCountryIso2CodeData(): array
    {
        return [
            [
                  [
                      static::TAX_SET_NAME_1 => static::TAX_RATE_1,
                      static::TAX_SET_NAME_1 => static::TAX_RATE_2,
                      static::TAX_SET_NAME_2 => static::TAX_RATE_2,
                  ],
                  static::TAX_RATE_2,
                ],
            [
                [
                    static::TAX_SET_NAME_2 => static::TAX_RATE_1,
                    static::TAX_SET_NAME_2 => static::TAX_RATE_2,
                ],
                static::TAX_RATE_2,
            ],
        ];
    }
}
