<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePointStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeServicePointStorage
 * @group Business
 * @group Facade
 * @group ExpandShipmentTypeStoragesWithServiceTypeTest
 * Add your own group annotations below this line
 */
class ExpandShipmentTypeStoragesWithServiceTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageBusinessTester
     */
    protected ShipmentTypeServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureShipmentTypeServiceTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testExpandsWithServiceType(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->tester->haveServiceType();

        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $shipmentTypeWithServiceTypeTransfer = $this->tester->haveShipmentTypeWithServiceTypeRelation($serviceTypeTransfer);

        $shipmentTypeStorageTransfers = [
            (new ShipmentTypeStorageTransfer())->fromArray($shipmentTypeTransfer->toArray(), true),
            (new ShipmentTypeStorageTransfer())->fromArray($shipmentTypeWithServiceTypeTransfer->toArray(), true),
        ];

        // Act
        $shipmentTypeStorageTransfers = $this->tester->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);

        // Assert
        $this->assertNull($shipmentTypeStorageTransfers[0]->getServiceType());
        $this->assertNotNull($shipmentTypeStorageTransfers[1]->getServiceType());
        $this->assertSame(
            $serviceTypeTransfer->getUuidOrFail(),
            $shipmentTypeStorageTransfers[1]->getServiceTypeOrFail()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testExpandsStoragesWithDifferentServiceTypes(): void
    {
        // Arrange
        $firstShipmentTypeTransfer = $this->tester->haveShipmentTypeWithServiceTypeRelation($this->tester->haveServiceType());
        $secondShipmentTypeTransfer = $this->tester->haveShipmentTypeWithServiceTypeRelation($this->tester->haveServiceType());

        $shipmentTypeStorageTransfers = [
            (new ShipmentTypeStorageTransfer())->fromArray($firstShipmentTypeTransfer->toArray(), true),
            (new ShipmentTypeStorageTransfer())->fromArray($secondShipmentTypeTransfer->toArray(), true),
        ];

        // Act
        $shipmentTypeStorageTransfers = $this->tester->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);

        // Assert
        $this->assertNotNull($shipmentTypeStorageTransfers[0]->getServiceType());
        $this->assertNotNull($shipmentTypeStorageTransfers[1]->getServiceType());
        $this->assertNotSame(
            $shipmentTypeStorageTransfers[0]->getServiceType(),
            $shipmentTypeStorageTransfers[1]->getServiceType(),
        );
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenIdShipmentTypeIsNotProvided(): void
    {
        // Arrange
        $shipmentTypeStorageTransfers = [
            new ShipmentTypeStorageTransfer(),
        ];

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);
    }

    /**
     * @return void
     */
    public function testDoesNotExpandStoragesWhenShipmentTypeServiceTypeCollectionEmpty(): void
    {
        // Arrange
        $this->tester->haveServiceType();
        $shipmentTypeStorageTransfers = [
            (new ShipmentTypeStorageTransfer())->fromArray($this->tester->haveShipmentType()->toArray(), true),
            (new ShipmentTypeStorageTransfer())->fromArray($this->tester->haveShipmentType()->toArray(), true),
        ];

        // Act
        $shipmentTypeStorageTransfers = $this->tester
            ->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);

        // Assert
        $this->assertNull($shipmentTypeStorageTransfers[0]->getServiceType());
        $this->assertNull($shipmentTypeStorageTransfers[1]->getServiceType());
    }
}
