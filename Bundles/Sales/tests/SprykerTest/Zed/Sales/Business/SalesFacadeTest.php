<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
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
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const DEFAULT_ITEM_STATE = 'test';

    protected const ORDER_SEARCH_PARAMS = [
        'orderReference' => '123',
        'customerReference' => 'testing-customer',
    ];
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
    public function testGetOrderByIdSalesOrderShouldReturnOrderTransferWithOrderDataAndTotals()
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
        $this->assertEquals(static::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertEquals(static::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $itemTransfer = $orderTransfer->getItems()[1];
        $this->assertEquals(static::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertEquals(static::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getBillingAddress());
        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getShippingAddress());
        $this->assertCount(1, $orderTransfer->getExpenses());

        $this->assertSame(1, $orderTransfer->getTotalOrderCount());
    }

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderWhenGuestCustomerShouldNotCountOrders()
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
    public function testCustomerOrderShouldReturnListOfCustomerPlacedOrders()
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
        $orderEntity = $this->tester->create();

        $salesFacade = $this->createSalesFacade();

        $order = $salesFacade->getCustomerOrderByOrderReference(
            $this->createOrderTransferWithParams(static::ORDER_SEARCH_PARAMS)
        );

        $this->assertNotNull($order);
        $this->assertSame($orderEntity->getIdSalesOrder(), $order->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderByNonExistingOrderReference(): void
    {
        $salesFacade = $this->createSalesFacade();

        $order = $salesFacade->getCustomerOrderByOrderReference(
            $this->createOrderTransferWithParams(static::ORDER_WRONG_SEARCH_PARAMS)
        );

        $this->assertNull($order->getIdSalesOrder());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade()
    {
        return $this->tester->getLocator()->sales()->facade();
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransferWithParams(array $data): OrderTransfer
    {
        return (new OrderBuilder())->build()->fromArray($data);
    }
}
