<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOfferGui\Communication\Plugin\ProductMerchantPortalGui;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductOfferGui\Communication\Plugin\ProductMerchantPortalGui\PriceProductOfferPriceProductTableFilterPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductTableFilterPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOfferGui
 * @group Communication
 * @group Plugin
 * @group ProductMerchantPortalGui
 * @group PriceProductOfferPriceProductTableFilterPluginTest
 * Add your own group annotations below this line
 */
class PriceProductOfferPriceProductTableFilterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU = 'sku';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU_2 = 'sku-2';

    /**
     * @var \SprykerTest\Zed\PriceProductOfferGui\PriceProductOfferGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterShouldNotFilterOutProductPricesWithoutPriceDimension(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => static::TEST_PRODUCT_SKU,
        ]);
        $priceProductTransfer->setPriceDimension();

        // Act
        $filteredPriceProductTransfers = $this->createPriceProductOfferPriceProductTableFilterPlugin()
            ->filter([$priceProductTransfer], new PriceProductTableCriteriaTransfer());

        // Assert
        $this->assertCount(1, $filteredPriceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFilterShouldFilterOutProductOfferPrices(): void
    {
        // Arrange
        $priceProductOfferTransfer = $this->tester->createPriceProductWithOffer([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => static::TEST_PRODUCT_SKU,
        ]);

        $priceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => static::TEST_PRODUCT_SKU_2,
        ]);

        // Act
        $filteredPriceProductTransfers = $this->createPriceProductOfferPriceProductTableFilterPlugin()
            ->filter([$priceProductOfferTransfer, $priceProductTransfer], new PriceProductTableCriteriaTransfer());

        // Assert
        $this->assertCount(1, $filteredPriceProductTransfers);
        $this->assertSame($priceProductTransfer->getIdPriceProduct(), $filteredPriceProductTransfers[0]->getIdPriceProduct());
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferGui\Communication\Plugin\ProductMerchantPortalGui\PriceProductOfferPriceProductTableFilterPlugin
     */
    protected function createPriceProductOfferPriceProductTableFilterPlugin(): PriceProductTableFilterPluginInterface
    {
        return new PriceProductOfferPriceProductTableFilterPlugin();
    }
}
