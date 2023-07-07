<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SalesFacadeTest
 * Add your own group annotations below this line
 */
class SalesFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const DEFAULT_ITEM_STATE = 'test';

    /**
     * @var string
     */
    protected const NON_EXISTING_ORDER_REFERENCE = 'test--111';

    /**
     * @var string
     */
    protected const COL_CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var string
     */
    protected const COL_CREATED_AT = 'created_at';

    /**
     * @var string
     */
    protected const COL_GRAND_TOTAL = 'grand_total';

    /**
     * @var array<string, string>
     */
    protected const ORDER_WRONG_SEARCH_PARAMS = [
        'orderReference' => '123_wrong',
        'customerReference' => 'testing-customer-wrong',
    ];

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderShouldReturnOrderTransferWithOrderDataAndTotals(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $productTransfer->getSku()]);

        $salesOrderEntity = $this->tester->create();

        $salesFacade = $this->createSalesFacade();

        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
        $this->assertInstanceOf(TotalsTransfer::class, $orderTransfer->getTotals());
        $this->assertCount(2, $orderTransfer->getItems());

        $itemTransfer = $orderTransfer->getItems()[0];
        $this->assertSame(static::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertSame(static::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $itemTransfer = $orderTransfer->getItems()[1];
        $this->assertSame(static::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertSame(static::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getBillingAddress());
        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getShippingAddress());
        $this->assertCount(1, $orderTransfer->getExpenses());

        $this->assertSame(1, $orderTransfer->getTotalOrderCount());
    }

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderWhenGuestCustomerShouldNotCountOrders(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $productTransfer->getSku()]);

        $salesOrderEntity = $this->tester->create();

        $salesOrderEntity->setCustomerReference(null);
        $salesOrderEntity->save();

        $salesFacade = $this->createSalesFacade();

        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $this->assertSame(0, $orderTransfer->getTotalOrderCount());
    }

    /**
     * @return void
     */
    public function testCustomerOrderShouldReturnListOfCustomerPlacedOrders(): void
    {
        $salesOrderEntity = $this->tester->create();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);

        $salesFacade = $this->createSalesFacade();

        $orderListTransfer = new OrderListTransfer();

        $orderListTransfer = $salesFacade->getCustomerOrders($orderListTransfer, $salesOrderEntity->getFkCustomer());

        $this->assertInstanceOf(OrderListTransfer::class, $orderListTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderByOrderReference(): void
    {
        //Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $salesFacade = $this->createSalesFacade();

        //Act
        $order = $salesFacade->getCustomerOrderByOrderReference(
            $this->createOrderTransferWithParams([
                OrderTransfer::ORDER_REFERENCE => $orderEntity->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
            ]),
        );

        //Assert
        $this->assertNotNull($order);
        $this->assertSame($orderEntity->getIdSalesOrder(), $order->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testGetOffsetPaginatedCustomerOrderList(): void
    {
        //Arrange
        $salesFacade = $this->createSalesFacade();
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $orderListRequestTransfer = $this->tester->createOrderListRequestTransfer([
            OrderListRequestTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
        ]);

        //Act
        $orderListTransfer = $salesFacade->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);

        //Assert
        $this->assertNotNull($orderListTransfer);
        $this->assertInstanceOf(OrderListTransfer::class, $orderListTransfer);
        $this->assertNotEmpty($orderListTransfer->getOrders());
        $this->assertSame($orderEntity->getOrderReference(), $orderListTransfer->getOrders()[0]->getOrderReference());
    }

    /**
     * @return void
     */
    public function testGetOffsetPaginatedCustomerOrderListByOrderReference(): void
    {
        //Arrange
        $salesFacade = $this->createSalesFacade();
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $orderListRequestTransfer = $this->tester->createOrderListRequestTransfer([
            OrderListRequestTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
            OrderListRequestTransfer::ORDER_REFERENCES => [$orderEntity->getOrderReference()],
        ]);

        //Act
        $orderListTransfer = $salesFacade->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);

        //Assert
        $this->assertNotNull($orderListTransfer);
        $this->assertInstanceOf(OrderListTransfer::class, $orderListTransfer);
        $this->assertNotEmpty($orderListTransfer->getOrders());
        $this->assertSame($orderEntity->getOrderReference(), $orderListTransfer->getOrders()[0]->getOrderReference());
    }

    /**
     * @return void
     */
    public function testGetOffsetPaginatedCustomerOrderListByNonExistingOrderReference(): void
    {
        //Arrange
        $salesFacade = $this->createSalesFacade();
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $orderListRequestTransfer = $this->tester->createOrderListRequestTransfer([
            OrderListRequestTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
            OrderListRequestTransfer::ORDER_REFERENCES => [static::NON_EXISTING_ORDER_REFERENCE],
        ]);

        //Act
        $orderListTransfer = $salesFacade->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);

        //Assert
        $this->assertNotNull($orderListTransfer);
        $this->assertInstanceOf(OrderListTransfer::class, $orderListTransfer);
        $this->assertEmpty($orderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderByNonExistingOrderReference(): void
    {
        $salesFacade = $this->createSalesFacade();

        $order = $salesFacade->getCustomerOrderByOrderReference(
            $this->createOrderTransferWithParams(static::ORDER_WRONG_SEARCH_PARAMS),
        );

        $this->assertNull($order->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testGetPaginatedCustomerOrdersReturnsOrdersExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
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
            ->getPaginatedCustomerOrders($orderListTransfer, $idCustomer)
            ->getOrders();

        // Assert
        $this->assertCount(1, $orderTransfers);
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfers->getIterator()->current()->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return void
     */
    public function testGetOffsetPaginatedCustomerOrderListReturnsOrdersExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $dateTime = new DateTime();
        $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 500,
        ]);
        $salesOrderTotalsLastEntity = $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 600,
        ]);

        $orderListRequestTransfer = $this->tester->createOrderListRequestTransfer([
            OrderListRequestTransfer::CUSTOMER_REFERENCE => $salesOrderEntity->getCustomerReference(),
        ]);

        // Act
        $orderTransfers = $this->tester->getFacade()
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer)
            ->getOrders();

        // Assert
        $this->assertCount(1, $orderTransfers);
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfers->getIterator()->current()->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerOrdersReturnsOrdersExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
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
            ->getCustomerOrders($orderListTransfer, $idCustomer)
            ->getOrders();

        // Assert
        $this->assertCount(1, $orderTransfers);
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfers->getIterator()->current()->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderByOrderReferenceReturnsOrderExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $dateTime = new DateTime();
        $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 500,
        ]);
        $salesOrderTotalsLastEntity = $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 600,
        ]);
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($salesOrderEntity->getOrderReference())
            ->setCustomerReference($salesOrderEntity->getCustomerReference());

        // Act
        $orderTransfer = $this->tester->getFacade()->getCustomerOrderByOrderReference($orderTransfer);

        // Assert
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfer->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return void
     */
    public function testFindOrderByIdSalesOrderReturnsOrderExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $dateTime = new DateTime();
        $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 500,
        ]);
        $salesOrderTotalsLastEntity = $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 600,
        ]);

        // Act
        $orderTransfer = $this->tester->getFacade()->findOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        // Assert
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfer->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return void
     */
    public function testGetOrderReturnsOrderExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $dateTime = new DateTime();
        $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 500,
        ]);
        $salesOrderTotalsLastEntity = $this->tester->createSalesOrderTotals($salesOrderEntity->getIdSalesOrder(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 600,
        ]);
        $orderFilterTransfer = (new OrderFilterTransfer())->setSalesOrderId($salesOrderEntity->getIdSalesOrder());

        // Act
        $orderTransfer = $this->tester->getFacade()->getOrder($orderFilterTransfer);

        // Assert
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderTransfer->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade(): SalesFacadeInterface
    {
        return $this->tester->getLocator()->sales()->facade();
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransferWithParams(array $data): OrderTransfer
    {
        return (new OrderBuilder())->build()->fromArray($data);
    }
}
