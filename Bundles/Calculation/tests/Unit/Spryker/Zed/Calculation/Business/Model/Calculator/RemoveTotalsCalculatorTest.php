<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;

class RemoveTotalsCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptyExpensesArrayObject()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getExpenses());
    }

    /**
     * @return void
     */
    public function testRemoveTotalsShouldSetEmptyDiscountArrayObject()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getDiscount());
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
    public function testRemoveTotalsShouldSetEmptyTaxTotalArrayObject()
    {
        $totalsTransfer = $this->getCleanedTotals();
        $this->assertEmpty($totalsTransfer->getTaxTotal());
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
