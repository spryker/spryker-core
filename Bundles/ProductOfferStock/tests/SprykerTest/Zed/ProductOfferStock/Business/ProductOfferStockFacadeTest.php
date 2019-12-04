<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStock
 * @group Business
 * @group Facade
 * @group ProductOfferStockFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferStockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferStock\ProductOfferStockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCalculateProductOfferStockForRequestReturnsAvailableStockAmount(): void
    {
        // Arrange
        $expectedStock = 5;
        $productOfferReference = 'product-offer1';

        $this->tester->truncateProductOffers();
        $storeTransfer = $this->tester->haveStore();
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferReference)
            ->setStore($storeTransfer);

        $this->tester->createProductOfferStock($expectedStock, $storeTransfer->getName(), $productOfferReference);

        // Act

        $productOfferStock = $this->tester->getFacade()
            ->calculateProductOfferStockForRequest($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame($expectedStock, $productOfferStock->toInt());
    }

    /**
     * @return void
     */
    public function testCalculateProductOfferStockForRequestReturnsNothingIfProductOfferNotExists(): void
    {
        // Arrange
        $notExistingProductOfferReference = 'product-offer1';

        $this->tester->truncateProductOffers();
        $storeTransfer = $this->tester->haveStore();
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($notExistingProductOfferReference)
            ->setStore($storeTransfer);

        // Act
        $productOfferStock = $this->tester->getFacade()
            ->calculateProductOfferStockForRequest($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame(0, $productOfferStock->toInt());
    }
}
