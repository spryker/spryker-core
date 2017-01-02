<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group RemoveTotalsCalculatorTest
 */
class RemoveTotalsCalculatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptyExpenses()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getExpenseTotal());
    }

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptyDiscount()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getDiscountTotal());
    }

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptyGrandTotal()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getGrandTotal());
    }

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptySubTotal()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getSubtotal());
    }

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptyTaxTotal()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getTaxTotal()->getAmount());
        $this->assertEmpty($totalsTransfer->getTaxTotal()->getTaxRate());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator
     */
    protected function createRemoveTotalsCalculator()
    {
        return new RemoveTotalsCalculator();
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
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getCleanedTotals()
    {
        $expensesCalculator = $this->createRemoveTotalsCalculator();

        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setTotals($this->createTotalsTransfer());

        $expensesCalculator->recalculate($quoteTransfer);

        $totalsTransfer = $quoteTransfer->getTotals();

        return $totalsTransfer;
    }

}
