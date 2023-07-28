<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShipmentTypeStorage;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\StorageScanResultTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageDependencyProvider;
use Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShipmentTypeStorage
 * @group ShipmentTypeStorageClientTest
 * Add your own group annotations below this line
 */
class ShipmentTypeStorageClientTest extends Unit
{
    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_TYPE_RESOURCE_NAME
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_RESOURCE_NAME = 'shipment_type';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'de';

    /**
     * @var int
     */
    protected const TEST_ID_SHIPMENT_TYPE = 777;

    /**
     * @var string
     */
    protected const TEST_UUID = 'test_uuid';

    /**
     * @uses \Spryker\Client\ShipmentTypeStorage\Generator\ShipmentTypeStorageKeyGenerator::MAPPING_TYPE_UUID
     *
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @uses \Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReader::KEY_ID
     *
     * @var string
     */
    protected const KEY_ID = 'id';

    /**
     * @var \SprykerTest\Client\ShipmentTypeStorage\ShipmentTypeStorageClientTester
     */
    protected ShipmentTypeStorageClientTester $tester;

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionThrowsAnExceptionWhenCriteriaWithNoConditionsTransferIsProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getShipmentTypeStorageCollection(new ShipmentTypeStorageCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionThrowsAnExceptionWhenConditionsTransferWithoutStoreNameIsProvided(): void
    {
        // Arrange
        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())->addIdShipmentType(static::TEST_ID_SHIPMENT_TYPE),
        );

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionThrowsAnExceptionWhenStoreNameCriteriaWasNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer()),
        );

        // Act
        $this->tester->getClient()->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionReturnsCollectionFilteredByStoreName(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $storageClientMock->expects($this->never())->method('getKeys');

        $storageClientMock->method('getMulti')->willReturn([
            'fake_storage_key_1' => [
                static::KEY_ID => static::TEST_ID_SHIPMENT_TYPE,
                ShipmentTypeStorageTransfer::ID_SHIPMENT_TYPE => static::TEST_ID_SHIPMENT_TYPE,
            ],
            'fake_storage_key_2' => [
                static::KEY_ID => static::TEST_ID_SHIPMENT_TYPE,
                ShipmentTypeStorageTransfer::ID_SHIPMENT_TYPE => static::TEST_ID_SHIPMENT_TYPE,
            ],
        ]);
        $storageClientMock->method('scanKeys')->willReturn(
            (new StorageScanResultTransfer())->setKeys(['fake_storage_key_1', 'fake_storage_key_2']),
        );

        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester
            ->getClient()
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionReturnsCollectionFilteredByStoreNameByGetKeysFallbackFunction(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $storageClientMock->method('scanKeys')->willThrowException(new Exception());

        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getKeys');

        // Act
        $this->tester->getClient()->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionFiltersStorageDataByShipmentTypeIdsAccordingToProvidedConditions(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->addIdShipmentType(static::TEST_ID_SHIPMENT_TYPE)
                ->setStoreName(static::STORE_NAME_DE),
        );

        $expectedKey = sprintf(
            '%s:%s:%s',
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::TEST_ID_SHIPMENT_TYPE,
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$expectedKey]);

        // Act
        $this->tester->getClient()->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionFiltersStorageDataByUuidAccordingToProvidedConditions(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        $expectedKey = sprintf(
            '%s:%s:%s:%s',
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$expectedKey]);

        // Act
        $this->tester->getClient()->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionReturnsEmptyCollectionWhenNoStorageDataFoundByShipmentTypeIds(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $storageClientMock->method('getMulti')->willReturn([null]);
        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->addIdShipmentType(static::TEST_ID_SHIPMENT_TYPE)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionReturnsCollectionOfFoundStorageDataFilteredByShipmentTypeIds(): void
    {
        // Arrange
        $shipmentTypeStorageKey = sprintf(
            '%s:%s:%s',
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::TEST_ID_SHIPMENT_TYPE,
        );

        $this->tester->setStorageData($shipmentTypeStorageKey, [
            ShipmentTypeStorageTransfer::ID_SHIPMENT_TYPE => static::TEST_ID_SHIPMENT_TYPE,
        ]);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->addIdShipmentType(static::TEST_ID_SHIPMENT_TYPE)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());

        /** @var \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer */
        $shipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getIterator()->current();
        $this->assertSame(static::TEST_ID_SHIPMENT_TYPE, $shipmentTypeStorageTransfer->getIdShipmentType());
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionReturnsEmptyCollectionWhenNoStorageDataFoundByUuids(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();

        $uuidMappingStorageKey = sprintf(
            '%s:%s:%s:%s',
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );
        $storageClientMock->method('getMulti')->with([$uuidMappingStorageKey])->willReturn([null]);

        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testGetShipmentTypeStorageCollectionReturnsCollectionOfFoundStorageDataFilteredByUuids(): void
    {
        // Arrange
        $uuidMappingStorageKey = sprintf(
            '%s:%s:%s:%s',
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );
        $this->tester->setStorageData($uuidMappingStorageKey, [static::KEY_ID => static::TEST_ID_SHIPMENT_TYPE]);

        $shipmentTypeStorageKey = sprintf(
            '%s:%s:%s',
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::TEST_ID_SHIPMENT_TYPE,
        );
        $this->tester->setStorageData($shipmentTypeStorageKey, [
            ShipmentTypeStorageTransfer::ID_SHIPMENT_TYPE => static::TEST_ID_SHIPMENT_TYPE,
        ]);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())->setShipmentTypeStorageConditions(
            (new ShipmentTypeStorageConditionsTransfer())
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());

        /** @var \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer */
        $shipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getIterator()->current();
        $this->assertSame(static::TEST_ID_SHIPMENT_TYPE, $shipmentTypeStorageTransfer->getIdShipmentType());
    }

    /**
     * @return void
     */
    public function testGetAvailableShipmentTypesThrowsExceptionWithoutProvidedStore(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getAvailableShipmentTypes((new QuoteTransfer()));
    }

    /**
     * @return void
     */
    public function testGetAvailableShipmentTypesThrowsExceptionWithoutProvidedStoreName(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getAvailableShipmentTypes((new QuoteTransfer())->setStore(new StoreTransfer()));
    }

    /**
     * @return void
     */
    public function testGetAvailableShipmentTypesExecutesAvailableShipmentTypeFilterPlugins(): void
    {
        // Assert
        $this->tester->setDependency(ShipmentTypeStorageDependencyProvider::PLUGINS_AVAILABLE_SHIPMENT_TYPE_FILTER, [
            $this->getAvailableShipmentTypeFilterPluginMock(),
        ]);

        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE));

        // Act
        $this->tester->getClient()->getAvailableShipmentTypes($quoteTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface
     */
    protected function createStorageClientMock(): ShipmentTypeStorageToStorageClientInterface
    {
        return $this->getMockBuilder(ShipmentTypeStorageToStorageClientInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface
     */
    protected function getAvailableShipmentTypeFilterPluginMock(): AvailableShipmentTypeFilterPluginInterface
    {
        $availableShipmentTypeFilterPluginMock = $this->getMockBuilder(AvailableShipmentTypeFilterPluginInterface::class)->getMock();
        $availableShipmentTypeFilterPluginMock
            ->expects($this->once())
            ->method('filter')
            ->willReturn((new ShipmentTypeStorageCollectionTransfer()));

        return $availableShipmentTypeFilterPluginMock;
    }
}
