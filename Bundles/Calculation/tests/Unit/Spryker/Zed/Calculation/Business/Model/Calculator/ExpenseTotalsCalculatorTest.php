<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;

class ExpenseTotalsCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateSumOfExpensesWhenMultipleExpensesProvided()
    {
        $expenseTotalsCalculator = $this->createExpenseTotalsCalculator();

        $fixtures = [
            [
                'unitPrice' => 100,
                'quantity' => 2,
                'sumGrossPrice' => 200,
            ],
            [
                'unitPrice' => 100,
                'quantity' => 2,
                'sumGrossPrice' => 200,
            ],
        ];

        $quoteTransfer = $this->createQuoteTransferWithFixtureData($fixtures);
        $expenseTotalsCalculator->recalculate($quoteTransfer);
        $expectedTotalExpenseAmount = array_reduce($fixtures, function ($carry, $item) {
            $carry += $item['sumGrossPrice'];

            return $carry;
        });

        $this->assertEquals($expectedTotalExpenseAmount, $quoteTransfer->getTotals()->getExpenses()->getTotalAmount());
    }

    /**
     * @return void
     */
    public function testShouldThrowAssertionExceptionWhenTotalsNotPresent()
    {
        $this->setExpectedException('SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException');

        $expenseTotalsCalculator = $this->createExpenseTotalsCalculator();
        $quoteTransfer = $this->createQuoteTransfer();
        $expenseTotalsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowAssertionExceptionWhenExpenseSumGrossPriceNotPresent()
    {
        $this->setExpectedException('SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException');

        $expenseTotalsCalculator = $this->createExpenseTotalsCalculator();

        $fixtures = [
            [
                'unitPrice' => 100,
                'quantity' => 2,
            ],
            [
                'unitPrice' => 100,
                'quantity' => 2,
            ],
        ];

        $quoteTransfer = $this->createQuoteTransferWithFixtureData($fixtures);
        $expenseTotalsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @param array $expenses
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData(array $expenses)
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setTotals($this->createTaxTotalTransfer());

        foreach ($expenses as $expense) {
            $expenseTransfer = $this->createExpenseTransfer();
            $expenseTransfer->setUnitGrossPrice($expense['unitPrice']);
            if (isset($expense['sumGrossPrice'])) {
                $expenseTransfer->setSumGrossPrice($expense['sumGrossPrice']);
            }
            $expenseTransfer->setQuantity($expense['quantity']);
            $quoteTransfer->addExpense($expenseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator
     */
    protected function createExpenseTotalsCalculator()
    {
        return new ExpenseTotalsCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTaxTotalTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

}
