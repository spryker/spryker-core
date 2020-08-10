<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductOfferVolume;

use Codeception\Test\Unit;
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
}
