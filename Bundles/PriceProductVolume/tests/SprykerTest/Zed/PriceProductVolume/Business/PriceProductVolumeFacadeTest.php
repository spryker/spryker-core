<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductVolume\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductVolume
 * @group Business
 * @group Facade
 * @group PriceProductVolumeFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductVolumeFacadeTest extends Unit
{
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';
    protected const MONEY_VALUE = 10000;
    protected const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractPriceProductVolumesForProductAbstractReturnsNotEmptyArray()
    {
        $priceProductVolumeFacade = $this->getPriceProductVolumeFacade();
        $priceProducts = $this->preparePriceProductsWithVolumePrices();

        $volumePrices = $priceProductVolumeFacade->extractPriceProductVolumesForProductAbstract($priceProducts);

        $this->assertGreaterThan(1, count($volumePrices));
    }

    /**
     * @return void
     */
    public function testExtractPriceProductVolumesForProductConcreteReturnsEmptyArrayWithoutPriceData()
    {
        $priceProductVolumeFacade = $this->getPriceProductVolumeFacade();
        $priceProducts = $this->preparePriceProductsWithoutVolumePrices();

        $volumePrices = $priceProductVolumeFacade->extractPriceProductVolumesForProductConcrete($priceProducts);

        $this->assertCount(1, $volumePrices);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacade
     */
    protected function getPriceProductVolumeFacade()
    {
        return new PriceProductVolumeFacade();
    }

    /**
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        $netPrice,
        $grossPrice
    ) {
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_DEFAULT);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer);

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        $grossAmount,
        $netAmount
    ) {
        return (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function preparePriceProductsWithoutVolumePrices(): array
    {
        $priceProductTransfer = $this->createPriceProductTransfer(static::MONEY_VALUE, static::MONEY_VALUE);

        return [$priceProductTransfer];
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function preparePriceProductsWithVolumePrices(): array
    {
        $priceProductTransfer = $this->createPriceProductTransfer(static::MONEY_VALUE, static::MONEY_VALUE);
        $priceProductTransfer->getMoneyValue()->setPriceData(static::PRICE_DATA_VOLUME);

        return [$priceProductTransfer];
    }
}
