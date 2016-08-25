<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
 * @group Model
 * @group Calculator
 * @group RemoveAllCalculatedDiscountsCalculatorTest
 */
class RemoveAllCalculatedDiscountsCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRemoveCalculatedDiscountsShouldEmptyItemCalculatedDiscountCollections()
    {
        $removeAllCalculatedDiscountsCalculator = $this->createRemoveAllCalculatedDiscountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData();
        $removeAllCalculatedDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertEmpty($quoteTransfer->getItems()[0]->getCalculatedDiscounts());
    }

    /**
     * @return void
     */
    public function testRemoveCalculatedDiscountsShouldEmptyExpenseCalculatedDiscountCollections()
    {
        $removeAllCalculatedDiscountsCalculator = $this->createRemoveAllCalculatedDiscountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData();
        $removeAllCalculatedDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertEmpty($quoteTransfer->getExpenses()[0]->getCalculatedDiscounts());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $itemTransfer = $this->createItemTransfer();

        $calculatedDiscountTransfer = $this->createCalculatedDiscountTransfer();
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = $this->createExpenseTransfer();
        $expenseTransfer->addCalculatedDiscount(clone $calculatedDiscountTransfer);

        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator
     */
    protected function createRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createCalculatedDiscountTransfer()
    {
        return new CalculatedDiscountTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

}
