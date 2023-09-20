<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group GroupPriceProductCollectionTest
 * Add your own group annotations below this line
 */
class GroupPriceProductCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_CURRENCY = 'FAKE_CURRENCY';

    /**
     * @var string
     */
    protected const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA
     *
     * @var string
     */
    protected const PRICE_DATA = 'priceData';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA_BY_PRICE_TYPE
     *
     * @var string
     */
    protected const PRICE_DATA_BY_PRICE_TYPE = 'priceDataByPriceType';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testGroupPriceProductCollectionGroupsProvidedCollection(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();
        $expectedResult = [
            'dummy currency 1' => [
                'GROSS_MODE' => [
                    $defaultPriceTypeName => 100,
                    static::PRICE_TYPE_ORIGINAL => 1100,
                ],
                'NET_MODE' => [
                    $defaultPriceTypeName => 300,
                    static::PRICE_TYPE_ORIGINAL => 1300,
                ],
                'priceData' => null,
                'priceDataByPriceType' => [
                    $defaultPriceTypeName => null,
                    static::PRICE_TYPE_ORIGINAL => null,
                ],
            ],
            'dummy currency 2' => [
                'GROSS_MODE' => [
                    $defaultPriceTypeName => 200,
                    static::PRICE_TYPE_ORIGINAL => 1200,
                ],
                'NET_MODE' => [
                    $defaultPriceTypeName => 400,
                    static::PRICE_TYPE_ORIGINAL => 1400,
                ],
                'priceData' => null,
                'priceDataByPriceType' => [
                    $defaultPriceTypeName => null,
                    static::PRICE_TYPE_ORIGINAL => null,
                ],
            ],
        ];
        $priceProductCollection = [];
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 1', $defaultPriceTypeName, 100, 300);
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 1', static::PRICE_TYPE_ORIGINAL, 1100, 1300);
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 2', $defaultPriceTypeName, 200, 400);
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 2', static::PRICE_TYPE_ORIGINAL, 1200, 1400);

        // Act
        $actualResult = $priceProductFacade->groupPriceProductCollection($priceProductCollection);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testGroupPriceProductCollectionDoesNotOverwritePriceDataByNull(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();

        $expectedPriceData = 'dummy price data';

        $priceProductWithPriceData = $this->createPriceProduct('dummy currency 1', $defaultPriceTypeName, 100, 300);
        $priceProductWithPriceData->getMoneyValue()->setPriceData($expectedPriceData);

        $priceProductCollection = [];
        $priceProductCollection[] = $priceProductWithPriceData;
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 1', static::PRICE_TYPE_ORIGINAL, 1100, 1300);

        // Act
        $actualResult = $priceProductFacade->groupPriceProductCollection($priceProductCollection);

        // Assert
        $this->assertSame($expectedPriceData, $actualResult['dummy currency 1']['priceData']);
    }

    /**
     * @return void
     */
    public function testGroupPriceProductCollectionVolumePriceDataOfDefaultPriceTypeShouldBeSameAsInPriceData(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();

        $expectedPriceData = 'dummy price data';

        $priceProductWithPriceData = $this->createPriceProduct(static::FAKE_CURRENCY, $defaultPriceTypeName, 100, 300);
        $priceProductWithPriceData->getMoneyValue()->setPriceData($expectedPriceData);

        $priceProductCollection = [];
        $priceProductCollection[] = $priceProductWithPriceData;
        $priceProductCollection[] = $this->createPriceProduct(static::FAKE_CURRENCY, static::PRICE_TYPE_ORIGINAL, 1100, 1300);

        // Act
        $actualResult = $priceProductFacade->groupPriceProductCollection($priceProductCollection);

        // Assert
        $this->assertSame($expectedPriceData, $actualResult[static::FAKE_CURRENCY][static::PRICE_DATA_BY_PRICE_TYPE][$defaultPriceTypeName]);
        $this->assertSame($expectedPriceData, $actualResult[static::FAKE_CURRENCY][static::PRICE_DATA]);
    }

    /**
     * @param string $currencyCode
     * @param string $priceTypeName
     * @param int $grossAmount
     * @param int $netAmount
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProduct(string $currencyCode, string $priceTypeName, int $grossAmount, int $netAmount): PriceProductTransfer
    {
        return (new PriceProductTransfer())
            ->setPriceType((new PriceTypeTransfer())->setName($priceTypeName))
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                    ->setGrossAmount($grossAmount)
                    ->setNetAmount($netAmount),
            );
    }
}
