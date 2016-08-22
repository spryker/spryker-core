<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Tax\Business\Model\TaxCalculation;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group TaxCalculationTest
 */
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
        $expenseTransfer->setSumTaxAmount(30);
        $quoteTransfer->addExpense($expenseTransfer);

        $taxCalculation = $this->createTaxCalculation();
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertSame(55, $quoteTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
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
