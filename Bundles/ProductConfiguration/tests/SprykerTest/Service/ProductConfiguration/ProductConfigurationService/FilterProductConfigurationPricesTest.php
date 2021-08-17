<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ProductConfiguration\ProductConfigurationService;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ProductConfiguration
 * @group ProductConfigurationService
 * @group FilterProductConfigurationPricesTest
 * Add your own group annotations below this line
 */
class FilterProductConfigurationPricesTest extends Unit
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    protected const PRICE_DIMENSION_TYPE_TEST = 'PRICE_DIMENSION_TYPE_TEST';

    /**
     * @var \SprykerTest\Service\ProductConfiguration\ProductConfigurationServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFiltersOutProductConfigurationPricesWhenProductConfigurationIsMissing(): void
    {
        // Arrange
        $priceProductFilterTransfer = (new PriceProductFilterTransfer());

        $priceProductTransfers = [
            $this->createPriceProductTransfer(),
            $this->createPriceProductTransfer(true),
        ];

        // Act
        $priceProductTransfers = $this->tester->getLocator()->productConfiguration()->service()->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers, 'Expects that only one PriceProductTransfer left.');
        $this->assertNull($priceProductTransfers[0]->getPriceDimension()->getProductConfigurationInstanceHash(), 'Expects product configuration instance hash to be empty.');
    }

    /**
     * @return void
     */
    public function testFiltersOutPricesExceptCurrentProductConfigurationInstancePrices(): void
    {
        // Arrange
        $productConfigurationPriceProductTransfer = $this->createPriceProductTransfer(true);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductConfigurationInstance(
            (new ProductConfigurationInstanceTransfer())->addPrice($productConfigurationPriceProductTransfer)
        );

        $priceProductTransfers = [
            $productConfigurationPriceProductTransfer,
            $this->createPriceProductTransfer(),
            $this->createPriceProductTransfer(true),
        ];

        // Act
        $priceProductTransfers = $this->tester->getLocator()->productConfiguration()->service()->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers, 'Expects that only one PriceProductTransfer left.');
        $this->assertSame(
            $priceProductTransfers[0]->getPriceDimension()->getProductConfigurationInstanceHash(),
            $productConfigurationPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash(),
            'Expects product configuration instance hash to be the same as for price from configuration instance.'
        );
    }

    /**
     * @return void
     */
    public function testFiltersOutProductConfigurationPricesWhenProductConfigurationInstanceHashIsDifferent(): void
    {
        // Arrange
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductConfigurationInstance(
            (new ProductConfigurationInstanceTransfer())->addPrice($this->createPriceProductTransfer(true))
        );

        $priceProductTransfers = [
            $this->createPriceProductTransfer(),
            $this->createPriceProductTransfer(true),
        ];

        // Act
        $priceProductTransfers = $this->tester->getLocator()->productConfiguration()->service()->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers, 'Expects that only one PriceProductTransfer left.');
        $this->assertNull($priceProductTransfers[0]->getPriceDimension()->getProductConfigurationInstanceHash(), 'Expects product configuration instance hash to be empty.');
    }

    /**
     * @param bool $hasProductConfiguration
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(bool $hasProductConfiguration = false): PriceProductTransfer
    {
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_TYPE_TEST)
            ->setProductConfigurationInstanceHash($hasProductConfiguration ? md5(microtime()) : null);

        return (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer);
    }
}
