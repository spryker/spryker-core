<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePoint\Facade\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointDependencyProvider;
use SprykerTest\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeServicePoint
 * @group Facade
 * @group Business
 * @group ExpandShipmentTypeStoragesWithServiceTypeTest
 * Add your own group annotations below this line
 */
class ExpandShipmentTypeStoragesWithServiceTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointBusinessTester
     */
    protected ShipmentTypeServicePointBusinessTester $tester;

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
    public function testExpandShipmentTypeStoragesWithServiceTypeExpandsWithServiceType(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->tester->haveServiceType();

        $shipmentType = $this->tester->haveShipmentType();
        $shipmentTypeWithServiceType = $this->createShipmentTypeWithServiceType($serviceTypeTransfer);

        $shipmentTypeStorageTransfers = [
            (new ShipmentTypeStorageTransfer())->fromArray($shipmentType->toArray(), true),
            (new ShipmentTypeStorageTransfer())->fromArray($shipmentTypeWithServiceType->toArray(), true),
        ];

        // Act
        $shipmentTypeStorageTransfers = $this->tester
            ->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);

        // Assert
        $this->assertNull($shipmentTypeStorageTransfers[0]->getServiceType());
        $this->assertSame($serviceTypeTransfer->getKeyOrFail(), $shipmentTypeStorageTransfers[1]->getServiceType());
    }

    /**
     * @return void
     */
    public function testExpandShipmentTypeStoragesWithServiceTypeExpandsStoragesWithDifferentServiceTypes(): void
    {
        // Arrange
        $firstShipmentType = $this->createShipmentTypeWithServiceType($this->tester->haveServiceType());
        $secondShipmentType = $this->createShipmentTypeWithServiceType($this->tester->haveServiceType());

        $shipmentTypeStorageTransfers = [
            (new ShipmentTypeStorageTransfer())->fromArray($firstShipmentType->toArray(), true),
            (new ShipmentTypeStorageTransfer())->fromArray($secondShipmentType->toArray(), true),
        ];

        // Act
        $shipmentTypeStorageTransfers = $this->tester
            ->getFacade()
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
    public function testExpandShipmentTypeStoragesWithServiceTypeReturnsOriginalStorageTransfers(): void
    {
        // Arrange
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

    /**
     * @return void
     */
    public function testExpandShipmentTypeStoragesWithServiceTypeThrowsExceptionWhenIdNotProvided(): void
    {
        // Arrange
        $shipmentTypeStorageTransfers = [
            new ShipmentTypeStorageTransfer(),
        ];

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);
    }

    /**
     * @return void
     */
    public function testExpandShipmentTypeStoragesWithServiceTypeDoesNotExpandsStoragesWhenServiceTypeCollectionEmpty(): void
    {
        // Arrange
        $this->tester->setDependency(
            ShipmentTypeServicePointDependencyProvider::FACADE_SERVICE_POINT,
            $this->getShipmentTypeServicePointToServicePointFacadeMock(),
        );

        $serviceTypeTransfer = $this->tester->haveServiceType();

        $shipmentTypeStorageTransfers = [
            (new ShipmentTypeStorageTransfer())->fromArray($this->createShipmentTypeWithServiceType($serviceTypeTransfer)->toArray(), true),
            (new ShipmentTypeStorageTransfer())->fromArray($this->createShipmentTypeWithServiceType($serviceTypeTransfer)->toArray(), true),
        ];

        // Act
        $shipmentTypeStorageTransfers = $this->tester
            ->getFacade()
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);

        // Assert
        $this->assertNull($shipmentTypeStorageTransfers[0]->getServiceType());
        $this->assertNull($shipmentTypeStorageTransfers[1]->getServiceType());
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    protected function createShipmentTypeWithServiceType(ServiceTypeTransfer $serviceTypeTransfer): ShipmentTypeTransfer
    {
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->createShipmentTypeServiceType($shipmentTypeTransfer, $serviceTypeTransfer);

        return $shipmentTypeTransfer;
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getShipmentTypeServicePointToServicePointFacadeMock(): ShipmentTypeServicePointToServicePointFacadeInterface
    {
        $shipmentTypeServicePointToServicePointFacadeMock = $this
            ->getMockBuilder(ShipmentTypeServicePointToServicePointFacadeInterface::class)
            ->getMock();

        $shipmentTypeServicePointToServicePointFacadeMock
            ->expects($this->once())
            ->method('getServiceTypeCollection')
            ->willReturn(new ServiceTypeCollectionTransfer());

        return $shipmentTypeServicePointToServicePointFacadeMock;
    }
}
