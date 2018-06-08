<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
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
    const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    const DEFAULT_ITEM_STATE = 'test';

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
     * @return void
     */
    public function testTransformItemShouldSplitPerItem()
    {
        $quantity = 5;

        $itemTransfer = (new ItemTransfer())->setQuantity($quantity);
        $salesFacade = $this->createSalesFacade();
        $itemCollectionTransfer = $salesFacade->transformItem($itemTransfer);

        $this->assertSame($itemCollectionTransfer->getItems()->count(), 1);

        $this->assertSame($itemCollectionTransfer->getItems()->count(), $quantity);

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getQuantity(), 1);
        }
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade()
    {
        return $this->tester->getLocator()->sales()->facade();
    }
}
