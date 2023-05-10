<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
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
 * @group GetServiceTypeCollectionTest
 * Add your own group annotations below this line
 */
class GetServiceTypeCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const UNKNOWN_SERVICE_TYPE_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @var int
     */
    protected const NUMBER_OF_SERVICE_TYPES = 5;

    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @var list<\Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    protected array $serviceTypeTransfers;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
        $this->serviceTypeTransfers = $this->createDummyServiceTypeTransfers();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyServiceTypeCollection(): void
    {
        // Arrange
        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addUuid(static::UNKNOWN_SERVICE_TYPE_UUID);

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            0,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionByUuids(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->serviceTypeTransfers[0];

        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addUuid($serviceTypeTransfer->getUuidOrFail());

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTypeTransfer->getUuidOrFail(),
            $serviceTypeCollectionTransfer
                ->getServiceTypes()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionByUuidsInversed(): void
    {
        // Arrange
        $serviceTypeTransferToExclude = $this->serviceTypeTransfers[0];
        $serviceTypeTransferExpected = $this->serviceTypeTransfers[1];

        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addUuid($serviceTypeTransferToExclude->getUuidOrFail())
            ->setIsUuidsConditionInversed(true);

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            4,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTypeTransferExpected->getUuidOrFail(),
            $serviceTypeCollectionTransfer
                ->getServiceTypes()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionByIds(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->serviceTypeTransfers[0];

        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addIdServiceType($serviceTypeTransfer->getIdServiceTypeOrFail());

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTypeTransfer->getIdServiceTypeOrFail(),
            $serviceTypeCollectionTransfer->getServiceTypes()
                ->getIterator()
                ->current()
                ->getIdServiceTypeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionByNames(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->serviceTypeTransfers[0];

        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addName($serviceTypeTransfer->getNameOrFail());

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTypeTransfer->getNameOrFail(),
            $serviceTypeCollectionTransfer
                ->getServiceTypes()
                ->getIterator()
                ->current()
                ->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionByKeys(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->serviceTypeTransfers[0];

        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addKey($serviceTypeTransfer->getKeyOrFail());

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            $serviceTypeTransfer->getKeyOrFail(),
            $serviceTypeCollectionTransfer
                ->getServiceTypes()
                ->getIterator()
                ->current()
                ->getKeyOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionPaginatedByOffsetAndLimit(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit(2);

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNotNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_SERVICE_TYPES,
            $serviceTypeCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionPaginatedByPage(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())->setPage(2)->setMaxPerPage(2);

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNotNull($serviceTypeCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_SERVICE_TYPES,
            $serviceTypeCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $sortTransfer = (new SortTransfer())
            ->setField(ServiceTypeTransfer::ID_SERVICE_TYPE)
            ->setIsAscending(true);

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_SERVICE_TYPES,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        foreach ($this->serviceTypeTransfers as $offset => $serviceTypeTransfer) {
            $this->assertSame(
                $serviceTypeTransfer->getIdServiceTypeOrFail(),
                $serviceTypeCollectionTransfer->getServiceTypes()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdServiceTypeOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnServiceTypeCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $serviceTypeTransfers = array_reverse($this->serviceTypeTransfers);

        $sortTransfer = (new SortTransfer())
            ->setField(ServiceTypeTransfer::ID_SERVICE_TYPE)
            ->setIsAscending(false);

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $serviceTypeCollectionTransfer = $this->tester
            ->getFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_SERVICE_TYPES,
            $serviceTypeCollectionTransfer->getServiceTypes(),
        );

        $this->assertNull($serviceTypeCollectionTransfer->getPagination());

        foreach ($serviceTypeTransfers as $offset => $serviceTypeTransfer) {
            $this->assertSame(
                $serviceTypeTransfer->getIdServiceTypeOrFail(),
                $serviceTypeCollectionTransfer->getServiceTypes()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdServiceTypeOrFail(),
            );
        }
    }

    /**
     * @return list<\Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    protected function createDummyServiceTypeTransfers(): array
    {
        $serviceTypeTransfers = [];
        for ($i = 0; $i < static::NUMBER_OF_SERVICE_TYPES; $i++) {
            $serviceTypeTransfers[] = $this->tester->haveServiceType();
        }

        return $serviceTypeTransfers;
    }
}
