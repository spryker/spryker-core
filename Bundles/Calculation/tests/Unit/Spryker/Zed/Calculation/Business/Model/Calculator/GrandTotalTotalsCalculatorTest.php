<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group GrandTotalTotalsCalculatorTest
 */
class GrandTotalTotalsCalculatorTest extends \PHPUnit_Framework_TestCase
{

    const SUB_TOTAL = 100;
    const EXPENSES_TOTAL = 20;

    /**
     * @return void
     */
    public function testGrandTotalCalculationShouldSumSubtotalAndExpenses()
    {
        $grandTotalTotalsCalculator = $this->createGrandTotalTotalsCalculator();
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::SUB_TOTAL, self::EXPENSES_TOTAL);
        $grandTotalTotalsCalculator->recalculate($quoteTransfer);

        $this->assertEquals(self::SUB_TOTAL + self::EXPENSES_TOTAL, $quoteTransfer->getTotals()->getGrandTotal());
    }

    /**
     * @return void
     */
    public function testCalculatorWhenTotalsNotPresetShouldThrowAssertException()
    {
        $this->setExpectedException(RequiredTransferPropertyException::class);

        $grandTotalTotalsCalculator = $this->createGrandTotalTotalsCalculator();
        $quoteTransfer = $this->createQuoteTransfer();
        $grandTotalTotalsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCalculatorWhenSubtTotalNotPresetShouldThrowAssertException()
    {
        $this->setExpectedException(RequiredTransferPropertyException::class);

        $grandTotalTotalsCalculator = $this->createGrandTotalTotalsCalculator();
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(null, self::EXPENSES_TOTAL);
        $grandTotalTotalsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @param int $subTotal
     * @param int $expensesTotal
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData($subTotal, $expensesTotal)
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $totalTransfer = $this->createTotalsTransfer();
        $totalTransfer->setSubtotal($subTotal);
        $totalTransfer->setExpenseTotal($expensesTotal);

        $quoteTransfer->setTotals($totalTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator
     */
    protected function createGrandTotalTotalsCalculator()
    {
        return new GrandTotalTotalsCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

}
