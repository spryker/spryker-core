<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ServicePointAddressStorageTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointStorage
 * @group Business
 * @group Facade
 * @group ServicePointStorageFacadeTest
 * Add your own group annotations below this line
 */
class ServicePointStorageFacadeTest extends Unit
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointAddressTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT = 'spy_service_point_address.fk_service_point';

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_POINT_STORE_COL_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var int
     */
    protected const FAKE_ID_SERVICE_POINT = -1;

    /**
     * @var int
     */
    protected const TEST_ID_SERVICE_POINT = 888;

    /**
     * @var int
     */
    protected const TEST_ID_SERVICE_POINT_2 = 889;

    /**
     * @var string
     */
    protected const TEST_UUID = 'TEST_UUID';

    /**
     * @var string
     */
    protected const TEST_UUID_2 = 'TEST_UUID_2';

    /**
     * @var string
     */
    protected const KEY_UUID = 'uuid';

    /**
     * @var string
     */
    protected const KEY_ADDRESS = 'address';

    /**
     * @var string
     */
    protected const KEY_ISO_2_CODE = 'iso2_code';

    /**
     * @var string
     */
    protected const KEY_COUNTRY = 'country';

    /**
     * @var string
     */
    protected const KEY_REGION = 'region';

    /**
     * @var string
     */
    protected const KEY_ID_SERVICE_POINT = 'id_service_point';

    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester
     */
    protected ServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointEventsShouldNotWriteWhenServicePointIdIsNotProvided(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([new EventEntityTransfer()]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointEventsShouldNotWriteWhenNotExistingServicePointIdIsProvided(): void
    {
        // Arrange
        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())
                ->setIsActive(true)
                ->setIdServicePoint(static::FAKE_ID_SERVICE_POINT),
            [static::STORE_NAME_DE],
        );
        $eventEntityTransfer = (new EventEntityTransfer())->setId(static::FAKE_ID_SERVICE_POINT);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint(static::FAKE_ID_SERVICE_POINT);
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointEventsShouldNotWriteNotActiveServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => false],
            [static::STORE_NAME_DE],
        );
        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointEventsShouldWriteActiveServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );
        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntity = $servicePointStorageEntities[0];
        $this->assertSame(static::STORE_NAME_DE, $servicePointEntity->getStore());
        $this->assertSame($servicePointTransfer->getUuid(), $servicePointEntity->getData()[static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointEventsShouldDeleteServicePointWhenServicePointDeactivated(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => false],
            $storeNames,
        );

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())
                ->fromArray($servicePointTransfer->toArray(), true)
                ->setIsActive(true),
            $storeNames,
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointEventsShouldUpdateServicePoint(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::UUID => static::TEST_UUID,
        ], $storeNames);

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())
                ->fromArray($servicePointTransfer->toArray(), true)
                ->setUuid(static::TEST_UUID_2),
            $storeNames,
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntity = $servicePointStorageEntities[0];
        $this->assertSame(static::STORE_NAME_DE, $servicePointEntity->getStore());
        $this->assertSame($servicePointTransfer->getUuid(), $servicePointEntity->getData()[static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointAddressEventsShouldAddAddress(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransfer($servicePointTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointAddressEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntityData = $servicePointStorageEntities[0]->getData();
        $this->assertSame($servicePointAddressTransfer->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_UUID]);
        $this->assertSame($servicePointAddressTransfer->getCountry()->getIso2Code(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_COUNTRY][static::KEY_ISO_2_CODE]);
        $this->assertSame($servicePointAddressTransfer->getRegion()->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_REGION][static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointAddressEventsShouldUpdateAddress(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            $storeNames,
        );

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransfer($servicePointTransfer);

        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->fromArray($servicePointTransfer->toArray(), true)
            ->setAddress(
                (new ServicePointAddressStorageTransfer())
                    ->fromArray($servicePointAddressTransfer->toArray(), true)
                    ->setUuid(static::TEST_UUID)
                    ->setCountry()
                    ->setRegion(),
            );

        $this->tester->createServicePointStorageByStoreRelations($servicePointStorageTransfer, $storeNames);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointAddressEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntityData = $servicePointStorageEntities[0]->getData();
        $this->assertSame($servicePointAddressTransfer->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_UUID]);
        $this->assertSame($servicePointAddressTransfer->getCountry()->getIso2Code(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_COUNTRY][static::KEY_ISO_2_CODE]);
        $this->assertSame($servicePointAddressTransfer->getRegion()->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_REGION][static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointStoreEventsShouldAddStoreRelation(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE, static::STORE_NAME_AT],
        );

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
            [static::STORE_NAME_AT],
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_STORE_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointStoreEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(2, $servicePointStorageEntities);

        $this->assertSame(static::STORE_NAME_AT, $servicePointStorageEntities[0]->getStore());
        $this->assertSame(static::STORE_NAME_DE, $servicePointStorageEntities[1]->getStore());
    }

    /**
     * @return void
     */
    public function testWriteServicePointStorageCollectionByServicePointStoreEventsShouldRemoveStoreRelation(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_AT],
        );

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
            [static::STORE_NAME_DE, static::STORE_NAME_AT],
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_STORE_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointStoreEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $this->assertSame(static::STORE_NAME_AT, $servicePointStorageEntities[0]->getStore());
    }

    /**
     * @dataProvider getServicePointStorageSynchronizationDataTransfersDataProvider
     *
     * @param array<string, mixed> $servicePointsStorageData
     * @param int $offset
     * @param int $limit
     * @param list<int> $servicePointIds
     * @param list<int> $expectedServicePointIds
     *
     * @return void
     */
    public function testGetServicePointStorageSynchronizationDataTransfers(
        array $servicePointsStorageData,
        int $offset,
        int $limit,
        array $servicePointIds,
        array $expectedServicePointIds
    ): void {
        // Arrange
        $this->tester->ensureServicePointStorageDatabaseTableIsEmpty();
        $storeNames = [static::STORE_NAME_DE];
        foreach ($servicePointsStorageData as $servicePointStorageData) {
            $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations($servicePointStorageData, $storeNames);
            $this->tester->createServicePointStorageByStoreRelations(
                (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
                $storeNames,
            );
        }

        // Act
        $servicePointStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getServicePointStorageSynchronizationDataTransfers($offset, $limit, $servicePointIds);

        // Assert
        $resultServicePointIds = $this->extractServicePointIdsFromSynchronizationDataTransfers($servicePointStorageSynchronizationDataTransfers);
        $this->assertSame($expectedServicePointIds, $resultServicePointIds);
    }

    /**
     * @return array<string, array<array<string, mixed>|int|list<int>>>
     */
    protected function getServicePointStorageSynchronizationDataTransfersDataProvider(): array
    {
        return [
            'Should return empty collection when service point storage data is empty' => [
                [], 0, 1, [], [],
            ],
            'Should return empty collection when offset is higher then number of service point storage' => [
                [[ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT]], 1, 1, [], [],
            ],
            'Should return empty collection when search by incorrect service point IDs' => [
                [[ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT]], 0, 1, [static::FAKE_ID_SERVICE_POINT], [],
            ],
            'Should return correct number of items when correct limit is provided' => [
                [[ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT]], 0, 1, [], [static::TEST_ID_SERVICE_POINT],
            ],
            'Should return correct items when correct offset is provided' => [
                [
                    [ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT],
                    [ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT_2],
                ], 1, 1, [], [static::TEST_ID_SERVICE_POINT_2],
            ],
            'Should return correct items search by correct service point IDs' => [
                [
                    [ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT],
                    [ServicePointStorageTransfer::ID_SERVICE_POINT => static::TEST_ID_SERVICE_POINT_2],
                ], 0, 1, [static::TEST_ID_SERVICE_POINT_2], [static::TEST_ID_SERVICE_POINT_2],
            ],
        ];
    }

    /**
     * @param list<\Generated\Shared\Transfer\SynchronizationDataTransfer> $synchronizationDataTransfers
     *
     * @return list<int>
     */
    protected function extractServicePointIdsFromSynchronizationDataTransfers(array $synchronizationDataTransfers): array
    {
        $servicePointIds = [];
        foreach ($synchronizationDataTransfers as $synchronizationDataTransfer) {
            $servicePointIds[] = (int)$synchronizationDataTransfer->getData()[static::KEY_ID_SERVICE_POINT];
        }

        return $servicePointIds;
    }
}
