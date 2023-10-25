<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage\Business\Facade;

use Codeception\Test\Unit;
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
 * @group GetShipmentTypeStorageSynchronizationDataTransfersTest
 * Add your own group annotations below this line
 */
class GetShipmentTypeStorageSynchronizationDataTransfersTest extends Unit
{
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
        $this->tester->setUpQueueAdapter();
    }

    /**
     * @return void
     */
    public function testReturnsEmptyCollectionWhenShipmentTypeStorageDataIsEmpty(): void
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
    public function testReturnsEmptyCollectionWhenOffsetIsHigherThenAmountOfPersistedShipmentTypeStorages(): void
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
    public function testReturnsEmptyCollectionWhenNoShipmentTypeStoragesFoundByGivenIds(): void
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
    public function testReturnsLimitedCollectionWhenLimitIsGiven(): void
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
    public function testReturnsFullCollectionWhenNoLimitationParamsGiven(): void
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
