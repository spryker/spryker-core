<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedBusiness;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;

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

    const DEFAULT_OMS_PROCESS_NAME = 'test';
    const DEFAULT_ITEM_STATE = 'test';

    /**
     * @var \Sales\ZedBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderShouldReturnOrderTransferWithOrderDataAndTotals()
    {
        $this->markTestSkipped();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock(['sku' => $productTransfer->getSku()]);
        $this->tester->haveState();

        $checkoutResponseTransfer = $this->tester->haveOrder();
//        echo '<pre>' . PHP_EOL . VarDumper::dump($checkoutResponseTransfer) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();

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
    }

    /**
     * @return void
     */
    public function testCustomerOrderShouldReturnListOfCustomerPlacedOrders()
    {
        $salesOrderEntity = $this->tester->create();

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
        $this->markTestSkipped();

        $salesOrderEntity = $this->tester->create();

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
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade()
    {
//        return new SalesFacade();
        return $this->tester->getLocator()->sales()->facade();
    }

}
