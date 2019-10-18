<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use Codeception\Test\Unit;
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
 * @group UpdateMethodTest
 * Add your own group annotations below this line
 */
class UpdateMethodTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateMethodShouldUpdateShipmentMethodWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensureShipmentMethodTableIsEmpty();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::NAME => 'test',
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_ENTITY => $shipmentMethodTransfer->getIdShipmentMethod(),
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();

        $shipmentMethodTransfer->setStoreRelation($storeRelationTransfer);
        $shipmentMethodTransfer->setName('test1');

        // Act
        $this->tester->getFacade()->updateMethod($shipmentMethodTransfer);

        // Assert
        $resultShipmentMethodEntity = SpyShipmentMethodQuery::create()
            ->filterByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
            ->findOne();

        $storeRelationExist = SpyShipmentMethodStoreQuery::create()
            ->filterByFkShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
            ->exists();

        $this->assertEquals('test1', $resultShipmentMethodEntity->getName(), 'Shipment method name should match to the expected value');
        $this->assertTrue($storeRelationExist, 'Shipment method store relation should exists');
    }
}
