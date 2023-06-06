<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ShipmentTypeStorage\Communication\Plugin\Publisher\ShipmentTypePublisherTriggerPlugin;
use SprykerTest\Zed\ShipmentTypeStorage\ShipmentTypeStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ShipmentTypePublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypePublisherTriggerPluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_TYPE_RESOURCE_NAME
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_RESOURCE_NAME = 'shipment_type';

    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_TYPE_PUBLISH
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_PUBLISH = 'ShipmentType.shipment_type.publish';

    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap::COL_ID_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_ID_SHIPMENT_TYPE = 'spy_shipment_type.id_shipment_type';

    /**
     * @var \SprykerTest\Zed\ShipmentTypeStorage\ShipmentTypeStorageCommunicationTester
     */
    protected ShipmentTypeStorageCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGetDataReturnsShipmentTypeTransfersAccordingToOffsetAndLimit(): void
    {
        // Arrange
        $this->tester->ensureShipmentTypeDatabaseIsEmpty();

        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        // Act
        $shipmentTypeTransfers = (new ShipmentTypePublisherTriggerPlugin())->getData(1, 1);

        // Assert
        $this->assertCount(1, $shipmentTypeTransfers);
        $this->assertSame($shipmentTypeTransfer->getUuidOrFail(), $shipmentTypeTransfers[0]->getUuid());
    }

    /**
     * @return void
     */
    public function testGetDataReturnsNoShipmentTypeTransfersWhenLimitIsEqualToZero(): void
    {
        // Arrange
        $this->tester->ensureShipmentTypeDatabaseIsEmpty();

        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        // Act
        $shipmentTypeTransfers = (new ShipmentTypePublisherTriggerPlugin())->getData(0, 0);

        // Assert
        $this->assertCount(0, $shipmentTypeTransfers);
    }

    /**
     * @return void
     */
    public function testGetResourceNameReturnsCorrectResourceName(): void
    {
        // Act
        $resourceName = (new ShipmentTypePublisherTriggerPlugin())->getResourceName();

        // Assert
        $this->assertSame(static::SHIPMENT_TYPE_RESOURCE_NAME, $resourceName);
    }

    /**
     * @return void
     */
    public function testGetEventNameReturnsCorrectEventName(): void
    {
        // Act
        $eventName = (new ShipmentTypePublisherTriggerPlugin())->getEventName();

        // Assert
        $this->assertSame(static::SHIPMENT_TYPE_PUBLISH, $eventName);
    }

    /**
     * @return void
     */
    public function testGetIdColumnNameReturnsCorrectColumnName(): void
    {
        // Act
        $idColumnName = (new ShipmentTypePublisherTriggerPlugin())->getIdColumnName();

        // Assert
        $this->assertSame(static::COL_ID_SHIPMENT_TYPE, $idColumnName);
    }
}
