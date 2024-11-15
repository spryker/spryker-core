<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentExpanderPluginInterface;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group GetSalesOrderAmendmentCollectionTest
 * Add your own group annotations below this line
 */
class GetSalesOrderAmendmentCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([SalesOrderAmendmentBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderAmendmentTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollection(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();

        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer());

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionFilteredByIdSalesOrderAmendment(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();

        $salesOrderAmendmentConditionsTransfer = (new SalesOrderAmendmentConditionsTransfer())
            ->addIdSalesOrderAmendment($salesOrderAmendmentTransfer->getIdSalesOrderAmendmentOrFail());
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments());
        $this->assertSame(
            $salesOrderAmendmentTransfer->getUuidOrFail(),
            $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionFilteredByUuid(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();

        $salesOrderAmendmentConditionsTransfer = (new SalesOrderAmendmentConditionsTransfer())
            ->addUuid($salesOrderAmendmentTransfer->getUuidOrFail());
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments());
        $this->assertSame(
            $salesOrderAmendmentTransfer->getUuidOrFail(),
            $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionFilteredByAmendmentOrderReference(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();

        $salesOrderAmendmentConditionsTransfer = (new SalesOrderAmendmentConditionsTransfer())
            ->addAmendmentOrderReference($salesOrderAmendmentTransfer->getAmendmentOrderReferenceOrFail());
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments());
        $this->assertSame(
            $salesOrderAmendmentTransfer->getUuidOrFail(),
            $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment([
            SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-1',
        ]);
        $this->tester->createSalesOrderAmendment([
            SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-2',
        ]);
        $this->tester->createSalesOrderAmendment([
            SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-3',
        ]);

        $sortTransfer = (new SortTransfer())
            ->setField(SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE)
            ->setIsAscending(true);

        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $salesOrderAmendmentTransfers = $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments();
        $this->assertCount(3, $salesOrderAmendmentTransfers);
        $this->assertSame('order-reference-1', $salesOrderAmendmentTransfers->offsetGet(0)->getAmendmentOrderReference());
        $this->assertSame('order-reference-2', $salesOrderAmendmentTransfers->offsetGet(1)->getAmendmentOrderReference());
        $this->assertSame('order-reference-3', $salesOrderAmendmentTransfers->offsetGet(2)->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment([
            SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-1',
        ]);
        $this->tester->createSalesOrderAmendment([
            SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-2',
        ]);
        $this->tester->createSalesOrderAmendment([
            SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-3',
        ]);

        $sortTransfer = (new SortTransfer())
            ->setField(SalesOrderAmendmentTransfer::AMENDMENT_ORDER_REFERENCE)
            ->setIsAscending(false);

        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $salesOrderAmendmentTransfers = $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments();
        $this->assertCount(3, $salesOrderAmendmentTransfers);
        $this->assertSame('order-reference-3', $salesOrderAmendmentTransfers->offsetGet(0)->getAmendmentOrderReference());
        $this->assertSame('order-reference-2', $salesOrderAmendmentTransfers->offsetGet(1)->getAmendmentOrderReference());
        $this->assertSame('order-reference-1', $salesOrderAmendmentTransfers->offsetGet(2)->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(3)
            ->setLimit(2);

        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments());
        $this->assertNotNull($salesOrderAmendmentCollectionTransfer->getPagination());

        $paginationTransfer = $salesOrderAmendmentCollectionTransfer->getPaginationOrFail();
        $this->assertSame(5, $paginationTransfer->getNbResults());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();
        $this->tester->createSalesOrderAmendment();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $salesOrderAmendmentCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments());
        $this->assertNotNull($salesOrderAmendmentCollectionTransfer->getPagination());

        $paginationTransfer = $salesOrderAmendmentCollectionTransfer->getPaginationOrFail();
        $this->assertSame(5, $paginationTransfer->getNbResults());
        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(3, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(4, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(3, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(3, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_EXPANDER,
            [$this->createSalesOrderAmendmentExpanderPluginMock()],
        );

        $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer());

        // Act
        $this->tester->getFacade()->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentExpanderPluginMock(): SalesOrderAmendmentExpanderPluginInterface
    {
        $salesOrderAmendmentExpanderPluginMock = $this->getMockBuilder(SalesOrderAmendmentExpanderPluginInterface::class)
            ->getMock();

        $salesOrderAmendmentExpanderPluginMock
            ->expects($this->once())
            ->method('expand');

        return $salesOrderAmendmentExpanderPluginMock;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer $salesOrderAmendmentCollectionTransfer
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer|null
     */
    protected function findSalesOrderAmendmentInSalesOrderAmendmentCollection(
        SalesOrderAmendmentCollectionTransfer $salesOrderAmendmentCollectionTransfer,
        string $uuid
    ): ?SalesOrderAmendmentTransfer {
        foreach ($salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments() as $salesOrderAmendmentTransfer) {
            if ($salesOrderAmendmentTransfer->getUuid() === $uuid) {
                return $salesOrderAmendmentTransfer;
            }
        }

        return null;
    }
}
