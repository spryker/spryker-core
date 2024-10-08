<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointSearchConditionsTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\ServicePoint\ServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePoint
 * @group Business
 * @group Facade
 * @group GetServicePointCollectionTest
 * Add your own group annotations below this line
 */
class GetServicePointCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByUuids(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $servicePointTransfer = $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($servicePointTransfer->getUuidOrFail());
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $retrievedServicePointTransfer */
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();

        $this->assertSame(
            $servicePointTransfer->getUuidOrFail(),
            $retrievedServicePointTransfer->getUuidOrFail(),
        );
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByUuidsInversed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $servicePointTransfer = $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $servicePointTransferToExclude = $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($servicePointTransferToExclude->getUuidOrFail())
            ->setIsUuidsConditionInversed(true);
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $retrievedServicePointTransfer */
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();

        $this->assertSame(
            $servicePointTransfer->getUuidOrFail(),
            $retrievedServicePointTransfer->getUuidOrFail(),
        );
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByKeys(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $servicePointTransfer = $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
        );

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addKey($servicePointTransfer->getKeyOrFail());
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $retrievedServicePointTransfer */
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();

        $this->assertSame(
            $servicePointTransfer->getKeyOrFail(),
            $retrievedServicePointTransfer->getKeyOrFail(),
        );
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByServicePointIds(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $servicePointTransfer = $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
        );

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addIdServicePoint($servicePointTransfer->getIdServicePoint());
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());
        /**
         * @var \Generated\Shared\Transfer\ServicePointTransfer $retrievedServicePointTransfer
         */
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();
        $this->assertSame(
            $servicePointTransfer->getKeyOrFail(),
            $retrievedServicePointTransfer->getKeyOrFail(),
        );
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByIsActiveParameter(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $servicePointTransfer = $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => false],
        );

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->setIsActive(true);
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());
        $this->assertTrue($servicePointTransfer->getIsActive());
        $this->assertSame($servicePointTransfer->getKeyOrFail(), $retrievedServicePointTransfer->getKeyOrFail());
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByStoreNames(): void
    {
        // Arrange
        $storeTransfer1 = $this->tester->haveStore();
        $storeTransfer2 = $this->tester->haveStore([StoreTransfer::NAME => 'testStore']);
        $servicePointTransfer = $this->createServicePointWithStoreRelation([
            $storeTransfer1->getIdStoreOrFail(),
            $storeTransfer2->getIdStoreOrFail(),
        ]);
        $this->createServicePointWithStoreRelation([$storeTransfer2->getIdStoreOrFail()]);

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addStoreName($storeTransfer1->getNameOrFail());
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $retrievedServicePointTransfer */
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();

        $this->assertSame(
            $servicePointTransfer->getIdServicePointOrFail(),
            $retrievedServicePointTransfer->getIdServicePointOrFail(),
        );
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsWithStoreRelations(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $servicePointTransfer = $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->setWithStoreRelations(true);
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $retrievedServicePointTransfer */
        $retrievedServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();

        $this->assertSame(
            $servicePointTransfer->getUuidOrFail(),
            $retrievedServicePointTransfer->getUuidOrFail(),
        );
        $this->assertCount(1, $retrievedServicePointTransfer->getStoreRelationOrFail()->getStores());
        $this->assertSame(
            $storeTransfer->getIdStore(),
            $retrievedServicePointTransfer->getStoreRelationOrFail()->getStores()->getIterator()->current()->getIdStoreOrFail(),
        );
        $this->assertNull($servicePointCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByLimitAndOffset(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(1)
            ->setLimit(2);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setServicePointConditions((new ServicePointConditionsTransfer()));

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(2, $servicePointCollectionTransfer->getServicePoints());
        $this->assertNotNull($servicePointCollectionTransfer->getPagination());
        $this->assertSame(4, $servicePointCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsByPagination(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);
        $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setServicePointConditions((new ServicePointConditionsTransfer()));

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(2, $servicePointCollectionTransfer->getServicePoints());
        $this->assertNotNull($servicePointCollectionTransfer->getPagination());

        $paginationTransfer = $servicePointCollectionTransfer->getPaginationOrFail();

        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(7, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(3, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(4, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(4, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(4, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(3, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsSortedByFieldDesc(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::KEY => 'abc'],
        );
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::KEY => 'cab'],
        );
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::KEY => 'bac'],
        );

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointTransfer::KEY)
            ->setIsAscending(false);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setServicePointConditions((new ServicePointConditionsTransfer()));

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        $servicePointTransfers = $servicePointCollectionTransfer->getServicePoints();

        // Assert
        $this->assertCount(3, $servicePointTransfers);
        $this->assertSame('cab', $servicePointTransfers->getIterator()->offsetGet(0)->getKeyOrFail());
        $this->assertSame('bac', $servicePointTransfers->getIterator()->offsetGet(1)->getKeyOrFail());
        $this->assertSame('abc', $servicePointTransfers->getIterator()->offsetGet(2)->getKeyOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsSortedByFieldAsc(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::KEY => 'bac'],
        );
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::KEY => 'abc'],
        );
        $this->createServicePointWithStoreRelation(
            [$storeTransfer->getIdStoreOrFail()],
            [ServicePointTransfer::KEY => 'cab'],
        );

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointTransfer::KEY)
            ->setIsAscending(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setServicePointConditions((new ServicePointConditionsTransfer()));

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        $servicePointTransfers = $servicePointCollectionTransfer->getServicePoints();

        // Assert
        $this->assertCount(3, $servicePointTransfers);
        $this->assertSame('abc', $servicePointTransfers->getIterator()->offsetGet(0)->getKeyOrFail());
        $this->assertSame('bac', $servicePointTransfers->getIterator()->offsetGet(1)->getKeyOrFail());
        $this->assertSame('cab', $servicePointTransfers->getIterator()->offsetGet(2)->getKeyOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsWithoutAddressRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $servicePointTransfer = $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($servicePointTransfer->getUuid())
            ->setWithAddressRelation(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionTransfer->getServicePoints());
        $this->assertNull($servicePointCollectionTransfer->getServicePoints()->getIterator()->current()->getAddress());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsWithAddressRelation(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($servicePointAddressTransfer->getServicePoint()->getUuid())
            ->setWithAddressRelation(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ServicePointAddressTransfer $retrievedServicePointAddressTransfer */
        $retrievedServicePointAddressTransfer = $servicePointCollectionTransfer->getServicePoints()->getIterator()->current()->getAddress();

        $this->assertSame($servicePointAddressTransfer->getIdServicePointAddress(), $retrievedServicePointAddressTransfer->getIdServicePointAddress());
        $this->assertSame($servicePointAddressTransfer->getUuid(), $retrievedServicePointAddressTransfer->getUuid());
        $this->assertSame($servicePointAddressTransfer->getCity(), $retrievedServicePointAddressTransfer->getCity());
        $this->assertSame($servicePointAddressTransfer->getAddress1(), $retrievedServicePointAddressTransfer->getAddress1());
        $this->assertSame($servicePointAddressTransfer->getAddress2(), $retrievedServicePointAddressTransfer->getAddress2());
        $this->assertSame($servicePointAddressTransfer->getAddress3(), $retrievedServicePointAddressTransfer->getAddress3());
        $this->assertSame($servicePointAddressTransfer->getServicePoint()->getUuid(), $retrievedServicePointAddressTransfer->getServicePoint()->getUuid());
    }

    /**
     * @return void
     */
    public function testReturnsOneServicePointWithAddressRelationAndOneServicePointWithoutAddressRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $firstServicePointTransfer = $this->createServicePointWithStoreRelation([$storeTransfer->getIdStoreOrFail()]);

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $secondServicePointTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray())->getServicePoint();

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($firstServicePointTransfer->getUuid())
            ->addUuid($secondServicePointTransfer->getUuid())
            ->setWithAddressRelation(true);

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointTransfer::ID_SERVICE_POINT)
            ->setIsAscending(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer)
            ->addSort($sortTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertNull($servicePointCollectionTransfer->getServicePoints()->offsetGet(0)->getAddress());
        $this->assertNotNull($servicePointCollectionTransfer->getServicePoints()->offsetGet(1)->getAddress());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointsWithServiceRelations(): void
    {
        // Arrange
        $idStore = $this->tester->haveStore()->getIdStore();

        $servicePointTransfer = $this->createServicePointWithStoreRelation([$idStore]);

        $serviceData = [
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ];
        $serviceTransfer = $this->tester->haveService($serviceData);
        $secondServiceTransfer = $this->tester->haveService($serviceData);

        $secondServicePointTransfer = $this->createServicePointWithStoreRelation([$idStore]);

        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->setServicePointIds([$servicePointTransfer->getIdServicePoint(), $secondServicePointTransfer->getIdServicePoint()])
            ->setWithServiceRelations(true);

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointTransfer::ID_SERVICE_POINT)
            ->setIsAscending(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer)
            ->addSort($sortTransfer);

        // Act
        $servicePointCollectionTransfer = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer);

        // Assert
        $this->assertCount(2, $servicePointCollectionTransfer->getServicePoints());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $resultServicePointTransfer */
        $resultServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->offsetGet(0);
        $this->assertSame($servicePointTransfer->getIdServicePoint(), $resultServicePointTransfer->getIdServicePoint());
        $this->assertCount(2, $resultServicePointTransfer->getServices());

        $expectedServices = [$serviceTransfer, $secondServiceTransfer];
        $actualServices = $resultServicePointTransfer->getServices()->getArrayCopy();

        usort($expectedServices, fn ($a, $b) => $a->getIdService() <=> $b->getIdService());
        usort($actualServices, fn ($a, $b) => $a->getIdService() <=> $b->getIdService());

        foreach ($expectedServices as $index => $expectedServiceTransfer) {
            $actualServiceTransfer = $actualServices[$index];

            $this->assertSame($expectedServiceTransfer->getIdService(), $actualServiceTransfer->getIdService());
            $this->assertSame($expectedServiceTransfer->getUuid(), $actualServiceTransfer->getUuid());
            $this->assertSame($expectedServiceTransfer->getKey(), $actualServiceTransfer->getKey());
            $this->assertSame($expectedServiceTransfer->getIsActive(), $actualServiceTransfer->getIsActive());
            $this->assertSame($expectedServiceTransfer->getServiceType()->toArray(), $actualServiceTransfer->getServiceType()->toArray());
        }

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $resultSecondServicePointTransfer */
        $resultSecondServicePointTransfer = $servicePointCollectionTransfer->getServicePoints()->offsetGet(1);
        $this->assertSame($secondServicePointTransfer->getIdServicePoint(), $resultSecondServicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $resultSecondServicePointTransfer->getServices());
    }

    /**
     * @return void
     */
    public function testSearchesServicePointsByKey(): void
    {
        // Arrange
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'abc']);
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'bcd']);
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'cde']);

        $servicePointSearchConditionsTransfer = (new ServicePointSearchConditionsTransfer())->setKey('bc');
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointSearchConditions($servicePointSearchConditionsTransfer);

        // Act
        $servicePointTransfers = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        // Assert
        $this->assertCount(2, $servicePointTransfers);
        $expectedServicePointKeys = ['abc', 'bcd'];
        foreach ($servicePointTransfers as $servicePointTransfer) {
            $this->assertContains($servicePointTransfer->getKey(), $expectedServicePointKeys);
        }
    }

    /**
     * @return void
     */
    public function testSearchesServicePointsByName(): void
    {
        // Arrange
        $this->tester->haveServicePoint([ServicePointTransfer::NAME => 'abc']);
        $this->tester->haveServicePoint([ServicePointTransfer::NAME => 'bcd']);
        $this->tester->haveServicePoint([ServicePointTransfer::NAME => 'cde']);

        $servicePointSearchConditionsTransfer = (new ServicePointSearchConditionsTransfer())->setName('bc');
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointSearchConditions($servicePointSearchConditionsTransfer);

        // Act
        $servicePointTransfers = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        // Assert
        $this->assertCount(2, $servicePointTransfers);
        $expectedServicePointNames = ['abc', 'bcd'];
        foreach ($servicePointTransfers as $servicePointTransfer) {
            $this->assertContains($servicePointTransfer->getName(), $expectedServicePointNames);
        }
    }

    /**
     * @return void
     */
    public function testSearchesServicePointsByKeyAndName(): void
    {
        // Arrange
        $this->tester->haveServicePoint([
            ServicePointTransfer::KEY => 'abc',
            ServicePointTransfer::NAME => 'cde',
        ]);
        $this->tester->haveServicePoint([
            ServicePointTransfer::KEY => 'def',
            ServicePointTransfer::NAME => 'def',
        ]);
        $this->tester->haveServicePoint([
            ServicePointTransfer::KEY => 'cde',
            ServicePointTransfer::NAME => 'abc',
        ]);

        $servicePointSearchConditionsTransfer = (new ServicePointSearchConditionsTransfer())
            ->setName('bc')
            ->setKey('bc');
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointSearchConditions($servicePointSearchConditionsTransfer);

        // Act
        $servicePointTransfers = $this->tester->getFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        // Assert
        $this->assertCount(2, $servicePointTransfers);
        $expectedServicePointKeys = ['abc', 'cde'];
        foreach ($servicePointTransfers as $servicePointTransfer) {
            $this->assertContains($servicePointTransfer->getKey(), $expectedServicePointKeys);
        }
    }

    /**
     * @param list<int> $storeIds
     * @param array<string, mixed> $servicePointData
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected function createServicePointWithStoreRelation(array $storeIds, array $servicePointData = []): ServicePointTransfer
    {
        $servicePointBuilder = (new ServicePointBuilder($servicePointData));

        $storeData = [];
        foreach ($storeIds as $idStore) {
            $storeData[] = [
                StoreTransfer::ID_STORE => $idStore,
            ];
        }

        $servicePointBuilder->withStoreRelation([
            StoreRelationTransfer::STORES => $storeData,
        ]);

        return $this->tester->haveServicePoint(
            $servicePointBuilder->build()->toArray(),
        );
    }
}
