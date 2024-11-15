<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group GetOrderCollectionTest
 * Add your own group annotations below this line
 */
class GetOrderCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSalesOrderTableIsEmpty();
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfOrders(): void
    {
        // Arrange
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $orderCriteriaTransfer = new OrderCriteriaTransfer();

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $this->assertCount(2, $orderCollectionTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfOrdersFilteredByIdSalesOrder(): void
    {
        // Arrange
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $orderConditionsTransfer = (new OrderConditionsTransfer())
            ->addIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setOrderConditions($orderConditionsTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $this->assertCount(1, $orderCollectionTransfer->getOrders());
        $this->assertSame(
            $orderTransfer->getOrderReferenceOrFail(),
            $orderCollectionTransfer->getOrders()->getIterator()->current()->getOrderReference(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfOrdersFilteredByOrderReference(): void
    {
        // Arrange
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $orderConditionsTransfer = (new OrderConditionsTransfer())
            ->addOrderReference($orderTransfer->getOrderReferenceOrFail());
        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setOrderConditions($orderConditionsTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $this->assertCount(1, $orderCollectionTransfer->getOrders());
        $this->assertSame(
            $orderTransfer->getOrderReferenceOrFail(),
            $orderCollectionTransfer->getOrders()->getIterator()->current()->getOrderReference(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfOrdersFilteredByCustomerReference(): void
    {
        // Arrange
        $customerReference = 'customer-reference';
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->haveOrder([
            OrderTransfer::CUSTOMER_REFERENCE => $customerReference,
        ], static::DEFAULT_OMS_PROCESS_NAME);

        $orderConditionsTransfer = (new OrderConditionsTransfer())
            ->addCustomerReference($customerReference);
        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setOrderConditions($orderConditionsTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $this->assertCount(1, $orderCollectionTransfer->getOrders());
        $this->assertSame(
            $orderTransfer->getOrderReferenceOrFail(),
            $orderCollectionTransfer->getOrders()->getIterator()->current()->getOrderReference(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnOrderCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => 'order-reference-1',
        ], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => 'order-reference-2',
        ], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => 'order-reference-3',
        ], static::DEFAULT_OMS_PROCESS_NAME);

        $sortTransfer = (new SortTransfer())
            ->setField(OrderTransfer::ORDER_REFERENCE)
            ->setIsAscending(true);

        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $orderTransfers = $orderCollectionTransfer->getOrders();
        $this->assertCount(3, $orderTransfers);
        $this->assertSame('order-reference-1', $orderTransfers->offsetGet(0)->getOrderReference());
        $this->assertSame('order-reference-2', $orderTransfers->offsetGet(1)->getOrderReference());
        $this->assertSame('order-reference-3', $orderTransfers->offsetGet(2)->getOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldReturnOrderCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => 'order-reference-1',
        ], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => 'order-reference-2',
        ], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => 'order-reference-3',
        ], static::DEFAULT_OMS_PROCESS_NAME);

        $sortTransfer = (new SortTransfer())
            ->setField(OrderTransfer::ORDER_REFERENCE)
            ->setIsAscending(false);

        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $orderTransfers = $orderCollectionTransfer->getOrders();
        $this->assertCount(3, $orderTransfers);
        $this->assertSame('order-reference-3', $orderTransfers->offsetGet(0)->getOrderReference());
        $this->assertSame('order-reference-2', $orderTransfers->offsetGet(1)->getOrderReference());
        $this->assertSame('order-reference-1', $orderTransfers->offsetGet(2)->getOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(3)
            ->setLimit(2);

        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $this->assertCount(2, $orderCollectionTransfer->getOrders());
        $this->assertNotNull($orderCollectionTransfer->getPagination());

        $paginationTransfer = $orderCollectionTransfer->getPaginationOrFail();
        $this->assertSame(5, $paginationTransfer->getNbResults());
    }

    /**
     * @return void
     */
    public function testShouldReturnSalesOrderAmendmentCollectionPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $orderCollectionTransfer = $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);

        // Assert
        $this->assertCount(2, $orderCollectionTransfer->getOrders());
        $this->assertNotNull($orderCollectionTransfer->getPagination());

        $paginationTransfer = $orderCollectionTransfer->getPaginationOrFail();
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
    public function testShouldExecuteOrderExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()],
        );

        $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $orderConditionsTransfer = (new OrderConditionsTransfer())->setWithOrderExpanderPlugins(true);
        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setOrderConditions($orderConditionsTransfer);

        // Act
        $this->tester->getFacade()->getOrderCollection($orderCriteriaTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface
     */
    protected function getOrderExpanderPluginMock(): OrderExpanderPluginInterface
    {
        $orderExpanderPluginMock = $this->getMockBuilder(OrderExpanderPluginInterface::class)->getMock();
        $orderExpanderPluginMock
            ->expects($this->once())
            ->method('hydrate')
            ->willReturnCallback(function (OrderTransfer $orderTransfer) {
                return $orderTransfer;
            });

        return $orderExpanderPluginMock;
    }
}
