<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Tax\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Tax\Business\Model\TaxCalculation;

class TaxCalculationTest extends \PHPUnit_Framework_TestCase
{

    const TAX_RATE = 19;
    const GRAND_TOTAL = 100;

    /**
     * @return void
     */
    public function testCalculateTaxAmountShouldSumUpAllTaxes()
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setTotals($this->createTotalsTransfer());
        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setSumTaxAmount(25);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = $this->createExpenseTransfer();
        $expenseTransfer->setSumTaxAmount(25);
        $quoteTransfer->addExpense($expenseTransfer);

        $taxCalculation = $this->createTaxCalculation();
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(50, $quoteTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     *
     * @return \Spryker\Zed\Tax\Business\Model\TaxCalculation
     */
    protected function createTaxCalculation()
    {
        return new TaxCalculation();
    }


    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOptionTransfer()
    {
        return new ProductOptionTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

}
