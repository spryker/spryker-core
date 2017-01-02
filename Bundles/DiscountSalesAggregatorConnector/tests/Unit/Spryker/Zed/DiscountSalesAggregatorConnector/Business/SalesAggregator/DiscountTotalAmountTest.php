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
use PHPUnit_Framework_TestCase;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\DiscountTotalAmount;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DiscountSalesAggregatorConnector
 * @group Business
 * @group SalesAggregator
 * @group DiscountTotalAmountTest
 */
class DiscountTotalAmountTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testDiscountCalculatedAmountShouldApplyTotalsFromItemAndExpenses()
    {
        $discountTotalsAggregator = $this->createDiscountTotalAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $discountTotalsAggregator->aggregate($orderTransfer);

        $this->assertSame(200, $orderTransfer->getTotals()->getDiscountTotal());
    }

    /**
     * @return void
     */
    public function testDiscountTotalAmountWhenDiscountIsBigerThanItemAmountShouldNotApplyMoreThatItemAmount()
    {
        $discountTotalsAggregator = $this->createDiscountTotalAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();

        $itemTransfer = $orderTransfer->getItems()[0];
        $itemTransfer->getCalculatedDiscounts()[0]->setSumGrossAmount(10000);

        $discountTotalsAggregator->aggregate($orderTransfer);

        $this->assertSame(500, $orderTransfer->getTotals()->getDiscountTotal());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(200);
        $itemTransfer->setSumGrossPrice(400);

        $calculatedDiscount = new CalculatedDiscountTransfer();
        $calculatedDiscount->setSumGrossAmount(100);
        $itemTransfer->addCalculatedDiscount($calculatedDiscount);

        $orderTransfer->addItem($itemTransfer);

        $totalsTransfer = new TotalsTransfer();
        $orderTransfer->setTotals($totalsTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setUnitGrossPrice(100);
        $expenseTransfer->setSumGrossPrice(200);

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
