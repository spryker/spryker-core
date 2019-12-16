<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

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
        $stockQuantity = 5;
        $expectedResult = $stockQuantity;

        $storeTransfer = $this->tester->haveStore();
        $productOfferStockTransfer = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::QUANTITY => $stockQuantity,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferStockTransfer->getProductOffer()->getProductOfferReference())
            ->setStore($storeTransfer);

        // Act
        $productOfferStock = $this->tester->getFacade()
            ->calculateProductOfferStockForRequest($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame($expectedResult, $productOfferStock->toInt());
    }

    /**
     * @return void
     */
    public function testCalculateProductOfferStockForRequestReturnsNothingIfProductOfferNotExists(): void
    {
        // Arrange
        $notExistingProductOfferReference = 'not-existing-product-offer-reference';

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
