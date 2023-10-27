<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferShipmentTypeAvailabilityStorage\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Dependency\Client\ProductOfferShipmentTypeAvailabilityStorageToShipmentTypeStorageClientBridge;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\ProductOfferShipmentTypeAvailabilityStorageDependencyProvider;
use SprykerTest\Client\ProductOfferShipmentTypeAvailabilityStorage\ProductOfferShipmentTypeAvailabilityStorageClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferShipmentTypeAvailabilityStorage
 * @group Client
 * @group FilterProductOfferServicePointAvailabilityCollectionTest
 * Add your own group annotations below this line
 */
class FilterProductOfferServicePointAvailabilityCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_UUID_1 = 'SHIPMENT_TYPE_UUID_1';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_UUID_2 = 'SHIPMENT_TYPE_UUID_2';

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var \SprykerTest\Client\ProductOfferShipmentTypeAvailabilityStorage\ProductOfferShipmentTypeAvailabilityStorageClientTester
     */
    protected ProductOfferShipmentTypeAvailabilityStorageClientTester $tester;

    /**
     * @return void
     */
    public function testFilterReturnsDataWithCorrectShipmentTypeUuid(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID_1);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->addShipmentType($shipmentTypeStorageTransfer);
        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->setShipmentTypeUuid($shipmentTypeStorageTransfer->getUuidOrFail()),
            );
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())->setProductOfferStorage($productOfferStorageTransfer),
            );
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer);
        $this->mockShipmentTypeStorageClient($shipmentTypeStorageCollectionTransfer);

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->filterProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems());
    }

    /**
     * @return void
     */
    public function testFilterDoesNotReturnDataWithIncorrectShipmentTypeUuid(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID_1);
        $shipmentTypeStorageTransfer2 = (new ShipmentTypeStorageTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID_2);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->addShipmentType($shipmentTypeStorageTransfer);
        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->setShipmentTypeUuid($shipmentTypeStorageTransfer2->getUuidOrFail()),
            );
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())->setProductOfferStorage($productOfferStorageTransfer),
            );
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer2);
        $this->mockShipmentTypeStorageClient($shipmentTypeStorageCollectionTransfer);

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->filterProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems());
    }

    /**
     * @return void
     */
    public function testFilterReturnsDataWithDeliveryShipmentTypeAndProductHasShipmentTypes(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setKey(static::SHIPMENT_TYPE_DELIVERY)
            ->setUuid(static::SHIPMENT_TYPE_UUID_1);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->addShipmentType($shipmentTypeStorageTransfer);
        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->setShipmentTypeUuid($shipmentTypeStorageTransfer->getUuidOrFail()),
            );
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())->setProductOfferStorage($productOfferStorageTransfer),
            );
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer);
        $this->mockShipmentTypeStorageClient($shipmentTypeStorageCollectionTransfer);

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->filterProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems());
    }

    /**
     * @return void
     */
    public function testFilterReturnsDataWithDeliveryShipmentTypeAndProductHasNoShipmentTypes(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setKey(static::SHIPMENT_TYPE_DELIVERY)
            ->setUuid(static::SHIPMENT_TYPE_UUID_1);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer());
        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->setShipmentTypeUuid($shipmentTypeStorageTransfer->getUuidOrFail()),
            );
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())->setProductOfferStorage($productOfferStorageTransfer),
            );
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer);
        $this->mockShipmentTypeStorageClient($shipmentTypeStorageCollectionTransfer);

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->filterProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems());
    }

    /**
     * @return void
     */
    public function testFilterReturnsDataWithEmptyShipmentTypeUuid(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID_1);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->addShipmentType($shipmentTypeStorageTransfer);
        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(new ProductOfferServicePointAvailabilityConditionsTransfer());
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())->setProductOfferStorage($productOfferStorageTransfer),
            );

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->filterProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return void
     */
    protected function mockShipmentTypeStorageClient(ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer): void
    {
        $shipmentTypeStorageClient = $this->createMock(ProductOfferShipmentTypeAvailabilityStorageToShipmentTypeStorageClientBridge::class);
        $shipmentTypeStorageClient
            ->method('getShipmentTypeStorageCollection')
            ->willReturn($shipmentTypeStorageCollectionTransfer);

        $this->tester->setDependency(ProductOfferShipmentTypeAvailabilityStorageDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE, $shipmentTypeStorageClient);
    }
}
