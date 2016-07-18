<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\DiscountTotalAmount;

class DiscountTotalAmountTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testDiscountCalculatedAmountShouldApplyTotalsFromItemAndExpenses()
    {
        $discountTotalsAggregator = $this->createDiscountTotalAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $discountTotalsAggregator->aggregate($orderTransfer);

        $this->assertEquals(200, $orderTransfer->getTotals()->getDiscountTotal());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $itemTransfer = new ItemTransfer();

        $calculatedDiscount = new CalculatedDiscountTransfer();
        $calculatedDiscount->setSumGrossAmount(100);
        $itemTransfer->addCalculatedDiscount($calculatedDiscount);

        $orderTransfer->addItem($itemTransfer);

        $totalsTransfer = new TotalsTransfer();
        $orderTransfer->setTotals($totalsTransfer);

        $expenseTransfer = new ExpenseTransfer();

        $calculatedDiscount = new CalculatedDiscountTransfer();
        $calculatedDiscount->setSumGrossAmount(100);
        $expenseTransfer->addCalculatedDiscount($calculatedDiscount);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\DiscountTotalAmount
     */
    protected function createDiscountTotalAmountAggregator()
    {
        return new DiscountTotalAmount();
    }

}
