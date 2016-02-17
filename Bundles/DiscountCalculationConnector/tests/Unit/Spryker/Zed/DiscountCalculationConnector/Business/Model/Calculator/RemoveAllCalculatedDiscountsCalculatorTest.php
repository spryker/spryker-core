<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;

class RemoveAllCalculatedDiscountsCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRemoveCalculatedDiscountsShouldEmptyItemCalculatedDiscountCollections()
    {
        $removeAllCalculatedDiscountsCalculator = $this->createRemoveAllCalculatedDiscountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData();
        $removeAllCalculatedDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertEmpty($quoteTransfer->getItems()[0]->getCalculatedDiscounts());
    }

    /**
     * @return void
     */
    public function testRemoveCalculatedDiscountsShouldEmptyItemOptionsCalculatedDiscountCollections()
    {
        $removeAllCalculatedDiscountsCalculator = $this->createRemoveAllCalculatedDiscountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData();
        $removeAllCalculatedDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertEmpty($quoteTransfer->getItems()[0]->getProductOptions()[0]->getCalculatedDiscounts());
    }

    /**
     * @return void
     */
    public function testRemoveCalculatedDiscountsShouldEmptyExpenseCalculatedDiscountCollections()
    {
        $removeAllCalculatedDiscountsCalculator = $this->createRemoveAllCalculatedDiscountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData();
        $removeAllCalculatedDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertEmpty($quoteTransfer->getExpenses()[0]->getCalculatedDiscounts());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $itemTransfer = $this->createItemTransfer();

        $calculatedDiscountTransfer = $this->createCalculatedDiscountTransfer();
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $productOptionTransfer = $this->createProductOptionTransfer();
        $productOptionTransfer->addCalculatedDiscount(clone $calculatedDiscountTransfer);
        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = $this->createExpenseTransfer();
        $expenseTransfer->addCalculatedDiscount(clone $calculatedDiscountTransfer);

        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator
     */
    protected function createRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createCalculatedDiscountTransfer()
    {
        return new CalculatedDiscountTransfer();
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

}
