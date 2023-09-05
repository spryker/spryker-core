<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Business\ServicePointStorageFacade;

use Codeception\Test\Unit;
use SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointStorage
 * @group Business
 * @group ServicePointStorageFacade
 * @group GetServiceTypeStorageSynchronizationDataTransfersTest
 * Add your own group annotations below this line
 */
class GetServiceTypeStorageSynchronizationDataTransfersTest extends Unit
{
    /**
     * @var string
     */
    protected const KEY_ID_SERVICE_TYPE = 'id_service_type';

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

        $this->tester->ensureServiceTypeStorageDatabaseTableIsEmpty();
    }

    /**
     * @dataProvider shouldReturnCollectionDataProvider
     *
     * @param int $serviceTypeStorageNumberInDb
     * @param int $offset
     * @param int $limit
     * @param int|null $idServiceTypeIndex
     *
     * @return void
     */
    public function testShouldReturnCollection(
        int $serviceTypeStorageNumberInDb,
        int $offset,
        int $limit,
        ?int $idServiceTypeIndex = null
    ): void {
        // Arrange
        $serviceTypeIdsFromDb = [];
        for ($i = 1; $i <= $serviceTypeStorageNumberInDb; $i++) {
            $serviceTypeTransfer = $this->tester->haveServiceType();
            $serviceTypeIdsFromDb[] = $serviceTypeTransfer->getIdServiceTypeOrFail();

            $this->tester->saveServiceTypeStorage($serviceTypeTransfer);
        }

        // Act
        $serviceTypeStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getServiceTypeStorageSynchronizationDataTransfers(
                $offset,
                $limit,
                $idServiceTypeIndex ? [$serviceTypeIdsFromDb[$idServiceTypeIndex]] : [],
            );
        $resultServiceTypeIds = $this->extractServiceTypeIdsFromSynchronizationDataTransfers($serviceTypeStorageSynchronizationDataTransfers);
        $expectedServiceTypeIds = $idServiceTypeIndex
            ? [$serviceTypeIdsFromDb[$idServiceTypeIndex]]
            : array_slice($serviceTypeIdsFromDb, $offset, $limit);

        // Assert
        $this->assertSame($expectedServiceTypeIds, $resultServiceTypeIds);
    }

    /**
     * @dataProvider shouldReturnEmptyCollectionDataProvider
     *
     * @param int $serviceTypeStorageNumberInDb
     * @param int $offset
     * @param int $limit
     * @param list<int> $serviceTypeIds
     *
     * @return void
     */
    public function testShouldReturnEmptyCollection(
        int $serviceTypeStorageNumberInDb,
        int $offset,
        int $limit,
        array $serviceTypeIds
    ): void {
        // Arrange
        for ($i = 1; $i <= $serviceTypeStorageNumberInDb; $i++) {
            $this->tester->saveServiceTypeStorage($this->tester->haveServiceType());
        }

        // Act
        $serviceTypeStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getServiceTypeStorageSynchronizationDataTransfers($offset, $limit, $serviceTypeIds);

        // Assert
        $this->assertCount(0, $serviceTypeStorageSynchronizationDataTransfers);
    }

    /**
     * @return array<string, list<int|null>>
     */
    protected function shouldReturnCollectionDataProvider(): array
    {
        return [
            'Should return collection when correct limit and 0 offset are provided' => [
                2, 0, 1, null,
            ],
            'Should return collection when both correct limit and offset are provided' => [
                2, 1, 1, null,
            ],
            'Should return collection by ID' => [
                2, 0, 2, 1,
            ],
        ];
    }

    /**
     * @return array<string, list<int|list<int>>>
     */
    protected function shouldReturnEmptyCollectionDataProvider(): array
    {
        return [
            'Should return empty collection when service type storage is empty' => [
                0, 0, 1, [],
            ],
            'Should return empty collection when offset is higher then number of service type storage entities' => [
                1, 2, 1, [],
            ],
            'Should return empty collection when offset when offset equals the number of service type storage entities' => [
                1, 1, 1, [],
            ],
            'Should return empty collection when not existing IDs are provided' => [
                1, 0, 1, [-1],
            ],
        ];
    }

    /**
     * @param list<\Generated\Shared\Transfer\SynchronizationDataTransfer> $synchronizationDataTransfers
     *
     * @return list<int>
     */
    protected function extractServiceTypeIdsFromSynchronizationDataTransfers(array $synchronizationDataTransfers): array
    {
        $servicePointIds = [];
        foreach ($synchronizationDataTransfers as $synchronizationDataTransfer) {
            $servicePointIds[] = (int)$synchronizationDataTransfer->getData()[static::KEY_ID_SERVICE_TYPE];
        }

        return $servicePointIds;
    }
}
