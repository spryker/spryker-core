<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShipmentTypeServicePointStorage\Client;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServiceTypeStorageBuilder;
use Generated\Shared\DataBuilder\ShipmentTypeStorageBuilder;
use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface;
use Spryker\Client\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageDependencyProvider;
use SprykerTest\Client\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShipmentTypeServicePointStorage
 * @group Client
 * @group ExpandShipmentTypeStorageCollectionWithServiceTypeTest
 * Add your own group annotations below this line
 */
class ExpandShipmentTypeStorageCollectionWithServiceTypeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE_UUID = 'test-service-type-uuid';

    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE_KEY = 'test-service-type-key';

    /**
     * @var int
     */
    protected const TEST_ID_SERVICE_TYPE = 777;

    /**
     * @var \SprykerTest\Client\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageClientTester
     */
    protected ShipmentTypeServicePointStorageClientTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            ShipmentTypeServicePointStorageDependencyProvider::CLIENT_SERVICE_POINT_STORAGE,
            $this->getServicePointStorageClientMock(),
        );
    }

    /**
     * @return void
     */
    public function testShouldExpandCollection(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageBuilder([
            ShipmentTypeStorageTransfer::SERVICE_TYPE => [
                ServiceTypeStorageTransfer::UUID => static::TEST_SERVICE_TYPE_UUID,
            ],
        ]))->build();
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer);

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->expandShipmentTypeStorageCollectionWithServiceType($shipmentTypeStorageCollectionTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
        $expandedShipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getIterator()->current();
        $this->assertNotNull($expandedShipmentTypeStorageTransfer->getServiceType());
        $this->assertSame(static::TEST_ID_SERVICE_TYPE, $expandedShipmentTypeStorageTransfer->getServiceTypeOrFail()->getIdServiceType());
        $this->assertSame(static::TEST_SERVICE_TYPE_KEY, $expandedShipmentTypeStorageTransfer->getServiceTypeOrFail()->getKey());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenServiceTypeUuidIsNotSpecifiedInShipmentTypeStorageTransfer(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageBuilder([
            ShipmentTypeStorageTransfer::SERVICE_TYPE => null,
        ]))->build();
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer);

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->expandShipmentTypeStorageCollectionWithServiceType($shipmentTypeStorageCollectionTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
        $expandedShipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getIterator()->current();
        $this->assertNull($expandedShipmentTypeStorageTransfer->getServiceType());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenServiceTypeWithProvidedUuidIsNotFoundInStorage(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageBuilder([
            ShipmentTypeStorageTransfer::SERVICE_TYPE => [
                ServiceTypeStorageTransfer::UUID => 'non-existing-uuid',
            ],
        ]))->build();
        $shipmentTypeStorageCollectionTransfer = (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage($shipmentTypeStorageTransfer);

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->expandShipmentTypeStorageCollectionWithServiceType($shipmentTypeStorageCollectionTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
        $expandedShipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getIterator()->current();
        $this->assertNotNull($expandedShipmentTypeStorageTransfer->getServiceType());
        $this->assertNull($expandedShipmentTypeStorageTransfer->getServiceTypeOrFail()->getIdServiceType());
        $this->assertNull($expandedShipmentTypeStorageTransfer->getServiceTypeOrFail()->getKey());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface
     */
    protected function getServicePointStorageClientMock(): ShipmentTypeServicePointStorageToServicePointStorageClientInterface
    {
        $serviceTypeStorageTransfer = (new ServiceTypeStorageBuilder([
            ServiceTypeStorageTransfer::UUID => static::TEST_SERVICE_TYPE_UUID,
            ServiceTypeStorageTransfer::ID_SERVICE_TYPE => static::TEST_ID_SERVICE_TYPE,
            ServiceTypeStorageTransfer::KEY => static::TEST_SERVICE_TYPE_KEY,
        ]))->build();

        $servicePointStorageClientMock = $this->getMockBuilder(ShipmentTypeServicePointStorageToServicePointStorageClientInterface::class)
            ->getMock();

        $servicePointStorageClientMock->method('getServiceTypeStorageCollection')
            ->willReturnCallback(function (ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer) use ($serviceTypeStorageTransfer) {
                $serviceTypeStorageCollectionTransfer = new ServiceTypeStorageCollectionTransfer();
                if (!$serviceTypeStorageCriteriaTransfer->getServiceTypeStorageConditions()) {
                    return $serviceTypeStorageCollectionTransfer;
                }

                foreach ($serviceTypeStorageCriteriaTransfer->getServiceTypeStorageConditionsOrFail()->getUuids() as $uuid) {
                    if ($serviceTypeStorageTransfer->getUuidOrFail() === $uuid) {
                        $serviceTypeStorageCollectionTransfer->addServiceTypeStorage($serviceTypeStorageTransfer);
                    }
                }

                return $serviceTypeStorageCollectionTransfer;
            });

        return $servicePointStorageClientMock;
    }
}
