<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentType
 * @group Business
 * @group Facade
 * @group ExpandShipmentMethodCollectionWithShipmentTypeTest
 * Add your own group annotations below this line
 */
class ExpandShipmentMethodCollectionWithShipmentTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester
     */
    protected ShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandsShipmentMethodWithCorrespondingShipmentType(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $shipmentMethodCollectionTransfer = (new ShipmentMethodCollectionTransfer())->addShipmentMethod($shipmentMethodTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()
            ->expandShipmentMethodCollectionWithShipmentType($shipmentMethodCollectionTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $expandedShipmentMethodTransfer = $shipmentMethodCollectionTransfer->getShipmentMethods()->getIterator()->current();
        $this->assertNotNull($expandedShipmentMethodTransfer->getShipmentType());
        $this->assertSame($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $expandedShipmentMethodTransfer->getShipmentType()->getIdShipmentType());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenShipmentMethodDoesNotHaveShipmentTypeRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $shipmentMethodCollectionTransfer = (new ShipmentMethodCollectionTransfer())->addShipmentMethod($shipmentMethodTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()
            ->expandShipmentMethodCollectionWithShipmentType($shipmentMethodCollectionTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $this->assertNull($shipmentMethodCollectionTransfer->getShipmentMethods()->getIterator()->current()->getShipmentType());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenIdShipmentMethodIsNotProvided(): void
    {
        // Arrange
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodCollectionTransfer = (new ShipmentMethodCollectionTransfer())->addShipmentMethod($shipmentMethodTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->expandShipmentMethodCollectionWithShipmentType($shipmentMethodCollectionTransfer);
    }
}
