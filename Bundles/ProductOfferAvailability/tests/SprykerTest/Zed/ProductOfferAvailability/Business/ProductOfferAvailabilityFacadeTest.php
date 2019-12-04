<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailability\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

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
    protected const TEST_SKU = 'sku-1';

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
        $storeTransfer = $this->tester->createStore();
        $this->tester->createStockForStore($storeTransfer);
        $productOfferTransfer = $this->tester->createProductOfferForSku(static::TEST_SKU);
        $this->tester->createProductOfferStock(5, $storeTransfer, $productOfferTransfer);

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku(static::TEST_SKU)
            ->setQuantity(1);

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
        $storeTransfer = $this->tester->createStore();
        $this->tester->createStockForStore($storeTransfer);
        $productOfferTransfer = $this->tester->createProductOfferForSku(static::TEST_SKU);
        $this->tester->createProductOfferStock(5, $storeTransfer, $productOfferTransfer);

        $storeWithoutAvailability = $this->tester->createStore();
        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeWithoutAvailability)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku(static::TEST_SKU)
            ->setQuantity(1);

        // Act
        $isProductOfferSellable = $this->tester->getFacade()
            ->isProductSellableForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertFalse($isProductOfferSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableForRequestConsidersOmsProductReservations(): void
    {
        // Arrange
        $storeTransfer = $this->tester->createStore();
        $this->tester->createStockForStore($storeTransfer);
        $productOfferTransfer = $this->tester->createProductOfferForSku(static::TEST_SKU);
        $this->tester->createProductOfferStock(5, $storeTransfer, $productOfferTransfer);
        $this->tester->createOmsProductReservation(3, $storeTransfer->getName(), static::TEST_SKU);

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku(static::TEST_SKU)
            ->setQuantity(3);

        // Act
        $isProductOfferSellable = $this->tester->getFacade()
            ->isProductSellableForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertFalse($isProductOfferSellable);
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityForRequestReturnsProductOfferAvailabilityAssumingOmsProductReservations()
    {
        // Arrange
        $storeTransfer = $this->tester->createStore();
        $this->tester->createStockForStore($storeTransfer);
        $productOfferTransfer = $this->tester->createProductOfferForSku(static::TEST_SKU);
        $this->tester->createProductOfferStock(5, $storeTransfer, $productOfferTransfer);
        $this->tester->createOmsProductReservation(3, $storeTransfer->getName(), static::TEST_SKU);

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setStore($storeTransfer)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku(static::TEST_SKU)
            ->setQuantity(3);

        // Act
        $productConcreteAvailabilityTransfer = $this->tester->getFacade()
            ->findProductConcreteAvailabilityForRequest($productOfferAvailabilityRequestTransfer);

        // Assert
        $this->assertNotNull($productConcreteAvailabilityTransfer);
        $this->assertSame(2, $productConcreteAvailabilityTransfer->getAvailability()->toInt());
    }
}
