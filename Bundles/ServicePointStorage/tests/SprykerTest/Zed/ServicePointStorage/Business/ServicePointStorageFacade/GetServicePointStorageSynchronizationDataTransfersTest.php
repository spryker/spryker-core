<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Business\ServicePointStorageFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointStorage
 * @group Business
 * @group ServicePointStorageFacade
 * @group GetServicePointStorageSynchronizationDataTransfersTest
 * Add your own group annotations below this line
 */
class GetServicePointStorageSynchronizationDataTransfersTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var int
     */
    protected const FAKE_ID_SERVICE_POINT = -1;

    /**
     * @var string
     */
    protected const TEST_NAME_SERVICE_POINT = 'SP1';

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

        $this->tester->ensureServicePointStorageDatabaseTableIsEmpty();
    }

    /**
     * @dataProvider getEmptyCollectionDataProvider
     *
     * @param array<string, mixed> $servicePointsStorageData
     * @param int $offset
     * @param int $limit
     * @param list<int> $servicePointIds
     * @param list<int> $expectedServicePointIds
     *
     * @return void
     */
    public function testShouldReturnEmptyCollection(
        array $servicePointsStorageData,
        int $offset,
        int $limit,
        array $servicePointIds,
        array $expectedServicePointIds
    ): void {
        // Arrange
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
     * @return void
     */
    public function testShouldReturnCorrectListWhenCorrectLimitIsProvided(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];

        $expectedServicePointIds = [];
        for ($i = 0; $i < 2; $i++) {
            $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations([], $storeNames);
            $this->tester->createServicePointStorageByStoreRelations(
                (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
                $storeNames,
            );
            $expectedServicePointIds[] = $servicePointTransfer->getIdServicePointOrFail();
        }

        // Act
        $servicePointStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getServicePointStorageSynchronizationDataTransfers(0, 1);

        // Assert
        $resultServicePointIds = $this->extractServicePointIdsFromSynchronizationDataTransfers($servicePointStorageSynchronizationDataTransfers);
        $this->assertSame([$expectedServicePointIds[0]], $resultServicePointIds);
    }

    /**
     * @return void
     */
    public function testShouldReturnCorrectListWhenCorrectOffsetIsProvided(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];

        $expectedServicePointIds = [];
        for ($i = 0; $i < 2; $i++) {
            $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations([], $storeNames);
            $this->tester->createServicePointStorageByStoreRelations(
                (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
                $storeNames,
            );
            $expectedServicePointIds[] = $servicePointTransfer->getIdServicePointOrFail();
        }

        // Act
        $servicePointStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getServicePointStorageSynchronizationDataTransfers(1, 1);

        // Assert
        $resultServicePointIds = $this->extractServicePointIdsFromSynchronizationDataTransfers($servicePointStorageSynchronizationDataTransfers);
        $this->assertSame([$expectedServicePointIds[1]], $resultServicePointIds);
    }

    /**
     * @return void
     */
    public function testShouldReturnCorrectList(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];

        $expectedServicePointIds = [];
        for ($i = 0; $i < 2; $i++) {
            $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations([], $storeNames);
            $this->tester->createServicePointStorageByStoreRelations(
                (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
                $storeNames,
            );
            $expectedServicePointIds[] = $servicePointTransfer->getIdServicePointOrFail();
        }

        // Act
        $servicePointStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getServicePointStorageSynchronizationDataTransfers(0, 2, $expectedServicePointIds);

        // Assert
        $resultServicePointIds = $this->extractServicePointIdsFromSynchronizationDataTransfers($servicePointStorageSynchronizationDataTransfers);
        $this->assertSame($expectedServicePointIds, $resultServicePointIds);
    }

    /**
     * @return array<string, array<array<string, mixed>|int|list<int>>>
     */
    protected function getEmptyCollectionDataProvider(): array
    {
        return [
            'When service point storage data is empty' => [
                [], 0, 1, [], [],
            ],
            'When offset is higher then number of service point storage' => [
                [[ServicePointStorageTransfer::NAME => static::TEST_NAME_SERVICE_POINT]], 1, 1, [], [],
            ],
            'When search by incorrect service point ids' => [
                [[ServicePointStorageTransfer::NAME => static::TEST_NAME_SERVICE_POINT]], 0, 1, [static::FAKE_ID_SERVICE_POINT], [],
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
