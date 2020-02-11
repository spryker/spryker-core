<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Business\Model\RefundCalculator;

use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Refund\Business\Model\RefundCalculator\ExpenseRefundCalculator;
use Spryker\Zed\Refund\Business\Model\RefundCalculator\ItemRefundCalculator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Business
 * @group Model
 * @group RefundCalculator
 * @group ExpenseRefundCalculatorTest
 * Add your own group annotations below this line
 */
class ExpenseRefundCalculatorTest extends AbstractRefundCalculatorTest
{
    /**
     * @return void
     */
    public function testCalculateRefundForOrderWithoutAlreadyRefundedItemsShouldNotAddExpenses(): void
    {
        //Arrange
        $refundCalculationPlugin = new ExpenseRefundCalculator();
        $orderTransfer = $this->getOrderTransferWithoutRefundedItems();
        $salesOrderItems = [
            $this->getSalesOrderItemOne(),
        ];

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(0);

        //Act
        $refundCalculationPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        //Assert
        $this->assertSame(0, $refundTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateRefundShouldIncludeExpenseWhenLastItemOfOrderShouldBeRefunded(): void
    {
        //Arrange
        $refundCalculationPlugin = new ExpenseRefundCalculator();
        $orderTransfer = $this->getOrderTransferWithRefundedItem();
        $salesOrderItems = [
            $this->getSalesOrderItemTwo(),
        ];

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(0);

        //Act
        $refundCalculationPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        //Assert
        $this->assertSame(10, $refundTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateRefundShouldNotDuplicateItems(): void
    {
        //Arrange
        $refundCalculationPlugin = new ItemRefundCalculator();
        $orderTransfer = $this->getOrderTransferWithoutRefundedItems();
        $salesOrderItems = [
            $this->getSalesOrderItemOne(),
        ];

        $refundTransfer = $this->getRefundTransferWithAmountAndItem();

        //Act
        $refundTransfer = $refundCalculationPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        //Assert
        $this->assertSame(1, $refundTransfer->getItems()->count());
    }
}
