<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Sales\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Spryker\Zed\Sales\Business\SalesFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Sales
 * @group Business
 * @group SalesFacadeTest
 */
class SalesFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderShouldReturnOrderTransferWithOrderDataAndTotals()
    {
        $testOrderCreator = $this->createTestOrderCreator();
        $salesOrderEntity = $testOrderCreator->create();

        $salesFacade = $this->createSalesFacade();

        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
        $this->assertInstanceOf(TotalsTransfer::class, $orderTransfer->getTotals());
        $this->assertCount(2, $orderTransfer->getItems());

        $itemTransfer = $orderTransfer->getItems()[0];
        $this->assertEquals(TestOrderCreator::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertEquals(TestOrderCreator::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $itemTransfer = $orderTransfer->getItems()[1];
        $this->assertEquals(TestOrderCreator::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertEquals(TestOrderCreator::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getBillingAddress());
        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getShippingAddress());
        $this->assertCount(1, $orderTransfer->getExpenses());
    }

    /**
     * @return void
     */
    public function testCustomerOrderShouldReturnListOfCustomerPlacedOrders()
    {
        $testOrderCreator = $this->createTestOrderCreator();
        $salesOrderEntity = $testOrderCreator->create();

        $salesFacade = $this->createSalesFacade();

        $orderListTransfer = new OrderListTransfer();

        $orderListTransfer = $salesFacade->getCustomerOrders($orderListTransfer, $salesOrderEntity->getFkCustomer());

        $this->assertInstanceOf(OrderListTransfer::class, $orderListTransfer);
    }

    /**
     * @return void
     */
    public function testCustomerOrderShouldReturnGrandTotalWithDiscounts()
    {
        $testOrderCreator = $this->createTestOrderCreator();
        $salesOrderEntity = $testOrderCreator->create();

        $orderItemEntity = $salesOrderEntity->getItems()[0];

        $orderItemDiscountEntity = new SpySalesDiscount();
        $orderItemDiscountEntity->setAmount(50);
        $orderItemDiscountEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $orderItemDiscountEntity->setFkSalesOrderItem($orderItemEntity->getIdSalesOrderItem());
        $orderItemDiscountEntity->setName('Discount order saver tester');
        $orderItemDiscountEntity->setDisplayName('discount');
        $orderItemDiscountEntity->setDescription('Description');
        $orderItemDiscountEntity->save();

        $salesFacade = $this->createSalesFacade();
        $orderListTransfer = new OrderListTransfer();
        $orderListTransfer = $salesFacade->getCustomerOrders($orderListTransfer, $salesOrderEntity->getFkCustomer());

        $orderTransfer = $orderListTransfer->getOrders()[0];
        $grandTotal = $orderTransfer->getTotals()->getGrandTotal();

        $this->assertSame(1350, $grandTotal);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected function createSalesFacade()
    {
        return new SalesFacade();
    }

    /**
     * @return \Functional\Spryker\Zed\Sales\Business\TestOrderCreator
     */
    protected function createTestOrderCreator()
    {
        return new TestOrderCreator();
    }

}
