<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface;

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
class GetPaginatedCustomerOrdersOverviewTest extends Test
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

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
            [$this->getSearchOrderExpanderPluginMock()]
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
        $orderTransfers = $this->tester
            ->getFacade()
            ->getPaginatedCustomerOrdersOverview($orderListTransfer, $orderTransfer->getCustomer()->getIdCustomer())
            ->getOrders();

        // Assert
        $this->assertTrue($orderTransfers->getIterator()->current()->getIsCancellable());
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
