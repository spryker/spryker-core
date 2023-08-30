<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentTypeAvailability\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeBridge;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\ProductOfferShipmentTypeAvailabilityDependencyProvider;
use SprykerTest\Zed\ProductOfferShipmentTypeAvailability\ProductOfferShipmentTypeAvailabilityBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentTypeAvailability
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
    protected const SHIPMENT_TYPE_ID_1 = 1000;

    /**
     * @var int
     */
    protected const SHIPMENT_TYPE_ID_2 = 2000;

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var string
     */
    protected const PRODUCT_SKU = 'PRODUCT_SKU';

    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentTypeAvailability\ProductOfferShipmentTypeAvailabilityBusinessTester
     */
    protected ProductOfferShipmentTypeAvailabilityBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidateShipmentTypeWithValidRequest(): void
    {
        // Arrange
        $productOfferTransfer = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);
        $productOfferTransferWithoutProductOfferReference = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1);
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID_1);
        $productOfferShipmentTypeTransfer = (new ProductOfferShipmentTypeTransfer())
            ->setProductOffer($productOfferTransferWithoutProductOfferReference)
            ->addShipmentType($shipmentTypeTransfer);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->setShipmentType($shipmentTypeTransfer);
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);
        $productOfferShipmentTypeCollectionTransfer = (new ProductOfferShipmentTypeCollectionTransfer())
            ->addProductOfferShipmentType($productOfferShipmentTypeTransfer);

        $this->mockProductOfferFacade(1, $productOfferCollectionTransfer);
        $this->mockProductOfferShipmentTypeFacade(1, $productOfferShipmentTypeCollectionTransfer);

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
    public function testValidateShipmentTypeWithInvalidRequest(): void
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
    public function testValidateShipmentTypeWithInvalidRequestAndDeliveryShipmentTypeWhenProductOfferHasAnotherShipmentTypeShouldReturnNotSellableItem(): void
    {
        // Arrange
        $productAvailabilityCriteriaTransfer = $this->getInvalidProductAvailabilityCriteriaTransfer();
        $productAvailabilityCriteriaTransfer->getShipmentTypeOrFail()->setKey(static::SHIPMENT_TYPE_DELIVERY);

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
    public function testValidateShipmentTypeWithInvalidRequestAndDeliveryShipmentTypeWhenProductOfferHasNoOtherShipmentTypeShouldNotReturnNotSellableItem(): void
    {
        // Arrange
        $productOfferTransfer = (new ProductOfferTransfer())
            ->setIdProductOffer(static::PRODUCT_OFFER_ID_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID_1)
            ->setKey(static::SHIPMENT_TYPE_DELIVERY);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->setShipmentType($shipmentTypeTransfer);
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);
        $productOfferShipmentTypeCollectionTransfer = new ProductOfferShipmentTypeCollectionTransfer();

        $this->mockProductOfferFacade(1, $productOfferCollectionTransfer);
        $this->mockProductOfferShipmentTypeFacade(1, $productOfferShipmentTypeCollectionTransfer);

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
    public function testValidateShipmentTypeWithInvalidRequestWhenResponseAlreadyHasNotSellableItem(): void
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
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID_1);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setShipmentType($shipmentTypeTransfer);

        $this->mockProductOfferFacade(0, new ProductOfferCollectionTransfer());
        $this->mockProductOfferShipmentTypeFacade(0, new ProductOfferShipmentTypeCollectionTransfer());

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
    public function testSkipValidationForRequestWithoutShipmentType(): void
    {
        // Arrange
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);

        $this->mockProductOfferFacade(0, new ProductOfferCollectionTransfer());
        $this->mockProductOfferShipmentTypeFacade(0, new ProductOfferShipmentTypeCollectionTransfer());

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
        $productOfferFacadeMock = $this->createMock(ProductOfferShipmentTypeAvailabilityToProductOfferFacadeBridge::class);
        $productOfferFacadeMock
            ->expects($this->exactly($callCount))
            ->method('getProductOfferCollection')
            ->willReturn($productOfferCollectionTransfer);

        $this->tester->setDependency(ProductOfferShipmentTypeAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER, $productOfferFacadeMock);
    }

    /**
     * @param int $callCount
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return void
     */
    protected function mockProductOfferShipmentTypeFacade(
        int $callCount,
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): void {
        $productOfferShipmentTypeFacadeMock = $this->createMock(ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeBridge::class);
        $productOfferShipmentTypeFacadeMock
            ->expects($this->exactly($callCount))
            ->method('getProductOfferShipmentTypeCollection')
            ->willReturn($productOfferShipmentTypeCollectionTransfer);

        $this->tester->setDependency(ProductOfferShipmentTypeAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE, $productOfferShipmentTypeFacadeMock);
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
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID_1);
        $shipmentTypeTransferSecond = (new ShipmentTypeTransfer())
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID_2);
        $productOfferShipmentTypeTransfer = (new ProductOfferShipmentTypeTransfer())
            ->setProductOffer($productOfferTransferWithoutProductOfferReference)
            ->addShipmentType($shipmentTypeTransferSecond);
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->setShipmentType($shipmentTypeTransfer);
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);
        $productOfferShipmentTypeCollectionTransfer = (new ProductOfferShipmentTypeCollectionTransfer())
            ->addProductOfferShipmentType($productOfferShipmentTypeTransfer);

        $this->mockProductOfferFacade(1, $productOfferCollectionTransfer);
        $this->mockProductOfferShipmentTypeFacade(1, $productOfferShipmentTypeCollectionTransfer);

        return $productAvailabilityCriteriaTransfer;
    }
}
