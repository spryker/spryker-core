<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointAvailability\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeBridge;
use Spryker\Zed\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityDependencyProvider;
use SprykerTest\Zed\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointAvailability
 * @group Business
 * @group Facade
 * @group GetItemsAvailabilityForStoreTest
 * Add your own group annotations below this line
 */
class GetItemsAvailabilityForStoreTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_1 = 'PRODUCT_OFFER_REFERENCE_1';

    /**
     * @var int
     */
    protected const PRODUCT_OFFER_ID_1 = 1000;

    /**
     * @var int
     */
    protected const SERVICE_POINT_ID_1 = 1000;

    /**
     * @var int
     */
    protected const SERVICE_POINT_ID_2 = 2000;

    /**
     * @var string
     */
    protected const PRODUCT_SKU = 'PRODUCT_SKU';

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityBusinessTester
     */
    protected ProductOfferServicePointAvailabilityBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidateServicePointWithValidRequest(): void
    {
        // Arrange
        $productOfferTransfer = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);
        $productOfferTransferWithoutProductOfferReference = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1);
        $servicePointTransfer = (new ServicePointTransfer())
            ->setIdServicePoint(static::SERVICE_POINT_ID_1);
        $serviceTransfer = (new ServiceTransfer())
            ->setServicePoint($servicePointTransfer);
        $productOfferServicesTransfer = (new ProductOfferServicesTransfer())
            ->setProductOffer($productOfferTransferWithoutProductOfferReference)
            ->addService($serviceTransfer);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->setServicePoint($servicePointTransfer);
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);
        $productOfferServiceCollectionTransfer = (new ProductOfferServiceCollectionTransfer())
            ->addProductOfferServices($productOfferServicesTransfer);

        $this->mockProductOfferFacade(1, $productOfferCollectionTransfer);
        $this->mockProductOfferServicePointFacade(1, $productOfferServiceCollectionTransfer);

        $sellableItemRequestTransfer = (new SellableItemRequestTransfer())
            ->setProductAvailabilityCriteria($productAvailabilityCriteriaTransfer);
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())
            ->addSellableItemRequest($sellableItemRequestTransfer);
        $sellableItemsResponseTransfer = new SellableItemsResponseTransfer();

        // Act
        $sellableItemsResponseTransfer = $this->tester->getFacade()->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $this->assertCount(0, $sellableItemsResponseTransfer->getSellableItemResponses());
    }

    /**
     * @return void
     */
    public function testValidateServicePointWithInvalidRequest(): void
    {
        // Arrange
        $productAvailabilityCriteriaTransfer = $this->getInvalidProductAvailabilityCriteriaTransfer();

        $sellableItemRequestTransfer = (new SellableItemRequestTransfer())
            ->setSku(static::PRODUCT_SKU)
            ->setProductAvailabilityCriteria($productAvailabilityCriteriaTransfer);
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())
            ->addSellableItemRequest($sellableItemRequestTransfer);
        $sellableItemsResponseTransfer = new SellableItemsResponseTransfer();

        // Act
        $sellableItemsResponseTransfer = $this->tester->getFacade()->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $this->assertCount(1, $sellableItemsResponseTransfer->getSellableItemResponses());
        /** @var \Generated\Shared\Transfer\SellableItemResponseTransfer $sellableItemResponseTransfer */
        $sellableItemResponseTransfer = $sellableItemsResponseTransfer->getSellableItemResponses()[0];
        $this->assertSame(static::PRODUCT_SKU, $sellableItemResponseTransfer->getSkuOrFail());
        $this->assertFalse($sellableItemResponseTransfer->getIsSellableOrFail());
        $this->assertSame(0, $sellableItemResponseTransfer->getAvailableQuantityOrFail()->toInt());
    }

    /**
     * @return void
     */
    public function testValidateServicePointWithInvalidRequestWhenResponseAlreadyHasNotSellableItem(): void
    {
        // Arrange
        $productAvailabilityCriteriaTransfer = $this->getInvalidProductAvailabilityCriteriaTransfer();

        $sellableItemResponseTransfer = (new SellableItemResponseTransfer())
            ->setSku(static::PRODUCT_SKU)
            ->setIsSellable(false);
        $sellableItemRequestTransfer = (new SellableItemRequestTransfer())
            ->setSku(static::PRODUCT_SKU)
            ->setProductAvailabilityCriteria($productAvailabilityCriteriaTransfer);
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())
            ->addSellableItemRequest($sellableItemRequestTransfer);
        $sellableItemsResponseTransfer = (new SellableItemsResponseTransfer())
            ->addSellableItemResponse($sellableItemResponseTransfer);

        // Act
        $sellableItemsResponseTransfer = $this->tester->getFacade()->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $this->assertCount(1, $sellableItemsResponseTransfer->getSellableItemResponses());
    }

    /**
     * @return void
     */
    public function testSkipValidationForRequestWithoutProductOfferReference(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointTransfer())
            ->setIdServicePoint(static::SERVICE_POINT_ID_1);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setServicePoint($servicePointTransfer);

        $this->mockProductOfferFacade(0, new ProductOfferCollectionTransfer());
        $this->mockProductOfferServicePointFacade(0, new ProductOfferServiceCollectionTransfer());

        $sellableItemRequestTransfer = (new SellableItemRequestTransfer())
            ->setSku(static::PRODUCT_SKU)
            ->setProductAvailabilityCriteria($productAvailabilityCriteriaTransfer);
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())
            ->addSellableItemRequest($sellableItemRequestTransfer);
        $sellableItemsResponseTransfer = new SellableItemsResponseTransfer();

        // Act
        $sellableItemsResponseTransfer = $this->tester->getFacade()->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $this->assertCount(0, $sellableItemsResponseTransfer->getSellableItemResponses());
    }

    /**
     * @return void
     */
    public function testSkipValidationForRequestWithoutServicePoint(): void
    {
        // Arrange
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);

        $this->mockProductOfferFacade(0, new ProductOfferCollectionTransfer());
        $this->mockProductOfferServicePointFacade(0, new ProductOfferServiceCollectionTransfer());

        $sellableItemRequestTransfer = (new SellableItemRequestTransfer())
            ->setSku(static::PRODUCT_SKU)
            ->setProductAvailabilityCriteria($productAvailabilityCriteriaTransfer);
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())
            ->addSellableItemRequest($sellableItemRequestTransfer);
        $sellableItemsResponseTransfer = new SellableItemsResponseTransfer();

        // Act
        $sellableItemsResponseTransfer = $this->tester->getFacade()->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $this->assertCount(0, $sellableItemsResponseTransfer->getSellableItemResponses());
    }

    /**
     * @param int $callCount
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return void
     */
    protected function mockProductOfferFacade(int $callCount, ProductOfferCollectionTransfer $productOfferCollectionTransfer): void
    {
        $productOfferFacadeMock = $this->createMock(ProductOfferServicePointAvailabilityToProductOfferFacadeBridge::class);
        $productOfferFacadeMock
            ->expects($this->exactly($callCount))
            ->method('getProductOfferCollection')
            ->willReturn($productOfferCollectionTransfer);

        $this->tester->setDependency(ProductOfferServicePointAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER, $productOfferFacadeMock);
    }

    /**
     * @param int $callCount
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return void
     */
    protected function mockProductOfferServicePointFacade(int $callCount, ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer): void
    {
        $productOfferServicePointFacadeMock = $this->createMock(ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeBridge::class);
        $productOfferServicePointFacadeMock
            ->expects($this->exactly($callCount))
            ->method('getProductOfferServiceCollection')
            ->willReturn($productOfferServiceCollectionTransfer);

        $this->tester->setDependency(ProductOfferServicePointAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER_SERVICE_POINT, $productOfferServicePointFacadeMock);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer
     */
    protected function getInvalidProductAvailabilityCriteriaTransfer(): ProductAvailabilityCriteriaTransfer
    {
        $productOfferTransfer = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);
        $productOfferTransferWithoutProductOfferReference = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1);
        $servicePointTransfer = (new ServicePointTransfer())
            ->setIdServicePoint(static::SERVICE_POINT_ID_1);
        $servicePointTransferSecond = (new ServicePointTransfer())
            ->setIdServicePoint(static::SERVICE_POINT_ID_2);
        $serviceTransfer = (new ServiceTransfer())
            ->setServicePoint($servicePointTransferSecond);
        $productOfferServicesTransfer = (new ProductOfferServicesTransfer())
            ->setProductOffer($productOfferTransferWithoutProductOfferReference)
            ->addService($serviceTransfer);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->setServicePoint($servicePointTransfer);
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);
        $productOfferServiceCollectionTransfer = (new ProductOfferServiceCollectionTransfer())
            ->addProductOfferServices($productOfferServicesTransfer);

        $this->mockProductOfferFacade(1, $productOfferCollectionTransfer);
        $this->mockProductOfferServicePointFacade(1, $productOfferServiceCollectionTransfer);

        return $productAvailabilityCriteriaTransfer;
    }
}
