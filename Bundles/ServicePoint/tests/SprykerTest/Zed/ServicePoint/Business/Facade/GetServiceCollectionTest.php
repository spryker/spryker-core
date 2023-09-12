<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\ServicePoint\ServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePoint
 * @group Business
 * @group Facade
 * @group GetServiceCollectionTest
 * Add your own group annotations below this line
 */
class GetServiceCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const UNKNOWN_SERVICE_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @var int
     */
    protected const NUMBER_OF_SERVICES = 5;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @var list<\Generated\Shared\Transfer\ServiceTransfer>
     */
    protected array $serviceTransfers;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
        $this->serviceTransfers = $this->createDummyServiceTransfers();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyServiceCollection(): void
    {
        // Arrange
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addUuid(static::UNKNOWN_SERVICE_UUID);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            0,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByUuids(): void
    {
        // Arrange
        $serviceTransfer = $this->serviceTransfers[0];

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addUuid($serviceTransfer->getUuidOrFail());

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByUuidsInversed(): void
    {
        // Arrange
        $serviceTransferToExclude = $this->serviceTransfers[0];
        $serviceTransferExpected = $this->serviceTransfers[1];

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addUuid($serviceTransferToExclude->getUuidOrFail())
            ->setIsUuidsConditionInversed(true);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            4,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransferExpected->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByIds(): void
    {
        // Arrange
        $serviceTransfer = $this->serviceTransfers[0];

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addIdService($serviceTransfer->getIdServiceOrFail());

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getIdServiceOrFail(),
            $serviceCollectionTransfer->getServices()
                ->getIterator()
                ->current()
                ->getIdServiceOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByKeys(): void
    {
        // Arrange
        $serviceTransfer = $this->serviceTransfers[0];

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addKey($serviceTransfer->getKeyOrFail());

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getKeyOrFail(),
            $serviceCollectionTransfer->getServices()
                ->getIterator()
                ->current()
                ->getKeyOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByServicePointUuids(): void
    {
        // Arrange
        $serviceTransfer = $this->serviceTransfers[0];
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addServicePointUuid($servicePointUuid);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByServiceTypeUuids(): void
    {
        // Arrange
        $serviceTransfer = $this->serviceTransfers[0];
        $serviceTypeUuid = $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail();
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addServiceTypeUuid($serviceTypeUuid);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionPaginatedByOffsetAndLimit(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit(2);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNotNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_SERVICES,
            $serviceCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionPaginatedByPage(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())->setPage(2)->setMaxPerPage(2);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNotNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_SERVICES,
            $serviceCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $sortTransfer = (new SortTransfer())
            ->setField(ServiceTransfer::ID_SERVICE)
            ->setIsAscending(true);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_SERVICES,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        foreach ($this->serviceTransfers as $offset => $serviceTransfer) {
            $this->assertSame(
                $serviceTransfer->getIdServiceOrFail(),
                $serviceCollectionTransfer->getServices()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdServiceOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $serviceTransfers = array_reverse($this->serviceTransfers);

        $sortTransfer = (new SortTransfer())
            ->setField(ServiceTransfer::ID_SERVICE)
            ->setIsAscending(false);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_SERVICES,
            $serviceCollectionTransfer->getServices(),
        );

        $this->assertNull($serviceCollectionTransfer->getPagination());

        foreach ($serviceTransfers as $offset => $serviceTransfer) {
            $this->assertSame(
                $serviceTransfer->getIdServiceOrFail(),
                $serviceCollectionTransfer->getServices()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdServiceOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionWithServicePointAndServiceTypeRelations(): void
    {
        // Arrange
        $serviceTransfer = $this->serviceTransfers[0];

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())->addIdService($serviceTransfer->getIdService());
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionTransfer->getServices());

        /** @var \Generated\Shared\Transfer\ServiceTransfer $resultServiceTransfer */
        $resultServiceTransfer = $serviceCollectionTransfer->getServices()->getIterator()->current();

        $this->assertSame($serviceTransfer->getServiceType()->toArray(), $resultServiceTransfer->getServiceType()->toArray());

        $this->assertSame($serviceTransfer->getServicePoint()->toArray(), $resultServiceTransfer->getServicePoint()->toArray());
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByServicePointIds(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $this->tester->haveService();

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addIdServicePoint($serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail());

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionTransfer->getServices());
        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByIsActive(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => true,
        ]);
        $secondServiceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
        ]);

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->setServiceIds([
                $serviceTransfer->getIdService(),
                $secondServiceTransfer->getIdService(),
            ])
            ->setIsActive(true);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionTransfer->getServices());
        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionByIsServicePointActive(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE, [
            ServicePointTransfer::IS_ACTIVE => true,
        ]);
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE, [
            ServicePointTransfer::IS_ACTIVE => false,
        ]);
        $secondServiceTransfer = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->setServiceIds([
                $serviceTransfer->getIdService(),
                $secondServiceTransfer->getIdService(),
            ])
            ->setIsActiveServicePoint(true);

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionTransfer->getServices());
        $this->assertNull($serviceCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTransfer->getUuidOrFail(),
            $serviceCollectionTransfer
                ->getServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceCollectionWithServicePointRelation(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE)->toArray(),
        ]);

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addIdService($serviceTransfer->getIdService())
            ->setWithServicePointRelations(true);
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions($serviceConditionsTransfer);

        // Act
        $serviceCollectionTransfer = $this->tester->getFacade()
            ->getServiceCollection($serviceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionTransfer->getServices());

        $servicePointTransfer = $serviceTransfer->getServicePoint();

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $resultServicePointTransfer */
        $resultServicePointTransfer = $serviceCollectionTransfer->getServices()->getIterator()->current()->getServicePoint();

        $this->assertSame($servicePointTransfer->getUuid(), $resultServicePointTransfer->getUuid());
        $this->assertSame($servicePointTransfer->getIdServicePoint(), $resultServicePointTransfer->getIdServicePoint());
        $this->assertSame($servicePointTransfer->getKey(), $resultServicePointTransfer->getKey());
        $this->assertSame($servicePointTransfer->getName(), $resultServicePointTransfer->getName());

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $servicePointTransfer->getStoreRelation()->getStores()->getIterator()->current();

        /** @var \Generated\Shared\Transfer\StoreTransfer $resultStoreTransfer */
        $resultStoreTransfer = $resultServicePointTransfer->getStoreRelation()->getStores()->getIterator()->current();

        $this->assertSame($storeTransfer->getIdStore(), $resultStoreTransfer->getIdStore());
    }

    /**
     * @return list<\Generated\Shared\Transfer\ServiceTransfer>
     */
    protected function createDummyServiceTransfers(): array
    {
        $serviceTransfers = [];
        for ($i = 0; $i < static::NUMBER_OF_SERVICES; $i++) {
            $serviceTransfers[] = $this->tester->haveService();
        }

        return $serviceTransfers;
    }
}
