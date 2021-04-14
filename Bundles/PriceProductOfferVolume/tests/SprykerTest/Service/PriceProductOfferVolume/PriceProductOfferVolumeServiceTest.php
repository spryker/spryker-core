<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductOfferVolume;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProductOfferVolume
 * @group PriceProductOfferVolumeServiceTest
 * Add your own group annotations below this line
 */
class PriceProductOfferVolumeServiceTest extends Unit
{
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';
    protected const MONEY_VALUE = 10000;
    protected const PRICE_DIMENSION_TYPE = 'PRODUCT_OFFER';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var \SprykerTest\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetMinPriceProductsSuccessful(): void
    {
        // Arrange
        $priceProductTransfer1 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_1',
            PriceProductTransfer::VOLUME_QUANTITY => 5,
        ]);
        $priceProductTransfer2 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_2',
            PriceProductTransfer::VOLUME_QUANTITY => 4,
        ]);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setQuantity(10);

        $priceProductTransfers = [
            $priceProductTransfer1,
            $priceProductTransfer2,
        ];

        // Act
        $minPriceProductTransfers = $this->tester
            ->getPriceProductOfferVolumeService()
            ->getMinPriceProducts($priceProductTransfers, $priceProductFilterTransfer);

        // Assert
        $this->assertCount(1, $minPriceProductTransfers);
        $this->assertSame($priceProductTransfer1, $minPriceProductTransfers[0]);
    }

    /**
     * @return void
     */
    public function testGetMinPriceProductsWithPriceProductFilterQuantityEqualOne(): void
    {
        // Arrange
        $priceProductTransfer1 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_1',
            PriceProductTransfer::VOLUME_QUANTITY => 5,
        ]);
        $priceProductTransfer2 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_2',
            PriceProductTransfer::VOLUME_QUANTITY => 4,
        ]);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setQuantity(1);

        $priceProductTransfers = [
            $priceProductTransfer1,
            $priceProductTransfer2,
        ];

        // Act
        $minPriceProductTransfers = $this->tester
            ->getPriceProductOfferVolumeService()
            ->getMinPriceProducts($priceProductTransfers, $priceProductFilterTransfer);

        // Assert
        $this->assertCount(0, $minPriceProductTransfers);
    }

    /**
     * @return void
     */
    public function testGetMinPriceProductsIsSingleItemPrice(): void
    {
        // Arrange
        $priceProductTransfer1 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_1',
        ]);
        $priceProductTransfer2 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_2',
            PriceProductTransfer::VOLUME_QUANTITY => 4,
        ]);

        $priceProductTransfers = [
            $priceProductTransfer1,
            $priceProductTransfer2,
        ];

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setQuantity(1);

        // Act
        $minPriceProductTransfers = $this->tester
            ->getPriceProductOfferVolumeService()
            ->getMinPriceProducts($priceProductTransfers, $priceProductFilterTransfer);

        // Assert
        $this->assertCount(1, $minPriceProductTransfers);
        $this->assertSame($priceProductTransfer1, $minPriceProductTransfers[0]);
    }

    /**
     * @return void
     */
    public function testExtractVolumePrices(): void
    {
        // Arrange
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_TYPE);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer);

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount(static::MONEY_VALUE)
            ->setGrossAmount(static::MONEY_VALUE)
            ->setPriceData(static::PRICE_DATA_VOLUME);

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        // Act
        $volumePrices = $this->tester
            ->getPriceProductOfferVolumeService()
            ->extractVolumePrices([$priceProductTransfer]);

        // Assert
        $this->assertCount(2, $volumePrices);
    }
}
