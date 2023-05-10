<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointServiceConditionsTransfer;
use Generated\Shared\Transfer\ServicePointServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointServiceTransfer;
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
 * @group GetServicePointServiceCollectionTest
 * Add your own group annotations below this line
 */
class GetServicePointServiceCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const UNKNOWN_SERVICE_POINT_SERVICE_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @var int
     */
    protected const NUMBER_OF_SERVICE_POINT_SERVICES = 5;

    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @var list<\Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    protected array $servicePointServiceTransfers;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
        $this->servicePointServiceTransfers = $this->createDummyServicePointServiceTransfers();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyServicePointServiceCollection(): void
    {
        // Arrange
        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addUuid(static::UNKNOWN_SERVICE_POINT_SERVICE_UUID);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            0,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionByUuids(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->servicePointServiceTransfers[0];

        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addUuid($servicePointServiceTransfer->getUuidOrFail());

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            $servicePointServiceTransfer->getUuidOrFail(),
            $servicePointServiceCollectionTransfer
                ->getServicePointServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionByUuidsInversed(): void
    {
        // Arrange
        $servicePointServiceTransferToExclude = $this->servicePointServiceTransfers[0];
        $servicePointServiceTransferExpected = $this->servicePointServiceTransfers[1];

        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addUuid($servicePointServiceTransferToExclude->getUuidOrFail())
            ->setIsUuidsConditionInversed(true);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            4,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            $servicePointServiceTransferExpected->getUuidOrFail(),
            $servicePointServiceCollectionTransfer
                ->getServicePointServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionByIds(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->servicePointServiceTransfers[0];

        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addIdServicePointService($servicePointServiceTransfer->getIdServicePointServiceOrFail());

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            $servicePointServiceTransfer->getIdServicePointServiceOrFail(),
            $servicePointServiceCollectionTransfer->getServicePointServices()
                ->getIterator()
                ->current()
                ->getIdServicePointServiceOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionByKeys(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->servicePointServiceTransfers[0];

        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addKey($servicePointServiceTransfer->getKeyOrFail());

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            $servicePointServiceTransfer->getKeyOrFail(),
            $servicePointServiceCollectionTransfer->getServicePointServices()
                ->getIterator()
                ->current()
                ->getKeyOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionByServicePointUuids(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->servicePointServiceTransfers[0];
        $servicePointUuid = $servicePointServiceTransfer->getServicePointOrFail()->getUuidOrFail();
        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addServicePointUuid($servicePointUuid);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            $servicePointServiceTransfer->getUuidOrFail(),
            $servicePointServiceCollectionTransfer
                ->getServicePointServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionByServiceTypeUuids(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->servicePointServiceTransfers[0];
        $serviceTypeUuid = $servicePointServiceTransfer->getServiceTypeOrFail()->getUuidOrFail();
        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addServiceTypeUuid($serviceTypeUuid);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            $servicePointServiceTransfer->getUuidOrFail(),
            $servicePointServiceCollectionTransfer
                ->getServicePointServices()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionPaginatedByOffsetAndLimit(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit(2);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNotNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_SERVICE_POINT_SERVICES,
            $servicePointServiceCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionPaginatedByPage(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())->setPage(2)->setMaxPerPage(2);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNotNull($servicePointServiceCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_SERVICE_POINT_SERVICES,
            $servicePointServiceCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointServiceTransfer::ID_SERVICE_POINT_SERVICE)
            ->setIsAscending(true);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_SERVICE_POINT_SERVICES,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        foreach ($this->servicePointServiceTransfers as $offset => $servicePointServiceTransfer) {
            $this->assertSame(
                $servicePointServiceTransfer->getIdServicePointServiceOrFail(),
                $servicePointServiceCollectionTransfer->getServicePointServices()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdServicePointServiceOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnServicePointServiceCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $servicePointServiceTransfers = array_reverse($this->servicePointServiceTransfers);

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointServiceTransfer::ID_SERVICE_POINT_SERVICE)
            ->setIsAscending(false);

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $servicePointServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_SERVICE_POINT_SERVICES,
            $servicePointServiceCollectionTransfer->getServicePointServices(),
        );

        $this->assertNull($servicePointServiceCollectionTransfer->getPagination());

        foreach ($servicePointServiceTransfers as $offset => $servicePointServiceTransfer) {
            $this->assertSame(
                $servicePointServiceTransfer->getIdServicePointServiceOrFail(),
                $servicePointServiceCollectionTransfer->getServicePointServices()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdServicePointServiceOrFail(),
            );
        }
    }

    /**
     * @return list<\Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    protected function createDummyServicePointServiceTransfers(): array
    {
        $servicePointServiceTransfers = [];
        for ($i = 0; $i < static::NUMBER_OF_SERVICE_POINT_SERVICES; $i++) {
            $servicePointServiceTransfers[] = $this->tester->haveServicePointService();
        }

        return $servicePointServiceTransfers;
    }
}
