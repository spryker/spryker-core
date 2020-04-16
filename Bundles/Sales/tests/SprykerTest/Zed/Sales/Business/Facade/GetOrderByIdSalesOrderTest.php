<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group GetOrderByIdSalesOrderTest
 * Add your own group annotations below this line
 */
class GetOrderByIdSalesOrderTest extends Test
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const SHIPPED_STATE_NAME = 'shipped';

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
    public function testGetOrderByIdSalesOrderSupportsItemExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEM_EXPANDER,
            [$this->getOrderItemExpanderPluginMock()]
        );

        $idSalesOrder = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME)->getIdSalesOrder();

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderByIdSalesOrder($idSalesOrder)
            ->getItems();

        // Assert
        $this->assertFalse($itemTransfers->getIterator()->current()->getIsReturnable());
    }

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderEnsureThatHistoryStateWasHydrated(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $this->tester->setItemState($orderTransfer->getItems()->getIterator()->current()->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->getItems();

        // Assert
        $this->assertCount(2, $itemTransfers->getIterator()->current()->getStateHistory());
    }

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderEnsureThatHistoryStateWasNotHydrated(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $this->tester->setItemState($orderTransfer->getItems()->getIterator()->current()->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        // Act
        $itemTransfers = $this->getFacadeMock()
            ->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->getItems();

        // Assert
        $this->assertEmpty($itemTransfers->getIterator()->current()->getStateHistory());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface
     */
    protected function getOrderItemExpanderPluginMock(): OrderItemExpanderPluginInterface
    {
        $orderItemExpanderPluginMock = $this
            ->getMockBuilder(OrderItemExpanderPluginInterface::class)
            ->getMock();

        $orderItemExpanderPluginMock
            ->method('expand')
            ->willReturnCallback(function (array $itemTransfers) {
                foreach ($itemTransfers as $itemTransfer) {
                    $itemTransfer->setIsReturnable(false);
                }

                return $itemTransfers;
            });

        return $orderItemExpanderPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getFacadeMock(): SalesFacadeInterface
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Sales\SalesConfig $salesConfigMock */
        $salesConfigMock = $this
            ->getMockBuilder(SalesConfig::class)
            ->getMock();

        $salesConfigMock
            ->method('isHydrateOrderHistoryToItems')
            ->willReturn(false);

        /** @var \Spryker\Zed\Sales\Business\SalesFacade $salesFacade */
        $salesFacade = $this->tester->getFacade();
        $salesFacade->setFactory((new SalesBusinessFactory())->setConfig($salesConfigMock));

        return $salesFacade;
    }
}
