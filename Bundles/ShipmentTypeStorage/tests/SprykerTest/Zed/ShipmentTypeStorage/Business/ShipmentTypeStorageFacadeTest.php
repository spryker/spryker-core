<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\ShipmentTypeStorage\ShipmentTypeStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeStorage
 * @group Business
 * @group Facade
 * @group ShipmentTypeStorageFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentTypeStorageFacadeTest extends Unit
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
     * @var int
     */
    protected const FAKE_SHIPMENT_TYPE_STORAGE_ID = -1;

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
    }

    /**
     * @return void
     */
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsPersistsShipmentTypeStorageData(): void
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
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeStoreEventsPersistsShipmentTypeStorageData(): void
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
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsDoesNothingWhenNotExistingIdShipmentTypeProvided(): void
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
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsDoesNothingWhenIdOfInactiveShipmentTypeIsProvided(): void
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
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsDoesNothingWhenIdOfShipmentTypeWithoutStoreRelationIsProvided(): void
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
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeEventsRemovesShipmentTypeStorageWhenIdOfDeactivatedShipmentTypeIsProvided(): void
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
    public function testWriteShipmentTypeStorageCollectionByShipmentTypeStoreEventsRemovesShipmentTypeStorageWhenIdOfShipmentTypeWithRemovedStoreRelationIsProvided(): void
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
    public function testGetShipmentTypeStorageSynchronizationDataTransfersReturnsEmptyCollectionWhenShipmentTypeStorageDataIsEmpty(): void
    {
        // Act
        $synchronizationDataTransfers = $this
            ->tester
            ->getFacade()
            ->getShipmentTypeStorageSynchronizationDataTransfers($this->tester->createFilterTransfer());

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageSynchronizationDataTransfersReturnsEmptyCollectionWhenOffsetIsHigherThenAmountOfPersistedShipmentTypeStorages(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            ],
        );

        $shipmentTypeStorageTransfer = $this->tester->createShipmentTypeStorageTransfer($shipmentTypeTransfer);

        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransfer);

        // Act
        $synchronizationDataTransfers = $this
            ->tester
            ->getFacade()
            ->getShipmentTypeStorageSynchronizationDataTransfers($this->tester->createFilterTransfer(5, 0));

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageSynchronizationDataTransfersReturnsEmptyCollectionWhenNoShipmentTypeStoragesFoundByGivenIds(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            ],
        );

        $shipmentTypeStorageTransfer = $this->tester->createShipmentTypeStorageTransfer($shipmentTypeTransfer);

        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransfer);

        // Act
        $synchronizationDataTransfers = $this
            ->tester
            ->getFacade()
            ->getShipmentTypeStorageSynchronizationDataTransfers(
                $this->tester->createFilterTransfer(),
                [static::FAKE_SHIPMENT_TYPE_STORAGE_ID],
            );

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageSynchronizationDataTransfersReturnsLimitedCollectionWhenLimitIsGiven(): void
    {
        // Arrange
        $store1Transfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $store2Transfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);

        $shipmentType1Transfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($store1Transfer),
            ],
        );
        $shipmentType2Transfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($store2Transfer),
            ],
        );

        $shipmentTypeStorage1Transfer = $this->tester->createShipmentTypeStorageTransfer($shipmentType1Transfer);
        $shipmentTypeStorage2Transfer = $this->tester->createShipmentTypeStorageTransfer($shipmentType2Transfer);

        $this->tester->createShipmentTypeStorage($shipmentTypeStorage1Transfer, $store1Transfer);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorage2Transfer, $store2Transfer);

        // Act
        $synchronizationDataTransfers = $this
            ->tester
            ->getFacade()
            ->getShipmentTypeStorageSynchronizationDataTransfers($this->tester->createFilterTransfer(0, 1));

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageSynchronizationDataTransfersReturnsFullCollectionWhenNoLimitationParamsGiven(): void
    {
        // Arrange
        $store1Transfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $store2Transfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);

        $shipmentType1Transfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($store1Transfer),
            ],
        );
        $shipmentType2Transfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($store2Transfer),
            ],
        );

        $shipmentTypeStorage1Transfer = $this->tester->createShipmentTypeStorageTransfer($shipmentType1Transfer);
        $shipmentTypeStorage2Transfer = $this->tester->createShipmentTypeStorageTransfer($shipmentType2Transfer);

        $this->tester->createShipmentTypeStorage($shipmentTypeStorage1Transfer, $store1Transfer);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorage2Transfer, $store2Transfer);

        // Act
        $synchronizationDataTransfers = $this
            ->tester
            ->getFacade()
            ->getShipmentTypeStorageSynchronizationDataTransfers($this->tester->createFilterTransfer());

        // Assert
        $this->assertCount(2, $synchronizationDataTransfers);
    }
}
