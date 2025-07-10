<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentQuoteExpanderPluginInterface;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group GetSalesOrderAmendmentQuoteCollectionTest
 * Add your own group annotations below this line
 */
class GetSalesOrderAmendmentQuoteCollectionTest extends Unit
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

        $this->tester->ensureSalesOrderAmendmentQuoteTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollection(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();

        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer());

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionFilteredByIdSalesOrderAmendmentQuote(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();

        $salesOrderAmendmentQuoteConditions = (new SalesOrderAmendmentQuoteConditionsTransfer())
            ->addIdSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer->getIdSalesOrderAmendmentQuoteOrFail());
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setSalesOrderAmendmentQuoteConditions($salesOrderAmendmentQuoteConditions);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertSame(
            $salesOrderAmendmentQuoteTransfer->getUuidOrFail(),
            $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionFilteredByUuid(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();

        $salesOrderAmendmentQuoteConditions = (new SalesOrderAmendmentQuoteConditionsTransfer())
            ->addUuid($salesOrderAmendmentQuoteTransfer->getUuidOrFail());
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setSalesOrderAmendmentQuoteConditions($salesOrderAmendmentQuoteConditions);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertSame(
            $salesOrderAmendmentQuoteTransfer->getUuidOrFail(),
            $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionFilteredByStore(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();

        $salesOrderAmendmentQuoteConditions = (new SalesOrderAmendmentQuoteConditionsTransfer())
            ->addStoreName($salesOrderAmendmentQuoteTransfer->getStore());
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setSalesOrderAmendmentQuoteConditions($salesOrderAmendmentQuoteConditions);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertSame(
            $salesOrderAmendmentQuoteTransfer->getStore(),
            $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->getIterator()->current()->getStore(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionFilteredByCustomerReference(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();

        $salesOrderAmendmentQuoteConditions = (new SalesOrderAmendmentQuoteConditionsTransfer())
            ->addCustomerReference($salesOrderAmendmentQuoteTransfer->getCustomerReferenceOrFail());
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setSalesOrderAmendmentQuoteConditions($salesOrderAmendmentQuoteConditions);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertSame(
            $salesOrderAmendmentQuoteTransfer->getCustomerReference(),
            $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->getIterator()->current()->getCustomerReference(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionFilteredByAmendmentOrderReference(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();

        $salesOrderAmendmentQuoteConditions = (new SalesOrderAmendmentQuoteConditionsTransfer())
            ->addAmendmentOrderReference($salesOrderAmendmentQuoteTransfer->getAmendmentOrderReferenceOrFail());
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setSalesOrderAmendmentQuoteConditions($salesOrderAmendmentQuoteConditions);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertSame(
            $salesOrderAmendmentQuoteTransfer->getAmendmentOrderReference(),
            $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->getIterator()->current()->getAmendmentOrderReference(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionSortedByAmendmentOrderReferenceFieldAsc(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-1',
        ]);
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-2',
        ]);
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-3',
        ]);

        $sortTransfer = (new SortTransfer())
            ->setField(SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE)
            ->setIsAscending(true);

        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $salesOrderAmendmentQuotes = $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes();
        $this->assertCount(3, $salesOrderAmendmentQuotes);
        $this->assertSame('order-reference-1', $salesOrderAmendmentQuotes->offsetGet(0)->getAmendmentOrderReference());
        $this->assertSame('order-reference-2', $salesOrderAmendmentQuotes->offsetGet(1)->getAmendmentOrderReference());
        $this->assertSame('order-reference-3', $salesOrderAmendmentQuotes->offsetGet(2)->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionSortedByAmendmentOrderReferenceFieldDesc(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-1',
        ]);
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-2',
        ]);
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-3',
        ]);

        $sortTransfer = (new SortTransfer())
            ->setField(SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE)
            ->setIsAscending(false);

        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $salesOrderAmendmentQuotes = $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes();
        $this->assertCount(3, $salesOrderAmendmentQuotes);
        $this->assertSame('order-reference-3', $salesOrderAmendmentQuotes->offsetGet(0)->getAmendmentOrderReference());
        $this->assertSame('order-reference-2', $salesOrderAmendmentQuotes->offsetGet(1)->getAmendmentOrderReference());
        $this->assertSame('order-reference-1', $salesOrderAmendmentQuotes->offsetGet(2)->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(3)
            ->setLimit(2);

        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertNotNull($salesOrderAmendmentQuoteCollectionTransfer->getPagination());

        $paginationTransfer = $salesOrderAmendmentQuoteCollectionTransfer->getPaginationOrFail();
        $this->assertSame(5, $paginationTransfer->getNbResults());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentQuoteCollectionPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();
        $this->tester->haveSalesOrderAmendmentQuote();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionTransfer = $this->tester->getFacade()
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes());
        $this->assertNotNull($salesOrderAmendmentQuoteCollectionTransfer->getPagination());

        $paginationTransfer = $salesOrderAmendmentQuoteCollectionTransfer->getPaginationOrFail();
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
    public function testShouldExecuteSalesOrderAmendmentQuoteExpanderPluginStackWhenWithExpanderPluginsIsTrue(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_QUOTE_EXPANDER,
            [$this->createSalesOrderAmendmentQuoteExpanderPluginMock(true)],
        );
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setWithExpanderPlugins(true);

        // Act
        $this->tester->getFacade()->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotExecuteSalesOrderAmendmentQuoteExpanderPluginStackWhenWithExpanderPluginsIsFalse(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_QUOTE_EXPANDER,
            [$this->createSalesOrderAmendmentQuoteExpanderPluginMock(false)],
        );
        $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setWithExpanderPlugins(false);

        // Act
        $this->tester->getFacade()->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotExecuteSalesOrderAmendmentQuoteExpanderPluginStackWhenWithExpanderPluginsIsNull(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_QUOTE_EXPANDER,
            [$this->createSalesOrderAmendmentQuoteExpanderPluginMock(false)],
        );
        $this->tester->haveSalesOrderAmendmentQuote();

        // Act
        $this->tester->getFacade()->getSalesOrderAmendmentQuoteCollection(new SalesOrderAmendmentQuoteCriteriaTransfer());
    }

    /**
     * @param bool $shouldBeCalled
     *
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentQuoteExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentQuoteExpanderPluginMock(
        bool $shouldBeCalled
    ): SalesOrderAmendmentQuoteExpanderPluginInterface {
        $salesOrderAmendmentQuoteExpanderPluginMock = $this->getMockBuilder(SalesOrderAmendmentQuoteExpanderPluginInterface::class)
            ->getMock();
        $salesOrderAmendmentQuoteExpanderPluginMock->expects($shouldBeCalled ? $this->once() : $this->never())
            ->method('expand')
            ->with($this->isInstanceOf(SalesOrderAmendmentQuoteCollectionTransfer::class))
            ->willReturnArgument(0);

        return $salesOrderAmendmentQuoteExpanderPluginMock;
    }
}
