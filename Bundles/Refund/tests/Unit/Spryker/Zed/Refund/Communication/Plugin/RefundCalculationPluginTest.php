<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Refund\Communication\Plugin;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPlugin;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Communication
 * @group RefundCalculationPlugin
 */
class RefundCalculationPluginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateRefundForOrderWithoutAlreadyRefundedItems()
    {
        $refundCalculationPlugin = new RefundCalculatorPlugin;
        $orderTransfer = $this->getOrderTransferWithoutRefundedItems();
        $salesOrderItems = [
            $this->getSalesOrderItemOne()
        ];

        $refundTransfer = new RefundTransfer();
        $refundCalculationPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        $this->assertSame(100, $refundTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateRefundShouldIncludeExpenseInCalculationWhenLastItemOfOrderShouldBeRefunded()
    {
        $refundCalculationPlugin = new RefundCalculatorPlugin;
        $orderTransfer = $this->getOrderTransferWithRefundedItem();
        $salesOrderItems = [
            $this->getSalesOrderItemTwo()
        ];

        $refundTransfer = new RefundTransfer();
        $refundCalculationPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        $this->assertSame(110, $refundTransfer->getAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransferWithoutRefundedItems()
    {
        $orderTransfer = new OrderTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRefundableAmount(100);
        $itemTransfer->setIdSalesOrderItem(1);
        $orderTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRefundableAmount(100);
        $itemTransfer->setIdSalesOrderItem(2);
        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransferWithRefundedItem()
    {
        $orderTransfer = new OrderTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRefundableAmount(0);
        $itemTransfer->setIdSalesOrderItem(1);
        $orderTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRefundableAmount(100);
        $itemTransfer->setIdSalesOrderItem(2);
        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setRefundableAmount(10);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemOne()
    {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->setIdSalesOrderItem(1);

        return $salesOrderItem;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemTwo()
    {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->setIdSalesOrderItem(2);

        return $salesOrderItem;
    }

}
