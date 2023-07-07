<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group GetPaginatedCustomerOrdersOverviewTest
 * Add your own group annotations below this line
 */
class GetPaginatedCustomerOrdersOverviewTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var int
     */
    protected const TEST_NON_EXISTING_CUSTOMER_ID = -1;

    /**
     * @var string
     */
    protected const COL_CREATED_AT = 'created_at';

    /**
     * @var string
     */
    protected const COL_GRAND_TOTAL = 'grand_total';

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

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testGetPaginatedCustomerOrdersOverviewSupportsSearchOrderExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_SEARCH_ORDER_EXPANDER,
            [$this->getSearchOrderExpanderPluginMock()],
        );

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $filterTransfer = (new FilterTransfer())
            ->setLimit(5)
            ->setOffset(0)
            ->setOrderBy('created_at')
            ->setOrderDirection('DESC');

        $orderListTransfer = (new OrderListTransfer())
            ->setIdCustomer($orderTransfer->getCustomer()->getIdCustomer())
            ->setFilter($filterTransfer);

        // Act
        $orderTransfers = $this->tester->getFacade()
            ->getPaginatedCustomerOrdersOverview($orderListTransfer, $orderTransfer->getCustomer()->getIdCustomer())
            ->getOrders();

        // Assert
        $this->assertTrue($orderTransfers->getIterator()->current()->getIsCancellable());
    }

    /**
     * @return void
     */
    public function testGetPaginatedCustomerOrdersOverviewShouldNotThrowAnExceptionWhenCustomerIsNotFound(): void
    {
        // Arrange
        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $orderListTransfer = $this->tester->getFacade()
            ->getPaginatedCustomerOrdersOverview(new OrderListTransfer(), static::TEST_NON_EXISTING_CUSTOMER_ID);

        // Assert
        $this->assertCount(0, $orderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testReturnsOrdersExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $dateTime = new DateTime();
        $this->tester->createSalesOrderTotals($orderTransfer->getIdSalesOrderOrFail(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 500,
        ]);
        $salesOrderTotalsLastEntity = $this->tester->createSalesOrderTotals($orderTransfer->getIdSalesOrderOrFail(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 600,
        ]);

        $idCustomer = $orderTransfer->getCustomerOrFail()->getIdCustomerOrFail();
        $orderListTransfer = (new OrderListTransfer())->setIdCustomer($idCustomer);

        // Act
        $orderTransfers = $this->tester->getFacade()
            ->getPaginatedCustomerOrdersOverview($orderListTransfer, $idCustomer)
            ->getOrders();

        // Assert
        $this->assertCount(1, $orderTransfers);
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfers->getIterator()->current()->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface
     */
    protected function getSearchOrderExpanderPluginMock(): SearchOrderExpanderPluginInterface
    {
        $searchOrderExpanderPluginMock = $this
            ->getMockBuilder(SearchOrderExpanderPluginInterface::class)
            ->getMock();

        $searchOrderExpanderPluginMock
            ->method('expand')
            ->willReturnCallback(function (array $orderTransfers) {
                foreach ($orderTransfers as $orderTransfer) {
                    $orderTransfer->setIsCancellable(true);
                }

                return $orderTransfers;
            });

        return $searchOrderExpanderPluginMock;
    }
}
