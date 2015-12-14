<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator;

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
        $this->setExpectedException('SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException');

        $expenseGrossSumAmountCalculator = $this->createExpenseGrossSumAmountCalculator();
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(null, self::ITEM_QUANTITY);
        $expenseGrossSumAmountCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCalculatorWhenItemQuantityIsNotPresentShouldThrowAssertException()
    {
        $this->setExpectedException('SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException');

        $expenseGrossSumAmountCalculator = $this->createExpenseGrossSumAmountCalculator();
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::UNIT_GROSS_PRICE, null);
        $expenseGrossSumAmountCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return ExpenseGrossSumAmountCalculator
     */
    protected function createExpenseGrossSumAmountCalculator()
    {
        return new ExpenseGrossSumAmountCalculator();
    }

    /**
     * @param int $unitGrossPrice
     * @param int $itemQuantity
     *
     * @return QuoteTransfer
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
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }


}
