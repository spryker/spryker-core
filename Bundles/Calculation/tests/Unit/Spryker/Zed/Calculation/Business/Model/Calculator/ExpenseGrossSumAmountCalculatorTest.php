<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group ExpenseGrossSumAmountCalculatorTest
 */
class ExpenseGrossSumAmountCalculatorTest extends \PHPUnit_Framework_TestCase
{

    const UNIT_GROSS_PRICE = 100;
    const ITEM_QUANTITY = 2;

    /**
     * @return void
     */
    public function testGrossSumAboutShouldBeMultipliedWithQuantity()
    {
        $expenseGrossSumAmountCalculator = $this->createExpenseGrossSumAmountCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::UNIT_GROSS_PRICE, self::ITEM_QUANTITY);
        $expenseGrossSumAmountCalculator->recalculate($quoteTransfer);

        $this->assertEquals(
            self::UNIT_GROSS_PRICE * self::ITEM_QUANTITY,
            $quoteTransfer->getExpenses()[0]->getSumGrossPrice()
        );
    }

    /**
     * @return void
     */
    public function testCalculatorWhenUnitGrossPriceNotPresentShouldThrowAssertException()
    {
        $this->setExpectedException(RequiredTransferPropertyException::class);

        $expenseGrossSumAmountCalculator = $this->createExpenseGrossSumAmountCalculator();
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(null, self::ITEM_QUANTITY);
        $expenseGrossSumAmountCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCalculatorWhenItemQuantityIsNotPresentShouldThrowAssertException()
    {
        $this->setExpectedException(RequiredTransferPropertyException::class);

        $expenseGrossSumAmountCalculator = $this->createExpenseGrossSumAmountCalculator();
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::UNIT_GROSS_PRICE, null);
        $expenseGrossSumAmountCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator
     */
    protected function createExpenseGrossSumAmountCalculator()
    {
        return new ExpenseGrossSumAmountCalculator();
    }

    /**
     * @param int $unitGrossPrice
     * @param int $itemQuantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData($unitGrossPrice, $itemQuantity)
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $expenseTransfer = $this->createExpenseTransfer();
        $expenseTransfer->setUnitGrossPrice($unitGrossPrice);
        $expenseTransfer->setQuantity($itemQuantity);

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
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

}
