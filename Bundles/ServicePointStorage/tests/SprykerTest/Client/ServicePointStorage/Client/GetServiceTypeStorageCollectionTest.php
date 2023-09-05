<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ServicePointStorage\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServiceTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface;
use Spryker\Client\ServicePointStorage\ServicePointStorageDependencyProvider;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;
use SprykerTest\Client\ServicePointStorage\ServicePointStorageClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ServicePointStorage
 * @group Client
 * @group GetServiceTypeStorageCollectionTest
 * Add your own group annotations below this line
 */
class GetServiceTypeStorageCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_ID_SERVICE_TYPE_1 = 1;

    /**
     * @var int
     */
    protected const TEST_ID_SERVICE_TYPE_2 = 2;

    /**
     * @var string
     */
    protected const TEST_UUID = 'test_uuid';

    /**
     * @uses \Spryker\Client\ServicePointStorage\Generator\StorageKeyGenerator::MAPPING_TYPE_UUID
     *
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @uses \Spryker\Client\ServicePointStorage\Reader\ServiceTypeStorageReader::KEY_ID
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
    public function testShouldThrowAnExceptionWhenConditionsTransferIsNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->getServiceTypeStorageCollection(new ServiceTypeStorageCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenConditionsAreNotProvidedInConditionsTransfer(): void
    {
        // Arrange
        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            new ServiceTypeStorageConditionsTransfer(),
        );

        // Act
        $serviceTypeStorageCollectionTransfer = $this->tester->getClient()
            ->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);

        // Assert
        $this->assertCount(0, $serviceTypeStorageCollectionTransfer->getServiceTypeStorages());
    }

    /**
     * @return void
     */
    public function testShouldFilterByServiceTypeIdsWhenBothServiceTypeIdAndUuidAreProvidedInConditions(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())
                ->addIdServiceType(static::TEST_ID_SERVICE_TYPE_1)
                ->addUuid(static::TEST_UUID),
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$this->getServiceTypeIdKey()]);

        // Act
        $this->tester->getClient()->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldFilterByServiceTypeIdsWhenServiceTypeIdIsProvidedInConditions(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())->addIdServiceType(static::TEST_ID_SERVICE_TYPE_1),
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$this->getServiceTypeIdKey()]);

        // Act
        $this->tester->getClient()->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldFilterByUuidWhenUuidIsProvidedInConditions(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())->addUuid(static::TEST_UUID),
        );

        // Assert
        $storageClientMock->expects($this->once())->method('getMulti')->with([$this->getUuidKey()]);

        // Act
        $this->tester->getClient()->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWhenFilterByServiceTypeIds(): void
    {
        // Arrange
        $this->tester->setToStorage($this->getServiceTypeIdKey(), [
            ServiceTypeStorageTransfer::ID_SERVICE_TYPE => static::TEST_ID_SERVICE_TYPE_1,
        ]);
        $this->tester->setToStorage($this->getServiceTypeIdKey() . 'test', [
            ServiceTypeStorageTransfer::ID_SERVICE_TYPE => static::TEST_ID_SERVICE_TYPE_2 . 'test',
        ]);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())->addIdServiceType(static::TEST_ID_SERVICE_TYPE_1),
        );

        // Act
        $serviceTypeStorageCollectionTransfer = $this->tester->getClient()->getServiceTypeStorageCollection(
            $serviceTypeStorageCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $serviceTypeStorageCollectionTransfer->getServiceTypeStorages());
        $serviceTypeStorageTransfer = $serviceTypeStorageCollectionTransfer->getServiceTypeStorages()->getIterator()->current();
        $this->assertSame(static::TEST_ID_SERVICE_TYPE_1, $serviceTypeStorageTransfer->getIdServiceType());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenFilterByServiceTypeIdsWhenStorageDataIsNotFound(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $storageClientMock->method('getMulti')->willReturn([null, '']);
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())->addIdServiceType(static::TEST_ID_SERVICE_TYPE_1),
        );

        // Act
        $serviceTypeStorageCollectionTransfer = $this->tester->getClient()->getServiceTypeStorageCollection(
            $serviceTypeStorageCriteriaTransfer,
        );

        // Assert
        $this->assertCount(0, $serviceTypeStorageCollectionTransfer->getServiceTypeStorages());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWhenFilterByUuids(): void
    {
        // Arrange
        $this->tester->setToStorage($this->getUuidKey(), [static::KEY_ID => static::TEST_ID_SERVICE_TYPE_1]);
        $this->tester->setToStorage($this->getServiceTypeIdKey(), [
            ServiceTypeStorageTransfer::ID_SERVICE_TYPE => static::TEST_ID_SERVICE_TYPE_1,
        ]);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())->addUuid(static::TEST_UUID),
        );

        // Act
        $serviceTypeStorageCollectionTransfer = $this->tester->getClient()->getServiceTypeStorageCollection(
            $serviceTypeStorageCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $serviceTypeStorageCollectionTransfer->getServiceTypeStorages());
        $serviceTypeStorageTransfer = $serviceTypeStorageCollectionTransfer->getServiceTypeStorages()->getIterator()->current();
        $this->assertSame(static::TEST_ID_SERVICE_TYPE_1, $serviceTypeStorageTransfer->getIdServiceType());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenFilterByUuidsWhenDataIsNotFound(): void
    {
        // Arrange
        $storageClientMock = $this->createStorageClientMock();
        $storageClientMock->method('getMulti')->with([$this->getUuidKey()])->willReturn([null, '']);
        $this->tester->setDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $serviceTypeStorageCriteriaTransfer = (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions(
            (new ServiceTypeStorageConditionsTransfer())->addUuid(static::TEST_UUID),
        );

        // Act
        $serviceTypeStorageCollectionTransfer = $this->tester->getClient()->getServiceTypeStorageCollection(
            $serviceTypeStorageCriteriaTransfer,
        );

        // Assert
        $this->assertCount(0, $serviceTypeStorageCollectionTransfer->getServiceTypeStorages());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface
     */
    protected function createStorageClientMock(): ServicePointStorageToStorageClientInterface
    {
        return $this->getMockBuilder(ServicePointStorageToStorageClientInterface::class)->getMock();
    }

    /**
     * @return string
     */
    protected function getServiceTypeIdKey(): string
    {
        return sprintf(
            '%s:%s',
            ServicePointStorageConfig::SERVICE_TYPE_RESOURCE_NAME,
            static::TEST_ID_SERVICE_TYPE_1,
        );
    }

    /**
     * @return string
     */
    protected function getUuidKey(): string
    {
        return sprintf(
            '%s:%s:%s',
            ServicePointStorageConfig::SERVICE_TYPE_RESOURCE_NAME,
            static::MAPPING_TYPE_UUID,
            static::TEST_UUID,
        );
    }
}
