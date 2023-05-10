<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ServicePointStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface;
use Spryker\Client\ServicePointStorage\ServicePointStorageDependencyProvider;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ServicePointStorage
 * @group ServicePointStorageClientTest
 * Add your own group annotations below this line
 */
class ServicePointStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'de';

    /**
     * @var int
     */
    protected const TEST_ID_SERVICE_POINT = 888;

    /**
     * @var string
     */
    protected const TEST_UUID = 'test_uuid';

    /**
     * @uses \Spryker\Client\ServicePointStorage\Generator\ServicePointStorageKeyGenerator::MAPPING_TYPE_UUID
     *
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @uses \Spryker\Client\ServicePointStorage\Reader\ServicePointStorageReader::KEY_ID
     *
     * @var string
     */
    protected const KEY_ID = 'id';

    /**
     * @var \SprykerTest\Client\ServicePointStorage\ServicePointStorageClientTester
     */
    protected ServicePointStorageClientTester $tester;

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldThrowAnExceptionWhenNoConditionsProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getServicePointStorageCollection(new ServicePointStorageCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldThrowAnExceptionWhenConditionWithServicePointIdsDoesNotHaveStore(): void
    {
        // Arrange
        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())->addIdServicePoint(static::TEST_ID_SERVICE_POINT),
        );

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldThrowAnExceptionWhenConditionWithUuidsDoesNotHaveStore(): void
    {
        // Arrange
        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())->addUuid(static::TEST_UUID),
        );

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldReturnEmptyCollectionWhenConditionsAreNotProvided(): void
    {
        // Arrange
        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            new ServicePointStorageConditionsTransfer(),
        );

        // Act
        $servicePointStorageCollectionTransfer = $this->tester->getClient()
            ->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);

        // Assert
        $this->assertCount(0, $servicePointStorageCollectionTransfer->getServicePointStorages());
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldFilterByServicePointIdsWhenConditionIsProvided(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->addIdServicePoint(static::TEST_ID_SERVICE_POINT)
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        $expectedKey = sprintf(
            '%s:%s:%s',
            ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::TEST_ID_SERVICE_POINT,
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$expectedKey]);

        // Act
        $this->tester->getClient()->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldFilterByUuidsMappingWhenConditionIsProvided(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        $expectedKey = sprintf(
            '%s:%s:%s:%s',
            ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$expectedKey]);

        // Act
        $this->tester->getClient()->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldReturnEmptyCollectionWhenNoDataByServicePointIdsIsFound(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $storageClientMock->method('getMulti')->willReturn([null, '']);
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->addIdServicePoint(static::TEST_ID_SERVICE_POINT)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $servicePointStorageCollectionTransfer = $this->tester->getClient()
            ->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);

        // Assert
        $this->assertCount(0, $servicePointStorageCollectionTransfer->getServicePointStorages());
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldReturnCollectionWhenFilerByServicePointIds(): void
    {
        // Arrange
        $servicePointIdKey = sprintf(
            '%s:%s:%s',
            ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::TEST_ID_SERVICE_POINT,
        );

        $this->tester->setToStorage($servicePointIdKey, [
            ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT,
        ]);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->addIdServicePoint(static::TEST_ID_SERVICE_POINT)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $servicePointStorageCollectionTransfer = $this->tester->getClient()
            ->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointStorageCollectionTransfer->getServicePointStorages());

        /** @var \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer */
        $servicePointStorageTransfer = $servicePointStorageCollectionTransfer->getServicePointStorages()->getIterator()->current();
        $this->assertSame(static::TEST_ID_SERVICE_POINT, $servicePointStorageTransfer->getIdServicePoint());
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldReturnEmptyCollectionWhenNoDataByUuidsIsFound(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();

        $uuidKey = sprintf(
            '%s:%s:%s:%s',
            ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );
        $storageClientMock->method('getMulti')->with([$uuidKey])->willReturn([null, '']);

        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $servicePointStorageCollectionTransfer = $this->tester->getClient()
            ->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);

        // Assert
        $this->assertCount(0, $servicePointStorageCollectionTransfer->getServicePointStorages());
    }

    /**
     * @return void
     */
    public function testGetServicePointStorageCollectionShouldReturnCollectionWhenFilerByUuids(): void
    {
        // Arrange
        $uuidKey = sprintf(
            '%s:%s:%s:%s',
            ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );
        $this->tester->setToStorage($uuidKey, [static::KEY_ID => static::TEST_ID_SERVICE_POINT]);

        $servicePointIdKey = sprintf(
            '%s:%s:%s',
            ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::TEST_ID_SERVICE_POINT,
        );
        $this->tester->setToStorage($servicePointIdKey, [
            ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT,
        ]);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->addUuid(static::TEST_UUID)
                ->setStoreName(static::STORE_NAME_DE),
        );

        // Act
        $servicePointStorageCollectionTransfer = $this->tester->getClient()
            ->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointStorageCollectionTransfer->getServicePointStorages());

        /** @var \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer */
        $servicePointStorageTransfer = $servicePointStorageCollectionTransfer->getServicePointStorages()->getIterator()->current();
        $this->assertSame(static::TEST_ID_SERVICE_POINT, $servicePointStorageTransfer->getIdServicePoint());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface
     */
    protected function createStorageClientMock(): ServicePointStorageToStorageClientInterface
    {
        return $this->getMockBuilder(ServicePointStorageToStorageClientInterface::class)->getMock();
    }
}
