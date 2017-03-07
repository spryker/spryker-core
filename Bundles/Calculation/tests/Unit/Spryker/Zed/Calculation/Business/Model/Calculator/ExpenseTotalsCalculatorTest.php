<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group ExpenseTotalsCalculatorTest
 */
class ExpenseTotalsCalculatorTest extends PHPUnit_Framework_TestCase
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

        $this->assertEquals($expectedTotalExpenseAmount, $quoteTransfer->getTotals()->getExpenseTotal());
    }

    /**
     * @return void
     */
    public function testShouldThrowAssertionExceptionWhenTotalsNotPresent()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $expenseTotalsCalculator = $this->createExpenseTotalsCalculator();
        $quoteTransfer = $this->createQuoteTransfer();
        $expenseTotalsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowAssertionExceptionWhenExpenseSumGrossPriceNotPresent()
    {
        $this->expectException(RequiredTransferPropertyException::class);

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
