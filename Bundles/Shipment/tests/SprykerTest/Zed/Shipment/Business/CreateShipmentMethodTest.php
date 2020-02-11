<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStoreQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group CreateShipmentMethodTest
 * Add your own group annotations below this line
 */
class CreateShipmentMethodTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMethodShouldCreateShipmentMethodWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensureShipmentMethodTableIsEmpty();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();

        $shipmentMethodTransfer = (new ShipmentMethodBuilder())->seed([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'test1',
            ShipmentMethodTransfer::NAME => 'test1',
            ShipmentMethodTransfer::CARRIER_NAME => 'test2',
            ShipmentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ])->build();

        // Act
        $this->tester->getFacade()->createMethod($shipmentMethodTransfer);

        // Assert
        $shipmentMethodExist = SpyShipmentMethodQuery::create()
            ->filterByName('test1')
            ->exists();
        $storeRelationExist = SpyShipmentMethodStoreQuery::create()
            ->useShipmentMethodQuery()
            ->filterByName('test1')
            ->endUse()
            ->exists();

        $this->assertTrue($shipmentMethodExist, 'Shipment method should exists');
        $this->assertTrue($storeRelationExist, 'Shipment method store relation should exists');
    }
}
