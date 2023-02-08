<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use SprykerTest\Zed\WarehouseUser\WarehouseUserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WarehouseUser
 * @group Business
 * @group Facade
 * @group Facade
 * @group GetWarehouseUserAssignmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetWarehouseUserAssignmentCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\WarehouseUser\WarehouseUserBusinessTester
     */
    protected WarehouseUserBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureWarehouseUserAssignmentTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsByUuid(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->addUuid($warehouseUserAssignmentTransfer->getUuidOrFail());
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsById(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->addIdWarehouseUserAssignment($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsByUserUuid(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->addUserUuid($userTransfer->getUuidOrFail());
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsByWarehouseUuid(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->addWarehouseUuid($stockTransfer->getUuidOrFail());
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsByIsActiveStatus(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => true],
        );

        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->setIsActive(true);
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsSorted(): void
    {
        // Arrange
        $userTransfer1 = $this->tester->haveUser();
        $stockTransfer1 = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer1 = $this->tester->haveWarehouseUserAssignment(
            $userTransfer1,
            $stockTransfer1,
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => true,
            ],
        );

        $userTransfer2 = $this->tester->haveUser();
        $stockTransfer2 = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer2 = $this->tester->haveWarehouseUserAssignment(
            $userTransfer2,
            $stockTransfer2,
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => false,
            ],
        );

        $sortTransfer = (new SortTransfer())
            ->setField(WarehouseUserAssignmentTransfer::IS_ACTIVE)
            ->setIsAscending(true);
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());

        $warehouseUserAssignmentCollectionIterator = $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator();
        $this->tester->assertSameWarehouseUserAssignment($warehouseUserAssignmentTransfer2, $warehouseUserAssignmentCollectionIterator->current());
        $warehouseUserAssignmentCollectionIterator->next();
        $this->tester->assertSameWarehouseUserAssignment($warehouseUserAssignmentTransfer1, $warehouseUserAssignmentCollectionIterator->current());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $userTransfer1 = $this->tester->haveUser();
        $stockTransfer1 = $this->tester->haveStock();
        $this->tester->haveWarehouseUserAssignment(
            $userTransfer1,
            $stockTransfer1,
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => true,
            ],
        );

        $userTransfer2 = $this->tester->haveUser();
        $stockTransfer2 = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer2 = $this->tester->haveWarehouseUserAssignment(
            $userTransfer2,
            $stockTransfer2,
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => false,
            ],
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setLimit(1)
            ->setOffset(1);
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer2,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfWarehouseUserAssignmentsPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $userTransfer1 = $this->tester->haveUser();
        $stockTransfer1 = $this->tester->haveStock();
        $this->tester->haveWarehouseUserAssignment(
            $userTransfer1,
            $stockTransfer1,
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => true,
            ],
        );

        $userTransfer2 = $this->tester->haveUser();
        $stockTransfer2 = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer2 = $this->tester->haveWarehouseUserAssignment(
            $userTransfer2,
            $stockTransfer2,
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => false,
            ],
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(1);
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $warehouseUserAssignmentCollectionTransfer = $this->tester->getFacade()
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
        $this->tester->assertSameWarehouseUserAssignment(
            $warehouseUserAssignmentTransfer2,
            $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current(),
        );
    }
}
