<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group SprykerFeature
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
 * @group DiscountTotalsCalculator
 */
class DiscountTotalsCalculatorTest extends Test
{

    const EXPENSE_1000 = 1000;
    const SALES_DISCOUNT_100 = 100;
    const SALES_DISCOUNT_50 = 50;
    const ITEM_GROSS_PRICE_1000 = 1000;

    public function testRecalculateTotalsMustSetDiscountWithZeroAmountIfNoDiscountWasApplied()
    {
        $calculableContainer = $this->getCalculableContainer();

        $itemTransfer = new ItemTransfer();
        $calculableContainer->getCalculableObject()->addItem($itemTransfer);

        $calculator = new DiscountTotalsCalculator();
        $totalsTransfer = new TotalsTransfer();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableContainer->getCalculableObject()->getItems());

        $this->assertEquals(0, $totalsTransfer->getDiscount()->getTotalAmount());
    }

    public function testRecalculateTotalsMustNotSetDiscountItemsToDiscountIfNoDiscountWasApplied()
    {
        $calculableContainer = $this->getCalculableContainer();

        $itemTransfer = new ItemTransfer();
        $calculableContainer->getCalculableObject()->addItem($itemTransfer);

        $calculator = new DiscountTotalsCalculator();
        $totalsTransfer = new TotalsTransfer();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableContainer->getCalculableObject()->getItems());

        $this->assertCount(0, $totalsTransfer->getDiscount()->getDiscountItems());
    }

    public function testDiscountShouldBeItemDiscountForOnlyDiscountedItems()
    {
        $calculableContainer = $this->getCalculableContainer();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::SALES_DISCOUNT_100);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $itemTransfer->setQuantity(1);

        $itemTransfer->addDiscount($discountTransfer);
        $calculableContainer->getCalculableObject()->addItem($itemTransfer);
        $totalsTransfer = new TotalsTransfer();

        $calculator = new DiscountTotalsCalculator();

        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableContainer->getCalculableObject()->getItems());

        $this->assertEquals(self::SALES_DISCOUNT_100, $totalsTransfer->getDiscount()->getTotalAmount());
        $this->assertCount(1, $totalsTransfer->getDiscount()->getDiscountItems());

        $this->assertEquals(self::SALES_DISCOUNT_100, $totalsTransfer->getDiscount()->getDiscountItems()[0]->getAmount());
    }

    public function testDiscountShouldBeItemDiscountAndExpenseDiscountForDiscountedItemsAndExpenses()
    {
        $calculableContainer = $this->getCalculableContainer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $itemTransfer->setQuantity(1);
        $expenseTransfer = new ExpenseTransfer();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::SALES_DISCOUNT_50);

        $expenseTransfer->setGrossPrice(self::EXPENSE_1000);
        $expenseTransfer->addDiscountItem($discountTransfer);

        $itemTransfer->addExpense($expenseTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::SALES_DISCOUNT_100);
        $itemTransfer->addDiscount($discountTransfer);
        $calculableContainer->getCalculableObject()->addItem($itemTransfer);

        $totalsTransfer = new TotalsTransfer();
        $calculator = new DiscountTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableContainer->getCalculableObject()->getItems());

        $this->assertEquals(
            self::SALES_DISCOUNT_50 + self::SALES_DISCOUNT_100,
            $totalsTransfer->getDiscount()->getTotalAmount()
        );

        $this->assertEquals(self::SALES_DISCOUNT_50 + self::SALES_DISCOUNT_100, $totalsTransfer->getDiscount()->getDiscountItems()[0]->getAmount());
    }

    /**
     * @return CalculableContainer
     */
    protected function getCalculableContainer()
    {
        return new CalculableContainer(new OrderTransfer());
    }

}
