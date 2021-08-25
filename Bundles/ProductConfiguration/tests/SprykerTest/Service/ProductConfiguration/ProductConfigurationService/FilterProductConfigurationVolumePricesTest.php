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
 * @group FilterProductConfigurationVolumePricesTest
 * Add your own group annotations below this line
 */
class FilterProductConfigurationVolumePricesTest extends Unit
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @uses \Spryker\Service\ProductConfiguration\Filter\PriceProductConfigurationVolumeFilter::SINGLE_ITEM_QUANTITY
     */
    protected const SINGLE_ITEM_QUANTITY = 1;

    protected const PRICE_DIMENSION_TYPE_TEST = 'PRICE_DIMENSION_TYPE_TEST';

    /**
     * @var \SprykerTest\Service\ProductConfiguration\ProductConfigurationServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFiltersOutNonSingleItemPrices(): void
    {
        // Arrange
        $productConfigurationPriceProductTransfer = $this->createPriceProductTransfer(true);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity(static::SINGLE_ITEM_QUANTITY)
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())->addPrice($productConfigurationPriceProductTransfer)
            );

        $priceProductTransfers = [
            $productConfigurationPriceProductTransfer,
            $this->createPriceProductTransfer(),
            (clone $productConfigurationPriceProductTransfer)->setVolumeQuantity(777),
        ];

        // Act
        $priceProductTransfers = $this->tester->getLocator()->productConfiguration()->service()->filterProductConfigurationVolumePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers, 'Expects that only one PriceProductTransfer left.');
        $this->assertNull($priceProductTransfers[0]->getVolumeQuantity(), 'Expects that volume quantity is not set.');
        $this->assertSame(
            $productConfigurationPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash(),
            $priceProductTransfers[0]->getPriceDimension()->getProductConfigurationInstanceHash(),
            'Expects product configuration instance hash to be the same as for product configuration price.'
        );
    }

    /**
     * @return void
     */
    public function testResolvesLowestVolumeQuantityPrice(): void
    {
        // Arrange
        $productConfigurationPriceProductTransfer = $this->createPriceProductTransfer(true);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity(7)
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())->addPrice($productConfigurationPriceProductTransfer)
            );

        $priceProductTransfers = [
            $this->createPriceProductTransfer(),
            (clone $productConfigurationPriceProductTransfer)->setVolumeQuantity(7),
            (clone $productConfigurationPriceProductTransfer)->setVolumeQuantity(77),
        ];

        // Act
        $priceProductTransfers = $this->tester->getLocator()->productConfiguration()->service()->filterProductConfigurationVolumePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers, 'Expects that only one PriceProductTransfer left.');
        $this->assertSame(7, $priceProductTransfers[0]->getVolumeQuantity(), 'Expects volume quantity to be lowest from available prices.');
        $this->assertSame(
            $productConfigurationPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash(),
            $priceProductTransfers[0]->getPriceDimension()->getProductConfigurationInstanceHash(),
            'Expects product configuration instance hash to be the same as for product configuration price.'
        );
    }

    /**
     * @return void
     */
    public function testReturnsSingleItemPriceWhenVolumeQuantityPriceWasNotResolved(): void
    {
        // Arrange
        $productConfigurationPriceProductTransfer = $this->createPriceProductTransfer(true);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity(10)
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())->addPrice($productConfigurationPriceProductTransfer)
            );

        $priceProductTransfers = [
            clone $productConfigurationPriceProductTransfer,
            (clone $productConfigurationPriceProductTransfer)->setVolumeQuantity(111),
            (clone $productConfigurationPriceProductTransfer)->setVolumeQuantity(333),
            $this->createPriceProductTransfer(),
        ];

        // Act
        $priceProductTransfers = $this->tester->getLocator()->productConfiguration()->service()->filterProductConfigurationVolumePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers, 'Expects that only one PriceProductTransfer left.');
        $this->assertNull($priceProductTransfers[0]->getVolumeQuantity(), 'Expects volume quantity to be empty.');
        $this->assertSame(
            $productConfigurationPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash(),
            $priceProductTransfers[0]->getPriceDimension()->getProductConfigurationInstanceHash(),
            'Expects product configuration instance hash to be the same as for product configuration price.'
        );
    }

    /**
     * @param bool $hasProductConfiguration
     * @param int|null $volumeQuantity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(bool $hasProductConfiguration = false, ?int $volumeQuantity = null): PriceProductTransfer
    {
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_TYPE_TEST)
            ->setProductConfigurationInstanceHash($hasProductConfiguration ? md5(microtime()) : null);

        return (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setVolumeQuantity($volumeQuantity)
            ->setPriceDimension($priceDimensionTransfer);
    }
}
