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
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderDiscounts;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DiscountSalesAggregatorConnector
 * @group Business
 * @group SalesAggregator
 * @group OrderDiscountsTest
 */
class OrderDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testOrderDiscountsGrossSumShouldAggregateAmountsFromItemsAndExpenses()
    {
        $orderDiscountsAggregator = $this->createOrderDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderDiscountsAggregator->aggregate($orderTransfer);

        $this->assertSame(60, $orderTransfer->getCalculatedDiscounts()['test']->getSumGrossAmount());
    }

    /**
     * @return void
     */
    public function testOrderDiscountsGrossUnitShouldAggregateAmountsFromItemsAndExpenses()
    {
        $orderDiscountsAggregator = $this->createOrderDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderDiscountsAggregator->aggregate($orderTransfer);

        $this->assertSame(30, $orderTransfer->getCalculatedDiscounts()['test']->getUnitGrossAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $orderTransfer->setIdSalesOrder(1);

        $itemTransfer = new ItemTransfer();
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setDisplayName('test');
        $calculatedDiscountTransfer->setUnitGrossAmount(10);
        $calculatedDiscountTransfer->setSumGrossAmount(20);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        $itemTransfer->addCalculatedDiscount(clone $calculatedDiscountTransfer);

        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->addCalculatedDiscount(clone $calculatedDiscountTransfer);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderDiscounts
     */
    protected function createOrderDiscountsAggregator()
    {
        return new OrderDiscounts();
    }

}
