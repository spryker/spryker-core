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
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator
     */
    protected function createExpensesRemoveCalculator()
    {
        return new RemoveAllExpensesCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

}
