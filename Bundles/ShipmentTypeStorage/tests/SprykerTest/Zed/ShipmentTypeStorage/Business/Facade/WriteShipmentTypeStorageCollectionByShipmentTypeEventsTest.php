<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageDependencyProvider;
use SprykerTest\Zed\ShipmentTypeStorage\ShipmentTypeStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeStorage
 * @group Business
 * @group Facade
 * @group WriteShipmentTypeStorageCollectionByShipmentTypeEventsTest
 * Add your own group annotations below this line
 */
class WriteShipmentTypeStorageCollectionByShipmentTypeEventsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentTypeStorage\ShipmentTypeStorageBusinessTester
     */
    protected ShipmentTypeStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureShipmentTypeStorageTableIsEmpty();
        $this->tester->ensureStoreTableIsEmpty();
        $this->tester->setUpQueueAdapter();
    }

    /**
     * @return void
     */
    public function testPersistsShipmentTypeStorageData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $shipmentTypeStorageTransfer = $this->tester->findShipmentTypeStorageTransfer(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($shipmentTypeStorageTransfer);
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $shipmentTypeStorageTransfer->getName());
        $this->assertSame($shipmentTypeTransfer->getKeyOrFail(), $shipmentTypeStorageTransfer->getKey());
        $this->assertSame($shipmentTypeTransfer->getUuidOrFail(), $shipmentTypeStorageTransfer->getUuid());
        $this->assertSame($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeStorageTransfer->getIdShipmentType());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNotExistingIdShipmentTypeProvided(): void
    {
        // Arrange
        $this->tester->ensureShipmentTypeStorageTableIsEmpty();
        $eventEntityTransfer = (new EventEntityTransfer())->setId(0);

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeStorageEntitiesCount());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenIdOfInactiveShipmentTypeIsProvided(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeStorageEntitiesCount());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenIdOfShipmentTypeWithoutStoreRelationIsProvided(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeStorageEntitiesCount());
    }

    /**
     * @return void
     */
    public function testRemovesShipmentTypeStorageWhenIdOfDeactivatedShipmentTypeIsProvided(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeStorageTransfer = $this->tester->createShipmentTypeStorageTransfer($shipmentTypeTransfer);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeStorageEntitiesCount());
    }

    /**
     * @return void
     */
    public function testExpandsStorageDataWithShipmentMethodIds(): void
    {
        // Arrange
        $this->tester->setUpShipmentTypeShipmentMethodCollectionExpanderPluginDependency();

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethodWithShipmentTypeRelation(
            $shipmentTypeTransfer1,
            $storeTransfer,
            [ShipmentMethodTransfer::IS_ACTIVE => true],
        );

        $eventEntityTransfer1 = (new EventEntityTransfer())->setId($shipmentTypeTransfer1->getIdShipmentTypeOrFail());
        $eventEntityTransfer2 = (new EventEntityTransfer())->setId($shipmentTypeTransfer2->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([
            $eventEntityTransfer1,
            $eventEntityTransfer2,
        ]);

        // Assert
        $shipmentTypeStorageTransferWithShipmentMethodIds = $this->tester->findShipmentTypeStorageTransfer(
            $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertCount(1, $shipmentTypeStorageTransferWithShipmentMethodIds->getShipmentMethodIds());
        $this->assertContainsEquals(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeStorageTransferWithShipmentMethodIds->getShipmentMethodIds(),
        );

        $shipmentTypeStorageTransferWithoutShipmentMethodIds = $this->tester->findShipmentTypeStorageTransfer(
            $shipmentTypeTransfer2->getIdShipmentTypeOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertCount(0, $shipmentTypeStorageTransferWithoutShipmentMethodIds->getShipmentMethodIds());
    }

    /**
     * @return void
     */
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsExecutesExpanderPlugins(): void
    {
        // Assert
        $this->tester->setDependency(
            ShipmentTypeStorageDependencyProvider::PLUGINS_SHIPMENT_TYPE_STORAGE_EXPANDER,
            [$this->tester->getShipmentTypeStorageExpanderPluginMock()],
        );

        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([$eventEntityTransfer]);
    }

    /**
     * @return void
     */
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsShouldEarlyReturnWhenShipmentTypeCollectionIsEmpty(): void
    {
        // Arrange
        $this->tester->setDependency(
            ShipmentTypeStorageDependencyProvider::FACADE_SHIPMENT_TYPE,
            $this->tester->getShipmentTypeFacadeMock(),
        );

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeStorageTransfer = $this->tester->createShipmentTypeStorageTransfer($shipmentTypeTransfer);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransfer);

        // Assert
        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::FACADE_STORE, $this->tester->getStoreFacadeMock());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeEvents([
            (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail()),
        ]);
    }
}
