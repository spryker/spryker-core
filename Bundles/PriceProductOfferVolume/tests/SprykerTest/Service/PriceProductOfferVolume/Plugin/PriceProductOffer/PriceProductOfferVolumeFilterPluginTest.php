<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductOfferVolume\Plugin\PriceProductOffer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductOfferVolume\Plugin\PriceProductOffer\PriceProductOfferVolumeFilterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProductOfferVolume
 * @group Plugin
 * @group PriceProductOffer
 * @group PriceProductOfferVolumeFilterPluginTest
 * Add your own group annotations below this line
 */
class PriceProductOfferVolumeFilterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransfer1;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransfer2;

    /**
     * @var \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected $priceProductFilterTransfer;

    /**
     * @return void
     */
    public function _before(): void
    {
        $this->priceProductTransfer1 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_1',
            PriceProductTransfer::VOLUME_QUANTITY => 5,
        ]);
        $this->priceProductTransfer2 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test_2',
            PriceProductTransfer::VOLUME_QUANTITY => 4,
        ]);

        $this->priceProductFilterTransfer = (new PriceProductFilterTransfer())->setQuantity(10);
    }

    /**
     * @return void
     */
    public function testFilterSuccessful(): void
    {
        // Act
        $minPriceProductTransfers = $this->runPluginFilter();

        // Assert
        $this->assertCount(1, $minPriceProductTransfers);
        $this->assertSame($this->priceProductTransfer1, $minPriceProductTransfers[0]);
    }

    /**
     * @return void
     */
    public function testFilterWithPriceProductFilterQuantityEqualOne(): void
    {
        // Arrange
        $this->priceProductFilterTransfer->setQuantity(1);

        // Act
        $minPriceProductTransfers = $this->runPluginFilter();

        // Assert
        $this->assertCount(0, $minPriceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFilterIsSingleItemPrice(): void
    {
        // Arrange
        $this->priceProductTransfer1->setVolumeQuantity(null);
        $this->priceProductFilterTransfer->setQuantity(1);

        // Act
        $minPriceProductTransfers = $this->runPluginFilter();

        // Assert
        $this->assertCount(1, $minPriceProductTransfers);
        $this->assertSame($this->priceProductTransfer1, $minPriceProductTransfers[0]);
    }

    /**
     * @return array
     */
    protected function runPluginFilter(): array
    {
        // Arrange
        $priceProductTransfers = [
            $this->priceProductTransfer1,
            $this->priceProductTransfer2,
        ];

        $priceProductOfferVolumeFilterPlugin = new PriceProductOfferVolumeFilterPlugin();

        // Act
        return $priceProductOfferVolumeFilterPlugin->filter($priceProductTransfers, $this->priceProductFilterTransfer);
    }
}
