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
 * @group WriteShipmentTypeStorageCollectionByShipmentMethodStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteShipmentTypeStorageCollectionByShipmentMethodStoreEventsTest extends Unit
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
     * @uses \Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodStoreTableMap::COL_FK_SHIPMENT_METHOD
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_METHOD = 'spy_shipment_method_store.fk_shipment_method';

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
        $this->tester->setUpShipmentTypeShipmentMethodCollectionExpanderPluginDependency();
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
        $shipmentMethodTransfer = $this->tester->haveShipmentMethodWithShipmentTypeRelation(
            $shipmentTypeTransfer,
            $storeTransfer,
            [ShipmentMethodTransfer::IS_ACTIVE => true],
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_METHOD => $shipmentMethodTransfer->getIdShipmentMethod(),
        ]);

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentMethodStoreEvents([$eventEntityTransfer]);

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
        $this->assertContainsEquals($shipmentMethodTransfer->getIdShipmentMethodOrFail(), $shipmentTypeStorageTransfer->getShipmentMethodIds());
    }

    /**
     * @return void
     */
    public function testRemovesShipmentMethodIdWhenShipmentMethodStoreRelationIsRemoved(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransferDe),
        ]);
        $shipmentMethodTransfer1 = $this->tester->haveShipmentMethodWithShipmentTypeRelation(
            $shipmentTypeTransfer,
            $storeTransferAt,
            [ShipmentMethodTransfer::IS_ACTIVE => true],
        );
        $shipmentMethodTransfer2 = $this->tester->haveShipmentMethodWithShipmentTypeRelation(
            $shipmentTypeTransfer,
            $storeTransferDe,
            [ShipmentMethodTransfer::IS_ACTIVE => true],
        );

        $shipmentTypeStorageTransfer = $this->tester->createShipmentTypeStorageTransfer($shipmentTypeTransfer);
        $shipmentTypeStorageTransfer->setShipmentMethodIds([
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfer2->getIdShipmentMethodOrFail(),
        ]);
        $this->tester->createShipmentTypeStorage($shipmentTypeStorageTransfer, $storeTransferDe);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_METHOD => $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeShipmentTypeStorageCollectionByShipmentMethodStoreEvents([$eventEntityTransfer]);

        // Assert
        $shipmentTypeStorageTransfer = $this->tester->findShipmentTypeStorageTransfer(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $storeTransferDe->getNameOrFail(),
        );
        $this->assertNotNull($shipmentTypeStorageTransfer);
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $shipmentTypeStorageTransfer->getName());
        $this->assertSame($shipmentTypeTransfer->getKeyOrFail(), $shipmentTypeStorageTransfer->getKey());
        $this->assertSame($shipmentTypeTransfer->getUuidOrFail(), $shipmentTypeStorageTransfer->getUuid());
        $this->assertSame($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeStorageTransfer->getIdShipmentType());
        $this->assertCount(1, $shipmentTypeStorageTransfer->getShipmentMethodIds());
        $this->assertContainsEquals($shipmentMethodTransfer2->getIdShipmentMethodOrFail(), $shipmentTypeStorageTransfer->getShipmentMethodIds());
        $this->assertNotContainsEquals($shipmentMethodTransfer1->getIdShipmentMethodOrFail(), $shipmentTypeStorageTransfer->getShipmentMethodIds());
    }
}
