<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
 * @group WriteShipmentTypeStorageCollectionByShipmentTypeStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteShipmentTypeStorageCollectionByShipmentTypeStoreEventsTest extends Unit
{
    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeStoreTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'spy_shipment_type_store.fk_shipment_type';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

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
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_TYPE => $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents([$eventEntityTransfer]);

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
    public function testRemovesShipmentTypeStorageWhenIdOfShipmentTypeWithRemovedStoreRelationIsProvided(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransferDe),
        ]);

        $shipmentTypeStorageTransfer = $this->tester->createShipmentTypeStorageTransfer($shipmentTypeTransfer);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransferDe);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransferAt);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_TYPE => $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents([$eventEntityTransfer]);

        // Assert
        $this->assertSame(1, $this->tester->getShipmentTypeStorageEntitiesCount());
        $shipmentTypeStorageTransfer = $this->tester->findShipmentTypeStorageTransfer(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $storeTransferDe->getNameOrFail(),
        );
        $this->assertNotNull($shipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesExpanderPlugins(): void
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
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_TYPE => $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents([$eventEntityTransfer]);
    }

    /**
     * @return void
     */
    public function testShouldEarlyReturnWhenShipmentTypeCollectionIsEmpty(): void
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

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_TYPE => $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        ]);

        // Assert
        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::FACADE_STORE, $this->tester->getStoreFacadeMock());

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents([$eventEntityTransfer]);
    }
}
