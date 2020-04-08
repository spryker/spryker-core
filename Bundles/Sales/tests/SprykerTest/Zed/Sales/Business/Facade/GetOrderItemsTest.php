<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
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
 * @group GetOrderItemsTest
 * Add your own group annotations below this line
 */
class GetOrderItemsTest extends Test
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_ID_SALES_ORDER_ITEM = 6666;

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
    public function testGetOrderItemsRetrieveOrderItemsByOrderItemIds(): void
    {
        // Arrange
        $idSalesOrderItem = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME)
            ->getOrderItems()
            ->getIterator()
            ->current()
            ->getIdSalesOrderItem();

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($idSalesOrderItem);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $itemTransfers->getIterator()->current();

        // Assert
        $this->assertCount(1, $itemTransfers);
        $this->assertSame($idSalesOrderItem, $itemTransfer->getIdSalesOrderItem());
        $this->assertNotNull($itemTransfer->getState());
        $this->assertNotNull($itemTransfer->getProcess());
    }

    /**
     * @return void
     */
    public function testGetOrderItemsRetrieveOrderItemsByCustomerReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addCustomerReference($customerTransfer->getCustomerReference());

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        // Assert
        $this->assertCount(4, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testGetOrderItemsRetrieveOrderItemsByFilter(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addCustomerReference($customerTransfer->getCustomerReference())
            ->setFilter((new FilterTransfer())->setLimit(1));

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        // Assert
        $this->assertCount(1, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testGetOrderItemsCopyOrderReferenceToItems(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer
            ->getOrderItems()
            ->getIterator()
            ->current()
            ->getIdSalesOrderItem();

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($idSalesOrderItem);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $itemTransfers->getIterator()->current();

        // Assert
        $this->assertSame($saveOrderTransfer->getOrderReference(), $itemTransfer->getOrderReference());
    }

    /**
     * @return void
     */
    public function testGetOrderItemsRetrieveOrderItemsByFakeOrderItemId(): void
    {
        // Arrange
        $idSalesOrderItem = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME)
            ->getOrderItems()
            ->getIterator()
            ->current()
            ->getIdSalesOrderItem();

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($idSalesOrderItem)
            ->addSalesOrderItemId(static::FAKE_ID_SALES_ORDER_ITEM);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        // Assert
        $this->assertCount(1, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testGetOrderItemsRetrieveOrderItemsUsingExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEM_EXPANDER,
            [$this->getOrderItemExpanderPluginMock()]
        );

        $idSalesOrderItem = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME)
            ->getOrderItems()
            ->getIterator()
            ->current()
            ->getIdSalesOrderItem();

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($idSalesOrderItem);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        // Assert
        $this->assertSame(static::FAKE_ID_SALES_ORDER_ITEM, $itemTransfers->getIterator()->current()->getIdSalesOrderItem());
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
                    $itemTransfer->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM);
                }

                return $itemTransfers;
            });

        return $orderItemExpanderPluginMock;
    }
}
