<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailability\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferAvailability
 * @group Business
 * @group Facade
 * @group ProductOfferAvailabilityFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferAvailabilityFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferAvailability\ProductOfferAvailabilityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsProductSellableForRequestReturnsTrueIfProductOfferAvailableInRequestedQuantity(): void
    {
        // Arrange
        $stockQuantity = 5;
        $requestedQuantity = 1;

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

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferStockTransfer->getProductOffer()->getProductOfferReference())
            ->setSku($productOfferStockTransfer->getProductOffer()->getConcreteSku())
            ->setQuantity($requestedQuantity);

        // Act
        $isProductOfferSellable = $this->tester->getFacade()
            ->isProductSellableForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertTrue($isProductOfferSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableForRequestReturnsFalseForNotAvailableForStoreProductOffer(): void
    {
        // Arrange
        $stockQuantity = 0;
        $requestedQuantity = 1;

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

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferStockTransfer->getProductOffer()->getProductOfferReference())
            ->setSku($productOfferStockTransfer->getProductOffer()->getConcreteSku())
            ->setQuantity($requestedQuantity);

        // Act
        $isProductOfferSellable = $this->tester->getFacade()
            ->isProductSellableForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertFalse($isProductOfferSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableForRequestReturnsFalseForReservedProductOffers(): void
    {
        // Arrange
        $stockQuantity = 5;
        $reservedQuantity = 3;
        $requestedQuantity = 3;

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

        $productOfferTransfer = $productOfferStockTransfer->getProductOffer();
        $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::SKU => $productOfferTransfer->getConcreteSku(),
            OmsProductReservationTransfer::RESERVATION_QUANTITY => $reservedQuantity,
            OmsProductReservationTransfer::FK_STORE => $storeTransfer->getIdStore(),
        ]);

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setQuantity($requestedQuantity);

        // Act
        $isProductOfferSellable = $this->tester->getFacade()
            ->isProductSellableForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertFalse($isProductOfferSellable);
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityForRequestReturnsProductOfferAvailabilityAssumingOmsProductReservations(): void
    {
        // Arrange=
        $stockQuantity = 5;
        $reservedQuantity = 3;
        $expectedAvailability = $stockQuantity - $reservedQuantity;

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

        $productOfferTransfer = $productOfferStockTransfer->getProductOffer();
        $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::SKU => $productOfferTransfer->getConcreteSku(),
            OmsProductReservationTransfer::RESERVATION_QUANTITY => $reservedQuantity,
            OmsProductReservationTransfer::FK_STORE => $storeTransfer->getIdStore(),
        ]);

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku($productOfferTransfer->getConcreteSku());

        // Act
        $productConcreteAvailabilityTransfer = $this->tester->getFacade()
            ->findProductConcreteAvailabilityForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertNotNull($productConcreteAvailabilityTransfer);
        $this->assertSame($expectedAvailability, $productConcreteAvailabilityTransfer->getAvailability()->toInt());
    }
}
