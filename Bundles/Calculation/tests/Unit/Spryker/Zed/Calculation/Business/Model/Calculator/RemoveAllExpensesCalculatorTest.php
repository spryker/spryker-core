<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator;

class RemoveAllExpensesCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testRemoveExpensesShouldSetEmptyArrayObject()
    {
        $expensesCalculator = $this->createExpensesRemoveCalculator();

        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->addExpense($this->createExpenseTransfer());

        $expensesCalculator->recalculate($quoteTransfer);

        $this->assertEmpty($quoteTransfer->getExpenses());
    }

    /**
     * @return RemoveAllExpensesCalculator
     */
    protected function createExpensesRemoveCalculator()
    {
        return new RemoveAllExpensesCalculator();
    }

    /**
     * @return ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }
}
